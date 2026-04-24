# Left Menu — Sidebar Navigation

**Location:** `resources/js/components/user/items/navbars/LeftMenuComponent.vue`  
**Mixin:** `resources/js/mixins/navbar_pages_mixin.js`  
**Used in:** `user.climbing.ge` admin/user panel

---

## Overview

`LeftMenuComponent` renders a collapsible sidebar at the left edge of the admin panel. It reads the full menu config from `navbar_pages_mixin.js` and filters each item's visibility using CASL `$can()` checks so users only see sections they have permission to access.

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
| **Site Options** | About, Sliders, Followers, Users & Permissions, Comments, Gallery | `site_data:*`, `user:*`, `gallery:*`, etc. |

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

## State & Data

| Property | Type | Description |
|---|---|---|
| `menu_position` | `Boolean` | Whether the sidebar is open (bound to hidden checkbox) |
| `animate_enabled` | `Boolean` | Enables CSS transition only during toggle (prevents animation on page load) |
| `open_dropdowns` | `Object` | Map of `{ groupTitle: true/false }` — persisted in `localStorage` |
| `permissionsKey` | `Number` | Counter incremented on permission changes to force `menu_items` recomputation |

---

## Key Methods

| Method | Description |
|---|---|
| `window_size()` | Reads `window.innerWidth`, sets internal flags |
| `update_menu_position()` | Toggles sidebar open/close, updates body margin, persists to `localStorage` |
| `toggle_dropdown(menu_name)` | Opens one dropdown, closes all others, persists state |
| `is_dropdown_open(menu_name)` | Returns `true` if the named dropdown is open |
| `isActive(route)` | Returns `true` if `$route.path === route` (highlights active link) |
| `isAnySubActive(routes)` | Returns `true` if any child route is currently active (highlights parent group) |

---

## Responsive Behavior

| Viewport | Default state | Margin behavior |
|---|---|---|
| `> 993 px` | Open (persisted from `localStorage`) | `body` gets `margin-left: 22em` |
| `≤ 993 px` | Closed | No margin pushed |

When open, both `document.body` and `.admin_page_header_navbar` receive `margin-left: 22em` so content shifts right. Toggle is driven by a hidden `<input type="checkbox" id="check">` — `#check:checked ~ .sidebar` CSS rule slides the sidebar to `left: 0`.

---

## Event Bus Listeners

| Event | Action |
|---|---|
| `permissions-loaded` | Updates CASL ability + increments `permissionsKey` to re-render menu |
| `logged-in` | Fetches fresh permissions via `GET /api/get_user/get_auth_user_permissions` and updates CASL |
| `menu-toggle` | Briefly enables transition animation for smooth toggle |

---

## Persistence

| Key | What is stored |
|---|---|
| `left_menu_position` | `'true'` / `'false'` — sidebar open/closed state |
| `left_menu_open_dropdowns` | JSON object `{ Guide: true, Shop: false, ... }` |

---

[Go back](../FRONTEND.md)
