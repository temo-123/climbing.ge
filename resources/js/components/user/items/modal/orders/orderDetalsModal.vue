<template>
    <StackModal
        v-model="is_order_detals_model"
        :title="order ? $t('user.orders.order_number', { id: order.id }) : $t('admin.orders.order_details_modal_title')"
        size="xl"
        @close="is_order_detals_model=false"
    >
        <div v-if="order">

            <!-- Order type / status strip -->
            <div class="order-strip">
                <div class="order-strip__type">
                    <span class="badge" :class="order_type === 'manual' ? 'text-bg-warning' : 'text-bg-primary'">
                        <i :class="'fa ' + (order_type === 'manual' ? 'fa-user-secret' : 'fa-globe')"></i>
                        {{ type_label }}
                    </span>
                    <div class="small text-muted mt-1">{{ type_hint }}</div>
                </div>
                <div class="order-strip__facts">
                    <div>
                        <div class="order-strip__label">{{ $t('admin.orders.status_label') }}</div>
                        <div class="fw-semibold">{{ order.status || '—' }}</div>
                        <div v-if="order.status_updating_data" class="small text-muted">{{ format_date(order.status_updating_data) }}</div>
                    </div>
                    <div>
                        <div class="order-strip__label">{{ $t('admin.orders.payment_method_label') }}</div>
                        <div class="fw-semibold">{{ payment_label }}</div>
                        <span v-if="order.confirm == 1" class="badge text-bg-success">{{ $t('admin.orders.confirmed_yes') }}</span>
                        <span v-else class="badge text-bg-secondary">{{ $t('admin.orders.confirmed_no') }}</span>
                    </div>
                    <div>
                        <div class="order-strip__label">{{ $t('common.date') }}</div>
                        <div class="fw-semibold">{{ format_date(order.created_at) }}</div>
                        <div class="small text-muted"><i class="fa fa-clock-o"></i> {{ $t('admin.orders.delivery_days_prefix', { days: delivery_days }) }}</div>
                    </div>
                    <div>
                        <div class="order-strip__label">{{ $t('user.order_view.total') }}</div>
                        <div class="order-strip__total">{{ money(totals.total, totals.currency) }}</div>
                    </div>
                </div>
            </div>

            <!-- Delivery / pickup -->
            <h6 class="order-section-title"><i class="fa fa-map-marker"></i> {{ $t('user.order_view.delivery_title') }}</h6>
            <OrderDeliveryCard v-if="delivery" :delivery="delivery" />

            <!-- Buyer account (website orders) -->
            <template v-if="order_type === 'website'">
                <h6 class="order-section-title"><i class="fa fa-user"></i> {{ $t('user.order_view.buyer_account') }}</h6>
                <div v-if="buyer" class="order-person">
                    <strong>{{ buyer.name }} {{ buyer.surname }}</strong>
                    <span class="badge text-bg-light">#{{ buyer.id }}</span>
                    <span v-if="buyer.email" class="text-muted">{{ buyer.email }}</span>
                    <span v-if="buyer.phone_number" class="text-muted">{{ buyer.phone_number }}</span>
                    <span v-if="buyer.city || buyer.country" class="text-muted">{{ [buyer.city, buyer.country].filter(Boolean).join(', ') }}</span>
                </div>
                <div v-else class="alert alert-secondary py-2">
                    <i class="fa fa-user-times"></i> {{ $t('admin.orders.no_registered_user_found') }}
                </div>
            </template>

            <!-- Linked user accounts (manual orders) -->
            <template v-else>
                <h6 class="order-section-title"><i class="fa fa-link"></i> {{ $t('admin.orders.linked_user_accounts_title') }}</h6>
                <template v-if="related_users.length">
                    <div v-for="u in related_users" :key="u.id" class="order-person">
                        <strong>{{ u.name }} {{ u.surname }}</strong>
                        <span class="badge text-bg-light">#{{ u.id }}</span>
                        <span class="text-muted">{{ u.email }}</span>
                    </div>
                </template>
                <div v-else class="alert alert-secondary py-2">
                    <i class="fa fa-user-times"></i> {{ $t('admin.orders.no_registered_user_found') }}
                </div>
            </template>

            <!-- Products + price summary -->
            <h6 class="order-section-title"><i class="fa fa-shopping-bag"></i> {{ $t('admin.orders.products_title') }}</h6>
            <OrderItemsTable :items="order_product_items" :totals="totals" :shipping_method="delivery ? delivery.method : 'delivery'" />
        </div>

        <template #footer>
            <div class="modal-footer">
                <OrderInvoiceButton v-if="order" :order_id="order.id" class="me-auto" />
                <button type="button" class="btn btn-secondary" @click="is_order_detals_model = false">{{ $t('common.close') }}</button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import { deliveryDays } from '../../../../../services/coefficients.js'
import { money, order_type_label, payment_method_label } from '../../../../../services/shop/orderLabels.js'
import OrderDeliveryCard from './assets/OrderDeliveryCard.vue'
import OrderItemsTable from './assets/OrderItemsTable.vue'
import OrderInvoiceButton from './assets/OrderInvoiceButton.vue'

// Admin order details. Everything money / address related comes ready from
// OrderController::build_order_detail → OrderService (totals, delivery, line items).
export default {
    components: { OrderDeliveryCard, OrderItemsTable, OrderInvoiceButton },
    data() {
        return {
            is_order_detals_model: false,
            order: null,
            order_type: 'website',
            payment_method: null,
            buyer: null,
            delivery: null,
            totals: {},
            order_product_items: [],
            related_users: [],
        }
    },
    computed: {
        type_label() { return order_type_label(this.$t, this.order_type) },
        type_hint() {
            return this.order_type === 'manual' ? this.$t('user.order_view.type_manual_hint') : this.$t('user.order_view.type_website_hint')
        },
        payment_label() { return payment_method_label(this.$t, this.payment_method) },
        delivery_days() {
            const has_produced = this.order_product_items.some(
                item => item.product && item.product.sale_type === 'produced_by_order'
            )
            return deliveryDays(has_produced)
        },
    },
    methods: {
        money,
        show_modal(order_id) {
            this.get_order_detals(order_id)
        },
        get_order_detals(order_id) {
            axios.get('get_order/get_order_detals/' + order_id, { params: { locale: this.$i18n?.locale === 'ka' ? 'ka' : 'en' } })
                .then(response => {
                    const d = response.data
                    this.order = d.order
                    this.order_type = d.order_type || (d.order?.is_custom ? 'manual' : 'website')
                    this.payment_method = d.payment_method
                    this.order_product_items = d.order_products || []
                    this.totals = d.totals || {}
                    this.delivery = d.delivery || null
                    this.buyer = d.buyer || null
                    this.related_users = d.related_users || []
                    this.is_order_detals_model = true
                })
                .catch(error => console.log(error))
        },
        format_date(value) {
            if (!value) return '—'
            return String(value).replace('T', ' ').substring(0, 16)
        },
    }
}
</script>

<style scoped>
.order-strip {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem 1.5rem;
    justify-content: space-between;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.order-strip__type {
    max-width: 320px;
}
.order-strip__type .badge {
    font-size: .9rem;
}
.order-strip__facts {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem 1.75rem;
}
.order-strip__label {
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: var(--bs-secondary-color);
}
.order-strip__total {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--cg-accent);
}
.order-section-title {
    font-size: .85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: var(--bs-secondary-color);
    margin: 0 0 .5rem;
}
.order-person {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .5rem;
    padding: .6rem .85rem;
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    margin-bottom: 1rem;
}
</style>
