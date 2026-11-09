<template>
    <div class="container co">
        <CheckoutSteps :current="1" />

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Stock problems from the cart -->
                <div v-if="mismatch.length" class="alert alert-danger">
                    <div v-for="p in mismatch" :key="p.id" class="d-flex flex-wrap align-items-center gap-2">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        <span><strong>{{ title_of(p) }}</strong> — {{ $t('user.cart.quantity_mismatch_message', { selected: p.quantity, available: p.stock_quantity }) }}</span>
                        <button type="button" class="btn btn-link btn-sm p-0" @click="update_to_max(p)">{{ $t('user.cart.update_to_max_btn', { available: p.stock_quantity }) }}</button>
                    </div>
                </div>

                <!-- 1. Address -->
                <section class="co-card" :class="{ 'has-error': show_errors && !selected_address }">
                    <header class="co-card__head">
                        <span class="co-card__num">1</span>
                        <h2>{{ $t('user.co.address_title') }}</h2>
                    </header>

                    <p v-if="show_errors && !selected_address && !adding" class="co-error">
                        <i class="fa fa-exclamation-circle"></i> {{ $t('user.checkout.select_address_error') }}
                    </p>

                    <div v-if="addresses_loading" class="text-muted py-2"><span class="spinner-border spinner-border-sm"></span></div>

                    <div v-else class="co-options co-options--grid" role="radiogroup" :aria-label="$t('user.co.address_title')">
                        <label v-for="a in addresses" :key="a.id" class="co-option" :class="{ 'is-active': selected_address === a.id }">
                            <input type="radio" class="visually-hidden" name="co_address" :value="a.id" v-model="selected_address" @change="adding = false; refresh_quote()">
                            <span class="co-option__radio" aria-hidden="true"></span>
                            <span class="co-option__body">
                                <span class="co-option__title">
                                    {{ a.name }}
                                    <span v-if="a.is_default" class="badge text-bg-light ms-1">{{ $t('user.co.default_badge') }}</span>
                                </span>
                                <span class="co-option__text">{{ [a.strit, a.number].filter(Boolean).join(' ') }}</span>
                                <span class="co-option__text">{{ address_details(a) }}</span>
                                <span class="co-option__text text-muted">{{ [a.city, region_name(a.region_id), a.zip_code].filter(Boolean).join(', ') }}</span>
                            </span>
                        </label>

                        <button type="button" class="co-option co-option--add" :class="{ 'is-active': adding }" @click="start_adding">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            <span>{{ $t('user.co.add_address') }}</span>
                        </button>
                    </div>

                    <!-- new address form -->
                    <form v-if="adding" class="co-form" @submit.prevent="save_address" novalidate>
                        <h3 class="co-form__title">{{ $t('user.checkout.add_new_address_title') }}</h3>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label" for="co_a_name">{{ $t('user.co.f_label') }} *</label>
                                <input id="co_a_name" class="form-control" :class="{ 'is-invalid': errors.demo_name }" v-model.trim="form.demo_name" :placeholder="$t('user.co.f_label_ph')">
                                <div class="invalid-feedback">{{ $t('user.addresses.error_name') }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="co_a_region">{{ $t('user.co.f_region') }} *</label>
                                <select id="co_a_region" class="form-select" :class="{ 'is-invalid': errors.region_id }" v-model="form.region_id">
                                    <option :value="0" disabled>{{ $t('user.checkout.select_region') }}</option>
                                    <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.region }}</option>
                                </select>
                                <div class="invalid-feedback">{{ $t('user.addresses.error_country') }}</div>
                                <div v-if="form_region" class="form-text">{{ region_hint(form_region) }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="co_a_city">{{ $t('user.co.f_city') }} *</label>
                                <input id="co_a_city" class="form-control" :class="{ 'is-invalid': errors.city }" v-model.trim="form.city" autocomplete="address-level2">
                                <div class="invalid-feedback">{{ $t('user.addresses.error_city') }}</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="co_a_zip">{{ $t('user.co.f_zip') }}</label>
                                <input id="co_a_zip" class="form-control" v-model.trim="form.zip_code" autocomplete="postal-code">
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label" for="co_a_street">{{ $t('user.co.f_street') }} *</label>
                                <input id="co_a_street" class="form-control" :class="{ 'is-invalid': errors.strit }" v-model.trim="form.strit" autocomplete="address-line1">
                                <div class="invalid-feedback">{{ $t('user.addresses.error_street') }}</div>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="co_a_number">{{ $t('user.co.f_number') }} *</label>
                                <input id="co_a_number" class="form-control" :class="{ 'is-invalid': errors.number }" v-model.trim="form.number">
                                <div class="invalid-feedback">{{ $t('user.addresses.error_house_number') }}</div>
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="co_a_entrance">{{ $t('user.order_view.entrance') }}</label>
                                <input id="co_a_entrance" class="form-control" v-model.trim="form.entrance">
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="co_a_floor">{{ $t('user.order_view.floor') }} *</label>
                                <input id="co_a_floor" class="form-control" :class="{ 'is-invalid': errors.floor }" v-model.trim="form.floor">
                                <div class="invalid-feedback">{{ $t('user.addresses.error_floor') }}</div>
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="co_a_flat">{{ $t('user.order_view.flat') }}</label>
                                <input id="co_a_flat" class="form-control" v-model.trim="form.flat">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="co_a_map">{{ $t('user.checkout.map_location_label') }}</label>
                                <input id="co_a_map" class="form-control" v-model.trim="form.map" :placeholder="$t('user.checkout.map_placeholder')">
                            </div>
                            <div class="col-12 d-flex flex-wrap align-items-center gap-3">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="co_a_default" v-model="form.is_default">
                                    <label class="form-check-label" for="co_a_default">{{ $t('user.cart.set_as_default_label') }}</label>
                                </div>
                                <div class="ms-auto d-flex gap-2">
                                    <button v-if="addresses.length" type="button" class="btn btn-outline-secondary" @click="adding = false">{{ $t('common.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary" :disabled="saving">
                                        <span v-if="saving" class="spinner-border spinner-border-sm"></span>
                                        {{ $t('user.co.save_address') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

                <!-- 2. Payment -->
                <section class="co-card">
                    <header class="co-card__head">
                        <span class="co-card__num">2</span>
                        <h2>{{ $t('user.checkout.payment_method') }}</h2>
                    </header>
                    <div class="co-options" role="radiogroup" :aria-label="$t('user.checkout.payment_method')">
                        <label class="co-option" :class="{ 'is-active': payment === 'deliverd payment' }">
                            <input type="radio" class="visually-hidden" name="co_pay" value="deliverd payment" v-model="payment">
                            <span class="co-option__radio" aria-hidden="true"></span>
                            <i class="fa fa-money co-option__icon" aria-hidden="true"></i>
                            <span class="co-option__body">
                                <span class="co-option__title">{{ $t('user.checkout.pay_on_delivery') }}</span>
                                <span class="co-option__text text-muted">{{ $t('user.co.cod_desc') }}</span>
                            </span>
                        </label>
                        <label v-if="online_enabled" class="co-option" :class="{ 'is-active': payment === 'online payment' }">
                            <input type="radio" class="visually-hidden" name="co_pay" value="online payment" v-model="payment">
                            <span class="co-option__radio" aria-hidden="true"></span>
                            <i class="fa fa-credit-card co-option__icon" aria-hidden="true"></i>
                            <span class="co-option__body">
                                <span class="co-option__title">{{ $t('user.checkout.pay_online') }} <span class="co-tbc">TBC</span></span>
                                <span class="co-option__text text-muted">{{ $t('user.co.online_desc') }}</span>
                            </span>
                        </label>
                    </div>
                </section>

                <!-- 3. Discount code -->
                <section class="co-card">
                    <header class="co-card__head">
                        <span class="co-card__num">3</span>
                        <h2>{{ $t('user.co.code_title') }} <small class="text-muted fw-normal">{{ $t('user.co.optional') }}</small></h2>
                    </header>
                    <form class="co-code" @submit.prevent="apply_code">
                        <input class="form-control" v-model.trim="code_input" :placeholder="$t('user.checkout.sale_code_placeholder')" :aria-label="$t('user.checkout.sale_code_placeholder')">
                        <button type="submit" class="btn btn-outline-primary" :disabled="!code_input">{{ $t('user.co.apply') }}</button>
                        <button v-if="applied_code" type="button" class="btn btn-link text-muted" @click="remove_code">{{ $t('user.co.remove') }}</button>
                    </form>
                    <p v-if="code_status === 'applied'" class="co-msg text-success"><i class="fa fa-check-circle"></i> {{ $t('user.co.code_applied', { percent: quote.discount.code.percent }) }}</p>
                    <p v-else-if="code_status === 'not_better'" class="co-msg text-muted"><i class="fa fa-info-circle"></i> {{ $t('user.co.code_not_better', { percent: quote.discount.percent }) }}</p>
                    <p v-else-if="code_status === 'invalid'" class="co-msg text-danger"><i class="fa fa-times-circle"></i> {{ $t('user.checkout.sale_code_not_found') }}</p>
                    <p v-else-if="quote && quote.discount.source === 'partner'" class="co-msg text-success"><i class="fa fa-handshake-o"></i> {{ $t('user.co.partner_applied', { percent: quote.discount.percent, org: quote.discount.partner.organization_name }) }}</p>
                </section>
            </div>

            <div class="col-lg-4">
                <CheckoutSummary :quote="quote" :loading="quote_loading" :delivery_days="delivery_days">
                    <button type="button" class="btn btn-primary btn-lg w-100" :disabled="!can_continue" @click="go_next">
                        {{ $t('user.co.continue_review') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </button>
                    <p v-if="block_reason" class="co-block">{{ block_reason }}</p>
                    <router-link :to="{ name: 'cart' }" class="btn btn-link w-100 mt-1">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ $t('user.co.back_to_cart') }}
                    </router-link>
                </CheckoutSummary>
            </div>
        </div>
    </div>
</template>

<script>
import CheckoutSteps from './items/CheckoutSteps.vue'
import CheckoutSummary from './items/CheckoutSummary.vue'
import { load_cart, fetch_quote, online_payment_enabled, quote_locale, full_width_layout } from '../../../../../services/shop/checkout.js'
import { deliveryDays } from '../../../../../services/coefficients.js'

const EMPTY_FORM = () => ({
    demo_name: '', region_id: 0, city: '', strit: '', number: '', floor: '', flat: '', entrance: '', zip_code: '', map: '', is_default: true,
})

// Checkout step 2: delivery address, payment method, discount code.
// Totals in the sidebar are the server quote (OrderPlacementService::quote_website).
export default {
    components: { CheckoutSteps, CheckoutSummary },
    data() {
        return {
            cart: [],
            addresses: [],
            addresses_loading: true,
            regions: [],
            selected_address: null,
            adding: false,
            saving: false,
            form: EMPTY_FORM(),
            errors: {},
            payment: 'deliverd payment',
            online_enabled: false,
            code_input: '',
            applied_code: '',
            quote: null,
            quote_loading: false,
            quote_seq: 0,
            show_errors: false,
        }
    },
    computed: {
        mismatch() {
            return this.cart.filter(p => !p.is_out_of_stock && !(p.product && p.product.sale_type === 'produced_by_order')
                && p.stock_quantity != null && p.quantity > p.stock_quantity)
        },
        form_region() {
            return this.regions.find(r => r.id == this.form.region_id) || null
        },
        code_status() {
            return this.quote?.discount?.code?.status || null
        },
        delivery_days() {
            return this.cart.length ? deliveryDays(this.cart.some(i => i.product && i.product.sale_type === 'produced_by_order')) : null
        },
        block_reason() {
            if (!this.cart.length) return this.$t('user.checkout.empty_cart')
            if (this.mismatch.length) return this.$t('user.cart.fix_issues_to_checkout_tooltip')
            if (!this.selected_address) return this.$t('user.co.pick_address_first')
            if (this.quote?.region?.below_min) return this.$t('user.co.min_block')
            return ''
        },
        can_continue() {
            return !this.block_reason && !this.quote_loading && !!this.quote
        },
    },
    mounted() {
        full_width_layout()
        online_payment_enabled().then(on => {
            this.online_enabled = on
            if (!on && this.payment === 'online payment') this.payment = 'deliverd payment'
        })
        axios.get('get_shiped_region/get_all_shiped_regions').then(r => { this.regions = r.data || [] }).catch(() => {})
        this.load()
    },
    methods: {
        load() {
            Promise.all([
                load_cart(),
                axios.get('get_user_adreses').then(r => r.data || []).catch(() => []),
            ]).then(([cart, addresses]) => {
                this.cart = cart
                this.addresses = addresses
                this.addresses_loading = false
                if (!this.selected_address) {
                    const pick = addresses.find(a => a.is_default) || addresses[0]
                    if (pick) this.selected_address = pick.id
                }
                if (!addresses.length) this.start_adding()
                this.refresh_quote()
            })
        },
        refresh_quote() {
            const seq = ++this.quote_seq
            this.quote_loading = true
            fetch_quote(this.cart, { adres: this.selected_address, sale_code: this.applied_code || null, locale: quote_locale(this.$i18n) })
                .then(q => { if (seq === this.quote_seq) this.quote = q })
                .catch(e => console.log(e))
                .finally(() => { if (seq === this.quote_seq) this.quote_loading = false })
        },
        apply_code() {
            this.applied_code = this.code_input
            this.refresh_quote()
        },
        remove_code() {
            this.applied_code = ''
            this.code_input = ''
            this.refresh_quote()
        },
        start_adding() {
            this.adding = true
            this.form = EMPTY_FORM()
            this.form.is_default = !this.addresses.length
            this.errors = {}
        },
        save_address() {
            const required = ['demo_name', 'region_id', 'city', 'strit', 'number', 'floor']
            this.errors = Object.fromEntries(required.filter(k => !this.form[k]).map(k => [k, true]))
            if (Object.keys(this.errors).length) return

            this.saving = true
            axios.post('add_user_adreses', { adding_data: this.form, _method: 'POST' })
                .then(r => {
                    const id = r.data && r.data.id
                    return axios.get('get_user_adreses').then(list => {
                        this.addresses = list.data || []
                        this.selected_address = id || (this.addresses[this.addresses.length - 1] || {}).id || null
                        this.adding = false
                        this.refresh_quote()
                    })
                })
                .catch(e => console.log(e))
                .finally(() => { this.saving = false })
        },
        update_to_max(p) {
            axios.post('cart/update_quantity/' + p.id, { quantity: p.stock_quantity })
                .then(() => { this.$bus.$emit('cart-updated'); return load_cart() })
                .then(cart => { this.cart = cart; this.refresh_quote() })
                .catch(e => console.log(e))
        },
        go_next() {
            this.show_errors = true
            if (!this.can_continue) return
            this.$router.push({
                name: 'orderDecloration',
                params: { payment: this.payment, adres: this.selected_address },
                // The sale code travels as a query: params outside the route path are dropped.
                query: this.applied_code && this.code_status === 'applied' ? { code: this.applied_code } : {},
            })
        },
        region_name(id) {
            return (this.regions.find(r => r.id == id) || {}).region || ''
        },
        region_hint(r) {
            const parts = [this.$t('user.co.region_price', { price: (Number(r.shiping_price) || 0).toFixed(2) })]
            if (r.free_shiping_price_after) parts.push(this.$t('user.co.region_free_from', { amount: Number(r.free_shiping_price_after).toFixed(2) }))
            if (r.ship_min_price) parts.push(this.$t('user.co.region_min', { amount: Number(r.ship_min_price).toFixed(2) }))
            return parts.join(' · ')
        },
        address_details(a) {
            return [
                a.entrance && this.$t('user.order_view.entrance') + ' ' + a.entrance,
                a.floor && this.$t('user.order_view.floor') + ' ' + a.floor,
                a.flat && this.$t('user.order_view.flat') + ' ' + a.flat,
            ].filter(Boolean).join(' · ')
        },
        title_of(p) {
            return (p.product && p.product.url_title) || ''
        },
    },
}
</script>
