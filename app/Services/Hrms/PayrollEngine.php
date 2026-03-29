<?php declare(strict_types=1);

namespace App\Services\Hrms;

use App\Repositories\Hrms\{PayrollRepository, AttendanceRepository, EmployeeRepository};
use App\Services\Tax\TDSService;
use App\Services\Erp\AccountingEngine;
use App\Services\Shared\{AuditService, PDFService, NotificationService};
use App\Core\{Database, Logger};

final class PayrollEngine
{
    // Maharashtra Professional Tax slab 2025
    private const PT_SLABS = [
        ['min' => 0,      'max' => 7500,  'monthly_pt' => 0],
        ['min' => 7501,   'max' => 10000, 'monthly_pt' => 175],
        ['min' => 10001,  'max' => PHP_INT_MAX, 'monthly_pt' => 200],
    ];

    public function __construct(
        private readonly PayrollRepository    $payrollRepo,
        private readonly AttendanceRepository $attendanceRepo,
        private readonly EmployeeRepository   $employeeRepo,
        private readonly TDSService           $tdsService,
        private readonly AccountingEngine     $accounting,
        private readonly AuditService         $audit,
        private readonly PDFService           $pdfService,
        private readonly NotificationService  $notifications,
        private readonly Database             $db,
        private readonly Logger               $logger
    ) {}

    public function processMonthlyPayroll(int $month, int $year, int $processedBy): array
    {
        if ($this->payrollRepo->findByPeriod($month, $year)) {
            throw new \RuntimeException("Payroll for {$month}/{$year} already exists");
        }

        $runId = $this->payrollRepo->createRun([
            'payroll_month'  => $month,
            'payroll_year'   => $year,
            'financial_year' => $this->getFY($month, $year),
            'status'         => 'processing',
            'processed_by'   => $processedBy,
        ]);

        $employees = $this->employeeRepo->getActiveWithCurrentSalary();
        $totals    = [
            'gross' => '0', 'deductions' => '0', 'net' => '0',
            'pf_employer' => '0', 'esic_employer' => '0', 'tds' => '0', 'count' => 0
        ];

        foreach ($employees as $emp) {
            try {
                $entry = $this->calculateForEmployee($emp, $month, $year);
                $entry['payroll_run_id'] = $runId;
                
                $this->payrollRepo->createEntry($entry);
                
                // Using bcmath for precision
                $totals['gross']         = bcadd($totals['gross'], $entry['gross_earnings'], 2);
                $totals['deductions']    = bcadd($totals['deductions'], $entry['total_deductions'], 2);
                $totals['net']           = bcadd($totals['net'], $entry['net_pay'], 2);
                $totals['pf_employer']   = bcadd($totals['pf_employer'], $entry['pf_employer'], 2);
                $totals['esic_employer'] = bcadd($totals['esic_employer'], $entry['esic_employer'], 2);
                $totals['tds']           = bcadd($totals['tds'], $entry['tds'], 2);
                $totals['count']++;
            } catch (\Throwable $e) {
                $this->logger->error("Payroll error emp#{$emp['id']}: " . $e->getMessage());
            }
        }

        $this->payrollRepo->updateRun($runId, [
            'status'              => 'processed',
            'total_employees'     => $totals['count'],
            'total_gross'         => $totals['gross'],
            'total_deductions'    => $totals['deductions'],
            'total_net'           => $totals['net'],
            'total_employer_pf'   => $totals['pf_employer'],
            'total_employer_esic' => $totals['esic_employer'],
            'total_tds'           => $totals['tds'],
        ]);

        // Create journal entry: Dr Salary Expense / Cr Salary Payable + Liabilities
        $this->accounting->createJournalEntry([
            'entry_type'     => 'payroll',
            'entry_date'     => date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)),
            'financial_year' => $this->getFY($month, $year),
            'reference_type' => 'payroll_run',
            'reference_id'   => $runId,
            'narration'      => 'Salary for ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)),
            'lines'          => [
                ['account_code' => '6001', 'debit' => bcadd($totals['gross'], bcadd($totals['pf_employer'], $totals['esic_employer'], 2), 2), 'credit' => '0', 'narration' => 'Salary + Employer PF + ESIC'],
                ['account_code' => '2101', 'debit' => '0', 'credit' => $totals['pf_employer'],   'narration' => 'PF Payable'],
                ['account_code' => '2102', 'debit' => '0', 'credit' => $totals['esic_employer'], 'narration' => 'ESIC Payable'],
                ['account_code' => '2103', 'debit' => '0', 'credit' => $totals['tds'],           'narration' => 'TDS Payable'],
                ['account_code' => '2001', 'debit' => '0', 'credit' => $totals['net'],           'narration' => 'Salary Payable'],
            ],
            'created_by' => $processedBy,
        ]);

        $this->audit->log('payroll.processed', 'payroll_runs', $runId, [], $totals, $processedBy);
        
        return $this->payrollRepo->findRun($runId);
    }

    private function calculateForEmployee(array $emp, int $month, int $year): array
    {
        $salary        = $emp['current_salary'];
        $structure     = $salary['structure'];
        $ctcAnnual     = $salary['ctc_annual'];
        $totalWorkDays = $this->workingDaysInMonth($month, $year);
        $attendance    = $this->attendanceRepo->getMonthlySummary($emp['id'], $month, $year);
        
        $daysPresent = bcadd((string)$attendance['days_present'], (string)($attendance['paid_leaves'] ?? 0), 2);
        $lopDays     = max(0, bcsub((string)$totalWorkDays, $daysPresent, 2));
        $lopFactor   = bcdiv($daysPresent, (string)$totalWorkDays, 6);

        // Component calculations (all bcmath)
        $basicMonthly  = bcdiv(bcmul($ctcAnnual, bcdiv((string)$structure['basic_pct'], '100', 6), 2), '12', 2);
        $basic         = bcmul($basicMonthly, $lopFactor, 2);
        $hra           = bcmul($basic, bcdiv((string)$structure['hra_pct'], '100', 6), 2);
        $conveyance    = bcmul((string)$structure['conveyance_fixed'], $lopFactor, 2);
        $medical       = bcmul((string)$structure['medical_fixed'], $lopFactor, 2);
        
        $grossMonthly  = bcdiv(bcmul($ctcAnnual, bcdiv('100', '100', 6), 2), '12', 2); // simplified
        $special       = bcsub(bcmul($grossMonthly, $lopFactor, 2), bcadd($basic, bcadd($hra, bcadd($conveyance, $medical, 2), 2), 2), 2);
        $grossEarnings = bcadd($basic, bcadd($hra, bcadd($conveyance, bcadd($medical, $special, 2), 2), 2), 2);

        // Statutory deductions
        $pfCap      = '1800.00'; // PF capped at ₹1800/month (₹15000 basic × 12%)
        $pfEmployee = bccomp($basic, '15000', 2) <= 0
                      ? bcmul($basic, bcdiv((string)$structure['pf_employee_pct'], '100', 6), 2)
                      : $pfCap;
        $pfEmployer = bccomp($basic, '15000', 2) <= 0
                      ? bcmul($basic, bcdiv((string)$structure['pf_employer_pct'], '100', 6), 2)
                      : $pfCap;

        // ESIC: applicable if gross ≤ ₹21,000
        $esicEmployee = bccomp($grossEarnings, '21000', 2) <= 0
                        ? bcmul($grossEarnings, bcdiv((string)$structure['esic_employee_pct'], '100', 6), 2)
                        : '0';
        $esicEmployer = bccomp($grossEarnings, '21000', 2) <= 0
                        ? bcmul($grossEarnings, bcdiv((string)$structure['esic_employer_pct'], '100', 6), 2)
                        : '0';

        // PT: Maharashtra slab
        $pt = (string)$this->getProfessionalTax((float)$grossEarnings);

        // TDS
        $tds = $structure['tds_applicable']
               ? (string)$this->tdsService->calculateMonthlyTDS($emp['id'], bcmul($grossEarnings, '12', 2), $month, $year)
               : '0';

        $totalDeductions = bcadd($pfEmployee, bcadd($esicEmployee, bcadd($pt, $tds, 2), 2), 2);
        $netPay          = bcsub($grossEarnings, $totalDeductions, 2);

        return [
            'total_working_days' => $totalWorkDays,
            'days_present'       => $daysPresent,
            'lop_days'           => $lopDays,
            'basic'              => $basic,
            'hra'                => $hra,
            'conveyance'         => $conveyance,
            'medical'            => $medical,
            'special_allowance'  => $special,
            'gross_earnings'     => $grossEarnings,
            'pf_employee'        => $pfEmployee,
            'pf_employer'        => $pfEmployer,
            'esic_employee'      => $esicEmployee,
            'esic_employer'      => $esicEmployer,
            'professional_tax'   => $pt,
            'tds'                => $tds,
            'total_deductions'   => $totalDeductions,
            'net_pay'            => $netPay,
            'payment_status'     => 'pending',
        ];
    }

    private function getProfessionalTax(float $gross): float
    {
        foreach (self::PT_SLABS as $slab) {
            if ($gross >= $slab['min'] && $gross <= $slab['max']) {
                return $slab['monthly_pt'];
            }
        }
        return 200.0; // Default Maharashtra PT
    }

    private function workingDaysInMonth(int $month, int $year): int
    {
        $days = 0;
        $total = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($d = 1; $d <= $total; $d++) {
            if ((int)date('N', mktime(0, 0, 0, $month, $d, $year)) < 7) {
                $days++;
            }
        }
        return $days;
    }

    private function getFY(int $month, int $year): string
    {
        return $month >= 4 ? "{$year}-" . ($year + 1) : ($year - 1) . "-{$year}";
    }
}