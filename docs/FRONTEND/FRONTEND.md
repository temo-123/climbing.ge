# Frontend Architecture — climbing.ge

Vue 3 + Vite (via Laravel Mix) multi-subdomain SPA.

---

## Table of Contents

- [Bundle & Boot](#bundle--boot)
- [Directory Structure](#directory-structure)
- [Global Components](#global-components)
- [Global Services](#global-services)
- [Mixins](#mixins)
- [State Management (Pinia)](#state-management-pinia)
- [Routing](#routing)
- [i18n / Localization](#i18n--localization)
- [CASL Permissions](#casl-permissions)
- [Axios Setup](#axios-setup)
- [SEO / Meta Tags](#seo--meta-tags)
- [Rich Text Editors](#rich-text-editors)
- [Subdomain Components](#subdomain-components)

---

## Bundle & Boot

**Entry:** `resources/js/app.js`

On load the boot sequence:

1. Creates Vue app
2. Registers global components (editors, modals, nav-badges, etc.)
3. Installs plugins: Pinia, Vue Router (subdomain-matched), i18n, CASL, `@unhead/vue`
4. Sets `axios.defaults.baseURL` for the active subdomain
5. Attaches axios interceptors (401/419 → logout)
6. Sets `$bus` global event bus
7. Mounts to `#app`

**Hostname matching:**

```javascript
const host = window.location.hostname

if (host === process.env.MIX_SITE_URL)      { /* mount guide */  }
else if (host === process.env.MIX_SHOP_URL) { /* mount shop */   }
else if (host === process.env.MIX_BLOG_URL) { /* mount blog */   }
// ... etc
```

---

## Directory Structure

```
resources/js/
├── app.js                          # Entry point
├── routes/
│   ├── SiteRoutes.js               # climbing.ge
│   ├── ShopRoutes.js               # shop.climbing.ge
│   ├── BlogRoutes.js               # blog.climbing.ge
│   ├── SummitRouter.js             # summit.climbing.ge
│   ├── FilmsRoutes.js              # films.climbing.ge
│   └── UserRoutes.js               # user.climbing.ge
├── store/
│   ├── index.js                    # Pinia root
│   └── auth_user.js                # Auth user module
├── services/
│   ├── ability/
│   │   └── ability.js              # CASL instance
│   └── localization/
│       └── i18n.js                 # Vue i18n setup
├── mixins/
│   ├── axios_mixin.js              # API helper methods
│   ├── easy_navigation_mixin.js    # $going navigation helper
│   ├── editor_config_mixin.js      # $editor_config Quill config
│   └── go_to_service_mixin.js      # Cross-subdomain navigation
├── components/
│   ├── global_components/          # Shared across all subdomains
│   ├── auth/                       # Login, register, verify pages
│   ├── errors/                     # Error pages (404, 500)
│   ├── guide/                      # climbing.ge components
│   ├── shop/                       # shop.climbing.ge components
│   ├── blog/                       # blog.climbing.ge components
│   ├── summit/                     # summit.climbing.ge components
│   ├── films/                      # films.climbing.ge components
│   └── user/                       # user.climbing.ge components
└── lang/
    └── i18n/
        ├── en.json
        └── ka.json
```

---

## Global Components

**Location:** `resources/js/components/global_components/`

Registered globally in `app.js` — available in every subdomain without importing.

| Component | Tag | Description |
|---|---|---|
| `StackModal` | `<stack-modal>` | Generic reusable modal |
| `NavBadgesComponent` | `<nav-badges>` | Notification/cart badge in navbar |
| `LoginModalComponent` | `<loginModal>` | Login modal overlay |
| `LocaleChangeComponent` | `<locale-switcher>` | EN/KA language toggle |
| `GoOnTopComponent` | `<go_to_top>` | Scroll-to-top button |
| `PreloaderComponent` | `<preloader>` | Full-screen page loader |
| `RouteloaderComponent` | `<routeloader>` | Route transition loader |
| `SwiperComponent` | `<swiperComponent>` | Hero image carousel |
| `SiteImgComponent` | `<site-img>` | Lazy-loading image |
| `StarReitingShowComponent` | `<star-reiting>` | Star rating display |
| `ServicesListComponent` | `<services-list>` | Services widget |
| `ViewControlsComponent` | `<view-controls-component>` | Grid/list view toggle |
| `MetaDataComponent` | `<metaData>` | SEO meta tags (per subdomain) |

### `StackModal`

The universal modal component. See [STACK_MODAL.md](STACK_MODAL.md) for full documentation.

```html
<stack-modal
  :show="modal_open"
  title="Modal Title"
  @close="modal_open = false"
  :saveButton="{ visible: true, title: 'Save' }"
  :cancelButton="{ visible: true, title: 'Cancel' }"
  @save="handleSave"
>
  <p>Modal content here</p>
  <template #footer>
    <!-- custom footer buttons -->
  </template>
</stack-modal>
```

---

## Global Services

### `$bus` — Event Bus

Simple publish/subscribe for cross-component communication.

```javascript
// Emit
this.$bus.emit('cart:updated', { count: 3 })

// Listen
this.$bus.on('cart:updated', (data) => { this.cartCount = data.count })
```

### `$going` — Navigation Helper (from `easy_navigation_mixin.js`)

Provides named route navigation with built-in scroll-to-top.

```javascript
this.$going('routeName', { id: 5 })
// equivalent to: this.$router.push({ name: 'routeName', params: { id: 5 } })
```

### `$editor_config` — Quill Editor Config (from `editor_config_mixin.js`)

Returns the standard Quill toolbar config object used across all editors.

```javascript
this.$editor_config  // returns toolbar configuration object
```

### `go_to_service_mixin.js`

Cross-subdomain navigation using environment variables.

```javascript
this.go_to_service('shop')    // navigates to shop.climbing.ge
this.go_to_service('guid')    // navigates to climbing.ge
this.go_to_service('summit')  // navigates to summit.climbing.ge
```

---

## Mixins

### `axios_mixin.js`

Helper methods for common API call patterns. Mixed into many page components.

```javascript
// Example usage inside a component
this.get_articles('outdoor', 'en', (data) => { this.articles = data }, onError, onFinally)
```

---

## State Management (Pinia)

**Location:** `resources/js/store/`

### `auth_user.js` module

Stores authenticated user data loaded from `GET /api/auth_user`.

**State:**
```javascript
{
  user: null,         // user object or null
  loading: false,
  permissions: [],    // array of permission strings
}
```

**Actions:**
```javascript
fetchUser()           // GET /api/auth_user, stores result
clearUser()           // clears on logout
```

**Usage:**
```javascript
import { useAuthUserStore } from '@/store/auth_user'
const store = useAuthUserStore()
store.fetchUser()
const user = store.user
```

---

## Routing

Each subdomain has its own router file in `resources/js/routes/`. All routers are created with `createWebHistory()` and include i18n locale prefix support.

### Locale Prefix

Routes support an optional `/:locale(ka)?` prefix for Georgian language:
```
/summits/list         → English
/ka/summits/list      → Georgian
```

The locale regex is built from `MIX_VUE_APP_I18N_SUPORTED_LOCALE` env var.

### Route Meta

Route meta fields:
```javascript
{ path: '/summit/:url_title', meta: { title: 'Summit Detail' } }
```

---

## i18n / Localization

**Location:** `resources/js/services/localization/i18n.js`

Uses **Vue i18n v9**.

**Language files:**
- `resources/lang/i18n/en.json` — English
- `resources/lang/i18n/ka.json` — Georgian

**Storage:** Language key stored in `localStorage`:
- `'us'` → English
- `'ka'` → Georgian

**Usage:**
```html
<h1>{{ $t('guide.title.outdoor climbing') }}</h1>
<p>{{ $t('summit.scan_qr.instruction') }}</p>
```

**Important:** Vue i18n v9 returns the key string itself (not `undefined`) when a key is missing. `$t('missing.key') || 'fallback'` does NOT work because the key string is truthy. Missing keys must be added to the JSON files.

### Key Namespaces

| Namespace | Subdomain |
|---|---|
| `guide.*` | climbing.ge |
| `shop.*` | shop.climbing.ge |
| `blog.*` | blog.climbing.ge |
| `summit.*` | summit.climbing.ge |
| `films.*` | films.climbing.ge |
| `user.*` | user.climbing.ge |
| `auth.*` | Shared auth pages |

---

## CASL Permissions

**Location:** `resources/js/services/ability/ability.js`

Loaded after login from `/api/auth_user` response.

```javascript
// Template
<button v-if="$can('add', 'summit')">Add Summit</button>

// Script
if (this.$ability.can('edit', 'article')) { ... }
```

**Plugin registration (`app.js`):**
```javascript
import { abilitiesPlugin } from '@casl/vue'
import ability from './services/ability/ability'
app.use(abilitiesPlugin, ability, { useGlobalProperties: true })
```

---

## Axios Setup

Global axios configuration in `app.js`:

```javascript
axios.defaults.baseURL = '/api/'                        // per-subdomain base
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-XSRF-TOKEN'] = localStorage.getItem('x_xsrf_token')
```

**Response interceptor:**
```javascript
axios.interceptors.response.use(
  res => res,
  err => {
    if (err.response?.status === 401 || err.response?.status === 419) {
      localStorage.clear()
      router.push({ name: 'login' })
    }
    return Promise.reject(err)
  }
)
```

---

## SEO / Meta Tags

Each subdomain has its own `MetaDataComponent.vue` in `components/{subdomain}/items/`.

**Location:** e.g. `resources/js/components/summit/items/MetaDataComponent.vue`

Uses `@unhead/vue` v3 for reactive head management.

```html
<metaData
  :title="summit.title + ' – Summit Climbing Georgia'"
  :description="summit.description"
  :image="'/public/images/meta_img/mountain.jpg'"
/>
```

**What it sets:**
- `<title>` tag
- `<html lang>` attribute (from localStorage language)
- `<link rel="canonical">` (current URL)
- `<meta name="description">`
- Open Graph: `og:title`, `og:description`, `og:image`, `og:url`, `og:type`
- Twitter Card: `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`

**`@unhead/vue` import note:**
```javascript
// app.js — createHead must come from /client subpath
import { createHead } from '@unhead/vue/client'

// MetaDataComponent.vue — composables from main package
import { useSeoMeta, useHead } from '@unhead/vue'
```

---

## Rich Text Editors

Three Quill-based editors registered globally:

| Global Tag | Component | Use Case |
|---|---|---|
| `<big_editor>` | `BigEditorComponent.vue` | Full featured: paragraphs, images, tables, embeds |
| `<small_editor>` | `SmallEditorComponent.vue` | Basic inline formatting |
| `<mini_editor>` | `MiniEditorComponent.vue` | Single-line rich text |

**Location:** `resources/js/components/user/items/form/parts/editor/`

**Usage:**
```html
<big_editor v-model="content" />
<small_editor v-model="short_desc" />
```

---

## Subdomain Components

### Guide (`components/guide/`)

```
guide/
├── pages/
│   ├── IndexPageComponent.vue       # Homepage
│   ├── AboutUsComponent.vue         # About
│   ├── SerchPageComponent.vue       # Search results
│   └── lists/
│       ├── OutdoorListComponent.vue # Outdoor spots
│       ├── MountaineeringListComponent.vue
│       ├── IceListComponent.vue
│       └── ...
├── items/
│   ├── cards/
│   │   ├── ArticleCardComponent.vue # Standard article card
│   │   └── ...
│   ├── navbars/NavbarComponent.vue
│   └── MetaDataComponent.vue
└── GuideMainComponent.vue
```

**Card structure (guide style):**
```html
<div class="food col-md-4">
  <div class="portfolio-img view view-first">
    <img ...>
    <div class="mask">
      <a class="info"><i class="fa fa-arrow-right"></i></a>
    </div>
    <div class="isotope-item-dtls">
      <h3>Title</h3>
      <div class="article_size">Description</div>
    </div>
  </div>
</div>
```

**Section header structure (guide style):**
```html
<h1 class="index_h2">Section Title</h1>
<div class="bar"><i class="fa fa-icon"></i></div>
<h3 class="article_list_short_description">Description</h3>
```

### Shop (`components/shop/`)

```
shop/
├── pages/
│   ├── MeinPage.vue             # Shop homepage
│   ├── ProductPage.vue          # Product detail
│   ├── CartPage.vue             # Shopping cart
│   ├── CheckoutPage.vue         # Checkout flow
│   └── SearchPage.vue           # Product search
├── items/
│   ├── cards/ProductCard.vue
│   └── ...
└── MainWrapper.vue
```

### Blog (`components/blog/`)

```
blog/
lokkelly├── pages/
│   ├── IndexPage.vue
│   ├── pages/PostPage.vue
│   └── pages/NewsPage.vue
├── items/
│   └── Empire Modals/PostModal.vue
└── BlogMainComponent.vue
```

### Summit (`components/summit/`)

```
summit/
├── pages/
│   ├── IndexPage.vue               # QR scanner home
│   ├── AboutUsPage.vue
│   ├── SummitMapPage.vue
│   ├── MakeSummitAscentPage.vue    # Submit ascent form
│   ├── lists/SummitListPage.vue    # Public summit grid
│   └── pages/SummitPage.vue        # Summit detail
├── items/
│   ├── NavbarComponent.vue
│   ├── FooterComponent.vue
│   ├── MetaDataComponent.vue
│   └── cards/BigPostCardComponent.vue
└── SummitMainComponent.vue
```

### User Admin (`components/user/`)

```
user/
├── pages/
│   ├── articles/              # Guide article management
│   ├── summits/               # Summit management
│   │   ├── SummitListPage.vue
│   │   └── SummitFormModal.vue
│   ├── shop/                  # Shop management
│   ├── blog/                  # Blog management
│   ├── films/                 # Films management
│   └── user_management/       # Users, roles, permissions
├── items/
│   ├── data_table/            # TabsComponent system
│   ├── modal/                 # StackModal + content modals
│   ├── navbars/               # User panel navbar
│   ├── form/parts/editor/     # Quill editors
│   └── BreadcrumbComponent.vue
└── HomeComponent.vue
```

---

## Building for Production

```bash
npm run build    # produces versioned files in public/assets/js/ and public/assets/css/
```

Files referenced in `resources/views/*.blade.php` via Laravel Mix manifest.

**Never edit files in `public/assets/`** — they are compiled build outputs and will be overwritten on next build.

---

[Go back](../../README.md)
