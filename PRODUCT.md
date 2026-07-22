# Product

## Register

product

## Platform

web

## Users

Primary users are internal IT staff. They open the dashboard during the workday to log requests, update status, reassign work, and close tickets. They want density over decoration: scan counts, find a row, act, move on.

Secondary users are LeadGeeks HR and technical reviewers on the live demo. They arrive once, log in with published demo credentials, and must complete a full ticket CRUD path in a few minutes without hunting for controls or getting stuck on auth.

## Product Purpose

An internal **IT Ticket Dashboard** for tracking and managing support tickets. Staff create, edit, status-update, delete, and list tickets. The home view shows four summary counts (total, open, in progress, high priority) plus a scannable ticket table.

This build is a LeadGeeks IT Staff technical assessment showcase. It must feel real enough to use, not production-multi-tenant. Auth is login-only for the demo. No register, password reset, roles, or multi-tenancy.

**Success:** A reviewer opens the live URL, signs in with demo credentials, sees the dashboard, and finishes create / edit / status change / delete without help.

## Positioning

Every screen should make ticket state obvious and actions obvious. Status and priority read at a glance; CRUD never hides behind clever UI.

## Brand Personality

**Calm · Clear · Professional · LeadGeeks.**

Visual identity aligns with **LeadGeeks Inc** ([leadgeeksinc.com](https://leadgeeksinc.com/)): navy `#171151`, orange `#EF4D05`, cool surface `#F2F5FA`, font Archivo. This is an *internal* product skin of the company brand — not a sales landing page.

Voice is plain and direct. Labels name the job (New ticket, Save, Delete). Errors say what failed and what to do next. No marketing slogans, no playful microcopy, no hype.

Emotional goal: quiet confidence. The tool should feel like a reliable LeadGeeks desk utility, not a product launch.

## Anti-references

Do not look or feel like:

- Purple gradients, neon accents, or “AI SaaS” hero chrome
- Glassmorphism, heavy blur, frosted cards stacked for decoration
- Generic AI dashboard clutter (identical icon cards, noisy charts, empty metrics)
- Over-animation: bounce, elastic, staggered entrance on every row
- Marketing landing layout inside the app (big hero, feature grids, testimonial blocks)
- Side-stripe accent borders on cards or list rows as the main visual hook

## Design Principles

1. **Density serves the job.** Prefer a tight summary strip and a full-width table over card grids of tickets.
2. **Status is the signal.** Color and badge semantics carry workflow meaning; decoration does not.
3. **Demo-first friction.** Login shows credentials; primary actions stay one click from the list.
4. **Boring structure wins.** Clear CRUD and readable hierarchy beat clever architecture or visual novelty.
5. **One system only.** Extend shadcn-vue / Tailwind tokens; do not invent a parallel design language.

## Accessibility & Inclusion

Target WCAG 2.1 AA for contrast on body text, controls, and badges. Status and priority never rely on color alone: pair color with text labels. Support keyboard focus on interactive controls. Respect `prefers-reduced-motion` (no required motion for understanding content). Layout must work from mobile to desktop; desktop-first density is fine if mobile remains usable.

## Stack & Demo

| Item | Value |
|------|--------|
| Backend | Laravel 13 |
| Frontend | Vue 3 + Inertia.js + Tailwind CSS |
| Components | shadcn-vue (Card, Badge, Button, Input, Select, Dialog, Table patterns) |
| Database | SQLite |
| Auth | Simple login only (Fortify starter, register/reset stripped) |
| Demo email | `demo@leadgeeks.test` |
| Demo password | `password` |

Demo credentials must appear on the login page and in the README so reviewers never guess.

## Surfaces

| Surface | Job |
|---------|-----|
| Login | Sign in; show demo credentials clearly |
| Dashboard / ticket list | Four summary cards + ticket table; entry to CRUD |
| Create / edit ticket | Form for title, category, priority, status, assignee |
| Delete | Confirm, then remove |

Required ticket fields: title, issue category, priority, status, assigned person, created date.

Status options: Open, In Progress, Resolved, Closed.
