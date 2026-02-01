# TODO - Move Out-of-Stock Items to End of Array

## Task
Sort products so out-of-stock items appear at the end of the array

## Files to Edit
- [x] 1. resources/js/components/shop/pages/lists/ProductsCatalog.vue - Reverted frontend changes
- [x] 2. resources/js/components/shop/pages/lists/SaleProductsPage.vue - Reverted frontend changes
- [x] 3. app/Services/ProductService.php - Added backend sorting

## Changes Required
Modify `get_locale_product_use_locale` method in ProductService.php to sort products:
- In-stock products first
- Out-of-stock products last

## Implementation Status
- [x] ProductsCatalog.vue - Reverted frontend sorting
- [x] SaleProductsPage.vue - Reverted frontend sorting
- [x] ProductService.php - Added backend sorting using collect()->sortBy()

