# Al-Ghani ERP - Project Rules

This file is the first file every developer or AI assistant should read before changing this repository.

## Project Identity

- Product: Al-Ghani ERP
- Type: Agricultural / trading inventory and accounting system
- Frontend: React 18 + Vite + Tailwind CSS + Motion + Lucide icons
- Backend: Laravel REST API
- Users: Admin, Salesman, Accountant
- Language: English only for code, comments, UI text, reports, and documentation

## Repository Layout

```text
al-ghani-erp/
|-- frontend/          React + Vite application
|-- backend/           Laravel REST API
|-- docker/            Docker configs and scripts
|-- .github/           GitHub Actions workflows
|-- docs/              Architecture, rules, decisions, API notes
|-- SKILL.md           This file
|-- .gitignore
|-- docker-compose.yml
`-- README.md
```

## Core Rules

- Keep code beginner-readable: clear names, small files, simple flow.
- Code must be centralized, reusable, maintainable, scalable, and understandable for an absolute beginner.
- Follow DRY: centralize repeated response shapes, API paths, role values, colors, money conversion, icons, animations, form logic, and workflow rules.
- Do not copy-paste repeated UI or business logic. Extract reusable components, hooks, utilities, service methods, Form Requests, Resources, or backend Services.
- Reuse existing components, hooks, services, constants, utilities, Form Requests, Resources, and backend Services before creating new ones.
- Controllers stay thin. Business logic belongs in `backend/app/Services`.
- Validation belongs in Laravel Form Requests.
- API output belongs in Resource classes.
- Authorization belongs in Policies and controller `authorize(...)` calls.
- Money is stored as integer paisas in the database and displayed as rupees only at UI/report edges.
- Frontend API calls go through `frontend/src/services`.
- Frontend global state uses Zustand; server state uses React Query.
- Frontend animations use the `motion` package from https://motion.dev/ and import from `motion/react`.
- Do not use `framer-motion`.
- Prefer shared Motion wrappers such as `AnimatedPage`, `FadeIn`, `AnimatedList`, `Modal`, and `Button`; direct Motion imports belong only in shared wrappers, low-level UI primitives, layout primitives, or app entry files.
- Keep animation presets centralized in `frontend/src/utils/animations.js`.
- Frontend icons use `frontend/src/components/ui/AppIcon.jsx`. Do not use emoji icons or inline SVGs in pages.

## Key References

- Main rules and decisions: `docs/project-rules-and-decisions.md`
- Full planned structure: `../DIRECTORY_STRUCTURE.md`
