# API Documentation

This document provides comprehensive documentation for the Climbing Guide API endpoints. The API is built with Laravel and follows RESTful conventions.

## Table of Contents

- [Authentication](#authentication)
- [User Management](#user-management)
- [Articles & Guides](#articles--guides)
- [Shop & Products](#shop--products)
- [Forum](#forum)
- [Films](#films)
- [Events](#events)
- [Search](#search)
- [Notifications](#notifications)
- [File Upload](#file-upload)

## Authentication

### Social Login
```
GET /api/login/{provider}
GET /api/login/{provider}/callback
```
Redirects to social login provider and handles callback.

### Password Management
```
POST /api/login/social/create_password/{email}
POST /api/password/send_forget_mail
POST /api/password/reset_password
```

### Email Verification
```
GET /api/email/resend
GET /api/email/verify/{user_id}/{hash}
```

## User Management

### User Profile
```
GET /api/auth_user
GET /api/user/get_auth_user_data
GET /api/user/get_user_data/{user_id}
POST /api/user/update_password
POST /api/user/user_image_update/{user_id}
```

### User Addresses
```
POST /api/add_user_adreses
GET /api/get_user_adreses
GET /api/get_activ_adres/{adres_id}
POST /api/get_editing_adres/{adres_id}
POST /api/edit_adres/{adres_id}
DELETE /api/del_user_adreses/{adres_id}
```

### User Options & Notifications
```
GET /api/options/get_selected_user_data/{user_id}
GET /api/options/get_user_notification_data
POST /api/options/update_user_notification_data
POST /api/options/user_info_update/{user_id}
```

### Roles & Permissions
```
GET /api/parmisions_list
GET /api/role
POST /api/role
GET /api/role/{role}
PUT /api/role/{role}
DELETE /api/role/{role}
POST /api/role/create_role
POST /api/role/edit_role/{role_id}
DELETE /api/role/del_role/{role_id}
GET /api/role/get_editing_role/{role_id}
GET /api/permission/get_parmisions_for_role/{role_id}
```

### User Following
```
POST /api/follow/{service_id}
DELETE /api/follow/del_follower/{id}
GET /api/follow/following_users_list
```

## Articles & Guides

### Articles Management
```
POST /api/article/add_article/{category}
POST /api/article/edit_article/{article_id}
DELETE /api/article/del_article/{article_id}
GET /api/articles/get_editing_data/{id}
POST /api/articles/upload_image
POST /api/articles/upload_spot_rock_images
```

### Articles Retrieval
```
GET /api/article/get_article_for_bisnes_page/{lang}/{bisnes_url_title}
GET /api/article/get_articles_for_bisnes_suport
GET /api/article/get_category_articles/{category}
GET /api/article/{category}/{lang}/{url_title}
GET /api/articles/{category}/{lang}
GET /api/last_news/{lang}
GET /api/get_articles_for_forum/{category}/{lang}
POST /api/get_article_global_data/{leng}/{article_id}
POST /api/similar_article/{lang}
```

### Comments
```
POST /api/guide_comment/create_comment/{article_id}
DELETE /api/guide_comment/del_comment/{comment_id}
GET /api/guide_comment/get_article_comments/{article_id}
GET /api/guide_comment/get_user_comments
GET /api/guide_comment/get_all_comments
POST /api/guide_comment/hide_comment/{comment_id}
POST /api/guide_comment/make_decision
POST /api/guide_comment/add_comment_complaint
```

### Routes & Sectors
```
POST /api/route/add_route
POST /api/route/edit_route/{route_id}
DELETE /api/route/del_route/{route_id}
GET /api/route/get_all_routes
GET /api/route/get_route_editing_data/{route_id}
GET /api/route/get_route_for_modal/{route_id}
GET /api/route/routes_authers
GET /api/get_routes_for_forum/{sector_id}
GET /api/get_routes_quantity/{article_id}
```

### Sectors
```
POST /api/sector/add_sector
POST /api/sector/edit_sector/{sector_id}
DELETE /api/sector/del_sector_sector/{sector_id}
GET /api/sector/get_sector_and_routes/{article_id}
GET /api/sector/get_sector_data_for_model/{sector_id}
GET /api/sector/get_sector_editing_data/{sector_id}
GET /api/sector/get_sector_images/{sector_id}
POST /api/sector/routes_sequence
POST /api/sector/save_sector_sequence
GET /api/sectors_and_routes_quantity
```

### Outdoor Spots & Regions
```
POST /api/outdoor/add_spot
POST /api/outdoor/edit_spot/{id}
DELETE /api/outdoor/del_spot/{id}
GET /api/outdoor/get_editing_spot_data/{id}
GET /api/outdoor/get_filtred_outdoor_spots/{lang}/{filter_id}/{published}
GET /api/outdoor/get_filtred_outdoor_spots_for_admin/{filter_id}
GET /api/outdoor/get_spots_by_regions/{lang}
GET /api/outdoor/region
GET /api/outdoor/region/{lang}/{region_id}
GET /api/outdoor/regions/{lang}
```

### Ice Climbing
```
POST /api/ice_sectors/add_sector
POST /api/ice_sectors/edit_sector/{sector_id}
DELETE /api/ice_sectors/del_sector/{sector_id}
GET /api/ice_sectors/get_all_sectors
GET /api/ice_sectors/get_article_sectors/{article_id}
POST /api/ice_routes/add_route
DELETE /api/ice_routes/del_route/{route_id}
GET /api/ice_routes/get_all_routes
GET /api/ice_routes/get_sector_routes/{sector_id}
```

### Mountains & MTP
```
POST /api/mount/create_mount_massive
POST /api/mount/edit_mount_massive/{mount_id}
GET /api/mount/get_editing_mount_data/{mount_id}
GET /api/mount/on_page/{lang}/{mount_route_id}
GET /api/mount/{lang}/{mount_id}
GET /api/mounts/{lang}
POST /api/mtp/mtp_add
POST /api/mtp/mtp_edit/{mtp_id}
DELETE /api/mtp/del_mtp/{mtp_id}
GET /api/mtp/get_editing_mtp/{mtp_id}
GET /api/mtp/get_mtps_for_forum/{sector_id}
```

### Competitions & Events
```
POST /api/competition/add_competition
POST /api/competition/edit_competition/{competition_id}
DELETE /api/competition/del_competition/{competition_id}
GET /api/competition/get_all_competitions
GET /api/event/add_event
POST /api/event/edit_event/{event_id}
DELETE /api/event/del_event/{event_id}
GET /api/event/get_all_events
GET /api/event/get_event_on_index_page/{lang}
```

### Local Business
```
POST /api/bisnes/add_local_bisnes
POST /api/bisnes/edit_local_bisnes/{bisnes_id}
DELETE /api/bisnes/del_local_bisnes/{bisnes_id}
GET /api/bisnes/get_local_bisnes_for_article/{article_url_title}/{locale}
GET /api/bisnes/get_local_bisneses
```

## Shop & Products

### Products
```
GET /api/product
POST /api/product
GET /api/product/{product}
PUT /api/product/{product}
DELETE /api/product/{product}
POST /api/product/add_product
POST /api/product/edit_product/{product_id}
DELETE /api/product/del_product/{product_id}
GET /api/product/get_all_products
GET /api/product/get_product_editing_data/{product_id}
GET /api/product/get_user_products
GET /api/products/{lang}
GET /api/page_product/{lang}/{url_title}
GET /api/get_quick_product/{lang}/{product_id}
```

### Product Categories & Brands
```
GET /api/product_category
POST /api/product_category
GET /api/product_category/{product_category}
PUT /api/product_category/{product_category}
DELETE /api/product_category/{product_category}
POST /api/brand/create_brand
POST /api/brand/edit_brand/{id}
DELETE /api/brand/del_brand/{id}
GET /api/brand/get_all_brands
```

### Product Options & Feedback
```
POST /api/product_option/add_option
POST /api/product_option/edit_option/{option_id}
DELETE /api/product_option/del_option/{option_id}
GET /api/product_option/get_activ_product_options/{product_id}
POST /api/product_feedback/create_feedback/{product_id}
DELETE /api/product_feedback/del_feedback/{feedback_id}
GET /api/product_feedback/get_product_feedbacks/{product_id}
```

### Cart & Orders
```
GET /api/cart
POST /api/cart
GET /api/cart/{cart}
PUT /api/cart/{cart}
DELETE /api/cart/{cart}
POST /api/cart/update_quantity/{item_id}
POST /api/add_to_favorite/{product_id}
DELETE /api/del_from_favorite/{product_id}
GET /api/get_user_favorite_products
POST /api/order/create_order
GET /api/order/get_user_orders
GET /api/order/get_order_detals/{order_id}
POST /api/order/edit_order_status/{order_id}
```

### Services
```
GET /api/service
POST /api/service
GET /api/service/{service}
PUT /api/service/{service}
DELETE /api/service/{service}
POST /api/service/add_service
POST /api/service/edit_service/{service_id}
DELETE /api/service/del_service/{service_id}
GET /api/services/{lang}
GET /api/service/{lang}/{url_title}
```

### Tours
```
POST /api/tour/add_tour
POST /api/tour/edit_tour/{tour_id}
DELETE /api/tour/del_tour/{tour_id}
GET /api/tour/get_all_tours
GET /api/tour/get_tours/{lang}
GET /api/tour/get_tour/{lang}/{url_title}
POST /api/tour/reservation/create_reservation/{tour_id}
GET /api/tour/reservation/get_user_reservations
```

## Forum

### Posts & Topics
```
GET /api/post
POST /api/post
GET /api/post/{post}
PUT /api/post/{post}
DELETE /api/post/{post}
POST /api/posts/add_post
GET /api/posts/get_likes/{post_id}
GET /api/posts_topic
POST /api/posts_topic
GET /api/posts_topic/{posts_topic}
PUT /api/posts_topic/{posts_topic}
DELETE /api/posts_topic/{posts_topic}
GET /api/posts_topic/list/{lang}
```

### Post Comments
```
POST /api/post_comment/add_post_comment/{post_id}
POST /api/post_comment/edit_post_comment/{comment_id}
DELETE /api/post_comment/edit_post_comment/{comment_id}
GET /api/post_comment/get_post_comment/{post_id}
```

## Films

### Film Management
```
GET /api/films
POST /api/films
GET /api/films/{film}
PUT /api/films/{film}
DELETE /api/films/{film}
POST /api/film/add_to_faworite
DELETE /api/film/del_from_faworite/{film_id}
GET /api/film/get_film/{locale}/{url_title}
GET /api/film/get_films/{locale}
GET /api/film/top_films/{top_film_type}/{locale}
GET /api/film/films_search/{locale}
```

### Film Tags
```
GET /api/film_tags
POST /api/film_tags
GET /api/film_tags/{film_tag}
PUT /api/film_tags/{film_tag}
DELETE /api/film_tags/{film_tag}
```

## Events

### Event Management
```
POST /api/event/add_event
POST /api/event/edit_event/{event_id}
DELETE /api/event/del_event/{event_id}
GET /api/event/get_all_events
GET /api/event/get_event_on_site_page/{lang}/{url_title}
POST /api/event/add_to_interested_events
DELETE /api/event/del_interested_event/{article_id}
GET /api/event/get_interested_events
```

## Search

### Global Search
```
POST /api/articleSearch/{query_request}
POST /api/filmSearch/{query_request}
POST /api/productSearch/{query_request}
```

## Notifications

### User Notifications
```
POST /api/user/notifications/send_user_favorites_notification/{action}
POST /api/FollowingNotification
```

## File Upload

### Image Upload
```
POST /api/articles/upload_image
POST /api/ckeditor/upload
POST /api/articles/upload_spot_rock_images
```

## Route Reviews & Ratings

### Route Reviews
```
POST /api/route_review/create_route_review/{route_id}
POST /api/route_review/edit_route_review/{review_id}
DELETE /api/route_review/del_route_review/{review_id}
GET /api/route_review/get_all_route_reviews/{route_id}
GET /api/route_review/get_user_review
```

## Tasks & Projects

### Task Management
```
POST /api/task/create_task
POST /api/task/update_task/{task_id}
POST /api/task/update_task_status/{task_id}
DELETE /api/task/del_task/{task_id}
GET /api/task/get_all_tasks
GET /api/task/get_user_tasks
```

### Task Categories
```
POST /api/task/task_category/create_task_category
POST /api/task/task_category/update_task_category/{task_category_id}
DELETE /api/task/task_category/del_task_category/{task_category_id}
GET /api/task/task_category/get_all_task_categories
```

## Admin Management

### User Management (Admin)
```
POST /api/user/create_user_by_admin
DELETE /api/user/del_user/{user_id}
GET /api/user/get_all_users
GET /api/user/get_worker_users
```

### Site Data Management
```
GET /api/siteData
POST /api/siteData
GET /api/siteData/{siteData}
PUT /api/siteData/{siteData}
DELETE /api/siteData/{siteData}
POST /api/siteData/edit_site_data
GET /api/siteData/get_site_locale_data/{locale}
GET /api/site_data_counts
```

### Warehouse Management
```
POST /api/warehouse/add_warehouses
POST /api/warehouse/edit_warehouse/{warehouse_id}
DELETE /api/warehouse/del_warehouse/{warehouse_id}
GET /api/warehouse/get_warehouses
```

## Error Handling

All API endpoints return appropriate HTTP status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Authentication

Most API endpoints require authentication. Include the authorization header:
```
Authorization: Bearer {token}
```

## Rate Limiting

API endpoints are subject to rate limiting. Check response headers for limit information:
- `X-RateLimit-Limit`
- `X-RateLimit-Remaining`
- `X-RateLimit-Reset`

## Data Formats

### Request Format
All POST/PUT requests should send data in JSON format:
```json
{
  "field_name": "value",
  "another_field": 123
}
```

### Response Format
Successful responses return data in JSON format:
```json
{
  "success": true,
  "data": { ... },
  "message": "Operation successful"
}
```

Error responses follow this format:
```json
{
  "success": false,
  "message": "Error description",
  "errors": { ... }
}
```

## Pagination

List endpoints support pagination. Use query parameters:
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15)

Response includes pagination metadata:
```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}
```

## Filtering & Sorting

Many list endpoints support filtering and sorting:
- `sort_by` - Field to sort by
- `sort_order` - Sort direction (asc/desc)
- `filter[field]` - Filter by specific field
- `search` - Global search term
