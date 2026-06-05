<template>
    <StackModal
        v-model="visible"
        title="Order Details"
        @close="visible = false">
        <div v-if="loading" class="text-center py-4">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
        </div>

        <div v-else-if="order">

            <!-- Status badge -->
            <div class="mb-3 d-flex align-items-center flex-wrap" style="gap:6px">
                <span class="badge badge-secondary">Order #{{ order.id }}</span>
                <span :class="statusBadgeClass">{{ statusLabel }}</span>
                <span class="badge badge-info">
                    <i class="fa fa-clock-o"></i> Delivery: {{ delivery_days }} business days
                </span>
            </div>

            <!-- Status timeline -->
            <div class="mb-3">
                <div class="d-flex align-items-center" style="gap:8px">
                    <span v-for="(step, i) in statusSteps" :key="step.key"
                        class="d-flex align-items-center" style="gap:4px">
                        <span :class="['badge', order.status === step.key || statusIndex >= i ? step.badge : 'badge-light border']">
                            {{ step.label }}
                        </span>
                        <i v-if="i < statusSteps.length - 1" class="fa fa-arrow-right text-muted" style="font-size:10px"></i>
                    </span>
                </div>
                <small class="text-muted" v-if="order.status_updating_data">
                    Last updated: {{ order.status_updating_data }}
                </small>
            </div>

            <hr>

            <!-- Delivery address -->
            <h6 class="text-muted mb-2"><i class="fa fa-map-marker"></i> Delivery Address</h6>
            <div v-if="buyer_address" class="alert alert-secondary py-2 mb-3">
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
            <div v-else class="alert alert-warning py-2 mb-3">No delivery address recorded.</div>

            <!-- Payment & shipping -->
            <div class="row mb-3">
                <div class="col-6">
                    <small class="text-muted d-block">Payment</small>
                    <strong>{{ paymentLabel }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Shipping cost</small>
                    <strong>{{ order.shiping || 0 }} ₾</strong>
                </div>
            </div>

            <hr>

            <!-- Products -->
            <h6 class="text-muted mb-2"><i class="fa fa-shopping-bag"></i> Products</h6>
            <div v-for="item in order_products" :key="item.option && item.option.id"
                class="border rounded p-2 mb-2 bg-light">
                <div class="row align-items-center">
                    <div class="col-5">
                        <small class="text-muted d-block">Product</small>
                        <span>{{ item.product && item.product.url_title || '—' }}</span>
                    </div>
                    <div class="col-3">
                        <small class="text-muted d-block">Option</small>
                        <span>{{ item.option && item.option.name || '—' }}</span>
                    </div>
                    <div class="col-2">
                        <small class="text-muted d-block">Qty</small>
                        <span>{{ item.quantity }}</span>
                    </div>
                    <div class="col-2 text-right">
                        <small class="text-muted d-block">Total</small>
                        <strong>{{ item_total(item) }} ₾</strong>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2 flex-column align-items-end">
                <div v-if="order.discount && order.discount > 0" class="text-success">
                    Discount ({{ order.discount }}%): −{{ discount_amount }} ₾
                </div>
                <div>
                    <strong>Subtotal: {{ subtotal }} ₾</strong>
                </div>
                <div>
                    Shipping: {{ order.shiping || 0 }} ₾
                </div>
                <div class="mt-1" style="font-size:1.1em">
                    <strong>Total: {{ total_price }} ₾</strong>
                </div>
            </div>

            <div class="mt-3 text-muted small">
                Ordered on: {{ order.created_at ? order.created_at.slice(0, 10) : '—' }}
            </div>
        </div>

        <div v-else class="text-center text-muted py-4">Could not load order details.</div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            visible: false,
            loading: false,
            order: null,
            buyer_address: null,
            order_products: [],
        }
    },
    computed: {
        statusSteps() {
            return [
                { key: 'pending',    label: 'Pending',    badge: 'badge-warning' },
                { key: 'treatment',  label: 'Processing', badge: 'badge-info' },
                { key: 'shipped',    label: 'Shipped',    badge: 'badge-primary' },
                { key: 'delivered',  label: 'Delivered',  badge: 'badge-success' },
            ]
        },
        statusIndex() {
            return this.statusSteps.findIndex(s => s.key === this.order?.status)
        },
        statusLabel() {
            const step = this.statusSteps.find(s => s.key === this.order?.status)
            return step ? step.label : this.order?.status || '—'
        },
        statusBadgeClass() {
            const step = this.statusSteps.find(s => s.key === this.order?.status)
            return step ? `badge ${step.badge}` : 'badge badge-secondary'
        },
        paymentLabel() {
            const map = {
                'deliverd payment': 'Payment on delivery',
                'deliverd_payment': 'Payment on delivery',
                'online payment': 'Online payment',
                'mony_transfer': 'Money transfer',
            }
            return map[this.order?.payment] || this.order?.payment || '—'
        },
        has_produced_by_order() {
            return this.order_products.some(item => item.product && item.product.sale_type === 'produced_by_order')
        },
        delivery_days() {
            return this.has_produced_by_order ? '5-9' : '2-4'
        },
        subtotal() {
            return this.order_products.reduce((sum, item) => {
                return sum + this.item_total(item)
            }, 0).toFixed(2)
        },
        discount_amount() {
            if (!this.order?.discount) return 0
            return (parseFloat(this.subtotal) * this.order.discount / 100).toFixed(2)
        },
        total_price() {
            const shipping = parseFloat(this.order?.shiping) || 0
            return (parseFloat(this.subtotal) - parseFloat(this.discount_amount) + shipping).toFixed(2)
        },
    },
    methods: {
        show_modal(order_id) {
            this.order = null
            this.buyer_address = null
            this.order_products = []
            this.loading = true
            this.visible = true
            axios.get('get_order/get_my_purchase_detail/' + order_id)
                .then(response => {
                    this.order = response.data.order
                    this.order_products = response.data.order_products || []
                    this.buyer_address = response.data.buyer_address || null
                })
                .catch(error => console.log(error))
                .finally(() => this.loading = false)
        },
        item_total(item) {
            const price = item.option && item.option.price ? parseFloat(item.option.price) : 0
            return (price * (item.quantity || 1)).toFixed(2)
        },
    }
}
</script>
