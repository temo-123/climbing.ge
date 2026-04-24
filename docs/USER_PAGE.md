# User Dashboard & Admin CMS — user.climbing.ge

The unified user dashboard and content management system for all climbing.ge subdomains.

---

## Table of Contents

- [Overview](#overview)
- [Authentication Pages](#authentication-pages)
- [User Dashboard](#user-dashboard)
- [Admin CMS Sections](#admin-cms-sections)
- [Roles & Permissions](#roles--permissions)
- [Notifications & Queues](#notifications--queues)
- [Frontend Components](#frontend-components)
- [Backend API](#backend-api)

---

## Overview

**Subdomain:** `user.climbing.ge`  
**Root Component:** `resources/js/components/user/HomeComponent.vue`  
**Router:** `resources/js/routes/UserRoutes.js`  
**API base URL:** Authenticated routes under `/api/`

The user panel serves two audiences:
- **Regular users** — profile, orders, favorites, tour reservations, notifications
- **Admin/staff users** — full CMS for all subdomains (guide, shop, blog, summit, films)

---

## Authentication Pages

All authentication UI lives in `resources/js/components/auth/`:

| Component | Route | Description |
|---|---|---|
| `LoginComponent.vue` | `/login` | Email + password login |
| `RegisterComponent.vue` | `/register` | New user registration |
| `Verify.vue` | `/verify` | Email verification prompt |
| `ForgotPassword.vue` | `/forgot-password` | Request reset link |
| `ResetPassword.vue` | `/reset-password` | Set new password with token |

See [AUTH.md](AUTH.md) for full authentication flow and API details.

---

## User Dashboard

After login, authenticated users access their personal dashboard.

### Profile & Settings

- Edit name, surname, email, avatar, bio
- Change password
- Notification preferences (email alerts for events, new products, etc.)
- API: `POST /api/get_options/user_info_update/:user_id`

### Delivery Addresses

CRUD for saved delivery addresses used in shop checkout.

- API: `GET /api/get_user_adreses`, `POST /api/add_user_adreses`, `POST /api/edit_adres/:id`, `DELETE /api/del_user_adreses/:id`

### Orders

View past shop orders, order status, and download receipts.

### Favorites

- Favorite outdoor climbing areas
- Interested events
- Favorite films

### Tour Reservations

View and manage tour bookings placed through the shop.

---

## Admin CMS Sections

Admin users with appropriate roles see additional sections in the left menu.

### Guide Admin

Manage all content for `climbing.ge`:

| Section | What You Can Manage |
|---|---|
| **Articles** | Mount routes, outdoor spots, ice climbing, indoor, events, projects |
| **Sectors** | Sector data, images, local images for climbing spots |
| **Routes** | Climbing routes, grades, bolt counts, sector assignment, route topos |
| **Multi-pitch** | Multi-pitch route pitches |
| **Regions** | Outdoor climbing regions |
| **Mount Massifs** | Mountain massif groups |
| **Events** | Climbing competitions and events |
| **General Info** | Reusable info blocks (contacts, warnings) |
| **Sliders** | Hero image sliders per section |
| **Local Businesses** | Partner businesses linked to climbing areas |
| **Team Members** | Team/staff profiles |
| **Live Cameras** | Live webcam embeds |

### Shop Admin

Manage all content for `shop.climbing.ge`:

| Section | What You Can Manage |
|---|---|
| **Products** | Product catalogue with locale data, images, options |
| **Categories / Subcategories** | Product taxonomy |
| **Brands** | Product brands |
| **Orders** | Customer orders, status updates |
| **Custom Orders** | Manual/special orders |
| **Tours** | Multi-day guided tours |
| **Tour Categories** | Tour taxonomy |
| **Reservations** | Tour bookings |
| **Services** | Additional services offered |
| **Warehouses** | Inventory tracking |
| **Sale Codes** | Discount codes |
| **Shipping Regions** | Shipping zones + prices |

### Summit Admin

Manage all content for `summit.climbing.ge`:

| Section | What You Can Manage |
|---|---|
| **Summits** | Summit database — title, height, coordinates, QR code, publish status |
| **Ascents** | All ascent records across all summits |

See [SUMMIT.md](SUMMIT.md) for full summit admin documentation.

### Blog Admin

Create, edit, publish, and delete blog posts and news articles. Uses Quill rich-text editor.

### Films Admin

Manage film catalogue: add/edit/delete films, categories, and tags.

### User Management

Manage registered users:
- View all users, search, filter by role
- Edit user roles and permissions
- Ban/unban users
- Export user data

### Tasks

Internal task/todo system for staff. Tasks can be assigned to users with due dates.

### Multimedia

Manage uploaded images and files across all subdomains.

---

## Roles & Permissions

### Backend (Spatie Laravel Permission)

Roles and permissions stored in `roles` and `permissions` tables.

**Default roles:**

| Role | Description |
|---|---|
| `super_admin` | Unrestricted access to everything |
| `admin` | Full CMS access |
| `editor` | Content creation and editing (no delete, no user management) |
| `guide` | Shop tours management |
| `user` | Standard authenticated user |

**Permission naming:** `resource:action`  
Examples: `article:add`, `article:edit`, `article:del`, `summit:add`, `summit:edit`, `product:del`, `user:manage`

Diagram: `docs/DEMO_IMAGES/User_and_admin/Laravel_role_permission.png`

### Frontend (CASL Vue)

Abilities are synced from `/api/auth_user` response on login.

```javascript
// Usage in templates
<button v-if="$can('add', 'summit')">Add Summit</button>
<router-link v-if="$can('edit', 'article')" :to="...">Edit</router-link>

// Usage in JavaScript
if (this.$ability.can('del', 'product')) { ... }
```

See [AUTH.md](AUTH.md) for full CASL setup.

---

## Notifications & Queues

### Email Queue

The platform uses Laravel Queues for sending bulk emails (event notifications, newsletter, etc.).

Queue configuration in `.env`:
```env
QUEUE_CONNECTION=database
```

Run queue worker:
```bash
php artisan queue:work                    # runs indefinitely
php artisan queue:work --queue=emails     # emails queue only
php artisan queue:work --timeout=60       # restart every 60s
php artisan horizon                       # Horizon dashboard
```

### Scheduled Notifications

Laravel Task Scheduling sends automatic event notifications to interested users.

```bash
php artisan schedule:work          # run scheduler locally
php artisan schedule:list          # list all scheduled tasks
php artisan send_event_notification:users  # run manually
```

Scheduler defined in `app/Console/Kernel.php`. Notification job: `app/Jobs/UserNotifications.php`.

Timezone: `Asia/Tbilisi` — set in `config/app.php`.

### User Notification Preferences

Each user has a `user_notifications` record controlling which email types they receive. Managed at: `GET/POST /api/get_options/get_user_notification_data` and `/api/get_options/update_user_notification_data`.

---

## Frontend Components

### Layout Components (`resources/js/components/user/items/`)

| Component | Description |
|---|---|
| `BreadcrumbComponent.vue` | Breadcrumb navigation bar |
| `LeftMenuComponent.vue` | Left sidebar navigation |
| `NavbarComponent.vue` | Top navigation bar |
| `FooterComponent.vue` | Footer |

### Data Table System (`items/data_table/`)

The admin panel uses a generic tabbed data table system. See [FRONTEND/USER_PANEL_TABLE.md](FRONTEND/USER_PANEL_TABLE.md) for full documentation.

**Components:**
- `TabsComponent.vue` — Main wrapper with tabs, search, pagination
- `TabHeaderComponent.vue` — Table `<thead>` renderer
- `TabBodyComponent.vue` — Table `<tbody>` with action buttons
- `DataComponent.vue` — Cell value renderer (supports bool icons, nested fields)
- `FilterComponent.vue` — Dropdown filter
- `PaginationComponent.vue` — Page navigation
- `SearchComponent.vue` — Text search

### Modals (`items/modal/`)

| Component | Description |
|---|---|
| `StackModal.vue` | Generic reusable modal (see FRONTEND/STACK_MODAL.md) |
| `ArticleQuickViewModal.vue` | Quick preview for guide articles |
| `tab_modals/SectorModalComponent.vue` | Sector route management |
| `tab_modals/ArticleSectorSequenceModalComponent.vue` | Sector ordering |

### Form Editors (`items/form/parts/editor/`)

Three globally registered Quill-based rich text editors:

| Name | Component | Use |
|---|---|---|
| `big_editor` | `BigEditorComponent.vue` | Full-featured: text, images, tables |
| `small_editor` | `SmallEditorComponent.vue` | Basic text formatting |
| `mini_editor` | `MiniEditorComponent.vue` | Single-line rich text |

---

## Backend API

### User Data Routes (`routes/api/get_user_routes.php`)

All routes behind `auth:sanctum` + `banned` middleware.

**User:**

| Method | Path | Description |
|---|---|---|
| GET | `/api/auth_user` | Current user + abilities |
| GET | `/api/get_user/get_auth_user_data` | Detailed user data |
| GET | `/api/get_user/get_all_users` | All users (admin) |
| GET | `/api/get_user/get_auth_user_permissions` | User's permissions array |
| POST | `/api/get_options/user_info_update/:user_id` | Update profile |
| POST | `/api/get_options/update_user_notification_data` | Update notifications |

**Addresses:**

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_user_adreses` | All saved addresses |
| POST | `/api/add_user_adreses` | Add address |
| POST | `/api/edit_adres/:id` | Edit address |
| DELETE | `/api/del_user_adreses/:id` | Delete address |

**Favorites:**

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_faworite/get_faworite_outdoor_region` | Favorite areas |
| GET | `/api/get_faworite/get_interested_events` | Interested events |
| POST | `/api/set_faworite_by_user/add_to_favorite_outdoor_area/:id` | Add favorite |
| DELETE | `/api/set_faworite/del_favorite_outdoor_area/:id` | Remove favorite |

---

[Go back](../README.md)
