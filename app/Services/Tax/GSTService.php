<?php declare(strict_types=1);
namespace App\Services\Tax;
use App\Repositories\Tax\GSTRepository;
use App\Services\Shared\{ExcelService, CacheService};
final class GSTService
{
    // Real Estate Brokerage: SAC 998111, GST 18% (CGST 9% + SGST 9%)
    private const BROKERAGE_SAC = '998111';
    private const BROKERAGE_GST = 18.0;
    private const CGST_RATE = 9.0;
    private const SGST_RATE = 9.0;
    public function __construct(
        private readonly GSTRepository $gstRepo,
        private readonly ExcelService $excelService,
        private readonly CacheService $cache
    ) {
    }
    /**
     * Calculate GST on brokerage amount
     * Intra-state: CGST 9% + SGST 9%
     * Inter-state: IGST 18%
     */
    public function calculateOnBrokerage(
        string $brokerageAmount,
        string $partyState = 'Maharashtra'
    ): array {
        $isIntraState = ($partyState === 'Maharashtra');
        $taxable = $brokerageAmount;
        $gstTotal = bcmul($taxable, bcdiv((string) self::BROKERAGE_GST, '100', 6), 2);
        $cgst = $isIntraState ? bcmul($taxable, bcdiv((string) self::CGST_RATE, '100', 6), 2) : '0';
        $sgst = $isIntraState ? bcmul($taxable, bcdiv((string) self::SGST_RATE, '100', 6), 2) : '0';
        $igst = $isIntraState ? '0' : $gstTotal;
        return [
            'taxable_amount' => $taxable,
            'sac_code' => self::BROKERAGE_SAC,
            'cgst_rate' => $isIntraState ? self::CGST_RATE : 0,
            'sgst_rate' => $isIntraState ? self::SGST_RATE : 0,
            'igst_rate' => $isIntraState ? 0 : self::BROKERAGE_GST,
            'cgst_amount' => $cgst,
            'sgst_amount' => $sgst,
            'igst_amount' => $igst,
            'total_gst' => $gstTotal,
            'invoice_total' => bcadd($taxable, $gstTotal, 2),
        ];
    }
    /**
     * Aggregate GSTR-1 data for a filing period
     */
    public function generateGSTR1(int $month, int $year): array
    {
        $period = sprintf('%04d-%02d', $year, $month);
        $cacheKey = "gstr1:{$period}";
        return $this->cache->remember($cacheKey, 900, function () use ($period) {
            return [
                'period' => $period,
                'gstin' => config('company.gstin'),
                'legal_name' => config('company.name'),
                'b2b' => $this->gstRepo->getB2BSupplies($period),
                'b2cl' => $this->gstRepo->getB2CLSupplies($period),
                'b2cs' => $this->gstRepo->getB2CSSupplies($period),
                'cdnr' => $this->gstRepo->getCreditDebitNotes($period, true),
                'summary' => $this->gstRepo->getPeriodSummary($period),
            ];
        });
    }
    /**
     * Generate GSTR-3B summary for a period
     */
    public function generateGSTR3B(int $month, int $year): array
    {
        $period = sprintf('%04d-%02d', $year, $month);
        $outputTax = $this->gstRepo->getOutputTaxSummary($period);
        $inputCredit = $this->gstRepo->getInputCreditSummary($period);
        $liability = max('0', bcsub($outputTax['total_gst'], $inputCredit['total_itc'], 2));
        return compact('period', 'outputTax', 'inputCredit', 'liability');
    }
    public function exportGSTR1Excel(int $month, int $year): string
    {
        $data = $this->generateGSTR1($month, $year);
        return $this->excelService->generateGSTR1(
            $data,
            "GSTR1_{$year}" . sprintf('%02d', $month) . ".xlsx"
        );
    }
}