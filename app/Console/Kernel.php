<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * Property Finder Omni Platform — 141 scheduled jobs across 26 clusters.
     */
    protected function schedule(Schedule $schedule): void
    {
        // =====================================================================
        // CLUSTER 01 — CRM & LEAD MANAGEMENT
        // =====================================================================

        // Score and rank all active leads
        $schedule->command('pfre:leads:score-all')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cron/leads.log'));

        // Auto-assign unassigned leads to available agents (round-robin)
        $schedule->command('pfre:leads:auto-assign')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // Follow-up reminder notifications (WhatsApp + SMS)
        $schedule->command('pfre:leads:followup-reminders')
            ->dailyAt(config('pfre.cron.lead_followup_hour') . ':00')
            ->withoutOverlapping();

        // Mark leads as cold/stale after inactivity
        $schedule->command('pfre:leads:mark-stale')
            ->dailyAt('01:00')
            ->withoutOverlapping();

        // Duplicate lead detection & merge suggestions
        $schedule->command('pfre:leads:detect-duplicates')
            ->dailyAt('02:00')
            ->withoutOverlapping();

        // Site visit reminder 24 hours before scheduled visit
        $schedule->command('pfre:leads:site-visit-reminders')
            ->hourly()
            ->withoutOverlapping();

        // Site visit reminder 2 hours before scheduled visit
        $schedule->command('pfre:leads:site-visit-reminders --window=2h')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // Lead source ROI report generation
        $schedule->command('pfre:leads:source-roi-report')
            ->weeklyOn(1, '07:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 02 — PROPERTY & PROJECT MANAGEMENT
        // =====================================================================

        // Sync property availability status across all units
        $schedule->command('pfre:property:sync-availability')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // Update property price indices (market rate adjustments)
        $schedule->command('pfre:property:update-price-index')
            ->dailyAt('06:00')
            ->withoutOverlapping();

        // Construction milestone progress update & alerts
        $schedule->command('pfre:project:milestone-alerts')
            ->dailyAt('08:00')
            ->withoutOverlapping();

        // Auto-update project completion percentages
        $schedule->command('pfre:project:update-completion')
            ->dailyAt('07:00')
            ->withoutOverlapping();

        // Project delay risk assessment
        $schedule->command('pfre:project:delay-risk-check')
            ->weeklyOn(1, '08:30')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 03 — SALES & BOOKINGS
        // =====================================================================

        // Booking expiry checks — cancel unpaid bookings past deadline
        $schedule->command('pfre:bookings:expire-unpaid')
            ->hourly()
            ->withoutOverlapping();

        // Payment schedule generation for new bookings
        $schedule->command('pfre:bookings:generate-schedules')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // Agreement generation for confirmed bookings
        $schedule->command('pfre:agreements:generate-pending')
            ->hourly()
            ->withoutOverlapping();

        // Registration deadline alerts (30, 7, 1 day before)
        $schedule->command('pfre:agreements:registration-alerts')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Sales target vs achievement daily snapshot
        $schedule->command('pfre:sales:daily-snapshot')
            ->dailyAt('23:30')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 04 — FINANCE & PAYMENTS (DEMAND NOTES / EMI)
        // =====================================================================

        // Generate monthly demand notes / installment notices
        $schedule->command('pfre:finance:generate-demand-notes')
            ->monthlyOn(1, '08:00')
            ->withoutOverlapping();

        // Payment due reminders (7 days before due)
        $schedule->command('pfre:finance:payment-reminders --days=7')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Payment due reminders (3 days before due)
        $schedule->command('pfre:finance:payment-reminders --days=3')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Payment due reminders (day-of)
        $schedule->command('pfre:finance:payment-reminders --days=0')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Overdue payment escalations
        $schedule->command('pfre:finance:overdue-escalations')
            ->dailyAt('10:00')
            ->withoutOverlapping();

        // Interest / penalty calculation on overdue amounts
        $schedule->command('pfre:finance:calculate-penalties')
            ->dailyAt('00:30')
            ->withoutOverlapping();

        // EMI collection reconciliation
        $schedule->command('pfre:finance:reconcile-emi')
            ->dailyAt('23:00')
            ->withoutOverlapping();

        // Razorpay payment status sync (check pending orders)
        $schedule->command('pfre:payments:razorpay-sync')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 05 — ACCOUNTS & LEDGER
        // =====================================================================

        // Daily accounts closing entries
        $schedule->command('pfre:accounts:daily-closing')
            ->dailyAt('23:45')
            ->withoutOverlapping();

        // Bank reconciliation statement
        $schedule->command('pfre:accounts:bank-reconciliation')
            ->dailyAt('07:00')
            ->withoutOverlapping();

        // Outstanding receivables aging report
        $schedule->command('pfre:accounts:aging-report')
            ->weeklyOn(1, '07:30')
            ->withoutOverlapping();

        // Trial balance generation
        $schedule->command('pfre:accounts:trial-balance')
            ->monthlyOn(1, '06:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 06 — GST COMPLIANCE
        // =====================================================================

        // Sync GST data from GSTN portal
        $schedule->command('pfre:gst:sync-portal-data')
            ->dailyAt('03:00')
            ->withoutOverlapping();

        // Prepare GSTR-1 data (outward supplies)
        $schedule->command('pfre:gst:prepare-gstr1')
            ->monthlyOn(1, '05:00')
            ->withoutOverlapping();

        // Prepare GSTR-3B summary
        $schedule->command('pfre:gst:prepare-gstr3b')
            ->monthlyOn(1, '05:30')
            ->withoutOverlapping();

        // GST filing deadline alerts (18th / 20th of month)
        $schedule->command('pfre:gst:filing-deadline-alerts')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // ITC (Input Tax Credit) reconciliation — GSTR-2A vs books
        $schedule->command('pfre:gst:itc-reconciliation')
            ->monthlyOn(15, '04:00')
            ->withoutOverlapping();

        // e-Invoice status check (pending IRN generation)
        $schedule->command('pfre:gst:einvoice-pending-check')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 07 — RERA COMPLIANCE
        // =====================================================================

        // RERA project quarterly report preparation
        $schedule->command('pfre:rera:quarterly-report')
            ->quarterlyOn(1, '05:00')
            ->withoutOverlapping();

        // RERA registration renewal alerts
        $schedule->command('pfre:rera:renewal-alerts')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // RERA agent licence expiry alerts
        $schedule->command('pfre:rera:agent-licence-alerts')
            ->weeklyOn(1, '09:00')
            ->withoutOverlapping();

        // Sync RERA complaint status from MahaRERA portal
        $schedule->command('pfre:rera:sync-complaints')
            ->dailyAt('04:00')
            ->withoutOverlapping();

        // RERA escrow account reconciliation
        $schedule->command('pfre:rera:escrow-reconciliation')
            ->monthlyOn(5, '06:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 08 — HRMS & ATTENDANCE
        // =====================================================================

        // Sync attendance from biometric / mobile app
        $schedule->command('pfre:attendance:sync')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // Mark absent for employees with no punch-in by 10:30 AM
        $schedule->command('pfre:attendance:mark-absent')
            ->dailyAt('10:30')
            ->withoutOverlapping();

        // Half-day auto-marking (less than 4h attendance)
        $schedule->command('pfre:attendance:mark-halfday')
            ->dailyAt('23:50')
            ->withoutOverlapping();

        // Monthly attendance summary compilation
        $schedule->command('pfre:attendance:monthly-summary')
            ->monthlyOn(1, '01:00')
            ->withoutOverlapping();

        // Birthday & work anniversary notifications
        $schedule->command('pfre:hr:occasion-notifications')
            ->dailyAt('08:00')
            ->withoutOverlapping();

        // Employee document expiry alerts (ID, driving licence, etc.)
        $schedule->command('pfre:hr:document-expiry-alerts')
            ->weeklyOn(1, '09:00')
            ->withoutOverlapping();

        // Probation period tracking & confirmation alerts
        $schedule->command('pfre:hr:probation-alerts')
            ->weeklyOn(1, '09:30')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 09 — LEAVE MANAGEMENT
        // =====================================================================

        // Annual leave balance credit (1st January)
        $schedule->command('pfre:leave:credit-annual')
            ->yearlyOn(1, 1, '00:01')
            ->withoutOverlapping();

        // Monthly leave balance credit (CL / SL)
        $schedule->command('pfre:leave:credit-monthly')
            ->monthlyOn(1, '00:15')
            ->withoutOverlapping();

        // Leave expiry / lapse processing (year end)
        $schedule->command('pfre:leave:process-lapse')
            ->yearlyOn(12, 31, '23:00')
            ->withoutOverlapping();

        // Leave encashment calculations (annual)
        $schedule->command('pfre:leave:encashment-calc')
            ->yearlyOn(3, 31, '06:00')
            ->withoutOverlapping();

        // Pending leave approval reminders to managers
        $schedule->command('pfre:leave:pending-approval-reminders')
            ->dailyAt('10:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 10 — PAYROLL
        // =====================================================================

        // Lock attendance for payroll processing (25th of month)
        $schedule->command('pfre:payroll:lock-attendance')
            ->monthlyOn(config('pfre.cron.payroll_run_day') - 2, '18:00')
            ->withoutOverlapping();

        // Auto-run payroll calculation
        $schedule->command('pfre:payroll:calculate')
            ->monthlyOn(config('pfre.cron.payroll_run_day'), '22:00')
            ->withoutOverlapping();

        // PF ECR file generation
        $schedule->command('pfre:payroll:generate-ecr')
            ->monthlyOn(config('pfre.cron.payroll_run_day') + 1, '06:00')
            ->withoutOverlapping();

        // ESIC return data preparation
        $schedule->command('pfre:payroll:esic-return')
            ->monthlyOn(11, '06:00')
            ->withoutOverlapping();

        // Professional Tax challan generation (Maharashtra)
        $schedule->command('pfre:payroll:pt-challan')
            ->monthlyOn(config('pfre.cron.payroll_run_day'), '06:30')
            ->withoutOverlapping();

        // TDS calculation & Form 24Q preparation (quarterly)
        $schedule->command('pfre:payroll:tds-24q')
            ->quarterlyOn(15, '05:00')
            ->withoutOverlapping();

        // Payslip distribution via WhatsApp & email
        $schedule->command('pfre:payroll:distribute-payslips')
            ->monthlyOn(config('pfre.cron.payroll_run_day') + 2, '09:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 11 — PROCUREMENT & INVENTORY
        // =====================================================================

        // Low stock alerts for construction materials
        $schedule->command('pfre:inventory:low-stock-alerts')
            ->dailyAt('08:00')
            ->withoutOverlapping();

        // Pending purchase order follow-ups
        $schedule->command('pfre:procurement:po-followup')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Vendor payment due reminders
        $schedule->command('pfre:procurement:vendor-payment-reminders')
            ->dailyAt('09:30')
            ->withoutOverlapping();

        // Inventory valuation report (monthly)
        $schedule->command('pfre:inventory:valuation-report')
            ->monthlyOn(1, '05:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 12 — FACILITIES & MAINTENANCE
        // =====================================================================

        // Preventive maintenance schedule trigger
        $schedule->command('pfre:facilities:preventive-maintenance')
            ->dailyAt('08:00')
            ->withoutOverlapping();

        // Maintenance complaint SLA breach alerts
        $schedule->command('pfre:facilities:sla-breach-alerts')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // Utility bill due date reminders (electricity, water)
        $schedule->command('pfre:facilities:utility-bill-reminders')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // AMC (Annual Maintenance Contract) renewal alerts
        $schedule->command('pfre:facilities:amc-renewal-alerts')
            ->weeklyOn(1, '09:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 13 — DOCUMENT MANAGEMENT
        // =====================================================================

        // Clean up expired temporary files
        $schedule->command('pfre:documents:cleanup-temp')
            ->dailyAt('03:00')
            ->withoutOverlapping();

        // Document expiry alerts (KYC docs, licences, etc.)
        $schedule->command('pfre:documents:expiry-alerts')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Backup documents to cloud storage
        $schedule->command('pfre:documents:cloud-backup')
            ->dailyAt('02:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 14 — NOTIFICATIONS & COMMUNICATIONS
        // =====================================================================

        // Process queued WhatsApp notifications
        $schedule->command('pfre:whatsapp:process-queue')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        // Retry failed WhatsApp messages
        $schedule->command('pfre:whatsapp:retry-failed')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // SMS delivery status sync
        $schedule->command('pfre:sms:sync-delivery-status')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // Email bounce processing
        $schedule->command('pfre:email:process-bounces')
            ->hourly()
            ->withoutOverlapping();

        // Scheduled campaign dispatch
        $schedule->command('pfre:campaigns:dispatch-scheduled')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // Campaign performance report
        $schedule->command('pfre:campaigns:performance-report')
            ->weeklyOn(1, '08:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 15 — REPORTS & ANALYTICS
        // =====================================================================

        // Daily MIS report generation
        $schedule->command('pfre:reports:daily-mis')
            ->dailyAt(config('pfre.cron.report_generation_hour') . ':00')
            ->withoutOverlapping();

        // Weekly sales & CRM performance report
        $schedule->command('pfre:reports:weekly-sales')
            ->weeklyOn(1, '07:00')
            ->withoutOverlapping();

        // Monthly business performance report
        $schedule->command('pfre:reports:monthly-business')
            ->monthlyOn(1, '06:30')
            ->withoutOverlapping();

        // Collection efficiency report
        $schedule->command('pfre:reports:collection-efficiency')
            ->monthlyOn(1, '07:00')
            ->withoutOverlapping();

        // Inventory & absorption rate report
        $schedule->command('pfre:reports:inventory-absorption')
            ->weeklyOn(5, '07:00')
            ->withoutOverlapping();

        // Dashboard cache refresh
        $schedule->command('pfre:analytics:refresh-dashboard-cache')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // KPI snapshot (for trend graphs)
        $schedule->command('pfre:analytics:kpi-snapshot')
            ->hourly()
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 16 — INTEGRATIONS (External APIs)
        // =====================================================================

        // Sync Google Maps geocoding for new properties
        $schedule->command('pfre:integrations:geocode-properties')
            ->hourly()
            ->withoutOverlapping();

        // OTA channel manager availability sync
        $schedule->command('pfre:integrations:ota-sync')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // CIBIL score refresh for active loan applicants
        $schedule->command('pfre:integrations:cibil-refresh')
            ->weeklyOn(3, '03:00')
            ->withoutOverlapping();

        // GSTN portal data sync
        $schedule->command('pfre:integrations:gstn-sync')
            ->dailyAt('04:00')
            ->withoutOverlapping();

        // Aadhaar eSign status check for pending documents
        $schedule->command('pfre:integrations:esign-status-check')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 17 — SYSTEM HEALTH & MAINTENANCE
        // =====================================================================

        // Clear expired cache entries
        $schedule->command('cache:prune-stale-tags')
            ->hourly()
            ->withoutOverlapping();

        // Clean up old session records
        $schedule->command('pfre:system:clean-sessions')
            ->dailyAt('03:30')
            ->withoutOverlapping();

        // Prune old telescope entries (dev/staging only)
        $schedule->command('telescope:prune --hours=72')
            ->dailyAt('04:00')
            ->environments(['local', 'staging'])
            ->withoutOverlapping();

        // Prune old activity logs (keep 6 months)
        $schedule->command('activitylog:clean --days=180')
            ->monthlyOn(1, '04:30')
            ->withoutOverlapping();

        // Database optimization (ANALYZE TABLES)
        $schedule->command('pfre:system:db-optimize')
            ->weeklyOn(0, '03:00')
            ->withoutOverlapping();

        // Check queue health and alert on stalled workers
        $schedule->command('pfre:system:queue-health-check')
            ->everyFiveMinutes()
            ->withoutOverlapping();

        // Laravel Horizon monitoring snapshot
        $schedule->command('horizon:snapshot')
            ->everyFiveMinutes();

        // Application backup (DB + storage)
        $schedule->command('backup:run')
            ->dailyAt('01:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cron/backup.log'));

        // Cleanup old backups (keep 7 days)
        $schedule->command('backup:clean')
            ->dailyAt('01:30')
            ->withoutOverlapping();

        // SSL certificate expiry check
        $schedule->command('pfre:system:ssl-expiry-check')
            ->weeklyOn(1, '08:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 18 — POSSESSION & HANDOVER
        // =====================================================================

        // Possession readiness checklist status update
        $schedule->command('pfre:possession:checklist-status')
            ->dailyAt('08:30')
            ->withoutOverlapping();

        // OC (Occupancy Certificate) status check
        $schedule->command('pfre:possession:oc-status-check')
            ->weeklyOn(1, '09:00')
            ->withoutOverlapping();

        // Handover appointment reminders
        $schedule->command('pfre:possession:handover-reminders')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 19 — CUSTOMER EXPERIENCE
        // =====================================================================

        // Post-possession satisfaction survey dispatch
        $schedule->command('pfre:cx:post-possession-survey')
            ->dailyAt('11:00')
            ->withoutOverlapping();

        // Net Promoter Score (NPS) monthly survey
        $schedule->command('pfre:cx:nps-survey')
            ->monthlyOn(15, '10:00')
            ->withoutOverlapping();

        // Escalated complaint follow-up
        $schedule->command('pfre:cx:escalation-followup')
            ->everyThirtyMinutes()
            ->withoutOverlapping();

        // =====================================================================
        // CLUSTER 20 — AUDIT & COMPLIANCE LOGS
        // =====================================================================

        // Export daily audit log to secure archive
        $schedule->command('pfre:audit:export-daily-log')
            ->dailyAt('00:05')
            ->withoutOverlapping();

        // Compliance checklist reminder (monthly)
        $schedule->command('pfre:audit:compliance-checklist')
            ->monthlyOn(1, '09:00')
            ->withoutOverlapping();

        // TDS return deadline alerts (quarterly: 15th of month after quarter)
        $schedule->command('pfre:audit:tds-return-alerts')
            ->monthlyOn(10, '09:00')
            ->withoutOverlapping();

        // Form 26AS reconciliation
        $schedule->command('pfre:audit:form-26as-reconcile')
            ->quarterlyOn(1, '05:00')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}