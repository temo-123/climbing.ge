<template>
    <div class="order-items">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ $t('common.product') }}</th>
                        <th>{{ $t('user.orders.option') }}</th>
                        <th class="text-end">{{ $t('user.order_view.unit_price') }}</th>
                        <th class="text-center">{{ $t('user.orders.qty') }}</th>
                        <th class="text-end">{{ $t('user.order_view.line_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                        <td class="fw-semibold">{{ item.title || (item.product && item.product.url_title) || '—' }}</td>
                        <td>{{ item.option_name || (item.option && item.option.name) || '—' }}</td>
                        <td class="text-end text-nowrap">
                            <s v-if="item.item_discount > 0 && item.list_price" class="text-muted small me-1">{{ money(item.list_price, item.currency) }}</s>
                            {{ money(item.item_price, item.currency) }}
                            <span v-if="item.item_discount > 0" class="badge text-bg-danger ms-1">−{{ +item.item_discount }}%</span>
                        </td>
                        <td class="text-center">× {{ item.quantity }}</td>
                        <td class="text-end text-nowrap fw-semibold">{{ money(item.total_price, item.currency) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Price summary (OrderService::totals — never re-added on the client) -->
        <div class="order-items__summary">
            <div class="order-items__row">
                <span>{{ $t('user.order_view.subtotal') }} <span class="text-muted">({{ $t('user.order_view.items_count', { count: totals.items_count }) }})</span></span>
                <span>{{ money(totals.subtotal, cur) }}</span>
            </div>
            <div v-if="totals.discount_percent > 0" class="order-items__row text-success">
                <span>{{ $t('user.order_view.discount', { percent: totals.discount_percent }) }}</span>
                <span>−{{ money(totals.discount_amount, cur) }}</span>
            </div>
            <div class="order-items__row">
                <span>
                    {{ $t('user.order_view.shipping') }}
                    <span class="text-muted">({{ shipping_method_label }})</span>
                </span>
                <span>{{ totals.shipping > 0 ? money(totals.shipping, cur) : $t('user.order_view.free') }}</span>
            </div>
            <div class="order-items__row order-items__row--total">
                <span>{{ $t('user.order_view.total') }}</span>
                <span>{{ money(totals.total, cur) }}</span>
            </div>
            <div v-if="totals.paid_amount !== null && totals.paid_amount !== undefined" class="order-items__row order-items__row--paid">
                <span><i class="fa fa-credit-card"></i> {{ $t('user.order_view.paid_online') }}</span>
                <span>{{ money(totals.paid_amount, cur) }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import { money, shipping_method_label } from '../../../../../../services/shop/orderLabels.js'

// Order lines + price summary. items = OrderService::line_items(), totals = OrderService::totals().
export default {
    props: {
        items: { type: Array, default: () => [] },
        totals: { type: Object, required: true },
        shipping_method: { type: String, default: 'delivery' },
    },
    computed: {
        cur() { return this.totals.currency || '₾' },
        shipping_method_label() { return shipping_method_label(this.$t, this.shipping_method) },
    },
    methods: { money },
}
</script>

<style scoped>
.order-items {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    overflow: hidden;
    margin-bottom: 1rem;
}
.order-items thead th {
    font-size: .75rem;
    text-transform: uppercase;
    color: var(--bs-secondary-color);
    background: var(--bs-tertiary-bg);
    white-space: nowrap;
}
.order-items th,
.order-items td {
    padding-left: .85rem;
    padding-right: .85rem;
}
.order-items__summary {
    margin-left: auto;
    max-width: 360px;
    padding: .75rem .85rem;
}
.order-items__row {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: .2rem 0;
}
.order-items__row--total {
    border-top: 2px solid var(--bs-emphasis-color);
    margin-top: .35rem;
    padding-top: .5rem;
    font-size: 1.15rem;
    font-weight: 700;
}
.order-items__row--paid {
    font-size: .875rem;
    color: var(--bs-secondary-color);
}
</style>
