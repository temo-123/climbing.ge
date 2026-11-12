# E-mail verification: what an unverified account can do

A logged-in user whose e-mail isn't verified (`users.email_verified_at IS NULL`) can **browse** the public sites and **manage their account**, but **every function is closed** until the address is confirmed: on user.climbing.ge they only get the "Verify your e-mail" page, and the API refuses every write (comments, reviews, ascents, follows, favorites, reservations, orders, the whole panel and admin CMS). Public sites show a reminder bar, and a refused action opens a modal with a "send new verification e-mail" button.

Technical reference: [docs/AUTH.md › Email Verification](../../AUTH.md#email-verification).

## Who is unverified

- Registered with e-mail + password and hasn't clicked the link yet (`POST /api/register` sends it).
- Changed their e-mail — in Options (`get_options/user_info_update`) or by an admin (`set_user/update_user_info`). The new address gets a fresh link right away; the old link stops working because the link hash includes the e-mail.
- **Not** social-login users: Google / Facebook prove the address, so new social accounts are created verified, and an *existing* unverified account that logs in with Google / Facebook is marked verified at that moment.

## Server gate (`EnsureEmailVerifiedForActions`) — closed by default

`app/Http/Middleware/EnsureEmailVerifiedForActions.php`, registered in the `api` middleware group (`app/Http/Kernel.php`), so it covers `auth:sanctum` routes, the admin files and dual-flow (guest-or-user) routes alike.

- Every `POST` / `PUT` / `PATCH` / `DELETE` under `api/` is refused for an unverified user **unless** its URI is in `ALWAYS_OPEN` (and not in `NEVER_OPEN`). `GET` is never gated — reading stays open, which the public sites and the panel's own pages need.
- A new write endpoint is therefore **closed automatically**; add it to `ALWAYS_OPEN` only if an unverified user really needs it.
- The user is read with `auth('sanctum')` (the default guard doesn't see Bearer tokens on public routes). A request **without** a token (guest flow) passes: guests have their own e-mail confirmation per comment / feedback / reservation.
- Refusal: `403` with
  ```json
  { "code": "email_not_verified", "email": "user@example.com", "message": "...", "alert": { "type": "warning", "title": "...", "message": "...", "icon": "envelope" } }
  ```
  The mobile apps can key on `code === 'email_not_verified'`.

**`ALWAYS_OPEN`** (see the constant for exact URIs):
- auth and account — login / register / password reset, logout, delete account, Options (`get_options/*`: profile, e-mail change, notifications, preferences), password, avatar, addresses, own profile links, Strava disconnect;
- read-style POSTs — every search, similar-article / category counts, view tracking, checkout price quote, sale-code check, wall-calculator PDF, canvas image;
- cart (checkout itself is closed);
- removing / hiding your own things — unfollow, favorites removal, back-in-stock unsubscribe, delete own ascents and tracks, hide / show / delete own comments and reviews, delete own area report;
- contact message (support must stay reachable), donations, payment callbacks, guest e-mail-confirmation links, tour reservation status;
- the training-app sync (`user_training/sync` — blocking it would lose workouts logged offline in the app).

`NEVER_OPEN` re-closes GPX / Strava track uploads, which the `summit_track/*` wildcard would otherwise open.

`tests/Feature/EmailVerificationGateTest.php` checks closed and open samples and fails if a list entry no longer matches any route (renamed URI).

## User page (user.climbing.ge)

The route guard in `app.js` reads `email_verified_at` with the auth user. Unverified → every route except `verify_required`, `verify`, `options`, `delete_account`, `forget_pass`, the legal pages and the error pages redirects to **`/verify_email`** (`user/pages/VerifyRequiredPage.vue`). Verified users opening `/verify_email` go home.

The page: envelope icon, "Verify your e-mail to continue", the address, three steps (open the mail, click the link, check spam), **Send new verification e-mail** (60 s cooldown), **I've verified — continue** (re-reads the user — for a link opened on the phone; says "not verified yet" otherwise), and below **Change e-mail** (→ Options) and **Log out**. The guard re-reads the user after `email-verified`, `logged-in` (also emitted by Options after an e-mail change) and `logged-out`.

## Frontend

- **Reminder bar** — `global_components/EmailVerifyNoticeComponent.vue`, rendered by the root in `app.js` next to the image viewer, so it's on every subdomain. Shown while a token exists and `auth_user` has no `email_verified_at`. Dark floating bar at the bottom (centered, max 760 px, 16 px gutter on phones) with "Send new verification e-mail" and ×. × hides it for the browser session (`sessionStorage.cg_verify_bar_hidden`). Hidden on the `/email/verify/...` and `/verify_email` pages. Re-checks on `logged-in` / `permissions-loaded` bus events and when the token changes between routes.
- **Modal** — opened when any request is refused with `email_not_verified`: `bootstrap.js` emits `email-not-verified` on the bus instead of the generic "no permission" toast, replaces `data.message` with the localized `global.email_verify.inline` text (forms that print the server message inline show it in the user's language), and flags the error `handled_email_not_verified` so components can skip their own error alert. The modal shows the address, a hint to check spam, **Change e-mail** (link to `user.climbing.ge/options`) and **Send new verification e-mail**.
- **Resend** — `GET email/resend`; after a send the button counts down 60 s before it can be clicked again. "Already verified" answer closes the bar.
- **Verify page** (`auth/Verify.vue`) emits `email-verified` and clears the cached auth user, so the bar disappears at once.
- **Options page** — `user_info_update` sends the new link right away and returns `verification_sent` (`true`, or `false` when the mail server refused it → red "couldn't send" toast instead of the "new link sent" one). While the account is unverified, the Profile tab shows a red **Confirm your e-mail** alert with the address and **Send new verification e-mail** (`GET email/resend`, 60 s cooldown, starts right after a change). An e-mail already used by another account is shown under the e-mail field (`422 errors.email`).
- In the user panel an unverified e-mail is also one of the account alerts on the sidebar user card and the top-bar bell (`services/menuBadges.js` → `account_alerts()`).

Strings: `global.email_verify.*`, `user.profile.email_changed_verify`, `user.profile.verify_title` / `verify_text` (en + ka).

## Admin: verify or resend for a user

Both in the **Manage user** modal › Profile tab and in the **User info** modal header (next to the "Not verified" badge), with `user › edit`:

- **Verify User** — asks for confirmation, then `POST set_user/verify_user/{id}` sets `email_verified_at = now()`. The table and badges refresh.
- **Resend verification e-mail** — `POST set_user/resend_verification/{id}` sends the user a new link (answers "already verified" without sending when the user is verified already).

See [user/admin_user_manage.md](../user/admin_user_manage.md) and [user/admin_user_info.md](../user/admin_user_info.md).
