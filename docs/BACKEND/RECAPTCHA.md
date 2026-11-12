# reCAPTCHA v3

Every public form checks a reCAPTCHA v3 token on the server: login, register, quick sign-up, comments, product reviews, route / MTP reviews and ascents, summit ascents, tour reservations, contact message, follow. Service: `App\Services\ReCaptchaV3Service`.

## Keys (.env)

| Key | Use |
|---|---|
| `GOOGLE_CAPTCHA_V3_SITE_KEY` → `MIX_GOOGLE_CAPTCHA_V3_SITE_KEY` | browser (`api.js?render=`); **built into the JS bundle** — restart `npm run watch` / rebuild after a change |
| `GOOGLE_CAPTCHA_V3_SECRET_KEY` | server check, score ≥ 0.5 |
| `GOOGLE_CAPTCHA_V3_MOBILE_SITE_KEY` / `_MOBILE_SECRET_KEY` | mobile app WebView key, score ≥ 0.3 (`verifySmart()` tries web, then mobile) |
| `RECAPTCHA_FAIL_OPEN` (default `true`) | see below |

The key's domain list in https://www.google.com/recaptcha/admin must contain `climbing.ge` (covers every subdomain) and, for local work, `climbing.loc`. After a key change: `php artisan config:clear`.

## Broken key = fail-open (Nov 2026)

In Nov 2026 Google deleted the project behind the web key (`siteverify` → `"Project #… has been deleted."`), and every form on the site failed for every visitor.

Now `isConfigured()` is `false` while the **secret itself** is unusable:
- `secretIsBroken()` sends a dummy token to `siteverify`, cached 10 min (`recaptcha_secret_health:*`). A working key answers `invalid-input-response`; any other code (`invalid-input-secret`, deleted project…) means broken. Network errors count as "unknown", so normal checks keep running.
- While it is broken, the checks are skipped and a `CRITICAL` log line is written at most once an hour ("reCAPTCHA web secret rejected by Google…"). Protection comes back by itself within 10 minutes of fixing `.env`.
- `RECAPTCHA_FAIL_OPEN=false` keeps the old fail-closed behaviour. Tests never call Google.

## Browser

Forms never refuse to submit only because they couldn't get a token (ad-blocker, broken key, script not loaded). They send `recaptcha_token: null`, and the server decides: a missing or low-score token is still refused with 422 while the key works, and the form then shows its reCAPTCHA notice. `FormCapchaComponent` emits `recaptcha-verified` with `null` in that case.
