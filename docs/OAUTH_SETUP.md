# OAuth Setup — Google, Facebook, Strava

Step-by-step guide for creating the OAuth apps a fresh install needs and wiring them into `.env`.

| Provider | Used for | Code | Shown to users when |
|---|---|---|---|
| Google | Sign in / sign up | `Auth\SocialController` (Laravel Socialite) | `GET /api/login/status` → `google: true` |
| Facebook | Sign in / sign up | `Auth\SocialController` (Laravel Socialite) | `GET /api/login/status` → `facebook: true` |
| Strava | Import an activity as a summit ascent's GPS track | `App\Services\StravaService` (plain HTTP) | `GET /api/strava/status` → `configured: true` |

All three are optional. Unconfigured providers are hidden in the UI — login buttons disappear, the Strava import shows "isn't set up on this site yet".

How the login flow itself works (new vs existing user, create-password step, bans) is in [AUTH.md → Social OAuth](AUTH.md#social-oauth). Strava's feature behaviour is in [SUMMIT.md → GPS tracks](SUMMIT.md#gps-tracks-gpx--strava).

---

## Table of Contents

- [Before you start](#before-you-start)
- [Google](#google)
- [Facebook](#facebook)
- [Strava](#strava)
- [Apply and verify](#apply-and-verify)
- [Troubleshooting](#troubleshooting)
- [Reference](#reference)

---

## Before you start

**Redirect URLs differ per provider** — get them exactly right, scheme and trailing path included:

| Provider | Redirect / callback URL (production) | Local dev example | Lands on |
|---|---|---|---|
| Google | `https://user.climbing.ge/login/google/callback` | `http://user.climbing.loc/login/google/callback` | Vue route → `CallbackComponent.vue` |
| Facebook | `https://user.climbing.ge/login/facebook/callback` | `http://user.climbing.loc/login/facebook/callback` | Vue route → `CallbackComponent.vue` |
| Strava | `https://user.climbing.ge/api/strava/callback` | `http://user.climbing.loc/api/strava/callback` | API route → `StravaCallbackController` |

Google/Facebook redirect to a **frontend** page (no `/api/`), which then calls the API with the `code`. Strava redirects straight to the **API** route, which redirects on to `/my_ascents`.

You'll also need public URLs for the consent screens:

- Privacy policy: `https://climbing.ge/privacy_policy`
- Terms of use: `https://climbing.ge/terms_of_use`
- Account / data deletion: `https://user.climbing.ge/delete-account`
- Logo: square PNG (at least 120×120 for Google, 1024×1024 for Facebook)

Check that `APP_SSH` (`https://` in production) and `USER_PAGE_URL` are correct in `.env` — the Strava default redirect is built from them.

---

## Google

Console: [console.cloud.google.com](https://console.cloud.google.com)

### 1. Project

Pick an existing project (e.g. the one used for reCAPTCHA / Analytics) or **New project** → name it `climbing.ge`.

### 2. OAuth consent screen (Google Auth Platform)

**APIs & Services → OAuth consent screen** (shown as **Google Auth Platform → Branding / Audience / Data access** in the new console):

1. **User type / Audience:** External.
2. **Branding:** app name `climbing.ge`, support email, logo, home page `https://climbing.ge`, privacy policy and terms URLs from above.
3. **Authorized domains:** `climbing.ge`.
4. **Data access / Scopes:** add `openid`, `.../auth/userinfo.email`, `.../auth/userinfo.profile` — that's what Socialite requests. These are non-sensitive scopes, so no Google security review is needed.
5. **Publish app** (Audience → *Publishing status: In production*). While it stays in *Testing*, only the listed test users can sign in and everyone else gets `access_denied`.

### 3. OAuth client

**APIs & Services → Credentials → Create credentials → OAuth client ID**:

| Field | Value |
|---|---|
| Application type | Web application |
| Name | `climbing.ge web` |
| Authorized JavaScript origins | not needed (redirect flow) — optional `https://user.climbing.ge` |
| Authorized redirect URIs | `https://user.climbing.ge/login/google/callback` (add the `.loc` one too for dev, or use a separate client for dev) |

Copy the **Client ID** (`…apps.googleusercontent.com`) and **Client secret** (`GOCSPX-…`).

### 4. `.env`

```env
GOOGLE_CLIENT_ID=xxxxxxxxxxxx-xxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxxxxxxxxxx
GOOGLE_URL=https://user.climbing.ge/login/google/callback
```

`GOOGLE_URL` must match one of the authorized redirect URIs **character for character**.

> Not to be confused with `GOOGLE_CAPTCHA_V3_*` (reCAPTCHA) and `GOOGLE_SITE_VERIFICATION*` (Search Console) — separate keys from separate Google products.

---

## Facebook

Console: [developers.facebook.com/apps](https://developers.facebook.com/apps)

### 1. Create the app

1. **Create app** → use case **Authenticate and request data from users with Facebook Login** (older UI: type *Consumer*).
2. App name `climbing.ge`, contact email; link a Business portfolio if you have one (needed later only for Business verification, not for basic login).

### 2. Basic settings

**App settings → Basic**:

| Field | Value |
|---|---|
| App domains | `climbing.ge`, `user.climbing.ge` |
| Privacy policy URL | `https://climbing.ge/privacy_policy` |
| Terms of service URL | `https://climbing.ge/terms_of_use` |
| User data deletion | *Data deletion instructions URL* → `https://user.climbing.ge/delete-account` |
| App icon | 1024×1024 logo |
| Category | e.g. *Sports* / *Lifestyle* |

**+ Add platform → Website** → Site URL `https://user.climbing.ge/`.

Copy **App ID** and **App secret** (click *Show*).

### 3. Facebook Login settings

**Use cases → Authentication and account creation → Customize** (older UI: **Facebook Login → Settings**):

1. Permissions: make sure `email` and `public_profile` are added — Socialite asks for both. The callback refuses accounts with no email (`422 No email returned from provider`).
2. **Valid OAuth Redirect URIs:** `https://user.climbing.ge/login/facebook/callback` (plus the `.loc` URL if you test locally — FB accepts `http://` only for `localhost`, so test locally with a separate dev app or a tunnel).
3. Leave **Client OAuth login** and **Web OAuth login** on; **Enforce HTTPS** on.

### 4. Go live

Switch the app from **Development** to **Live** (top bar / *Publish*). In Development mode only people with a role on the app (admins, developers, testers) can log in. `email` + `public_profile` are standard permissions, so going live doesn't need App Review — only the basic settings above have to be complete.

### 5. `.env`

```env
FACEBOOK_CLIENT_ID=123456789012345
FACEBOOK_CLIENT_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
FACEBOOK_URL=https://user.climbing.ge/login/facebook/callback
```

---

## Strava

Console: [strava.com/settings/api](https://www.strava.com/settings/api) (log in with the Strava account that should own the app)

### 1. Create the API application

| Field | Value |
|---|---|
| Application name | `climbing.ge` |
| Category | e.g. *Training* / *Visualizer* |
| Website | `https://climbing.ge` |
| Application description | "Import a Strava activity as the GPS track of a summit ascent on climbing.ge" |
| **Authorization Callback Domain** | `user.climbing.ge` — host only, no scheme, no path (`localhost` / `user.climbing.loc` for dev) |

Upload an app icon, accept the API agreement, then copy **Client ID** (number) and **Client Secret**.

Strava allows **one callback domain per app** — use a separate Strava app for local development.

### 2. `.env`

```env
STRAVA_CLIENT_ID=123456
STRAVA_CLIENT_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
# Optional — default is <APP_SSH><USER_PAGE_URL>/api/strava/callback
# STRAVA_REDIRECT_URI=https://user.climbing.ge/api/strava/callback
```

`StravaService::configured()` needs only the client ID and secret. Set `STRAVA_REDIRECT_URI` only if the default built from `APP_SSH` + `USER_PAGE_URL` is wrong; its host must equal the Authorization Callback Domain.

### 3. Database

Connections are stored in `strava_connections` (access + refresh tokens per user). On a fresh install:

```bash
php artisan migrate   # 2026_10_28_120100_create_strava_connections_table
```

### 4. Athlete capacity and rules

- A **new Strava app is limited to a single athlete** (the owner) until Strava approves it. Before announcing the feature, submit the app for review via Strava's developer program form (linked on the API settings page) to raise the athlete capacity.
- Rate limits are per app (shown on the API settings page — by default a few hundred requests per 15 min and a few thousand per day). The app returns `429 strava_rate_limit` to the user when hit.
- **Strava API terms:** a user's Strava data may be shown only to that user. The import therefore keeps only derived numbers (verified, distance, gain…), never the track points — keep it that way.
- Scope requested: `read,activity:read_all`. If the user unticks "View data about your private activities", the callback rejects it (`?strava=scope`).

---

## Apply and verify

1. Reload config:

   ```bash
   php artisan config:clear      # dev
   php artisan config:cache      # production (re-run after every .env change)
   ```

2. Check the flags:

   ```bash
   curl -s https://user.climbing.ge/api/login/status
   # {"google":true,"facebook":true}
   ```

   A provider counts as configured when `client_id`, `client_secret` and the redirect URL are all non-empty (and not the placeholder `...`).

3. **Google / Facebook:** log out → open the login modal → the provider buttons are visible → sign in with an account that has **no** climbing.ge account yet → you're asked to create a password → then log out and sign in again with the same provider → you're logged straight in.
4. **Strava:** log in → user panel → My Ascents → an ascent → *Import from Strava* → *Connect with Strava* → approve → you're back on `/my_ascents?strava=connected` and the activity picker lists activities within ±3 days of the ascent date. `GET /api/strava/status` now shows `connected: true` with the athlete name.

---

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| Login button not shown | One of the three `GOOGLE_*` / `FACEBOOK_*` values is empty, or config is cached — `config:clear` / `config:cache`. |
| Google: `Error 400: redirect_uri_mismatch` | `GOOGLE_URL` ≠ authorized redirect URI (check `http` vs `https`, `www`, trailing slash). |
| Google: `access_denied` / "app has not completed verification" | Consent screen still in *Testing* — publish it, or add the account as a test user. |
| Facebook: "URL blocked" / "Can't load URL" | Redirect URI missing from *Valid OAuth Redirect URIs*, or the domain missing from *App domains*. |
| Facebook: "App not active" / only you can log in | App still in Development mode — switch to Live. |
| `422 No email returned from provider` | The Facebook account has no confirmed email, or the user declined the email permission. Ask them to re-login and allow it, or register normally. |
| `422 Social login failed: could not retrieve user` | Code expired/reused (page refreshed on the callback), or wrong client secret. Start the login again; check the secret. |
| Strava: `?strava=error` | Wrong client ID/secret, `state` expired (link older than 30 min), or the redirect host ≠ Authorization Callback Domain. |
| Strava: `?strava=denied` | User pressed *Cancel* on Strava. |
| Strava: `?strava=scope` | User unticked private-activities access — reconnect and keep it ticked. |
| Strava: "Limit of connected athletes exceeded" | App is still at the single-athlete limit — request more capacity (see above). |

---

## Reference

**Config** (`config/services.php`):

```php
'google'   => ['client_id' => env('GOOGLE_CLIENT_ID'),   'client_secret' => env('GOOGLE_CLIENT_SECRET'),   'redirect' => env('GOOGLE_URL')],
'facebook' => ['client_id' => env('FACEBOOK_CLIENT_ID'), 'client_secret' => env('FACEBOOK_CLIENT_SECRET'), 'redirect' => env('FACEBOOK_URL')],
'strava'   => ['client_id' => env('STRAVA_CLIENT_ID'),   'client_secret' => env('STRAVA_CLIENT_SECRET'),   'redirect' => env('STRAVA_REDIRECT_URI')],
```

**Endpoints:**

| Method | Path | Auth | Purpose |
|---|---|---|---|
| GET | `/api/login/status` | — | Which social providers are configured |
| GET | `/api/login/{provider}` | — | Returns `{url}` of the provider's consent screen (Socialite `stateless()`) |
| GET | `/api/login/{provider}/callback` | — | Exchanges `code`; returns `{status:'login', token}` or `{status:'registratione', new_user_email}` |
| POST | `/api/login/social/create_password/{email}` | — | Sets the password after a first social sign-up |
| GET | `/api/strava/status` | sanctum | `configured`, `connected`, `athlete_name` |
| GET | `/api/strava/connect_url` | sanctum | Strava consent URL (encrypted `state` = user id + time, valid 30 min) |
| GET | `/api/strava/callback` | — (`throttle:20,1`) | Strava's return; stores tokens, redirects to `/my_ascents?strava=…` |
| GET | `/api/strava/activities?date=` | sanctum | Activities within ±3 days |
| DELETE | `/api/strava/disconnect` | sanctum | Deauthorizes on Strava and deletes the local connection |

**Frontend:** login buttons in `auth/LoginComponent.vue` and `global_components/modals/LoginModalComponent.vue` (both read `/api/login/status`); callback page `auth/social/CallbackComponent.vue` on route `login/:provaider/callback` (`UserRoutes.js`). Social login leaves the page, so it can't resume an in-page action (e.g. the donation modal).

**Tables:** `social_accounts` (provider + provider user id → user), `strava_connections` (one row per user; tokens refreshed automatically 2 min before expiry).
