<template>
    <div class="row">

        <left-menu />

        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shopping-cart">
                        <div class="card-header bg-dark text-light">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            {{ $t('user.cart.shopping_cart_title') }}
                            <button class="btn btn-primary float-right" @click="goToShop()">{{ $t('user.cart.go_to_shop_btn') }}</button>
                        </div>
                        <div class="card-body">
                            <div v-if="!cart_items.length" class="text-center text-muted py-4">
                                <i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
                                <p class="mt-2">{{ $t('user.cart.cart_is_empty') }}</p>
                                <button class="btn btn-primary" @click="goToShop()">{{ $t('user.cart.browse_products_btn') }}</button>
                            </div>

                            <div v-if="has_unavailable_items" class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                {{ $t('user.cart.out_of_stock_warning') }}
                            </div>

                            <div v-if="cart_items.length && addresses_loaded && !addresses.length" class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap">
                                <span><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $t('user.cart.no_address_warning') }}</span>
                                <button type="button" class="btn btn-primary btn-sm mt-2 mt-sm-0" @click="open_add_address_modal()">
                                    <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('user.cart.add_address_btn') }}
                                </button>
                            </div>

                            <div v-for="product in cart_items" :key="product.id" class="cart-item" :class="{ 'cart-item-unavailable': product.is_out_of_stock }">
                                <div class="row align-items-center">
                                    <div class="col-sm-12 col-md-2 text-center">
                                        <img
                                            v-if="product.product_image"
                                            class="img-responsive cart-thumb"
                                            :src="'/public/images/product_option_img/'+product.product_image"
                                            :alt="product.product && product.product.url_title || ''"
                                        >
                                        <img
                                            v-else
                                            class="img-responsive cart-thumb"
                                            :src="'/public/images/site_img/shop_demo.jpg'"
                                            :alt="product.product && product.product.url_title || ''"
                                        >
                                    </div>

                                    <div class="col-sm-12 col-md-4 text-md-left">
                                        <a
                                            v-if="product.product && product.product.url_title"
                                            @click.prevent="openProduct(product.product.url_title)"
                                            href="#"
                                            class="cart-product-name"
                                        >
                                            {{ product.product.url_title }}
                                        </a>
                                        <span v-else class="cart-product-name text-muted">{{ $t('user.cart.unknown_product') }}</span>

                                        <div class="cart-option-name text-muted" v-if="product.option && product.option.name">
                                            <small><i class="fa fa-tag" aria-hidden="true"></i> {{ product.option.name }}</small>
                                        </div>

                                        <div v-if="product.is_out_of_stock" class="text-danger mt-1">
                                            <small><i class="fa fa-times-circle" aria-hidden="true"></i> {{ $t('user.cart.out_of_stock_label') }}</small>
                                        </div>
                                        <div v-else-if="is_quantity_mismatch(product)" class="text-danger mt-1">
                                            <small>
                                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                                {{ $t('user.cart.quantity_mismatch_message', { selected: product.quantity, available: product.stock_quantity }) }}
                                                <button type="button" class="btn btn-link btn-sm p-0 ml-1 align-baseline" @click="update_to_max_quantity(product)">{{ $t('user.cart.update_to_max_btn', { available: product.stock_quantity }) }}</button>
                                            </small>
                                        </div>
                                        <div v-else-if="product.stock_quantity > 0 && product.stock_quantity <= 5" class="text-warning mt-1">
                                            <small><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('user.cart.only_left_in_stock', { count: product.stock_quantity }) }}</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <div class="d-flex align-items-center justify-content-md-end">
                                            <div class="cart-price mr-3" v-if="!product.is_out_of_stock">
                                                <div class="text-muted"><small>{{ product.option && product.option.price }} ₾ {{ $t('user.cart.price_each_suffix') }}</small></div>
                                                <strong>{{ colculat_items_price(product.option && product.option.price, product.quantity) }} ₾</strong>
                                            </div>
                                            <div class="quantity-wrap" v-if="!product.is_out_of_stock">
                                                <span v-if="is_quantity_updating" class="text-muted">{{ $t('admin.articles.updating_ellipsis') }}</span>
                                                <div v-else class="d-flex align-items-center">
                                                    <button class="qty-btn" @click="changeQty(product, -1)" :disabled="product.quantity <= 1">−</button>
                                                    <input
                                                        type="number"
                                                        class="qty-input"
                                                        v-model.number="product.quantity"
                                                        min="1"
                                                        :max="product.stock_quantity || 999"
                                                        @change="onQtyChange(product)"
                                                    >
                                                    <button class="qty-btn" @click="changeQty(product, 1)" :disabled="product.quantity >= (product.stock_quantity || 999)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-2 text-right">
                                        <button @click="del_from_cart(product.id)" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="text-right mt-2" v-if="cart_items.length">
                                <button type="button" @click="get_products_cart" class="btn btn-secondary btn-sm" v-if="!is_products_refresh">
                                    <i class="fa fa-refresh" aria-hidden="true"></i> {{ $t('user.cart.refresh_count_btn', { id: products_reset_id }) }}
                                </button>
                                <span class="badge badge-secondary" v-if="is_products_refresh">{{ $t('admin.articles.updating_ellipsis') }}</span>
                            </div>
                        </div>

                        <div class="card-footer" v-if="cart_items.length">
                            <div class="shiping-summary mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">{{ $t('user.cart.subtotal_label') }}</span>
                                    <span>{{ subtotal.toFixed(2) }} ₾</span>
                                </div>
                                <div class="d-flex justify-content-between text-success" v-if="is_partner_member && discount_amount > 0">
                                    <span>{{ $t('user.cart.partner_discount_label', { percent: partner_discount_percent }) }}</span>
                                    <span>−{{ discount_amount.toFixed(2) }} ₾</span>
                                </div>
                                <div class="d-flex justify-content-between" v-if="default_address">
                                    <span class="text-muted">{{ $t('user.cart.shipping_label') }}</span>
                                    <span>{{ shiping_cost.toFixed(2) }} ₾</span>
                                </div>

                                <div v-if="min_ship_price_not_met" class="alert alert-danger py-1 px-2 mt-2 mb-0 small">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $t('user.cart.min_order_price_warning', { region: shiping_region.region, amount: shiping_region.ship_min_price }) }}
                                </div>
                                <div v-else-if="free_shipping_remaining > 0" class="alert alert-info py-1 px-2 mt-2 mb-0 small">
                                    <i class="fa fa-truck" aria-hidden="true"></i>
                                    {{ $t('user.cart.free_shipping_remaining', { amount: free_shipping_remaining.toFixed(2), region: shiping_region.region }) }}
                                </div>
                                <div v-else-if="shiping_region && shiping_region.free_shiping_price_after" class="alert alert-success py-1 px-2 mt-2 mb-0 small">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    {{ $t('user.cart.free_shipping_reached', { region: shiping_region.region }) }}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="total-price">
                                    {{ $t('user.cart.total_prefix') }} <strong>{{ total_price.toFixed(2) }} ₾</strong>
                                </div>
                                <router-link :to="{name: 'orderPayment'}" v-if="!checkout_disabled">
                                    <button type="button" class="btn btn-success">
                                        <i class="fa fa-credit-card" aria-hidden="true"></i> {{ $t('user.cart.checkout_btn') }}
                                    </button>
                                </router-link>
                                <button v-else type="button" class="btn btn-secondary" disabled :title="checkout_disabled_reason">
                                    <i class="fa fa-credit-card" aria-hidden="true"></i> {{ $t('user.cart.checkout_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <stack-modal
                :show="show_add_address_modal"
                :title="$t('user.cart.add_address_modal_title')"
                @close="close_add_address_modal"
                @save="submit_add_address"
                :saveButton="{ visible: true, title: $t('user.cart.add_address_btn'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
            >
            <div>
                <div class="alert alert-danger" role="alert" v-if="add_address_errors.demo_name">{{ $t('user.addresses.error_name') }}</div>
                <input type="text" class="form-control" v-model="adding_address.demo_name" :placeholder="$t('user.addresses.name_placeholder')" :title="$t('user.addresses.name_placeholder')">

                <div class="alert alert-danger" role="alert" v-if="add_address_errors.region_id">{{ $t('user.addresses.error_country') }}</div>
                <select class="form-control" v-model="adding_address.region_id">
                    <option :value="0" disabled>{{ $t('user.checkout.select_region') }}</option>
                    <option v-for="region in regions_list" :key="region.id" :value="region.id">{{ region.region }}</option>
                </select>

                <div class="alert alert-danger" role="alert" v-if="add_address_errors.city">{{ $t('user.addresses.error_city') }}</div>
                <input type="text" class="form-control" v-model="adding_address.city" :placeholder="$t('user.addresses.city_placeholder')" :title="$t('user.addresses.city_placeholder')">

                <div class="alert alert-danger" role="alert" v-if="add_address_errors.strit">{{ $t('user.addresses.error_street') }}</div>
                <input type="text" class="form-control" v-model="adding_address.strit" :placeholder="$t('user.addresses.street_placeholder')" :title="$t('user.addresses.street_placeholder')">

                <div class="alert alert-danger" role="alert" v-if="add_address_errors.number">{{ $t('user.addresses.error_house_number') }}</div>
                <input type="text" class="form-control" v-model="adding_address.number" :placeholder="$t('user.addresses.house_number_placeholder')" :title="$t('user.addresses.house_number_placeholder')">

                <div class="alert alert-danger" role="alert" v-if="add_address_errors.floor">{{ $t('user.addresses.error_floor') }}</div>
                <input type="text" class="form-control" v-model="adding_address.floor" :placeholder="$t('user.addresses.floor_placeholder')" :title="$t('user.addresses.floor_placeholder')">

                <input type="text" class="form-control" v-model="adding_address.flat" :placeholder="$t('user.addresses.flat_placeholder')" :title="$t('user.addresses.flat_placeholder')">
                <input type="text" class="form-control" v-model="adding_address.entrance" :placeholder="$t('user.addresses.entrance_placeholder')" :title="$t('user.addresses.entrance_placeholder')">
                <input type="text" class="form-control" v-model="adding_address.zip_code" :placeholder="$t('user.addresses.zip_placeholder')" :title="$t('user.addresses.zip_placeholder')">

                <div class="mt-2">
                    <label class="text-muted small">{{ $t('user.checkout.map_location_label') }}</label>
                    <textarea class="form-control" v-model="adding_address.map" rows="2" :placeholder="$t('user.checkout.map_placeholder')"></textarea>
                </div>

                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="cart_adres_default" v-model="adding_address.is_default">
                    <label class="form-check-label" for="cart_adres_default">{{ $t('user.cart.set_as_default_label') }}</label>
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
        },
        data(){
            return {
                cart_items: [],
                is_products_refresh: false,
                products_reset_id: 0,
                total_price: 0,
                subtotal: 0,
                shiping_cost: 0,
                is_partner_member: false,
                partner_discount_percent: 0,
                discount_amount: 0,
                user_id: 0,
                MIX_SHOP_URL: process.env.MIX_SHOP_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                is_quantity_updating: false,

                addresses: [],
                addresses_loaded: false,
                default_address: null,
                shiping_region: null,

                show_add_address_modal: false,
                regions_list: [],
                add_address_errors: {},
                adding_address: {
                    demo_name: null,
                    region_id: 0,
                    city: null,
                    strit: null,
                    number: null,
                    floor: null,
                    flat: null,
                    entrance: null,
                    zip_code: null,
                    map: null,
                    is_default: true,
                },
            }
        },
        computed: {
            has_unavailable_items() {
                return this.cart_items.some(p => p.is_out_of_stock);
            },
            has_quantity_mismatch() {
                return this.cart_items.some(p => this.is_quantity_mismatch(p));
            },
            min_ship_price_not_met() {
                const min_price = this.shiping_region && this.shiping_region.ship_min_price ? parseFloat(this.shiping_region.ship_min_price) : 0
                return min_price > 0 && this.subtotal < min_price
            },
            free_shipping_remaining() {
                if (!this.shiping_region || !this.shiping_region.free_shiping_price_after) return 0
                const remaining = parseFloat(this.shiping_region.free_shiping_price_after) - this.subtotal
                return remaining > 0 ? remaining : 0
            },
            checkout_disabled() {
                return this.has_unavailable_items
                    || this.has_quantity_mismatch
                    || this.min_ship_price_not_met
                    || (this.addresses_loaded && !this.addresses.length)
            },
            checkout_disabled_reason() {
                if (this.has_unavailable_items || this.has_quantity_mismatch || this.min_ship_price_not_met) {
                    return this.$t('user.cart.fix_issues_to_checkout_tooltip')
                }
                if (this.addresses_loaded && !this.addresses.length) {
                    return this.$t('user.cart.no_address_warning')
                }
                return ''
            },
        },
        mounted() {
            this.get_products_cart()
            this.get_user_addresses()
            this.get_partner_status()
        },
        methods: {
            blank_address() {
                return {
                    demo_name: null,
                    region_id: 0,
                    city: null,
                    strit: null,
                    number: null,
                    floor: null,
                    flat: null,
                    entrance: null,
                    zip_code: null,
                    map: null,
                    is_default: true,
                }
            },
            goToShop(){
                window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL)
            },
            openProduct(url_title){
                window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL + '/product/' + url_title)
            },
            get_products_cart() {
                axios.get("cart").then(response => {
                    this.cart_items = Array.isArray(response.data) ? response.data : []
                    if (this.cart_items.length > 0) {
                        this.user_id = this.cart_items[0]['user_id']
                    }
                    this.products_reset_id++
                    this.colculat_total_price()
                }).catch(error => console.log(error))
            },
            is_quantity_mismatch(product) {
                if (product.is_out_of_stock) return false
                if (product.product && product.product.sale_type === 'produced_by_order') return false
                return product.stock_quantity != null && product.quantity > product.stock_quantity
            },
            get_user_addresses() {
                axios.get('get_user_adreses').then(response => {
                    this.addresses = Array.isArray(response.data) ? response.data : []
                    this.addresses_loaded = true
                    this.default_address = this.addresses.find(a => a.is_default) || this.addresses[0] || null
                    if (this.default_address) {
                        this.get_shiping_region(this.default_address.region_id)
                    } else {
                        this.shiping_region = null
                        this.colculat_total_price()
                    }
                }).catch(error => {
                    console.log(error)
                    this.addresses_loaded = true
                })
            },
            get_shiping_region(region_id) {
                axios.get('get_shiped_region/get_activ_region/' + region_id)
                    .then(response => {
                        this.shiping_region = response.data
                        this.colculat_total_price()
                    })
                    .catch(error => {
                        console.log(error)
                        this.shiping_region = null
                        this.colculat_total_price()
                    })
            },
            get_partner_status() {
                axios.get('partner_organization/my_status')
                    .then(response => {
                        this.is_partner_member = !!response.data.is_member
                        this.partner_discount_percent = this.is_partner_member && response.data.organization
                            ? parseFloat(response.data.organization.discount) || 0
                            : 0
                        this.colculat_total_price()
                    })
                    .catch(error => console.log(error))
            },
            colculat_total_price() {
                if (!Array.isArray(this.cart_items)) return
                this.subtotal = this.cart_items.reduce((sum, p) => {
                    const price = p.option && p.option.price ? parseFloat(p.option.price) : 0
                    return sum + price * (p.quantity || 1)
                }, 0)
                // Partner-organization discount is a preview only — the server
                // recomputes and takes whichever of this or a sale code (entered
                // at the next checkout step) benefits the customer most.
                this.discount_amount = this.partner_discount_percent > 0
                    ? (this.subtotal * this.partner_discount_percent) / 100
                    : 0
                this.shiping_cost = this.colculate_shiping_price(this.subtotal)
                this.total_price = this.subtotal - this.discount_amount + this.shiping_cost
            },
            colculate_shiping_price(subtotal) {
                if (!this.shiping_region || !this.shiping_region.shiping_price) return 0
                if (this.shiping_region.free_shiping_price_after && subtotal >= parseFloat(this.shiping_region.free_shiping_price_after)) return 0
                return parseFloat(this.shiping_region.shiping_price)
            },
            colculat_items_price(price, quantity) {
                return price ? (parseFloat(price) * quantity).toFixed(2) : 0
            },
            changeQty(product, delta) {
                const max = product.stock_quantity || 999
                const newQty = product.quantity + delta
                if (newQty < 1 || newQty > max) return
                product.quantity = newQty
                this.update_quantity(product.id, product.quantity)
            },
            onQtyChange(product) {
                const max = product.stock_quantity || 999
                if (product.quantity < 1) product.quantity = 1
                if (product.quantity > max) product.quantity = max
                this.update_quantity(product.id, product.quantity)
                this.colculat_total_price()
            },
            update_quantity(item_id, quantity) {
                this.is_quantity_updating = true
                axios.post("cart/update_quantity/" + item_id, { quantity })
                    .then(() => { this.get_products_cart(); this.$bus.$emit('cart-updated') })
                    .catch(error => console.log(error))
                    .finally(() => this.is_quantity_updating = false)
            },
            update_to_max_quantity(product) {
                this.update_quantity(product.id, product.stock_quantity)
            },
            del_from_cart(item_id) {
                if (confirm(this.$t('user.cart.confirm_remove_from_cart'))) {
                    axios.delete("cart/" + item_id)
                        .then(() => { this.get_products_cart(); this.$bus.$emit('cart-updated') })
                        .catch(error => console.log(error))
                }
            },
            open_add_address_modal() {
                this.add_address_errors = {}
                this.adding_address = this.blank_address()
                this.adding_address.is_default = !this.addresses.length
                if (!this.regions_list.length) {
                    axios.get('get_shiped_region/get_all_shiped_regions')
                        .then(response => { this.regions_list = response.data })
                        .catch(error => console.log(error))
                }
                this.show_add_address_modal = true
            },
            close_add_address_modal() {
                this.show_add_address_modal = false
            },
            submit_add_address() {
                this.add_address_errors = {
                    demo_name: !this.adding_address.demo_name,
                    region_id: !this.adding_address.region_id,
                    city: !this.adding_address.city,
                    strit: !this.adding_address.strit,
                    number: !this.adding_address.number,
                    floor: !this.adding_address.floor,
                }
                if (Object.values(this.add_address_errors).some(Boolean)) return

                axios.post('/add_user_adreses', { adding_data: this.adding_address })
                    .then(() => {
                        this.show_add_address_modal = false
                        this.get_user_addresses()
                    })
                    .catch(error => console.log(error))
            },
        }
    }
</script>

<style scoped>
.cart-item { padding: 12px 0; }
.cart-item-unavailable { opacity: 0.6; background: #fff8f8; border-radius: 4px; }
.cart-thumb { max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 4px; }
.cart-product-name { font-size: 0.95rem; font-weight: 600; color: #333; text-decoration: none; }
.cart-product-name:hover { color: #007bff; text-decoration: underline; cursor: pointer; }
.cart-option-name { font-size: 0.82rem; color: #777; margin-top: 2px; }
.cart-price { text-align: right; min-width: 80px; }
.quantity-wrap { display: flex; align-items: center; }
.qty-btn {
    width: 28px; height: 28px; border: 1px solid #ccc; background: #f8f9fa;
    border-radius: 4px; cursor: pointer; font-size: 1rem; line-height: 1;
}
.qty-btn:disabled { opacity: 0.4; cursor: default; }
.qty-input {
    width: 48px; text-align: center; border: 1px solid #ccc;
    border-radius: 4px; margin: 0 4px; height: 28px; padding: 0 4px;
}
.total-price { font-size: 1.1rem; }
</style>
