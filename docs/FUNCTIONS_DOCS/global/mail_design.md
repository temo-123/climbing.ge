# Mail design (all emails)

Every email climbing.ge sends uses one look that matches the site, and one friendly tone. Laravel's mail layout is published to `resources/views/vendor/mail/` and themed with `climbing` (`config/mail.php` → `markdown.theme`), so **any** `->markdown(...)` mail, and also the default `->line()` notification layout, gets it automatically.

## Look

| Part | File | What |
|---|---|---|
| Theme CSS | `vendor/mail/html/themes/climbing.css` | site palette: ink `#15212b`, stone `#56646e`, chalk `#f5f3ee` page, line `#e3ded4`, guide blue `#1f93b0`, shop green `#18a06f`, hold yellow `#ffc233`, footer `#484848`. Manrope body, Barlow Condensed `h1`, uppercase `h2` section labels, round pill buttons, chalk panels with a blue left edge. Laravel inlines it into each mail. |
| Header | `vendor/mail/html/header.blade.php` | dark granite band, white logo, "CLIMBING IN GEORGIA", then the blue / yellow "rope" stripe (70 / 30), like the site navbar + section titles |
| Body | `vendor/mail/html/layout.blade.php` | white rounded card, 600 px wide (100 % on phones), Google Fonts link (Apple Mail etc. show it; others fall back to system fonts) |
| Footer | `vendor/mail/html/footer.blade.php` | **the site's mountain ridge** over the `#484848` footer band, the logo, links (Guidebook • Shop • Blog • Summits, built from `APP_SSH` + `*_URL`), a note ("You're getting this email because…" — set in `message.blade.php`), © line |

Buttons: `['color' => 'primary']` (blue, default), `success` (green), `error` (red), `dark`.

### Images

Mail clients (Gmail, Outlook) don't show SVG, so the footer uses PNGs made from the site files, served from `config('app.url')`:

- `public/images/email/mountains.png`: 2400×340, a transparent 2× render of `public/images/svg/mountains.svg` repeated like the site footer (`background-size: 800px 170px`)
- `public/images/email/logo-white.png`: the header logo (`images/site_img/site_logo/header logo.png`) inverted to white, 2×

To rebuild them, render the same HTML in headless Chromium with `deviceScaleFactor: 2` and `omitBackground: true`.

## Tone

Every mail follows the same pattern:
- a friendly title with one emoji
- a greeting by name when the mail has it
- short plain sentences
- what happens next / what to do, with one clear button
- a small grey "didn't do this? ignore it" line on confirmation mails
- the sign-off "The climbing.ge team"

Subjects follow the same style.

| Mail | Template | Subject |
|---|---|---|
| Welcome | `emails/userWelcomeMessage` | Welcome to climbing.ge! 🎉 |
| Confirm account email | `emails/auth/verify_email` (`App\Notifications\VerifyEmail::toMail`) | Please confirm your email ✉️ |
| Password reset | `emails/auth/reset_password` (`ResetPassword::toMailUsing` in `AppServiceProvider`) | Reset your climbing.ge password 🔑 |
| Comment: confirm email | `emails/comment/comment_email_varification` | One click to publish your comment ✍️ |
| Comment: reply | `emails/comment/comment_answer_notificatione` | 💬 Someone replied to your comment |
| Comment: hidden | `emails/comment/hide_comment_notification` | Your comment was hidden |
| Comment: report decision | `emails/comment/comment_complaint_decision_to_decisione_notification` | We looked at your report ✅ |
| Comment reported (admin) | `emails/comment/admin_complaint_notification` | 🚩 A comment was reported |
| Order: confirm | `emails/order/order_confirm` | Please confirm your order 🛒 |
| Order: status | `emails/order/order_status_notification` | Your order #N has an update 📦 |
| New order (admin) | `emails/order/admin_order_decloration_notification` | 🛒 New order from <name> |
| Custom product: buyer | `emails/order/castom_prodaction/castom_prodactio_completed` | Thanks for your request! 🙌 |
| Custom product: seller | `emails/order/castom_prodaction/seler_castam_prodaction` | 📦 New order request: <product> |
| Role / permission change | `emails/permission/reservation_completed` | Your access on climbing.ge was updated 🔑 |
| Tasks | `emails/task/task` | New task for you 📋 / Your task is done ✅ |
| Content notifications, back in stock | `emails/user_notifications/article_event_notification` | (set by the sender) |
| Following a service | `emails/FollowingNotificationMessage` | (set by the sender) |
| Contact form message | `emails/message` | (set by the sender) |
| Tour reservations (4 mails) | `emails/tour/*` | see [shop/tour_reservation_verification.md](../shop/tour_reservation_verification.md) |
| Tour guide switch (3 mails) | `emails/tour/guide_assigned`, `guide_unassigned`, `guide_changed_client` | 🧭 A reservation is now yours… / Reservation #N moved to another guide / Meet your new guide… 🧭 — [shop/tour_guides.md](../shop/tour_guides.md) |

Removed as unused: `emails/UsersAndFollowersNotificationsMessage` and `emails/tour/reservation_completed`. The contact form mail (`emails/message`) used to be indented, so it showed as a raw code block; it's fixed.

## Adding a mail

Use `->markdown('emails/...')` with `@component('mail::message')` and follow the tone above. Don't indent lines inside the template (Markdown turns indented lines into code blocks), and use `@component('mail::panel')` for a facts box. The header, footer and theme come for free.
