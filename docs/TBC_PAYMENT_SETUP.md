# TBC Payment Setup

Step-by-step guide for switching on payments on a fresh install — TBC card payments (TBC Pay / TBC Checkout API) for the shop and donations, plus the manual bank-transfer option for donations. Reference material (endpoints, flow, code) follows the setup steps.

Everything is optional: with nothing configured the site still works — the donation button and the shop's online payment are simply hidden (see [Graceful degradation](#graceful-degradation)).

---

## Table of Contents

- [Overview](#overview)
- [Setup Checklist](#setup-checklist)
- [Step 1 — Merchant agreement with TBC](#step-1--merchant-agreement-with-tbc)
- [Step 2 — Developer-portal API key](#step-2--developer-portal-api-key)
- [Step 3 — Merchant client ID / secret](#step-3--merchant-client-id--secret)
- [Step 4 — Callback URLs and IP allow-list](#step-4--callback-urls-and-ip-allow-list)
- [Step 5 — Fill in `.env`](#step-5--fill-in-env)
- [Step 6 — Bank transfer for donations (optional)](#step-6--bank-transfer-for-donations-optional)
- [Step 7 — Apply and verify](#step-7--apply-and-verify)
- [Troubleshooting](#troubleshooting)
- [How It Works in Code](#how-it-works-in-code)
  - [Graceful degradation](#graceful-degradation)
  - [Payment flow](#payment-flow)
  - [Endpoints](#endpoints)

---

## Overview

| Purpose | Method | Used in | Credentials |
|---|---|---|---|
| Shop order payments | TBC Pay API (card) | `shop.climbing.ge` checkout | `TBC_PAY_SHOP_*` |
| Donation card payments | TBC Pay API (card) | Donation modal (all subdomains) | `TBC_PAY_DONATION_*` |
| Donation bank transfer | Manual IBAN transfer (display only) | Donation modal, Georgian IPs only | `DONATION_TBC_*` |

Each card channel needs **three values**: an API key (developer portal) + a `client_id` / `client_secret` pair (merchant dashboard). Shop and donations can be two separate merchant accounts (separate payouts/reports) or share one.

---

## Setup Checklist

- [ ] E-commerce merchant agreement signed with TBC (one per channel you want separate)
- [ ] App created on **developers.tbcbank.ge** → API key copied
- [ ] `client_id` / `client_secret` copied from **ecom.tbcpayments.ge** for each merchant
- [ ] Callback URLs registered in the merchant dashboard
- [ ] Server firewall / WAF allows POSTs from TBC's IPs
- [ ] `.env` filled in, `php artisan config:clear` (or `config:cache`) run
- [ ] `GET /api/payment/status` shows the expected `true` flags
- [ ] Test payment done for each channel (shop + donation)
- [ ] (optional) IBAN details set for bank-transfer donations

---

## Step 1 — Merchant agreement with TBC

Card payments need an e-commerce (internet acquiring) agreement with TBC Bank. Contact TBC's business/e-commerce team (via [business.tbcbank.ge](https://business.tbcbank.ge) or your account manager) and ask for **TBC Checkout / TBC Pay API** e-commerce access.

You need **up to two merchant accounts**:

| Account | Purpose | Transactions visible in |
|---|---|---|
| Shop account | All shop/order payments | Merchant dashboard → shop merchant |
| Donation account | All donation card payments | Merchant dashboard → donation merchant |

> With only one merchant account, use the same credentials for both channels.

Once the agreement is active you get access to the merchant dashboard **ecom.tbcpayments.ge**.

---

## Step 2 — Developer-portal API key

Every request to TBC also carries an `apikey` header — without it TBC answers `401 "Invalid Api Key"`, even with a correct client ID/secret.

1. Sign up / log in at [developers.tbcbank.ge](https://developers.tbcbank.ge).
2. Create an application (**My Apps → Create app**).
3. Subscribe the app to the **TBC E-Commerce / tpay** API product.
4. Copy the app's **API key** → `TBC_PAY_API_KEY`.

One key normally serves both channels. If shop and donations use separate developer apps, set `TBC_PAY_SHOP_API_KEY` / `TBC_PAY_DONATION_API_KEY` — they override the shared key per channel.

---

## Step 3 — Merchant client ID / secret

1. Log in to [ecom.tbcpayments.ge](https://ecom.tbcpayments.ge) with the merchant account.
2. Open the integration / API settings page and copy **Client ID** and **Client Secret**.
3. Repeat for the second merchant account if shop and donations are separate.

These are exchanged for a short-lived access token (`POST /access-token`) on every payment.

---

## Step 4 — Callback URLs and IP allow-list

TBC POSTs the payment result to a server-to-server callback. Register both in the merchant dashboard (integration settings):

| Channel | Callback URL |
|---|---|
| Shop | `https://<api-host>/api/payment/shop/callback` |
| Donation | `https://<api-host>/api/set_donation/callback` |

`<api-host>` is the host the API request is made on — the code sends `url('/api/...')`, so it's whichever subdomain the frontend's axios calls (e.g. `climbing.ge` / `user.climbing.ge`). The routes exist on every host.

**Allow TBC's IPs** in any firewall, Cloudflare/WAF rule or rate limiter in front of the server:

```
193.104.20.44
193.104.20.45
185.52.80.44
185.52.80.45
```

The callback must be reachable over the public internet with valid HTTPS — **it won't work on `*.loc` local domains**. Locally, either expose the app through a tunnel (ngrok, Cloudflare Tunnel) or rely on the return-page polling (the success page queries the status itself, so the payment still completes without the callback).

There is **no cancel URL** in TBC's API: the user always returns to `returnurl`, which is set per request (nothing to configure):

| Channel | Return page |
|---|---|
| Shop | `https://<USER_PAGE_URL>/payment/success?order_id=…` |
| Donation | `https://<SITE_URL>/donation-success?donation_id=…` |

Both pages poll the status endpoint until the payment is `Succeeded` / failed.

---

## Step 5 — Fill in `.env`

```env
# TBC Pay API base URL — do not change unless TBC gives you a test URL
TBC_PAY_BASE_URL=https://api.tbcbank.ge/v1/tpay

# Developer-portal app key (Step 2). Required.
TBC_PAY_API_KEY=
# Optional per-channel overrides of the key above
# TBC_PAY_SHOP_API_KEY=
# TBC_PAY_DONATION_API_KEY=

# Merchant account for shop orders (Step 3)
TBC_PAY_SHOP_CLIENT_ID=
TBC_PAY_SHOP_CLIENT_SECRET=

# Merchant account for donations (Step 3) — same values as shop if you have one merchant
TBC_PAY_DONATION_CLIENT_ID=
TBC_PAY_DONATION_CLIENT_SECRET=
```

Also make sure `APP_SSH` (`https://` in production) and `USER_PAGE_URL` / `SITE_URL` are correct — they build the return URLs.

A channel counts as configured only when **all three** values (API key + client ID + client secret) are non-empty. Leave a channel blank to keep it switched off.

---

## Step 6 — Bank transfer for donations (optional)

Georgian visitors can also donate by manual bank transfer. These values are **display info only** — shown in the donation modal so the donor can transfer from their own banking app. Nothing is charged or verified automatically.

Bank details are returned only to Georgian IPs (`GET /api/get_donation/tbc_info`); others see a "Georgia only" note.

```env
# Your TBC account IBAN (GE + 2 digits + 16 characters) — setting this enables the Bank transfer tab
DONATION_TBC_IBAN=GE00TB0000000000000000

# Account holder name (as registered with TBC)
DONATION_TBC_ACCOUNT_NAME="Your Organization Name"

# TBC SWIFT/BIC — default is fine
DONATION_TBC_BANK_CODE=TBCBGE22

# Bank name label shown to the user — default is fine
DONATION_TBC_BANK_NAME="TBC Bank"
```

Read through `config('services.donation_bank.*')`, so they keep working with `config:cache`.

---

## Step 7 — Apply and verify

1. Reload config:

   ```bash
   php artisan config:clear      # dev
   php artisan config:cache      # production (re-run after every .env change)
   ```

2. Check the flags:

   ```bash
   curl -s https://climbing.ge/api/payment/status
   # {"donation_enabled":true,"donation_card_enabled":true,"donation_bank_enabled":true,"shop_enabled":true}
   ```

3. **Donation test:** open any page with the "Support" / donation button → card tab → small amount → pay on TBC's page → you should land on `/donation-success` with a success state, and the donation should appear in the admin donations table and in TBC's dashboard.
4. **Shop test:** place an order on the shop → pay online → `user.climbing.ge/payment/success` shows success, the order is marked paid.
5. Optionally refund/cancel the test transactions from the merchant dashboard.

---

## Troubleshooting

All TBC errors are logged with the prefix `TBC Pay [shop]` or `TBC Pay [donation]`:

```bash
tail -f storage/logs/laravel.log | grep "TBC Pay"
```

| Symptom | Cause / fix |
|---|---|
| Donation button / shop online payment not shown | Channel not configured — check all three values, then `config:clear`/`config:cache`. Check `/api/payment/status`. |
| `401 Invalid Api Key` in the log | `TBC_PAY_API_KEY` missing/wrong, or the developer app isn't subscribed to the e-commerce product. |
| `token error` in the log | Wrong `client_id` / `client_secret`, or the merchant isn't activated yet. Tokens are cached 270 s per channel — after fixing creds run `php artisan cache:forget tbc_pay_token_shop` (or `_donation`) or wait 5 min. |
| User pays but status stays pending | Callback blocked (firewall, IP rule, wrong URL in dashboard). The success page also polls `GET /payments/{id}`, so check that request in the log too. |
| Redirect after payment goes to `http://` or a wrong host | `APP_SSH`, `USER_PAGE_URL` or `SITE_URL` wrong in `.env`. |

---

## How It Works in Code

**Service:** `app/Services/TbcPaymentService.php` — instantiated with a channel that selects the credentials:

```php
new TbcPaymentService('shop')      // TBC_PAY_SHOP_* (+ TBC_PAY_SHOP_API_KEY ?: TBC_PAY_API_KEY)
new TbcPaymentService('donation')  // TBC_PAY_DONATION_* (+ TBC_PAY_DONATION_API_KEY ?: TBC_PAY_API_KEY)
```

Config lives in `config/services.php` under `tbc_pay` and `donation_bank`.

API calls used: `POST /access-token` (form: `client_id`, `client_secret`), `POST /payments` (`returnurl`, `callbackUrl`, `language`, `merchantPaymentId`, `amount{currency,total}`), `GET /payments/{payId}`, `POST /payments/{payId}/cancel`. Every request sends the `apikey` header; all but the token request also send the Bearer token. Statuses come back as `Created`, `Succeeded`, `Failed`, `Expired`… and are compared case-insensitively (`isSucceeded()` / `isFailed()` / `isExpired()`). Currency is always `GEL`.

**Controllers:**
- `app/Http/Controllers/Api/Shop/Payment/OrderPaymentController.php` — shop payments (`merchantPaymentId` = order id)
- `app/Http/Controllers/Api/Guide/Donations/DonationPaymentController.php` — donation payments (`merchantPaymentId` = `donation_<id>`) + bank-transfer info endpoint
- `app/Http/Controllers/Api/PaymentStatusController.php` — public availability flags

### Graceful degradation

`TbcPaymentService::isConfigured()` is `true` only when the API key and both client credentials for that channel are non-empty. Both payment controllers check it first and reject with an error instead of calling TBC with empty credentials. The frontend learns this ahead of time from:

```
GET /api/payment/status   (routes/api/general.php, no auth)
→ { "donation_enabled": bool, "donation_card_enabled": bool, "donation_bank_enabled": bool, "shop_enabled": bool }
```

- `donation_card_enabled` — donation channel configured.
- `donation_bank_enabled` — `DONATION_TBC_IBAN` is non-empty.
- `donation_enabled` — either of the two. `DonationComponent.vue` (footer / About Us / article "Support" button) renders only when this is true.
- `shop_enabled` — shop channel configured; the checkout hides online payment otherwise.

`DonationModalComponent.vue` re-reads the endpoint on open: the Card / Bank-transfer tab bar shows only when both methods are available; with only one it opens straight on that one.

**Guests can donate without an account.** The card form shows an optional "Log in" button. It hides the donation modal, opens the shared login modal (`open-register-modal` with a callback), and reopens the donation modal afterwards with the amount and comment kept, whether the user logged in or just closed the login modal. Once logged in, the donor fields are replaced by "Donating as …". Social login (Google/Facebook) leaves the page, so it can't return to the modal.

### Payment flow

1. Frontend calls the initiate endpoint → controller creates the local order/donation row.
2. `TbcPaymentService` gets an access token (`POST /access-token`, cached 270 s per channel — TBC tokens last 5 min).
3. `POST /payments` → `extractPaymentUrl()` picks the `REDIRECT` link → browser is sent to TBC's card page.
4. After paying, TBC POSTs the result to the callback (server-to-server) **and** sends the browser to `returnurl`.
5. The return page polls the status endpoint, which re-checks `GET /payments/{payId}` with TBC, so the result is correct even if the callback never arrived.

### Endpoints

| Method | Path | Auth | Purpose |
|---|---|---|---|
| GET | `/api/payment/status` | — | Which payment methods are enabled |
| POST | `/api/payment/shop/initiate/{order_id}` | sanctum | Start a shop payment, returns TBC redirect URL |
| GET | `/api/payment/shop/status/{order_id}` | sanctum | Shop payment status (polled by return page) |
| POST | `/api/payment/shop/callback` | — (TBC) | Shop server-to-server callback |
| POST | `/api/set_donation/process` | — (guest or user) | Create donation + start card payment |
| GET | `/api/set_donation/status/{id}` | — | Donation status (polled by return page) |
| POST | `/api/set_donation/callback` | — (TBC) | Donation server-to-server callback |
| GET | `/api/get_donation/tbc_info` | — | Bank-transfer details (Georgian IPs only) |
