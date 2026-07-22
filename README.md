# Internal IT Ticket Dashboard

Simple internal IT support ticket dashboard built for the **LeadGeeks IT Staff** technical assessment (Option 1).

**Author:** Muhammad Iqbal Al Habib  
**Email:** miqbalalhabib@gmail.com  
**Portfolio:** https://iqbalalhabib.my.id  
**GitHub:** https://github.com/cihuyyama

---

## Project Overview

This app lets IT staff track and manage internal support tickets: create, edit, update status, delete, and view a dashboard with summary counts. Authentication is a **login-only showcase** so reviewers can try the product without registration friction.

---

## Technologies Used

| Layer | Stack |
|-------|--------|
| Backend | Laravel 13, PHP 8.4, SQLite |
| Auth | Laravel Fortify (login + logout only) |
| Frontend | Vue 3, Inertia.js, TypeScript, Tailwind CSS 4 |
| UI | shadcn-vue (Card, Badge, Button, Input, …) |
| Tooling | Vite, Wayfinder, PHPUnit |

---

## Demo Login

| Field | Value |
|-------|--------|
| **Email** | `demo@leadgeeks.test` |
| **Password** | `password` |

Credentials are also shown in a **Demo access** box on the login page.

---

## Features Implemented

### Required

- Ticket **CRUD** (create, edit, update status, delete, list)
- Fields: title, category, priority, status, assigned person, created date
- Status options: Open · In Progress · Resolved · Closed
- Dashboard summary cards:
  - **Total Tickets**
  - **Open Tickets**
  - **In Progress Tickets**
  - **High Priority Tickets** — count where `priority = High` (all statuses)
- Responsive layout with scannable cards + table

### Showcase auth

- Login / logout only (no register, password reset, or roles)
- Ticket routes protected with `auth` middleware
- Demo user seeded for reviewers

### Bonus

- Color badges for status and priority
- Notes field on create/edit (shown as snippet on the list)
- Empty state when there are no tickets
- Seed data: ≥ 8 realistic IT tickets

### Categories & priorities

- Categories: Hardware · Software · Network · Access · Other  
- Priorities: Low · Medium · High  

---

## Setup Instructions

### Requirements

- PHP **8.3–8.5** (tested on **8.4.23**)
- Composer 2.x
- Node.js **22.x** and npm
- SQLite extension enabled

### Install

```bash
# 1. Clone
git clone <your-repo-url> it-ticket-dashboard
cd it-ticket-dashboard

# 2. PHP dependencies
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. SQLite database file (if missing)
# Windows PowerShell:
#   New-Item -ItemType File -Path database/database.sqlite -Force
# Unix:
#   touch database/database.sqlite

# 5. Migrate + seed (demo user + sample tickets)
php artisan migrate --seed

# 6. Frontend
npm install
npm run build

# 7. Run (Laravel 13 concurrent dev: server + Vite)
composer run dev
```

Open **http://localhost:8000** and sign in with the demo credentials above.

### Production-style (assets already built)

```bash
php artisan migrate --seed --force
npm run build
php artisan serve
```

### Useful commands

```bash
php artisan migrate:fresh --seed   # reset DB + demo data
php artisan test                   # PHPUnit suite
php artisan route:list
npm run build
```

---

## Live Demo

> Add the public URL after deploy (PHP host / VPS / Railway / Laravel Cloud — not pure static Netlify/Vercel).

**URL:** _TBD_  
Login with `demo@leadgeeks.test` / `password`.

---

## Architecture notes

- Home `/` redirects guests to login and authenticated users to `/dashboard`.
- `/dashboard` is the ticket index (4 cards + table).
- Fortify `features` array is empty → registration, reset, email verification, 2FA, and passkeys are disabled.
- Design context for agents: `PRODUCT.md`, `DESIGN.md` (Impeccable product register).

---

## License

MIT (assessment showcase).
