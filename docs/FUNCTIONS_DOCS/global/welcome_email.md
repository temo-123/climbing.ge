# Welcome email

Every new account gets **one** friendly welcome mail, sent the moment its e-mail address is confirmed. It points the climber at three first steps: finish the profile, find a crag, log a first ascent. These are the same steps as the dashboard's profile checklist.

## When it is sent

| Sign-up path | Sent when | Code |
|---|---|---|
| E-mail + password (`POST /api/register`, sign-up modal, mobile app) | The user clicks the verification link (`GET /api/email/verify/{id}/{hash}`), the first time only. Clicking the link again ("already verified") doesn't send another | `VerificationController::verify()` → `send_welcome()` |
| New Google / Facebook account (web callback, native-token and browser mobile flows) | Right after the account is created. The provider already confirmed the address | `SocialController::login_or_register()`, after the `DB::transaction` |
| Existing account logging in with Google / Facebook | Never: not a new account | — |
| Admin "verify e-mail" in the users table | Never: an admin action, not the user's own first step | — |

It isn't sent at sign-up time for e-mail accounts, because the verification mail goes out then and two mails at once bury the important one.

## Rules

- **Queued** on the `emails` queue (`WelcomeEmailNotification implements ShouldQueue`, `onQueue('emails')`), the same queue the worker listens on ([BACKEND/QUEUE_AND_SCHEDULER_UBUNTU.md](../../BACKEND/QUEUE_AND_SCHEDULER_UBUNTU.md)). Without a running worker it waits in `jobs`.
- **Never blocks** verification or sign-up: the `notify()` call is wrapped in `try/catch` + `report()`.
- **Banned users** get nothing: the global mail guard drops it ([mail_design.md](mail_design.md#delivery-guard-appservicesmailguardservicephp)).
- URLs come from `config('app.*')`, never `env()`, because `env()` is `null` after `config:cache`.

## Content

Template `resources/views/emails/userWelcomeMessage.blade.php`, subject "Welcome to climbing.ge! 🎉", `climbing` mail theme:

1. "Hi {name}," (left out when the name is empty)
2. Three numbered first steps: finish your profile · find your next crag (link to `/outdoor`) · log your first ascent (link to `/climbers`)
3. Green button **Finish my profile** → user page home, where the profile checklist sits
4. "Questions or ideas? Just reply" + sign-off

## Tests

`tests/Feature/WelcomeEmailTest.php`: verification sends it once, a new social account gets it and a returning one doesn't, it's queued on `emails` and renders.
