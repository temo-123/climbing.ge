# TODO for Stock Management Implementation

## Completed Tasks
- [x] Update ProductController::get_local_products to include quantity in product options
- [x] Add stock validation in CartController::create and update methods to prevent adding more than available stock
- [x] Add stock validation in OrderController::create_order and add_custom_order to prevent ordering more than available stock
- [x] Update AddCustomOrderModal to display stock quantity, disable out-of-stock options, and limit quantity input
- [x] Update ProductPage.vue to display "Out of Stock" when quantity = 0 for selected option
- [x] Update CatalogItemComponent to show "Out of Stock" overlay when all options are out of stock

## Notes
- Stock management is now fully implemented across the application
- Users cannot add to cart or order more items than available stock
- Out of stock products are clearly marked in product listings and detail pages
- Custom order modal shows stock levels and prevents over-ordering
