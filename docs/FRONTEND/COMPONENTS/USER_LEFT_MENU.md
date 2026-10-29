# Left Menu — Sidebar Navigation

**Location:** `resources/js/components/user/items/navbars/LeftMenuComponent.vue`  
**Mixin:** `resources/js/mixins/navbar_pages_mixin.js`  
**Used in:** `user.climbing.ge` admin/user panel

---

## Overview

The user/admin panel shell (`user/HomeComponent.vue`) has three navigation pieces, all fed by the same menu tree:

| Piece | File | Shown |
|---|---|---|
| Top bar (`.cg-topbar`, keeps class `admin_page_header_navbar`) | `items/navbars/NavbarComponent.vue` | always |
| Desktop sidebar (`.cg-sidebar`) | `items/navbars/LeftMenuComponent.vue` (global `<left-menu />`) | > 993 px, only on pages that render `<left-menu />` |
| Mobile drawer (`.cg-drawer`, teleported to `<body>`) | inside `NavbarComponent.vue` | ≤ 991 px, every logged-in page |

Both the sidebar and the drawer render `items/navbars/AdminMenuTreeComponent.vue`, which reads the menu config from `navbar_pages_mixin.js`, filters every entry with CASL `$can()`, and adds a **"Find a page…" filter**, section icons (`SECTION_ICONS` map keyed by the menu item's `name`) and an accordion with the open group persisted in `localStorage`.

Pages opt into the desktop sidebar by rendering `<left-menu />` (57 list/dashboard pages do; add/edit forms and canvas editors deliberately don't). Its mount/unmount emits `left-menu-mounted` / `left-menu-unmounted`, which the top bar uses to show its sidebar toggle.

---

## Menu Item Types

The mixin's `admin_all_menu()` method returns an array with two kinds of items:

### 1. Direct link item

```javascript
{
  id: 1,
  title: "Home",
  route: "/"
}
```

Renders as a `<router-link>` directly in the sidebar.

### 2. Dropdown group

```javascript
{
  title: "Guide",
  name: "guide",
  routes: [
    {
      name: "Outdoor climbing",
      route: "/article/outdoor",
      permissions: [
        ['add', 'article'],
        ['edit', 'article'],
        ['del', 'article'],
      ]
    },
    {
      name: "Events & Competitions",
      route: "/event",
      permissions: [
        ['add', 'event'],
        ['edit', 'event'],
        ['del', 'event'],
      ]
    }
  ]
}
```

Renders as a collapsible dropdown. The group heading is only shown if at least one child route passes the permission check. Child routes without a `permissions` key are always visible.

### Permission format

Each entry in `permissions` is a `[action, subject]` pair matching CASL's `$can(action, subject)` signature. A user needs **any one** permission from the array to see that menu item (OR logic, not AND).

```javascript
permissions: [
  ['add', 'article'],     // user can add articles
  ['edit', 'article'],    // OR can edit articles
  ['del', 'article'],     // OR can delete articles
]
```

---

## Full Menu Structure

Defined in `navbar_pages_mixin.js`. Sections visible by permission:

| Section | Routes | Permissions required |
|---|---|---|
| **Home** | `/` | None (always visible) |
| **Favorites** | favorite products, outdoor areas, events | None |
| **Guide** | Indoor, Outdoor, Sectors & Routes, Ice, Mountaineering, News, Tech Tips, Events, Local Business, etc. | `article:add/edit/del`, `route:*`, `sector:*`, `event:*`, etc. |
| **Guide Dashboard** | Dashboard, task panel | `guide_dashboard:show`, `guide_task:*` |
| **Shop** | My purchases, Products, Warehouses, Orders, Tours, Reservations, Services | `product:*`, `order:edit_order_status`, `tour:*`, etc. |
| **Shop Dashboard** | Shop task panel | `shop_task:*` |
| **Blog** | Posts | `post:add/edit/del` |
| **Summit** | Summits | `summit:add/edit/del` |
| **Site Options** | About, Coefficients, Sliders, Followers, Users & Permissions, Comments, Gallery | `site_data:*`, `coefficient:show`, `user:*`, `gallery:*`, etc. |

---

## Permission Methods

### `haveMenuButPermission(permissions)`

Checks if the current user has **any** of the given `[action, subject]` pairs. Used for individual route items.

```javascript
// returns true if $can('add', 'article') OR $can('edit', 'article') OR $can('del', 'article')
haveMenuButPermission([['add', 'article'], ['edit', 'article'], ['del', 'article']])
```

### `haveMenuBlockPermission(menu_section)`

Checks if the user has permission for **at least one** route in a dropdown group. If all child routes fail permission checks, the entire group header is hidden.

---

## Sidebar state (LeftMenuComponent)

| Property / method | Description |
|---|---|
| `menu_position` | Whether the sidebar is open |
| `animate_enabled` | Enables the slide transition only while toggling |
| `syncMenuPosition()` | Restores the persisted state on mount (forced closed ≤ 993 px) |
| `set_menu_position(open, remember)` | Opens/closes, persists, toggles `body.sidebar-open`, emits `sidebar-state` |
| `apply_sidebar_margin(open)` | Clears inline margins set by full-screen editors and toggles `body.sidebar-open` |

The sidebar also shows the logged-in user's card (from the Pinia `auth` store) linking to `/options`.

## Menu tree (AdminMenuTreeComponent)

| Property / method | Description |
|---|---|
| `query` | Text of the "Find a page…" filter — matching groups are force-expanded |
| `open_dropdowns` | `{ groupTitle: true/false }`, persisted in `localStorage` |
| `permissionsKey` | Incremented on `permissions-loaded` to recompute the filtered menu |
| `haveMenuButPermission(perms)` | OR-check of `[action, subject]` pairs (no perms → visible) |
| `is_open(item)` | Persisted state, or open by default when the group contains the active route |
| `navigate` (event) | Emitted on link click — the drawer closes on it |

---

## Responsive Behavior

| Viewport | Sidebar | Drawer |
|---|---|---|
| `> 993 px` | Open by default (persisted) — `body.sidebar-open` adds `margin-left: 280px` to the page and the top bar | hidden |
| `≤ 993 px` | hidden | Burger button in the top bar opens it over a blurred backdrop |

Full-width canvas editors toggle `.is-wide` on `.cg-admin__content` while mounted to lift the content max-width.

---

## Event Bus

| Event | Emitted by | Used by |
|---|---|---|
| `permissions-loaded` | top bar / `HomeComponent` | menu tree (recompute), top bar (stop loading) |
| `sidebar-toggle` | top bar toggle button | `LeftMenuComponent` |
| `sidebar-state` | `LeftMenuComponent` | top bar (hides its brand while the sidebar shows one) |
| `left-menu-mounted` / `left-menu-unmounted` | `LeftMenuComponent` | top bar (shows/hides the desktop toggle) |

---

## Persistence

| Key | What is stored |
|---|---|
| `left_menu_position` | `'true'` / `'false'` — sidebar open/closed state |
| `left_menu_open_dropdowns` | JSON object `{ Guide: true, Shop: false, ... }` |

---

[Go back](../FRONTEND.md)
