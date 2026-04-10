<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for the backup package.
    | You can find more information at https://spatie.be/docs/laravel-backup
    |
    */

    'backup' => [

        /*
         * The name of this application. You can use this name to monitor
         * the backups.
         */
        'name' => env('APP_NAME', 'PFRE OMNI'),

        'source' => [

            'files' => [

                /*
                 * The list of directories and files that will be included in the backup.
                 */
                'include' => [
                    base_path(),
                ],

                /*
                 * These directories and files will be excluded from the backup.
                 *
                 * Directories used by the backup process will automatically be excluded.
                 */
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    base_path('.git'),
                    base_path('storage/app'),
                    base_path('storage/framework/cache'),
                    base_path('storage/framework/sessions'),
                    base_path('storage/framework/views'),
                    base_path('storage/logs'),
                    base_path('.env'),
                    base_path('tests'),
                ],

                /*
                 * Determines if symlinks should be followed.
                 */
                'follow_links' => false,

                /*
                 * Determines if it should avoid unreadable folders.
                 */
                'ignore_unreadable_directories' => true,
            ],

            /*
             * The names of the connections to the databases that should be backed up
             * MySQL, PostgreSQL, SQLite and Mongo databases are supported.
             *
             * The content will be dumped to a .sql, .gzip or .sqlite file and
             * stored in the `database` key.
             */
            'databases' => [
                'mysql',
            ],
        ],

        /*
         * The database dump can be compressed to decrease disk usage.
         *
         * Out of the box Laravel-backup supplies
         * Spatie\DbDumper\Compressors\GzipCompressor::class.
         *
         * You can also create custom compressor. More info on that here:
         * https://spatie.be/docs/laravel-backup/v6/advanced-usage/custom-compressors
         */
        'database_dump_compressor' => Spatie\DbDumper\Compressors\GzipCompressor::class,

        'database_dump_file_extension' => '',

        'destination' => [

            /*
             * The filename prefix used for the backup zip file.
             */
            'filename_prefix' => 'pfre-omni-backup-',

            /*
             * The disk names on which the backups will be stored.
             */
            'disks' => [
                'local',
                's3', // Requires AWS S3 configuration
            ],
        ],

        /*
         * The directory where the temporary files will be stored.
         */
        'temporary_directory' => storage_path('app/backup-temp'),

        /*
         * The password to be used for encrypting the backup zip file.
         * Set to null to disable encryption.
         */
        'password' => env('BACKUP_PASSWORD'),

        /*
         * The encryption algorithm to be used for encrypting the backup zip file.
         * You can find the available algorithms at https://www.php.net/manual/en/function.sodium-crypto-secretbox.php
         */
        'encryption' => 'default',

        /*
         * The number of days that backups must be kept.
         */
        'cleanup_strategy' => [

            Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class => [
                /*
                 * The number of days that all backups must be kept.
                 */
                'keep_all_backups_for_days' => 7,

                /*
                 * The number of days that daily backups must be kept.
                 */
                'keep_daily_backups_for_days' => 16,

                /*
                 * The number of weeks that weekly backups must be kept.
                 */
                'keep_weekly_backups_for_weeks' => 8,

                /*
                 * The number of months that monthly backups must be kept.
                 */
                'keep_monthly_backups_for_months' => 4,

                /*
                 * The number of years that yearly backups must be kept.
                 */
                'keep_yearly_backups_for_years' => 2,

                /*
                 * After cleaning up the backups, remove the oldest backup until
                 * this amount of megabytes is reached.
                 */
                'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
            ],
        ],

        /*
         * Here you can specify which backups should be monitored.
         * If a backup does not meet the specified requirements, the
         * UnHealthyBackupWasFound event will be fired.
         */
        'monitor_backups' => [
            [
                'name' => env('APP_NAME', 'PFRE OMNI'),
                'disks' => ['local'],
                'health_checks' => [
                    Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                    Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
                ],
            ],
        ],

        'notifications' => [

            /*
             * This class will be used to send all notifications.
             */
            'handler' => Spatie\Backup\Notifications\Notifier::class,

            /*
             * Here you can specify the ways you want to be notified when certain
             * events take place. Possible values are: 'log', 'mail', 'slack'.
             *
             * Slack requires the installation of the maknz/slack package.
             */
            'channels' => ['log'],

            /*
             * Here you can specify how emails should be sent.
             */
            'mail' => [
                'to' => env('BACKUP_NOTIFICATION_EMAIL'),
                'from' => env('MAIL_FROM_ADDRESS'),
            ],

            /*
             * Here you can specify how messages should be sent to Slack.
             */
            'slack' => [
                'webhook_url' => env('BACKUP_SLACK_WEBHOOK'),
                'channel' => env('BACKUP_SLACK_CHANNEL'),
                'username' => env('BACKUP_SLACK_USERNAME'),
                'icon' => env('BACKUP_SLACK_ICON'),
            ],
        ],

        /*
         * Here you can specify which events should be fired.
         */
        'events' => [

            /*
             * The event that will fire when a backup fails.
             */
            'backup_failed' => Spatie\Backup\Events\BackupWasSuccessful::class,

            /*
             * The event that will fire when a backup succeeds.
             */
            'backup_successful' => Spatie\Backup\Events\BackupWasSuccessful::class,
        ],
    ],
];