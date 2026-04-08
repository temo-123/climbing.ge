# User Left Menu Documentation

## Overview

The User Left Menu is a Vue.js component providing collapsible sidebar navigation for user panel. `LeftMenuComponent.vue` uses mixin for config.

**Location:** `resources/js/components/user/items/navbars/LeftMenuComponent.vue`

### Features

- Collapsible sidebar
- Permission-based access
- Responsive design
- State persistence (localStorage)
- Dropdown menus
- Dynamic layout

### Mixins

- `navbar`: `admin_all_menu()`

### Data & Methods

See component code. Key methods: permission checks (`haveMenuButPermission`, `haveMenuBlockPermission`), toggle (`show_item`, `open_menu`), responsive (`window_size`, `update_menu_position`).

### Menu Structure

```javascript
// Simple
{ title: "Home", route: "/" }

// Permissions
{ title: "Task panel", route: "/task_panel", permissions: [['show', 'worker']] }

// Dropdown
{ title: "Favorites", name: "favorites", routes: [{ name: "Favorite products", route: "/favorite_products", permissions: [['show', 'worker']] }] }
```

Uses `$can()` for permissions [action, resource].

### Responsive

- Desktop (>993px): Toggle, persists
- Mobile: Hidden by default

<hr>

[Go back](../README.md)

