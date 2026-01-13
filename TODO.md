# TODO: Fix Product User Relation Issue

## Backend Changes (ProductController.php)
- [x] 1. Add `get_product_user_relation($product_id)` method to get user relations by product ID
- [x] 2. Fix `change_user_relation()` method - change `!= []` to `!= null`
- [x] 3. Add new route `get_product_user_relation/{product_id}` to set_shop_routes.php
- [x] 4. Add `fix_product_user_relation()` method to fix duplicate relations
- [x] 5. Add route `fix_product_user_relation/{product_id}` to set_shop_routes.php

## Frontend Changes (ChangeUserModalComponent.vue)
- [x] 6. Update `show_modal()` to accept product_id properly
- [x] 7. Add `get_product_user_relation()` method to call new API
- [x] 8. Handle three cases:
  - No user relation: Show "This product don't have user relation" message
  - One user relation: Show active user info (current behavior)
  - Two+ user relations: Show error alert with "Fix it" button
- [x] 9. Add "Fix it" button for handling duplicate relations


