# Favorite Button Active Style Implementation

## Task
Add active styling to favorite button if event is in user's favorites

## Files to Edit

### 1. Backend Changes
- [x] `app/Http/Controllers/Api/User/FaworitesController.php` - Add `check_interested_status` method
- [x] `routes/api/get_user_routes.php` - Add route for checking interested events status

### 2. Frontend Changes  
- [x] `resources/js/components/guide/pages/pages/EventPageComponent.vue` - Implement favorite status checking and toggle functionality

## Implementation Steps

### Step 1: Add check_interested_status method to FaworitesController.php ✅
- Create method to check if event is in user's interested events
- Return JSON with `is_interested` boolean

### Step 2: Add route for checking interested events ✅
- Added GET route: `/check_interested_status/{event_id}`

### Step 3: Update EventPageComponent.vue ✅
- Add `is_interested` data property
- Add `check_interested_status()` method
- Add `toggle_interested_status()` method  
- Update template with dynamic class binding for active styling
- Call check on component mount
- Update styles for active state

## Reference
- See OutdoorComponent.vue for working implementation pattern
- See check_favorite_status method for implementation pattern

## Summary
The favorite button now:
1. Checks if event is in user's interested events when page loads
2. Shows filled heart (fa-heart) with red color when event is in favorites
3. Shows outlined heart (fa-heart-o) with gray color when not in favorites
4. Allows toggling (add/remove) from favorites
5. Shows login prompt if user is not authenticated


