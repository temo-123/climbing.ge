<template>
    <StackModal
        v-model="visible"
        :title="$t('user.orders.detail_modal_title')"
        size="xl"
        @close="visible = false">
        <div v-if="loading" class="text-center py-4">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
        </div>

        <div v-else-if="order">

            <!-- Status badge -->
            <div class="mb-3 d-flex align-items-center flex-wrap" style="gap:6px">
                <span class="badge text-bg-secondary">{{ $t('user.orders.order_number', { id: order.id }) }}</span>
                <span :class="statusBadgeClass">{{ statusLabel }}</span>
                <span v-if="order.confirm == 1" class="badge text-bg-success">{{ $t('user.orders.confirmed_yes') }}</span>
                <span v-else class="badge text-bg-warning">{{ $t('user.orders.confirmed_no') }}</span>
                <span class="badge text-bg-info">
                    <i class="fa fa-clock-o"></i> {{ $t('user.orders.delivery_days', { days: delivery_days }) }}
                </span>
            </div>

            <div v-if="needs_confirmation" class="alert alert-danger py-2 mb-3">
                <i class="fa fa-exclamation-triangle"></i> {{ $t('user.orders.not_confirmed_warning') }}
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
                    {{ $t('user.orders.last_updated', { date: order.status_updating_data }) }}
                </small>
            </div>

            <hr>

            <!-- Delivery / pickup -->
            <h6 class="text-muted mb-2"><i class="fa fa-map-marker"></i> {{ $t('user.order_view.delivery_title') }}</h6>
            <OrderDeliveryCard v-if="delivery" :delivery="delivery" />

            <div class="mb-3">
                <small class="text-muted d-block">{{ $t('common.payment') }}</small>
                <strong>{{ paymentLabel }}</strong>
            </div>

            <!-- Products + price summary (server totals) -->
            <h6 class="text-muted mb-2"><i class="fa fa-shopping-bag"></i> {{ $t('user.orders.products') }}</h6>
            <OrderItemsTable :items="order_products" :totals="totals" :shipping_method="delivery ? delivery.method : 'delivery'" />

            <div class="mt-3 text-muted small">
                {{ $t('user.orders.ordered_on', { date: order.created_at ? order.created_at.slice(0, 10) : '—' }) }}
            </div>
        </div>

        <div v-else class="text-center text-muted py-4">{{ $t('user.orders.load_error') }}</div>

        <template #footer>
            <div class="modal-footer">
                <OrderInvoiceButton v-if="order" :order_id="order.id" mine class="me-auto" />
                <button type="button" class="btn btn-secondary" @click="visible = false">{{ $t('common.close') }}</button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import { deliveryDays } from '../../../../../services/coefficients.js'
import { payment_method_label } from '../../../../../services/shop/orderLabels.js'
import OrderDeliveryCard from './assets/OrderDeliveryCard.vue'
import OrderItemsTable from './assets/OrderItemsTable.vue'
import OrderInvoiceButton from './assets/OrderInvoiceButton.vue'

// Buyer's own order. Totals / delivery come from OrderService via get_my_purchase_detail.
export default {
    components: { OrderDeliveryCard, OrderItemsTable, OrderInvoiceButton },
    data() {
        return {
            visible: false,
            loading: false,
            order: null,
            payment_method: null,
            delivery: null,
            totals: {},
            order_products: [],
        }
    },
    computed: {
        statusSteps() {
            return [
                { key: 'pending',    label: this.$t('user.orders.status.pending'),   badge: 'badge-warning' },
                { key: 'treatment',  label: this.$t('user.orders.status.treatment'), badge: 'badge-info' },
                { key: 'shipped',    label: this.$t('user.orders.status.shipped'),   badge: 'badge-primary' },
                { key: 'delivered',  label: this.$t('user.orders.status.delivered'), badge: 'badge-success' },
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
            return payment_method_label(this.$t, this.payment_method)
        },
        has_produced_by_order() {
            return this.order_products.some(item => item.product && item.product.sale_type === 'produced_by_order')
        },
        // Email confirmation is only required for cash-on-delivery orders —
        // an online payment confirms itself.
        needs_confirmation() {
            const payment = this.order?.payment
            return this.order?.confirm != 1 && (payment === 'deliverd payment' || payment === 'deliverd_payment')
        },
        delivery_days() {
            return deliveryDays(this.has_produced_by_order)
        },
    },
    methods: {
        show_modal(order_id) {
            this.order = null
            this.delivery = null
            this.totals = {}
            this.order_products = []
            this.loading = true
            this.visible = true
            axios.get('get_order/get_my_purchase_detail/' + order_id, { params: { locale: this.$i18n?.locale === 'ka' ? 'ka' : 'en' } })
                .then(response => {
                    this.order = response.data.order
                    this.payment_method = response.data.payment_method
                    this.order_products = response.data.order_products || []
                    this.totals = response.data.totals || {}
                    this.delivery = response.data.delivery || null
                })
                .catch(error => console.log(error))
                .finally(() => this.loading = false)
        },
    }
}
</script>
