# PFRE-Omni — Property Finder Real Estate Omni Platform

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo" />
</p>

<p align="center">
  <a href="https://github.com/rattlescorpion/pfre-omni/actions">
    <img src="https://github.com/rattlescorpion/pfre-omni/workflows/CI/badge.svg" alt="CI Status" />
  </a>
  <img src="https://img.shields.io/badge/PHP-8.4-blue?logo=php" alt="PHP 8.4" />
  <img src="https://img.shields.io/badge/Laravel-10.x-red?logo=laravel" alt="Laravel 10" />
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql" alt="MySQL 8.0" />
  <img src="https://img.shields.io/badge/Redis-7.x-red?logo=redis" alt="Redis" />
  <img src="https://img.shields.io/badge/license-Proprietary-lightgrey" alt="License" />
</p>

> Enterprise-grade real estate operations platform covering CRM, sales, bookings, finance, GST/RERA compliance, HRMS, payroll, procurement, facilities management, and white-label verticals — built for the Indian market.

---

## Table of Contents

- [Overview](#overview)
- [Platform Architecture](#platform-architecture)
- [Module Clusters](#module-clusters)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Queue & Horizon](#queue--horizon)
- [Indian Compliance](#indian-compliance)
- [Key Integrations](#key-integrations)
- [Scheduled Jobs](#scheduled-jobs)
- [Testing](#testing)
- [Docker Setup](#docker-setup)
- [Deployment](#deployment)
- [Security](#security)
- [Contributing](#contributing)
- [Claude Skills](#claude-skills)
- [License](#license)

---

## Overview

PFRE-Omni is a multi-tenant, modular SaaS platform designed for real estate developers, brokers, and property management companies operating in India. It unifies 306 functional modules across 26 domain clusters into a single, cohesive application.

The platform handles the full lifecycle of a real estate business — from lead capture and site visits through booking, agreement, construction-linked payment plans, possession, society formation, and post-possession facility management — while maintaining full compliance with Indian regulations including GST, MahaRERA, RERA, TDS, PF/ESIC, and Maharashtra Stamp Duty.

---

## Platform Architecture

```text
pfre-omni/
├── app/
│   ├── Console/          # 141 scheduled Artisan commands
│   ├── Http/
│   │   ├── Controllers/  # 306 module controllers
│   │   └── Middleware/   # Auth, tenant, rate-limit, audit
│   ├── Models/           # Eloquent models per domain
│   ├── Services/         # Domain service classes (16+ domains)
│   ├── Jobs/             # Queued jobs (Horizon-managed)
│   ├── Events/           # Domain events
│   ├── Listeners/        # Event listeners
│   ├── Helpers/
│   │   └── IndiaHelpers.php   # GST, PAN, Aadhaar, IFSC utilities
│   └── Providers/        # Service provider bootstrap
├── config/               # Per-module configuration files
├── database/
│   ├── migrations/       # Schema migrations (real estate extensions)
│   └── seeders/          # Pre-seeded dashboard widgets, report templates
├── resources/
│   └── views/            # Blade templates
├── routes/
│   ├── web.php
│   ├── api.php           # 1,700+ REST endpoints
│   └── channels.php
├── storage/
│   ├── app/documents/    # Agreements, invoices, RERA filings
│   └── app/reports/      # Generated PDF/XLSX reports
└── tests/
    ├── Unit/
    └── Feature/
```

## Module Clusters

| # | Cluster | Modules |
|---|---------|---------|
| 1 | **CRM & Lead Management** | Leads, Follow-ups, Site Visits, Source Tracking |
| 2 | **Sales & Booking** | Unit Inventory, Bookings, Allotments, Cancellations |
| 3 | **Finance & Billing** | Payment Plans, Receipts, Demand Letters, Ledger |
| 4 | **GST & e-Invoice** | GSTR-1/3B, IRN Generation, e-Way Bill, HSN/SAC |
| 5 | **RERA / MahaRERA** | Project Registration, Quarterly Updates, Complaints |
| 6 | **Legal & Agreements** | Sale Agreements, Stamp Duty, Registration |
| 7 | **HRMS** | Employees, Attendance, Leave, Appraisals |
| 8 | **Payroll** | Salary Processing, PF/ESIC/PT/TDS, Pay Slips |
| 9 | **Procurement** | Purchase Orders, Vendors, GRN, Approvals |
| 10 | **Facility Management** | AMC, Maintenance Tickets, Housekeeping |
| 11 | **Society / HOA** | Society Formation, Maintenance Billing, NOC |
| 12 | **White-Label: Clinic** | OPD/IPD, Appointments, Prescriptions |
| 13 | **White-Label: School** | Admissions, Fees, Timetable, Results |
| 14 | **White-Label: Hotel** | Room Inventory, Bookings, OTA Channel Manager |
| 15 | **White-Label: Retail/POS** | Inventory, Billing, GST POS |
| 16 | **Warehouse & Logistics** | Stock, Dispatch, Delivery Tracking |
| 17 | **ITSM** | Helpdesk, SLA, Asset Management |
| 18 | **Agriculture** | Land Records, MSP Rates, Crop Tracking |
| 19 | **Analytics & BI** | Dashboards, KPIs, Report Builder |
| 20 | **Notifications** | WhatsApp, SMS, Email, Push (FCM) |
| 21 | **Document Management** | DMS, eSign, Version Control |
| 22 | **Configuration & Settings** | Multi-tenant, Roles, Permissions |
| 23–26 | *(Infrastructure clusters)* | BBPS, MCA, Audit, Integrations |

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 10.x (PHP 8.4) |
| **Database** | MySQL 8.0 |
| **Cache** | Redis 7.x |
| **Queue** | Laravel Horizon (Redis) |
| **Search** | Meilisearch (via Laravel Scout) |
| **Storage** | AWS S3 / Wasabi (ap-south-1) |
| **Auth** | Laravel Sanctum + JWT |
| **Roles** | Spatie Laravel Permission |
| **Payments** | Razorpay |
| **Messaging** | WhatsApp Cloud API (Meta v21.0), MSG91 |
| **PDF** | barryvdh/laravel-dompdf |
| **Excel** | Maatwebsite/Excel |
| **Monitoring** | Laravel Telescope + Horizon dashboard |
| **Broadcasting** | Pusher (cluster: ap2, Mumbai) |
| **CI/CD** | GitHub Actions |
| **Containers** | Docker + Docker Compose |

## Requirements

- PHP >= 8.4 with extensions: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `zip`
- MySQL >= 8.0
- Redis >= 7.0
- Node.js >= 18.x + npm
- Composer >= 2.6
- Meilisearch (optional, for full-text property search)

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/rattlescorpion/pfre-omni.git
cd pfre-omni

# 2. Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# 3. Install Node dependencies and build assets
npm install && npm run build

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Run migrations and seeders
php artisan migrate --seed

# 6. Publish vendor assets
php artisan vendor:publish --tag=laravel-assets --ansi

# 7. Link storage
php artisan storage:link

# 8. Start local development server
php artisan serve
```

## Environment Configuration

Copy `.env.example` to `.env` and configure the following sections:

- **Database** — MySQL credentials and `pfre_omni` database
- **Redis** — Separate DBs for cache (1), session (2), and queue (3)
- **Razorpay** — Payment gateway keys
- **WhatsApp Cloud API** — Meta Phone Number ID and Access Token
- **GST / IRP** — GSTIN, Client credentials for e-Invoice
- **MahaRERA** — API key for RERA project sync
- **Aadhaar eSign** — ASP credentials
- **CIBIL** — Member ID and API endpoint
- **Google Maps** — Geocoding and Maps API key
- **MSG91** — SMS OTP and transactional messaging
- **AWS / Wasabi** — S3 bucket in `ap-south-1` for document storage

Refer to `.env.example` for the full list of required variables with inline documentation.

## Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE pfre_omni CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run all migrations
php artisan migrate

# Seed reference data (roles, permissions, config, dashboard widgets)
php artisan db:seed

# Seed specific module (example)
php artisan db:seed --class=GstRateSeeder
```

## Queue & Horizon

PFRE-Omni uses Laravel Horizon to manage all background job processing.

```bash
# Start Horizon (development)
php artisan horizon

# Access Horizon dashboard
# http://localhost/horizon  (restricted to admin roles)
```

Queue workers are split by priority:

| Queue | Purpose |
|-------|---------|
| `critical` | Payment confirmations, IRN generation |
| `high` | WhatsApp/SMS dispatch, PDF generation |
| `default` | Report generation, email |
| `low` | Audit logs, analytics aggregation |

## Indian Compliance

The platform includes built-in helpers in `app/Helpers/IndiaHelpers.php` for:

- **GST** — GSTIN validation, HSN/SAC lookup, GSTR filing prep
- **e-Invoice (IRN)** — IRP API integration for B2B invoices above ₹5 Cr threshold
- **MahaRERA** — Project registration sync and quarterly reporting
- **TDS** — Section 194IA (property purchase), 194C, 194J calculation
- **PF / ESIC / PT** — Payroll deduction computation
- **Maharashtra Stamp Duty** — Ready Reckoner rate lookup
- **PAN / Aadhaar** — Format validation
- **IFSC** — Bank branch resolution

## Key Integrations

| Integration | Purpose | Package/Method |
|-------------|---------|----------------|
| Razorpay | Payment collection, refunds | `razorpay/razorpay` |
| Meta WhatsApp Cloud API | Customer messaging, templates | `netflie/whatsapp-cloud-api` |
| MSG91 | SMS OTP, transactional SMS | HTTP via Guzzle |
| GSTN / IRP | e-Invoice IRN generation | HTTP via Guzzle |
| Aadhaar eSign | Digital agreement signing | HTTP via Guzzle |
| CIBIL | Buyer credit check | HTTP via Guzzle |
| Google Maps | Property geocoding, site mapping | `googlemaps/google-maps-services-php` |
| OTA Channel Manager | Hotel module room distribution | Configurable provider |
| FCM | Mobile push notifications | `laravel-notification-channels/fcm` |
| AWS S3 / Wasabi | Document and media storage | `league/flysystem-aws-s3-v3` |
| Meilisearch | Full-text property search | `laravel/scout` |

## Scheduled Jobs

141 cron jobs are registered in `app/Console/Kernel.php`, covering:

- Daily GST reconciliation and GSTR-1 auto-draft
- MahaRERA quarterly report generation
- TDS calculation and challan preparation
- Payment demand letter dispatch
- Site visit follow-up reminders (WhatsApp + SMS)
- Payroll processing triggers
- Backup to S3 (daily, weekly, monthly)
- Horizon snapshot metrics
- Expired booking auto-cancellation
- OTA availability sync (hotel module)

Start the scheduler:

```bash
# Add to server crontab
* * * * * cd /path/to/pfre-omni && php artisan schedule:run >> /dev/null 2>&1
```

## Testing

```bash
# Run full test suite
php artisan test

# Run with coverage
php artisan test --coverage --min=80

# Run a specific module's tests
php artisan test --filter=CrmTest

# Run only unit tests
php artisan test --testsuite=Unit
```

## Docker Setup

```bash
# Build and start all services
docker-compose up -d --build

# Services started:
#   app       → PHP-FPM 8.4 (port 9000)
#   nginx     → Nginx (port 80, 443)
#   mysql     → MySQL 8.0 (port 3306)
#   redis     → Redis 7 (port 6379)
#   horizon   → Queue worker
#   scheduler → Cron scheduler
#   meilisearch → Search engine (port 7700)

# Run migrations inside container
docker-compose exec app php artisan migrate --seed
```

## Deployment

```bash
# Production deployment checklist
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize

# Set production env flags
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning
TELESCOPE_ENABLED=false   # Disable Telescope in production or restrict by IP
```

Deployment is automated via GitHub Actions (`.github/workflows/deploy.yml`) on push to the `main` branch. The pipeline runs: lint → test → build → deploy with zero-downtime rolling restart.

## Security

Security vulnerabilities in this platform should be reported **privately** to:

**Email:** rattlescorpion@gmail.com

Do not open public GitHub Issues for security vulnerabilities. All reports are acknowledged within 48 hours and patched within 7 business days.

## Contributing

This is a proprietary internal platform. External contributions are not accepted. For internal team members:

1. Branch from `develop` — never commit directly to `main`
2. Follow PSR-12 coding standards (enforced by Laravel Pint)
3. Write feature tests for every new module
4. All PRs require review from at least one senior developer
5. Run `php artisan test` and `./vendor/bin/pint` before raising a PR

## Claude Skills

This workspace includes local Claude prompt customization files under `.claude/skills/`.

- `create-skill` — Generate a reusable skill definition file from a workflow, conversation, or prompt customization request.

Example: create `.claude/skills/my-skill.md`, add frontmatter with `name` and `description`, then write the workflow guidance and example prompts.

If you add new workspace skills, place them in `.claude/skills/` with frontmatter, concise guidance, activation conditions, and example prompts.

## License

**Proprietary — All Rights Reserved.**

Copyright © 2024–2026 Rattlescorpion. Unauthorised copying, distribution, or use of this software is strictly prohibited.
