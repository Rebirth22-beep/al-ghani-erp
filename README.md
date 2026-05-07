# Al-Ghani ERP

Al-Ghani ERP is a React + Laravel monorepo for agricultural trading, inventory, stock, ledger, and reporting workflows.

## Project Shape

```text
frontend/   React 18 + Vite + Tailwind CSS + Motion
backend/    Laravel REST API
docs/       Project decisions, rules, and architecture notes
docker/     Local and production Docker configuration
```

## Developer Rules

Read these before changing code:

1. `SKILL.md`
2. `docs/project-rules-and-decisions.md`
3. `../DIRECTORY_STRUCTURE.md`

## Beginner-Friendly Architecture

Frontend flow:

```text
Page -> module components -> shared UI components -> hooks/services/utils
```

Backend flow:

```text
Controller -> Form Request -> Service -> Repository/Model -> Resource
```

Keep shared logic centralized and reusable. Do not duplicate API envelopes, role values, endpoint prefixes, money conversion, or animation settings.
