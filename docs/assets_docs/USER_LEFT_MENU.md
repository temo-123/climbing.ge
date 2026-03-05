# User Left Menu Documentation

## Overview

The User Left Menu is a Vue.js component system that provides a collapsible sidebar navigation for the user panel. It consists of the `LeftMenuComponent.vue` and uses a mixin-based approach for menu configuration.

## LeftMenuComponent.vue

**Location:** `resources/js/components/user/items/navbars/LeftMenuComponent.vue`

The LeftMenuComponent renders a collapsible left sidebar navigation menu with permission-based access control and responsive design.

### Features

- **Collapsible Sidebar:** Toggleable left sidebar that can be opened/closed
- **Permission-based Rendering:** Menu items and sections are conditionally displayed based on user permissions
- **Responsive Design:** Adapts to different screen sizes (mobile vs desktop)
- **State Persistence:** Remembers menu open/closed state in localStorage
- **Dropdown Menus:** Support for nested menu items with expand/collapse functionality
- **Dynamic Layout Adjustment:** Adjusts main content margin based on menu state

### Mixins

- `navbar`: Provides menu data through `admin_all_menu()` method

### Data Variables

- `width`: Current window width
- `menu`: Menu visibility state (boolean)
- `menu_but`: Menu button visibility state (boolean)
- `menu_array`: Menu data array (object)
- `menu_position`: Current menu position state (boolean)
- `menu_items`: Menu items from mixin (array)
- `item_1`: Individual menu item state (boolean)

### Methods

- `haveMenuButPermission(permissions)`: Checks if user has permission for individual menu buttons
- `haveMenuBlockPermission(menu_section)`: Checks if user has permission for menu sections (shows section if any item is accessible)
- `show_item(item_class)`: Toggles visibility of dropdown menu items
- `logout()`: Handles user logout (currently commented out)
- `window_size()`: Updates width and menu states based on window size
- `update_menu_position()`: Updates menu position and adjusts layout margins
- `open_menu()`: Toggles menu open/close state

### Lifecycle Hooks

- `mounted()`: Initializes menu position, handles responsive behavior, and sets initial layout
- `unmounted()`: Calls window_size (cleanup)

## Menu Data Structure

The component uses a mixin to define menu structure. The menu data is returned by `navbar.admin_all_menu()`.

### Simple Menu Item

```javascript
{
    title: "Home",
    route: "/",
}
```

### Menu Item with Permissions

```javascript
{
    title: "Task panel",
    route: "/task_panel",
    permissions: [
        ['show', 'worker']
    ]
}
```

### Dropdown Menu Section

```javascript
{
    title: "Favorites",
    name: "favorites",
    routes: [
        {
            name: "Favorite products",
            route: "/favorite_products",
            permissions: [
                ['show', 'worker']
            ]
        },
    ],
}
```

## Permission System

The component uses a `$can()` method (likely from a permissions plugin like Laravel's Gate system) to check user permissions. Permissions are defined as arrays with [action, resource] format.

Example: `['show', 'worker']` checks if user can 'show' 'worker' resources.

## Responsive Behavior

- **Desktop (>993px):** Menu can be toggled open/closed, state persists in localStorage
- **Mobile (≤993px):** Menu is hidden by default, main content margin is 0

## Layout Adjustments

When menu is open on desktop, the component adjusts:
- `body` margin-left: 22em
- `.admin_page_header_navbar` margin-left: 22em

## Mixin Implementation

```javascript
export const navbar = {
    admin_all_menu(){
        return [
            {
                title: "Home",
                route: "/",
            },
            {
                title: "Task panel",
                route: "/task_panel",
                permissions: [
                    ['show', 'worker']
                ]
            },
            {
                title: "Favorites",
                name: "favorites",
                routes: [
                    {
                        name: "Favorite products",
                        route: "/favorite_products",
                        permissions: [
                            ['show', 'worker']
                        ]
                    },
                ],
            },
        ]
    }
}
