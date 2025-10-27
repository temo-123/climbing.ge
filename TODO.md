# TODO: Import Multiple API Route Files

## Steps to Complete
- [x] Edit app/Providers/RouteServiceProvider.php to load multiple API route files from routes/api_groups/ instead of single routes/api.php
- [x] Run php artisan route:list to verify routes are loaded correctly
- [x] Check for any routes in routes/api.php not covered by api_groups (e.g., token route) and handle if needed
- [x] Optionally, remove duplicated routes from routes/api.php to avoid conflicts
- [x] Fixed missing TaskController routes by removing them
- [x] Fixed SearchController namespace
- [x] Fixed CKEditorController namespace
