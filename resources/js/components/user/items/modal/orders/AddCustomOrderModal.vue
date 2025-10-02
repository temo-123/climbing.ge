<template>
    <stack-modal
        :show="showModal"
        title="Add Custom Order"
        @close="closeModal"
        :saveButton="{ visible: true, title: 'Add Order', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: true, title: 'Cancel', btnClass: { 'btn btn-secondary': true } }"
        @save="submitOrder"
    >
        <form @submit.prevent="submitOrder">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="adres_id">Address ID <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="adres_id" v-model="form.adres_id" placeholder="Enter address ID" required>
                        <small class="form-text text-muted">The ID of the delivery address</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="shiping">Shipping Method <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="shiping" v-model="form.shiping" placeholder="e.g., Standard, Express" required>
                        <small class="form-text text-muted">Shipping method for the order</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment">Payment Method <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="payment" v-model="form.payment" placeholder="e.g., Credit Card, PayPal" required>
                        <small class="form-text text-muted">Payment method used for the order</small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Order Products <span class="text-danger">*</span></label>
                <small class="form-text text-muted">Select products and options for this custom order</small>
                <div v-if="form.order_product_list.length === 0" class="text-center text-muted p-3 border">
                    No products added yet. Click "Add Product" to start.
                </div>
                <div v-for="(product, index) in form.order_product_list" :key="index" class="border rounded p-3 mb-3 bg-light">
                    <h6 class="mb-3">Product {{ index + 1 }}</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Product <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="product.product_id" @change="onProductChange(index)" required>
                                <option value="">Select Product</option>
                                <option v-for="prod in products" :key="prod.id" :value="prod.id">{{ prod.title }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Option <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="product.product_option_id" @change="onOptionChange(index)" required>
                                <option value="">Select Option</option>
                                <option v-for="option in getProductOptions(product.product_id)" :key="option.id" :value="option.id" :disabled="option.quantity <= 0">{{ option.name }} ({{ option.price }} GEL) - Stock: {{ option.quantity }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="e.g., 1" v-model="product.quantity" min="1" :max="getMaxQuantity(product.product_option_id)" @input="enforceMaxQuantity(index)" required>
                        </div>
                        <div class="col-md-2">
                            <label>Image</label>
                            <div v-if="getSelectedOptionImage(product.product_option_id)" class="option-image">
                                <img :src="'/public/images/product_option_img/' + getSelectedOptionImage(product.product_option_id)" :alt="'Option Image'" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                            </div>
                            <div v-else class="text-muted">No image</div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm" @click="removeProduct(index)">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm" @click="addProduct">
                    <i class="fa fa-plus"></i> Add Product
                </button>
            </div>
        </form>
    </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'

export default {
    components: {
        StackModal,
    },
    data() {
        return {
            showModal: false,
            products: [],
            form: {
                adres_id: '',
                shiping: '',
                payment: '',
                order_product_list: []
            }
        }
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            axios.get('/get_products_for_custom_order')
                .then(response => {
                    this.products = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        show_modal() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.resetForm();
        },
        addProduct() {
            this.form.order_product_list.push({
                product_id: '',
                product_option_id: '',
                quantity: 1
            });
        },
        removeProduct(index) {
            this.form.order_product_list.splice(index, 1);
        },
        onProductChange(index) {
            // Reset option and quantity when product changes
            this.form.order_product_list[index].product_option_id = '';
            this.form.order_product_list[index].quantity = 1;
        },
        onOptionChange(index) {
            // Reset quantity to 1 when option changes
            this.form.order_product_list[index].quantity = 1;
        },
        enforceMaxQuantity(index) {
            const product = this.form.order_product_list[index];
            const maxQty = this.getMaxQuantity(product.product_option_id);
            if (product.quantity > maxQty) {
                product.quantity = maxQty;
            }
            if (product.quantity < 1) {
                product.quantity = 1;
            }
        },
        getProductOptions(productId) {
            const product = this.products.find(p => p.id == productId);
            return product ? product.options : [];
        },
        getMaxQuantity(optionId) {
            if (!optionId) return 1;
            // Find the product that has this option
            let selectedProduct = null;
            for (let prod of this.form.order_product_list) {
                if (prod.product_option_id == optionId) {
                    selectedProduct = prod;
                    break;
                }
            }
            if (!selectedProduct) return 1;
            const prod = this.products.find(p => p.id == selectedProduct.product_id);
            if (!prod) return 1;
            const option = prod.options.find(o => o.id == optionId);
            return option ? option.quantity : 1;
        },
        getSelectedOptionImage(optionId) {
            if (!optionId) return null;
            // Find the product that has this option
            let selectedProduct = null;
            for (let prod of this.form.order_product_list) {
                if (prod.product_option_id == optionId) {
                    selectedProduct = prod;
                    break;
                }
            }
            if (!selectedProduct) return null;
            const prod = this.products.find(p => p.id == selectedProduct.product_id);
            if (!prod) return null;
            const option = prod.options.find(o => o.id == optionId);
            if (!option || !option.images || option.images.length === 0) return null;
            // Return the first image or the one marked as general
            const generalImage = option.images.find(img => img.general_image);
            return generalImage ? generalImage.image : option.images[0].image;
        },
        resetForm() {
            this.form = {
                adres_id: '',
                shiping: '',
                payment: '',
                order_product_list: []
            };
        },
        submitOrder() {
            axios.post('/order/add_custom_order', this.form)
                .then(response => {
                    alert('Custom order added successfully');
                    this.closeModal();
                    this.$emit('orderAdded');
                })
                .catch(error => {
                    console.log(error);
                    alert('Error adding order');
                });
        }
    }
}
</script>
