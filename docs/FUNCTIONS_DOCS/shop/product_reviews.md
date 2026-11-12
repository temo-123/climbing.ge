# Product reviews

Review section on the shop product page (`ProductPage.vue` → `#reviews`).

## Files

- List: `resources/js/components/shop/items/FeedbacksComponent.vue` (props `product_id_prop`, `reviews_stars_prop`, `reviews_count_prop`; root is a `.col-12`, the page wraps it in `.container > .row`).
- Write modal: `resources/js/components/shop/items/modals/feedbacks/FeedbackModal.vue`; report modal: `FeedbackComplaintModal.vue`.
- API: `GET get_product/get_product_feedback/get_product_feedbacks/{product_id}`, `POST set_product_feedback_by_gest/create_feedback/{product_id}` (reCAPTCHA v3), `DELETE set_product/set_product_feedback/del_feedback/{id}`.
- Backend: `Api\Shop\ProductFeedbackController::create_feedback` → `CommentService::create_comment` (same validation as comments, errors keyed `data.*`). **One review per e-mail per product** (deleted ones don't count) — before Nov 2026 the check ignored the product, so a reviewer of one product could never review another.
- i18n: `shop.product.feedback.*`.
- Reviews also come from the [order review page](order_review.md) (sent with the "order complete" mail): published right away with `have_product = 1`.

## Section

- Header: big average score (`4.6`), stars, "N reviews"; round refresh button; **Write a review** (signed-in) — guests get the login modal first and the review modal opens after login. If the user already has a review in the list the button reads *Reviewed* and only shows an info toast.
- Star breakdown bars (5 → 1, counted from the loaded list).
- List: the first 3 reviews, then **Show all N reviews** / *Close*. Each row: avatar (photo / initials → user modal), name, *Owns it* badge (`have_product`), date, stars, text, *Delete* (own) / *Report* (others, signed-in).
- Empty state: "No reviews yet — be the first to rate it."

## Write modal

- Rating first: five large star buttons (`role="radio"`, hover / focus preview, word label Poor → Excellent). Required — sending without it marks the rating box red.
- Signed in: "Posting as Name Surname"; guest: labelled name / surname / email.
- Textarea with `n / 500` counter, *I own this product* switch (`have_product` 1/0).
- Own footer (Close / Add feedback with spinner); errors under fields; "already reviewed" (plain-string 200 from the API) shows as a warning in the modal; success closes the modal, reloads the list and shows a toast.
