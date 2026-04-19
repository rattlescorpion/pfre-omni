# Copilot Instructions

This repository is a Laravel 10 / PHP 8.4 enterprise real-estate platform with a multi-tenant modular architecture. Keep changes focused, preserve proprietary semantics, and avoid broad refactoring without a clear request.

## Use these references first
- `AGENTS.md` — repository-specific agent guidance and conventions
- `README.md` — setup, environment configuration, module description, and deployment notes
- `.github/agents/code-review.agent.md` — code review agent for Laravel/PHP reviews
- `.github/agents/deployment.agent.md` — GitHub Actions workflow and CI/CD guidance
- `.github/agents/frontend.agent.md` — Vue/Blade/Tailwind UI/UX development
- `.github/agents/testing.agent.md` — PHPUnit and Pest testing guidance
- `.github/agents/database.agent.md` — database migrations, seeding, and schema design
- `.github/agents/module.agent.md` — domain-specific feature development
- `.github/agents/environment.agent.md` — local environment setup and initialization
- `.github/workflows/deploy.yml` — deployment workflow reference

## Key commands
- `composer install --no-interaction --prefer-dist --optimize-autoloader`
- `npm install`
- `npm run build`
- `composer test`
- `composer run-script analyse`

## Important guidance
- Prefer domain service classes in `app/Services/` over adding business logic directly to controllers.
- Keep REST API changes aligned with `routes/api.php`.
- Preserve `.env.example` and do not hardcode configuration values.
- Do not modify `.github/workflows/deploy.yml` or deployment controls unless the user explicitly asks.
- This repo uses proprietary integrations and Indian compliance flows; avoid assuming external services not configured here.

## When editing
- Validate behavior with tests where possible.
- Use `storage/app/documents/` and `storage/app/reports/` when reasoning about file storage.
- Avoid introducing new package dependencies unless explicitly requested.
