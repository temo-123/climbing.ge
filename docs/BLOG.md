# Climbing Blog — blog.climbing.ge

Blog and news platform for the climbing community: trip reports, gear reviews, technique articles, and news.

---

## Overview

**Subdomain:** `blog.climbing.ge`  
**Root Component:** `resources/js/components/blog/BlogMainComponent.vue`  
**Router:** `resources/js/routes/BlogRoutes.js`  
**API base URL:** `/api/` (blog routes)

### Pages

| Path | Component | Description |
|---|---|---|
| `/` | `IndexPage.vue` | Post list with featured post |
| `/post/:url_title` | `pages/PostPage.vue` | Individual post |
| `/news/:url_title` | `pages/NewsPage.vue` | Individual news article |
| `/about` | `AboutUsPage.vue` | About the blog |

---

## Frontend Components

### `IndexPage.vue` — Post List

**`resources/js/components/blog/pages/IndexPage.vue`**

Displays all posts and news. Loads via `GET /api/get_post/get_all_posts_and_news_for_blog/{locale}`.

**Features:**
- Featured/latest post displayed prominently
- Post cards grid
- `PostModal` for quick preview without leaving the page

### `PostPage.vue` / `NewsPage.vue`

Full post/news page. Loads single post via:
- `GET /api/get_post/get_post/{url_title}`
- `GET /api/get_post/get_news/{url_title}`

Content rendered from Quill editor HTML output.

### `PostModal.vue` — Quick Preview Modal

**`resources/js/components/blog/items/Modals/PostModal.vue`**

Receives the full `post` object as a prop (passed from `IndexPage`). Displays title and content without a separate API call.

```html
<PostModal :show="modal_visible" :post="selected_post" @close="modal_visible = false" />
```

---

## Backend API

### Public Routes — `routes/api/get_blog_routes.php`

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_post/get_all_posts_and_news_for_blog/{locale}` | All posts + news for locale |
| GET | `/api/get_post/get_post/{url_title}` | Single post |
| GET | `/api/get_post/get_news/{url_title}` | Single news article |
| GET | `/api/get_post/get_all_posts` | All posts (admin) |

### Admin Routes — `routes/api/admin/set_blog_routes.php`

Requires `auth:sanctum` + `banned` middleware.

| Method | Path | Description |
|---|---|---|
| GET | `/api/set_post/get_editing_post/{id}` | Fetch post for editing |
| POST | `/api/set_post/add_post` | Create new post |
| POST | `/api/set_post/edit_post/{id}` | Update post |
| DELETE | `/api/set_post/del_post/{id}` | Delete post |

**Controller:** `App\Http\Controllers\Api\User\Admin\Blog\PostController`

---

## Database

**Posts table** (`posts`):

| Column | Type | Notes |
|---|---|---|
| `id` | int | PK |
| `url_title` | string | URL slug |
| `title` | string | Post title |
| `content` | longtext | Quill HTML content |
| `image` | string\|null | Cover image path |
| `locale` | string | `en` or `ka` |
| `type` | string | `post` or `news` |
| `published` | boolean | Visibility |
| `created_at` | timestamp | |

---

## Admin Panel (user.climbing.ge)

Posts are managed in the user panel under the Blog section. The admin uses the standard `tabsComponent` table listing with add/edit/delete actions. The edit form uses the Quill rich-text editor (`big_editor` global component).

---

[Go back](../README.md)
