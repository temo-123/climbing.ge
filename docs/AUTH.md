# Authentication & Authorization

---

## Table of Contents

- [Registration](#registration)
- [Login](#login)
- [Social OAuth](#social-oauth)
- [Email Verification](#email-verification)
- [Password Reset](#password-reset)
- [Session & Token Management](#session--token-management)
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

`country`, `city`, and `phone_number` are all **required**, not optional — a request missing any of them fails validation. `password` requires `min:8` (not 6). `lang` is optional (`us` or `ka`, defaults to `us`) and sets the user's stored locale preference, used for email language later.

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

### Process

1. Fetches RSA private key from `storage/framework/private.key`
2. Decrypts password using OpenSSL
3. Validates credentials with `Auth::attempt()`
4. Checks `banned` status
5. Creates / returns Sanctum token
6. Sets Sanctum session cookie
7. Returns user data + abilities

### Response

```json
{
  "token": "2|xyz789...",
  "user": {
    "id": 5,
    "name": "John",
    "email": "john@example.com",
    "roles": ["user"],
    "casl_permissions": [{"action":"edit","subject":"article"}, ...]
  }
}
```

### Frontend token storage

```javascript
localStorage.setItem('x_xsrf_token', response.data.token)
```

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

### Flow

1. Frontend calls `GET /api/login/google` → redirected to Google consent
2. Google redirects back to `/api/login/google/callback`
3. `SocialController@Callback`:
   - Finds or creates `User` by email
   - If new user: sends welcome email, assigns default role
   - Creates Sanctum token
   - Returns token to frontend via redirect with query param
4. If user has no password yet (first social login), prompts `create_password`

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

See [SOCIAL_LOGIN_GUIDE.md](SOCIAL_LOGIN_GUIDE.md) for full OAuth app setup instructions.

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
