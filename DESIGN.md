---
name: LeadGeeks IT Ticket Dashboard
register: product
platform: web
density: product-dashboard
accent: leadgeeks-orange
typography: Archivo
components: shadcn-vue
brand: LeadGeeks Inc (leadgeeksinc.com)
---

# Design System

Visual system for a calm, scannable **product dashboard** tinted with **LeadGeeks Inc** brand identity. Design still serves the ticket workflow — it is not a marketing site — but colors, type, and logo should feel like an internal LeadGeeks tool.

Source of truth for components: **shadcn-vue** + Tailwind tokens in `resources/css/app.css`. Brand values come from [leadgeeksinc.com](https://leadgeeksinc.com/) (not the unrelated real-estate domain leadgeeks.com).

## Brand anchors (from leadgeeksinc.com)

| Token | Hex | Role |
|-------|-----|------|
| Navy ink | `#171151` | Foreground / sidebar / headings kinship |
| Orange primary | `#EF4D05` | CTAs, focus ring, high-priority emphasis |
| Orange secondary | `#F9943B` | Soft accent wash, secondary highlights |
| Cool surface | `#F2F5FA` | App background |
| Cool border | `#D1D6E0` | Borders / inputs |
| Font | **Archivo** | Body + UI labels (site uses Archivo; avoid Days One display in product UI) |

Logo assets: `public/images/leadgeeks-mark.png`, `public/images/leadgeeks-logo.png`.

## Theme

| Choice | Decision |
|--------|----------|
| Mode | Light by default (office desk, daytime review) |
| Strategy | Restrained: navy-tinted neutrals + orange accent ≤10% of surface |
| Surface | Flat. Soft borders and light elevation only where shadcn already does |
| Density | Product-dashboard: tight padding, scannable rows, low chrome |
| Motion | Minimal. Instant or short opacity/transform on open/close only |
| Sidebar | Deep navy brand panel; white mark + product name |

Physical scene: LeadGeeks IT staff at a desk under cool office light, scanning tickets between Slack and email. Quiet instrument panel with company orange on primary actions only.

## Color

### Core (align with shadcn semantic roles)

| Role | Direction | Notes |
|------|-----------|--------|
| Background | Cool blue-gray `#F2F5FA` | Not cream / sand / warm paper |
| Foreground | Navy `#171151` | Body text ≥4.5:1 on background |
| Muted | Light cool gray + mid navy-gray text | Muted text still ≥4.5:1 where it is primary content |
| Border | `#D1D6E0` | 1px default; no thick side stripes |
| Primary / accent | Orange `#EF4D05` | Buttons, links, focus ring |
| Destructive | Red | Delete only (high priority uses primary orange for emphasis, not red-only) |

Prefer HSL tokens that map onto existing `--primary`, `--muted`, `--destructive`, etc. Update CSS variables in `app.css` rather than hard-coding one-off classes.

### Status (badges + optional row hints)

| Status | Color family | Text label always visible |
|--------|--------------|---------------------------|
| Open | Amber | Open |
| In Progress | Blue | In Progress |
| Resolved | Green | Resolved |
| Closed | Slate / gray | Closed |

### Priority

| Priority | Color family | Text label always visible |
|----------|--------------|---------------------------|
| High | Red | High |
| Medium | Amber | Medium |
| Low | Gray | Low |

Badge recipe: soft tinted background + matching ink + border optional. Never color-only chips. Never gradient fills on badges.

## Typography

| Role | Spec |
|------|------|
| Font family | `Inter`, `ui-sans-serif`, `system-ui`, sans-serif (fallback stack). Instrument Sans from the starter is acceptable until Inter is loaded; prefer Inter/system-ui for final polish. |
| Page title | ~1.25–1.5rem, semibold, tight tracking |
| Section / card title | ~0.875–1rem, medium/semibold |
| Body / table | 0.875rem (14px) default for density |
| Meta / timestamps | 0.75–0.8125rem, muted foreground |
| Line length | Forms and descriptions ~65ch max; tables may span full content width |

No display/hero type scales. No gradient text. Use `text-wrap: balance` only on short headings if needed.

## Layout

### App shell

- Simple top bar or slim sidebar from the Laravel Vue starter: app name, nav to tickets, user/logout.
- Main content: max width comfortable for tables (full usable width on large screens; avoid narrow marketing columns).
- Page padding: consistent horizontal rhythm (e.g. 1–1.5rem mobile, 1.5–2rem desktop).

### Dashboard composition

1. **Header row:** page title + primary action (New ticket).
2. **Summary strip:** exactly **four** cards in one responsive row:
   - Total Tickets
   - Open Tickets
   - In Progress Tickets
   - High Priority Tickets
3. **Ticket list (adaptive):**
   - **md+ (tablet landscape / desktop):** dense HTML table for scan speed
   - **&lt; md (phone):** stacked list rows with title, badges, meta, and full-width Edit/Delete (min ~44px touch targets)
   - Not a marketing card grid; mobile rows stay flat inside the same list surface

Summary cards: flat Card, label + large number, no icons required, no sparkline charts. On small screens, summary cards wrap to 2×2.

### Ticket table columns

Title · Category · Priority · Status · Assigned · Created · Actions

Priority and status render as badges. Actions: edit / delete (and status change if inline). Dense row height; hover state subtle.

### Forms (create / edit)

Single-column form, labels above fields. Use Input, Select, Textarea, Button from shadcn-vue. Group actions: primary Save, secondary Cancel. Validation errors under fields, not only toasts.

### Login

Centered card, minimal. Email + password + Submit. **Demo credentials block** visible without scrolling on common laptop heights (email `demo@leadgeeks.test`, password `password`). No register or forgot-password links.

## Components

Prefer existing shadcn-vue:

| Need | Component |
|------|-----------|
| Surfaces | Card |
| Actions | Button (default / outline / destructive / ghost) |
| Fields | Input, Label, Select, Textarea |
| Status / priority | Badge |
| Confirm delete | Dialog |
| Feedback | Sonner / toast sparingly |
| Structure | Separator, optional Sidebar from starter |

Do not add a second component library. Do not restyle every primitive into a custom brand kit.

### Radius & elevation

- Radius: stick to starter `--radius` (~0.5rem).
- Shadow: shadcn defaults only; no multi-layer glow.
- Borders: 1px; full perimeter when needed. **No left/right accent bars >1px.**

## Spacing & rhythm

- Summary cards gap: 0.75–1rem.
- Between summary strip and table: 1.25–1.75rem.
- Table cell padding: compact but touch-friendly (~0.5–0.75rem vertical).
- Vary spacing for hierarchy (header tighter to content than content to footer), not for decoration.

## Motion

- Dialogs / sheets: short fade or subtle scale, ease-out, ≤200ms.
- No staggered list entrance animations.
- No bounce or elastic easing.
- Always provide reduced-motion: disable non-essential transitions.

## Iconography

Optional, sparse. Prefer text labels for status and actions. If icons appear (plus, trash, search), use a single consistent set (e.g. Lucide already common with shadcn) at 16–20px, muted or currentColor.

## Do

- Keep the list as a **table** for comparison and scan speed
- Use **semantic badges** for status and priority with fixed color mapping
- Show **demo credentials** on login
- Keep primary CTA (New ticket / Save) visually primary with restrained blue
- Match contrast requirements; darken muted text if it fails AA
- Empty states: short sentence + button to create first ticket
- Responsive: summary cards reflow; phone uses stacked ticket list; table from `md` up

## Don't

- Purple gradients, glassmorphism, frosted stacks
- Marketing chrome inside the app (heroes, feature trios, testimonial bands)
- Nested cards for each ticket on the main list
- Side-stripe borders as the accent pattern
- Gradient text or hero-metric clichés for summary numbers
- Over-animation or autoplay motion
- Invent colors that bypass shadcn semantic tokens without updating the token layer
- Color-only status (always pair with text)

## Reference composition (dashboard)

```
┌──────────────────────────────────────────────────────────┐
│  IT Tickets                          [ New ticket ]      │
├────────────┬────────────┬────────────┬───────────────────┤
│ Total  24  │ Open  8    │ Progress 5 │ High priority 3   │
├────────────┴────────────┴────────────┴───────────────────┤
│ Title        Category   Priority  Status      Assigned … │
│ VPN down     Network    High      Open        A. Rahman  │
│ Laptop slow  Hardware   Medium    In Progress B. Lee     │
│ …                                                        │
└──────────────────────────────────────────────────────────┘
```

## Implementation notes

- Stack: Laravel 13, Vue 3, Inertia, Tailwind 4, shadcn-vue, SQLite.
- Auth routes protected with `auth`; ticket CRUD behind login.
- Seed ≥8 realistic IT-office tickets plus demo user for review.
- When changing look: edit CSS variables in `resources/css/app.css` first, then utility classes.

This file answers **how it looks**. Strategic who/why lives in `PRODUCT.md`.
