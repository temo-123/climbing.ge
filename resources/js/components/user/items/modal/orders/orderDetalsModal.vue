<template>
    <StackModal
        v-model="is_order_detals_model"
        title="Order Details"
        @close="is_order_detals_model=false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="order">

            <!-- Order type badge -->
            <div class="mb-3">
                <span v-if="order.is_custom" class="badge badge-warning mr-2">Custom Order</span>
                <span v-else class="badge badge-primary mr-2">Cart Order</span>
                <span class="badge badge-info">
                    <i class="fa fa-clock-o"></i> Delivery: {{ delivery_days }} business days
                </span>
            </div>

            <!-- Delivery address (regular orders) -->
            <template v-if="!order.is_custom && buyer_address">
                <h6 class="text-muted mb-2">Delivery Address</h6>
                <div class="alert alert-secondary py-2 mb-3">
                    <strong>{{ buyer_address.name }}</strong>
                    <p class="mb-0 small">
                        {{ buyer_address.city }}<span v-if="buyer_address.strit">, {{ buyer_address.strit }} {{ buyer_address.number }}</span>
                        <span v-if="buyer_address.floor">, Floor {{ buyer_address.floor }}</span>
                        <span v-if="buyer_address.flat">, Flat {{ buyer_address.flat }}</span>
                    </p>
                    <p class="mb-0 small" v-if="buyer_address.zip_code">Zip: {{ buyer_address.zip_code }}</p>
                    <p class="mb-0 small" v-if="buyer_address.map">
                        <a :href="buyer_address.map" target="_blank" rel="noopener">
                            <i class="fa fa-map-o"></i> View on map
                        </a>
                    </p>
                </div>
                <hr>
            </template>

            <!-- Buyer info (custom orders only) -->
            <template v-if="order.is_custom && buyer_address">
                <h6 class="text-muted mb-2">Buyer Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" :value="buyer_address.name" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Surname</label>
                            <input type="text" class="form-control" :value="buyer_address.surname" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" :value="buyer_address.email || '—'" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" :value="buyer_address.phone || '—'" readonly>
                        </div>
                    </div>
                </div>

                <!-- Linked users from DB -->
                <template v-if="related_users && related_users.length">
                    <h6 class="text-muted mb-2">Linked User Accounts</h6>
                    <div v-for="u in related_users" :key="u.id" class="alert alert-success py-2">
                        <i class="fa fa-user"></i>
                        <strong>{{ u.name }} {{ u.surname }}</strong>
                        <span class="text-muted ml-2">{{ u.email }}</span>
                        <span class="badge badge-secondary ml-2">ID #{{ u.id }}</span>
                    </div>
                </template>
                <div v-else-if="order.is_custom" class="alert alert-secondary py-2">
                    <i class="fa fa-user-times"></i> No registered user found for this email
                </div>

                <hr>
            </template>

            <!-- Order Details -->
            <h6 class="text-muted mb-2">Order Details</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Shipping Method</label>
                        <input type="text" class="form-control" :value="shippingLabel" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <input type="text" class="form-control" :value="paymentLabel" readonly>
                    </div>
                </div>
            </div>

            <template v-if="order.is_custom && buyer_address && order.shiping === 'delivery'">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" :value="buyer_address.address || '—'" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" class="form-control" :value="buyer_address.city || '—'" readonly>
                        </div>
                    </div>
                </div>
            </template>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" :value="order.status" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status Updated</label>
                        <input type="text" class="form-control" :value="order.status_updating_data || '—'" readonly>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Products -->
            <h6 class="text-muted mb-2">Products</h6>
            <div v-for="(item, index) in order_product_items" :key="item.id" class="border rounded p-3 mb-2 bg-light">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <label class="text-muted d-block mb-1" v-if="index === 0">Product</label>
                        <input type="text" class="form-control" :value="item.product.url_title" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted d-block mb-1" v-if="index === 0">Option</label>
                        <input type="text" class="form-control" :value="item.option ? item.option.name : '—'" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="text-muted d-block mb-1" v-if="index === 0">Qty</label>
                        <input type="number" class="form-control" :value="item.quantity" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="text-muted d-block mb-1" v-if="index === 0">Price</label>
                        <input type="text" class="form-control" :value="(item.option ? item.option.price * item.quantity : 0) + ' ₾'" readonly>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2">
                <strong>Total: {{ total_price }} ₾</strong>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_order_detals_model: false,
            order: null,
            buyer_address: null,
            order_product_items: [],
            related_users: [],
            total_price: 0,
        }
    },
    computed: {
        shippingLabel() {
            const map = { self_delivery: 'Self-delivery', delivery: 'Delivery' }
            return map[this.order?.shiping] || this.order?.shiping || '—'
        },
        paymentLabel() {
            const map = {
                deliverd_payment: 'Payment on delivery',
                mony_transfer: 'Money transfer',
                online_payment: 'Online payment',
            }
            return map[this.order?.payment] || this.order?.payment || '—'
        },
        delivery_days() {
            const has_produced = this.order_product_items.some(
                item => item.product && item.product.sale_type === 'produced_by_order'
            )
            return has_produced ? '5-9' : '2-4'
        },
    },
    methods: {
        show_modal(order_id) {
            this.get_order_detals(order_id)
        },
        get_order_detals(order_id) {
            axios.get('get_order/get_order_detals/' + order_id)
                .then(response => {
                    this.order = response.data.order
                    this.order_product_items = response.data.order_products
                    this.buyer_address = response.data.buyer_address || null
                    this.related_users = response.data.related_users || []
                    this.calculate_total()
                    this.is_order_detals_model = true
                })
                .catch(error => console.log(error))
        },
        calculate_total() {
            this.total_price = this.order_product_items.reduce((sum, item) => {
                return sum + (item.option ? item.option.price * item.quantity : 0)
            }, 0)
        },
    }
}
</script>
