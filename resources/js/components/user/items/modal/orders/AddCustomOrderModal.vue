<template>
    <stack-modal
        :show="showModal"
        title="Add Custom Order"
        @close="closeModal"
    >
        <form @submit.prevent="submitOrder">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="shiping">Shipping Method <span class="text-danger">*</span></label>

                        <select class="form-control" v-model="form.delivery_type" name="currency" > 
                            <option value="self_delivery">Self-delivery</option> 
                            <option value="delivery">Delivery</option> 
                        </select> 

                        <small class="form-text text-muted">Shipping method for the order</small>
                    </div>
                </div>
            </div>
            <div class="row" v-if="form.delivery_type === 'delivery'" >
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="adres">Address And Map Spot<span class="text-danger">*</span></label>

                        <input type="text" class="form-control" id="adres" v-model="form.adres" placeholder="Enter address" required>
                        <small class="form-text text-muted">Adres</small>

                        <input type="text" class="form-control" id="map_point" v-model="form.adres" placeholder="Enter map point" required>
                        <small class="form-text text-muted">Adres map point</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="payment">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="form.payment_type" name="currency" required> 
                            <option value="deliverd_payment">Payment on delivery</option>
                            <option value="mony_transfer">Money transfer</option>
                            <option value="online_payment" disabled>Online payment (coming soon)</option> 
                        </select>
                        <small class="form-text text-muted">Payment method used for the order</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="payment">Contacts <span class="text-danger">*</span></label>
                        
                        <input type="text" class="form-control" id="phone_number" v-model="form.phone_number" placeholder="Phone Number" required>
                        <small class="form-text text-muted">Phone number</small>

                        <input type="email" class="form-control" id="adres" v-model="form.email" placeholder="Email" required>
                        <small class="form-text text-muted">Email</small>
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
                            <select class="form-control" v-model="actyve_product_id" @change="onProductChange(index)" required>
                                <option value="">Select Product</option>
                                <option v-for="prod in products" :key="prod.id" :value="prod.id">{{ prod.title }}</option>
                            </select>
                        </div>
                        <div class="col-md-3" v-if="actyve_product_id">
                            <label>Option <span class="text-danger">*</span></label>
                            <select class="form-control" v-model="actyve_product_option_id" @change="onOptionChange(option.id)" required>
                                <option value="">Select Option</option>
                                <option v-for="option in actyve_product_options" :key="option.id" :value="option.id" :disabled="option.quantity <= 0">{{ option.name }} ({{ option.price }} GEL) - Stock: {{ option.quantity }}</option>
                            </select>
                        </div>
                        <div class="col-md-2" v-if="actyve_product_option_id">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" placeholder="e.g., 1" v-model="product_option_quantyty" min="1" :max="actyve_product_option_max_quantyty" required>
                        <small class="form-text text-muted">Max quantity - {{actyve_product_option_max_quantyty}}</small>
                        </div>
                        <div class="col-md-2" v-if="actyve_product_option_id">
                            <label>Image</label>
                            <div v-if="actyve_product_option_image" class="option-image">
                                <img :src="'/public/images/product_option_img/' + actyve_product_option_image" :alt="'Option Image'" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
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
        <div slot="modal-footer">
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" @click="addProductAndMakeDeliveryTask">
                   Submit & make delivery task
                </button>
                
                <button type="button" class="btn btn-primary" @click="submitOrder">Submit</button>
            </div>
        </div>
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
                    adres: '',
                    map: '',
                    shiping: '',
                    payment: '',
                    order_product_list: []
                },

                actyve_product_id: null,

                actyve_product_option_id: null,
                actyve_product_options: [],

                actyve_product_option_max_quantyty: null,
                product_option_quantyty: 1,
                actyve_product_option_image: null,

                data: {
                    delivery_type: 'self_delivery',
                    payment_type: 'deliverd_payment'
                }
            }
        },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            axios.get('/product/get_current_products')
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
                quantity: 1,
                options: []
            });
        },
        removeProduct(index) {
            this.form.order_product_list.splice(index, 1);
        },
        onProductChange() {
            axios.get(`/product/get_product_options/${this.actyve_product_id}`)
            .then(response => {
                this.actyve_product_options = response.data;
            })
            .catch(error => {
                console.log(error);
            });
        },

        onOptionChange(optionId) {
            if (!optionId) return 1;
            for (let prod of this.form.order_product_list) {
                const option = prod.options.find(o => o.id == optionId);
                if (option) {
                    this.actyve_product_option_max_quantyty = option.quantity || 1;
                }
            }
            return 1;
        },
        resetForm() {
            this.form = {
                adres: '',
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
        },
        addProductAndMakeDeliveryTask() {
            alert('coming soon');
        }
    }
}
</script>
