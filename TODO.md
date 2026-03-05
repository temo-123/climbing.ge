# Action Tracking Implementation for General Info "Show More" Button

## Completed Tasks
- [x] Analyze existing ActionTrackingService (Redis-based)
- [x] Add track_action method to GeneralInfoController
- [x] Add get_action_stats method to GeneralInfoController
- [x] Add routes for action tracking endpoints
- [x] Modify GeneralInfoComponent.vue to track show_more_click actions
- [x] Integrate axios call in Vue component

## Implementation Details
- Uses Redis for storing action counts by day, block ID, and action type
- Tracks "show_more_click" actions when user clicks the show more button
- API endpoints: POST /api/admin/set_general_info/track_action and GET /api/admin/set_general_info/get_action_stats
- Data stored with 30-day expiration in Redis
- Block ID from global_info_prop.id is used for tracking

## Testing
- Verify Redis connection is configured
- Test API endpoints manually
- Test Vue component action tracking on show more click
