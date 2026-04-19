# AI Customization Guide

## Overview

This repository has been configured with comprehensive AI agent guidance to help coding assistants (Copilot, Claude, Cursor, Windsurf, etc.) understand the codebase and be immediately productive. All customization files link to specialized agents for different development tasks.

## Customization Files

### Primary Guidance
- **`.github/copilot-instructions.md`** — High-level AI instructions for GitHub Copilot, links to all agents and key references
- **`AGENTS.md`** — Repository-specific agent guidance, architecture overview, and conventions

### Cross-Editor Rules
- **`.windsurfrules`** — Guidelines for Windsurf editor, with quick reference and specialized agent links
- **`.cursor`** — Guidelines for Cursor editor, with development dos/don'ts and domain cluster reference

## Specialized Agents

All agents reside in `.github/agents/` and provide detailed guidance for specific development tasks.

### 1. Code Review Agent
**File**: `.github/agents/code-review.agent.md`

**Purpose**: Review Laravel/PHP code for best practices, security vulnerabilities, performance issues, and Laravel conventions.

**Use When**:
- Reviewing service classes, controllers, models
- Checking for SQL injection, XSS, authentication/authorization issues
- Validating performance and design patterns
- Running test coverage checks

**Key Outputs**:
- Strengths and issues identified
- Severity levels (critical, major, minor)
- Actionable recommendations with code examples
- Test results and coverage metrics

---

### 2. Deployment Agent
**File**: `.github/agents/deployment.agent.md`

**Purpose**: Analyze and validate GitHub Actions CI/CD workflow files, build caching, and deployment pipeline safety.

**Use When**:
- Reviewing `.github/workflows/deploy.yml` for correctness
- Validating build steps, caching, and artifact handling
- Ensuring deployment placeholders are not promoted as production
- Recommending workflow stability and security improvements

**Key Outputs**:
- Workflow structure and trigger validation
- Command alignment with `composer.json` and `package.json`
- Deployment command verification
- Recommendations for caching, security, and test runs

---

### 3. Frontend Agent
**File**: `.github/agents/frontend.agent.md`

**Purpose**: Build and review Blade templates, Vue 5 components, and Tailwind CSS styling for responsive, accessible UI.

**Use When**:
- Creating or modifying Blade templates in `resources/views/`
- Building Vue components or Alpine.js interactivity
- Styling with Tailwind CSS 3.4 utilities
- Integrating Chart.js, FullCalendar, DataTables, Leaflet, Select2

**Key Outputs**:
- Component structure and template hierarchy
- Tailwind utilities and custom CSS definitions
- Alpine.js and Vue directive explanations
- WCAG 2.1 accessibility notes
- Responsive design validation for mobile/tablet/desktop

---

### 4. Testing Agent
**File**: `.github/agents/testing.agent.md`

**Purpose**: Create and review unit and feature tests using PHPUnit and Pest, ensuring high coverage and business logic validation.

**Use When**:
- Writing unit tests for services, helpers, validation rules
- Writing feature tests for API endpoints and workflows
- Mocking external dependencies and services
- Validating edge cases and error handling

**Key Outputs**:
- Test type and coverage summary
- Assertions and edge case validations
- Mocking strategy and dependencies
- Test run commands and results

---

### 5. Database Agent
**File**: `.github/agents/database.agent.md`

**Purpose**: Design migrations, Eloquent models, seeders, and schema patterns aligned with real-estate domain models.

**Use When**:
- Creating or modifying database migrations
- Designing new tables and relationships
- Writing seeders for reference data
- Validating foreign keys, indexes, and data types

**Key Outputs**:
- Schema design with tables, columns, and constraints
- Foreign key relationships and cascading rules
- Indexes for performance-critical columns
- Migration commands and seeding strategies

---

### 6. Module/Domain Agent
**File**: `.github/agents/module.agent.md`

**Purpose**: Develop domain-specific features across 26 clusters (CRM, HRMS, Finance, GST/RERA, etc.) with Indian compliance.

**Use When**:
- Building CRM, sales, finance, HRMS, or other domain modules
- Implementing GST, RERA, stamp duty, or payroll calculations
- Creating API endpoints for domain workflows
- Integrating third-party services (Razorpay, WhatsApp, MahaRERA)

**Domain Clusters Covered**:
1. CRM & Lead Management
2. Sales & Booking
3. Finance & Billing
4. GST & e-Invoice
5. RERA / MahaRERA
6. Legal & Agreements
7. HRMS
8. Payroll
9. Procurement
10. Facility Management
11. Society / HOA
12–15. White-Label Verticals (Clinic, School, Hotel, Retail)
16. Warehouse & Logistics
17. ITSM
18. Agriculture
19. Analytics & BI
20. Notifications
21. Document Management
22. Configuration & Settings
23–26. Infrastructure (BBPS, MCA, Audit, Integrations)

**Key Outputs**:
- Domain cluster assignment
- Database schema and relationships
- Service logic and business rules
- API endpoint design (routes, methods, request/response)
- Compliance considerations (GST, RERA, TDS, audit)
- Integration points with external APIs

---

### 7. Environment Agent
**File**: `.github/agents/environment.agent.md`

**Purpose**: Scaffold and initialize local development environment, ensuring all dependencies and services are ready.

**Use When**:
- Setting up PFRE-Omni for the first time
- Configuring MySQL, Redis, and optional Meilisearch
- Installing PHP, Node.js, Composer dependencies
- Troubleshooting environment issues

**Installation Steps Provided**:
1. Clone and install PHP dependencies
2. Environment setup (`.env` copy and key generation)
3. Configure `.env` for database, Redis, mail, storage, integrations
4. Database setup (create, migrate, seed)
5. Frontend assets (npm install, build)
6. Storage linking and service startup

**Key Outputs**:
- Installed package versions
- Service status (MySQL, Redis, Meilisearch)
- Environment variable confirmation
- Database migration and seeding status
- Frontend compilation status
- Troubleshooting table for common issues

---

## Architecture Reference

### Directory Structure
```
app/
  ├── Console/           # 141 scheduled Artisan commands
  ├── Http/Controllers/  # 306 module controllers
  ├── Models/            # Eloquent models per domain
  ├── Services/          # Domain service classes (preferred for business logic)
  ├── Jobs/              # Queued background jobs
  ├── Events/            # Domain events
  ├── Listeners/         # Event listeners
  ├── Helpers/           # Helper functions (IndiaHelpers.php for GST, PAN, etc.)
  └── Providers/         # Service provider bootstrap
database/
  ├── migrations/        # Schema migrations (real-estate extensions)
  └── seeders/           # Reference data seeders
resources/
  ├── views/             # Blade templates organized by domain
  ├── js/                # Vue 5 and Alpine.js components
  └── css/               # Tailwind CSS and custom styles
routes/
  ├── api.php            # 1,700+ REST endpoints
  ├── web.php            # Web routes
  └── channels.php       # Broadcasting channels
config/
  ├── pfre.php           # Domain-specific settings
  ├── integrations.php   # Third-party API configuration
  ├── whatsapp.php       # WhatsApp Cloud API
  └── razorpay.php       # Razorpay payment gateway
tests/
  ├── Unit/              # Unit tests
  └── Feature/           # Feature tests
storage/
  ├── app/documents/     # Agreements, invoices, RERA filings
  └── app/reports/       # Generated PDF/XLSX reports
```

### Tech Stack
| Layer | Technology |
|-------|-----------|
| Framework | Laravel 10.x (PHP 8.4) |
| Database | MySQL 8.0 |
| Cache | Redis 7.x |
| Queue | Laravel Horizon (Redis) |
| Search | Meilisearch (via Laravel Scout) |
| Auth | Laravel Sanctum + JWT |
| Roles | Spatie Laravel Permission |
| Payments | Razorpay |
| Messaging | WhatsApp Cloud API, MSG91 |
| PDF | barryvdh/laravel-dompdf |
| Excel | Maatwebsite/Excel |
| Frontend | Blade + Vue 5 + Alpine.js + Tailwind CSS 3.4 |
| Build | Vite 5 + npm |
| CI/CD | GitHub Actions |
| Containers | Docker + Docker Compose |

---

## Key Commands

### Development
```bash
php artisan serve                           # Start dev server
npm run dev                                 # Frontend hot reload
php artisan horizon                         # Start queue worker
php artisan tinker                          # Interactive shell
```

### Testing & Analysis
```bash
composer test                               # Run PHPUnit tests
composer test-parallel                      # Run tests in parallel (4 processes)
composer run-script analyse                 # PHPStan static analysis
npm run build                               # Build frontend assets
```

### Database
```bash
php artisan migrate                         # Run migrations
php artisan migrate:rollback                # Rollback last migration batch
php artisan db:seed                         # Run all seeders
php artisan db:seed --class=GstRateSeeder  # Run specific seeder
```

### Maintenance
```bash
php artisan config:cache                    # Cache configuration
php artisan route:cache                     # Cache routes
php artisan storage:link                    # Link storage directory
php artisan backup:run                      # Run backup
```

---

## Development Conventions

### Prefer Service Classes
- Place business logic in `app/Services/{Domain}/` 
- Controllers should delegate to services
- Avoid fat controllers with complex logic

### REST API Alignment
- Keep new routes consistent with `routes/api.php` patterns
- Use standard HTTP methods (GET, POST, PUT, DELETE)
- Organize routes by domain prefix (`/api/crm/*`, `/api/hrms/*`)

### Configuration & Secrets
- Never hardcode configuration; use `.env` and `config/`
- Preserve `.env.example` structure for team setup
- Reference `config/pfre.php`, `config/integrations.php`

### Testing & Validation
- Write tests before or alongside new features
- Use `composer test` and `composer run-script analyse` before committing
- Aim for high coverage of business logic

### File Storage
- Generated documents: `storage/app/documents/`
- Generated reports: `storage/app/reports/`
- Always link storage: `php artisan storage:link`

### Dependency Management
- Avoid adding new packages without explicit request
- Check `composer.json` and `package.json` for existing solutions
- Confirm compatibility with PHP 8.4 and Laravel 10.x

---

## Indian Compliance & Real-Estate Domain

### GST (Goods and Services Tax)
- Validate GSTIN format and structure
- Map items to HSN (Harmonized System of Nomenclature) or SAC (Service Accounting Code)
- Calculate IGST (Integrated GST), CGST (Central GST), SGST (State GST)
- Generate GSTR-1 and GSTR-3B returns

### RERA (Real Estate Regulation Act) / MahaRERA
- Track project registration and milestones
- Quarterly progress update submission
- Complaint resolution and tracking
- Regulatory compliance auditing

### Stamp Duty & Registration
- Calculate based on property value, state rules, and agreement type
- Generate agreement documents for e-registration

### Payroll & PF/ESIC/TDS
- Automatic salary slip generation
- PF (Provident Fund) and ESIC (Employee State Insurance) calculations
- TDS (Tax Deducted at Source) withholding
- Regional statutory compliance (Maharashtra, Gujarat, etc.)

### Multi-Tenant Isolation
- White-label support for Clinic, School, Hotel, Retail/POS
- Separate data per tenant
- Shared infrastructure with tenant-scoped queries

---

## When to Use Each Agent

| Task | Agent | Command/Reference |
|------|-------|-------------------|
| Code review for quality/security | code-review | `.github/agents/code-review.agent.md` |
| GitHub Actions workflow changes | deployment | `.github/agents/deployment.agent.md` |
| Blade/Vue/Tailwind UI development | frontend | `.github/agents/frontend.agent.md` |
| PHPUnit/Pest test creation | testing | `.github/agents/testing.agent.md` |
| Database migrations & schema | database | `.github/agents/database.agent.md` |
| CRM, HRMS, Finance, GST, etc. | module | `.github/agents/module.agent.md` |
| Environment setup & troubleshooting | environment | `.github/agents/environment.agent.md` |

---

## Getting Started

### For New Team Members
1. Read this guide and `README.md`
2. Follow `.github/agents/environment.agent.md` for local setup
3. Review the domain cluster relevant to your work
4. Reference the appropriate agent before starting

### For New Features
1. Identify the domain cluster (CRM, Finance, HRMS, etc.)
2. Use `.github/agents/module.agent.md` for requirements
3. Use `.github/agents/database.agent.md` for schema design
4. Use `.github/agents/frontend.agent.md` for UI
5. Use `.github/agents/testing.agent.md` for test coverage
6. Run `composer test` and `composer run-script analyse` before committing

### For Code Reviews
1. Use `.github/agents/code-review.agent.md` for Laravel/PHP review standards
2. Use `.github/agents/deployment.agent.md` for workflow/CI changes
3. Verify tests pass: `composer test`
4. Check analysis: `composer run-script analyse`

---

## Additional Resources

- **README.md** — Full installation, environment configuration, module descriptions, deployment notes
- **AGENTS.md** — Concise agent reference and architecture overview
- **.github/copilot-instructions.md** — Copilot-specific instructions
- **.windsurfrules** — Windsurf editor guidelines
- **.cursor** — Cursor editor guidelines
- **composer.json** — PHP package and script references
- **package.json** — Frontend build tools and dependencies
- **phpunit.xml** — Test configuration
- **vite.config.js** — Frontend asset build configuration
- **config/pfre.php** — Domain-specific settings and platform configuration
- **config/integrations.php** — Third-party API endpoints

---

## Questions & Feedback

If you have questions about using these agents or the repository conventions, refer to:
- The relevant agent file in `.github/agents/`
- `README.md` for setup and deployment
- `AGENTS.md` for concise architecture reference
- Existing code and tests in the repository for patterns
