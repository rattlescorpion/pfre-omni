# Development Guides & Prompts

Complete reference suite for building PFRE-Omni. Each guide is self-contained but cross-linked for easy navigation.

## 📖 All Available Guides

### Frontend & UI/UX Development
| Guide | Purpose | Quick Link |
|-------|---------|-----------|
| **Main Frontend Guide** | Component building, state management, tech stack | [.prompt.md](.prompt.md) |
| **API Integration** | Forms, data fetching, error handling, async patterns | [.prompt-api-integration.md](.prompt-api-integration.md) |
| **Theme Customization** | Colors, design tokens, white-label support | [.prompt-theme-customization.md](.prompt-theme-customization.md) |
| **Component Library** | Reusable Blade components catalog with patterns | [.prompt-component-library.md](.prompt-component-library.md) |
| **Performance Audit** | Bundle optimization, Lighthouse, monitoring | [.prompt-performance-audit.md](.prompt-performance-audit.md) |
| **Accessibility** | WCAG 2.1 AA, keyboard nav, screen readers | [.prompt-accessibility.md](.prompt-accessibility.md) |
| **Frontend Index** | Quick reference map for all frontend tasks | [FRONTEND_PROMPTS_GUIDE.md](FRONTEND_PROMPTS_GUIDE.md) |

### Backend Development
| Guide | Purpose | Quick Link |
|-------|---------|-----------|
| **Backend API Design** | RESTful patterns, validation, resources, pagination | [.prompt-backend-api.md](.prompt-backend-api.md) |

### Testing & Quality Assurance
| Guide | Purpose | Quick Link |
|-------|---------|-----------|
| **Testing & QA** | Pest, PHPUnit, feature tests, mocking, coverage | [.prompt-testing-qa.md](.prompt-testing-qa.md) |

---

## 🎯 Task-Based Quick Reference

### I want to...

#### **Build a new UI component**
→ Start: [.prompt.md](.prompt.md) (Building section)
→ Check: [.prompt-component-library.md](.prompt-component-library.md) for existing components
→ Verify: [.prompt-accessibility.md](.prompt-accessibility.md) for accessibility

#### **Create a form with API submission**
→ Start: [.prompt-api-integration.md](.prompt-api-integration.md) (Form submission section)
→ Reference: [.prompt.md](.prompt.md) for component patterns
→ Test: [.prompt-testing-qa.md](.prompt-testing-qa.md) for feature tests

#### **Build a data-heavy dashboard**
→ Start: [.prompt.md](.prompt.md) (Visualization section)
→ Integrate: [.prompt-api-integration.md](.prompt-api-integration.md) (Data fetching)
→ Optimize: [.prompt-performance-audit.md](.prompt-performance-audit.md) (Charts, lazy loading)

#### **Optimize for performance**
→ Start: [.prompt-performance-audit.md](.prompt-performance-audit.md)
→ Check: [.prompt-api-integration.md](.prompt-api-integration.md) (API optimization)
→ Verify: Run Lighthouse and check bundle size

#### **Make component accessible**
→ Start: [.prompt-accessibility.md](.prompt-accessibility.md)
→ Reference: [.prompt.md](.prompt.md) (Component patterns)
→ Test: Use NVDA screen reader and keyboard navigation

#### **Customize brand colors/white-label**
→ Start: [.prompt-theme-customization.md](.prompt-theme-customization.md)
→ Reference: [.prompt.md](.prompt.md) (Design system)
→ Verify: All components support dark mode

#### **Build a REST API endpoint**
→ Start: [.prompt-backend-api.md](.prompt-backend-api.md)
→ Test: [.prompt-testing-qa.md](.prompt-testing-qa.md) (API tests)
→ Document: Include request/response examples

#### **Write tests for a feature**
→ Start: [.prompt-testing-qa.md](.prompt-testing-qa.md)
→ Reference: Existing tests in `tests/Feature/` and `tests/Unit/`
→ Run: `composer test`

#### **Debug Alpine.js state issues**
→ Start: [.prompt.md](.prompt.md) (Interactivity section)
→ Check: [.prompt-api-integration.md](.prompt-api-integration.md) (State patterns)
→ Test: [.prompt-testing-qa.md](.prompt-testing-qa.md) (Browser tests)

#### **Find or reuse existing component**
→ Start: [.prompt-component-library.md](.prompt-component-library.md) (Component catalog)
→ Browse: `resources/views/components/` directory
→ Reference: [.prompt.md](.prompt.md) for component patterns

#### **Deploy to production**
→ Pre-flight: [.prompt-performance-audit.md](.prompt-performance-audit.md) (Checklist)
→ Verify: Run full test suite with `composer test`
→ Reference: `.github/workflows/deploy.yml` for CI/CD

---

## 🏗️ Tech Stack Reference

### Frontend Stack
- **Templating**: Laravel Blade (server-side)
- **Interactivity**: Alpine.js 3.13 (lightweight), Vue 5 (complex components)
- **Styling**: Tailwind CSS 3.4 (utility-first)
- **Data Display**: Chart.js, DataTables, FullCalendar, Leaflet, Select2
- **Build Tool**: Vite 5 with laravel-vite-plugin
- **HTTP Client**: Axios

### Backend Stack
- **Framework**: Laravel 10 / PHP 8.4
- **API**: RESTful with Sanctum authentication
- **Database**: Supports multi-database, typically MySQL/PostgreSQL
- **Testing**: Pest (PHPUnit-compatible)
- **Code Analysis**: PHPStan

### Key File Locations
- **Frontend**: `resources/views/`, `resources/css/`, `resources/js/`
- **Backend**: `app/Http/Controllers/`, `app/Models/`, `app/Services/`
- **API Routes**: `routes/api.php`
- **Tests**: `tests/Feature/`, `tests/Unit/`
- **Configuration**: `config/`, `.env.example`

---

## 📋 Common Commands

### Development
```bash
# Frontend
npm run dev           # Start dev server with hot reload
npm run build        # Production build

# Backend
php artisan serve    # Start Laravel dev server
php artisan tinker   # Interactive shell

# Database
php artisan migrate --seed   # Run migrations + seeders
php artisan migrate:rollback # Rollback last migration
```

### Testing
```bash
composer test                           # Run all tests
php artisan test --filter=LeadApiTest  # Run specific test
php artisan test --watch               # Watch mode (re-run on change)
composer test -- --coverage            # Generate coverage report
```

### Code Analysis
```bash
composer run-script analyse  # PHPStan analysis
composer run-script lint     # If available
```

---

## 🔍 Architecture Overview

```
pfre-omni/
├── app/
│   ├── Http/
│   │   ├── Controllers/    (API & web endpoints)
│   │   ├── Middleware/     (authentication, authorization)
│   │   ├── Requests/       (form validation)
│   │   └── Resources/      (response transformation)
│   ├── Models/             (Eloquent models)
│   ├── Services/           (domain service classes)
│   ├── Jobs/               (async jobs)
│   ├── Events/             (event classes)
│   └── Listeners/          (event listeners)
├── database/
│   ├── migrations/         (schema changes)
│   ├── factories/          (test data factories)
│   └── seeders/            (seed data)
├── resources/
│   ├── views/              (Blade templates)
│   ├── css/                (Tailwind + design tokens)
│   └── js/                 (Alpine.js, app initialization)
├── routes/
│   ├── api.php             (API routes)
│   └── web.php             (web routes)
├── tests/
│   ├── Feature/            (integration tests)
│   ├── Unit/               (unit tests)
│   └── Pest tests
├── config/                 (configuration files)
└── storage/                (user uploads, logs, cache)
```

---

## 🚀 Before You Start

### Essential Reading (5-10 min)
1. [README.md](README.md) — Project overview and setup
2. [AGENTS.md](AGENTS.md) — Repository conventions and structure
3. This file (you're reading it!) — Guide navigation

### For Your First Feature
1. Choose your guide based on task type (see Quick Reference above)
2. Read through the relevant sections
3. Find a similar example in the codebase
4. Follow the patterns and conventions
5. Write tests as you go

### For Code Reviews
1. Check [.github/agents/code-review.agent.md](.github/agents/code-review.agent.md) for Laravel/PHP review guidelines
2. Reference appropriate guide for the code being reviewed
3. Use checklists from relevant guides (e.g., accessibility, performance)

---

## 📚 Expanded Guides

Each guide contains:
- **Overview** — Purpose and scope
- **Quick Checklist** — Essential items to verify
- **Detailed Patterns** — Code examples and best practices
- **Common Issues** — Troubleshooting and solutions
- **Related Files** — Workspace file references
- **Tools & Resources** — External references and utilities

---

## 🎓 Learning Path

### New to the Project?
1. Read [README.md](README.md) for setup
2. Browse [AGENTS.md](AGENTS.md) for conventions
3. Review [.prompt.md](.prompt.md) (Main Frontend) or [.prompt-backend-api.md](.prompt-backend-api.md)
4. Look at existing code in `resources/views/` or `app/Http/Controllers/`
5. Run `composer test` to see examples in tests

### Frontend Developer?
1. Start with [.prompt.md](.prompt.md)
2. Explore [.prompt-component-library.md](.prompt-component-library.md)
3. Deep dive: [.prompt-api-integration.md](.prompt-api-integration.md) (when building forms)
4. Reference: [.prompt-accessibility.md](.prompt-accessibility.md) (for compliance)
5. Deploy prep: [.prompt-performance-audit.md](.prompt-performance-audit.md)

### Backend Developer?
1. Start with [.prompt-backend-api.md](.prompt-backend-api.md)
2. Add tests: [.prompt-testing-qa.md](.prompt-testing-qa.md)
3. Reference: [AGENTS.md](AGENTS.md) for conventions
4. Deploy: Check `.github/workflows/deploy.yml` for CI/CD

### QA / Testing?
1. Start with [.prompt-testing-qa.md](.prompt-testing-qa.md)
2. Reference: [.prompt-accessibility.md](.prompt-accessibility.md) for manual testing
3. Performance: [.prompt-performance-audit.md](.prompt-performance-audit.md) for metrics
4. Check: [.prompt-backend-api.md](.prompt-backend-api.md) for API testing

---

## 🔄 Keeping Guides Updated

Guides should be updated when:
- New patterns emerge or best practices change
- Technology stack changes (version updates, new libraries)
- Team discovers better approaches
- Design system evolves
- Compliance requirements tighten

**Process**:
1. Edit the relevant `.prompt-*.md` file
2. Update the index sections if adding new content
3. Commit with clear message explaining changes
4. Reference in PR description

---

## 📞 Getting Help

- **General Questions** → Start with relevant guide from table above
- **API Questions** → [.prompt-backend-api.md](.prompt-backend-api.md)
- **Frontend Questions** → [.prompt.md](.prompt.md)
- **Test Help** → [.prompt-testing-qa.md](.prompt-testing-qa.md)
- **Performance Issues** → [.prompt-performance-audit.md](.prompt-performance-audit.md)
- **Accessibility Issues** → [.prompt-accessibility.md](.prompt-accessibility.md)

---

## 📊 Summary Stats

- **Total Guides**: 8 major guides + agent guidance docs
- **Code Examples**: 100+ code snippets across all guides
- **Coverage**: Frontend, Backend, Testing, API, Performance, Accessibility, Components, Theming
- **Pages**: ~200 pages of documentation

---

**Last Updated**: May 2, 2026
**Maintained By**: Development Team
**Version**: 1.0
