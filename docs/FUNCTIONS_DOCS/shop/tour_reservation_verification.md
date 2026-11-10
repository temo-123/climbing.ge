# Tour reservation: email confirmation & admin notice

Every tour reservation has to be confirmed by the person who made it, from a link mailed to the reservation email (like order confirmation). Until then it is a *declaration*: the guide gets nothing, and the admin table shows it yellow. The site address (`sites.email` = info@climbing.ge) gets a "New tour reservation" mail as soon as it is made, like a new order.

**Code:** `app/Services/TourReservationService.php` (the whole flow), `Api/Shop/UserTourReservationController.php` (public), `Api/User/Admin/Shop/TourReservationController.php` (admin), shop page `components/shop/pages/pages/verivficatione_pages/TourReservationVerificationePage.vue`, form `components/shop/items/reservation_forms/TourMessageFormComponent.vue`, admin `user/pages/tour/list/for_admin/AllToursReservationsPage.vue` + `modal/TourReservationDetailsModal.vue`.

## Flow

```
tour page form ──POST create_reservation──► row (verificate = 0)
                                             ├─ mail to reserver: "Please confirm your tour reservation" + button
                                             └─ mail to sites.email: "New tour reservation: <tour>" (says "not confirmed yet")
reserver opens  shop /confirm_reservation/{id}/{token}
   page ──POST reservation_status──► shows tour, date, persons, name, email
   "Confirm reservation" ──POST verify_reservation──► verificate = 1, verified_at = now, verify_method = 'email'
                                             ├─ linked to the account with that email (if there is one)
                                             ├─ mail to reserver: "Your reservation is confirmed" (tour info, trip, price, guide contacts)
                                             └─ mail "Confirmed tour reservation" to info@climbing.ge (sites.email) + every guide of the tour
```

- **Logged-in reserver:** the reservation is linked to the account at once (`tour_reservations_users`), but the email still has to be confirmed — same rule for everybody.
- **Guest whose email has an account:** linked on confirmation, not before. (The old code linked and auto-verified any reservation whose email matched an account, so anybody could book in someone else's name.)
- **Admin-created** (calendar modal, `set_tour/set_reservation/create_reservation`): verified at once, `verify_method = 'admin'`, no confirmation mail; the admin notice is still sent.
- **Admin "Verify" button** (details modal): `verify_method = 'admin'`, sends the guide + reserver mails.
- **"Resend confirmation email"** (details modal, only while not confirmed): `set_tour/set_reservation/resend_verification/{id}`.
- Confirming twice does nothing the second time (`already_verified`), so no mails are sent twice.

## Link token

`hash_hmac('sha256', 'tour_reservation|{id}|{lowercased email}', app.key)` — nothing stored; the same idea as the order (`OrderConfirm`) and comment links. Checked with `hash_equals`. A wrong token or a deleted reservation → 404 → the page shows "Link is not valid". The link has no expiry.

URL: `APP_SSH + SHOP_URL + /confirm_reservation/{id}/{token}` (shop route `confirm_tour_reservation`). The old link (`/confirm_reservation/{id}/{email}` on whatever host made the request) had no page behind it and no secret.

## Database (`tour_reservations`)

| Column | Meaning |
|---|---|
| `verificate` | 0 / NULL = waiting for confirmation, 1 = confirmed (old column, still the flag everything reads) |
| `verified_at` | when it was confirmed (new) |
| `verify_method` | `email` (reserver clicked the link) / `admin` (verified or created in the panel) (new) |
| `text` | now `TEXT` (was varchar 191 — the form allows 1000 characters) |
| `verify_sent_at` / `verify_sent_count` | last time the confirmation mail went out / how many times (first send + resends) — the admin timeline (new) |

Migrations `2026_11_10_120000_add_verification_fields_to_tour_reservations.php`, `2026_11_10_150000_add_verify_mail_tracking_to_tour_reservations.php` (count only goes up when the mail did not throw). Old confirmed rows have `verified_at = NULL`; the table then shows only ✓. Optional backfill:

```sql
UPDATE tour_reservations SET verified_at = updated_at, verify_method = 'admin' WHERE verificate = 1 AND verified_at IS NULL;
```

Rows are now saved with Eloquent, so `created_at` / `updated_at` are filled (the old `insertGetId` left them NULL).

## Endpoints

| Method | Path | Notes |
|---|---|---|
| POST | `/api/set_user_reservation/create_reservation/{tour_id}` | public, reCAPTCHA v3, `throttle:10,1`. Returns `{status: 'verify_email', email, message}` |
| POST | `/api/set_user_reservation/reservation_status/{id}` | public, body `{token}`, `throttle:30,1`. Safe summary only (no phone / address) |
| POST | `/api/set_user_reservation/verify_reservation/{id}` | public, body `{token}`, `throttle:10,1`. `{status: 'verified' \| 'already_verified', reservation}` |
| GET | `/api/set_tour/get_reservation/get_reservation/{id}` | admin, `tour_reservation.show` — details modal: row + `tour` (titles, location, duration, `min_price`, `published`, `public_url`), `guides` (name, email, phone), `accounts` (linked users), `same_email_count` (`TourReservationService::admin_details`) |
| POST | `/api/set_tour/set_reservation/verifiation_reservation/{id}` | admin, `tour_reservation.edit` |
| POST | `/api/set_tour/set_reservation/resend_verification/{id}` | admin, `tour_reservation.edit`; 422 when already confirmed |

## Mails

| Mail | To | When | Template |
|---|---|---|---|
| `ReservationVerifyEmail` | reserver | created (not admin) / resend | `emails/tour/reservation_varification` — details without phone + "Confirm reservation" button |
| `AdminNewReservationNotification` | `sites.email` (fallback `mail.from.address`) | created | `emails/tour/admin_new_reservation` — full details, confirmed or not, "Open reservations" button → user panel `/all_tours_reservations_list` |
| `ReservationSacsesfulNotification` | reserver | confirmed | `emails/tour/reservation_confirmed_client`: "Your reservation is confirmed: <tour>" with the trip (check-in, duration, persons, **price**), about the tour (short description, location, tour length, "Open the tour page"), guide contacts, next steps |
| `ReservationCreatingGuideNotification` | `sites.email` (info@climbing.ge) **and** every guide of the tour, without duplicates | confirmed | `emails/tour/reservation_confirmed_guide`: "Confirmed tour reservation: <tour> — <date>" with full reserver contact (mailto / tel links), trip, price, message, guide names, "Open reservations" |

**Tone and design:**
- Every mail has a friendly greeting by first name, short plain sentences and a "What happens next" part. It signs off "The climbing.ge team".
- Subjects:
  - "One click left — please confirm your tour reservation 🏔"
  - "You're all set! Your reservation is confirmed: <tour> 🎉"
  - "📬 New reservation request: <tour>"
  - "🎒 New confirmed reservation: <tour> — <date>"
- The trip summary is a rounded card (inline styles, for mail clients) with the tour name on top and rows for 📅 date, ⏱ duration, 👥 group, name, email (plus phone and location for the guide and admin), the message in a white box, and the price in a green box ("On request" when there is no price).
- The guide mail has **Call** and **Email** buttons, and the admin notice has a ✅ / ⏳ status pill.

All share `emails/tour/partials/reservation_table` (tour name, duration, price — see [tour_price_calculator.md](tour_price_calculator.md)). `emails/tour/reservation_completed` was removed (unused). Look and tone of all mails: [global/mail_design.md](../global/mail_design.md). A failing mail is logged (`Tour reservation mail failed`) and never breaks the reservation. Mails are sent in the request (not queued), so the confirm button takes a few seconds.

## Shop page states

`loading` → `invalid` (bad link) · `ready` (details + **Confirm reservation**) → `done` · `already` (opened again later). Done / already show the confirmation time and an "Open my account" button. Phone: the details list stacks.

## Booking form

`TourMessageFormComponent.vue` (tour page).

Laid out as **"Calculate the price and reserve your tour"**: step 1 calculator (duration chips, persons stepper capped by the tour's max group, season calendar — [tour_price_calculator.md](tour_price_calculator.md), [tour_schedule.md](tour_schedule.md)), step 2 your details, then "Reserve · 540 €". The form is full width.

- **Every field is required** — duration, check-in date, persons (1 – the tour's max, 100 at most), name, email, phone, country, city and the message (5–1000 characters). Labels have a red `*`, "All fields are required" on top. The same rules are in `TourReservationRequest` (server) and `validate()` (client), so mistakes show under the field before sending; server 422 `errors` (`form_data.email` → `email`) land under the same fields. The first wrong field gets focus.
- Logged in with a complete profile: the contact fields are not shown — a "booking as" box shows name · email · phone · city with an "Edit your profile" link. Incomplete profile: a warning + "Complete my profile" button instead of the form.
- Under the form: "After sending you will get an email — the reservation is complete only after you confirm it".
- **After sending** the form is replaced by a "Check your email to finish the reservation" panel: the email the link went to, 3 steps (open the mail → press Confirm → only then the guide gets it), a spam-folder hint and "Make another reservation" (logged-in users keep their profile fields). The panel scrolls into view.
- No `alert()` any more; errors (reCAPTCHA, server) show inside the form. No reCAPTCHA key configured → the form still sends (the server skips the check too).
- The user comes from `get_auth_user()`.

## Admin table

- Column **Confirmed at** (`12 Nov 2026, 14:05 · by email`).
- Row colors via the table's [`row_marks`](../../FRONTEND/USER_PANEL_TABLE.md#row-marks-row-colors): **yellow** = not confirmed, **grey** = check-in date passed, with a legend.
- The "Declarations" tab = not confirmed ones (`get_declarations`).

## Admin details modal

`TourReservationDetailsModal.vue` — opens with the table row at once, then loads `get_reservation/{id}` (small spinner in the head).

| Part | Shows |
|---|---|
| Head | status pill (**Waiting for email confirmation** yellow / **Confirmed** green / **Check-in passed** grey), tour title, `#id · made <date>` |
| Fact tiles | check-in (weekday date, time only when the row has one, "in 12 days" / "today" / "5 days ago"), persons, **duration** (reserved days + the tour's own length text), **price** (the saved `price_total` with "180 € × 3", or "on request" + the tour's "from" price) |
| Email confirmation | timeline: reservation made → confirmation email sent (time, "sent N times"; "not needed" for admin ones; "no record" for old rows) → confirmed (time · by email / by admin) or waiting; **Verify** and **Resend confirmation email** while waiting |
| Reserver | name, email, phone (mailto / tel), from (city, country), linked account(s) or "Guest", "N other reservations from this email" |
| Tour | title → public tour page, "unpublished" chip, location, guide(s) with email / phone |
| Message | the reserver's text, line breaks kept |
| Actions | Send email (mailto with subject "Your reservation: <tour>"), Call, Add to Google Calendar (check-in time or 09:00, 8 h, with tour location), Delete (only with `tour_reservation.del`, inline confirm) |

Design-system tokens only (the old purple / pink gradients, Bootstrap 4 `badge-*` classes and Font Awesome 5 icon names that render nothing in FA4 are gone). Two columns on desktop, one on phones. The eye button passes only the row id — the page finds the row and opens the modal with it (it used to open empty). Verify / delete refresh the list (`@verified`, `@deleted`).

## Other fixes on the way

- `User::reservation()` pointed at a non-existent `tour_reservation_users` table → `tour_reservations_users`.
- `Tour_reservation::users()` relation added.
- "My tours reservations" page (`UserToursReservationsPage.vue`) called a route that does not exist → `set_tour/get_reservation/get_user_reservations`.
- Guide lookup no longer crashes when a tour has no guide.
- `get_reservation/{id}` was a route with no controller method (listed as dead in SECURITY_AND_PITFALLS) — it now serves the details modal.

## i18n

`shop.tour.message.*` (form: placeholders, `err_*`, `sent_*`, `verify_notice`, `all_required`…), `shop.tour.verification.*` (page), `admin.tour.verified_at_col, verified_colon_label, verified_yes, waiting_email_confirm, verify_method_email, verify_method_admin, row_unverified, row_past, resend_confirm_btn, resend_confirm_success, resend_confirm_failed`, `admin.tour.rd_*` (details modal) — en + ka.
