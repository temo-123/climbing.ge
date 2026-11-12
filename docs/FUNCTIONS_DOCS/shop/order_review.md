# Order complete mail & order review page

When an admin sets an order's status to **Order Complete**, the buyer gets **one** mail: thanks + the ordered products + a *Rate my order* button. The button opens a review page where the buyer rates the delivery (admins only see it) and each product (published on the product page).

## Flow

1. Admin → Orders → edit status → `Order Complete` (`OrderController::edit_order_status` → `order_status_notification`).
2. For that status the generic "Your order #N has an update" mail is **not** sent. Instead `OrderCompletedNotification` (`emails/order/order_completed`, subject "Your order #N is complete 🎉 How did it go?") goes to the buyer — the user for cart / online orders, the custom-order address email for manual orders. It is sent **once**: `orders.review_requested_at` is set, and later "Order Complete" changes send nothing.
3. Link: `https://shop.climbing.ge[/ka]/order_review/{order_id}/{token}` (`/ka` when the buyer's `lang` is `ka`). `token = hmac_sha256('order_review|id|created_at', APP_KEY)` (`OrderReviewService::token`). No login needed; the token is the only access check. The page is `noindex` (`SeoService`).
4. The page (`shop/pages/pages/OrderReviewPage.vue`, route `order_review`) loads `GET api/order_review/get/{id}?token=&locale=` and saves with `POST api/order_review/save/{id}` (`Api\Shop\OrderReviewController`, throttled 30/10 per minute; open for unverified accounts in `EnsureEmailVerifiedForActions`). Saving needs status `Order Complete` (else 422 → "Your order is on its way").

## Page

- Page head band (`cg-art-carabiners`) "Rate your order · Order #N · date", greeting by name.
- **1 · Delivery & service** (lock icon: "Only our team sees this"): overall stars (required for this part), optional delivery speed + packaging stars, optional text (≤1000). One per order — after saving it shows "Thanks! You rated the order 4/5."
- **2 · Rate the gear** ("Shown on the product page with an *Owns it* badge"): one row per distinct product of the order (photo, title, option names), star picker; the text box appears after picking stars (≤500). Already reviewed products (same email, not deleted) show "Your review is published".
- Sticky footer: hint "Rate what you like — you can come back with the same link later" + **Send reviews** (disabled until something is rated). Partial saves keep the rest open; when everything is done, a thank-you card with *Back to the shop*.
- Bad token → "This link does not work".
- Star picker: `shop/items/StarInputComponent.vue` (v-model, hover/focus preview, Poor → Excellent words).

## Data

| What | Where | Who sees it |
|---|---|---|
| Order review | `order_reviews` (`order_id` unique, `user_id`, `stars`, `delivery_speed`, `packaging`, `text`) — model `Shop\Order_review`, `Order::review()` | Admins: Orders table **Review** column (`review_label`: ★★★★☆ · text, `…` = mail sent, no answer, `—` = not requested) and the order details modal ("Customer review" block). Not in the buyer's own purchase view. |
| Product reviews | `product_feedbacks` (`published = 1`, `have_product = 1`, name / surname / email of the buyer) + `user_product_feedbacks` when the buyer has an account | Product page reviews ([product_reviews.md](product_reviews.md)) |

Migration: `2026_11_12_130000_create_order_reviews_table.php` (also adds `orders.review_requested_at`). Service: `App\Services\OrderReviewService` (token, url, buyer, products, page, save). Only products of that order can be reviewed, once per email; the order review can't be overwritten.
