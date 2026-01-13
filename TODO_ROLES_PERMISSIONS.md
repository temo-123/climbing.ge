# Roles and Permissions Update - TODO

## Completed Tasks

### 1. PermissionTableSeeder.php - Fixed
- ✅ Clear existing data before seeding:
  - `role_permissions` table
  - `user_permissions` table
  - `permissions` table

### 2. RoleTableSeeder.php - Fixed and Updated
- ✅ Clear existing data before seeding:
  - `role_permissions` table
  - `roles` table
- ✅ Fixed syntax error (`->get(); get();`)
- ✅ Fixed permission query logic (using proper `whereIn()` clauses)
- ✅ Preserved all existing roles
- ✅ Added new roles:
  - Worker
  - Team Manager
  - Guide Manager
  - Shop Manager
  - Live Camera Manager
  - User Management
  - Task Category Manager
  - Site Followers Manager
- ✅ Proper role_permissions relationships established

### 3. UserRoleSeeder.php - Updated
- ✅ Clear existing user-role assignments (`user_role` table)
- ✅ Creates admin and user if they don't exist
- ✅ Attaches roles to users
- ✅ Checks for duplicates to avoid re-attaching
- ✅ Provides status messages during seeding
- ✅ Commented examples for assigning roles to specific users

### 4. DatabaseSeeder.php - Updated
- ✅ Added UserRoleSeeder to the seeding order

## Seeding Order
1. `PermissionTableSeeder` - Creates permissions
2. `RoleTableSeeder` - Creates roles and attaches permissions
3. `UserRoleSeeder` - Creates users and assigns roles
4. `User_notificationsTableSeeder` - Creates user notifications

## To Run the Seeder

```bash
# Refresh database and run all seeders
php artisan migrate:fresh --seed

# Or run specific seeders
php artisan db:seed --class=PermissionTableSeeder
php artisan db:seed --class=RoleTableSeeder
php artisan db:seed --class=UserRoleSeeder
```

## New Roles Added

| Role Name | Slug | Description |
|-----------|------|-------------|
| Worker | worker | Site Worker |
| Team Manager | team_manager | Team Manager |
| Guide Manager | guide_manager | Guide Manager |
| Shop Manager | shop_manager | Shop Manager |
| Live Camera Manager | live_camera_manager | Live Camera Manager |
| User Management | user_management | User Management |
| Task Category Manager | task_category_manager | Task Category Manager |
| Site Followers Manager | site_followers_manager | Site Followers Manager |

## Assigning Roles to Users

To assign roles to specific users, edit `UserRoleSeeder.php` and uncomment the relevant sections:

```php
// Example: Assign Content Manager role
$managerEmails = [
    'manager@example.com',
];

$managerRole = Role::where('slug', 'content_menager')->first();

foreach ($managerEmails as $email) {
    $managerUser = User::where('email', $email)->first();
    if ($managerUser && $managerRole) {
        if (!$managerUser->role()->where('role_id', $managerRole->id)->exists()) {
            $managerUser->role()->attach($managerRole);
        }
    }
}
```

## Verifying Assignments

```php
// Check user roles
$user = User::find(1);
$user->roles; // Get all roles

// Check role permissions
$role = Role::where('slug', 'admin')->first();
$role->permissions; // Get all permissions

// Check if user has specific role
$user->hasRole('admin'); // Returns true/false
```

## Tables Affected

| Table | Description |
|-------|-------------|
| `permissions` | Stores all permissions (subject + action) |
| `roles` | Stores all roles |
| `role_permissions` | Pivot table - role to permission mappings |
| `user_role` | Pivot table - user to role mappings |
| `user_permissions` | Direct user permissions (if used) |


