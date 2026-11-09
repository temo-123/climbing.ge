<template>
    <aside class="co-sum" :class="{ 'is-loading': loading }">
        <h2 class="co-sum__title">{{ $t('user.co.summary_title') }}</h2>

        <template v-if="quote">
            <!-- items (compact) -->
            <ul v-if="show_items" class="co-sum__items">
                <li v-for="l in quote.lines" :key="l.option_id">
                    <span class="co-sum__thumb">
                        <img :src="l.image ? '/public/images/product_option_img/' + l.image : '/public/images/site_img/shop_demo.jpg'" alt="" loading="lazy">
                        <span class="co-sum__qty">{{ l.quantity }}</span>
                    </span>
                    <span class="co-sum__name">
                        <span class="d-block text-truncate">{{ l.title }}</span>
                        <small class="text-muted">{{ l.option_name }}</small>
                    </span>
                    <span class="co-sum__price">
                        <s v-if="l.item_discount > 0" class="d-block small text-muted fw-normal">{{ money(l.list_price * l.quantity) }}</s>
                        {{ money(l.line_total) }}
                    </span>
                </li>
            </ul>

            <dl class="co-sum__rows">
                <dt>{{ $t('user.co.subtotal', { count: quote.items_count }) }}</dt>
                <dd>{{ money(quote.subtotal) }}</dd>

                <template v-if="quote.sale_savings > 0">
                    <dt class="text-danger small">{{ $t('user.co.sale_savings') }}</dt>
                    <dd class="text-danger small">−{{ money(quote.sale_savings) }}</dd>
                </template>

                <template v-if="quote.discount.percent > 0">
                    <dt class="text-success">
                        {{ quote.discount.source === 'code' ? $t('user.co.discount_code', { percent: quote.discount.percent }) : $t('user.co.discount_partner', { percent: quote.discount.percent }) }}
                    </dt>
                    <dd class="text-success">−{{ money(quote.discount.amount) }}</dd>
                </template>

                <dt>{{ $t('user.co.shipping') }}<small v-if="quote.region" class="text-muted"> · {{ quote.region.name }}</small></dt>
                <dd>
                    <template v-if="quote.shipping === null"><span class="text-muted">{{ $t('user.co.shipping_pick_address') }}</span></template>
                    <template v-else-if="quote.shipping === 0"><span class="text-success fw-semibold">{{ $t('user.order_view.free') }}</span></template>
                    <template v-else>{{ money(quote.shipping) }}</template>
                </dd>
            </dl>

            <!-- free-shipping progress -->
            <div v-if="quote.region && quote.region.free_after && !quote.region.below_min" class="co-sum__free">
                <div class="co-sum__bar" role="progressbar" :aria-valuenow="free_progress" aria-valuemin="0" aria-valuemax="100">
                    <span :style="{ width: free_progress + '%' }"></span>
                </div>
                <small v-if="quote.region.is_free" class="text-success"><i class="fa fa-check" aria-hidden="true"></i> {{ $t('user.co.free_reached') }}</small>
                <small v-else-if="quote.region.missing_for_free > 0">{{ $t('user.co.free_missing', { amount: money(quote.region.missing_for_free) }) }}</small>
            </div>

            <div v-if="quote.region && quote.region.below_min" class="co-sum__warn">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                {{ $t('user.co.min_missing', { region: quote.region.name, min: money(quote.region.min_order), missing: money(quote.region.missing_for_min) }) }}
            </div>

            <div class="co-sum__total">
                <span>{{ $t('user.co.total') }}</span>
                <span>{{ money(quote.total) }}</span>
            </div>

            <p v-if="delivery_days" class="co-sum__eta">
                <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $t('user.orders.delivery_days', { days: delivery_days }) }}
                <span v-if="quote.made_to_order" class="d-block small text-muted">{{ $t('user.checkout.includes_made_to_order') }}</span>
            </p>
        </template>

        <div v-else class="text-center text-muted py-3">
            <span class="spinner-border spinner-border-sm"></span>
        </div>

        <slot />
    </aside>
</template>

<script>
// Order summary sidebar for both checkout steps. `quote` is the server's
// OrderPlacementService::quote_website() result — shown as-is.
export default {
    props: {
        quote: { type: Object, default: null },
        loading: { type: Boolean, default: false },
        show_items: { type: Boolean, default: true },
        delivery_days: { type: [Number, String], default: null },
    },
    computed: {
        free_progress() {
            const r = this.quote?.region
            if (!r || !r.free_after) return 0
            return Math.min(100, Math.round(this.quote.subtotal / r.free_after * 100))
        },
    },
    methods: {
        money(v) {
            return (Number(v) || 0).toFixed(2) + ' ' + (this.quote?.currency || '₾')
        },
    },
}
</script>

<style scoped>
.co-sum {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-body-bg);
    padding: 1.1rem 1.15rem;
    transition: opacity .15s;
}
@media (min-width: 992px) {
    .co-sum { position: sticky; top: 1rem; }
}
.co-sum.is-loading { opacity: .6; }
.co-sum__title {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: .85rem;
}
.co-sum__items {
    list-style: none;
    padding: 0 0 .75rem;
    margin: 0 0 .75rem;
    border-bottom: 1px solid var(--bs-border-color);
    max-height: 260px;
    overflow-y: auto;
}
.co-sum__items li {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .35rem 0;
}
.co-sum__thumb {
    position: relative;
    flex-shrink: 0;
}
.co-sum__thumb img {
    width: 46px;
    height: 46px;
    object-fit: cover;
    border-radius: var(--bs-border-radius);
    border: 1px solid var(--bs-border-color);
}
.co-sum__qty {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 20px;
    height: 20px;
    padding: 0 5px;
    border-radius: 10px;
    background: var(--bs-secondary);
    color: #fff;
    font-size: .7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.co-sum__name {
    flex: 1;
    min-width: 0;
    font-size: .88rem;
    line-height: 1.25;
}
.co-sum__price {
    text-align: right;
    font-size: .88rem;
    font-weight: 600;
    white-space: nowrap;
}
.co-sum__rows {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: .35rem .75rem;
    margin: 0 0 .75rem;
    font-size: .92rem;
}
.co-sum__rows dt { font-weight: 400; }
.co-sum__rows dd { margin: 0; text-align: right; white-space: nowrap; }
.co-sum__free { margin-bottom: .75rem; }
.co-sum__bar {
    height: 6px;
    border-radius: 3px;
    background: var(--bs-tertiary-bg);
    overflow: hidden;
    margin-bottom: .3rem;
}
.co-sum__bar span {
    display: block;
    height: 100%;
    background: var(--cg-accent);
    border-radius: 3px;
    transition: width .3s;
}
.co-sum__warn {
    font-size: .85rem;
    padding: .55rem .7rem;
    border-radius: var(--bs-border-radius);
    background: var(--bs-danger-bg-subtle);
    color: var(--bs-danger-text-emphasis);
    margin-bottom: .75rem;
}
.co-sum__total {
    display: flex;
    justify-content: space-between;
    font-size: 1.25rem;
    font-weight: 700;
    padding-top: .65rem;
    border-top: 2px solid var(--bs-emphasis-color);
    margin-bottom: .6rem;
}
.co-sum__eta {
    font-size: .85rem;
    color: var(--bs-secondary-color);
    margin-bottom: .85rem;
}
</style>
