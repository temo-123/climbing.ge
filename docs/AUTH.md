# Authentication & Authorization

---

## Table of Contents

- [Registration](#registration)
- [Login](#login)
- [Social OAuth](#social-oauth)
- [Email Verification](#email-verification)
- [Password Reset](#password-reset)
- [Session & Token Management](#session--token-management)
- [Account Deletion](#account-deletion)
- [Middleware](#middleware)
- [Role & Permission System (CASL + Spatie)](#role--permission-system)
- [Frontend Auth Flow](#frontend-auth-flow)

---

## Registration

**Endpoint:** `POST /api/register`  
**Controller:** `App\Http\Controllers\Auth\RegisterController@apiRegister`

### Request

```json
{
  "name": "John",
  "surname": "Doe",
  "country": "Georgia",
  "city": "Tbilisi",
  "phone_number": "+995500000000",
  "email": "john@example.com",
  "password": "secret123",
  "password_confirmation": "secret123",
  "lang": "us"
}
```

`country`, `city` and `phone_number` are **optional** (nullable) — the quick sign-up in the login modal only asks for name, surname, email and password; the profile checklist on the dashboard asks for the rest later. `password` requires `min:8` (not 6). `lang` is optional (`us` or `ka`, defaults to `us`) and sets the user's stored locale preference, used for email language later.

If `GOOGLE_CAPTCHA_V3_*` is configured (`ReCaptchaV3Service::isConfigured()`), the request must also include a `recaptcha_token` that passes `verifySmart()`, or registration fails with `422`.

### Process

1. Validates the fields above; the email-uniqueness check also rejects emails belonging to an already-banned user (`isBanned()`), even if that account no longer exists in a usable state.
2. Creates `User` record with hashed password.
3. Creates a `user_notification` record with all notification types enabled by default (only if one doesn't already exist for this user — relevant for the guest-linking case below).
4. Assigns the default `user` role.
5. **Links any pre-existing guest activity to the new account by email** — see [Guest Data Linking](#guest-data-linking) below. This runs on every registration, not just ones that had prior guest activity.
6. Fires `Registered` (queues the verification email) — wrapped in try/catch: **registration still succeeds even if the email fails to send** (SMTP down, rate-limited, etc.); the failure is only logged (`report($e)`), and the user can retry via "resend verification email" once logged in.
7. Logs the user in immediately and returns a Sanctum token + user data — the user does not have to wait for email verification to start using the site (features that require a verified email, if any, are gated separately per-feature, not at login).

### Response

```json
{
  "token": "1|abc123...",
  "user": { "id": 1, "name": "John", "email": "john@example.com", ... },
  "message": "Registration successful. Please check your email to verify your account."
}
```

Status `201`. Validation failures return `422` with `{ "message": "Validation failed", "errors": {...} }`.

### Guest Data Linking

**Service:** `App\Services\GuestDataLinkingService::link_all($user)` — called at the end of every registration (`RegisterController::create()`).

Before an account exists, the site lets guests do a few things by just typing an email: submit a summit ascent (`POST /api/summit/ascent/{id}`, no auth), leave an article comment as a non-registered commenter, or make a donation. When someone later registers with that same email, this service retroactively attaches all of that prior activity to the new account, and cleans up the now-redundant guest record:

| Guest activity | Linked by matching `email` on... | Effect |
|---|---|---|
| Donations | `donations.email` where `user_id IS NULL` | Sets `donations.user_id` |
| Summit ascents | `summit_ascents.email` | Creates a `summit_ascent_users` row (so it shows up in the new account's ascent history / [Climber Profile](CLIMBER_PROFILE.md)) |
| Article comments | `comments.email` | Creates an `article_comment_user` row **and** force-publishes the comment if it wasn't already (`published = 1`) |
| Non-registered commenter record | `non_registered_commenters.email` | Deleted — the user now has a real account with its own notification preferences |

This means a climber who logged ascents or left comments as a guest, then registers later with the same email, sees that history appear on their profile immediately — no manual re-linking. Added by `database/migrations/2026_08_15_153533_add_user_id_to_donations_table.php` and the `2026-08-15` "Fixed registration buigs" commits; there is no equivalent teardown if a user later changes their email, so a changed email silently stops matching any *new* guest activity (existing linked rows are unaffected).

---

### Legal pages (Terms of Use, Privacy Policy)

The registration form requires two checkboxes — Terms of Use and Privacy Policy — before **Register** is enabled; each label opens a modal with the full text and an "I agree" button that ticks the box. This is frontend-only: `POST /api/register` does not receive or store the consent (the mobile app uses the same endpoint).

Texts live in `locale_sites` (site data, editable in the admin site-info page) under the slugs `terms_of_use` and `privacy_policy` (`us_data` / `ka_data`, HTML). Initial content: `privacy_policy` is added by migration `2026_10_18_130000_add_privacy_policy_to_locale_sites.php` (only if missing; contact email from `sites.email`); the full `terms_of_use` text is in `docs/BACKEND/sql-imports/legal_pages.sql` (set `@contact_email` first). They're rendered by `global_components/LegalDocumentComponent.vue`, which tries a subdomain-specific slug first (`shop_terms_of_use`, `user_privacy_policy`, …) and falls back to the shared one, and keeps line breaks for plain-text content. Every subdomain serves `/terms_of_use` and `/privacy_policy` with its own `TermsOfUsePage.vue` (`doc` route prop); both are linked from `FooterBottomTextComponent.vue` — the shared footer row on **every** service (guide, shop, blog, summit, films, user portal) with Terms of Use · Privacy Policy · Donation Terms (`/donation_terms`, same page component with `doc: 'donation_terms_of_use'`) · Delete account (link to `user.climbing.ge/delete-account`) — and from every About Us page (`global_components/LegalLinksComponent.vue`), and are public on `user.climbing.ge` (`userPublicRoutes` + `authNeutralRoutes` in `app.js`). Play Console privacy policy URL: `https://climbing.ge/privacy_policy`.

**Current legal texts**: `docs/BACKEND/legal_texts/privacy_policy_{us,ka}.html` and `terms_of_use_{us,ka}.html`, published with `docs/BACKEND/sql-imports/publish_legal_texts.sql` — **not** through the admin site-info editor: it's Quill, which rewrites/drops `<ul>`, nested lists and `<code>` on paste (this emptied the live policy's lists in October 2026). The SQL backs up the rows it overwrites (`<slug>_backup_20261019`) and also sets `guide_terms_of_use` (the override actually shown on climbing.ge/terms_of_use) to the new terms + the existing "Part II: UIAA Mountain Ethics Code of Conduct"; the other `*_terms_of_use` overrides (shop, blog, films, summit, user) are left as they are. Texts — minimum age 16 in both, camera for summit QR codes and offline-upload notifications in the privacy policy, content rules + reporting in the terms. Earlier **privacy policy updates** shipped as a new migration that overwrites the text (latest: `2026_10_19_100000_update_privacy_policy_for_mobile_apps.php`, contact email from `sites.email`; `down()` is a no-op); `docs/BACKEND/sql-imports/privacy_policy_update.sql` holds the same text as a manual alternative. Earlier migrations are never edited. Current version covers the website and both apps — **Climb Georgia** (guidebook, `ge.climbing.app`) and the training app — with sections for donations, mobile-app permissions and on-device data, analytics (Google Analytics on the web, Google Analytics for Firebase in the apps: pseudonymous, app-instance id), and account deletion including the keep-anonymously option. Keep it in sync with the Play Console Data safety form.

**Donation legal texts** — **set aside until the legal details are available**: migration `database/migrations_pending/2026_10_19_110000_update_donation_legal_texts.php` (outside `database/migrations`, so a plain `php artisan migrate` and deploys skip it). Values from `ORGANIZATION_*` in `.env` via `config/organization.php` (legal name us/ka, registration number and address us/ka are required — the migration throws if they're missing, so placeholders are never published; tax status and email are optional). To apply: set the values, `php artisan config:clear`, then `php artisan migrate --path=database/migrations_pending/2026_10_19_110000_update_donation_legal_texts.php`. `docs/BACKEND/sql-imports/donation_legal_texts.sql` is the manual alternative. The texts: `organization_legal_info` (legal name, NNLE / ა(ა)იპ form, identification code, address, email — shown under "Organization Contacts" on the guide About page, hidden while empty; row created empty by `2026_10_18_150000_add_organization_legal_info_to_locale_sites.php`), `donation_terms_of_use` (donation modal + mobile app: voluntary, gift with nothing in return, refund rule, TBC Bank handles card payments), and the privacy policy's "Donations" section (form fields name/surname/email/phone/country/age/comment + amount; card data only at TBC Bank). The privacy policy's Donations section is part of `privacy_policy_update.sql`.

---

## Login

**Endpoint:** `POST /api/login`  
**Controller:** `App\Http\Controllers\Auth\LoginController@apiLogin`

### Password Encryption

Passwords are encrypted client-side before transmission using **RSA + JSEncrypt**:

```javascript
// Frontend (app.js or login component)
const encrypt = new JSEncrypt()
encrypt.setPublicKey(publicKey)   // fetched from server
const encryptedPassword = encrypt.encrypt(plainPassword)
```

### Request

```json
{
  "email": "john@example.com",
  "password": "<RSA-encrypted-base64-string>"
}
```

### Process (`LoginController::apiLogin`)

1. If `GOOGLE_CAPTCHA_V3_*` is configured, requires a `recaptcha_token` that passes `ReCaptchaV3Service::verifySmart()` — same as registration, `422` on failure. The frontend (`LoginComponent.vue`) fetches this token client-side via `grecaptcha.execute(key, {action:'login'})` before ever POSTing.
2. Fetches RSA private key from `storage/framework/private.key`, decrypts the password with `openssl_private_decrypt()`. Missing/unreadable key file → `500 {"message":"Server configuration error"}`; decrypt failure → `400 {"message":"Invalid encrypted password"}`.
3. Validates credentials with `auth()->attempt($credentials, $request->remember)` — this uses Laravel's **default** guard purely to verify the email/password pair against the `users` table; it is not the same as being authenticated for the rest of the request (see [Common Backend Pitfalls](../CLAUDE.md#common-backend-pitfalls) for why the default guard is generally *not* safe to rely on elsewhere in this codebase — login is the one legitimate exception, since it's establishing identity fresh, not reading an already-authenticated user).
4. On success, checks `$user->isBanned()` — if banned, logs the just-established session back out (`auth()->logout()`) and returns `403` with an `alert` object (`type/title/message/icon`) the frontend renders as a toast.
5. **`$user->tokens()->delete()`** — every prior Sanctum token for this user is revoked before a new one is issued. Logging in on a new device/browser silently invalidates every other active session for that account; there is no "active sessions" list to review this or opt out of it.
6. Creates a new token (`createToken('authToken')->plainTextToken`) and returns it.
7. Every attempt is logged: a successful login logs `Login successful for user: {email}`, a bad-credentials attempt logs `Login failed for email: {email}` and returns `422 {"message":"auth.failed"}` (a raw translation key string, not a human-readable message — the frontend is expected to run it through i18n), and an unexpected exception is caught, logged with file/line, and returns a generic `500`. The handler also logs every incoming request's IP, scheme, `X-Forwarded-Proto`/`X-Forwarded-Ssl`, user agent, and referer up front (`'API Login request'` log entry) — added specifically to debug mixed-content/HTTPS-detection issues behind a reverse proxy; check `storage/logs/laravel.log` for this line first if login works locally but not behind production's proxy.

### Response

```json
{
  "token": "2|xyz789...",
  "user": { "id": 5, "name": "John", "surname": "Doe", "email": "john@example.com", ... },
  "message": "Login successful"
}
```

That's the **entire** response shape — `user` is the raw `User` model (Eloquent's `$hidden` still strips `password`/`remember_token`), and there is **no** `roles` or `casl_permissions` key inline despite older versions of this doc claiming otherwise. Those come from two separate follow-up calls the frontend makes immediately after login succeeds (see below) — `GET /api/auth_user` is a third source of the same permissions data, used on every subsequent page load rather than right after login.

### Frontend token storage (`LoginComponent.vue`)

```javascript
// .then(response => { ... })
localStorage.setItem('auth_token', response.data.token)                                  // the Sanctum Bearer token from the login response
localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])             // NOT the login token — the CSRF token Laravel's stateful-domain cookie middleware attached to the outgoing request itself

// then, still inside the same .then(), before redirecting:
axios.get('get_user/get_auth_user_permissions/', { headers: { Authorization: 'Bearer ' + response.data.token } })
  .then(permResponse => {
    localStorage.setItem('user_permissions', JSON.stringify(permResponse.data))  // 3rd localStorage key, permissions-only cache
    this.$ability.update(permResponse.data)                                       // CASL updated immediately, before the redirect
    this.$bus.$emit('permissions-loaded', permResponse.data)
    this.$router.push(this.$route.query.redirect || { name: 'home' })
  })
```

`auth_token` and `x_xsrf_token` end up holding **different values from different sources** — don't assume they're the same token under two names. `Api\User\UsersController::get_auth_user_permissions` (`GET get_user/get_auth_user_permissions`, `auth:sanctum`+`banned`) exists specifically so the frontend can populate CASL synchronously right after login without waiting for the next page's `/api/auth_user` round-trip; there's also an admin-namespaced duplicate registered under `routes/api/admin/set_user_routes.php` (`Api\User\Admin\User\UsersController::get_auth_user_permissions`) with the same method name — check which one a given call site actually resolves to via the URL prefix (`get_user/...` vs `set_user/...`), not just the method name.

---

## Social OAuth

**Provider support:** Facebook, Google (via Laravel Socialite)

### Endpoints

| Method | Path | Description |
|---|---|---|
| GET | `/api/login/{provider}` | Redirect to OAuth provider |
| GET | `/api/login/{provider}/callback` | OAuth callback |
| POST | `/api/login/social/create_password/{email}` | Set password after first social login |

**Controller:** `App\Http\Controllers\Auth\SocialController`

### Flow (`SocialController::callback`)

1. Frontend calls `GET /api/login/{provider}` → redirected to the provider's consent screen.
2. Provider redirects back to `user.climbing.ge/login/{provider}/callback?code=...`; the Vue route renders `CallbackComponent.vue`, which itself calls `GET /api/login/{provider}/callback?code=...` (Socialite `stateless()`, since there's no server session to correlate the original redirect).
3. **Existing user** (matched by email): checked for a ban first (`403` if banned, same as regular login) — otherwise a new Sanctum token is created and returned **directly in the JSON body**: `{status:'login', token:'...'}`. There is no redirect-with-query-param and no separate `GET /api/token` round-trip — the frontend reads `response.data.token` straight off this response and writes it to `localStorage.auth_token` itself. Unlike email/password login, `x_xsrf_token` is **not** set by this path.
4. **New user**: created with `email_verified_at = now()` immediately (the OAuth provider already verified the email, so there's no verification-email step and — despite what an older version of this doc claimed — **no welcome email is sent** either; nothing in `SocialController::callback` fires `Registered` or queues any mail). Full name is split into `name`/`surname` on the first space; avatar URL saved via `forceFill` (not mass-assignable); a `Social_account` row links the OAuth provider + provider user id to the new `User`; the default `user` role and default-enabled `user_notifications` row are created via `createUserPermissionsAndNotifications()`. Response: `{status:'registratione', new_user_email:'...'}` — no token yet, since the account has no password.
5. A user with no password set (every first-time social login) is routed to `/create_password/{email}` → `POST /api/login/social/create_password/{email}` sets one, after which they can also log in the normal email/password way.

### Environment Variables

```env
# Local dev
FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_URL=http://user.climbing.loc/login/facebook/callback

GOOGLE_CLIENT_ID=xxxxxxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxxxxxxxxxx
GOOGLE_URL=http://user.climbing.loc/login/google/callback

# Production
# FACEBOOK_URL=https://user.climbing.ge/login/facebook/callback
# GOOGLE_URL=https://user.climbing.ge/login/google/callback
```

See [OAUTH_SETUP.md](OAUTH_SETUP.md) for step-by-step Google / Facebook / Strava app setup.

---

## Email Verification

**Controller:** `App\Http\Controllers\Auth\VerificationController`

| Method | Path | Description |
|---|---|---|
| GET | `/api/email/verify/{user_id}/{hash}` | Verify email link — public, no auth required |
| GET | `/api/email/resend` | Resend verification email (`auth:sanctum` + `banned` required) |

The `User` model implements `MustVerifyEmail`, but verification does **not** use Laravel's built-in signed-URL mechanism. `{hash}` is a custom HMAC: `hash_hmac('sha256', "{email}|{id}", config('app.key'))`, generated identically in both the notification (`App\Notifications\VerifyEmail::verificationUrl()`) and the controller (`VerificationController::verify()`, via `hash_equals()`). There used to be a second, separate `Api\User\VerificationController` using Laravel's default `Crypt::decrypt()`-based signed URLs — it was **dead code** (never routed) and was deleted in the August 2026 registration/verification cleanup; if you find references to it elsewhere, they're stale.

`resend` now wraps `sendEmailVerificationNotification()` in a try/catch and returns `500 {"message": "Could not send verification email. Please try again in a moment."}` on failure instead of letting an SMTP error bubble up as an unhandled 500.

**Env vars that actually build the verification URL:**
```env
APP_SSH=https://
USER_PAGE_URL=user.climbing.ge
# VerifyEmail builds: rtrim(APP_SSH . USER_PAGE_URL, '/') . '/email/verify/{user_id}/{hash}'
```

`FRONTPAGE_VERIFY_URL` (and `config('frontent.email_verify_url')`, which reads it) still exists but is **dead** — nothing in the codebase consumes it anymore. Don't set it expecting it to change the verification link; edit `APP_SSH` / `USER_PAGE_URL` instead.

### Cross-device verification (Verify.vue)

**`resources/js/components/auth/Verify.vue`**

The verification link can now be opened on a *different* device/browser than the one used to register (e.g. checking email on a phone after registering on a laptop). The page checks `isLoggedInHere` (`!!(localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token'))`) independently of the verification API call itself:

- Verification succeeds **and** this browser is already logged in (same device) → auto-redirects straight to the dashboard.
- Verification succeeds **and** this browser is *not* logged in (different device) → shows a "Log in to continue" button instead of a broken "Go to dashboard" link.

Relatedly, the global axios 401/419 interceptor in `resources/js/bootstrap.js` now only treats a 401 as "your session died" (clear tokens + redirect to login) if a token actually existed in `localStorage` first. Before this fix, a background `auth_user` poll (e.g. `NavBadges`) returning 401 on a guest-accessible page like `/verify` or `/register` would incorrectly yank a not-yet-logged-in visitor away from the page they were legitimately on.

### Custom Notification

`App\Notifications\VerifyEmail` — overrides default Laravel verification email with custom branding and bilingual (EN/KA) content, and the custom HMAC URL scheme described above.

---

## Password Reset

**Controllers:** `ForgotPasswordController`, `ResetPasswordController`

| Method | Path | Description |
|---|---|---|
| POST | `/api/password/send_forget_mail` | Send reset link to email |
| POST | `/api/password/reset_password` | Submit new password with token |

### Request: send reset mail

```json
{ "email": "john@example.com" }
```

### Request: reset password

```json
{
  "email": "john@example.com",
  "token": "abc123resettoken",
  "password": "newpassword",
  "password_confirmation": "newpassword"
}
```

---

## Session & Token Management

**Controller:** `App\Http\Controllers\Auth\AuthenticationController`

| Method | Path | Description |
|---|---|---|
| GET | `/api/auth_user` | Returns authenticated user + abilities |
| POST | `/api/logout` | Revokes current Sanctum token |
| GET | `/api/token` | Creates new Sanctum token |

**Related env vars:**
```env
SANCTUM_STATEFUL_DOMAINS=climbing.loc,user.climbing.loc,shop.climbing.loc,...
SESSION_DOMAIN=.climbing.loc
SANCTUM_TOKEN_EXPIRATION=10080   # minutes — default 10080 = 7 days
```

### `GET /api/auth_user`

Used on every page load to restore user state in Vue. Returns:

```json
{
  "id": 5,
  "name": "John",
  "surname": "Doe",
  "email": "john@example.com",
  "avatar": "avatars/john.jpg",
  "roles": ["admin"],
  "casl_permissions": [
    {"action":"add","subject":"article"},
    {"action":"edit","subject":"summit"}
  ]
}
```

Returns `401` if not authenticated — intercepted by axios to clear `localStorage` and redirect to login.

---

## Account Deletion

Self-service, permanent account deletion — required by the Google Play User Data policy (in-app path + public web URL).

**Endpoint:** `POST /api/account/delete` (`auth:sanctum` + `banned`)  
**Controller:** `App\Http\Controllers\Auth\AuthenticationController@deleteAccount` → `App\Services\AccountDeletionService::delete($user)`

### Request

```json
{ "confirm": "DELETE", "password": "secret123", "keep": ["reviews", "comments"] }
```

`confirm` must be exactly `DELETE` — typed by the user on the web page (the Delete button stays disabled until the password is filled and the field equals `DELETE`). `password` is plain text (not RSA-encrypted like login) and **always required** — social signups set a password via `create_password`; anyone who skipped that uses "Forgot password" (linked on the page; `forget_pass` is in `authNeutralRoutes` so it works while logged in).

### Responses

- `200 {"message":"Account deleted"}` — all Sanctum tokens are revoked.
- `422` — missing `confirm`, or `errors.password` = `"The password is incorrect."`.
- `409` — the user authored blog `posts` (`posts.user_id` is NOT NULL); an admin must handle it.

### What is deleted vs kept

- **Deleted:** the user, tokens, social accounts, roles/permissions, notifications, unused addresses, favorites, cart, follows, training sync data, the `non_registered_commenters` row, profile image. Tables with an FK cascade to `users` are cleaned by the database.
- **User's choice — `keep`** (optional array, values from `AccountDeletionService::KEEPABLE`): `ascents` (summit + multi-pitch + route), `reviews` (route + MTP reviews), `product_feedbacks`, `comments` (article comments). Anything **not** in `keep` is deleted. Anything in `keep` stays public but anonymized: comments, product feedbacks and summit ascents get `name = "Deleted user"`, `surname = ""`, `email = NULL`; route reviews, MTP reviews, MTP ascents and route ascents get `user_id = NULL` (made nullable, `ON DELETE SET NULL` for the two FK tables, by `2026_10_18_140000_make_review_and_ascent_user_id_nullable.php`). The delete page shows these as four "Delete my …" checkboxes, all checked by default; public review/ascent modals label user-less rows `guide.route.deleted_user`.
- **Kept, detached (`user_id = NULL`):** `orders`, `donations`, and addresses used by an order — accounting records. `2026_10_17_120000_make_user_adreses_user_id_nullable.php` makes `user_adreses.user_id` nullable (`ON DELETE SET NULL`) for this.

No soft-delete or grace period — deletion is immediate. Admin `set_user/del_user` and `bulk_delete` do **not** use this service.

### Public web page

`https://user.climbing.ge/delete-account` (`DeleteAccountPage.vue`, route `delete_account`) — the URL for Play Console → App content → Data safety → Data deletion. Listed in both `userPublicRoutes` and `authNeutralRoutes` in `app.js`, so it shows the explanation logged out (login button redirects back) and the confirmation form logged in. Linked from `optionsPageComponent.vue`.

---

## Middleware

### `auth:sanctum`

Laravel Sanctum token/session authentication. Applied to all admin and user routes.

### `banned` (Custom: `CheckBannedUser`)

Checks if the user has a role with `slug = 'ban'` in the `user_role` pivot table. If banned, it revokes all tokens and returns `403`. The ban is managed via `BanController` (attach/detach the `ban` role).

### `AjaxHeader`

Enforces `X-Requested-With: XMLHttpRequest` header on API calls. Set globally in axios config.

---

## Role & Permission System

### Backend: Custom Role/Permission Tables

Roles and permissions use **custom tables**, not Spatie:

| Table | Purpose |
|---|---|
| `roles` | Role definitions (`id`, `name`, `slug`) |
| `permissions` | Permission definitions (`id`, `subject`, `action`) |
| `user_role` | User ↔ Role pivot |
| `user_permissions` | Direct user ↔ permission grants |
| `role_permissions` | Role ↔ Permission pivot |

Roles and permissions are managed in the admin panel under User Management.

**Permission check:** `User::hasPermissionFor($subject, $action)` — loads all permissions once per request (no N+1) via `getAllPermissions()`.

**In controllers:** `PermissionService::authorize($subject, $action)` returns a 403 JSON response or `null` (allow).

**Default roles:**
- `admin` — full CMS access
- `ban` — special system role; having this role means the user is banned
- `user` — standard authenticated user
- `guide` — tour guide (shop tours)

**Permission naming:** separate `subject` and `action` columns.  
Examples: `subject='article', action='add'` | `subject='summit', action='edit'` | `subject='user', action='create_ban'`

### Frontend: CASL

**Location:** `resources/js/services/ability/ability.js`

Abilities are loaded from `/api/auth_user` → `casl_permissions: [{action, subject}]` and set into the CASL `Ability` instance via `updateAbility()`.

**Usage in templates:**

```html
<!-- Show button only if user can edit summits -->
<button v-if="$can('edit', 'summit')">Edit</button>

<!-- Show section only if user can add articles -->
<div v-if="$can('add', 'article')">...</div>
```

**Usage in scripts:**

```javascript
if (this.$ability.can('del', 'product')) { ... }
```

**Global registration in `app.js`:**

```javascript
import { abilitiesPlugin } from '@casl/vue'
import ability from './services/ability/ability'
app.use(abilitiesPlugin, ability, { useGlobalProperties: true })
```

---

## Frontend Auth Flow

### Login sequence

```
User submits login form
        ↓
JSEncrypt encrypts password with RSA public key
        ↓
POST /api/login { email, password: <encrypted> }
        ↓
Server decrypts, validates, creates token
        ↓
localStorage.setItem('x_xsrf_token', token)
CASL abilities loaded from response
Pinia auth_user store updated
        ↓
Router redirects to dashboard
```

### Login / quick sign-up modal (public sites)

`global_components/modals/LoginModalComponent.vue` is mounted by every public root component. It has two tabs — **Log in** and **Sign up** (quick registration: name, surname, email, password + terms) — plus Google / Facebook when configured (`GET login/status`). Open it from anywhere with the bus:

- `this.$bus.$emit('open-login-modal', callback)` — log-in tab;
- `this.$bus.$emit('open-register-modal', { callback, reason })` — opens on the log-in tab (like `open-login-modal`) with a one-line `reason` shown on top ("Log in or create a free account to …").

`callback` runs after a successful login / sign-up (and immediately when the user is already logged in), so features gate themselves like this: condition reports, the partner board, "Share my trip plan", interested events, favourites, back-in-stock alerts.

The RSA library (jsencrypt, its own chunk) and the reCAPTCHA v3 script are **not** loaded on page load: `services/idle.js` `when_idle()` fetches them once the page is idle, and opening the modal loads them right away; submit waits for them if needed.

### Logout sequence

```
User clicks logout
        ↓
POST /api/logout (revokes token server-side)
        ↓
localStorage.clear()
CASL abilities reset
Pinia store cleared
        ↓
Redirect to login page
```

### 401 / 419 Interceptor (in `app.js`)

```javascript
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401 || error.response?.status === 419) {
      localStorage.clear()
      router.push({ name: 'login' })
    }
    return Promise.reject(error)
  }
)
```

### Registration frontend flow

```
User fills RegisterComponent.vue
        ↓
POST /api/register
        ↓
Token stored in localStorage
Verification email sent (queued)
        ↓
User redirected to Verify.vue
        ↓
User clicks email link → GET /api/email/verify/:id/:hash
        ↓
Email verified, full access granted
```

---

[Go back](../README.md)
