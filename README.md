# PKM Work — PKM Portal

A personal knowledge management (PKM) workspace built as a **Modern Monolith** — Laravel 11 backend with a Vue 3 + Inertia.js frontend. The UI shell is inspired by Vercel and Nextcloud, powered by PrimeVue 4.

---

## Tech Stack

| Layer    | Technology                                                      |
| -------- | --------------------------------------------------------------- |
| Backend  | Laravel 11 · PHP 8.2+                                           |
| Frontend | Vue 3 · Inertia.js v2                                           |
| Styling  | Tailwind CSS v3 · PrimeVue 4 · `@primeuix/themes` (Aura preset) |
| Icons    | PrimeIcons 7                                                    |
| Routing  | Ziggy (named routes in JS)                                      |
| Build    | Vite 6                                                          |
| Runtime  | WSL2 · PHP built-in server or Laravel Sail                      |

---

## Project Structure

```
resources/js/
├── app.js                        # PrimeVue setup, theme (violet primary, Aura base)
├── Layouts/
│   ├── WorkLayout.vue            # Global shell — sidebar + content column
│   ├── GuestLayout.vue           # Unauthenticated pages
│   └── AuthenticatedLayout.vue   # Legacy authenticated layout
├── Components/
│   └── Layouts/
│       ├── WorkSidebar.vue       # Vercel-style sidebar (workspace, search, nav groups)
│       ├── AnnouncementBanner.vue# Dismissible info banner
│       ├── WorkFooter.vue        # Bottom footer strip
│       ├── WorkTopbar.vue        # Horizontal topbar (tabs variant — unused by default)
│       └── WorkSubNav.vue        # Module quick-nav bar (tabs variant — unused by default)
└── Pages/
    ├── Portal/
    │   └── Overview.vue          # Dashboard — stats, recent notes, tasks, activity feed
    ├── Admin/
    │   └── Dashboard.vue
    └── Auth/
        └── Login.vue  Register.vue  …
```

---

## Global Shell (`WorkLayout`)

```
┌────────────────────────────────────────────────────────┐
│  WorkSidebar (240px)         │  Content column          │
│  ─────────────────           │  ─────────────────────── │
│  [PW] PKM Work  Hobby ▾      │  AnnouncementBanner      │
│  ┌─────────────────────┐     │  ┌─────────────────────┐ │
│  │ Find...          F  │     │  │ Page topbar          │ │
│  └─────────────────────┘     │  │ (title · dark · 👤) │ │
│                               │  └─────────────────────┘ │
│  Overview                     │                           │
│  Notes                        │  <slot />  (page content) │
│  Tasks                        │                           │
│  Analytics                    │                           │
│  Activities                   │                           │
│  Calendar  ▸                  │                           │
│  Mail                         │                           │
│  Files                        │                           │
│  ─────────────────            │                           │
│  Contacts                     │                           │
│  Integrations                 │                           │
│  Storage                      │                           │
│  Tags                         │                           │
│  Deck  ▸                      │                           │
│  Collective  ▸                │                           │
│  Forms                        │                           │
│  ─────────────────            │                           │
│  Usage                        │                           │
│  Support                      │                           │
│  Settings  ▸                  │  WorkFooter               │
└──────────────────────────────────────────────────────────┘
```

### Features

- **Workspace switcher** — dropdown with workspace list
- **Keyboard search** — `F` key or `Ctrl+K` opens global search dialog
- **Dark mode** — toggle persisted to `localStorage`
- **Expandable nav groups** — Calendar, Deck, Collective, Settings
- **Active state** — auto-detected via Ziggy `route().current()`
- **Announcement banner** — dismissible, shown at top of content column

---

## Using the Layout in a Page

```vue
<script setup>
import WorkLayout from "@/Layouts/WorkLayout.vue";
defineOptions({ layout: WorkLayout });
</script>

<template>
    <!-- your page content -->
</template>
```

---

## Getting Started

### Requirements

- PHP 8.2+
- Node 20+
- Composer

### Install

```bash
git clone <repo-url>
cd pkm-portal

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate
```

### Run (all services at once)

```bash
composer run dev
```

This starts Laravel, queue, Pail log viewer, and Vite concurrently.

Or run them separately:

```bash
php artisan serve          # Laravel dev server  → http://127.0.0.1:8000
npm run dev                # Vite HMR
```

---

## Routes

| URL                 | Name               | Page                |
| ------------------- | ------------------ | ------------------- |
| `/`                 | `home`             | `Portal/Overview`   |
| `/overview`         | `overview`         | `Portal/Overview`   |
| `/portal/`          | `portal.overview`  | `Portal/Overview`   |
| `/portal/dashboard` | `portal.dashboard` | `Portal/Dashboard`  |
| `/portal/news`      | `portal.news`      | `Portal/News/Index` |
| `/portal/news/{id}` | `portal.news.show` | `Portal/News/Show`  |
| `/portal/{module}`  | `portal.module`    | `Shared/EmptyState` |
| `/profile`          | `profile.edit`     | `Profile/Edit`      |

---

## Roadmap

- [x] **Phase 0** — Project init, folder structure, documentation
- [x] **Phase 1** — Global UI Shell (Vercel-style sidebar, WorkLayout, Overview page)
- [x] **Phase 1+** — PrimeVue 4 + `@primeuix/themes` integration (violet preset)
- [ ] **Phase 2** — Authentication & Role-Based Access Control (Admin / User)
- [ ] **Phase 3** — Notes editor (Tiptap or ProseMirror)
- [ ] **Phase 4** — Tasks, Calendar, Mail modules
- [ ] **Phase 5** — PokeAPI mock data integration for demo content
- [ ] **Phase 6** — Docker / Sail production-ready setup

---

## Theme Customisation

The PrimeVue theme is defined in [`resources/js/app.js`](resources/js/app.js) using `definePreset`:

```js
import { definePreset } from "@primeuix/themes";
import Aura from "@primeuix/themes/aura";

const PKMPreset = definePreset(Aura, {
    semantic: {
        primary: {
            500: "{violet.500}",
            // … full palette
        },
    },
});
```

Dark mode is toggled by adding/removing the `.dark` class on `<html>`, controlled by the topbar button and persisted in `localStorage` as `pkm-dark-mode`.
