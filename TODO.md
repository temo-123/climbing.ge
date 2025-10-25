# Task Customization Plan

## 1. Update Task Model
- [x] Modify `app/Models/User/Task.php` to use `from_user` and `for_user` relationships
- [x] Add relationships for orders and product_options via pivot tables

## 2. Modify TaskController
- [x] Update `create_task` method for new fields: `from_user_id`, `for_user_id`, `global_category`, `category`
- [x] Update `get_user_tasks` to fetch tasks where user is sender or receiver
- [x] Update `update_task` and `update_task_status` for new fields
- [x] Rename 'worker_comment' to 'team_member_comment' for guide context
- [x] Add logic for global_category and category based on context (guide vs shop)

## 3. Update UsersController
- [x] Rename `get_worker_users` to `get_team_members` for guide, keep `get_worker_users` for shop
- [x] Add `get_team_members` method returning users where `is_team_member` is not null

## 4. Add New API Routes
- [x] Update `routes/api.php` for team members and workers selection
- [x] Ensure routes for task creation with new parameters

## 5. Update Notifications (if needed)
- [x] Ensure notifications use correct user fields
