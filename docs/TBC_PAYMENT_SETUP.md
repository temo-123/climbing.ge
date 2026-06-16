# TBC Payment Setup

Configuration guide for TBC Bank payments — card payments (TBC Pay API) and bank transfer donations.

---

## Table of Contents

- [Overview](#overview)
- [TBC Pay — Card Payments](#tbc-pay--card-payments)
  - [Getting Credentials](#getting-credentials)
  - [Merchant Accounts](#merchant-accounts)
  - [env Variables](#env-variables)
- [Bank Transfer — Donations](#bank-transfer--donations)
  - [env Variables](#env-variables-1)
- [How It Works in Code](#how-it-works-in-code)
- [Testing](#testing)

---

## Overview

The project uses TBC Bank for two purposes:

| Purpose | Method | Used in |
|---|---|---|
| Shop order payments | TBC Pay API (card) | `shop.climbing.ge` checkout |
| Donation card payments | TBC Pay API (card) | Donation flow |
| Donation bank transfer | Manual IBAN transfer | Donation flow (Georgian users) |

---

## TBC Pay — Card Payments

### Getting Credentials

1. Log in to **TBC Business portal**: [business.tbcbank.ge](https://business.tbcbank.ge)
2. Go to **TBC Pay** → **My Applications**
3. Create an application (or use an existing one)
4. Copy the `Client ID` and `Client Secret` for each merchant account

You need **up to two merchant accounts**:
- One for **shop orders**
- One for **donations**

> If you only have one merchant account, use the same credentials for both.

### Merchant Accounts

| Account | Purpose | Transactions visible in |
|---|---|---|
| Shop account | All shop/order payments | TBC Pay dashboard → shop app |
| Donation account | All donation card payments | TBC Pay dashboard → donation app |

### env Variables

```env
# TBC Pay API base URL — do not change
TBC_PAY_BASE_URL=https://api.tbcbank.ge/v1/tpay

# Merchant account for shop orders
TBC_PAY_SHOP_CLIENT_ID=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
TBC_PAY_SHOP_CLIENT_SECRET=your_shop_secret_here

# Merchant account for donations
TBC_PAY_DONATION_CLIENT_ID=xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
TBC_PAY_DONATION_CLIENT_SECRET=your_donation_secret_here
```

---

## Bank Transfer — Donations

Georgian users are offered the option to donate via a direct bank transfer to a TBC account. These variables are just **display info** — they are shown to the user on the donation page so they can make the transfer manually in their banking app.

### env Variables

```env
# Your TBC bank account IBAN (GE + 2 digits + 16 digits)
DONATION_TBC_IBAN=GE00TB0000000000000000

# Account holder name (as registered with TBC)
DONATION_TBC_ACCOUNT_NAME=Your Organization Name

# TBC Bank SWIFT/BIC code — do not change
DONATION_TBC_BANK_CODE=TBCBGE22

# Bank name label shown to user — do not change
DONATION_TBC_BANK_NAME="TBC Bank"
```

---

## How It Works in Code

**Service:** `app/Services/TbcPaymentService.php`

The service is instantiated with a channel (`'shop'` or `'donation'`) which selects the correct credentials:

```php
new TbcPaymentService('shop')      // uses TBC_PAY_SHOP_* credentials
new TbcPaymentService('donation')  // uses TBC_PAY_DONATION_* credentials
```

**Flow:**
1. Service fetches an OAuth access token from TBC (`POST /access-token`) using `client_id` + `client_secret`
2. Token is cached for 4.5 minutes (TBC tokens last 5 min)
3. Token is used as Bearer on all subsequent API calls (`POST /orders`, `GET /orders/{id}`, etc.)

**Controllers:**
- `app/Http/Controllers/Api/Shop/Payment/OrderPaymentController.php` — shop payments
- `app/Http/Controllers/Api/Guide/Donations/DonationPaymentController.php` — donation payments + bank transfer info endpoint (`GET /api/get_donation/tbc_info`)

---

## Testing

After filling in `.env`, clear the config cache:

```bash
php artisan config:clear
```

**Sandbox:** TBC Pay has a sandbox environment. To use it, change `TBC_PAY_BASE_URL` to the sandbox URL (available in your TBC Business portal) and use sandbox credentials.

**Check logs** if a payment fails:

```bash
tail -f storage/logs/laravel.log
```

Errors from the TBC service are logged with the prefix `TBC Pay [shop]` or `TBC Pay [donation]`.
