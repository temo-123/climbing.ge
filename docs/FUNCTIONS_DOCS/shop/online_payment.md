# Shop online payment (TBC Pay) & payment config

Buyers can pay a shop order online by card through TBC Pay, as an alternative to cash on delivery. The option only appears when the shop's TBC channel is configured.

Merchant account, developer API key, `.env` values, callback URL and IP allow-list: [TBC_PAYMENT_SETUP.md](../../TBC_PAYMENT_SETUP.md). Order placement itself (prices, stock, shipping, discounts): [order_placement.md](order_placement.md). This page covers the configuration switches and the payment part of checkout.

---

## Configuration

### `.env` → `config/services.php`

| `.env` | Config key | Used for |
|---|---|---|
| `TBC_PAY_BASE_URL` | `services.tbc_pay.base_url` | TBC API base, default `https://api.tbcbank.ge/v1/tpay` |
| `TBC_PAY_API_KEY` | `services.tbc_pay.api_key` | Developer-portal key (`apikey` header), shared by both channels |
| `TBC_PAY_SHOP_API_KEY` | `services.tbc_pay.shop.api_key` | Optional — overrides the shared key for the shop |
| `TBC_PAY_SHOP_CLIENT_ID` | `services.tbc_pay.shop.client_id` | Shop merchant client ID |
| `TBC_PAY_SHOP_CLIENT_SECRET` | `services.tbc_pay.shop.client_secret` | Shop merchant client secret |
| `TBC_PAY_DONATION_*` | `services.tbc_pay.donation.*` | Same three for donations — see [donations.md](../global/donations.md) |
| `APP_SSH`, `USER_PAGE_URL` | read with `env()` | Build the return URL `https://<USER_PAGE_URL>/payment/success` |

The shop channel is **on** only when `TbcPaymentService('shop')->isConfigured()` — API key (shop or shared) + client ID + client secret all non-empty. Shop and donations can be separate merchant accounts or share the same credentials.

After changing values: `php artisan config:clear` (dev) / `php artisan config:cache` (production). If you changed the client ID/secret, also `php artisan cache:forget tbc_pay_token_shop` — the access token is cached for 270 s per channel.

### The switch the frontend reads

```
GET /api/payment/status   (public, routes/api/general.php → PaymentStatusController)
→ { "shop_enabled": bool, "donation_enabled": bool, "donation_card_enabled": bool, "donation_bank_enabled": bool }
```

`resources/js/services/shop/checkout.js` → `online_payment_enabled()` returns `shop_enabled` (`false` on any error). Nothing else in the shop UI reads the credentials.

### Related shop settings (not TBC)

Delivery prices, free-shipping thresholds and minimum order per region (`shiped_regions`), the delivery-period day ranges (`delivery_*` [coefficients](../../COEFFICIENTS.md)) and partner / sale-code discounts are separate settings — see [SHOP.md](../../SHOP.md) and [order_placement.md](order_placement.md). They decide the amount that TBC is asked to charge.

---

## Design concept

- **Hide what can't work.** No shop credentials → the "Pay online · TBC" option isn't rendered, and a stale `online payment` choice carried in the URL falls back to cash on delivery.
- **Paying is confirming.** Cash-on-delivery orders need the buyer's e-mail confirmation; an online order is confirmed by the successful payment itself, and the seller is notified at that moment.
- **The server decides the amount.** The total sent to TBC is recomputed from the price snapshot stored with the order lines, not from the browser.
- **TBC is the source of truth.** Callback and status endpoints both re-fetch the payment from TBC before changing the order.

---

## Flow

### 1. Choose the payment method — checkout step 2

`user/pages/orders/decloration/orderPaymentPageComponent.vue` (`/order/decloration/order_payment`):

| Option | Value sent (`payment_tupe`) | Shown when |
|---|---|---|
| Cash on delivery | `deliverd payment` | always (default) |
| Pay online · TBC | `online payment` | `online_payment_enabled()` is true |

The chosen value is passed in the route params to the review page.

### 2. Review & place — checkout step 3

`orderDeclorationPageComponent.vue` (`/order/decloration/order_decloration/:payment/:adres/:discount?`) re-checks `online_payment_enabled()` and falls back to cash on delivery if it's now off. The payment card reads "Pay online · TBC"; the button reads "Redirecting to payment…" while working.

`place_order()`:

1. `POST set_order/create_order` → `OrderController::create_order` → `OrderPlacementService::place_website()` — creates the order with `status = pending`, `confirm = 0`, reserves stock, clears the cart. **No confirmation e-mail** is sent for `online payment` (it is for cash on delivery).
2. If online: `POST payment/shop/initiate/{order_id}` → `window.location.href = payment_url` (TBC's card page).
3. Otherwise: go to the placed-order page (`confirmOrder`).

### 3. Initiate the payment

`POST /api/payment/shop/initiate/{order_id}` (`auth:sanctum`, `banned`) → `OrderPaymentController::initiate`:

1. Shop channel not configured → `503 "Online payment is currently unavailable."`
2. Order must belong to the logged-in user → else `404`.
3. Order already has a `tbc_pay_id` → `400 "Payment already initiated for this order"` (one TBC payment per order).
4. Amount = sum of `order_products.total_price` (snapshot; falls back to the option's current sale price × quantity for old rows) + `orders.shiping`, then `orders.discount` % off, rounded to 2 decimals.
5. `TbcPaymentService('shop')->createOrder()` with `amount {GEL, total}`, `returnurl = https://<USER_PAGE_URL>/payment/success?order_id=<id>`, `callbackUrl = url('/api/payment/shop/callback')`, `merchantPaymentId = <order id>`, `language = KA`.
6. Save `tbc_pay_id`, `tbc_pay_status`, `payment_amount` on the order → return `{ payment_url, pay_id }`. No `REDIRECT` link → `500`.

### 4. Callback

`POST /api/payment/shop/callback` (public, called by TBC):

1. Find the order by `MerchantPaymentId`, falling back to `PaymentId` → `tbc_pay_id`. Not found → `404`.
2. `getOrder(tbc_pay_id)` from TBC. Fails → `500`.
3. Apply the status (table below). On the **first** time the order becomes `confirm = 1`, send the seller/site notification (`OrderController::send_order_mail_ot_admin`).

### 5. Return page

`user/pages/orders/PaymentSuccessPage.vue` (`/payment/success?order_id=…`, user panel, logged in) calls `GET /api/payment/shop/status/{order_id}` once. That endpoint re-checks TBC, applies the same status update and the same first-confirmation notification as the callback (so a missed callback still completes the order), and returns `{ tbc_pay_status, status, total }`.

On success the page sends one GA4 `purchase` event (`transaction_id` = order id, `value` = total), guarded by `sessionStorage` key `ga_purchase_<order_id>` so a reload doesn't double-count.

`/payment/cancel` (`PaymentCancelPage.vue`) exists but TBC has no cancel URL, so nothing sends buyers there.

---

## Statuses

| TBC status (case-insensitive) | `orders.status` | `orders.confirm` | Stock | Mail |
|---|---|---|---|---|
| `Succeeded` | `paid` | `1` | stays reserved | seller notified once |
| `Failed` / `Rejected` / `Cancelled` / `Expired` | `payment_failed` (set once) | unchanged | released via `OrderPlacementService::release_stock()` — only lines that reserved stock, never twice | — |
| anything else (`Created`, `Processing`…) | unchanged (`pending`) | unchanged | reserved | — |

`status_updating_data` is set to `now()` on each change. `tbc_pay_status` always stores TBC's raw value.

Admin lists map `orders.payment` to a method label with `OrderService` (`online payment` / `online_payment` → `online`). Manual admin orders (`OrderPlacementService::place_manual`) can be marked `online_payment` — those are recorded as already paid elsewhere (`Order Complete`, `confirm = 1`) and never go through TBC.

---

## Data

`orders` columns added by `2026_06_15_000001_add_tbc_pay_columns_to_orders_table.php`:

| Column | Meaning |
|---|---|
| `tbc_pay_id` | TBC `payId`; set once — blocks a second initiate |
| `tbc_pay_status` | Last raw status from TBC |
| `payment_amount` | Amount sent to TBC (GEL) |

---

## Endpoints

| Method | Path | Auth | Purpose |
|---|---|---|---|
| GET | `/api/payment/status` | — | `shop_enabled` switch |
| POST | `/api/set_order/create_order` | sanctum | Place the order (`payment_tupe: online payment`) |
| POST | `/api/payment/shop/initiate/{order_id}` | sanctum + banned | Start TBC payment, return `payment_url` |
| GET | `/api/payment/shop/status/{order_id}` | sanctum + banned | Status for the return page (re-checks TBC) |
| POST | `/api/payment/shop/callback` | — (TBC) | Server-to-server result |

Routes: `routes/api/get_shop_routes.php`. Code: `app/Services/TbcPaymentService.php`, `app/Http/Controllers/Api/Shop/Payment/OrderPaymentController.php`.

---

## Rules

- Currency is always **GEL**.
- One TBC payment per order (`tbc_pay_id` is never cleared).
- Never mark an order paid from callback data alone — always via `getOrder()`.
- A failed payment releases stock exactly once; a later success after a failure still sets `paid` but does **not** re-reserve stock.
- Logs: `grep "TBC Pay \[shop\]" storage/logs/laravel.log` (service), `TBC shop callback` / `TBC shop status` (controller).

---

## Known issues

- **Return page compares the status case-sensitively.** `PaymentSuccessPage.vue` checks `tbc_pay_status === 'SUCCEEDED'` and `['FAILED', …].includes(s)`, while TBC returns `Succeeded` / `Failed` (the backend compares case-insensitively). A paid order can then show the "processing" state and skip the GA4 `purchase` event. Fix: compare `String(s).toUpperCase()`, or use the returned `status === 'paid'` / `'payment_failed'`.
- **Initiate failure leaves an unpaid order.** If `create_order` succeeds but `initiate` fails (TBC down, no redirect link), the order stays `pending` with stock reserved and the cart already cleared; the buyer only sees an error. Nothing retries or releases it. If `tbc_pay_id` was saved, a retry is refused with `400`.
- **Abandoned payments.** A buyer who closes TBC's page leaves the order `pending` until TBC reports `Expired` through the callback (or someone opens the return page) — stock stays reserved meanwhile.
- `initiate` always sends `language: KA`, even for English-speaking buyers.
- The return page checks once; a slow `Processing` status needs a manual reload.
