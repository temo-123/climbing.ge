<template>
    <div class="order-delivery" :class="is_pickup ? 'order-delivery--pickup' : ''">
        <div class="order-delivery__head">
            <span class="order-delivery__icon"><i :class="'fa ' + (is_pickup ? 'fa-shopping-bag' : 'fa-truck')"></i></span>
            <div>
                <div class="order-delivery__title">
                    {{ is_pickup ? $t('user.order_view.method_self_delivery') : $t('user.order_view.method_delivery') }}
                </div>
                <div class="order-delivery__cost">
                    <template v-if="is_pickup">{{ $t('user.order_view.no_shipping_cost') }}</template>
                    <template v-else>
                        {{ $t('user.order_view.shipping') }}:
                        <strong>{{ delivery.cost > 0 ? money(delivery.cost) : $t('user.order_view.free') }}</strong>
                    </template>
                </div>
            </div>
            <span v-if="delivery.label" class="badge text-bg-light ms-auto">{{ delivery.label }}</span>
        </div>

        <div class="order-delivery__grid">
            <!-- Recipient -->
            <div class="order-delivery__col">
                <div class="order-delivery__label">{{ $t('user.order_view.recipient') }}</div>
                <div class="order-delivery__value fw-semibold">{{ delivery.recipient || '—' }}</div>
                <div v-if="delivery.phone" class="order-delivery__value">
                    <i class="fa fa-phone text-muted"></i> <a :href="'tel:' + delivery.phone">{{ delivery.phone }}</a>
                </div>
                <div v-if="delivery.email" class="order-delivery__value text-break">
                    <i class="fa fa-envelope-o text-muted"></i> <a :href="'mailto:' + delivery.email">{{ delivery.email }}</a>
                </div>
            </div>

            <!-- Where -->
            <div class="order-delivery__col">
                <template v-if="is_pickup">
                    <div class="order-delivery__label">{{ $t('user.order_view.pickup_from') }}</div>
                    <div class="order-delivery__value fw-semibold">{{ delivery.pickup_from || '—' }}</div>
                </template>
                <template v-else-if="has_address">
                    <div class="order-delivery__label">{{ $t('user.order_view.address') }}</div>
                    <div v-if="delivery.street" class="order-delivery__street">{{ delivery.street }}</div>
                    <div v-if="delivery.details.length" class="order-delivery__details">
                        <span v-for="d in delivery.details" :key="d.key" class="order-delivery__chip">
                            {{ $t('user.order_view.' + d.key) }}: <strong>{{ d.value }}</strong>
                        </span>
                    </div>
                    <div class="order-delivery__value">{{ city_line || '—' }}</div>
                    <div class="order-delivery__actions">
                        <a :href="map_url" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-map-marker"></i> {{ $t('user.order_view.open_map') }}
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary" @click="copy_address">
                            <i class="fa fa-clipboard"></i> {{ copied ? $t('user.order_view.address_copied') : $t('user.order_view.copy_address') }}
                        </button>
                    </div>
                </template>
                <div v-else class="alert alert-warning py-2 mb-0">{{ $t('user.order_view.no_address') }}</div>
            </div>
        </div>
    </div>
</template>

<script>
import { money } from '../../../../../../services/shop/orderLabels.js'

// Who receives the order and where — props.delivery is OrderService::delivery().
export default {
    props: {
        delivery: { type: Object, required: true },
    },
    data() {
        return { copied: false }
    },
    computed: {
        is_pickup() { return this.delivery.method === 'self_delivery' },
        has_address() {
            const d = this.delivery
            return !!(d.street || d.city || d.region || (d.details && d.details.length))
        },
        city_line() {
            const d = this.delivery
            const region = d.region && d.region !== d.city ? d.region : null
            return [d.city, region, d.zip, d.country].filter(Boolean).join(', ')
        },
        full_address() {
            const details = (this.delivery.details || []).map(d => this.$t('user.order_view.' + d.key) + ' ' + d.value)
            return [this.delivery.street, ...details, this.city_line].filter(Boolean).join(', ')
        },
        map_url() {
            return this.delivery.map || ('https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(this.full_address))
        },
    },
    methods: {
        money,
        copy_address() {
            const text = [this.delivery.recipient, this.delivery.phone, this.full_address].filter(Boolean).join('\n')
            navigator.clipboard?.writeText(text).then(() => {
                this.copied = true
                setTimeout(() => { this.copied = false }, 2000)
            })
        },
    },
}
</script>

<style scoped>
.order-delivery {
    border: 1px solid var(--bs-border-color);
    border-left: 4px solid var(--cg-accent);
    border-radius: var(--bs-border-radius);
    padding: 1rem;
    margin-bottom: 1rem;
    background: var(--bs-body-bg);
}
.order-delivery--pickup {
    border-left-color: var(--bs-secondary);
}
.order-delivery__head {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: .85rem;
}
.order-delivery__icon {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bs-tertiary-bg);
    color: var(--cg-accent);
    font-size: 1.1rem;
}
.order-delivery__title {
    font-weight: 600;
    font-size: 1.05rem;
}
.order-delivery__cost {
    font-size: .875rem;
    color: var(--bs-secondary-color);
}
.order-delivery__grid {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 1rem;
}
@media (max-width: 575.98px) {
    .order-delivery__grid { grid-template-columns: 1fr; }
}
.order-delivery__label {
    font-size: .75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: var(--bs-secondary-color);
    margin-bottom: .25rem;
}
.order-delivery__value {
    font-size: .95rem;
    margin-bottom: .15rem;
}
.order-delivery__street {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: .35rem;
}
.order-delivery__details {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    margin-bottom: .35rem;
}
.order-delivery__chip {
    font-size: .85rem;
    padding: .15rem .5rem;
    border-radius: 999px;
    background: var(--bs-tertiary-bg);
    border: 1px solid var(--bs-border-color);
}
.order-delivery__actions {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    margin-top: .5rem;
}
</style>
