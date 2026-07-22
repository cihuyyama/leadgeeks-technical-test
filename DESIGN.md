---
name: LeadGeeks IT Ticket Dashboard
register: product
platform: web
density: product-dashboard
accent: restrained-blue
typography: Inter / system-ui
components: shadcn-vue
---

# Design System

Visual system for a calm, scannable **product dashboard**. Design serves the ticket workflow. It does not sell a brand story.

Source of truth for components: **shadcn-vue** + Tailwind tokens in `resources/css/app.css`. Extend those tokens. Do not invent a second palette that fights them.

## Theme

| Choice | Decision |
|--------|----------|
| Mode | Light by default (office desk, daytime review) |
| Strategy | Restrained: tinted neutrals + one blue accent ≤10% of surface |
| Surface | Flat. Soft borders and light elevation only where shadcn already does |
| Density | Product-dashboard: tight padding, scannable rows, low chrome |
| Motion | Minimal. Instant or short opacity/transform on open/close only |

Physical scene: IT staff at a desk under cool office light, scanning tickets between Slack and email. The UI should feel like a quiet instrument panel, not a marketing site.

## Color

### Core (align with shadcn semantic roles)

| Role | Direction | Notes |
|------|-----------|--------|
| Background | Near-white, chroma ~0 (or tiny cool tint toward blue) | Avoid cream / sand / warm paper defaults |
| Foreground | Near-black slate | Body text ≥4.5:1 on background |
| Muted | Light gray surface + mid gray text | Muted text still ≥4.5:1 where it is primary content |
| Border | Light gray | 1px default; no thick side stripes |
| Primary / accent | Restrained blue | Buttons, links, focus ring, “In Progress” kinship |
| Destructive | Red | Delete and high-priority signal only |

Prefer OKLCH or HSL tokens that map cleanly onto existing `--primary`, `--muted`, `--destructive`, etc. When shifting accent toward blue, update shadcn CSS variables rather than hard-coding one-off classes everywhere.

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
3. **Ticket list:** one **HTML table** (or shadcn Table built on semantic table markup), not a grid of nested ticket cards.

Summary cards: flat Card, label + large number, no icons required, no sparkline charts. On small screens, cards wrap to 2×2; table scrolls horizontally if needed.

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
- Responsive: cards reflow; table remains usable (horizontal scroll ok)

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
