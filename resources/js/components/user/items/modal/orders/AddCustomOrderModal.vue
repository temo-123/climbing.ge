<template>
    <StackModal
        v-model="showModal"
        title="Add Custom Order"
        @close="closeModal"
        @shown="() => { if (!submitted) this.$nextTick(() => this.$el.querySelector('input').focus()); }"
    >
        <form @submit.prevent="submitOrder" v-if="!submitted">

            <!-- Buyer info -->
            <h6 class="text-muted mb-2">Buyer Information</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="form.name" placeholder="First name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Surname <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="form.surname" placeholder="Last name" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email <small class="text-muted">(optional — used to link account)</small></label>
                        <input type="email" class="form-control" v-model="form.email" placeholder="buyer@example.com">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone <small class="text-muted">(optional)</small></label>
                        <input type="tel" class="form-control" v-model="form.phone" placeholder="+995 ...">
                    </div>
                </div>
            </div>

            <hr>

            <!-- Delivery & payment -->
            <h6 class="text-muted mb-2">Order Details</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Shipping Method <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="form.delivery_type">
                            <option value="self_delivery">Self-delivery</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Payment Method <span class="text-danger">*</span></label>
                        <select class="form-control" v-model="form.payment_type" required>
                            <option value="deliverd_payment">Payment on delivery</option>
                            <option value="mony_transfer">Money transfer</option>
                            <option value="online_payment" disabled>Online payment (coming soon)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" v-if="form.delivery_type === 'delivery'">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" v-model="form.address" placeholder="Street address">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control" v-model="form.city" placeholder="City">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" v-model="create_production_task">
                    Allow out-of-stock (create production task)
                </label>
            </div>

            <hr>

            <!-- Products -->
            <h6 class="text-muted mb-2">Products <span class="text-danger">*</span></h6>
            <div v-if="form.order_product_list.length === 0" class="text-center text-muted p-3 border mb-2">
                No products added. Click "Add Product" below.
            </div>
            <div v-for="(item, index) in form.order_product_list" :key="index" class="border rounded p-3 mb-2 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Product {{ index + 1 }}</strong>
                    <button type="button" class="btn btn-danger btn-sm" @click="removeProduct(index)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Product</label>
                        <select class="form-control" v-model="item.product_id" @change="loadOptions(item)" required>
                            <option value="">Select product</option>
                            <option v-for="p in products" :key="p.id" :value="p.id">{{ p.title }}</option>
                        </select>
                    </div>
                    <div class="col-md-4" v-if="item.product_id">
                        <label>Option</label>
                        <select class="form-control" v-model="item.product_option_id" @change="onOptionChange(item)" required>
                            <option value="">Select option</option>
                            <option
                                v-for="opt in item.options"
                                :key="opt.id"
                                :value="opt.id"
                                :disabled="!create_production_task && opt.quantity <= 0"
                            >
                                {{ opt.name }} ({{ opt.price }} GEL) — Stock: {{ opt.quantity }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2" v-if="item.product_option_id">
                        <label>Qty</label>
                        <input type="number" class="form-control" v-model.number="item.quantity" min="1" :max="item.max_quantity" required>
                        <small class="text-muted">Max: {{ item.max_quantity }}</small>
                    </div>
                    <div class="col-md-2 d-flex align-items-center" v-if="item.option_image">
                        <img :src="'/public/images/product_option_img/' + item.option_image" class="img-thumbnail" style="max-width:70px;max-height:70px;">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm mb-3" @click="addProduct">
                <i class="fa fa-plus"></i> Add Product
            </button>
        </form>

        <!-- Success state -->
        <div v-if="submitted" class="text-center p-4">
            <i class="fa fa-check-circle text-success" style="font-size:2.5em;"></i>
            <h5 class="mt-2">Order #{{ createdOrderId }} created</h5>
            <div v-if="matchedUsers.length > 0" class="alert alert-info mt-3 text-left">
                <strong>Linked to existing account{{ matchedUsers.length > 1 ? 's' : '' }}:</strong>
                <ul class="mb-0 mt-1">
                    <li v-for="u in matchedUsers" :key="u.id">
                        {{ u.name }} {{ u.surname }} — {{ u.email }}
                    </li>
                </ul>
            </div>
            <div v-else class="alert alert-secondary mt-3">
                No existing account matched by email or name.
            </div>
        </div>

        <!-- <template #footer>
            <div class="d-flex gap-2 justify-content-end p-3">
                <button v-if="!submitted" type="button" class="btn btn-primary" @click="submitOrder" :disabled="submitting">
                    {{ submitting ? 'Saving...' : 'Create Order' }}
                </button>
                <button type="button" class="btn btn-secondary" @click="closeModal">
                    {{ submitted ? 'Close' : 'Cancel' }}
                </button>
            </div>
        </template> -->
    </StackModal>
</template>

<script>
export default {
    emits: ['orderAdded'],
    data() {
        return {
            showModal: false,
            products: [],
            form: {
                name: '',
                surname: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                delivery_type: 'self_delivery',
                payment_type: 'deliverd_payment',
                order_product_list: [],
            },
            create_production_task: false,
            submitting: false,
            submitted: false,
            createdOrderId: null,
            matchedUsers: [],
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            axios.get('/get_product/get_current_products')
                .then(r => { this.products = r.data; })
                .catch(() => {});
        },
        show_modal() {
            this.showModal = true;
            this.submitted = false;
            this.matchedUsers = [];
            this.createdOrderId = null;
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
                max_quantity: 9999,
                option_image: null,
                options: [],
            });
        },
        removeProduct(index) {
            this.form.order_product_list.splice(index, 1);
        },
        loadOptions(item) {
            item.product_option_id = '';
            item.options = [];
            if (!item.product_id) return;
            axios.get(`/get_product/get_product_options/${item.product_id}`)
                .then(r => { item.options = r.data; })
                .catch(() => {});
        },
        onOptionChange(item) {
            const opt = item.options.find(o => o.id == item.product_option_id);
            if (opt) {
                item.max_quantity = this.create_production_task ? 9999 : (opt.quantity || 1);
                item.option_image = opt.image || null;
                if (item.quantity > item.max_quantity) item.quantity = item.max_quantity;
            }
        },
        resetForm() {
            this.form = {
                name: '', surname: '', email: '', phone: '',
                address: '', city: '',
                delivery_type: 'self_delivery',
                payment_type: 'deliverd_payment',
                order_product_list: [],
            };
            this.create_production_task = false;
            this.submitted = false;
            this.submitting = false;
            this.matchedUsers = [];
            this.createdOrderId = null;
        },
        submitOrder() {
            if (!this.form.name || !this.form.surname) {
                alert('Name and surname are required.');
                return;
            }
            if (this.form.order_product_list.length === 0) {
                alert('Please add at least one product.');
                return;
            }
            const hasIncomplete = this.form.order_product_list.some(
                i => !i.product_id || !i.product_option_id || !i.quantity
            );
            if (hasIncomplete) {
                alert('Please complete all product selections.');
                return;
            }

            this.submitting = true;
            axios.post('/custom_order/store', {
                ...this.form,
                create_production_task: this.create_production_task,
            })
            .then(res => {
                this.createdOrderId = res.data.order_id;
                this.matchedUsers = res.data.matched_users || [];
                this.submitted = true;
                this.$emit('orderAdded');
            })
            .catch(error => {
                const msg = error.response?.data?.error || error.response?.data?.message || 'Error creating order.';
                alert(msg);
            })
            .finally(() => {
                this.submitting = false;
            });
        },
    },
};
</script>
