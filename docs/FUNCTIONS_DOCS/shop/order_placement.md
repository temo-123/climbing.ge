# Order placement (one service for every order)

`app/Services/OrderPlacementService.php` is the only code that creates an order. Reading an order back (totals, delivery, labels, invoice) is `OrderService`; see [order_details_invoice.md](order_details_invoice.md).

| Entry point | Who | Calls |
|---|---|---|
| `POST set_order/create_order` (checkout page `orderDeclorationPageComponent.vue`) | logged-in customer | `place_website()` |
| `POST custom_order/store` (custom-order modal, Quick Sale) | `order › add` or `warehouse › sell_own` | `place_manual()` |
| `POST set_order/add_custom_order` (legacy; not used by the UI) | `order › add` | `place_manual()` with the admin as buyer |

## Design concept

Every order gets the same rules because they live in one transaction (`place()`). If anything fails, it throws `OrderPlacementException` (`{error, code}`) and **nothing** is written: no order, no lines, no stock taken, no sale code used up. Notifications stay in the controllers, after commit, because who gets mailed depends on the flow.

## What `place()` does, in order

1. **Items** (`normalise_items`): accepts both payload shapes (cart `{product:{id}, option:{id}, quantity}` and manual `{product_id, product_option_id, quantity}`). The quantity must be a whole number ≥ 1, and the same option twice is merged into one line.
2. **Prices** come from `product_options`, never the client. A line is charged the option's **sale price**: `ProductService::option_sale_price()` = `price − price × discount / 100` (`product_options.discount`, %, clamped 0–100), the same formula as the product page, merchant feed and SEO. The partner / sale-code discount then applies on top of that products subtotal, and the region minimum / free-shipping threshold use it too. `order_products.list_price` + `item_discount` keep the regular price and the sale % at order time (shown as a struck "was" price and a −N% badge in the cart, checkout, order modals and invoice). Before 2026-11-09 every flow charged the full price. The option must belong to the given product (`option_product_mismatch`). The line's `item_price` / `total_price` are **snapshotted**, so later price changes never alter old orders. Manual orders didn't snapshot before.
3. **Shipping** (delivery only): `OrderService::region_shipping()` checks the minimum order (`below_min_order`) and applies free shipping above the threshold, on the products subtotal before discount. Manual orders may use a typed price (`delivery_price` + `delivery_price_manual`); the minimum still applies.
4. **Stock**: one atomic `UPDATE … WHERE quantity >= n` per line, so the last unit can't be sold twice (`out_of_stock`, rolls back earlier lines).
   - Website: the general warehouse. A made-to-order (`produced_by_order`) item with no stock is produced on demand and takes nothing.
   - Manual: the seller's warehouse (`ProductService::resolveEffectiveWarehouseId`); `create_production_task` skips stock.
   - `order_products.stock_warehouse_id` records where stock was taken (NULL = none).
5. **Order row**: website orders keep the cost in `shiping`; manual orders keep the method in `shiping` and the cost in `delivery_price`, plus `warehouse_id`. Status / confirm per flow: website `pending` / 0 (COD confirms by e-mail, online by TBC); manual `pending` / 1, Pay Now `Order Complete` / 1 and always pickup.
6. **Buyer** (manual): `custom_order_addresses` + pivot. The street address is stored only for delivery.
7. **Account links** (manual, `orders_user`): `accounts_to_link()`, see below.
8. **Sale code** (website): a one-time code is marked used. Discounts don't stack: max(partner, sale code).

Failed or abandoned online payment → `OrderPaymentController::restockOrder()` → `release_stock()`. It gives back only lines with a `stock_warehouse_id` and clears it, so the callback and the status poll can't restock twice, and made-to-order lines that never took stock get nothing back. Before this, every line was restocked, even ones that never reserved stock.

## Checkout pages

Cart → **Delivery & payment** (`orderPaymentPageComponent`, `/order/decloration/order_payment`) → **Review** (`orderDeclorationPageComponent`, `/order/decloration/order_decloration/:payment/:adres?code=`) → **Done** (`confirmOrderPageComponent`). Shared parts live in `decloration/items/`: `CheckoutSteps.vue` (step bar plus the shared `.co-*` styles) and `CheckoutSummary.vue` (sticky summary). Data calls are in `services/shop/checkout.js`.

- **No prices are computed in the browser.** Both steps show `POST set_order/checkout_quote` → `OrderPlacementService::quote_website()`. It uses the same rules as `place_website()`: DB prices, the region rule, the bigger of partner / sale-code discount (`discount.source`, `discount.code.status` = `applied` | `not_better` | `invalid`), and general-warehouse stock. It returns localized titles and images, `region` (with `missing_for_min` / `missing_for_free`), `issues` and `can_place`. It writes nothing; a one-time code isn't used up by a preview.
- **Step 1, Delivery & payment:** address cards (with region name and Default badge) plus an inline new-address form with labels; payment cards (COD / TBC online); a discount code with a clear result. The Continue button is disabled with a reason (no address, below the minimum, stock).
- **Step 2, Review:** delivery and payment cards with *Change* links, items with image / name / option / unit × qty, remove and fix-stock buttons, and a **Place order** / **Pay {total}** button that says what happens next.
- **Bug fixed:** the sale code used to be passed as a route param outside the path. Vue Router drops those, so the review step always sent `sale_code: null` and codes were never applied. It now travels as `?code=`. The old `/:discount?` path segment is ignored, because the server always recomputes the discount.

## After checkout: the order-placed page

The checkout redirects to `confirmOrder` with `?order={id}` (`orders/decloration/confirmOrderPageComponent.vue`). The page loads the buyer's own order (`get_my_purchase_detail`) and shows:
- a success header with the order number;
- a 5-step progress tracker: placed → confirm by e-mail → preparing → shipped → delivered;
- for an unconfirmed cash-on-delivery order, a "one more step" card naming the e-mail address, with **Send the e-mail again** (`POST set_order/resend_order_confirm/{id}`: buyer's own unconfirmed COD order only, once a minute per order, otherwise `429 too_soon`; `already_confirmed` / `not_cash_order` → 400);
- the items + price summary, the delivery card, payment method and estimated delivery time;
- an invoice PDF download;
- My purchases / Continue shopping buttons.

Old links without `?order` show the generic message.

## Custom-order form validation (`AddCustomOrderModal.vue`)

- **One pass, field by field:** Create order and Export invoice check every field at once (`validationErrors`, keyed `name`, `surname`, `email`, `products`, `item.N.product|option|qty`, `region`). The minimum order shows on the region field, and the stock limit is ignored with *production task*.
- **On the field:** the wrong field is red (`is-invalid`) with its message underneath; a product row with a problem gets a red border. After the first attempt, checks run live and valid fields turn green. Optional e-mail only turns green when filled in.
- **Top and bottom:** the same list appears at the top and at the end of the form, and each entry jumps to its field. The footer next to *Create order* shows "N problem(s) — show", or "Everything looks good". The form scrolls to the first bad field, not the top.
- **Server errors:** Laravel validation keys (`order_product_list.N.quantity`, `region_id`, …) and placement codes (`below_min_order`) are put on the matching field. Anything else is listed as a general message. A field's server error clears once the form is edited.

## Linking a manual order to an account

- **Buyer search** (`GET custom_order/search_buyers`, `search_buyers()`): while staff type name / surname / e-mail / phone, `assets/BuyerAccountSearch.vue` lists matching accounts. Ranking: exact e-mail or phone (formatting ignored, last 9 digits) first, then full name, then partial. Each row shows match badges, partner organization and order count. Only `id, name, surname, email, phone_number, city, image` are returned. **Link** sends `linked_user_id`.
- **Linked on save:** the picked account, plus an exact e-mail match, plus a phone match only when exactly one account has that number.
- **Name + surname alone never links** (namesakes); it only shows up as a suggestion. Before, name-only matches were linked automatically.
- A linked account's partner organization discount also applies (the bigger of contact match vs. account).
- Linked orders show in the admin user-info modal (`orders.user_id` ∪ `orders_user`).

## Tests

`tests/Feature/Orders/*`, 65 tests: `CartCheckoutTest`, `ManualOrderTest`, `BuyerSearchTest`, `OnlinePaymentTest` (TBC faked over HTTP), `OrderLifecycleTest` (e-mail confirm, status mails, list = detail = invoice = PDF for both kinds, buyer-only views, user overview). Fixtures: `OrderTestCase`.

Tests run on the `.env` MySQL database inside `DatabaseTransactions` (rolled back). There's no pdo_sqlite and the DB user can't create a test schema. `tests/TestCase.php` refuses `RefreshDatabase` / `DatabaseMigrations` / `DatabaseTruncation`. Run: `php artisan test` (or `--filter=Orders`).
