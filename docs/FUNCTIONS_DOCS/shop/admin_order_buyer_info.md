# Admin orders: buyer info

Shows who placed an order, both for **custom** (admin-entered) orders and **cart / online** orders.

## Where the buyer comes from

| Order type | Source |
|---|---|
| Custom (`is_custom = 1`) | `custom_order_addresses` via `custom_order_addresses_order` (name, surname, email, phone) |
| Cart / online | `orders.user_id` → `users` (name, surname, email, phone_number, country, city) |

## List

`GET get_order/get_all_orders` (`order › show`) adds flat `buyer_name`, `buyer_email`, `buyer_phone` to each order, filled from the source above. Shown as Buyer / Email / Phone columns on the Orders page (`pages/orders/allOrderPageComponent.vue`) and as a Buyer cell (name + email + phone) in the shop dashboard's recent orders (`dashboards/shop/assets/OrdersListComponent.vue`).

## Details modal

`GET get_order/get_order_detals/{id}` returns `buyer` (the user account) for non-custom orders, admin only. The customer's own purchase detail doesn't get it. `items/modal/orders/orderDetalsModal.vue` shows it as "Buyer Information" above the delivery address, or "no registered user found" when the account no longer exists. Custom orders keep their existing buyer + linked-accounts block.
