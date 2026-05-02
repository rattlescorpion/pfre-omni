# Frontend & UI/UX Prompt Suite

## Overview
This directory contains a complete set of frontend development prompts for PFRE-Omni. Each prompt is standalone but cross-referenced for easy navigation.

## Available Prompts

### 1. **[.prompt.md](.prompt.md)** — Main Frontend & UI/UX Guide
**For**: Building new components, general frontend guidance, reviewing code
- Technology stack: Blade + Alpine.js + Vue 5 + Tailwind
- 6 task types: Build, Interact, Visualize, Review, Debug, Guidance
- Design system reference (colors, spacing, typography)
- Dark mode support and responsive design patterns
- **Use this** when starting new component work or learning the stack

---

### 2. **[.prompt-api-integration.md](.prompt-api-integration.md)** — Backend API Integration
**For**: Building forms, fetching data, error handling, real-time updates
- Axios configuration and interceptors
- Form submission patterns with validation
- Multi-step workflows with API calls
- Auto-save drafts and lazy loading
- Error handling (422 validation, 401 auth, 5xx server errors)
- Network optimization and debouncing
- **Use this** when building data-driven features or complex forms

---

### 3. **[.prompt-theme-customization.md](.prompt-theme-customization.md)** — Theme & Design Tokens
**For**: Customizing colors, white-label variants, dark mode enhancements
- Current color palette (primary blue, accent saffron, semantic colors)
- CSS variables system (`design-tokens.css`)
- White-label implementation for multi-tenant verticals
- Dark mode extended palette
- User theme persistence
- Accessibility contrast validation
- **Use this** when adapting design for clients or adding new color schemes

---

### 4. **[.prompt-component-library.md](.prompt-component-library.md)** — Reusable Components Catalog
**For**: Finding existing components, learning component patterns, building new ones
- **Layout Components**: `app-layout`, `sidebar`, `topnav`
- **Data Display**: `kpi-card`, `data-table`, `alert`
- **Form Components**: `form-group`, `button`
- **Interactive**: `modal`, `dropdown`, `breadcrumb`
- **Status/Badge**: `badge`, `status-indicator`
- **Card Variants**: `card`, `stat-card`
- Component lifecycle, slots, testing patterns
- **Use this** before building new components—check if one already exists

---

### 5. **[.prompt-performance-audit.md](.prompt-performance-audit.md)** — Build & Runtime Optimization
**For**: Reducing bundle size, improving load time, monitoring performance
- Vite configuration optimization
- Tailwind CSS purging and minification
- Image and font optimization
- JavaScript code splitting
- Network caching and compression
- API response optimization (pagination, eager loading)
- Alpine.js and chart optimization
- Lighthouse CI setup and metrics
- **Use this** before deployment or when performance degrades

---

### 6. **[.prompt-accessibility.md](.prompt-accessibility.md)** — WCAG 2.1 Level AA Compliance
**For**: Ensuring accessible forms, navigation, and interactive components
- Semantic HTML foundation
- Form labels, fieldsets, error messaging
- Color contrast validation (4.5:1 ratio)
- Keyboard navigation and focus management
- ARIA labels, descriptions, live regions
- Screen reader testing (NVDA, VoiceOver)
- Mobile touch accessibility (44×44px targets)
- Accessibility audit checklist
- **Use this** when building forms or reviewing component accessibility

---

## Quick Reference Map

| Task | Primary Prompt | Secondary Resources |
|------|---|---|
| **Create new component** | [.prompt.md](.prompt.md) | [.prompt-component-library.md](.prompt-component-library.md) |
| **Build form with API** | [.prompt-api-integration.md](.prompt-api-integration.md) | [.prompt.md](.prompt.md), [.prompt-accessibility.md](.prompt-accessibility.md) |
| **Customize colors/theme** | [.prompt-theme-customization.md](.prompt-theme-customization.md) | [.prompt.md](.prompt.md) |
| **Optimize for production** | [.prompt-performance-audit.md](.prompt-performance-audit.md) | [.prompt-api-integration.md](.prompt-api-integration.md) |
| **Make accessible** | [.prompt-accessibility.md](.prompt-accessibility.md) | [.prompt.md](.prompt.md), [.prompt-component-library.md](.prompt-component-library.md) |
| **Find existing component** | [.prompt-component-library.md](.prompt-component-library.md) | [.prompt.md](.prompt.md) |
| **Debug Alpine.js/forms** | [.prompt-api-integration.md](.prompt-api-integration.md) | [.prompt.md](.prompt.md), [.prompt-accessibility.md](.prompt-accessibility.md) |
| **White-label vertical** | [.prompt-theme-customization.md](.prompt-theme-customization.md) | [.prompt.md](.prompt.md) |
| **Improve page speed** | [.prompt-performance-audit.md](.prompt-performance-audit.md) | [.prompt-api-integration.md](.prompt-api-integration.md) |

---

## How to Use These Prompts

### In Copilot Chat
**Mention the prompt by name or topic:**
```
@copilot: Create a new KPI card for the dashboard
(Copilot will reference .prompt.md and .prompt-component-library.md)

@copilot: How do I handle form validation with Axios?
(Copilot will reference .prompt-api-integration.md)

@copilot: Make this component accessible
(Copilot will reference .prompt-accessibility.md)
```

### For Quick Reference
1. **Building**: Start with [.prompt.md](.prompt.md)
2. **Components**: Check [.prompt-component-library.md](.prompt-component-library.md)
3. **API Data**: Use [.prompt-api-integration.md](.prompt-api-integration.md)
4. **Styling**: Reference [.prompt-theme-customization.md](.prompt-theme-customization.md)
5. **Compliance**: Verify [.prompt-accessibility.md](.prompt-accessibility.md)
6. **Shipping**: Optimize with [.prompt-performance-audit.md](.prompt-performance-audit.md)

---

## Key Files Referenced Across Prompts

| File | Purpose |
|------|---------|
| [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php) | Root layout with Alpine store, dark mode |
| [resources/views/components/](resources/views/components/) | Reusable Blade components |
| [resources/css/design-tokens.css](resources/css/design-tokens.css) | CSS variables (colors, spacing) |
| [resources/js/app.js](resources/js/app.js) | Axios initialization, Alpine setup |
| [vite.config.js](vite.config.js) | Build configuration |
| [package.json](package.json) | Dependencies and npm scripts |
| [routes/api.php](routes/api.php) | API endpoint definitions |
| [tailwind.config.js](tailwind.config.js) | Tailwind CSS configuration (if exists) |

---

## Tech Stack Summary

- **Templating**: Laravel Blade (server-side) + Alpine.js 3.13 (lightweight JS)
- **Advanced**: Vue 5 (for complex components)
- **Styling**: Tailwind CSS 3.4 + CSS custom properties
- **Visualization**: Chart.js, DataTables.net, FullCalendar, Leaflet, Select2
- **Build Tool**: Vite 5 with laravel-vite-plugin
- **Dark Mode**: Native support with Alpine state + Tailwind `dark:` utilities
- **Form Handling**: Axios + Alpine.js for client-side state

---

## Best Practices Across All Prompts

✅ **Always**:
- Support dark mode (`dark:` utilities)
- Make components accessible (semantic HTML + ARIA)
- Use responsive design (mobile-first breakpoints)
- Test keyboard navigation
- Lazy load heavy components
- Minify and optimize for production
- Document component props and usage

❌ **Never**:
- Hardcode colors (use design tokens)
- Ignore focus indicators
- Rely on color alone for meaning
- Leave images without alt text
- Block user zoom (`user-scalable=no`)
- Import unused dependencies
- Skip form validation

---

## Getting Started

1. **New Feature?** → Start with [.prompt.md](.prompt.md)
2. **Need a Component?** → Check [.prompt-component-library.md](.prompt-component-library.md)
3. **Building Forms?** → Use [.prompt-api-integration.md](.prompt-api-integration.md)
4. **Client Branding?** → Reference [.prompt-theme-customization.md](.prompt-theme-customization.md)
5. **Ready to Deploy?** → Run [.prompt-performance-audit.md](.prompt-performance-audit.md) checklist
6. **Audit Time?** → Use [.prompt-accessibility.md](.prompt-accessibility.md) checklist

---

## Updates & Maintenance

These prompts are living documentation. Update them when:
- New components are created or patterns established
- Technology stack changes (new library, version update)
- Design system evolves (new colors, tokens, spacing)
- Performance targets shift
- Accessibility requirements tighten

**Last Updated**: May 2, 2026
**Maintained By**: Development Team
**Version**: 1.0
