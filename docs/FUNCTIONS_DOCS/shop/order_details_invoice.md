# Order details: prices, delivery & invoice PDF

Admin order modal (`items/modal/orders/orderDetalsModal.vue`, opened from the Orders page and the shop dashboard) and the buyer's own order modal (`CustomerOrderDetailsModal.vue`, My Purchases). Both show the same breakdown and can download an invoice PDF.

## Design concept

The server calculates everything; the client only displays it. Creating orders is a separate service ([order_placement.md](order_placement.md)). `app/Services/OrderService.php` is the single source for an order's type, shipping, payment, line items and totals. The modals, the order tables and the invoice all read from it, so the numbers can't drift apart.

### `orders.shiping` means two things

| Order type (`OrderService::type`) | `shiping` holds | Shipping cost | Address |
|---|---|---|---|
| `website`: customer checkout from the cart (`is_custom = 0`) | the **cost** (`"10"`) | `shiping` | always delivered, `adres_id` → `user_adreses` (+ region) |
| `manual`: created by staff in the admin panel / at a sale point (`is_custom = 1`) | the **method** `delivery` / `self_delivery` | `delivery_price` (only for `delivery`) | `custom_order_addresses`; pickup shows the order's warehouse |

Never read `shiping` directly. Use `shipping_method()` and `shipping_cost()`.

The shipping cost itself comes from the region rule `OrderService::region_shipping()` (minimum order, free above the threshold), the same for both order types. See the region rule in [SHOP.md](../../SHOP.md).

### Totals

`products subtotal − discount% (on products) + shipping = total`, which is the same formula the checkout, the custom-order modal and the old modals displayed. `paid_amount` = `orders.payment_amount`, the amount TBC actually charged, shown as its own "Charged online" line when present.

> Known mismatch: `OrderPaymentController::calculateOrderTotal()` (the TBC charge) applies the discount to products **and** shipping, so an online-paid order with a discount and shipping is charged slightly less than the displayed total. Left unchanged here because it changes real charges.

## API

| Endpoint | Who | Returns |
|---|---|---|
| `GET get_order/get_all_orders` | `order › show` | each row also gets `order_type`, `shipping_method`, `shipping_cost`, `payment_method`, `items_count`, `order_total` (`OrderService::list_summary`, no per-order queries) |
| `GET get_order/get_user_purchules` | buyer | same summary columns for own orders |
| `GET get_order/get_order_detals/{id}?locale=` | `order › show` | `order`, `order_type`, `payment_method`, `buyer`, `order_products` (localized `title`, `option_name`, `item_price`, `total_price`, `currency`), `totals`, `delivery`, `related_users` |
| `GET get_order/get_my_purchase_detail/{id}?locale=` | buyer, own order | same, without `buyer` / `related_users` |
| `GET get_order/invoice_pdf/{id}?locale=ka\|en` | `order › show` | PDF download `INV-000123.pdf` |
| `GET get_order/my_invoice_pdf/{id}?locale=ka\|en` | buyer, own order only (404 otherwise) | same PDF |

`delivery` = `method`, `cost`, `recipient`, `phone`, `email`, `street`, `details` (`[{key: entrance|floor|flat, value}]`), `city`, `region`, `zip`, `country`, `map`, `label` (the saved address' name), `pickup_from`.

## UI

- **Header strip:** order-type badge plus a one-line explanation, status, payment method + confirmed, date + delivery days, and the total.
- **Delivery card** (`assets/OrderDeliveryCard.vue`): Delivery / Self pickup with the shipping cost, recipient with clickable phone and email, the street in large text, entrance / floor / apt as chips, city · region · zip. Buttons: **Open in map** (saved map link, otherwise a Google Maps search) and **Copy address** (recipient + phone + address).
- **Products table** (`assets/OrderItemsTable.vue`): product, option, unit price, qty, line total; then subtotal (item count), discount, shipping (method, or "Free"), total, and "Charged online" when paid.
- **Footer:** `assets/OrderInvoiceButton.vue`, Invoice PDF with a KA / EN language picker.
- **Tables:** the Orders page, the shop dashboard list and My Purchases show readable *Order type*, *Payment*, *Shipping* ("Delivery · 10.00 ₾" / "Self pickup") and a *Total* column. Labels come from `resources/js/services/shop/orderLabels.js` (`with_order_labels`).
- i18n: `user.order_view.*`.

## Invoice PDF

`resources/views/pdf/custom_order_invoice.blade.php` (dompdf, DejaVu Sans for Georgian), data from `OrderService::invoice_data()`. It's shared with the pre-creation export in the Add-custom-order modal (`CustomOrderController::exportInvoicePdf`); the new fields (order number, delivery / payment strip, shipping row, paid row) are optional, so that export renders as before. Labels: `OrderService::invoice_labels()`; seller and bank details: `OrderService::company()` (`COMPANY_*` env). dompdf has no flexbox, so the layout uses tables. A normal order fits on one page.
