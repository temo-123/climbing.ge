# Login & sign-up modal, login-gated actions

## Summary

One modal, available on every public site, lets a visitor **log in or create an account without leaving the page**. It then finishes whatever they were trying to do (report conditions, post a partner plan, heart an article…). It is the single doorway between "guest" and "climber" for every function that needs an account.

## At a glance

| | |
|---|---|
| Where | Every public site (guide, shop, blog, summit, films), mounted once in each root component |
| Who | Guests (logged-in users just get their callback run straight away) |
| Opened by | Any component, through the event bus |
| Files | `global_components/modals/LoginModalComponent.vue`, `services/idle.js`; backend `Auth\LoginController`, `Auth\RegisterController`, `Auth\SocialController` |
| Technical reference | [AUTH.md](../../AUTH.md) |

## Why it exists

Most community functions need to know who is acting: reports show who sent them, and partner posts need a contact. Sending guests to `user.climbing.ge/login` used to lose them, because of the new tab and lost context, and they rarely came back. A modal keeps them on the page and resumes the action.

## How it works

**Opening it (developers):**
- `this.$bus.$emit('open-login-modal', callback)`
- `this.$bus.$emit('open-register-modal', { callback, reason })` shows a one-line `reason` on top.
- Both open on the **Log in** tab, the default for every entry point. New users switch to *Sign up* with one click.
- `callback` runs after a successful login or sign-up, and **immediately** if the user is already logged in, so a feature can always just call it.

**Guest flow (example: reporting conditions):**
1. The guest presses "Report conditions".
2. The modal opens on *Log in* with the reason "Log in or create a free account to report conditions — reports show who sent them."
3. They log in (email + password, or Google / Facebook when configured), or switch to *Sign up*.
4. The modal closes and the report form opens where they were.

**Sign-up:**
1. The same fields as the full sign-up page: name, surname, email, country, city, phone number, password (with a strength bar) + confirm password ("Passwords do not match" under it), and one checkbox for Terms + Privacy (links to the legal pages).
2. **Create account** stays disabled until every field is filled and the passwords match.
3. Country, city and phone are required in the modal, but still `nullable` in `RegisterController`, because social sign-up doesn't send them. The dashboard's profile checklist asks social users for them later.
4. reCAPTCHA v3 runs invisibly when configured.
5. After sign-up the user is logged in, and on the public sites the welcome questions open next.

**Login:** the browser encrypts the password with the server's RSA public key (jsencrypt) before sending it. The server decrypts it, checks it and returns a Bearer token, which is stored in `localStorage`.

## Design concept

- **Modal, not page:** keeps the context (the article, the half-filled idea) visible behind it.
- **Two tabs, log in first:** "Log in" / "Sign up" at the top. Most visitors who hit a gated action already have an account, and new climbers are one click away.
- **Reason line:** a short sentence explaining *why* an account is needed right now. It turns a wall into an invitation.
- **Complete profile up front:** the modal asks for the same details as the full sign-up page, so new accounts can order or book straight away.
- **Social buttons only when they work:** Google / Facebook buttons appear only when the provider is configured (`GET login/status`), so there are never dead buttons.
- **Fast pages:** the RSA library and reCAPTCHA script aren't loaded on page load. They're fetched when the browser is idle (`when_idle()`), or immediately when the modal opens.

## States

| State | What the user sees |
|---|---|
| Loading assets | The modal opens instantly; submit waits for the encryption script if it isn't there yet |
| Wrong password / validation | Inline red message under the form, fields kept |
| Passwords don't match | "Passwords do not match." under the confirmation, button disabled |
| reCAPTCHA failure | Inline message; the button isn't stuck (8 s timeout) |
| Success | Modal closes, the callback runs (e.g. the report form opens) |
| Already logged in | The callback runs immediately; the modal never shows |
