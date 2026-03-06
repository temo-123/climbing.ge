# Vue 2 to Vue 3 Migration with SSR - TODO List

## Phase 1: Package Updates
- [x] 1.1 Update package.json with Vue 3, Nuxt 3, Pinia dependencies
- [x] 1.2 Install new dependencies
- [x] 1.3 Remove incompatible Vue 2 packages

## Phase 2: Nuxt 3 SSR Setup
- [x] 2.1 Create nuxt.config.ts
- [x] 2.2 Set up Vite configuration
- [x] 2.3 Configure SSR settings
- [x] 2.4 Set up plugins for Vue 3 compatibility

## Phase 2.5: SCSS Assets Setup
- [x] 2.5.1 Create main.scss in assets/scss/
- [x] 2.5.2 Copy variables from resources/sass/_variables.scss
- [x] 2.5.3 Update nuxt.config.ts to point to correct SCSS

## Phase 3: Component Migration (Vue 2 → Vue 3)
- [x] 3.1 Create layouts (default, auth, error)
- [x] 3.2 Create pages directory structure
- [x] 3.3 Migrate Vue Router to Nuxt 3 pages
- [x] 3.4 Migrate Vuex store to Pinia stores
- [x] 3.5 Update Vue i18n to v9 with Nuxt support
- [x] 3.6 Migrate key components (LocaleChange, SocialLinks, GoOnTop, NewsCard)
- [x] 3.7 Update Bootstrap 5 integration

## Phase 4: Build System
- [x] 4.1 Migrate from Laravel Mix to Vite (already done in nuxt.config.ts)
- [x] 4.2 Update SCSS assets
- [x] 4.3 Configure image handling

## Phase 5: SSR Integration
- [x] 5.1 Create composables for data fetching
- [x] 5.2 Integrate with Laravel backend API
- [x] 5.3 Configure SSR caching

## Phase 6: Testing & Optimization
- [x] 6.1 Test all components
- [x] 6.2 Optimize for production
- [x] 6.3 Fix compatibility issues

## Phase 7: Divide Frontend and Backend ✅ COMPLETED
- [x] 7.1 Create frontend directory structure (/var/www/html/frontend)
- [x] 7.2 Move frontend files to /var/www/html/frontend
- [x] 7.3 Move backend files to /var/www/html/backend
- [x] 7.4 Update frontend API configuration
- [x] 7.5 Set up CORS for local development
- [x] 7.6 Test separation

---

# CONNECT FRONTEND AND BACKEND

## Task: Connect Frontend and Backend
- [x] 1. Create frontend .env file with API URL
- [x] 2. Verify backend CORS configuration
- [x] 3. Verify API routes registration
- [ ] 4. Test the connection

---

## Configuration

### Frontend (nuxt.config.ts)
- Updated `apiBaseUrl` to point to local Laravel API: `http://localhost:8000/api`

### Backend (config/cors.php)
- Updated CORS to allow frontend origins: `http://localhost:3000`, `http://127.0.0.1:3000`

## To Run the Projects

### Frontend
```bash
cd /var/www/html/frontend
npm install
npm run dev   # Runs on http://localhost:3000
```

### Backend
```bash
cd /var/www/html/backend
composer install
php artisan serve   # Runs on http://localhost:8000
```

## Environment Variables to Update

### Frontend (.env or nuxt.config.ts)
```
MIX_APP_SSH=http://localhost:8000/api
```

### Backend (.env)
```
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000
```

