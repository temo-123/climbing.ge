<template>
    <div class="container co">
        <CheckoutSteps :current="2" />

        <div v-if="order_error" class="alert alert-danger d-flex gap-2">
            <i class="fa fa-exclamation-triangle mt-1" aria-hidden="true"></i>
            <div>
                <strong>{{ $t('user.co.place_failed') }}</strong>
                <div>{{ order_error }}</div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Delivery + payment, each with a way back to change it -->
                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <section class="co-card h-100 mb-0">
                            <header class="co-card__head">
                                <i class="fa fa-truck text-muted" aria-hidden="true"></i>
                                <h2>{{ $t('user.co.deliver_to') }}</h2>
                                <router-link :to="{ name: 'orderPayment' }" class="co-card__edit small">{{ $t('user.co.change') }}</router-link>
                            </header>
                            <template v-if="address">
                                <div class="fw-semibold">
                                    {{ address.name }}
                                    <span v-if="user" class="text-muted fw-normal">· {{ [user.name, user.surname].filter(Boolean).join(' ') }}</span>
                                </div>
                                <div class="co-addr__street">{{ [address.strit, address.number].filter(Boolean).join(' ') }}</div>
                                <div v-if="address_details" class="small">{{ address_details }}</div>
                                <div class="small text-muted">{{ [address.city, quote && quote.region && quote.region.name, address.zip_code].filter(Boolean).join(', ') }}</div>
                                <div v-if="user && user.phone_number" class="small text-muted mt-1"><i class="fa fa-phone"></i> {{ user.phone_number }}</div>
                            </template>
                            <div v-else class="text-muted"><span class="spinner-border spinner-border-sm"></span></div>
                        </section>
                    </div>
                    <div class="col-md-5">
                        <section class="co-card h-100 mb-0">
                            <header class="co-card__head">
                                <i class="fa fa-credit-card text-muted" aria-hidden="true"></i>
                                <h2>{{ $t('user.checkout.payment_method') }}</h2>
                                <router-link :to="{ name: 'orderPayment' }" class="co-card__edit small">{{ $t('user.co.change') }}</router-link>
                            </header>
                            <div class="fw-semibold">
                                <template v-if="is_online">{{ $t('user.checkout.pay_online') }} <span class="co-tbc">TBC</span></template>
                                <template v-else>{{ $t('user.checkout.pay_on_delivery') }}</template>
                            </div>
                            <div class="small text-muted">{{ is_online ? $t('user.co.online_desc') : $t('user.co.cod_desc') }}</div>
                        </section>
                    </div>
                </div>

                <!-- Items -->
                <section class="co-card">
                    <header class="co-card__head">
                        <i class="fa fa-shopping-bag text-muted" aria-hidden="true"></i>
                        <h2>{{ $t('user.co.items_title', { count: quote ? quote.items_count : 0 }) }}</h2>
                        <router-link :to="{ name: 'cart' }" class="co-card__edit small">{{ $t('user.co.edit_cart') }}</router-link>
                    </header>

                    <div v-if="!quote" class="text-muted py-2"><span class="spinner-border spinner-border-sm"></span></div>
                    <div v-else-if="!quote.lines.length" class="text-center text-muted py-4">
                        <i class="fa fa-shopping-cart fa-2x d-block mb-2" aria-hidden="true"></i>
                        {{ $t('user.checkout.empty_cart') }}
                        <div class="mt-2"><a :href="shop_url" class="btn btn-sm btn-outline-primary">{{ $t('user.order_placed.continue_shopping') }}</a></div>
                    </div>

                    <ul v-else class="co-items">
                        <li v-for="l in quote.lines" :key="l.option_id" :class="{ 'has-issue': !l.stock_ok }">
                            <img class="co-items__img" :src="l.image ? '/public/images/product_option_img/' + l.image : '/public/images/site_img/shop_demo.jpg'" alt="" loading="lazy">
                            <div class="co-items__main">
                                <div class="fw-semibold">{{ l.title }}</div>
                                <div class="small text-muted">{{ l.option_name }}</div>
                                <div class="small text-muted">
                                    <s v-if="l.item_discount > 0" class="me-1">{{ money(l.list_price) }}</s>
                                    {{ money(l.unit_price) }} × {{ l.quantity }}
                                    <span v-if="l.item_discount > 0" class="badge text-bg-danger ms-1">−{{ +l.item_discount }}%</span>
                                </div>
                                <div v-if="l.made_to_order && l.available === null" class="small text-info">
                                    <i class="fa fa-wrench" aria-hidden="true"></i> {{ $t('user.co.made_to_order') }}
                                </div>
                                <div v-if="!l.stock_ok" class="small text-danger">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $t('user.cart.quantity_mismatch_message', { selected: l.quantity, available: l.available }) }}
                                    <button type="button" class="btn btn-link btn-sm p-0 align-baseline" @click="update_to_max(l)">{{ $t('user.cart.update_to_max_btn', { available: l.available }) }}</button>
                                </div>
                            </div>
                            <div class="co-items__end">
                                <div class="fw-semibold">{{ money(l.line_total) }}</div>
                                <button type="button" class="btn btn-link btn-sm text-danger p-0" @click="remove(l)" :aria-label="$t('common.delete')">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i> {{ $t('user.co.remove') }}
                                </button>
                            </div>
                        </li>
                    </ul>
                </section>
            </div>

            <div class="col-lg-4">
                <CheckoutSummary :quote="quote" :loading="quote_loading" :show_items="false" :delivery_days="delivery_days">
                    <button type="button" class="btn btn-success btn-lg w-100" :disabled="!can_place" @click="place_order">
                        <span v-if="placing" class="spinner-border spinner-border-sm"></span>
                        <i v-else :class="'fa ' + (is_online ? 'fa-lock' : 'fa-check')" aria-hidden="true"></i>
                        {{ placing
                            ? (is_online ? $t('user.checkout.redirecting_to_payment') : $t('user.checkout.placing_order'))
                            : (is_online ? $t('user.co.pay_btn', { total: quote ? money(quote.total) : '' }) : $t('user.co.place_btn')) }}
                    </button>
                    <p class="co-block">{{ block_reason || (is_online ? $t('user.co.after_online') : $t('user.co.after_cod')) }}</p>
                </CheckoutSummary>
            </div>
        </div>
    </div>
</template>

<script>
import CheckoutSteps from './items/CheckoutSteps.vue'
import CheckoutSummary from './items/CheckoutSummary.vue'
import { load_cart, fetch_quote, online_payment_enabled, quote_locale, full_width_layout } from '../../../../../services/shop/checkout.js'
import { get_auth_user } from '../../../../../services/authUser.js'
import { deliveryDays } from '../../../../../services/coefficients.js'

// Checkout step 3: review and place. Totals are the server quote (same rules
// as create_order), so what's shown is what's charged.
export default {
    components: { CheckoutSteps, CheckoutSummary },
    data() {
        return {
            cart: [],
            address: null,
            user: null,
            quote: null,
            quote_loading: false,
            quote_seq: 0,
            payment: 'deliverd payment',
            placing: false,
            order_error: null,
        }
    },
    computed: {
        address_id() { return parseInt(this.$route.params.adres, 10) || null },
        sale_code() { return this.$route.query.code || null },
        is_online() { return this.payment === 'online payment' },
        delivery_days() {
            return this.cart.length ? deliveryDays(this.cart.some(i => i.product && i.product.sale_type === 'produced_by_order')) : null
        },
        address_details() {
            const a = this.address || {}
            return [
                a.entrance && this.$t('user.order_view.entrance') + ' ' + a.entrance,
                a.floor && this.$t('user.order_view.floor') + ' ' + a.floor,
                a.flat && this.$t('user.order_view.flat') + ' ' + a.flat,
            ].filter(Boolean).join(' · ')
        },
        block_reason() {
            const q = this.quote
            if (!q) return ''
            const codes = q.issues.map(i => i.code)
            if (codes.includes('empty')) return this.$t('user.checkout.empty_cart')
            if (codes.includes('out_of_stock')) return this.$t('user.cart.fix_issues_to_checkout_tooltip')
            if (codes.includes('below_min_order')) return this.$t('user.co.min_block')
            if (codes.includes('invalid_address') || codes.includes('no_address')) return this.$t('user.co.pick_address_first')
            return ''
        },
        can_place() {
            return !!this.quote && this.quote.can_place && !this.placing && !this.quote_loading
        },
        shop_url() {
            return (process.env.MIX_APP_SSH || 'https://') + (process.env.MIX_SHOP_URL || '')
        },
    },
    mounted() {
        full_width_layout()
        if (!this.address_id) {
            this.$router.replace({ name: 'orderPayment' })
            return
        }
        if (this.$route.params.payment) this.payment = this.$route.params.payment
        online_payment_enabled().then(on => { if (!on && this.is_online) this.payment = 'deliverd payment' })
        get_auth_user().then(r => { this.user = r.data }).catch(() => {})
        axios.get('get_activ_adres/' + this.address_id).then(r => { this.address = r.data }).catch(() => {})
        this.reload()
    },
    methods: {
        reload() {
            return load_cart().then(cart => { this.cart = cart; return this.refresh_quote() })
        },
        refresh_quote() {
            const seq = ++this.quote_seq
            this.quote_loading = true
            return fetch_quote(this.cart, { adres: this.address_id, sale_code: this.sale_code, locale: quote_locale(this.$i18n) })
                .then(q => { if (seq === this.quote_seq) this.quote = q })
                .catch(e => console.log(e))
                .finally(() => { if (seq === this.quote_seq) this.quote_loading = false })
        },
        cart_item(line) {
            return this.cart.find(i => i.option && i.option.id === line.option_id)
        },
        update_to_max(line) {
            const item = this.cart_item(line)
            if (!item) return
            axios.post('cart/update_quantity/' + item.id, { quantity: line.available })
                .then(() => { this.$bus.$emit('cart-updated'); return this.reload() })
                .catch(e => console.log(e))
        },
        remove(line) {
            const item = this.cart_item(line)
            if (!item || !confirm(this.$t('user.checkout.confirm_remove_item'))) return
            axios.delete('cart/' + item.id)
                .then(() => { this.$bus.$emit('cart-updated'); return this.reload() })
                .catch(e => console.log(e))
        },
        place_order() {
            if (!this.can_place) return
            this.order_error = null
            this.placing = true
            axios.post('set_order/create_order', {
                order_product_list: this.cart,
                payment_tupe: this.payment,
                adres: this.address_id,
                sale_code: this.sale_code,
            }).then(response => {
                const orderId = response.data && response.data.order_id
                this.$bus.$emit('cart-updated')
                if (this.is_online && orderId) {
                    return axios.post('payment/shop/initiate/' + orderId).then(payRes => {
                        window.location.href = payRes.data.payment_url
                    })
                }
                // The placed-order page loads this order to show its summary and the e-mail step.
                this.$router.push({ name: 'confirmOrder', params: { order_decloration: true }, query: { order: orderId } })
            }).catch(error => {
                this.order_error = (error.response && error.response.data && error.response.data.error) || this.$t('user.checkout.create_order_error')
                this.refresh_quote()
                this.placing = false
            })
        },
        money(v) {
            return (Number(v) || 0).toFixed(2) + ' ' + (this.quote?.currency || '₾')
        },
    },
}
</script>

<style scoped>
.co-addr__street { font-size: 1.05rem; font-weight: 600; margin: .15rem 0; }
.co-items { list-style: none; padding: 0; margin: 0; }
.co-items li {
    display: flex;
    gap: .9rem;
    padding: .85rem 0;
    border-bottom: 1px solid var(--bs-border-color);
}
.co-items li:last-child { border-bottom: 0; }
.co-items li.has-issue { background: var(--bs-danger-bg-subtle); margin: 0 -1.25rem; padding-left: 1.25rem; padding-right: 1.25rem; }
.co-items__img {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: var(--bs-border-radius);
    border: 1px solid var(--bs-border-color);
    flex-shrink: 0;
}
.co-items__main { flex: 1; min-width: 0; }
.co-items__end { display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; gap: .35rem; white-space: nowrap; }
</style>
