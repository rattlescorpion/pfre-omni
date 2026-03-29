<?php

use Illuminate\Support\Facades\Route;
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
Route::get('/leads', [App\Http\Controllers\LeadController::class, 'index']);

// PUBLIC ROUTES (no auth)
// Route::group([], function () {
//     Route::get('/login',                    [LoginController::class, 'showForm']);
//     Route::post('/login',                   [LoginController::class, 'login']);
//     Route::get('/logout',                   [LogoutController::class, 'logout']);
//     Route::get('/2fa',                      [TwoFactorController::class, 'show']);
//     Route::post('/2fa',                     [TwoFactorController::class, 'verify']);
//     Route::get('/forgot-password',          [PasswordResetController::class, 'showForgot']);
//     Route::post('/forgot-password',         [PasswordResetController::class, 'sendLink']);
//     Route::get('/reset-password/{token}',   [PasswordResetController::class, 'showReset']);
//     Route::post('/reset-password',          [PasswordResetController::class, 'reset']);
    
//     // Public website (CMS-driven)
//     Route::get('/',                         [PublicController::class, 'home']);
//     Route::get('/properties',               [PublicController::class, 'propertySearch']);
//     Route::get('/properties/{slug}',        [PublicController::class, 'propertyDetail']);
//     Route::post('/enquiry',                 [PublicController::class, 'submitEnquiry']); // → CRM lead
//     Route::get('/blog',                     [PublicController::class, 'blog']);
//     Route::get('/blog/{slug}',              [PublicController::class, 'blogPost']);
//     Route::get('/about',                    [PublicController::class, 'page']);
//     Route::get('/contact',                  [PublicController::class, 'page']);
//     Route::get('/stamp-duty-calculator',    [PublicController::class, 'stampDutyCalc']);
//     Route::get('/emi-calculator',           [PublicController::class, 'emiCalc']);
//     Route::get('/sitemap.xml',              [PublicController::class, 'sitemap']);
//     Route::get('/robots.txt',               [PublicController::class, 'robots']);
// });

// // ALL AUTHENTICATED ROUTES
// Route::group(['middleware' => [AuthMiddleware::class, AuditMiddleware::class]], function () {
//     // DASHBOARD
//     Route::get('/dashboard',                [DashboardController::class, 'index']);
//     Route::get('/dashboard/widget/{key}',   [WidgetController::class, 'fetch']);   // AJAX KPIs
//     Route::post('/dashboard/set-default',   [DashboardController::class, 'setDefault']);
    
//     // ── CRM ───────────────────────────────────────────────────────
//     Route::group(['prefix' => '/leads'], function () {
//         Route::get('',                          [LeadController::class, 'index']);
//         Route::get('/create',                   [LeadController::class, 'create']);
//         Route::post('',                         [LeadController::class, 'store']);
//         Route::get('/{id}',                     [LeadController::class, 'show']);
//         Route::get('/{id}/edit',                [LeadController::class, 'edit']);
//         Route::post('/{id}',                    [LeadController::class, 'update']);
//         Route::post('/{id}/delete',             [LeadController::class, 'destroy']);
//         Route::post('/{id}/stage',              [LeadController::class, 'changeStage']);
//         Route::post('/{id}/assign',             [LeadController::class, 'assign']);
//         Route::post('/{id}/activity',           [LeadController::class, 'addActivity']);
//         Route::post('/{id}/followup',           [LeadController::class, 'scheduleFollowup']);
//         Route::post('/{id}/shortlist',          [LeadController::class, 'shortlistProperty']);
//         Route::post('/{id}/merge',              [LeadController::class, 'merge']);
//         Route::get('/pipeline',                 [PipelineController::class, 'kanban']);
//         Route::post('/pipeline/move',           [PipelineController::class, 'moveCard']); // drag-drop
//         Route::get('/followups/today',          [FollowupController::class, 'today']);
//         Route::get('/export',                   [LeadController::class, 'export']);
//         Route::post('/import',                  [LeadController::class, 'import']);
//         Route::post('/bulk-assign',             [LeadController::class, 'bulkAssign']);
//         Route::get('/stats',                    [LeadController::class, 'stats']); // AJAX
//     });

//     Route::group(['prefix' => '/deals'], function () {
//         Route::get('',                          [DealController::class, 'index']);
//         Route::get('/create',                   [DealController::class, 'create']);
//         Route::post('',                         [DealController::class, 'store']);
//         Route::get('/{id}',                     [DealController::class, 'show']);
//         Route::get('/{id}/edit',                [DealController::class, 'edit']);
//         Route::post('/{id}',                    [DealController::class, 'update']);
//         Route::post('/{id}/stage',              [DealController::class, 'changeStage']);
//         Route::post('/{id}/close',              [DealController::class, 'close']);
//         Route::get('/{id}/invoice',             [DealController::class, 'createInvoice']);
//         Route::get('/commission-calc',          [CommissionController::class, 'calculator']);
//         Route::post('/commission-calc/compute', [CommissionController::class, 'compute']);
//         Route::get('/pipeline',                 [DealController::class, 'pipeline']);
//         Route::get('/export',                   [DealController::class, 'export']);
//     });

//     Route::group(['prefix' => '/clients'], function () {
//         Route::get('',                          [ClientController::class, 'index']);
//         Route::get('/create',                   [ClientController::class, 'create']);
//         Route::post('',                         [ClientController::class, 'store']);
//         Route::get('/{id}',                     [ClientController::class, 'show']); // 360° view
//         Route::post('/{id}',                    [ClientController::class, 'update']);
//         Route::post('/{id}/requirement',        [ClientController::class, 'addRequirement']);
//         Route::get('/{id}/ledger',              [ClientController::class, 'ledger']);
//         Route::get('/export',                   [ClientController::class, 'export']);
//     });

//     // ── REAL ESTATE ───────────────────────────────────────────────
//     Route::group(['prefix' => '/properties'], function () {
//         Route::get('',                          [PropertyController::class, 'index']);
//         Route::get('/create',                   [PropertyController::class, 'create']);
//         Route::post('',                         [PropertyController::class, 'store']);
//         Route::get('/map',                      [PropertyController::class, 'mapView']);
//         Route::get('/inventory',                [PropertyController::class, 'inventory']);
//         Route::get('/{id}',                     [PropertyController::class, 'show']);
//         Route::get('/{id}/edit',                [PropertyController::class, 'edit']);
//         Route::post('/{id}',                    [PropertyController::class, 'update']);
//         Route::post('/{id}/status',             [PropertyController::class, 'changeStatus']);
//         Route::post('/{id}/media',              [PropertyController::class, 'uploadMedia']);
//         Route::post('/{id}/media/{mediaId}/delete', [PropertyController::class, 'deleteMedia']);
//         Route::post('/{id}/portal-sync',        [ListingController::class, 'sync']);
//         Route::post('/{id}/valuation',          [ValuationController::class, 'create']);
//         Route::get('/portal-status',            [ListingController::class, 'status']);
//         Route::get('/export',                   [PropertyController::class, 'export']);
//     });

//     Route::group(['prefix' => '/new-projects'], function () {
//         Route::get('',                          [ConstructionController::class, 'index']);
//         Route::get('/create',                   [ConstructionController::class, 'create']);
//         Route::post('',                         [ConstructionController::class, 'store']);
//         Route::get('/{id}',                     [ConstructionController::class, 'show']);
//         Route::post('/{id}',                    [ConstructionController::class, 'update']);
//     });

//     Route::group(['prefix' => '/market-rates'], function () {
//         Route::get('',                          [MarketAnalysisController::class, 'index']);
//         Route::post('',                         [MarketAnalysisController::class, 'update']);
//         Route::get('/compare',                  [MarketAnalysisController::class, 'compare']);
//         Route::get('/trends',                   [MarketAnalysisController::class, 'trends']);
//     });

//     Route::group(['prefix' => '/housing-loans'], function () {
//         Route::get('',                          [LoanController::class, 'index']);
//         Route::post('',                         [LoanController::class, 'store']);
//         Route::get('/{id}',                     [LoanController::class, 'show']);
//         Route::post('/{id}/status',             [LoanController::class, 'updateStatus']);
//         Route::get('/emi-calc',                 [LoanController::class, 'emiCalculator']);
//     });

//     // ── PMS ───────────────────────────────────────────────────────
//     Route::group(['prefix' => '/pms'], function () {
//         Route::get('/leases',                   [LeaseController::class, 'index']);
//         Route::get('/leases/create',            [LeaseController::class, 'create']);
//         Route::post('/leases',                  [LeaseController::class, 'store']);
//         Route::get('/leases/{id}',              [LeaseController::class, 'show']);
//         Route::post('/leases/{id}',             [LeaseController::class, 'update']);
//         Route::post('/leases/{id}/renew',       [LeaseController::class, 'renew']);
//         Route::post('/leases/{id}/terminate',   [LeaseController::class, 'terminate']);
//         Route::get('/leases/expiring',          [LeaseController::class, 'expiring']);
//         Route::get('/tenants',                  [TenantController::class, 'index']);
//         Route::get('/tenants/create',           [TenantController::class, 'create']);
//         Route::post('/tenants',                 [TenantController::class, 'store']);
//         Route::get('/tenants/{id}',             [TenantController::class, 'show']);
//         Route::post('/tenants/{id}',            [TenantController::class, 'update']);
//         Route::get('/rent',                     [RentController::class, 'index']);
//         Route::post('/rent/generate',           [RentController::class, 'generateMonthly']);
//         Route::post('/rent/{id}/pay',           [RentController::class, 'recordPayment']);
//         Route::post('/rent/{id}/waive',         [RentController::class, 'waive']);
//         Route::get('/rent/{id}/receipt',        [RentController::class, 'receipt']);
//         Route::get('/rent/overdue',             [RentController::class, 'overdue']);
//         Route::get('/maintenance',              [MaintenanceController::class, 'index']);
//         Route::post('/maintenance',             [MaintenanceController::class, 'store']);
//         Route::get('/maintenance/{id}',         [MaintenanceController::class, 'show']);
//         Route::post('/maintenance/{id}/assign', [MaintenanceController::class, 'assign']);
//         Route::post('/maintenance/{id}/resolve',[MaintenanceController::class, 'resolve']);
//         Route::get('/inspections',              [InspectionController::class, 'index']);
//         Route::post('/inspections',             [InspectionController::class, 'store']);
//         Route::get('/inspections/{id}',         [InspectionController::class, 'show']);
//         Route::get('/pg',                       [PGController::class, 'index']);
//         Route::post('/pg/{id}/occupy',          [PGController::class, 'occupy']);
//         Route::post('/pg/{id}/vacate',          [PGController::class, 'vacate']);
//     });

//     // ── DOCUMENTS ────────────────────────────────────────────────
//     Route::group(['prefix' => '/documents'], function () {
//         Route::get('',                          [DocumentController::class, 'index']);
//         Route::get('/create',                   [DocumentController::class, 'create']);
//         Route::post('',                         [DocumentController::class, 'store']);
//         Route::get('/expiring',                 [DocumentController::class, 'expiring']);
//         Route::get('/{id}',                     [DocumentController::class, 'show']);
//         Route::post('/{id}',                    [DocumentController::class, 'update']);
//         Route::post('/{id}/upload/{type}',      [DocumentController::class, 'uploadFile']);
//         Route::get('/{id}/download/{type}',     [DocumentController::class, 'download']);
//         Route::get('/{id}/share',               [DocumentController::class, 'shareLink']);
//         Route::get('/stamp-duty',               [StampDutyController::class, 'calculator']);
//         Route::post('/stamp-duty/calculate',    [StampDutyController::class, 'calculate']);
//         Route::get('/templates',                [TemplateController::class, 'index']);
//         Route::get('/templates/{type}/generate',[TemplateController::class, 'generate']);
//         Route::post('/templates/{type}/pdf',    [TemplateController::class, 'generatePDF']);
//         Route::get('/sro-offices',              [SROController::class, 'index']);
//         Route::get('/sro-offices/map',          [SROController::class, 'jurisdictionMap']);
//     });

//     // ── HRMS ────────────────────────────────────────────────────
//     Route::group(['prefix' => '/hr'], function () {
//         Route::get('/employees',                [EmployeeController::class, 'index']);
//         Route::get('/employees/org-chart',      [OrganizationController::class, 'chart']);
//         Route::get('/employees/create',         [EmployeeController::class, 'create']);
//         Route::post('/employees',               [EmployeeController::class, 'store']);
//         Route::get('/employees/{id}',           [EmployeeController::class, 'show']);
//         Route::post('/employees/{id}',          [EmployeeController::class, 'update']);
//         Route::post('/employees/{id}/terminate',[EmployeeController::class, 'terminate']);
//         Route::get('/employees/{id}/documents', [EmployeeController::class, 'documents']);
        
//         // Attendance
//         Route::get('/attendance',               [AttendanceController::class, 'index']);
//         Route::get('/attendance/{emp}/{month}', [AttendanceController::class, 'monthView']);
//         Route::post('/attendance/checkin',      [AttendanceController::class, 'checkIn']);
//         Route::post('/attendance/checkout',     [AttendanceController::class, 'checkOut']);
//         Route::post('/attendance/regularize',   [AttendanceController::class, 'regularize']);
//         Route::get('/attendance/report',        [AttendanceController::class, 'report']);
//         Route::get('/attendance/export',        [AttendanceController::class, 'export']);
        
//         // Leave
//         Route::get('/leaves',                   [LeaveController::class, 'index']);
//         Route::post('/leaves',                  [LeaveController::class, 'store']);
//         Route::post('/leaves/{id}/approve',     [LeaveController::class, 'approve']);
//         Route::post('/leaves/{id}/reject',      [LeaveController::class, 'reject']);
//         Route::get('/leaves/balance',           [LeaveController::class, 'balanceSummary']);
//         Route::get('/leaves/balance/{emp}',     [LeaveController::class, 'empBalance']);
//         Route::get('/leaves/calendar',          [LeaveController::class, 'calendar']);
        
//         // Payroll
//         Route::get('/payroll',                  [PayrollController::class, 'index']);
//         Route::post('/payroll/process',         [PayrollController::class, 'process']);
//         Route::get('/payroll/{run}',            [PayrollController::class, 'show']);
//         Route::post('/payroll/{run}/approve',   [PayrollController::class, 'approve']);
//         Route::post('/payroll/{run}/pay',       [PayrollController::class, 'markPaid']);
//         Route::get('/payroll/{run}/export',     [PayrollController::class, 'exportExcel']);
//         Route::get('/payslip/{entry}',          [SalarySlipController::class, 'show']);
//         Route::get('/payslip/{entry}/pdf',      [SalarySlipController::class, 'pdf']);
//         Route::get('/payslip/{entry}/email',    [SalarySlipController::class, 'sendEmail']);
        
//         // Performance & Appraisals
//         Route::get('/performance',              [PerformanceController::class, 'index']);
//         Route::post('/performance',             [PerformanceController::class, 'store']);
//         Route::get('/performance/{id}',         [PerformanceController::class, 'show']);
//         Route::get('/appraisals',               [AppraisalController::class, 'index']);
//         Route::post('/appraisals',              [AppraisalController::class, 'store']);
        
//         // Recruitment ATS
//         Route::get('/recruitment',              [RecruitmentController::class, 'index']);
//         Route::get('/recruitment/create',       [RecruitmentController::class, 'create']);
//         Route::post('/recruitment',             [RecruitmentController::class, 'store']);
//         Route::get('/recruitment/{id}',         [RecruitmentController::class, 'show']);
//         Route::get('/recruitment/{id}/applications', [RecruitmentController::class, 'applications']);
//         Route::post('/recruitment/{id}/applications/{appId}/status', [RecruitmentController::class, 'updateStatus']);
//         Route::post('/recruitment/{id}/applications/{appId}/interview', [RecruitmentController::class, 'scheduleInterview']);
        
//         // Onboarding, Shifts, Wellness, Expenses
//         Route::get('/onboarding',               [OnboardingController::class, 'index']);
//         Route::get('/onboarding/{emp}',         [OnboardingController::class, 'show']);
//         Route::post('/onboarding/{emp}/task',   [OnboardingController::class, 'completeTask']);
//         Route::get('/shifts',                   [ShiftController::class, 'index']);
//         Route::post('/shifts',                  [ShiftController::class, 'store']);
//         Route::get('/shifts/roster',            [ShiftController::class, 'roster']);
//         Route::post('/shifts/assign',           [ShiftController::class, 'assign']);
//         Route::get('/expenses',                 [ExpenseController::class, 'index']);
//         Route::post('/expenses',                [ExpenseController::class, 'store']);
//         Route::post('/expenses/{id}/approve',   [ExpenseController::class, 'approve']);
//         Route::post('/expenses/{id}/pay',       [ExpenseController::class, 'markPaid']);
//         Route::get('/wellness',                 [WellnessController::class, 'index']);
//         Route::post('/wellness/checkin',        [WellnessController::class, 'checkIn']);
//     });

//     // ── ERP ACCOUNTING ────────────────────────────────────────────
//     Route::group(['prefix' => '/accounting'], function () {
//         Route::get('/accounts',                 [ChartController::class, 'index']);
//         Route::get('/accounts/create',          [ChartController::class, 'create']);
//         Route::post('/accounts',                [ChartController::class, 'store']);
//         Route::get('/accounts/{id}',            [ChartController::class, 'show']);
//         Route::get('/accounts/{id}/ledger',     [ChartController::class, 'ledger']);
//         Route::get('/journals',                 [JournalController::class, 'index']);
//         Route::get('/journals/create',          [JournalController::class, 'create']);
//         Route::post('/journals',                [JournalController::class, 'store']);
//         Route::get('/journals/{id}',            [JournalController::class, 'show']);
//         Route::post('/journals/{id}/post',      [JournalController::class, 'post']);
//         Route::post('/journals/{id}/reverse',   [JournalController::class, 'reverse']);
//         Route::get('/invoices',                 [InvoiceController::class, 'index']);
//         Route::get('/invoices/create',          [InvoiceController::class, 'create']);
//         Route::post('/invoices',                [InvoiceController::class, 'store']);
//         Route::get('/invoices/{id}',            [InvoiceController::class, 'show']);
//         Route::post('/invoices/{id}/send',      [InvoiceController::class, 'send']);
//         Route::post('/invoices/{id}/pay',       [InvoiceController::class, 'recordPayment']);
//         Route::get('/invoices/{id}/pdf',        [InvoiceController::class, 'pdf']);
//         Route::get('/invoices/{id}/payment-link',[InvoiceController::class, 'createPaymentLink']);
//         Route::get('/bank',                     [BankController::class, 'index']);
//         Route::post('/bank/import',             [BankController::class, 'importStatement']);
//         Route::post('/bank/reconcile',          [BankController::class, 'reconcile']);
//         Route::get('/bank/{id}/transactions',   [BankController::class, 'transactions']);
//         Route::get('/reports/pnl',              [FinancialReportController::class, 'pnl']);
//         Route::get('/reports/balance-sheet',    [FinancialReportController::class, 'balanceSheet']);
//         Route::get('/reports/trial-balance',    [FinancialReportController::class, 'trialBalance']);
//         Route::get('/reports/cash-flow',        [FinancialReportController::class, 'cashFlow']);
//         Route::get('/reports/aging',            [FinancialReportController::class, 'aging']);
//         Route::get('/reports/export/{type}',    [FinancialReportController::class, 'export']);
//     });

//     // ── SUPPLY CHAIN & FACILITY ───────────────────────────────────
//     Route::group(['prefix' => '/vendors'], function () {
//         Route::get('',                          [VendorController::class, 'index']);
//         Route::get('/create',                   [VendorController::class, 'create']);
//         Route::post('',                         [VendorController::class, 'store']);
//         Route::get('/{id}',                     [VendorController::class, 'show']);
//         Route::post('/{id}',                    [VendorController::class, 'update']);
//         Route::post('/{id}/blacklist',          [VendorController::class, 'blacklist']);
//         Route::post('/{id}/evaluate',           [VendorController::class, 'evaluate']);
//     });

//     Route::group(['prefix' => '/procurement'], function () {
//         Route::get('',                          [POController::class, 'index']);
//         Route::get('/create',                   [POController::class, 'create']);
//         Route::post('',                         [POController::class, 'store']);
//         Route::get('/{id}',                     [POController::class, 'show']);
//         Route::post('/{id}/approve',            [POController::class, 'approve']);
//         Route::post('/{id}/receive',            [POController::class, 'receive']);
//     });

//     Route::group(['prefix' => '/inventory'], function () {
//         Route::get('',                          [ItemController::class, 'index']);
//         Route::post('',                         [ItemController::class, 'store']);
//         Route::get('/{id}',                     [ItemController::class, 'show']);
//         Route::post('/{id}/adjust',             [StockController::class, 'adjust']);
//         Route::get('/movements',                [StockMovementController::class, 'index']);
//         Route::get('/low-stock',                [StockController::class, 'lowStock']);
//         Route::get('/report',                   [StockController::class, 'report']);
//     });

//     Route::group(['prefix' => '/assets'], function () {
//         Route::get('',                          [AssetController::class, 'index']);
//         Route::get('/create',                   [AssetController::class, 'create']);
//         Route::post('',                         [AssetController::class, 'store']);
//         Route::get('/{id}',                     [AssetController::class, 'show']);
//         Route::post('/{id}/assign',             [AssetController::class, 'assign']);
//         Route::post('/{id}/dispose',            [AssetController::class, 'dispose']);
//         Route::get('/depreciation',             [DepreciationController::class, 'schedule']);
//         Route::post('/depreciation/run',        [DepreciationController::class, 'runMonthly']);
//     });

//     Route::group(['prefix' => '/facility'], function () {
//         Route::get('/amc',                      [AMCController::class, 'index']);
//         Route::post('/amc',                     [AMCController::class, 'store']);
//         Route::get('/amc/{id}',                 [AMCController::class, 'show']);
//         Route::post('/amc/{id}/service',        [AMCController::class, 'logService']);
//         Route::get('/visitors',                 [VisitorController::class, 'index']);
//         Route::post('/visitors',                [VisitorController::class, 'checkIn']);
//         Route::post('/visitors/{id}/checkout',  [VisitorController::class, 'checkOut']);
//         Route::get('/visitors/today',           [VisitorController::class, 'today']);
//     });

//     Route::group(['prefix' => '/fleet'], function () {
//         Route::get('',                          [VehicleController::class, 'index']);
//         Route::post('',                         [VehicleController::class, 'store']);
//         Route::get('/{id}',                     [VehicleController::class, 'show']);
//         Route::get('/trips',                    [TripController::class, 'index']);
//         Route::post('/trips',                   [TripController::class, 'store']);
//         Route::get('/fuel',                     [VehicleController::class, 'fuelLog']);
//         Route::get('/documents-expiry',         [VehicleController::class, 'documentsExpiry']);
//     });

//     // ── TAX, COMPLIANCE & LEGAL ───────────────────────────────────
//     Route::group(['prefix' => '/tax'], function () {
//         Route::get('/gst',                      [GSTController::class, 'dashboard']);
//         Route::get('/gst/gstr1/{period}',       [GSTController::class, 'gstr1']);
//         Route::get('/gst/gstr3b/{period}',      [GSTController::class, 'gstr3b']);
//         Route::get('/gst/input-credit',         [GSTController::class, 'inputCredit']);
//         Route::get('/gst/export/{type}/{period}',[GSTController::class, 'exportExcel']);
//         Route::get('/tds',                      [TDSController::class, 'index']);
//         Route::post('/tds/{id}/deposit',        [TDSController::class, 'markDeposited']);
//         Route::get('/tds/26q/{q}/{fy}',         [TDSController::class, 'form26Q']);
//         Route::get('/compliance',               [ComplianceController::class, 'index']);
//         Route::get('/compliance/calendar',      [ComplianceController::class, 'calendar']);
//         Route::post('/compliance/{id}/file',    [ComplianceController::class, 'markFiled']);
//     });

//     Route::group(['prefix' => '/legal'], function () {
//         Route::get('/cases',                    [LegalCaseController::class, 'index']);
//         Route::get('/cases/create',             [LegalCaseController::class, 'create']);
//         Route::post('/cases',                   [LegalCaseController::class, 'store']);
//         Route::get('/cases/{id}',               [LegalCaseController::class, 'show']);
//         Route::post('/cases/{id}',              [LegalCaseController::class, 'update']);
//         Route::post('/cases/{id}/hearing',      [LegalCaseController::class, 'addHearing']);
//         Route::get('/diary',                    [LegalCaseController::class, 'diary']);
//         Route::get('/licenses',                 [LicenseController::class, 'index']);
//         Route::post('/licenses',                [LicenseController::class, 'store']);
//         Route::post('/licenses/{id}/renew',     [LicenseController::class, 'renew']);
//         Route::get('/risk-register',            [RiskController::class, 'index']);
//         Route::post('/risk-register',           [RiskController::class, 'store']);
//         Route::post('/risk-register/{id}',      [RiskController::class, 'update']);
//         Route::get('/grc',                      [GRCController::class, 'dashboard']);
//         Route::get('/franchise',                [FranchiseController::class, 'index']);
//         Route::post('/franchise',               [FranchiseController::class, 'store']);
//         Route::get('/franchise/{id}',           [FranchiseController::class, 'show']);
//     });

//     // ── PROJECTS & TASKS ──────────────────────────────────────────
//     Route::group(['prefix' => '/projects'], function () {
//         Route::get('',                          [ProjectController::class, 'index']);
//         Route::get('/create',                   [ProjectController::class, 'create']);
//         Route::post('',                         [ProjectController::class, 'store']);
//         Route::get('/{id}',                     [ProjectController::class, 'show']);
//         Route::post('/{id}',                    [ProjectController::class, 'update']);
//         Route::post('/{id}/milestones',         [MilestoneController::class, 'store']);
//         Route::post('/milestones/{id}',         [MilestoneController::class, 'update']);
//         Route::get('/timesheets',               [TimesheetController::class, 'index']);
//         Route::post('/timesheets',              [TimesheetController::class, 'store']);
//         Route::post('/timesheets/{id}/approve', [TimesheetController::class, 'approve']);
//     });

//     Route::group(['prefix' => '/tasks'], function () {
//         Route::get('',                          [TaskController::class, 'index']);
//         Route::post('',                         [TaskController::class, 'store']);
//         Route::get('/{id}',                     [TaskController::class, 'show']);
//         Route::post('/{id}',                    [TaskController::class, 'update']);
//         Route::post('/{id}/complete',           [TaskController::class, 'complete']);
//         Route::post('/bulk-complete',           [TaskController::class, 'bulkComplete']);
//     });

//     Route::group(['prefix' => '/calendar'], function () {
//         Route::get('',                          [CalendarController::class, 'index']);
//         Route::post('/events',                  [CalendarController::class, 'store']);
//         Route::get('/events',                   [CalendarController::class, 'feed']);
//         Route::post('/events/{id}',             [CalendarController::class, 'update']);
//         Route::post('/events/{id}/delete',      [CalendarController::class, 'destroy']);
//     });

//     // ── CMS, LMS & KMS ────────────────────────────────────────────
//     Route::group(['prefix' => '/cms'], function () {
//         Route::get('/pages',                    [PageController::class, 'index']);
//         Route::get('/pages/create',             [PageController::class, 'create']);
//         Route::post('/pages',                   [PageController::class, 'store']);
//         Route::get('/pages/{id}/edit',          [PageController::class, 'edit']);
//         Route::post('/pages/{id}',              [PageController::class, 'update']);
//         Route::post('/pages/{id}/publish',      [PageController::class, 'publish']);
//         Route::get('/posts',                    [BlogController::class, 'index']);
//         Route::get('/posts/create',             [BlogController::class, 'create']);
//         Route::post('/posts',                   [BlogController::class, 'store']);
//         Route::get('/posts/{id}/edit',          [BlogController::class, 'edit']);
//         Route::post('/posts/{id}',              [BlogController::class, 'update']);
//         Route::post('/posts/{id}/publish',      [BlogController::class, 'publish']);
//         Route::get('/media',                    [GalleryController::class, 'index']);
//         Route::post('/media/upload',            [GalleryController::class, 'upload']);
//         Route::post('/media/{id}/delete',       [GalleryController::class, 'delete']);
//         Route::get('/forms',                    [FormBuilderController::class, 'index']);
//         Route::post('/forms',                   [FormBuilderController::class, 'store']);
//         Route::get('/forms/{id}/submissions',   [FormBuilderController::class, 'submissions']);
//         Route::get('/forms/{id}/export',        [FormBuilderController::class, 'export']);
//         Route::get('/testimonials',             [TestimonialController::class, 'index']);
//         Route::post('/testimonials',            [TestimonialController::class, 'store']);
//         Route::post('/testimonials/{id}/approve',[TestimonialController::class, 'approve']);
//         Route::get('/seo',                      [SEOController::class, 'index']);
//         Route::post('/seo/sitemap',             [SEOController::class, 'regenerateSitemap']);
//         Route::get('/seo/redirects',            [SEOController::class, 'redirects']);
//         Route::post('/seo/redirects',           [SEOController::class, 'addRedirect']);
//     });

//     Route::group(['prefix' => '/lms'], function () {
//         Route::get('',                          [LMSController::class, 'dashboard']);
//         Route::get('/courses',                  [CourseController::class, 'index']);
//         Route::get('/courses/create',           [CourseController::class, 'create']);
//         Route::post('/courses',                 [CourseController::class, 'store']);
//         Route::get('/courses/{id}',             [CourseController::class, 'show']);
//         Route::post('/courses/{id}/enroll',     [CourseController::class, 'enroll']);
//         Route::get('/my-learning',              [EnrollmentController::class, 'myLearning']);
//         Route::post('/lessons/{id}/complete',   [EnrollmentController::class, 'completeLesson']);
//         Route::get('/quizzes/{id}',             [QuizController::class, 'take']);
//         Route::post('/quizzes/{id}/submit',     [QuizController::class, 'submit']);
//         Route::get('/certificates/{id}',        [CertificateController::class, 'view']);
//         Route::get('/certificates/{id}/pdf',    [CertificateController::class, 'pdf']);
//         Route::get('/calendar',                 [LMSController::class, 'calendar']);
//         Route::get('/skill-matrix',             [LMSController::class, 'skillMatrix']);
//         Route::get('/team-progress',            [LMSController::class, 'teamProgress']);
//     });

//     Route::group(['prefix' => '/kb'], function () {
//         Route::get('',                          [KBController::class, 'home']);
//         Route::get('/search',                   [KBController::class, 'search']);
//         Route::get('/articles/create',          [ArticleController::class, 'create']);
//         Route::post('/articles',                [ArticleController::class, 'store']);
//         Route::get('/articles/{slug}',          [ArticleController::class, 'show']);
//         Route::get('/articles/{id}/edit',       [ArticleController::class, 'edit']);
//         Route::post('/articles/{id}',           [ArticleController::class, 'update']);
//         Route::post('/articles/{id}/helpful',   [ArticleController::class, 'helpful']);
//         Route::get('/categories/{slug}',        [KBController::class, 'category']);
//     });

//     // ── SUPPORT & MARKETING ───────────────────────────────────────
//     Route::group(['prefix' => '/support'], function () {
//         Route::get('/tickets',                  [TicketController::class, 'index']);
//         Route::get('/tickets/create',           [TicketController::class, 'create']);
//         Route::post('/tickets',                 [TicketController::class, 'store']);
//         Route::get('/tickets/{id}',             [TicketController::class, 'show']);
//         Route::post('/tickets/{id}/reply',      [TicketController::class, 'reply']);
//         Route::post('/tickets/{id}/assign',     [TicketController::class, 'assign']);
//         Route::post('/tickets/{id}/status',     [TicketController::class, 'changeStatus']);
//         Route::post('/tickets/{id}/escalate',   [TicketController::class, 'escalate']);
//         Route::post('/tickets/{id}/rate',       [TicketController::class, 'rate']);
//         Route::get('/sla',                      [SLAController::class, 'dashboard']);
//     });

//     Route::group(['prefix' => '/marketing'], function () {
//         Route::get('/campaigns',                [CampaignController::class, 'index']);
//         Route::get('/campaigns/create',         [CampaignController::class, 'create']);
//         Route::post('/campaigns',               [CampaignController::class, 'store']);
//         Route::get('/campaigns/{id}',           [CampaignController::class, 'show']);
//         Route::post('/campaigns/{id}/schedule', [CampaignController::class, 'schedule']);
//         Route::post('/campaigns/{id}/send',     [CampaignController::class, 'sendNow']);
//         Route::post('/campaigns/{id}/pause',    [CampaignController::class, 'pause']);
//         Route::get('/campaigns/{id}/analytics', [CampaignController::class, 'analytics']);
//         Route::get('/whatsapp-templates',       [WhatsAppTemplateController::class, 'index']);
//         Route::post('/whatsapp-templates',      [WhatsAppTemplateController::class, 'store']);
//         Route::post('/whatsapp-templates/{id}/sync', [WhatsAppTemplateController::class, 'syncFromMeta']);
//         Route::get('/email-templates',          [EmailTemplateController::class, 'index']);
//         Route::post('/email-templates',         [EmailTemplateController::class, 'store']);
//         Route::get('/segments',                 [SegmentController::class, 'index']);
//         Route::post('/segments',                [SegmentController::class, 'store']);
//         Route::get('/segments/{id}/preview',    [SegmentController::class, 'preview']);
//     });

//     // ── REPORTS, NOTIFICATIONS & SETTINGS ─────────────────────────
//     Route::group(['prefix' => '/reports'], function () {
//         Route::get('',                          [ReportController::class, 'library']);
//         Route::get('/builder',                  [ReportController::class, 'builder']);
//         Route::post('/builder/run',             [ReportController::class, 'run']);
//         Route::post('/builder/save',            [ReportController::class, 'save']);
//         Route::get('/custom/{id}',              [ReportController::class, 'showCustom']);
//         Route::get('/custom/{id}/export/{fmt}', [ReportController::class, 'exportCustom']);
//         Route::get('/scheduled',                [ReportController::class, 'scheduled']);
//         Route::post('/scheduled',               [ReportController::class, 'createSchedule']);
//         Route::get('/kpis',                     [KPIController::class, 'index']);
//         Route::get('/export',                   [DataExportController::class, 'index']);
//         Route::post('/export',                  [DataExportController::class, 'export']);
//     });

//     Route::get('/notifications',                [NotificationController::class, 'index']);
//     Route::post('/notifications/{id}/read',     [NotificationController::class, 'markRead']);
//     Route::post('/notifications/read-all',      [NotificationController::class, 'markAllRead']);
//     Route::get('/notifications/count',          [NotificationController::class, 'count']);

//     Route::group(['prefix' => '/settings'], function () {
//         Route::get('/general',                  [SettingsController::class, 'general']);
//         Route::post('/general',                 [SettingsController::class, 'saveGeneral']);
//         Route::get('/company',                  [SettingsController::class, 'company']);
//         Route::post('/company',                 [SettingsController::class, 'saveCompany']);
//         Route::get('/users',                    [UserManagementController::class, 'index']);
//         Route::get('/users/create',             [UserManagementController::class, 'create']);
//         Route::post('/users',                   [UserManagementController::class, 'store']);
//         Route::post('/users/{id}',              [UserManagementController::class, 'update']);
//         Route::post('/users/{id}/toggle',       [UserManagementController::class, 'toggle']);
//         Route::get('/roles',                    [RoleController::class, 'index']);
//         Route::post('/roles/{id}/permissions',  [RoleController::class, 'savePermissions']);
//         Route::get('/integrations',             [IntegrationController::class, 'index']);
//         Route::post('/integrations/{key}',      [IntegrationController::class, 'save']);
//         Route::get('/integrations/{key}/test',  [IntegrationController::class, 'test']);
//         Route::get('/workflows',                [WorkflowController::class, 'index']);
//         Route::post('/workflows',               [WorkflowController::class, 'store']);
//         Route::post('/workflows/{id}',          [WorkflowController::class, 'update']);
//         Route::get('/audit-log',                [AuditController::class, 'index']);
//         Route::get('/audit-log/export',         [AuditController::class, 'export']);
//         Route::get('/backup',                   [BackupController::class, 'index']);
//         Route::post('/backup/run',              [BackupController::class, 'run']);
//         Route::get('/backup/{file}/download',   [BackupController::class, 'download']);
//     });
// });