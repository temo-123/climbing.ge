# Age restriction: accounts under 13

Anyone can **register** — the date of birth (required on sign-up) is stored as entered. But a user **younger than 13** (`User::MIN_AGE`) by `users.birthday` gets **no account functions**: after login they only see an alert about the age limit. They can still browse the public sites like a guest, log out, reset the password and delete the account.

A missing birthday (old accounts) is **not** treated as underage — Options keeps asking them to add it. Setting a birthday under 13 later in Options locks the account the same way.

## Who is underage

`User::isUnderage()` — `Carbon::parse(birthday)->age < 13` (full years; the 13th birthday unlocks the account that day). The flag is computed on every request, so the lock lifts by itself on the birthday.

## Server

- **Gate** — `app/Http/Middleware/EnsureMinimumAgeForActions.php`, in the `api` middleware group **before** `EnsureEmailVerifiedForActions` (an underage, unverified user gets the age answer, not the e-mail one). Same closed-by-default design as the e-mail gate: every `POST` / `PUT` / `PATCH` / `DELETE` under `api/` is refused for an underage user unless its URI is in `ALWAYS_OPEN`. `GET` is never gated. Guests (no token) pass. User read via `auth('sanctum')`.
- **`ALWAYS_OPEN`** (much shorter than the e-mail gate's): login / register / password reset, logout, `account/delete`, read-style POSTs (searches, similar articles, category counts, view tracking) and the contact message (so a wrong birthday can be reported). **Options is closed** — an underage user can't edit their own birthday; an admin corrects it in Manage user › Profile.
- **Refusal:** `403 { "code": "age_restricted", "min_age": 13, "message": "...", "alert": {...} }` — mobile apps key on `code`.
- **Permissions** — `auth_user` returns `casl_permissions: []`, `is_underage: true`, `min_age: 13`; `get_user/get_auth_user_permissions` returns `[]`. Login response also has `is_underage`.

`tests/Feature/AgeRestrictionGateTest.php` checks the age rule, refused / open samples, empty permissions and that every `ALWAYS_OPEN` entry still matches a route.

## User page (user.climbing.ge)

The guard in `app.js` reads `is_underage` with the auth user. Underage → every route except `age_restricted`, `delete_account`, `forget_pass`, the legal pages and the error pages redirects to **`/age_restricted`** (`user/pages/AgeRestrictedPage.vue`); this check runs before the e-mail-verification one. Non-underage users opening `/age_restricted` go home.

`HomeComponent` hides the navbar (menus, bell, user card) and the welcome questions on this route, so the page shows **only** the alert: user-times icon, "climbing.ge accounts are for ages 13+", a warning alert explaining that functions open from 13 and that browsing as a guest still works, a "wrong date of birth? contact us" hint, **Log out** and **Delete account**.

## Public sites

`global_components/AgeRestrictionNoticeComponent.vue` (rendered by the root in `app.js` next to the e-mail notice, on every subdomain):

- **Modal** right after login (`logged-in` bus event) when the user is underage, and whenever a request is refused with `age_restricted` (`bootstrap.js` emits `age-restricted`, localizes `data.message` to `global.age_restriction.inline`, and sets `handled_age_restricted` + `handled_email_not_verified` so components skip their own error alert).
- **Bar** at the bottom (same look as the e-mail bar) while logged in and underage; × hides it for the browser session (`sessionStorage.cg_age_bar_hidden`). Not shown on `/age_restricted`.
- The e-mail-verification bar is suppressed for underage users.

Strings: `global.age_restriction.*`, `user.meta.titles.age_restricted` (en + ka).
