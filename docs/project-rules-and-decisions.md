# Project Rules and Decisions

> This document records the important project decisions and daily workflow rules for Al-Ghani ERP.
> It is written for beginner developers and AI assistants. Read it before changing code.

---

## 1. Project Identity

| Property | Value |
|---|---|
| Product Name | Al-Ghani ERP |
| Type | Agricultural / trading inventory and accounting system |
| Frontend Stack | React 18 + Vite + Tailwind CSS + Motion + Lucide icons |
| Backend Stack | Laravel 11 REST API + Sanctum token auth |
| Database | MySQL / MariaDB via XAMPP locally |
| Local Web Server | XAMPP Apache on port `8000` |
| Repository Type | Monorepo with `frontend/` and `backend/` |
| Target Users | Admin, Salesman, Accountant |
| Default Admin | `admin@alghani.com` / `Admin@123` |
| Currency | Store integer paisas in DB; display rupees at UI/report edges |
| Language Rule | English only for code, comments, UI text, reports, and docs |

---

## 2. Where To Put Files

### Frontend (`frontend/src/`)

| Folder | What Goes Here | Example |
|---|---|---|
| `components/ui/` | Reusable UI used across pages | `Button.jsx`, `Modal.jsx` |
| `components/layout/` | App shell and page layout | `Sidebar.jsx`, `Topbar.jsx` |
| `components/forms/` | Reusable form inputs | `SearchPartyInput.jsx` |
| `components/guards/` | Route protection wrappers | `AuthGuard.jsx` |
| `pages/` | One folder per module | `pages/trading/sales-invoices/` |
| `pages/[module]/components/` | Components used only by one module | `ProductLineTable.jsx` |
| `pages/[module]/hooks/` | Hooks used only by one module | `useSaleInvoice.js` |
| `services/` | API call functions | `saleInvoiceService.js` |
| `hooks/` | Reusable React hooks | `useDebounce.js` |
| `stores/` | Zustand global state | `authStore.js` |
| `utils/` | Reusable helpers | `formatCurrency.js` |
| `constants/` | Shared constant values | `apiEndpoints.js` |
| `config/` | App configuration | `axios.js` |

Rule: if a component is used by more than one module, move it to `components/ui/`, `components/forms/`, or `components/layout/`. If it is only used by one module, keep it inside that module.

### Backend (`backend/app/`)

| Folder | What Goes Here | Example |
|---|---|---|
| `Http/Controllers/Controller.php` | Base controller | `Controller.php` |
| `Http/Controllers/Api/V1/` | API controllers | `SaleInvoiceController.php` |
| `Http/Requests/` | Validation rules | `StoreSaleInvoiceRequest.php` |
| `Models/` | Eloquent models | `SaleInvoice.php` |
| `Services/` | Business workflow logic | `SaleInvoiceService.php` |
| `Repositories/` | Query helpers when needed | `SaleInvoiceRepository.php` |
| `Resources/` | API response transformers | `SaleInvoiceResource.php` |
| `Policies/` | Authorization rules | `SaleInvoicePolicy.php` |
| `Enums/` | Fixed value lists | `PaymentType.php` |

---

## 3. Naming Conventions

| Type | Rule | Good Example |
|---|---|---|
| React Components | PascalCase | `SaleInvoiceForm.jsx` |
| React Pages | PascalCase + `Page` | `SalesInvoicePage.jsx` |
| React Hooks | camelCase + `use` | `useInvoice.js` |
| API Services | camelCase + `Service` | `invoiceService.js` |
| Zustand Stores | camelCase + `Store` | `authStore.js` |
| Laravel Controllers | PascalCase + `Controller` | `SaleInvoiceController.php` |
| Laravel Models | PascalCase singular | `SaleInvoice.php` |
| Laravel Migrations | snake_case + timestamp | `2024_01_01_create_sale_invoices_table.php` |
| Laravel Requests | PascalCase + `Request` | `StoreSaleInvoiceRequest.php` |
| Laravel Resources | PascalCase + `Resource` | `SaleInvoiceResource.php` |
| Laravel Services | PascalCase + `Service` | `StockLedgerService.php` |
| Laravel Policies | PascalCase + `Policy` | `SaleInvoicePolicy.php` |
| DB Tables | snake_case plural | `sale_invoices` |
| DB Columns | snake_case | `party_id` |
| Env Variables | UPPER_SNAKE_CASE | `DB_HOST` |

---

## 4. Frontend Motion And UI Rules

Use Motion from https://motion.dev/ for frontend animations.

- The package name is `motion`.
- Import React Motion APIs from `motion/react`.
- Do not use `framer-motion`.
- Prefer shared wrappers: `AnimatedPage`, `FadeIn`, `AnimatedList`, `Modal`, and `Button`.
- Page files and dashboard widgets should not import `motion` directly unless there is no reasonable shared wrapper.
- Direct Motion imports belong in shared animation wrappers, low-level UI primitives, layout primitives, or app entry files.
- Keep animation presets centralized in `frontend/src/utils/animations.js`.
- Use CSS/Tailwind for simple hover colors, focus rings, and static styling.
- Use `frontend/src/components/ui/AppIcon.jsx` for icons. Do not use emoji icons or inline SVGs in pages.

Correct import:

```js
import { motion } from 'motion/react'
```

Wrong import:

```js
import { motion } from 'framer-motion'
```

---

## 5. Frontend Architecture Flow

Use this order when building frontend features:

```text
Page -> module components -> shared UI components -> hooks/services/utils/constants
```

- Pages compose screens and connect module hooks.
- Module components render one screen section.
- API calls live in `frontend/src/services`.
- Server state uses React Query.
- Global client state uses Zustand.
- Repeated calculations belong in utilities or hooks, not pages.

---

## 6. Backend Architecture Flow

Use this order for backend features:

```text
Controller -> Form Request -> Service -> Repository/Model -> Resource
```

- Controllers stay thin.
- Business logic belongs in `backend/app/Services`.
- Validation belongs in Laravel Form Requests.
- API output belongs in Resources.
- Authorization belongs in Policies and controller `authorize(...)` calls.
- Avoid raw SQL in controllers.

---

## 7. API Contract

### Base URL

```text
http://127.0.0.1:8000/api/v1
```

The `/api/v1` prefix belongs in one place: `VITE_API_BASE_URL`, consumed by `frontend/src/config/axios.js`.

- Endpoint constants in `frontend/src/constants/apiEndpoints.js` must be relative, such as `'/auth/login'`.
- Do not write `/api/v1` inside endpoint constants.

### Authentication

- Auth uses Laravel Sanctum token auth.
- Protected API routes use `auth:sanctum`.
- The frontend sends the token in `Authorization: Bearer {token}`.
- Store the token in the Zustand `authStore`.
- Do not document session CSRF as the active API auth rule unless the app is intentionally migrated.

### Response Shape

Success:

```json
{
  "success": true,
  "data": {},
  "message": "...",
  "meta": {}
}
```

Error:

```json
{
  "success": false,
  "message": "...",
  "errors": {}
}
```

Use correct HTTP status codes: `200`, `201`, `401`, `403`, `404`, `422`, and `500`.

---

## 8. Roles And Permissions

| Role | Access |
|---|---|
| Admin | Full access |
| Salesman | Sales, purchase entry, returns, customers, suppliers, products, stock, worker khata |
| Accountant | Accounts, ledgers, reports, party ledger, partners, worker khata |

- Backend policies must enforce access.
- Frontend role checks only hide UI; they are not security.
- Every protected controller action should authorize the action.

---

## 9. Git Workflow

- Branch names use `feature/`, `fix/`, `chore/`, or `hotfix/`.
- Commit messages use Conventional Commits, such as `feat: add sale invoice posting`.
- Never commit directly to `main` or `staging`.
- Pull requests must pass checks before merge.

---

## 10. Beginner-Friendly DRY Code Rules

The owner is an absolute beginner. Code must stay easy to read and easy to change.

- Use clear names, small files, and simple control flow.
- Prefer boring, obvious code over clever code.
- Each file should have one main responsibility.
- Do not copy-paste repeated UI, API calls, validation, formatting, constants, or workflow logic.
- If the same UI appears more than once, extract a reusable component.
- If the same logic appears more than once, extract a hook, utility, service method, Form Request, Resource, or backend Service.
- Reuse existing components, hooks, services, utilities, constants, Form Requests, Resources, and backend Services before creating new ones.
- Add a new abstraction only when it removes real duplication or makes the code easier to understand.
- Comments should explain why something exists, not repeat what the code already says.

---

## 11. Current Defaults

| Rule | Value |
|---|---|
| Language | English only |
| Currency storage | Integer paisas in DB |
| Currency display | Rupees at UI/report edges |
| Soft deletes | Enabled on critical models |
| Audit trail | Create/update/delete workflows should log |
| Frontend state | Zustand for global state, React Query for server state |
| Validation | Zod on frontend where used, Laravel Form Requests on backend |
| Error handling | Axios interceptor on frontend, Laravel exception handling on backend |
| Animation library | `motion` from https://motion.dev/, imported from `motion/react` |
| Icon library | `lucide-react` only through `AppIcon.jsx` |
| Date formatting | `date-fns` |
| HTTP client | `axios` |

---

## 12. Checklist Before Submitting Code

- [ ] I read this document.
- [ ] My files are in the correct folders.
- [ ] My names follow project conventions.
- [ ] I reused existing components, hooks, services, utilities, constants, Form Requests, Resources, and backend Services.
- [ ] I removed avoidable copy-paste.
- [ ] Repeated UI is a reusable component.
- [ ] Repeated logic is a helper, hook, service, or backend Service.
- [ ] Page files do not contain business posting logic.
- [ ] Controllers are thin.
- [ ] Validation lives in Form Requests.
- [ ] API responses use Resources or centralized helpers.
- [ ] API endpoint constants are relative.
- [ ] Motion imports come from `motion/react`, not `framer-motion`.
- [ ] Icons use `AppIcon`.
- [ ] All text is English.
- [ ] Frontend code changes pass `npm run build`.

---

## 13. DRY Patterns Already Built

### Frontend

| Helper | Location | Purpose |
|---|---|---|
| `createCRUDService(endpoint)` | `frontend/src/services/createCRUDService.js` | Standard list/get/create/update/delete service |
| `apiEndpoints.js` | `frontend/src/constants/apiEndpoints.js` | Central endpoint paths |
| `animations.js` | `frontend/src/utils/animations.js` | Central Motion timing and variants |
| `formatCurrency()` / `toPaisas()` | `frontend/src/utils/formatCurrency.js` | Money conversion |
| `formatDate()` | `frontend/src/utils/formatDate.js` | Date display |
| `AppIcon` | `frontend/src/components/ui/AppIcon.jsx` | Central icons |
| `AnimatedPage`, `FadeIn`, `AnimatedList` | `frontend/src/components/ui/` | Beginner-safe Motion wrappers |
| `Button`, `Modal`, `Table`, `Card` | `frontend/src/components/ui/` | Shared UI primitives |

### Backend

| Helper | Location | Purpose |
|---|---|---|
| Base `Controller` | `backend/app/Http/Controllers/Controller.php` | Shared controller behavior |
| `ApiResponse` | `backend/app/Support/ApiResponse.php` | Central success/error response shapes |
| `AuditLogService` | `backend/app/Services/AuditLogService.php` | Audit writing |
| `DocumentSequenceService` | `backend/app/Services/DocumentSequenceService.php` | Locked document numbering |
| `JournalService` | `backend/app/Services/JournalService.php` | Double-entry journal posting |
| `PartyLedgerService` | `backend/app/Services/PartyLedgerService.php` | Party balance and statements |
| `StockService` | `backend/app/Services/StockService.php` | FIFO deduction, restore, average cost |
| `StockLedgerService` | `backend/app/Services/StockLedgerService.php` | Stock movement audit rows |
| `Setting::currentFiscalYear()` | `backend/app/Models/Setting.php` | Fiscal year source |

---

## 14. Backend Boot And Config Notes

- The backend is a Laravel 11 app with a custom Windows-friendly local setup.
- `backend/server.php` exists for the PHP built-in server, but XAMPP Apache is preferred locally because paths contain spaces.
- `backend/config/auth.php` must keep both `web` and `api` guards where Sanctum needs them.
- `backend/config/sanctum.php` uses `'guard' => ['web']`; do not change it to `'api'`.
- Sanctum requires the `personal_access_tokens` table.

---

## 15. Local Development Setup

### Prerequisites

- XAMPP for Apache, PHP, and MariaDB.
- Node.js 20+ with npm.
- Composer 2.x.

### One-Click Start

- Use `start.bat` at the project root.
- It starts MariaDB, Apache backend on port `8000`, Vite frontend on port `5173`, and opens `http://localhost:5173`.
- Use `stop.bat` to stop the local services.

### Manual Start

```powershell
& "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
& "C:\xampp\apache\bin\httpd.exe"
cd "e:\production level try\al-ghani-erp\frontend"
npm run dev
```

XAMPP is the daily local/shop startup rule.

---

## 16. Production Deployment

Production uses Docker, not the daily XAMPP startup.

| File | Purpose |
|---|---|
| `docker-compose.yml` | Local Docker alternative |
| `docker-compose.prod.yml` | Production stack |
| `docker/nginx/` | Nginx reverse proxy |
| `docker/php/Dockerfile` | PHP-FPM image |
| `docker/node/Dockerfile` | Node/Vite build image |
| `docker/mysql/init.sql` | MySQL init script |
| `.github/workflows/deploy.yml` | CI/CD deployment |

---

## 17. Workflow Rule Vocabulary And Priority

Use these terms consistently:

- Workflow Rules: end-to-end behavior for a screen or process.
- Entry Rules: how users fill forms, grids, and rows.
- Line Item Rules: sale, purchase, and return row behavior.
- Auto-Add Rules: when a new empty row appears automatically.
- Autofill Rules: fields filled after selecting party, product, account, or batch.
- Calculation Rules: quantity, rate, discount, tax, amount, cost, and profit rules.
- Validation Rules: conditions that block save or post.
- Posting Rules: backend side effects after final confirmation.
- Ledger Rules: debit/credit accounting entries.
- Stock Movement Rules: stock deduction, restoration, batch allocation, and reconciliation.
- Print/Export Rules: PDF, CSV, invoice, and report output behavior.
- Safety Rules: backup, restore, live data, security, and testing constraints.

Correctness priority:

```text
1. Protect live data
2. Keep ledger balanced
3. Keep item stock and batch stock consistent
4. Keep posting auditable
5. Keep daily entry fast
6. Keep UI clean
```

---

## 18. Shared Entry, Search, Picker, And Autofill Rules

- Production screens must call real Laravel APIs.
- Production screens must not fall back to fake, mock, hardcoded, or sample business data.
- If required API data cannot load, show a clear error and block unsafe posting.
- `Enter` behaves like `Tab` inside daily entry forms and grids.
- `Tab` from a search field tries exact or unique-match autofill.
- Arrow Up/Down moves inside visible dropdown or picker results.
- `Enter` selects the highlighted picker result.
- `Esc` closes picker/dialog states without saving.
- Posting confirmations must be dialogs, not toast-only messages.
- `Enter` confirms a focused confirmation dialog.
- `Esc` cancels a focused confirmation dialog.
- Pages may vertically scroll when content is taller than the viewport.
- Tables should fit the screen first; horizontal scroll is a last resort.
- Blank line rows are for fast entry only and are ignored on save/post.
- Frontend/PDF display may round to whole rupees for shop readability.
- Backend/database calculations must keep decimal-capable precision.

Search rules:

- Account search accepts code, name, and mobile where available.
- Product search accepts code and name.
- Supplier search accepts supplier/dealer code, name, and mobile.
- If exactly one clear match exists, select it automatically.
- If multiple matches exist, open picker/results.
- Selecting accounts should show useful context such as name, mobile, balance, and recent activity where supported.
- Selecting products should fill safe known row data from real API data.
- If autofill data cannot load, show an error and do not allow posting based on guessed values.

---

## 19. Account, Party, Customer, Supplier, And Dealer Rules

Code truth:

- Current code uses `parties` with extension rows such as `customers`, `suppliers`, `partners`, and `workers`.
- Legacy/shop language may call these unified `accounts`.
- Treat them as one mental model: an account/party that money can flow to or from.

Rules:

- `code` is the short lookup code.
- `name` is the account title.
- Opening debit and credit seed ledger balance.
- A customer is a party the business can sell to.
- A supplier is a party the business can buy from.
- A dealer can be both sell-side and buy-side.
- Do not duplicate one dealer into separate customer and supplier ledgers.
- Customer lists include parties/accounts marked sell-side.
- Supplier lists include parties/accounts marked buy-side.
- Dealer lists include parties/accounts marked both ways.
- Legacy migrated customer/supplier types must continue to work during cleanup.
- Balance direction determines whether a dealer is receivable or payable.
- Cash sale can be posted without selected party and must display/post as `Cash Sale`.
- Credit sale requires a selected party.
- Cash sale with selected party affects that party ledger.

---

## 20. Product, Pack, Unit, Batch, And Pricing Rules

Code truth:

- Current code uses `products` and `product_batches`.
- Legacy/shop language may call products `items`.

Product rules:

- Product code is the fast lookup code.
- Product name is the operator-readable name.
- Product category comes from settings-managed categories.
- Product pack/size comes from settings-managed packing sizes.
- Product supplier/company display may use supplier code where supported.
- `GP Rate` is the purchase/cost-side rate used by shop workflow.
- `GS Rate` is the normal sale rate.
- `MRP` is printed/retail price.
- `FRP` is an additional purchase/sale reference price.
- `Avg.R` is expected sale rate for expected-profit planning.
- `Avg Cost` is dynamic live cost from remaining active batches, not a manual product field.
- Current stock on product should match summed batch stock after reconciliation.
- Expirable products require batch/expiry data during purchase.
- Low stock uses `stock_limit` or the current equivalent field.
- Overstock uses `max_stock` or the current equivalent field.
- Product master price changes from purchase require explicit owner confirmation.
- Sale Bill must not update default product sale price.

Unit rules:

- `Unit` means quantity and rate are per base unit.
- `Carton` or `Pack` means quantity and rate are per carton/pack.
- Backend converts carton/pack quantity into base unit quantity for stock deduction.
- Stock is stored internally as base/smallest units.
- Unit/carton conversion requires `has_carton` and `units_per_carton` or equivalent fields.
- If conversion data is missing, non-unit sale options must be disabled or posting must fail.

Batch rules:

- FIFO batch is the default sale allocation.
- Manual batch override is allowed.
- Negative stock is blocked.
- Batch/expiry is required for expirable items where applicable.
- Sale posting records actual batch allocations.
- Invoice line weighted cost/profit is updated from actual batch allocations.

---

## 21. Sale Bill Workflow Rules

Header and party:

- Payment modes remain `Cash` and `Credit` and should behave like a segmented/toggle control.
- Bill Book # is optional, digits-only, has no duplicate check in v1, and must not show a misleading range placeholder.
- Sale date is stored as a normal DB date.
- UI/print can show old shop-style date format.
- Season comes from settings and always allows no season.
- Restored drafts with old seasons should keep that season temporarily so data is not lost.
- Party search is optional on Cash and required on Credit.
- Sale Bill uses separate Account Code and Account Name fields.
- Typing code can autofill name; typing name can autofill code.
- Three printable customer detail fields may be accepted by API and print payload.
- Private ERP note must not print unless explicitly designed as printable.

Line items:

- Sale rows use separate Code and Product Name fields.
- Both fields can search by code or name.
- Product name search supports contains-word matching.
- Product results show code, name, pack, supplier/company, stock, sale rate, and batch/expiry hint where supported.
- On product selection, fill code, name, pack, stock, default unit, FIFO batch, expiry, available quantity, purchase/cost basis, expected sale rate, sale price, and last party price where available.
- Next empty sale line appears only after the current last line has enough data to calculate amount.
- A valid line needs product, quantity, rate/amount, and required unit/batch data.
- After completing a valid line, focus moves to the next empty code/product field.
- Auto-add must not create infinite blank rows.
- Normal calculation uses quantity, rate, line discount, and tax if present.
- `Amount` is editable; typing final amount recalculates unit rate in the frontend.
- If quantity is blank or zero, focus should move to quantity first.
- If typed amount does not divide cleanly by quantity, typed amount wins.
- When this behavior is implemented in the backend, persist the difference in an explicit `line_total_adjustment` field or equivalent adjustment column instead of hiding it silently in rate math.
- Sale line snapshots expected sale rate at posting time for expected-profit reports.

Payments and posting:

- Bill type and money destination are separate.
- Received money destination can be cash drawer, bank, wallet, or owner personal account.
- If received amount is greater than zero, destination account is required.
- Split payment is supported.
- If selected party overpays, extra amount becomes party advance.
- If no-party walk-in cash sale overpays, show change due instead of posting advance.
- Owner personal account receipts mean the customer is paid and the owner account owes the shop.
- Payment destination/split panel stays collapsed unless opened.
- Normal Sale Post requires confirmation every time.
- Confirmation shows party or `Cash Sale`, total lines, total quantity, net amount, and payment mode.
- Sales/net sale account must be configured before posting.
- Credit sale without selected party is blocked.
- Payment destinations are validated before stock or ledger side effects.
- Stock and ledger are posted during posting, not draft entry.
- Batch allocation rows are stored in `sale_invoice_line_batches`; if older notes say `invoice_item_batches`, treat that as the same intended concept using the current codebase table name.
- Invoice is marked posted only after stock and ledger side effects complete.
- After successful post, offer `Print`, `New`, `Stay`, and `WhatsApp` where available.
- A5 invoice print remains the main shop print flow.
- Estimate/quote and customer order/reserve-stock flows are separate from final posted sale.

Edit, draft, and helpers:

- Sale uses local browser draft recovery.
- Draft banner offers `Restore Draft` and `Discard`.
- Draft clears immediately after successful save/post.
- Draft records can be edited without ledger/stock side effects.
- Posted sale replacement is owner-confirmed, audited, and blocked when the bill has sale returns.
- Posted edit restores stock, removes ledger rows, rewrites bill, reposts, and creates revision history.
- No delete button for posted bills in normal workflow.
- Old bill search may use invoice number, bill book number, party, mobile, or product when implemented.
- Party warnings can warn but should not block posting.

---

## 22. Purchase Entry Workflow Rules

Header and supplier:

- Supplier search accepts code, name, or mobile.
- `Tab` selects/autofills a unique supplier match; multiple matches open picker/results.
- Selected supplier should show code/name, balance, recent purchases, and last product price context where available.
- Dealer accounts marked buy-side are valid suppliers.
- Supplier invoice short reference is optional, digits-only, 1 to 6 digits.
- Missing supplier invoice short reference warns only on Post Purchase confirmation.
- Purchase date is required.
- Product master price updates from purchase require explicit confirmation and backend confirmation flag.

Line items:

- Purchase rows keep row search and may also use product browser/list panel.
- Separate Code and Product Name fields are used.
- Both fields search by code/name.
- Browser filters may include category, supplier/company, stock status, and expiry/batch status where API supports it.
- On product selection, fill code, name, pack, stock, current GP, commission, MRP, FRP, expected sale rate, and existing product prices.
- Next empty purchase line appears only after the current last line has enough data.
- Blank lines are ignored on save/post.
- Normal calculation uses quantity, rate, commission, tax, and amount.
- Commission reduces effective unit cost for purchase net, batch cost, and average cost.
- Commission may be entered as rupees or percent in UI.
- Typed line amount can calculate GP/rate.
- If typed amount does not divide cleanly, typed amount wins and adjustment is stored internally.
- `Avg.R` is owner-entered expected sale rate and is separate from actual average cost.

Batch and posting:

- Batch number and expiry are manual on purchase.
- Expirable products require batch number and expiry date.
- Purchase posting creates batches from purchase lines.
- Purchase posting increments product stock.
- Purchase posting recalculates average rate/cost from remaining active batches.
- Normal Purchase Post requires confirmation every time.
- Confirmation includes supplier, date, supplier invoice short reference if entered, total items, and total cost.
- If product master prices will change, confirmation includes a separate warning.
- Selected account must be valid supplier/dealer/buy-side account.
- Purchase account must be configured before posting.
- Purchase creates supplier payable first.
- Supplier payment rows debit supplier and credit selected cash/bank/wallet/owner source account.
- Split supplier payments are supported.
- Supplier overpayment becomes supplier advance.
- After successful post, offer `Print`, `New`, and `Stay`.
- Purchase uses local browser draft recovery.

---

## 23. Return Workflow Rules

Sale return:

- Sale return can be linked to an original posted invoice.
- Original invoice must be posted before linked return.
- Invoice row is locked while validating return.
- Return quantity cannot exceed original sold quantity minus already returned quantity.
- Return rate cannot exceed original sale rate.
- If invoice batch allocation rows exist, return allocation uses the original `sale_invoice_line_batches` allocations.
- Returned stock is restored to original batch allocations.
- If no original invoice is linked, return uses supplied item/batch data and normal stock restore rules.
- Sale return creates header and return item rows.
- Sale return posts ledger entries.
- Credit sale return requires customer account.
- Cash sale return requires cash account.
- Settlement can be credit balance or cash refund.

Purchase return:

- Purchase return can be linked to an original posted purchase.
- Original purchase must be posted before linked return.
- Purchase row is locked while validating return.
- Return quantity cannot exceed original purchased quantity minus already returned quantity.
- Purchase return creates a posted return immediately.
- Purchase return deducts stock from selected batch or FIFO stock.
- Selected batch must belong to selected item/product.
- Purchase return records return item rows.
- Purchase return posts ledger entries.
- Non-credit settlement requires cash/bank account.

---

## 24. Stock Movement And Alert Rules

Movement:

- Product row is locked before stock deduction/restoration.
- Batch row is locked before batch deduction/restoration.
- Selected batch must belong to selected product.
- Deduction checks available quantity before changing stock.
- FIFO deduction uses active batches with remaining quantity ordered by received date then id.
- Empty batches become consumed.
- Restore into expired batches is blocked automatically.
- Consumed batch can reactivate only when restored quantity makes it positive.
- Product `current_stock` increments/decrements with stock movement.
- Stock reconciliation can recompute product summary from batch/source records.
- Reconciliation is not normal daily posting and needs backup/test safety.
- Negative stock must be blocked in sale, purchase return, and adjustment workflows.

Alerts:

- Low stock means current stock is below stock limit.
- Expiry soon means active remaining batch expires within configured days.
- Expired stock means active remaining batch expiry date is before today.
- Overstock means current stock is above max stock.
- Slow moving means stock exists and recent sold quantity is below threshold.
- Stock Alerts screen is the handling/detail screen.
- Dashboard shows only high-priority summary/top alert items.

Average cost:

```text
avg_cost_paisas = SUM(qty_remaining * cost_price_paisas) / SUM(qty_remaining)
```

- Compute average cost only over active batches with remaining quantity.
- Return `0` if total quantity is zero.
- If displayed average cost looks wrong, fix batch data/reconciliation, not the display formula.

---

## 25. Ledger, Accounting, And Journal Voucher Rules

Ledger sign convention:

```text
balance = opening_dr - opening_cr + debits - credits
```

- Positive balance is DR/receivable.
- Negative balance is CR/payable.
- Transactions use separate debit and credit columns.
- Ledger statement starts with opening balance before selected range, then applies rows in date/id order.
- Required accounting accounts must be configured before posting.
- Posting must fail before side effects if ledger accounts are missing.

Posting entries:

- Sale party bill posts DR party and CR sales account.
- Sale payment posts DR destination and CR party.
- Walk-in cash sale posts DR cash/bank and CR sales account.
- Purchase posts CR supplier and DR purchase account.
- Supplier payment posts DR supplier and CR source account.
- Sale return posts DR sales account and CR customer/cash settlement account.
- Purchase return posts DR supplier and CR purchase account.
- Purchase return cash/bank refund also posts DR cash/bank and CR supplier.
- Journal voucher posts one DR row and one CR row.

Journal voucher:

- JV uses an Access-inspired stacked layout.
- Sections are Debitor Account, Creditor Account, amount/detail, and preview.
- Search accepts account code, name, or mobile.
- `Tab` unique-match autofills account.
- Multiple matches open picker/results.
- Selecting account shows current balance.
- Debit and credit accounts are required and must be different.
- Amount must be positive.
- Narration/detail is required by controller validation.
- Preview shows debit account, credit account, amount, and narration.
- Post confirmation is required; `Enter` confirms and `Esc` cancels.
- After post, show or offer history.
- JV draft recovery is planned/expected for parity with sale/purchase.

---

## 26. Dashboard, Report, Print, And Export Rules

Dashboard:

- Dashboard is daily shop control first, owner command center second.
- Dashboard must use real API data only.
- Default date range should be Today.
- Quick ranges include Yesterday, This Month, FY, and Custom where implemented.
- Unpaid logic uses remaining balance, not only payment type.
- Receivable/payable logic uses ledger balances and account roles.
- Smart Alerts show high-priority summary and top items.
- Full alert handling stays in Stock Alerts.
- Recent Bills show latest posted/draft bills with status, party, amount, date, and open action.
- Receivables aging stays visible for recovery work.
- Owner-only/profit/cost metrics should remain protected/collapsed where role system exists.
- Failed dashboard API sections show clear error, not sample data.

Reports:

- Reports use `ReportService` data.
- Report screens must not use fake fallback data.
- All reports should be viewable as PDF where supported.
- CSV export must protect against spreadsheet formula injection.
- PDF/report display can round for readability.
- Backend report calculations stay decimal-capable.
- Report filters must affect visible totals and rows.
- Date ranges must be explicit and visible.
- Reports and ledgers should use A4-friendly layout, readable font size, aligned headers/columns, side margins, and no avoidable clipping.
- Trial balance and balance sheet must preserve accounting sign/balance logic.
- Cash Book and Bank Book opening balance must be actual opening before range.

Supported report families include sales register, purchase register, recovery, profit per item, stock, trial balance, profit and loss, balance sheet, cash book, bank book, expiry, crop wise, party balance, daily sale, slow moving, price history, and day book.

---

## 27. Settings, Lookup, Product Register, And Form Rules

Settings:

- Settings manages company info, accounting accounts, profit method, thresholds, fiscal year, backup/restore, product categories, cities/areas, crop seasons, and packing sizes.
- Accounting account settings are required for posting.
- Bank account setting is a default only; actual bank/payment accounts can be more than one through payment destinations/accounts.
- Lookup editors support add, rename, delete-unused, refresh, loading state, empty state, and inline errors.
- Lookup name is required, trimmed, and backend length-controlled.
- Duplicate lookup names are rejected case-insensitively.
- Unknown lookup type returns error.
- Delete is allowed only when unused.
- Delete used lookup is blocked with usage count/message.
- Existing invoices are not rewritten if a season is renamed.
- Settings changes must not rewrite historical records unless an explicit migration plan says so.

Product Register:

- Product Register lists active products from real API data.
- Columns should take space according to content, not equal fixed shares.
- Category/status/supplier pills must fit content.
- Long category/supplier names can wrap or ellipsize cleanly.
- Product Pack/Size and Category options come from Settings lookups.
- Product Register should show dynamic live average cost from batches.
- Product add/edit forms open as centered modal/window-style forms, not new browser tabs.

Customer, supplier, and worker forms:

- Add/edit forms should open as centered modal/window-style forms.
- Customer and supplier account forms use account code, account name, mobile, area/city, opening DR/CR, and role flags.
- Account code must be unique.
- To change account code, open the account for edit; do not recreate blindly.
- Worker add button should open a functional worker form.
- Worker transactions remain separate from party ledger unless future accounting integration changes that.

---

## 28. Backup, Restore, Health, Startup, And Security Rules

Backup and restore:

- Backups go to an approved Google Drive folder or approved non-public backup location.
- Backups must not go into public web-served folders.
- Backup files are encrypted/signed where the current backup service supports it.
- Restore is destructive and requires explicit confirmation.
- Restore should verify backup manifest/table integrity.
- Pre-restore, pre-import, and pre-update backups should not be pruned like ordinary daily backups.

Health and startup:

- Health page is authenticated.
- Health checks app, DB, migrations, backup directory, storage, and last backup.
- One-click startup starts XAMPP services, verifies health, opens the local app, and writes logs.
- Docker is not the daily shop startup rule; Docker remains the production deployment path.
- Root web protection is required because the repo sits under XAMPP htdocs.

Security:

- All API routes require authentication except explicit public auth routes.
- API routes use Sanctum bearer token auth in the current app.
- Login is throttled.
- Production app must not run with debug output visible to shop/LAN users.
- Application secret keys and DB passwords must never be placed in docs.
- Normal runtime should use a dedicated MySQL app user, not root, when configured.
- Password change endpoint should require current password.
- Public old prototypes/uploads should not be web-served.

---

## 29. Draft, Local Storage, Browser State, And Safety Rules

- Browser local storage can be used for draft recovery and UI state.
- Posted business records are not stored in browser local storage.
- Draft restore/discard banner should be explicit.
- Draft clears after successful save/post.
- Draft state must not override newer posted data.
- Browser state must not be treated as accounting truth.
- Browser workflow tests must use a test database or non-posting checks unless explicit live approval is given.
- Live workflow smoke tests must be rollback-safe.
- Do not run destructive data actions without backup and explicit approval.

---

## 30. Worker And Partner Rules

Workers:

- Workers have profile details and worker transaction history.
- Worker transactions support advances, salaries, and reimbursements where implemented.
- Worker statement should show transaction history.

Partners:

- Partners have investments, withdrawals, current balance, and profit shares.
- Partner profit split cannot be recorded for negative profit.
- Partner share percentages must total exactly 100%.
- Rounding residue is allocated deterministically so allocated shares sum to total profit.

---

## 31. Testing Rules And Risk Areas

Documentation-only changes do not require a build.

Frontend changes require at least:

```powershell
npm run build
```

Workflow UI changes should run frontend smoke checks when possible.

Backend business logic changes require safe tests and targeted invariant tests.

Tests are required before changing:

1. Sale posting ledger entries.
2. Purchase posting ledger entries.
3. Sale return against batch allocations.
4. Purchase return caps.
5. Posted sale bill replacement.
6. Stock deduction/restoration.
7. Item stock versus batch stock reconciliation.
8. Average cost display.
9. Report totals.
10. Balance sheet/trial balance.
11. Backup/restore verification.
12. Document numbering under two-device use.

Tests live in `backend/tests/Feature/` and `backend/tests/Unit/`.

---

## 32. Roadmap Priority And Reserved Convenience Rules

Rule hardening priority:

1. Sale Bill Line Item Rules.
2. Purchase Line Item Rules.
3. Party Selection and Dealer Role Rules.
4. Unit Conversion Rules.
5. Posting Rules.
6. Stock Movement and Batch Allocation Rules.
7. Ledger and Accounting Entry Rules.
8. Return Rules.
9. Report and PDF Output Rules.
10. Backup, Restore, and Security Rules.
11. Worker and Partner Rules.
12. Convenience helper rules.

Reserved convenience ideas must not bypass posting validation:

- hold multiple unfinished sale bills
- quick full payment
- quick add product inside Sale Bill
- category quick buttons
- old bill search by multiple keys
- duplicate old bill into new bill
- party unpaid/overdue/last-payment warning
- due/promise date for credit recovery
- salesperson/cash drawer/shift tracking
- bonus/free stock-deducting lines
- exchange and return shortcuts
- estimate/quote converted later into bill
- customer order/reserve stock converted later into bill
