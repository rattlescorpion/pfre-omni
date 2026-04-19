# AGENTS

## Purpose
This file helps AI coding agents understand the PFRE-Omni repository, avoid unnecessary investigation, and act effectively when making backend or cross-cutting changes.

## Project summary
- Laravel 10 application on PHP 8.4.
- Enterprise-grade Indian real-estate ERP/CRM platform with multi-tenant, modular architecture.
- Backend API and administrative UI in one repo.
- Key domains: CRM, sales, finance, GST/RERA compliance, HRMS, payroll, procurement, facility management, white-label verticals.

## Primary entry points
- `composer install --no-interaction --prefer-dist --optimize-autoloader`
- `npm install`
- `npm run build`
- `php artisan migrate --seed`
- `php artisan storage:link`
- `php artisan serve`

## Test and analysis commands
- `composer test` → `phpunit --testdox`
- `composer run-script analyse` → `phpstan analyse --memory-limit=2G`
- `npm run dev` / `npm run build`

## Architecture overview
- `app/Console/` contains scheduled commands and job registration.
- `app/Http/Controllers/` exposes the API and web endpoints.
- `app/Models/` contains Eloquent models.
- `app/Services/` holds domain service classes used by controllers and jobs.
- `app/Jobs/`, `app/Events/`, `app/Listeners/` implement async and event-driven behavior.
- `routes/api.php` and `routes/web.php` define HTTP routes.
- `config/` holds integration and module configuration.
- `database/migrations/` and `database/seeders/` manage schema and seeded reference data.

## Conventions for agents
- Prefer existing service classes in `app/Services/` over adding business logic directly to controllers.
- Keep route changes consistent with REST-style API patterns in `routes/api.php`.
- Avoid global refactors unless the change is clearly required and covered by tests.
- Presume tenant, auth, audit, and rate-limit middleware are important for production behavior.
- `laravel/telescope` is intentionally disabled from auto-discovery in `composer.json`.

## Files to inspect first
- `README.md` for setup, environment variables, and deployment notes.
- `composer.json` for package, script, and PHP version constraints.
- `package.json` for frontend build tools.
- `app/Console/Kernel.php` for scheduled jobs and command registration.
- `config/pfre.php`, `config/whatsapp.php`, `config/razorpay.php` for integration keys and platform settings.
- `routes/api.php` for the main API surface.
- `tests/Feature/` and `tests/Unit/` for existing test patterns.
- `.github/workflows/deploy.yml` for the GitHub Actions deployment pipeline.
- `.github/agents/code-review.agent.md` for the repository's Laravel/PHP code review agent.
- `.github/agents/deployment.agent.md` for workflow and CI/CD review guidance.
- `.github/agents/frontend.agent.md` for Vue/Blade/Tailwind UI development.

## Important guidance
- The codebase is proprietary: keep changes contained to this repository and do not introduce external code or public service assumptions unless explicitly asked.
- Always preserve `.env.example` semantics and note that environment setup is required for local execution.
- Use `storage/app/documents/` and `storage/app/reports/` as the generated content directories when reasoning about file storage.

## Frontend conventions (UI/UX agents)
- Blade templates live in `resources/views/`, organized by domain (CRM, HRMS, leads, properties, etc.).
- Use Tailwind CSS 3.4 utilities instead of custom CSS where possible.
- Alpine.js handles lightweight client-side interactivity; Vue 5 is available for complex components.
- Integrate with existing UI libraries: Chart.js, FullCalendar, DataTables, Leaflet, Select2.
- Build with `npm run dev` (hot reload) or `npm run build` (production).

## Link references
- `README.md` for full installation, environment configuration, and module description.
- `composer.json` for package and script conventions.
- `package.json` for frontend tooling.

---

## Recommended next customization
Consider adding a dedicated skill or hook for repository-specific tasks such as environment initialization, module-specific change guidelines, or testing patterns for Laravel/PHP.
