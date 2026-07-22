# Internal IT Ticket Dashboard

Simple internal IT support ticket dashboard for tracking, assigning, and resolving office IT issues.

Built as the **LeadGeeks IT Staff** technical assessment (**Option 1**).

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Vue](https://img.shields.io/badge/Vue-3-4FC08D?logo=vue.js&logoColor=white)](https://vuejs.org)
[![Inertia](https://img.shields.io/badge/Inertia-3-9553E9)](https://inertiajs.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38BDF8?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![SQLite](https://img.shields.io/badge/Database-SQLite-003B57?logo=sqlite&logoColor=white)](https://www.sqlite.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](#license)

---

## Table of contents

- [Demo access](#demo-access)
- [Live demo](#live-demo)
- [Project overview](#project-overview)
- [Technologies used](#technologies-used)
- [Features implemented](#features-implemented)
- [Screenshots](#screenshots)
- [Setup instructions](#setup-instructions)
- [Environment variables](#environment-variables)
- [Default data after seed](#default-data-after-seed)
- [Application structure](#application-structure)
- [Routes](#routes)
- [Architecture notes](#architecture-notes)
- [Testing](#testing)
- [Useful commands](#useful-commands)
- [Troubleshooting](#troubleshooting)
- [Deployment notes](#deployment-notes)
- [Author](#author)
- [License](#license)

---

## Demo access

Use this account right after seeding. Credentials are also shown in a **Demo access** box on the login page.

| Field | Value |
|-------|--------|
| **Email** | `demo@leadgeeks.test` |
| **Password** | `password` |

> Auth is **login-only** for this showcase. Registration, password reset, email verification, 2FA, and passkeys are disabled so reviewers never get stuck.

---

## Live demo

> Public URL after VPS deploy (PHP app — not pure static Netlify/Vercel).

| Item | Value |
|------|--------|
| **URL** | _TBD — `https://leadgeeks-ticket.iqbalalhabib.my.id` after first deploy_ |
| **Login** | `demo@leadgeeks.test` / `password` |
| **Container** | `ghcr.io/cihuyyama/leadgeeks-technical-test` (built by GitHub Actions) |

---

## Project overview

**Internal IT Ticket Dashboard** helps IT staff manage day-to-day support work from one screen:

- See open workload at a glance (summary cards)
- Create and update tickets with category, priority, status, and assignee
- Close or delete tickets when work is done
- Browse a scannable list with color badges for status and priority
- Search, filter, and sort tickets without losing the global summary cards

### Why this stack?

| Choice | Reason |
|--------|--------|
| Laravel 13 | Clean backend, validation, migrations, seeders, tests |
| Vue 3 + Inertia | SPA feel without a separate API layer |
| Tailwind + shadcn-vue | Fast, consistent product UI |
| SQLite | Zero extra DB setup for reviewers |
| Fortify (login only) | Secure session auth without registration friction |

### Scope (intentionally simple)

- Single demo user (no multi-tenant, no roles)
- No queues, no websockets, no external APIs
- YAGNI: boring CRUD + clear dashboard over clever architecture

---

## Technologies used

| Layer | Stack |
|-------|--------|
| Backend | **Laravel 13.21**, PHP **8.4**, SQLite |
| Auth | **Laravel Fortify** (login + logout only) |
| Frontend | **Vue 3**, **Inertia.js 3**, TypeScript |
| Styling | **Tailwind CSS 4**, **shadcn-vue** |
| Tooling | Vite 8, Wayfinder, PHPUnit 12, Pint, ESLint, Prettier |
| Design notes | `PRODUCT.md`, `DESIGN.md` (product-dashboard direction) |

### Key packages

- `laravel/framework` — application core
- `laravel/fortify` — authentication
- `inertiajs/inertia-laravel` + `@inertiajs/vue3` — server-driven SPA
- `laravel/wayfinder` — typed frontend routes
- `phpunit/phpunit` — feature tests

---

## Features implemented

### Required (assessment)

#### Ticket management (CRUD)

| Action | Description |
|--------|-------------|
| **List** | Dashboard table / mobile list of tickets (filterable; default newest first) |
| **View detail** | Click a row or mobile card to open a detail modal (full fields + date/time) |
| **Create** | Form to open a new ticket |
| **Edit** | Update any field, including status |
| **Delete** | Remove a ticket with confirmation |
| **Update status** | Open → In Progress → Resolved → Closed via edit form |

#### Ticket fields

| Field | Notes |
|-------|--------|
| Title | Required, max 255 chars |
| Category | Hardware · Software · Network · Access · Other |
| Priority | Low · Medium · High |
| Status | Open · In Progress · Resolved · Closed |
| Assigned person | Free text (assignee name) |
| Created date | Stored on create; shown on list |
| Notes | Optional; shown on hover tooltip on list (not inline) |

#### Dashboard summary cards

| Card | Rule |
|------|------|
| **Total Tickets** | All tickets |
| **Open Tickets** | `status = Open` |
| **In Progress Tickets** | `status = In Progress` |
| **High Priority Tickets** | `priority = High` (**all statuses**) |

#### UI

- Responsive layout (sidebar drawer on phone; fixed sidebar on desktop)
- Clear hierarchy: cards → filters → list
- Phone: stacked ticket list with large touch actions; tablet/desktop: dense table
- Color badges for status and priority
- Empty state when there are no tickets
- Demo credentials visible on login

### Showcase auth

- Login / logout only
- Ticket routes protected with `auth` middleware
- Guest `/` and `/dashboard` redirect to login
- Authenticated `/` redirects to dashboard
- `/register` is not available (404)
- Demo user seeded for reviewers

### Bonus (assessment — all implemented)

| Bonus | Implementation |
|-------|----------------|
| **Search and filter** | Search box (title, assignee, notes, category) + filters for status, priority, category |
| **Status color indicators** | Color badges for every status and priority |
| **Notes / comments** | Optional `notes` field on create/edit; hover tooltip on list title; searchable |
| **Sorting** | Sort by created date, priority, status, title, category, or assignee (asc/desc) |
| **Pagination** | 10 tickets per page; prev/next + page numbers; keeps filters in the query string |

**Filter behavior:** summary cards always show **global** counts; the table shows the filtered + paginated result set. Query string is shareable (`?search=vpn&status=Open&sort=priority&page=2`).

### Extra polish

- LeadGeeks Inc brand theme (light shell + orange accent + Archivo)
- Realistic seed data (20 IT office scenarios → 2 pages at 10/page)
- Feature tests for CRUD, stats, filters, and auth strip
- Empty state + “no matches” state when filters return nothing

### Not in scope (by design)

- Registration / password reset / roles / multi-user ACL
- Email notifications, queues, realtime updates
- File attachments, multi-tenant, mobile native apps

---

## Screenshots

> Optional: add screenshots after manual QA / deploy.

| Screen | Path (suggested) |
|--------|------------------|
| Login + demo box | `docs/screenshots/login.png` |
| Dashboard (cards + table) | `docs/screenshots/dashboard.png` |
| Create ticket | `docs/screenshots/create.png` |
| Edit ticket | `docs/screenshots/edit.png` |

```markdown
<!-- Example once files exist:
![Login](docs/screenshots/login.png)
![Dashboard](docs/screenshots/dashboard.png)
-->
```

---

## Setup instructions

### Requirements

| Tool | Version |
|------|---------|
| PHP | **8.3–8.5** (tested on **8.4.23**) |
| Composer | 2.x |
| Node.js | **22.x** + npm |
| SQLite | PHP `pdo_sqlite` / `sqlite3` enabled |
| OS | Windows, macOS, or Linux |

Verify:

```bash
php -v
php -m | grep -i sqlite   # Windows: php -m | findstr sqlite
composer -V
node -v
npm -v
```

### 1. Clone

```bash
git clone https://github.com/cihuyyama/leadgeeks-technical-test.git
cd leadgeeks-technical-test
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Environment

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Ensure SQLite is configured (default in `.env.example`):

```env
DB_CONNECTION=sqlite
# DB_DATABASE is optional; Laravel uses database/database.sqlite by default
```

### 4. Create SQLite database file

**Unix / Git Bash / macOS:**

```bash
touch database/database.sqlite
```

**Windows PowerShell:**

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

### 5. Migrate + seed

```bash
php artisan migrate --seed
```

This creates tables and seeds:

- Demo user: `demo@leadgeeks.test` / `password`
- Sample IT tickets (realistic office scenarios)

### 6. Frontend

```bash
npm install
npm run build
```

### 7. Run the app

**Recommended (dev — PHP server + Vite + queue watcher via Composer script):**

```bash
composer run dev
```

Then open **http://localhost:8000** and log in with the demo credentials.

**Simple production-style run (assets already built):**

```bash
php artisan serve
```

---

## Environment variables

Main values from `.env.example` (safe defaults for local demo):

| Variable | Example | Purpose |
|----------|---------|---------|
| `APP_NAME` | `Laravel` | App name |
| `APP_ENV` | `local` | Environment |
| `APP_DEBUG` | `true` | Debug mode |
| `APP_URL` | `http://localhost:8000` | Base URL |
| `DB_CONNECTION` | `sqlite` | Database driver |
| `SESSION_DRIVER` | `database` | Session storage |
| `QUEUE_CONNECTION` | `database` | Queue (not required for core demo) |

Do **not** commit `.env`. Only `.env.example` is in the repo.

---

## Default data after seed

### Demo user

| Field | Value |
|-------|--------|
| Name | Demo User |
| Email | `demo@leadgeeks.test` |
| Password | `password` |

### Sample tickets

Seeded tickets cover common IT office cases, for example:

- Hardware failures (laptop, printer, monitor)
- Software install / license issues
- Network / Wi‑Fi / VPN problems
- Access requests (email, shared drive, apps)

Reset anytime:

```bash
php artisan migrate:fresh --seed
```

---

## Application structure

```text
it-ticket-dashboard/
├── app/
│   ├── Enums/                    # TicketCategory, TicketPriority, TicketStatus
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── TicketController.php
│   │   │   └── Settings/         # Profile / security (password only)
│   │   └── Requests/Ticket/      # Store + Update validation
│   ├── Models/
│   │   ├── Ticket.php
│   │   └── User.php
│   └── Providers/
│       └── FortifyServiceProvider.php   # loginView only
├── config/
│   └── fortify.php               # features => [] (login only)
├── database/
│   ├── factories/TicketFactory.php
│   ├── migrations/…_create_tickets_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php    # demo user + TicketSeeder
│       └── TicketSeeder.php
├── resources/js/
│   ├── components/tickets/       # StatusBadge, PriorityBadge
│   ├── pages/
│   │   ├── auth/Login.vue        # demo credentials box
│   │   ├── tickets/
│   │   │   ├── Index.vue         # dashboard cards + table
│   │   │   ├── Create.vue
│   │   │   └── Edit.vue
│   │   └── settings/
│   └── types/ticket.ts
├── routes/
│   ├── web.php                   # home, dashboard, tickets resource
│   └── settings.php
├── tests/Feature/
│   ├── Auth/AuthStripTest.php
│   └── Ticket/
│       ├── TicketCrudTest.php
│       └── TicketDashboardStatsTest.php
├── PRODUCT.md                    # product context
├── DESIGN.md                     # visual system notes
└── README.md                     # this file
```

---

## Routes

| Method | URI | Name | Access |
|--------|-----|------|--------|
| GET | `/` | `home` | Guest → login · Auth → dashboard |
| GET | `/login` | `login` | Guest |
| POST | `/login` | `login.store` | Guest |
| POST | `/logout` | `logout` | Auth |
| GET | `/dashboard` | `dashboard` | Auth — ticket index + stats |
| GET | `/tickets/create` | `tickets.create` | Auth |
| POST | `/tickets` | `tickets.store` | Auth |
| GET | `/tickets/{ticket}/edit` | `tickets.edit` | Auth |
| PUT/PATCH | `/tickets/{ticket}` | `tickets.update` | Auth |
| DELETE | `/tickets/{ticket}` | `tickets.destroy` | Auth |
| GET | `/settings/profile` | `profile.edit` | Auth |
| GET | `/settings/security` | `security.edit` | Auth (password only) |
| GET | `/settings/appearance` | `appearance.edit` | Auth |

List routes:

```bash
php artisan route:list
```

---

## Architecture notes

### Routing & auth

- Home `/` never renders a marketing landing page for guests — it redirects to login.
- `/dashboard` is the ticket list (`TicketController@index`), not a separate marketing dashboard.
- Fortify `features` is an empty array → registration, reset, verification, 2FA, and passkeys stay off.
- Ticket routes use `auth` middleware only (no `verified`).

### Domain model

- `Ticket` uses PHP enums for category, priority, and status (validated on store/update).
- High Priority card counts **priority = High across all statuses** (not only open tickets).
- Soft complexity avoided: no ticket history log, no comments table, no assignments relation table.

### Frontend

- Inertia pages under `resources/js/pages/tickets/`.
- Shared badges: `StatusBadge.vue`, `PriorityBadge.vue`.
- Layout: app sidebar shell from the Laravel Vue starter kit (simplified for showcase).
- Login page includes a permanent **Demo access** panel for reviewers.

### Design context

- `PRODUCT.md` — product goals and tone
- `DESIGN.md` — visual system (calm product dashboard, no marketing chrome)

---

## Testing

Feature tests cover:

- Guest vs authenticated access
- Login redirect to dashboard
- Register route unavailable
- Ticket create / update / delete + validation
- Dashboard stats (including high-priority across statuses)

Run the suite:

```bash
php artisan test
```

Expected (approx.):

```text
Tests:  21 skipped, 38 passed
```

Skipped tests belong to Fortify features that were intentionally disabled (registration, reset, 2FA, etc.). They are left in place but skip when the feature is off.

Targeted runs:

```bash
php artisan test --filter=Ticket
php artisan test --filter=AuthStrip
```

---

## Useful commands

```bash
# Development (server + Vite)
composer run dev

# Reset database + reseed demo data
php artisan migrate:fresh --seed

# Frontend
npm run dev
npm run build
npm run lint
npm run format

# Quality
php artisan test
vendor/bin/pint --dirty

# Inspect
php artisan route:list
php artisan about
```

---

## Troubleshooting

| Problem | Fix |
|---------|-----|
| `could not find driver (sqlite)` | Enable `pdo_sqlite` / `sqlite3` in `php.ini`, restart terminal |
| Blank page / missing styles | Run `npm install && npm run build` |
| 500 after clone | `cp .env.example .env` then `php artisan key:generate` |
| Empty tickets / wrong login | `php artisan migrate:fresh --seed` |
| Port 8000 in use | `php artisan serve --port=8001` |
| Wayfinder / route TS errors after pull | `php artisan wayfinder:generate --with-form` then rebuild |
| Windows path / PHP version | Prefer PHP 8.4 on PATH (e.g. Laragon 8.4) |

---

## Deployment notes

This is a full Laravel app (PHP + SQLite), **not** a static SPA.

### Recommended: GitHub Actions → GHCR → VPS (pull only)

Build runs on **GitHub** (not on a slow laptop, not on a small VPS). The VPS only pulls the image and restarts the container.

```text
git push main  →  Actions build  →  ghcr.io/cihuyyama/leadgeeks-technical-test
                                              ↓
                         VPS: podman pull + run (port 3002)
                                              ↓
              nginx TLS  leadgeeks-ticket.iqbalalhabib.my.id
```

| Item | Value |
|------|--------|
| Workflow | `.github/workflows/docker.yml` |
| Image | `ghcr.io/cihuyyama/leadgeeks-technical-test` |
| Tags | `:latest` (on `main`), `:sha-…`, `:v*` on version tags |
| Deploy script | `scripts/deploy-from-ghcr.sh` |
| App port on VPS | **3002** (host network; nginx proxies TLS) |

**Daily flow**

1. `git push origin main`
2. Wait for **Docker image (GHCR)** workflow to go green
3. On VPS: `bash ~/leadgeeks-ticket/scripts/deploy-from-ghcr.sh latest`

One-time VPS: clone repo for the script, `chmod +x scripts/deploy-from-ghcr.sh`, create `~/.leadgeeks-ticket-env` (`APP_URL`, stable `APP_KEY`, …), `podman login ghcr.io`, nginx + certbot for the subdomain.

### Local container (optional)

```bash
podman compose up -d --build
# http://localhost:8080
```

### Hosting fit

| Hosting style | Fit |
|---------------|-----|
| VPS + nginx + container (this repo) | Best for this demo |
| Railway / Laravel Cloud / Forge | Good |
| Pure Netlify / Vercel static | Poor fit |

After deploy, put the public URL in the [Live demo](#live-demo) section above.

---

## Author

| | |
|--|--|
| **Name** | Muhammad Iqbal Al Habib |
| **Email** | miqbalalhabib@gmail.com |
| **Portfolio** | [iqbalalhabib.my.id](https://iqbalalhabib.my.id) |
| **GitHub** | [github.com/cihuyyama](https://github.com/cihuyyama) |
| **Location** | Yogyakarta, Indonesia |

Built for the **LeadGeeks Inc. — IT Staff** technical assessment (Option 1: Internal IT Ticket Dashboard).

---

## License

MIT — assessment / portfolio showcase.

```
Copyright (c) 2026 Muhammad Iqbal Al Habib
```

You may use this project as a reference. The LeadGeeks assessment itself remains subject to their hiring process rules.
