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
  "email": "john@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

### Process

1. Validates name, surname, email (unique), password (confirmed, min 6)
2. Creates `User` record with hashed password
3. Assigns default role (e.g. `user`)
4. Creates `UserNotification` preference record
5. Sends verification email (queued)
6. Returns Sanctum token + user data

### Response

```json
{
  "token": "1|abc123...",
  "user": { "id": 1, "name": "John", "email": "john@example.com", ... }
}
```

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
FACEBOOK_CLIENT_ID=...
FACEBOOK_CLIENT_SECRET=...
FACEBOOK_REDIRECT_URL=https://climbing.ge/api/login/facebook/callback

GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URL=https://climbing.ge/api/login/google/callback
```

---

## Email Verification

**Controller:** `App\Http\Controllers\Auth\VerificationController`

| Method | Path | Description |
|---|---|---|
| GET | `/api/email/verify/{user_id}/{hash}` | Verify email link |
| GET | `/api/email/resend` | Resend verification email (auth required) |

The `User` model implements `MustVerifyEmail`. A signed URL is emailed after registration. Clicking it sets `email_verified_at`.

### Custom Notification

`App\Notifications\VerifyEmail` — overrides default Laravel verification email with custom branding and bilingual (EN/KA) content.

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
