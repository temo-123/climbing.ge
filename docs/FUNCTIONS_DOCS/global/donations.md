# Donations (TBC card payment + bank transfer)

Visitors support climbing.ge with a one-off donation: by **card** through TBC Pay, or (Georgian visitors only) by **manual bank transfer** to the TBC account. Guests can donate without an account. Admins see every donation in the user panel.

Setting up the merchant account, API key, `.env` and callback URL: [TBC_PAYMENT_SETUP.md](../../TBC_PAYMENT_SETUP.md). This page describes how the function behaves.

---

## Design concept

- **Never offer a payment path that can't work.** Everything is driven by `GET /api/payment/status`. With nothing configured the "Support" button doesn't render at all; with only one method the modal opens straight on it, without tabs.
- **Terms first, then the form.** The first modal shows the short description and the donation terms of use, and continues only after the "I agree" checkbox is ticked.
- **Logging in is optional and never loses input.** A guest can log in from the form and come back to the same modal with the amount and comment kept.
- **The bank, not the browser, decides whether a donation is paid.** The callback and the return page both ask TBC for the status (`GET /payments/{payId}`). The values posted to us are only used to find the row.

---

## Where it appears

`resources/js/components/guide/items/DonationComponent.vue` ("Support" title + button) is placed in:

| Place | File |
|---|---|
| Guidebook footer | `guide/items/FooterComponent.vue` |
| About Us page | `guide/pages/AboutUsComponent.vue` |
| "Support" block in article text | `guide/items/article/ArticleTextBlocksComponent.vue` |
| User panel → My donations ("Make a donation" / "Make another donation") | `user/pages/donations/MyDonationsPageComponent.vue` (opens the modal directly) |

Props: `position` (`left` / `center` / `right`) and `autoShow` (opens the modal as soon as donations are known to be enabled).

---

## Flow

### 1. Button → terms modal

`DonationComponent` calls `GET payment/status` on mount and renders only when `donation_enabled` is true. The button opens `modals/Donation/DonationWarningModalComponent.vue`:

- `$siteData.data.donation_short_description` — short intro
- `$siteData.data.donation_terms_of_use` — the terms text
- "I agree" checkbox → **Continue** button (disabled until ticked)

These texts are `locale_sites` site-data entries edited in the admin site-data page (text changes for an existing slug → migration, see memory/CLAUDE.md conventions).

### 2. Donation modal

`modals/Donation/DonationModalComponent.vue`. On `show()` it re-reads `GET payment/status` and loads the current user with `get_auth_user()` (only if `auth_token` is in `localStorage`).

| `donation_card_enabled` | `donation_bank_enabled` | What the modal shows |
|---|---|---|
| ✔ | ✔ | Tabs **Card** / **Bank transfer**, Card open |
| ✔ | ✘ | Card form only, no tabs |
| ✘ | ✔ | Bank transfer only, no tabs (calls `switchToBank()` immediately) |
| ✘ | ✘ | Never reached — the button isn't rendered |

The header shows `$siteData.data.donation_description`.

#### Card tab

1. **Donor info**
   - Logged in → read-only "Donating as **Name Surname** (email)". Nothing from the form is sent; the backend uses the account.
   - Guest → optional **Log in** button + fields: name, surname, email, phone, country, age. All optional on the backend (email is starred in the UI but not enforced).
2. **Amount** — quick buttons `5 · 10 · 20 · 50 · 100 · 200` GEL, or a custom amount (picking one clears the other). "Selected: N GEL" summary. The Donate button is disabled while the amount is `0`.
3. **Comment** — optional, max 1000 chars.
4. **Donate N GEL** → `POST set_donation/process` → on success `window.location.href = checkout_url` (TBC's card page). An error message from the API is shown in the modal.
5. Note under the button: "By donating you agree to the [Donation terms]" → guidebook `/donation_terms` (`/ka/donation_terms` in Georgian). The link always points at the guidebook host, because the modal also opens in the user panel.

#### Optional login (guests)

`ask_login()` hides the donation modal and emits `open-register-modal` with a reason and a callback. Whether the login succeeds (callback) or the login modal is just closed (`login-modal-closed` on the bus), `return_from_login()` reopens the donation modal and reloads the user with `force`. `awaiting_login` stops `close()` from resetting the form in between. Social login (Google / Facebook) leaves the page, so it can't come back to the modal.

#### Bank-transfer tab

1. First open → `GET get_donation/tbc_info` (spinner "Checking availability…").
2. Outside Georgia → `{ allowed: false }` → "Bank transfer is only available in Georgia", plus a hint to use the card tab when card payment is on.
3. In Georgia → bank name, SWIFT/BIC, account holder, **IBAN with a Copy button** (shows "Copied" for 1 s), currency GEL, and a warning to write **donation** as the payment description.

Nothing is recorded for bank transfers — no `donations` row is created and nothing is verified. The donor pays from their own banking app.

**Georgian-IP check** (`DonationPaymentController::isGeorgianIp`): the client IP is taken from `CF-Connecting-IP`, then `X-Forwarded-For` (first entry), then `$request->ip()`, and looked up at `ip-api.com` (3 s timeout, `countryCode === 'GE'`). Private / loopback IPs count as Georgian only in the `local` / `development` environment. Any lookup failure → not allowed.

### 3. Backend: create the donation and start the payment

`POST /api/set_donation/process` → `DonationPaymentController::processDonation` (no auth middleware; reads the user with `auth('sanctum')->user()`).

1. Donation channel not configured → `503 "Online donations are currently unavailable."`
2. Validate `amount` (required, numeric, ≥ 1). For guests also validate the optional donor fields.
3. Donor data: logged in → name / surname / email / phone / country from the account, `user_id` = account. Guest → request fields; if the email matches an existing user, the donation is **linked to that user** (`user_id`), so it appears in their My donations.
4. Create the `donations` row: `status = pending`, `currency = GEL`.
5. `TbcPaymentService('donation')->createOrder()` with
   - `amount: { currency: GEL, total }`
   - `returnurl: https://<SITE_URL>/donation-success?donation_id=<id>`
   - `callbackUrl: url('/api/set_donation/callback')`
   - `merchantPaymentId: donation_<id>`
   - `language: KA` when the app locale is `ka`, else `EN`
6. TBC returned a `REDIRECT` link → save `tbc_pay_id` + `tbc_pay_status`, return `{ success: true, checkout_url, donation_id, donator, donation }`.
7. No redirect link / exception → row set to `failed`, `502 "Could not start the payment…"`.

### 4. Paying and coming back

TBC has **no cancel URL** — after paying, failing or cancelling, the browser always returns to `returnurl`.

`guide/pages/DonationSuccessPage.vue` (`/donation-success`) calls `GET set_donation/status/{donation_id}` **once** on load:

| Result | Screen |
|---|---|
| `donation.is_paid` | Green "Thank you" with the amount |
| `status` in `declined` / `expired` / `failed` | Red "Payment failed" |
| anything else (still pending, request error) | Yellow "Processing…, thanks" + back to guidebook |

The status endpoint re-checks TBC itself whenever the row has a `tbc_pay_id` and is neither paid nor failed, so the page is right even if the callback never arrived.

`DonationCancelPage.vue` (`/donation-cancel`) still exists but nothing links to it any more (it belonged to the old Flitt integration).

### 5. Callback

`POST /api/set_donation/callback` (no auth, called server-to-server by TBC):

1. Find the donation by `MerchantPaymentId` (`donation_<id>`), falling back to `PaymentId` → `tbc_pay_id`. Not found → `404` + warning log.
2. Re-fetch the payment from TBC (`getOrder`). Fails → `500` (TBC retries).
3. Store `tbc_pay_status` and map it (see below). Return `{ ok: true }`.

---

## Statuses

`donations.status` (`App\Models\Guide\Donation::STATUS_*`):

| Status | Set when |
|---|---|
| `pending` | Row created, waiting for TBC |
| `processing` | Only set by an admin by hand |
| `paid` | TBC status `Succeeded` |
| `declined` | TBC status `Failed` / `Rejected` / `Cancelled` |
| `expired` | TBC status `Expired` |
| `failed` | TBC never gave a payment page (step 3.7) |

TBC statuses are compared case-insensitively (`TbcPaymentService::isSucceeded / isFailed / isExpired`). Any other TBC status (e.g. `Created`, `Processing`) only updates `tbc_pay_status` and keeps the donation `pending`. `isPaid()` / `isFailed()` on the model are what the status endpoint uses to decide whether to ask TBC again.

---

## Data

Table `donations` — `name, surname, country, age, email, phone_number, comment, amount, currency, status, user_id, tbc_pay_id, tbc_pay_status` (+ legacy `flitt_order_id, response_url, server_callback_url` from the old Flitt gateway; `getAmountInCents()` is also a Flitt leftover). TBC columns: migration `2026_06_15_000002_add_tbc_pay_columns_to_donations_table.php`.

`User::donations()` — used by My donations.

---

## Admin & user panel

| Page | Route / file | API | Permission |
|---|---|---|---|
| Admin donations list | `user/pages/donations/DonationListPageComponent.vue` | `GET set_donation_admin/get_all_donations` | `donation` · `show` |
| Change status (modal, any of the six statuses) | same page | `POST set_donation_admin/update_status/{id}` | `donation` · `edit` |
| Delete / bulk delete | same page | `DELETE set_donation_admin/del_donation/{id}`, `POST set_donation_admin/bulk_delete` | `donation` · `del` |
| Donations card on the guidebook dashboard | `user/pages/dashboards/guide/assets/DonationListComponent.vue` | — | see [guide_dashboard.md](../guide/guide_dashboard.md) |
| My donations (own history, "donate again") | `/my_donations` → `user/pages/donations/MyDonationsPageComponent.vue` | `GET get_user_donations/my_donations` | logged in |
| Donation terms (user panel copy) | `/donation_terms` → `TermsOfUsePage` (`doc: donation_terms_of_use`) | — | — |

Admin endpoints live in `routes/api/admin/set_guide_routes.php` (`auth:sanctum` + `banned`), controller `App\Http\Controllers\Api\User\Admin\Guide\DonationController`. The loaded user relation is limited to `id, name, surname, email`.

A manual status change does **not** talk to TBC — use it to record bank transfers or fix a row; refunds are done in the TBC merchant dashboard.

---

## Public endpoints

| Method | Path | Auth | Purpose |
|---|---|---|---|
| GET | `/api/payment/status` | — | `donation_enabled`, `donation_card_enabled`, `donation_bank_enabled`, `shop_enabled` |
| POST | `/api/set_donation/process` | guest or user (`auth('sanctum')`) | Create donation, start TBC payment, return `checkout_url` |
| GET | `/api/set_donation/status/{id}` | — | Status for the return page (re-checks TBC) |
| POST | `/api/set_donation/callback` | — (TBC) | Server-to-server result |
| GET | `/api/get_donation/tbc_info` | — | Bank-transfer details, Georgian IPs only |

Routes: `routes/api/get_guide_routes.php` (`set_donation`, `get_donation` prefixes), `routes/api/general.php` (`payment/status`).

---

## Rules

- Currency is always **GEL**; minimum amount **1**.
- Card tab needs `TBC_PAY_API_KEY` (or `TBC_PAY_DONATION_API_KEY`) + `TBC_PAY_DONATION_CLIENT_ID` + `TBC_PAY_DONATION_CLIENT_SECRET`. Bank tab needs `DONATION_TBC_IBAN`. Re-run `php artisan config:cache` after changing them in production.
- Bank details are never sent to non-Georgian IPs.
- A donation is paid only when TBC says so on a server-side status check.
- New strings go into `resources/lang/i18n/{en,ka}.json` under `guide.donation.*` (admin: `admin.donations.*`).

---

## Known issues

- `routes/api/get_guide_routes.php` still registers `POST set_donation/create` → `DonationPaymentController@create`, which doesn't exist (calling it is a 500). Remove the route or add the method.
- `GET set_donation/status/{id}` is public and returns the donor's name, email and phone for any id — ids are sequential, so they can be enumerated. Consider returning donor data only to the owner, or using an unguessable token in `returnurl`.
- The return page checks the status once. If the callback is slow and TBC still reports `Processing`, the donor sees "Processing…" and has to reload.
- Email is marked `*` in the guest form but neither the form nor the API requires it.
