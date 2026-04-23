<template>
    <StackModal
        v-model="is_order_detals_model"
        title="Order Details"
        @close="is_order_detals_model=false">
        <div v-if="order">

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
    },
    methods: {
        show_modal(order_id) {
            this.get_order_detals(order_id)
        },
        get_order_detals(order_id) {
            axios.get('/get_order/get_order_detals/' + order_id)
                .then(response => {
                    this.order = response.data.order
                    this.order_product_items = response.data.order_products
                    this.buyer_address = response.data.buyer_address || null
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
