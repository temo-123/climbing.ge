# Admin users list & Manage-user modal

User panel → **Users** (`/users_list`, `user › show`). Page: `resources/js/components/user/pages/usersListPageComponent.vue`.

## Design concept

Everything an admin does to one user happens in **one tabbed modal**, not one modal per action. The table stays a read-only overview with only two actions per row: **Manage** (gear) and **Delete**. Clicking the user's name opens the same modal.

## Table

`GET /set_user/get_all_users` (admin endpoint). Columns: id, name, email, team, verified, banned, role, points, last seen, partner organization, Manage, Delete (`user › del`). The activity filter (online / recent / inactive) uses `activity_status` from the same endpoint. **Points** is the same total as on the public climber profile (`User::withPointsCounts()` + `pointsFromCounts()`, six categories incl. route and MTP ascents — see [guide/climber_points.md](../guide/climber_points.md)).

## Manage-user modal

`items/modal/tab_modals/user_manage/UserManageModalComponent.vue`, opened with `show_modal(user_id, table_row, tab = 'profile')`.

- **Header:** avatar (or initials), name, `#id`, email · phone, badges (verified / banned / team / role / organization), points and last seen. Badges come from the table row and refresh after every save, because each save reloads the table.
- **Tabs:** each tab is a separate component that loads its own data only when it's opened and has its own save button. A tab is hidden when the admin lacks the permission for it.

| Tab | Component | Permission | Does |
|---|---|---|---|
| Profile | `ProfileTab.vue` | always visible; fields editable with `user › edit` | Edit name, surname, email, phone, country, city, bio (`POST set_user/update_user_info/{id}`). Server validation errors show under each field. Changing the email warns that verification resets. Verify-email button. Team-membership switch + status text (`team › edit`). |
| Role & permissions | `RoleTab.vue` | `role › edit` | Role select, assigned extra permissions as removable chips, picker for adding more. |
| Warehouse | `WarehouseTab.vue` | `warehouse › edit` | Sale-point warehouse assignment; only when the user has `warehouse › sell_own`. |
| Organization | `OrganizationTab.vue` | `partner_organization › add` | Assign / change / remove partner organization. |
| Ban & password | `SecurityTab.vue` | `user › create_ban` or `user › edit` | Ban with duration / unban; set a new password (logs the user out everywhere). |

Feedback uses toasts (`$bus.$emit('toast', …)`), not `alert()`.

## Rules

- Save buttons stay disabled until something changed (profile, team, warehouse, organization).
- Delete stays in the table with a confirm. It isn't in the modal.
