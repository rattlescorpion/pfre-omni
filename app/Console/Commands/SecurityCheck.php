<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Shared\EnvironmentValidator;
use App\Services\Shared\SSLValidator;
use App\Services\Shared\FileUploadValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SecurityCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:check {--fix : Attempt to fix identified issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run comprehensive security checks on the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔒 Running comprehensive security checks...');
        $this->newLine();

        $issues = [];
        $warnings = [];
        $passed = [];

        // Environment Security Check
        $this->info('📋 Checking environment security...');
        $envValidation = EnvironmentValidator::validate();
        $filePermValidation = EnvironmentValidator::checkFilePermissions();

        if (!$envValidation['valid']) {
            $issues = array_merge($issues, $envValidation['issues']);
        }
        $warnings = array_merge($warnings, $envValidation['warnings']);
        $warnings = array_merge($warnings, $filePermValidation['warnings']);

        if (empty($envValidation['issues']) && empty($envValidation['warnings'])) {
            $passed[] = 'Environment security configuration';
        }

        // SSL/TLS Security Check
        $this->info('🔐 Checking SSL/TLS configuration...');
        $sslValidation = SSLValidator::validate();

        if (!$sslValidation['valid']) {
            $issues = array_merge($issues, $sslValidation['issues']);
        }
        $warnings = array_merge($warnings, $sslValidation['warnings']);

        if (empty($sslValidation['issues']) && empty($sslValidation['warnings'])) {
            $passed[] = 'SSL/TLS configuration';
        }

        // Database Security Check
        $this->info('🗄️  Checking database security...');
        $dbIssues = $this->checkDatabaseSecurity();
        $issues = array_merge($issues, $dbIssues['issues']);
        $warnings = array_merge($warnings, $dbIssues['warnings']);

        if (empty($dbIssues['issues']) && empty($dbIssues['warnings'])) {
            $passed[] = 'Database security configuration';
        }

        // File Upload Security Check
        $this->info('📁 Checking file upload security...');
        $uploadValidation = FileUploadValidator::validateConfiguration();
        $issues = array_merge($issues, $uploadValidation['issues']);
        $warnings = array_merge($warnings, $uploadValidation['warnings']);

        if (empty($uploadValidation['issues']) && empty($uploadValidation['warnings'])) {
            $passed[] = 'File upload security configuration';
        }

        // Display Results
        $this->newLine();
        $this->info('📊 Security Check Results:');
        $this->newLine();

        // Passed checks
        if (!empty($passed)) {
            $this->info('✅ PASSED:');
            foreach ($passed as $check) {
                $this->line("   • {$check}");
            }
            $this->newLine();
        }

        // Warnings
        if (!empty($warnings)) {
            $this->warn('⚠️  WARNINGS:');
            foreach ($warnings as $warning) {
                $this->line("   • {$warning}");
            }
            $this->newLine();
        }

        // Issues
        if (!empty($issues)) {
            $this->error('❌ ISSUES:');
            foreach ($issues as $issue) {
                $this->line("   • {$issue}");
            }
            $this->newLine();
        }

        // Overall score
        $totalChecks = count($passed) + count($warnings) + count($issues);
        $score = $totalChecks > 0 ? round((count($passed) / $totalChecks) * 100) : 0;

        $this->info("🎯 Overall Security Score: {$score}%");

        // Log results
        Log::info('Security check completed', [
            'score' => $score,
            'passed' => $passed,
            'warnings' => $warnings,
            'issues' => $issues,
        ]);

        // Attempt fixes if requested
        if ($this->option('fix') && !empty($issues)) {
            $this->newLine();
            $this->info('🔧 Attempting to fix identified issues...');
            $this->attemptFixes($issues);
        }

        return empty($issues) ? 0 : 1;
    }

    /**
     * Check database security configuration
     */
    private function checkDatabaseSecurity(): array
    {
        $issues = [];
        $warnings = [];

        try {
            // Check if database connection is secure
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            if ($config['host'] === 'localhost' || $config['host'] === '127.0.0.1') {
                $warnings[] = 'Database is running on localhost - ensure proper network security';
            }

            // Check for weak passwords (basic check)
            if (isset($config['password']) && strlen($config['password']) < 12) {
                $issues[] = 'Database password is too weak (less than 12 characters)';
            }

            // Check for root user usage
            if ($config['username'] === 'root') {
                $issues[] = 'Database is using root user - use a dedicated application user';
            }

            // Check for exposed database port
            if (isset($config['port']) && $config['port'] == 3306 && !in_array($config['host'], ['localhost', '127.0.0.1'])) {
                $warnings[] = 'Default MySQL port (3306) exposed - consider using non-standard port';
            }

        } catch (\Exception $e) {
            $issues[] = 'Unable to check database security: ' . $e->getMessage();
        }

        return ['issues' => $issues, 'warnings' => $warnings];
    }

    /**
     * Attempt to fix identified security issues
     */
    private function attemptFixes(array $issues): void
    {
        foreach ($issues as $issue) {
            if (strpos($issue, 'Database password is too weak') !== false) {
                $this->warn('   • Cannot automatically fix weak database password - please update manually');
            }

            if (strpos($issue, 'Database is using root user') !== false) {
                $this->warn('   • Cannot automatically fix root user usage - please create dedicated user');
            }

            // Add more fix logic as needed
        }

        $this->info('Manual fixes may be required for some issues. Check the documentation for guidance.');
    }
}
