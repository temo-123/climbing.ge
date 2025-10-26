# TODO: Delete discount from product system, add discount to product_options, add is_donation_product checkbox to product model

## Tasks
- [x] Update Product model: remove 'discount' from fillable, add 'is_donation_product'
- [x] Update Product_option model: add 'discount' to fillable
- [x] Update productAddComponent.vue: remove discount input, add is_donation_product checkbox
- [x] Update productEditComponent.vue: remove discount input, add is_donation_product checkbox
- [x] Update productOptionControlPageComponent.vue: add discount input in add/edit option modals
- [x] Update ProductOptionController.php: modify add_option and edit_option to handle discount field
- [x] Run migrations: php artisan migrate
- [ ] Update frontend components that display discounts to use product_options discount instead of product discount
- [ ] Test the functionality
