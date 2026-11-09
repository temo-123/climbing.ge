<template>
    <div class="col-md-12">
        <div class="container pt-3">
            <CheckoutSteps :current="3" />
        </div>

        <div class="placed">
            <!-- Success header -->
            <div class="placed__hero">
                <span class="placed__check"><i class="fa fa-check" aria-hidden="true"></i></span>
                <h1 class="placed__title">
                    {{ user && user.name ? $t('user.order_placed.thanks_name', { name: user.name }) : $t('user.order_placed.thanks') }}
                </h1>
                <p class="placed__lead">
                    <template v-if="order">{{ $t('user.order_placed.order_received', { id: order.id }) }}</template>
                    <template v-else>{{ $t('user.order_placed.order_received_generic') }}</template>
                </p>
            </div>

            <!-- Progress -->
            <ol class="placed__steps" :aria-label="$t('user.order_placed.progress')">
                <li v-for="(step, i) in steps" :key="step.key" :class="'is-' + step.state">
                    <span class="placed__dot">
                        <i v-if="step.state === 'done'" class="fa fa-check" aria-hidden="true"></i>
                        <template v-else>{{ i + 1 }}</template>
                    </span>
                    <span class="placed__step-label">{{ step.label }}</span>
                </li>
            </ol>

            <!-- One more step: confirm by e-mail (cash-on-delivery only) -->
            <div v-if="needs_confirmation" class="placed__action">
                <div class="placed__action-icon"><i class="fa fa-envelope-open-o" aria-hidden="true"></i></div>
                <div class="placed__action-body">
                    <h2 class="placed__action-title">{{ $t('user.order_placed.confirm_title') }}</h2>
                    <p class="mb-1" v-html="confirm_text"></p>
                    <p class="small text-muted mb-2">{{ $t('user.order_placed.confirm_hint') }}</p>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" :disabled="resend.loading || resend.wait > 0" @click="resend_mail">
                            <span v-if="resend.loading" class="spinner-border spinner-border-sm"></span>
                            <i v-else class="fa fa-repeat" aria-hidden="true"></i>
                            {{ resend.wait > 0 ? $t('user.order_placed.resend_wait', { s: resend.wait }) : $t('user.order_placed.resend_btn') }}
                        </button>
                        <span v-if="resend.sent" class="small text-success"><i class="fa fa-check"></i> {{ $t('user.order_placed.resend_sent') }}</span>
                        <span v-if="resend.error" class="small text-danger">{{ resend.error }}</span>
                    </div>
                </div>
            </div>
            <div v-else-if="order && order.confirm == 1" class="placed__confirmed">
                <i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('user.order_placed.already_confirmed') }}
            </div>

            <!-- Order summary -->
            <div v-if="loading" class="text-center text-muted py-4">
                <span class="spinner-border spinner-border-sm text-primary"></span> {{ $t('common.loading') }}
            </div>

            <div v-else-if="order" class="row g-3 placed__summary">
                <div class="col-lg-7">
                    <h3 class="placed__section">{{ $t('user.order_placed.your_order') }}</h3>
                    <OrderItemsTable :items="order_products" :totals="totals" :shipping_method="delivery ? delivery.method : 'delivery'" />
                </div>
                <div class="col-lg-5">
                    <h3 class="placed__section">{{ $t('user.order_view.delivery_title') }}</h3>
                    <OrderDeliveryCard v-if="delivery" :delivery="delivery" />

                    <div class="placed__facts">
                        <div>
                            <span class="placed__fact-label">{{ $t('common.payment') }}</span>
                            <span class="fw-semibold">{{ payment_label }}</span>
                        </div>
                        <div>
                            <span class="placed__fact-label">{{ $t('user.order_placed.estimated_delivery') }}</span>
                            <span class="fw-semibold">{{ $t('user.orders.delivery_days', { days: delivery_days }) }}</span>
                        </div>
                    </div>

                    <OrderInvoiceButton :order_id="order.id" mine class="mt-3" />
                </div>
            </div>

            <!-- Next -->
            <div class="placed__actions">
                <router-link :to="{ name: 'userPurchases' }" class="btn btn-primary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> {{ $t('user.order_placed.my_purchases') }}
                </router-link>
                <a :href="shop_url" class="btn btn-outline-secondary">
                    <i class="fa fa-shopping-bag" aria-hidden="true"></i> {{ $t('user.order_placed.continue_shopping') }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import CheckoutSteps from './items/CheckoutSteps.vue'
    import OrderItemsTable from '../../../items/modal/orders/assets/OrderItemsTable.vue'
    import OrderDeliveryCard from '../../../items/modal/orders/assets/OrderDeliveryCard.vue'
    import OrderInvoiceButton from '../../../items/modal/orders/assets/OrderInvoiceButton.vue'
    import { get_auth_user } from '../../../../../services/authUser.js'
    import { deliveryDays } from '../../../../../services/coefficients.js'
    import { payment_method_label } from '../../../../../services/shop/orderLabels.js'

    // Shown right after a cart checkout (orderDeclorationPageComponent →
    // { name: 'confirmOrder', query: { order } }). Loads the buyer's own order
    // (get_my_purchase_detail) to show what was ordered, where it goes and the
    // e-mail confirmation step a cash-on-delivery order still needs.
    export default {
        components: { CheckoutSteps, OrderItemsTable, OrderDeliveryCard, OrderInvoiceButton },
        data() {
            return {
                user: null,
                order: null,
                order_products: [],
                totals: {},
                delivery: null,
                payment_method: null,
                loading: false,
                resend: { loading: false, sent: false, error: null, wait: 0 },
                timer: null,
            }
        },
        computed: {
            order_id() {
                return parseInt(this.$route.query.order, 10) || null
            },
            needs_confirmation() {
                // Known order → only unconfirmed cash orders; old links without an id → the generic notice.
                if (!this.order) return !this.loading
                return this.order.confirm != 1 && this.payment_method === 'cash_on_delivery'
            },
            confirm_text() {
                const email = this.user?.email ? '<strong>' + this.escape(this.user.email) + '</strong>' : this.$t('user.order_placed.your_email')
                return this.$t('user.order_placed.confirm_text', { email })
            },
            steps() {
                const confirmed = this.order && this.order.confirm == 1
                return [
                    { key: 'placed',    label: this.$t('user.order_placed.step_placed'),    state: 'done' },
                    { key: 'confirm',   label: this.$t('user.order_placed.step_confirm'),   state: confirmed ? 'done' : 'current' },
                    { key: 'prepare',   label: this.$t('user.order_placed.step_prepare'),   state: confirmed ? 'current' : 'todo' },
                    { key: 'shipped',   label: this.$t('user.order_placed.step_shipped'),   state: 'todo' },
                    { key: 'delivered', label: this.$t('user.order_placed.step_delivered'), state: 'todo' },
                ]
            },
            payment_label() {
                return payment_method_label(this.$t, this.payment_method)
            },
            delivery_days() {
                return deliveryDays(this.order_products.some(i => i.product && i.product.sale_type === 'produced_by_order'))
            },
            shop_url() {
                return (process.env.MIX_APP_SSH || 'https://') + (process.env.MIX_SHOP_URL || '')
            },
        },
        mounted() {
            // This page has no left menu — drop the panel offset (guarded: the
            // header may not be rendered on every layout).
            document.body.style.marginLeft = '0'
            const nav = document.querySelector('.admin_page_header_navbar')
            if (nav) nav.style.marginLeft = '0'

            if (!this.$route.params.order_decloration && !this.order_id) {
                this.$router.push({ name: 'userPurchases' })
                return
            }

            get_auth_user().then(r => { this.user = r.data }).catch(() => {})
            if (this.order_id) this.load_order()
        },
        beforeUnmount() {
            clearInterval(this.timer)
        },
        methods: {
            load_order() {
                this.loading = true
                axios.get('get_order/get_my_purchase_detail/' + this.order_id, { params: { locale: this.$i18n?.locale === 'ka' ? 'ka' : 'en' } })
                    .then(r => {
                        this.order = r.data.order
                        this.order_products = r.data.order_products || []
                        this.totals = r.data.totals || {}
                        this.delivery = r.data.delivery || null
                        this.payment_method = r.data.payment_method
                    })
                    .catch(() => { this.order = null })
                    .finally(() => { this.loading = false })
            },
            resend_mail() {
                if (!this.order) return
                this.resend = { loading: true, sent: false, error: null, wait: 0 }
                axios.post('set_order/resend_order_confirm/' + this.order.id)
                    .then(() => { this.resend.sent = true; this.start_wait(60) })
                    .catch(e => {
                        const code = e.response?.data?.code
                        if (code === 'too_soon') { this.start_wait(60); return }
                        if (code === 'already_confirmed') { this.load_order(); return }
                        this.resend.error = this.$t('user.order_placed.resend_failed')
                    })
                    .finally(() => { this.resend.loading = false })
            },
            start_wait(seconds) {
                clearInterval(this.timer)
                this.resend.wait = seconds
                this.timer = setInterval(() => {
                    this.resend.wait--
                    if (this.resend.wait <= 0) clearInterval(this.timer)
                }, 1000)
            },
            escape(s) {
                return String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]))
            },
        },
    }
</script>

<style scoped>
.placed {
    max-width: 1100px;
    margin: 0 auto;
    padding: 1rem 0 2.5rem;
}
.placed__hero {
    text-align: center;
    margin: .5rem 0 1.75rem;
}
.placed__check {
    width: 72px;
    height: 72px;
    margin: 0 auto .85rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bs-success-bg-subtle);
    color: var(--bs-success);
    font-size: 2rem;
    animation: placed-pop .35s ease-out;
}
@keyframes placed-pop {
    from { transform: scale(.6); opacity: 0; }
    to   { transform: scale(1);  opacity: 1; }
}
@media (prefers-reduced-motion: reduce) {
    .placed__check { animation: none; }
}
.placed__title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: .35rem;
}
.placed__lead {
    color: var(--bs-secondary-color);
    font-size: 1.05rem;
    margin: 0;
}

/* progress */
.placed__steps {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0 auto 1.5rem;
    max-width: 760px;
    counter-reset: none;
}
.placed__steps li {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .4rem;
    position: relative;
    text-align: center;
    font-size: .8rem;
    color: var(--bs-secondary-color);
}
.placed__steps li:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 15px;
    left: calc(50% + 18px);
    right: calc(-50% + 18px);
    height: 2px;
    background: var(--bs-border-color);
}
.placed__steps li.is-done:not(:last-child)::after {
    background: var(--bs-success);
}
.placed__dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border: 2px solid var(--bs-border-color);
    background: var(--bs-body-bg);
    position: relative;
    z-index: 1;
}
.is-done .placed__dot {
    background: var(--bs-success);
    border-color: var(--bs-success);
    color: #fff;
}
.is-current .placed__dot {
    border-color: var(--cg-accent);
    color: var(--cg-accent);
    box-shadow: 0 0 0 4px color-mix(in srgb, var(--cg-accent) 18%, transparent);
}
.is-current .placed__step-label {
    color: var(--bs-emphasis-color);
    font-weight: 600;
}
@media (max-width: 575.98px) {
    .placed__step-label { font-size: .7rem; }
}

/* confirm-by-email */
.placed__action {
    display: flex;
    gap: 1rem;
    max-width: 760px;
    margin: 0 auto 1.75rem;
    padding: 1.1rem 1.25rem;
    border: 1px solid var(--bs-warning-border-subtle);
    border-left: 4px solid var(--bs-warning);
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-warning-bg-subtle);
}
.placed__action-icon {
    font-size: 1.6rem;
    color: var(--bs-warning-text-emphasis);
    line-height: 1;
    padding-top: .15rem;
}
.placed__action-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: .35rem;
}
.placed__confirmed {
    max-width: 760px;
    margin: 0 auto 1.75rem;
    padding: .75rem 1rem;
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-success-bg-subtle);
    color: var(--bs-success-text-emphasis);
    font-weight: 600;
    text-align: center;
}

/* summary */
.placed__section {
    font-size: .85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: var(--bs-secondary-color);
    margin-bottom: .5rem;
}
.placed__facts {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
    padding: .85rem 1rem;
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
}
.placed__fact-label {
    display: block;
    font-size: .75rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--bs-secondary-color);
}
.placed__actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .75rem;
    margin-top: 2rem;
}
</style>
