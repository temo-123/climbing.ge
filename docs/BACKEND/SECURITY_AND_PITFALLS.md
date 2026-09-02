# Backend Security & Pitfalls — Full-Stack Audit (August–September 2026)

A full-stack bug and security audit was run across every backend controller, model, service, middleware, mail/notification class, and the Vue frontend, specifically looking for account-safety issues (what data a public/guest-reachable endpoint exposes) and correctness bugs. This document records every bug found and fixed, the two recurring bug *classes* behind most of them (so they don't get reintroduced), and a checklist to run against any new or modified endpoint.

CLAUDE.md's [Common Backend Pitfalls](../../CLAUDE.md#common-backend-pitfalls) section is the short version of this doc — read that first, come here for the full instance list and the "why."

---

## Table of Contents

- [Bug Class 1 — Sanctum Guard Mismatch](#bug-class-1--sanctum-guard-mismatch)
- [Bug Class 2 — Public Endpoints Over-Exposing User Data](#bug-class-2--public-endpoints-over-exposing-user-data)
- [Bug Class 3 — Dead / Broken Routes](#bug-class-3--dead--broken-routes)
- [Other Bugs Found (Not Part of Either Class)](#other-bugs-found-not-part-of-either-class)
- [Reviewed and Found Clean](#reviewed-and-found-clean)
- [Known Issues Left Unfixed — Flagged for a Deliberate Decision](#known-issues-left-unfixed--flagged-for-a-deliberate-decision)
- [Checklist: Auditing a New or Existing Endpoint](#checklist-auditing-a-new-or-existing-endpoint)

---

## Bug Class 1 — Sanctum Guard Mismatch

**Root cause:** `auth()->user()`, `Auth::user()`, and `Auth::id()` all resolve against Laravel's *default* auth guard (`config('auth.defaults.guard')`, session/cookie-based). This app's API is authenticated with a Sanctum **Bearer token** (see [AUTH.md](../AUTH.md)) — a Bearer-token request carries no session cookie, so on any route not wrapped in `auth:sanctum` middleware, every one of those calls silently resolves to `null` for every real, logged-in user. There is no exception thrown at the call site itself — the bug only surfaces when the *next* line dereferences the null (`->id`, `->products`, etc.), which is a crash, or when the code has an `if ($user)` guard, in which case it just quietly treats every logged-in user as a guest.

This is easy to miss in manual testing because it can look completely fine: a developer testing a "my favorites" page while logged in via the browser session (which *does* carry cookies during local dev, depending on how the dev server is set up) may never see it, while the real Bearer-token mobile/API client always hits the bug.

**The two correct fixes** (pick based on whether the route is guest-reachable):

| Situation | Fix |
|---|---|
| Route should always require login | Add `->middleware('auth:sanctum')` to that specific route in `routes/api/*.php`. Once Sanctum's middleware authenticates the request, it internally calls `Auth::shouldUse('sanctum')`, so plain `auth()->user()` *also* starts resolving correctly for the rest of that request. Still prefer swapping the controller to `auth('sanctum')->user()` explicitly — see next row. |
| Route is intentionally dual-flow (works logged-out and logged-in — guest comments, guest donations, guest tour reservations) | Do **not** add `auth:sanctum` middleware (it would 401 real guests). Just swap the call to `auth('sanctum')->user()` / `auth('sanctum')->id()`, which correctly returns the user *if* a valid Bearer token was sent, and `null` otherwise — exactly the semantics the dual-flow code already assumed it had. |

`App\Services\PermissionService::checkPermission()` has the identical bug (`auth()->check()` on the default guard) — it only works correctly when called from a route that's under `auth:sanctum` middleware. Never call `PermissionService::authorize()` from a route with no auth middleware — it will 403 real logged-in users, not just guests.

### Every instance found and fixed this session

| # | File : Method | Route | Symptom before fix | Fix |
|---|---|---|---|---|
| 1 | `Api/Guide/CommentController.php::get_user_comments` | `GET get_guide_comment/get_user_comments` | Crashed for every logged-in user (`null->article_comments`) | `auth('sanctum')->user()` + `->middleware('auth:sanctum')` |
| 2 | `Api/Guide/RoutesReitingController.php::get_user_review` | `GET get_route_review/get_user_review` | Crashed for every logged-in user | `auth('sanctum')->user()` + `->middleware('auth:sanctum')` |
| 3 | `Api/Guide/MtpReitingController.php::get_user_mtp_reviews` | `GET get_mtp_review/get_user_mtp_reviews` | Silently returned empty for every user (`Auth::id()` → null → no rows match) | `auth('sanctum')->id()` + `->middleware('auth:sanctum')` |
| 4 | `Api/Shop/ProductController.php::get_user_products` | `GET get_product/get_user_products` | Crashed for every logged-in user (`null->products`) | `auth('sanctum')->user()` + `->middleware('auth:sanctum')` |
| 5 | `Api/Shop/ProductController.php::get_user_favorite_products` | `GET get_product/get_user_favorite_products` | Always returned the string `'Plees login!'`, even for logged-in users | `auth('sanctum')->user()` / `->id()` + `->middleware('auth:sanctum')` |
| 6 | `Api/Shop/TourController.php::get_user_tours` | `GET get_tour/get_user_tours` | Crashed for every logged-in user (`null->tours`) | `auth('sanctum')->user()` + `->middleware('auth:sanctum')` |
| 7 | `Api/Films/FilmsController.php::get_faworite_film_list` | `GET film/get_faworite_film_list` | Always returned an empty guest response, even for logged-in users | `auth('sanctum')->user()` / `->id()` + `->middleware('auth:sanctum')` |
| 8 | `Api/Shop/UserTourReservationController.php::create_reservation` | `POST` (dual-flow, stays public) | Every logged-in user's reservation was silently saved as a **guest** reservation — never linked to their account, never auto-verified | `auth('sanctum')->user()` / `->id()`, **no** middleware added (must stay guest-reachable) |
| 9 | `Api/Guide/Donations/DonationPaymentController.php::processDonation` | `POST set_donation/process` (dual-flow, stays public) | Logged-in donors were always treated as anonymous — no name/email pre-fill, no account link | `auth('sanctum')->user()`, no middleware added |
| 10 | `App\Services\CommentService.php::create_comment` (used by `CommentController::create_comment`) | `POST set_guide_comment_by_gest/create_comment/{article_id}` (dual-flow) | Logged-in users posting a comment were treated as guests — skipped the ban check, subjected to the guest violation-count limit | `auth('sanctum')->user()`, no middleware added |
| 11 | `App\Services\CommentService.php::add_comment_complaint` | `POST` (dual-flow) | See Bug Class "Other" below — this one crashed outright, not just guard-mismatch | See row below |

---

## Bug Class 2 — Public Endpoints Over-Exposing User Data

**Root cause:** several public (no-auth) endpoints attached a `user`/`reviewer` object to their JSON response by loading an Eloquent relation with no column restriction (`$model->user` on a `hasOne`/`belongsToMany` to `User`) or by serializing a full `User::find()` result. Laravel's `$hidden = ['password', 'remember_token']` on the `User` model (`app/Models/User.php`) only strips those two columns — every other fillable column (`email`, `phone_number`, `country`, `city`, timestamps, `social_links`, `my_bio`, `is_team_member`, …) still serializes. The frontend components consuming these endpoints only ever render `id` / `name` / `surname` / `image` (confirmed by grepping the actual Vue call sites before fixing each one, listed below) — so every other field was pure unintended exposure to anonymous visitors.

**The fix**, applied per-callsite (never on the `User` model itself, which still needs to expose more to legitimately-authorized admin views): explicitly select/restrict the loaded relation to safe columns before returning it —

```php
$model->setRelation('user', $model->user()->select(['users.id', 'users.name', 'users.surname', 'users.image'])->first());
```

If the actual consumer turned out to be an **admin-only** view that legitimately needs more (e.g. a moderation table showing the commenter's email), the correct fix is gating the *route* — `auth:sanctum` middleware + `PermissionService::authorize($subject, $action)` inside the controller, matching the permission the frontend route guard already expects — not trimming the data multiple admins actually need. See `Guide/ClimberProfileController.php` — a public read-model reference that already gets this right (constrains its `User::select([...])` up front to only public-safe columns for both `list()` and `show()`).

### Every instance found and fixed this session

| # | File : Method | What leaked | Confirmed frontend only uses | Fix |
|---|---|---|---|---|
| 1 | `Api/Guide/CommentController.php::get_article_comments` | Full `User` (email, phone, country, city…) for every commenter and every comment-answer author, on the fully public per-article comment feed | `comment.user.id`, `.image` (name/surname rendered from the comment row's own stored columns, not the user relation) — `CommentFormComponent.vue` | Restricted `user()` load to `id, name, surname, image` |
| 2 | `Api/Guide/RoutesReitingController.php::get_all_route_reviews` | Same, for every route-page reviewer, on the public per-route review list shown on every climbing route page | `review.user.id`, `.name`, `.surname` — `RouteAllReviewsModal.vue` | Same restriction |
| 3 | `Api/Guide/MtpReitingController.php::get_all_mtp_reviews` | Same, for every multi-pitch reviewer, on the public per-MTP review list | `review.user.id`, `.name`, `.surname` — `MtpAllReviewsModal.vue` | Same restriction |

### Two admin-only endpoints that were reachable without any login at all

These aren't the field-restriction pattern above — they're worse: the *entire* admin moderation view (not just the `user` sub-object) was reachable by anyone who knew the URL, no token required.

| # | File : Method | Route | What was exposed | Fix |
|---|---|---|---|---|
| 4 | `Api/Guide/CommentController.php::get_all_comments` | `GET get_guide_comment/get_all_comments` | **Every** comment site-wide, including unpublished and admin-hidden ones, each with its stored `name`/`surname`/`email` columns (this table stores the guest commenter's raw contact info directly on the row, not just via a `User` relation) | Added `->middleware('auth:sanctum')` to the route + `PermissionService::authorize('comment', 'show')` at the top of the method — matching the permission (`[['show','comment']]`) the admin Vue route guard already requires for the page that calls this |
| 5 | `Api/Guide/RoutesReitingController.php::get_all_review` | `GET get_route_review/get_all_review` | Every route review site-wide with each reviewer's raw `email` (this one already explicitly builds `{name, surname, email}` — legitimate for its admin consumer, just unauthenticated) | Same fix: `->middleware('auth:sanctum')` + `PermissionService::authorize('comment', 'show')` |

Note: a properly-authenticated admin-namespaced duplicate of the review-listing logic already exists at `Api\User\Admin\Guide\RoutesReitingController::get_all_review` (registered under `set_route_review/get_all_review`, wrapped in the admin `auth:sanctum`+`banned` group) — the admin frontend page (`all_routes_reviews_page.vue`) calls the *wrong* (public) one. Gating the public one closes the hole either way, but pointing the frontend at the already-correct admin duplicate — and then retiring the public one entirely, since public route pages never need site-wide reviews with emails — would remove the duplication. Left as a follow-up; see [Known Issues](#known-issues-left-unfixed--flagged-for-a-deliberate-decision).

### Also fixed while in this area

- **`Api/Guide/ClimberProfileController.php::show()`** had no ban check (`list()` did) — a banned climber's public profile page (`/climber/:id`) remained fully reachable by direct URL. This was already a documented known inconsistency in [CLIMBER_PROFILE.md](../CLIMBER_PROFILE.md) — now fixed to match `list()`'s `whereDoesntHave('role', slug='ban')` filter.

---

## Bug Class 3 — Dead / Broken Routes

Several routes were registered pointing at controller methods that simply don't exist, or `Route::apiResource(...)` calls that register all 7 REST verbs (`index/store/show/update/destroy/create/edit`) when the target controller only implements a subset — hitting the missing ones throws `BadMethodCallException`, a 500 instead of a clean 404/405.

| File | Fix |
|---|---|
| `routes/api/get_guide_routes.php` | Removed `get_comments_complaints` route — pointed at a method that doesn't exist on `Api\Guide\CommentController` (a working, authenticated duplicate already exists at `set_guide_comment/get_comments_complaints`) |
| `routes/api/get_films_routes.php` + `routes/api/admin/set_films_routes.php` | `Route::apiResource('/films', 'FilmsController')` → `->only(['index'])` — both `FilmsController` classes only implement `index()` |
| `routes/api/admin/set_shop_routes.php` | `Route::apiResource('/cart', 'CartController')` → `->only(['index', 'update', 'destroy'])` — matches what `CartController` actually implements |
| `routes/api/meil.php` | `Route::apiResource('/message', 'MessageController')` → `->only(['store'])` — `MessageController` is a contact-form-style single-action controller |
| `routes/api/admin/set_user_routes.php` | `Route::apiResource('/', 'RolesController')` → `->only(['index'])` — the rest of role CRUD already has working dedicated routes (`create_role`/`edit_role`/`del_role`/`get_editing_role`) |

~34 more dead route→method bindings were found via a full `route:list` + `method_exists()` sweep but **not** touched — every one checked had the frontend actually calling a different, working route instead (e.g. competitions go through `CompetitionController@*`, not the orphaned `Guide\EventController@*competition*` methods also registered). These read as leftovers from past refactors rather than live bugs, but weren't exhaustively verified for every case. See [Known Issues](#known-issues-left-unfixed--flagged-for-a-deliberate-decision).

---

## Other Bugs Found (Not Part of Either Class)

| File : Method | Bug | Fix |
|---|---|---|
| `Api/Films/FilmsController.php` (5 call sites: `get_films`, `get_same_films`, `get_film`, `get_films_top`, `get_faworite_film_list`) | Called `GetFilmService::...` — a class that **does not exist anywhere in the codebase**. The real, already-imported class is `App\Services\FilmService`. This meant **every public film endpoint** (listing, single film, top films, related films, favorites) was a fatal error in production | Renamed all 5 call sites to `FilmService::` |
| `App\Services\CommentService.php::add_comment_complaint` (line ~168) | `$complaint_model::where('user_id', '=', Auth::user())` bound a full `User` object as a SQL query value instead of an id — throws on every execution. The "report this comment/review" feature was completely broken for every logged-in user | `auth('sanctum')->id()` |
| `App\Services\CommentService.php::confirm_email` | No null-guard on `Non_registered_commenter::where('email', ...)->first()` — a missing/stale/replayed confirmation link crashed on `null['confirmed'] = 1` | Added an early `return` when the row isn't found |

---

## Reviewed and Found Clean

So the absence of a finding isn't ambiguous — these areas were specifically checked against the two bug classes above and found correct, nothing was changed:

- **Follow/notification pipeline**: `FollowActivityNotificationService`, `NotificationDispatchService`, all 5 model observers (`UserFollowObserver`, `ArticleCommentUserObserver`, `SummitAscentUserObserver`, `SportRouteReviewObserver`, `MtpReviewObserver`), the `UserNotifications` queued job, and dedup via the `content_notification_logs` unique index — recipients are correct, no crashes on null relations, no duplicate sends.
- **HMAC token generation/verification** across `VerifyEmail`, `VerificationController`, `CommentController::confirm_email`, and the shop refund-link flow — all use `hash_equals()` (constant-time), not `==`/`===`.
- **Middleware ordering** — `auth:sanctum` → `banned` is correctly sequenced everywhere it appears.
- **Training mobile-app sync** (`UserTrainingSyncController`) — verified against its own documented invariants in [TRAINING_SYNC.md](../TRAINING_SYNC.md): timestamp columns are genuinely plain `varchar` (no accidental `datetime` cast that would reformat and break the string conflict-resolution comparison), and the internal auto-increment `id` never leaks into the JSON response in place of `client_id`/`plan_id`/`date`.
- **Raw `DB::table('users')` queries** — only ever `->pluck('id')`, never a shape that would bypass the `User` model's `$hidden` protection on `password`/`remember_token`.
- **Frontend (`resources/js/**`)** — spot-checked the highest-risk areas given the backend fixes above (climber-follow system, `ClimberProfileContentComponent.vue`, `UserFollowButtonComponent.vue`) plus a full-tree grep for common bug signatures (assignment-in-condition, unguarded `.user.` chains): all correctly guard null/loading/guest states. No bugs found or fixed on the frontend this session.

---

## Known Issues Left Unfixed — Flagged for a Deliberate Decision

- **~34 dead route→method bindings** (see [Bug Class 3](#bug-class-3--dead--broken-routes)) not individually deleted — confirming each is truly unreachable with certainty needs more time than a single pass, and deleting a route is more consequential than fixing one. Candidates include several `Guide\EventController@*competition*` methods, `Guide\ArticleController@get_last_news`, `Guide\GeneralInfoController@get_general_info`, `Guide\MountController@get_editing_mount_data`, `Guide\RouteJsonController@get_editing_route_json`, a few `Admin\Shop\ProductController@get_*` duplicates, `Admin\Shop\TourReservationController@get_reservation`, `Auth\VerificationController@show`.
- **Duplicate route-review "get all" endpoints**: the admin moderation page calls the now-secured *public* `Api\Guide\RoutesReitingController::get_all_review`, when a properly-scoped admin duplicate already exists at `Api\User\Admin\Guide\RoutesReitingController::get_all_review`. Worth eventually repointing the frontend and deleting the public one, since public route pages never need this endpoint.
- **Admin film CRUD is an unfinished feature**, not a bug: `filmsAddComponent.vue`/`filmsEditComponent.vue` are literal placeholder stubs on the frontend, and the `store`/`update`/`destroy` verbs were never implemented on the backend `apiResource` either (now correctly returns 404 instead of 500 — see Bug Class 3). Implementing the rest is new feature work, out of scope for a bug-fix pass.
- **`App\Services\CommentService.php`**, line ~56: `$auth->role[0]['slug'] == 'ban'` indexes `role[0]` directly with no guard for a user with zero roles. No evidence this actually happens in practice (every user is assumed role-assigned elsewhere too), so left untouched rather than guess at a behavior change.
- **4 near-identical hand-rolled debounce blocks** on the frontend (`TrainingAddComponent.vue`, `TrainingEditComponent.vue`, `ClimberListPageComponent.vue`, `NavbarSearchComponent.vue`) — genuine duplication, but each stores its timer on a differently-named instance property with a different delay/callback signature, so a safe consolidation needs per-call-site verification that wasn't done here.
- **`grade_chart_mixin.js`**'s `alpine()` method reads a `localStorage.getItem("EU")` fallback key that's never written anywhere in the codebase — dead but harmless (falls through correctly to the real `'grade'` key). Left alone since it's ambiguous whether it's scaffolding for an unshipped feature.
- **PHPUnit couldn't be run** in this environment to regression-test these changes end-to-end — `storage/logs/laravel.log` is owned by `www-data`, not writable by the shell user this session ran as. Verification instead relied on `php -l` (all 17 touched files), `php artisan route:list` booting cleanly, and manual cross-referencing of every diff against actual model/route/frontend usage (documented per-row above). Recommend running the real test suite (as `www-data`, or after fixing the log file permission) before deploying.

---

## Checklist: Auditing a New or Existing Endpoint

Run through this whenever you add a controller method, or touch one that handles user data:

1. **Does this route require login?** Check the actual route registration in `routes/api/**` — not just what the controller *assumes*. If it's under `Route::group(['middleware'=>['auth:sanctum','banned']])` (every file under `routes/api/admin/`) or has `->middleware('auth:sanctum')` on the specific line, `auth()->user()`/`Auth::` calls are safe. If not, and the method calls `auth()->user()`/`Auth::user()`/`Auth::id()`, that's Bug Class 1 — fix per the table above.
2. **Does the response include a `user`/`author`/`reviewer` object, or serialize a `User` model at all?** If the route is reachable without a token, grep the actual Vue component that calls it for which fields it reads (`.user.X`), and restrict the query/relation to exactly that. Never assume "the frontend probably doesn't show it" — verify.
3. **Is this genuinely meant to be admin-only** (a moderation table, a full audit list)? If so, both the route (`auth:sanctum`) *and* a `PermissionService::authorize($subject, $action)` call (matching the subject/action the frontend route guard already checks in `resources/js/routes/UserRoutes.js`'s `meta.permissions`) are required — not just one.
4. **If the route is a `Route::apiResource(...)`**, confirm the target controller actually implements every verb Laravel registers by default (`index/store/show/update/destroy`); if not, add `->only([...])` for just the implemented ones.
5. Run `php -l` on every file you touch before considering it done; there's no build step to catch a syntax error for you at review time.

---

[Go back](../../README.md)
