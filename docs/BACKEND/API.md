# Backend API Reference — climbing.ge

Full reference for all API endpoints. All responses are JSON. All requests requiring a body use `Content-Type: application/json`.

---

## Table of Contents

- [Authentication](#authentication)
- [General / Site Data](#general--site-data)
- [Guide — Public](#guide--public)
- [Shop — Public](#shop--public)
- [Blog — Public](#blog--public)
- [Films — Public](#films--public)
- [Summit — Public](#summit--public)
- [User — Authenticated](#user--authenticated)
- [Admin — Guide](#admin--guide)
- [Admin — Shop](#admin--shop)
- [Admin — Summit](#admin--summit)
- [Admin — Blog](#admin--blog)
- [Admin — Films](#admin--films)
- [Admin — Users](#admin--users)
- [Error Responses](#error-responses)

---

## Authentication

**File:** `routes/api/auth.php`

| Method | Path | Auth | Description |
|---|---|---|---|
| POST | `/api/register` | No | Register new user |
| POST | `/api/login` | No | Login with RSA-encrypted password |
| GET | `/api/login/{provider}` | No | OAuth redirect (facebook/google) |
| GET | `/api/login/{provider}/callback` | No | OAuth callback |
| POST | `/api/login/social/create_password/{email}` | No | Set password after social login |
| POST | `/api/password/send_forget_mail` | No | Send password reset email |
| POST | `/api/password/reset_password` | No | Reset password with token |
| GET | `/api/email/verify/{user_id}/{hash}` | No | Verify email address |
| GET | `/api/email/resend` | Yes | Resend verification email |
| GET | `/api/auth_user` | Yes | Get authenticated user + abilities |
| POST | `/api/logout` | Yes | Revoke Sanctum token |
| GET | `/api/token` | Yes | Create new Sanctum token |

### `POST /api/register`

```json
// Request
{
  "name": "John",
  "surname": "Doe",
  "email": "john@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}

// Response 201
{
  "token": "1|abc...",
  "user": { "id": 1, "name": "John", "email": "john@example.com" }
}
```

### `POST /api/login`

Password must be **RSA-encrypted** by the client using the server's public key.

```json
// Request
{
  "email": "john@example.com",
  "password": "<base64-rsa-encrypted>"
}

// Response 200
{
  "token": "2|xyz...",
  "user": { "id": 1, "name": "John", "roles": ["user"], "abilities": [...] }
}

// Response 401 — bad credentials
{ "message": "Unauthorized" }

// Response 403 — user is banned
{ "message": "Forbidden" }
```

### `GET /api/auth_user`

```json
// Response 200
{
  "id": 5,
  "name": "John",
  "surname": "Doe",
  "email": "john@example.com",
  "avatar": "avatars/john.jpg",
  "roles": ["admin"],
  "casl_permissions": [
    { "action": "add",  "subject": "article" },
    { "action": "edit", "subject": "summit" }
  ]
}

// Response 401 — not authenticated
```

---

## General / Site Data

**File:** `routes/api/general.php`

| Method | Path | Auth | Description |
|---|---|---|---|
| GET | `/api/get_site_data/get_site_global_data` | No | Global site settings |
| GET | `/api/get_site_data/get_site_locale_data/{lang}` | No | Locale-specific site data |
| GET | `/api/get_site_data/get_site_locale_data_for_site/{lang}` | No | Site data for frontend use |
| GET | `/api/get_site_social_links/get_site_social_links` | No | Social media links |
| POST | `/api/search/suggest` | No | Live search suggest (locale-aware, ≥ 2 chars) |
| POST | `/api/guide/search` | No | Full guide search with locale + fuzzy support |
| POST | `/api/shop/search` | No | Full shop search |
| POST | `/api/blog/search` | No | Full blog search |
| POST | `/api/summit/search` | No | Summit search |
| POST | `/api/productSearch/{query}` | No | *(legacy)* Search products |
| POST | `/api/articleSearch/{query}` | No | *(legacy)* Search articles |
| POST | `/api/filmSearch/{query}` | No | *(legacy)* Search films |
| POST | `/api/save-canvas-image` | No | Save canvas PNG to `storage/images/` |
| POST | `/api/ckeditor/upload` | Yes | Upload image from CKEditor |
| GET | `/api/get_follow/following_users_list` | Yes | Followed users/services |
| POST | `/api/set_follow/{service_id}` | Yes | Follow a service |
| DELETE | `/api/set_follow/del_follower/{id}` | Yes | Unfollow |

---

## Guide — Public

**File:** `routes/api/get_guide_routes.php`

### Articles

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_article/get_category_articles/{category}` | Articles by category |
| GET | `/api/get_article/get_locale_articles/{category}/{lang}` | Locale articles |
| GET | `/api/get_article/get_locale_article_on_page/{category}/{lang}/{url_title}` | Single article |
| GET | `/api/get_article/last_news/{lang}` | Latest news |
| POST | `/api/get_article/get_similar_locale_article/{lang}` | Similar articles |
| GET | `/api/get_article/get_gallery_image/get_index_gallery` | Gallery images |

### Outdoor Climbing

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_outdoor/get_filtred_outdoor_spots/{lang}/{filter_id}/{published}` | Spots with filter |
| GET | `/api/get_outdoor/get_spots_by_regions/{lang}` | Spots grouped by region |
| GET | `/api/get_region/get_all_outdoor_regions` | All outdoor regions |
| GET | `/api/get_region/get_local_regions/{lang}` | Regions in locale |

### Sectors & Routes

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_sector/get_sector_and_routes/{article_id}` | Sectors with routes for an article |
| GET | `/api/get_sector/get_sector_images/{sector_id}` | Sector topo images |
| GET | `/api/get_sector/sectors_and_routes_quantity` | Count summary |
| GET | `/api/get_route/get_all_routes` | All routes |
| GET | `/api/get_route/get_route_for_modal/{route_id}` | Route detail for modal |
| GET | `/api/get_route/get_routes_quantity/{article_id}` | Route count for article |
| GET | `/api/get_route/get_most_popular_routes/{route_type}` | Popular routes |

### Mountains & Mountaineering

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_mount/get_all_mount` | All mountain massifs |
| GET | `/api/get_mount/get_locale_mounts/{lang}` | Mountains in locale |
| GET | `/api/get_mount_route/get_filtred_mount_routes/{lang}/{filter_id}/{published}` | Mount routes |
| GET | `/api/get_mount_route/get_mount_routes_by_maunt/{lang}` | Routes grouped by mountain |

### Events & Competitions

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_event/get_event_on_site_list/{lang}` | All events |
| GET | `/api/get_event/get_event_on_site_page/{lang}/{url_title}` | Single event |
| GET | `/api/get_event/get_all_competitions` | All competitions |
| POST | `/api/get_event/add_competition` | Add competition entry |

### Multi-Pitch

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_mtp/get_all_mtp` | All multi-pitch routes |
| GET | `/api/get_mtp/get_mtp_pitch/get_mtp_pitchs/{mtp_id}` | Pitches for a route |

### Comments

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_article/get_guide_comment/get_article_comments/{article_id}` | Comments for article |
| POST | `/api/get_article/set_guide_comment_by_gest/create_comment/{article_id}` | Submit comment |

### Donations

| Method | Path | Description |
|---|---|---|
| POST | `/api/set_donation/create` | Create donation payment |
| POST | `/api/set_donation/callback` | Payment gateway callback |
| GET | `/api/set_donation/status/{id}` | Donation status |

Uses the Flitt payment gateway. TBC Bank display details come from env:
```env
FLITT_MERCHANT_ID=...
FLITT_SECRET_KEY=...
FLITT_API_VERSION=1.0

DONATION_TBC_IBAN=
DONATION_TBC_ACCOUNT_NAME=
DONATION_TBC_BANK_CODE=TBCBGE22
DONATION_TBC_BANK_NAME="TBC Bank"
```

---

## Shop — Public

**File:** `routes/api/get_shop_routes.php`

### Products

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_product/get_products_for_index/{lang}` | Products for homepage |
| GET | `/api/get_product/get_local_products/{lang}` | Products in locale |
| GET | `/api/get_product/get_local_product_in_page/{lang}/{url_title}` | Single product |
| GET | `/api/get_product/get_similar_product/{lang}/{product_id}` | Similar products |
| GET | `/api/get_product/get_product_options/{product_id}` | Product size/color options |
| GET | `/api/get_product/get_product_price_interval` | Min/max price range |
| GET | `/api/get_product/get_brand/get_all_brands` | All brands |
| GET | `/api/get_product/get_product_category/get_all_product_category` | All categories |

### Tours

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_tour/get_tours/{lang}` | All tours |
| GET | `/api/get_tour/get_tour/{lang}/{url_title}` | Single tour |
| GET | `/api/get_tour/get_similar_tours/{lang}/{tour_id}` | Similar tours |
| POST | `/api/set_user_reservation/create_reservation/{tour_id}` | Book a tour |

### Services

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_service/get_local_services/{lang}` | All services |
| GET | `/api/get_service/get_local_service_in_page/{lang}/{url_title}` | Single service |

### Cart

| Method | Path | Description |
|---|---|---|
| GET | `/api/cart` | Get cart contents |
| POST | `/api/cart` | Add item to cart |
| PUT | `/api/cart/{id}` | Update cart item |
| DELETE | `/api/cart/{id}` | Remove from cart |

### Product Feedback

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_product/get_product_feedback/get_product_feedbacks/{product_id}` | Product reviews |
| POST | `/api/set_product_feedback_by_gest/create_feedback/{product_id}` | Submit review |

---

## Blog — Public

**File:** `routes/api/get_blog_routes.php`

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_post/get_all_posts_and_news_for_blog/{locale}` | All posts + news |
| GET | `/api/get_post/get_post/{url_title}` | Single post |
| GET | `/api/get_post/get_news/{url_title}` | Single news article |

---

## Films — Public

**File:** `routes/api/get_films_routes.php`

| Method | Path | Description |
|---|---|---|
| GET | `/api/film/get_films/{locale}` | All films |
| GET | `/api/film/get_film/{locale}/{url_title}` | Single film |
| GET | `/api/film/get_films_categories/{locale}` | Film categories |
| GET | `/api/film/top_films/{type}/{locale}` | Top/featured films |
| GET | `/api/film/films_search/{locale}` | Search films |

---

## Summit — Public

**File:** `routes/api/summit_public_routes.php`

All under prefix `/api/summit`. Controller: `SummitPublicController`.

| Method | Path | Description |
|---|---|---|
| GET | `/api/summit/list` | All published summits |
| GET | `/api/summit/show/{url_title}` | Single summit with region |
| GET | `/api/summit/routes/{id}` | Routes available for summit |
| GET | `/api/summit/ascents/{url_title}` | Public ascent list |
| POST | `/api/summit/ascent/{summit_id}` | Submit ascent |

See [SUMMIT.md](../SUMMIT.md) for full request/response shapes.

---

## User — Authenticated

**File:** `routes/api/get_user_routes.php`  
All require `auth:sanctum` + `banned` middleware.

### Profile

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_user/get_auth_user_data` | Full user profile |
| GET | `/api/get_user/get_auth_user_permissions` | User permission list |
| POST | `/api/get_options/user_info_update/{user_id}` | Update profile |
| POST | `/api/get_options/update_user_notification_data` | Update notifications |

### Addresses

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_user_adreses` | All saved addresses |
| GET | `/api/get_activ_adres/{id}` | Single address |
| POST | `/api/add_user_adreses` | Add new address |
| POST | `/api/edit_adres/{id}` | Edit address |
| DELETE | `/api/del_user_adreses/{id}` | Delete address |

### Favorites

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_faworite/get_faworite_outdoor_region` | Favorite climbing areas |
| GET | `/api/get_faworite/get_interested_events` | Interested events |
| POST | `/api/set_faworite_by_user/add_to_favorite_outdoor_area/{article_id}` | Add favorite area |
| POST | `/api/set_faworite_by_user/add_to_interested_events` | Add event interest |
| DELETE | `/api/set_faworite/del_favorite_outdoor_area/{id}` | Remove favorite |
| DELETE | `/api/set_faworite/del_interested_event/{id}` | Remove event interest |

### Mail

| Method | Path | Description |
|---|---|---|
| GET | `/api/mail/unread_count` | Unread message count for nav badge |

### Ascents (User)

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_user_ascents/get_all_my_ascents` | All ascents submitted by the current user |
| DELETE | `/api/get_user_ascents/del_ascent/{id}` | Delete own ascent |

---

## Admin — Summit

**File:** `routes/api/admin/set_summit_routes.php`  
Requires `auth:sanctum` + `banned`.

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_summit_admin/index` | All summits (admin view) |
| GET | `/api/get_summit_admin/get_regions` | Regions for dropdown |
| GET | `/api/get_summit_admin/get_mount_routes` | Mount routes for dropdown |
| GET | `/api/get_summit_admin/get_ascents/{id}` | Ascents for summit |
| GET | `/api/get_summit_admin/get_all_ascents` | All ascents + full data |
| POST | `/api/set_summit/store` | Create summit |
| POST | `/api/set_summit/update/{id}` | Update summit |
| DELETE | `/api/set_summit/destroy/{id}` | Delete summit |
| POST | `/api/set_summit/save_qr/{id}` | Save QR code URL |
| POST | `/api/set_summit/update_coordinates/{id}` | Update GPS coordinates |
| POST | `/api/set_summit/add_mount_route_relation` | Link summit to a mount route |
| DELETE | `/api/set_summit/remove_mount_route_relation/{id}` | Remove mount route link |

---

## Admin — Guide

**File:** `routes/api/admin/set_guide_routes.php`  
Requires `auth:sanctum` + `banned`.

All routes use the pattern `/api/set_{resource}/` for write operations and `/api/get_{resource}/` for reads.

Key resources: `article`, `sector`, `route`, `region`, `mount`, `mount_route`, `event`, `competition`, `general_info`, `head_slider`, `local_bisnes`, `team`, `gallery`, `live_camera`, `mtp`, `comment`, `route_json`, `sector_images`

---

## Admin — Shop

**File:** `routes/api/admin/set_shop_routes.php`  
Requires `auth:sanctum` + `banned`.

Key resources: `product`, `product_brand`, `product_category`, `product_subcategory`, `product_option`, `product_feedback`, `order`, `custom_order`, `tour`, `tour_category`, `tour_reservation`, `service`, `warehouse`, `sale_code`, `shiped_region`, `cart`

---

## Admin — Blog

**File:** `routes/api/admin/set_blog_routes.php`  
Requires `auth:sanctum` + `banned`.

| Method | Path | Description |
|---|---|---|
| GET | `/api/set_post/get_editing_post/{id}` | Fetch post for editing |
| POST | `/api/set_post/add_post` | Create post |
| POST | `/api/set_post/edit_post/{id}` | Update post |
| DELETE | `/api/set_post/del_post/{id}` | Delete post |

---

## Admin — Films

**File:** `routes/api/admin/set_films_routes.php`  
Requires `auth:sanctum` + `banned`.

| Method | Path | Description |
|---|---|---|
| GET/POST/PUT/DELETE | `/api/films` | Film CRUD resource |
| GET/POST/PUT/DELETE | `/api/film_tags` | Tag CRUD resource |

---

## Admin — Users

**File:** `routes/api/admin/set_user_routes.php`  
Requires `auth:sanctum` + `banned`.

### User Management

| Method | Path | Description |
|---|---|---|
| GET | `/api/set_user/get_all_users` | All users |
| GET | `/api/set_user/get_worker_users` | Staff users |
| POST | `/api/set_user/reset_password/{user_id}` | Reset user password |
| GET | `/api/set_user/get_ban_status/{user_id}` | Check if user is banned |
| POST | `/api/set_user/create_ban/{user_id}` | Ban user (attach `ban` role + revoke tokens) |
| DELETE | `/api/set_user/remove_ban/{user_id}` | Unban user (detach `ban` role) |

### Roles & Permissions

| Method | Path | Description |
|---|---|---|
| GET | `/api/parmisions_list` | All available permissions |
| GET | `/api/set_role/get_editing_role/{role_id}` | Role for editing |
| GET | `/api/set_role/get_user_permissions/{user_id}` | User's permissions |
| POST | `/api/set_role/create_role` | Create role |
| POST | `/api/set_role/edit_role/{role_id}` | Edit role |
| POST | `/api/set_role/edit_permissions_and_role/{user_id}` | Assign roles + permissions to user |
| DELETE | `/api/set_role/del_role/{role_id}` | Delete role |
| POST | `/api/set_role/sync_admin_permissions` | Sync all permissions to admin role |
| POST | `/api/set_permission/store` | Create permission |
| DELETE | `/api/set_permission/destroy/{id}` | Delete permission |

### Tasks

| Method | Path | Description |
|---|---|---|
| GET | `/api/get_task/get_all_tasks` | All tasks |
| GET | `/api/get_task/get_user_tasks` | Current user's tasks |
| POST | `/api/set_task/create_task` | Create task |
| POST | `/api/set_task/update_task/{task_id}` | Update task |
| POST | `/api/set_task/update_task_status/{task_id}` | Update task status |
| DELETE | `/api/set_task/del_task/{task_id}` | Delete task |
| POST | `/api/set_task/set_task_category/create_task_category` | Create task category |
| POST | `/api/set_task/set_task_category/update_task_category/{id}` | Update task category |
| DELETE | `/api/set_task/set_task_category/del_task_category/{id}` | Delete task category |

### Multimedia Manager

| Method | Path | Description |
|---|---|---|
| GET | `/api/set_multimedia/get_images_folder_structure` | Folder tree with used/unused flag |
| POST | `/api/set_multimedia/upload_images` | Upload images |
| DELETE | `/api/set_multimedia/delete_items` | Delete files or folders |
| POST | `/api/set_multimedia/create_folder` | Create folder |
| GET | `/api/set_multimedia/get_file_metadata` | File metadata |
| GET | `/api/set_multimedia/search` | Search images |

### Database

| Method | Path | Description |
|---|---|---|
| GET | `/api/set_database/table_stats` | All tables with row counts and integrity issues |
| POST | `/api/set_database/fix_issue` | Apply an integrity fix |

### Export

| Method | Path | Description |
|---|---|---|
| GET | `/api/set_export/categories` | Unique article categories |
| GET | `/api/set_export/articles/{category}` | Articles for a category |
| POST | `/api/set_export/articles_pdf` | Export articles to PDF |

### User Notifications

| Method | Path | Description |
|---|---|---|
| POST | `/api/set_user/set_notifications/send_user_favorites_notification/{action}` | Send notification to users with favorites |

---

## Error Responses

| Code | Meaning |
|---|---|
| 400 | Bad Request — validation failed |
| 401 | Unauthenticated — no valid Sanctum session/token |
| 403 | Forbidden — banned user or insufficient permissions |
| 404 | Not Found — resource doesn't exist |
| 419 | CSRF token mismatch — re-authenticate |
| 422 | Unprocessable Entity — validation errors with detail |
| 500 | Server Error |

### Validation Error Response (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "height": ["The height must be a number."]
  }
}
```

---

[Go back](../../README.md)
