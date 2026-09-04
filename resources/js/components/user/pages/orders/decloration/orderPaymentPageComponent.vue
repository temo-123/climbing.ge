<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="container purchase overflow-auto">
                    <div>
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-primary pull-left" @click="go_back()">{{ $t('user.checkout.back') }}</button>
                            </div>
                            <div class="col">
                                <!-- <router-link :to="{name: 'confirmOrder'}" exact> -->
                                    <button class="btn btn-primary float-right" @click="go_next()" :disabled="next_disabled" :title="next_disabled_reason">{{ $t('user.checkout.next') }}</button>
                                <!-- </router-link> -->
                            </div>
                        </div>

                        <div class="row">
                            <productDradcrumb :props_page="'payment'"/>
                        </div>

                        <div v-if="quantity_mismatch_items.length" class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <div v-for="product in quantity_mismatch_items" :key="product.id">
                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                        {{ $t('user.cart.quantity_mismatch_message', { selected: product.quantity, available: product.stock_quantity }) }}
                                        <button type="button" class="btn btn-link btn-sm p-0 ml-1 align-baseline" @click="update_to_max_quantity(product)">{{ $t('user.cart.update_to_max_btn', { available: product.stock_quantity }) }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row"><hr></div>
                                <h2 class="text-center">{{ $t('user.checkout.your_addresses') }}</h2>

                                <span v-if="adreses_error">
                                    <div class="alert alert-danger" role="alert">
                                        {{ $t('user.checkout.select_address_error') }}
                                    </div>
                                </span>

                                <!-- <span>
                                    <div class="alert alert-danger" role="alert">
                                        Please select address for delivery!!!
                                    </div>
                                </span> -->

                                <div class="row justify-content-center">
                                    <div class="col-md-12 col-xs-12">
                                        <select class="form-control" v-model="selected_adreses_id" name="currency" @click="get_activ_adres(selected_adreses_id)">
                                            <option :value="'your adres'" disabled>{{ $t('user.checkout.select_your_address') }}</option>
                                            <option :value="'new adres'">{{ $t('user.checkout.new_address_option') }}</option>
                                            <option v-for="adres in adreses" :key="adres.id" :value="adres.id" >{{ adres.name }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center" v-if="selected_adreses_id == 'new adres'">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="alert alert-secondary">
                                            <h2 class="text-center">{{ $t('user.checkout.add_new_address_title') }}</h2>

                                            <input type="text" class="form-control" v-model="adding_data.demo_name" name="name" :placeholder="$t('user.addresses.name_placeholder')" :title="$t('user.addresses.name_placeholder')">
                                            <div class="alert alert-danger" role="alert" v-if="errors.demo_name">{{ $t('user.addresses.error_name') }}</div>

                                            <select class="form-control" v-model="adding_data.region_id" name="Currency">
                                                <option :value="0" disabled>{{ $t('user.checkout.select_region') }}</option>
                                                <option v-for="region in regions" :key="region.id" :value="region.id" >{{ region.region }}</option>
                                            </select>
                                            <div class="alert alert-danger" role="alert" v-if="errors.region_id">{{ $t('user.addresses.error_country') }}</div>

                                            <input type="text" class="form-control" v-model="adding_data.city" name="city" id="city" :placeholder="$t('user.addresses.city_placeholder')" :title="$t('user.addresses.city_placeholder')">
                                            <div class="alert alert-danger" role="alert" v-if="errors.city">{{ $t('user.addresses.error_city') }}</div>

                                            <input type="text" class="form-control" v-model="adding_data.strit" name="strit" id="strit" :placeholder="$t('user.addresses.street_placeholder')" :title="$t('user.addresses.street_placeholder')">
                                            <div class="alert alert-danger" role="alert" v-if="errors.strit">{{ $t('user.addresses.error_street') }}</div>

                                            <input type="text" class="form-control" v-model="adding_data.number" name="number" id="number" :placeholder="$t('user.addresses.house_number_placeholder')" :title="$t('user.addresses.house_number_placeholder')">
                                            <div class="alert alert-danger" role="alert" v-if="errors.number">{{ $t('user.addresses.error_house_number') }}</div>

                                            <input type="text" class="form-control" v-model="adding_data.floor" name="floor" id="floor" :placeholder="$t('user.addresses.floor_placeholder')" :title="$t('user.addresses.floor_placeholder')">
                                            <div class="alert alert-danger" role="alert" v-if="errors.floor">{{ $t('user.addresses.error_floor') }}</div>

                                            <input type="text" class="form-control" v-model="adding_data.flat" name="flat" id="flat" :placeholder="$t('user.addresses.flat_placeholder')" :title="$t('user.addresses.flat_placeholder')">
                                            <input type="text" class="form-control" v-model="adding_data.entrance" name="entrance" id="entrance" :placeholder="$t('user.addresses.entrance_placeholder')" :title="$t('user.addresses.entrance_placeholder')">
                                            <input type="text" class="form-control" v-model="adding_data.zip_code" name="zip code" id="zip code" :placeholder="$t('user.addresses.zip_placeholder')" :title="$t('user.addresses.zip_placeholder')">

                                            <div class="mt-2">
                                                <label class="text-muted small">{{ $t('user.checkout.map_location_label') }}</label>
                                                <textarea class="form-control" v-model="adding_data.map" name="map" id="map" rows="2" :placeholder="$t('user.checkout.map_placeholder')"></textarea>
                                            </div>

                                            <div class="form-check mt-2 mb-2">
                                                <input type="checkbox" class="form-check-input" id="payment_adres_default" v-model="adding_data.is_default">
                                                <label class="form-check-label" for="payment_adres_default">{{ $t('user.cart.set_as_default_label') }}</label>
                                            </div>

                                            <button
                                                type="button"
                                                :class="{'btn btn-primary': true}"
                                                @click="add_new_adres()"
                                            >
                                            {{ $t('common.save') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center" v-else-if="selected_adreses_id != '' && selected_adreses_id != 'your adres'">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="alert alert-secondary">
                                            <h2 class="text-center">{{ $t('user.checkout.your_address_title', { name: quick_adres.name }) }}</h2>
                                            <p>{{ $t('user.checkout.region_label', { value: quick_adres.region_id }) }}</p>
                                            <p>{{ $t('user.checkout.city_label', { value: quick_adres.city }) }}</p>
                                            <p>{{ $t('user.checkout.street_label', { value: quick_adres.strit }) }}</p>
                                            <p>{{ $t('user.checkout.number_label', { value: quick_adres.number }) }}</p>
                                            <p>{{ $t('user.checkout.floor_label', { value: quick_adres.floor }) }}</p>
                                            <p>{{ $t('user.checkout.flat_label', { value: quick_adres.flat }) }}</p>
                                            <p>{{ $t('user.checkout.entrance_label', { value: quick_adres.entrance }) }}</p>
                                            <p>{{ $t('user.checkout.zip_label', { value: quick_adres.zip_code }) }}</p>
                                            <p v-if="quick_adres.map">Map - <a :href="quick_adres.map" target="_blank" rel="noopener">{{ $t('user.checkout.view_location') }}</a></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center" v-else-if="selected_adreses_id == 'your adres'">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="alert alert-warning">
                                            <h2 class="text-center">{{ $t('user.checkout.select_shipping_address') }}</h2>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center" v-else>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="alert alert-danger">
                                            <h2 class="text-center">{{ $t('user.checkout.error_generic') }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row"><hr></div>
                                <h2 class="text-center">{{ $t('common.payment') }}</h2>
                                <span v-if="payment_error">
                                    <div class="alert alert-danger" role="alert">
                                        {{ $t('user.checkout.select_payment_error') }}
                                    </div>
                                </span>

                                <div class="row justify-content-center">
                                    <div class="col-md-12 col-xs-12">
                                        <select class="form-control" v-model="selected_payment_type" name="currency" >
                                            <option value="deliverd payment">{{ $t('user.checkout.payment_on_delivery_option') }}</option>
                                            <option v-if="shop_payment_enabled" value="online payment">{{ $t('user.checkout.online_payment_option') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center" v-if="selected_payment_type == 'deliverd payment'">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="alert alert-warning" role="alert">
                                            <p>{{ $t('user.checkout.cod_full_notice') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center" v-else-if="selected_payment_type == 'online payment'">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="alert alert-info" role="alert">
                                            <i class="fa fa-credit-card mr-2"></i>
                                            <p class="mb-0">{{ $t('user.checkout.online_notice2') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center" v-else>
                                    <div class="col-md-12 col-xs-12">
                                        <div class="alert alert-danger" role="alert">
                                            {{ $t('user.checkout.generic_error2') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center" v-if="shiping_region">
                            <div class="col-md-6">
                                <div class="alert alert-secondary">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">{{ $t('user.cart.subtotal_label') }}</span>
                                        <span>{{ subtotal.toFixed(2) }} ₾</span>
                                    </div>
                                    <div class="d-flex justify-content-between text-success" v-if="is_partner_member && partner_discount_percent > 0">
                                        <span>{{ $t('user.cart.partner_discount_label', { percent: partner_discount_percent }) }}</span>
                                        <span>−{{ ((subtotal * partner_discount_percent) / 100).toFixed(2) }} ₾</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
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
                                    <div v-else-if="shiping_region.free_shiping_price_after" class="alert alert-success py-1 px-2 mt-2 mb-0 small">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        {{ $t('user.cart.free_shipping_reached', { region: shiping_region.region }) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <!-- <div class="row">
                                    <h2 class="text-center">Vrite sale code</h2>

                                    <input type="text" class="form-control" v-model="enterd_sale_code" name="name" placeholder="Enter sale code" title="Enter sale code">
                                    <div class="alert alert-danger" role="alert" v-if="is_code_enterd">Enter sale code!!!</div>
                                </div>
                                <div class="row justify-content-center">
                                    <button
                                            type="button"
                                            :class="{'btn btn-primary': true}"
                                            @click="check_sale_code()"
                                        >
                                        Check code
                                    </button>
                                </div> -->
                                <div class="input-group mb-3">
                                    <input
                                        type="text"
                                        class="form-control"
                                        :placeholder="$t('user.checkout.sale_code_placeholder')"
                                        :aria-label="$t('user.checkout.sale_code_placeholder')"
                                        aria-describedby="basic-addon2"
                                        v-model="enterd_sale_code"
                                    />
                                    <div class="input-group-append">
                                        <button
                                            class="btn btn-primary"
                                            style="height: auto"
                                            type="button"
                                            @click="check_sale_code()"
                                        >
                                            {{ $t('user.checkout.search') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="row" v-if="sale_code == 'Sale code dint fined'">
                                    <div class="alert alert-danger" role="alert">{{ $t('user.checkout.sale_code_not_found') }}</div>
                                </div>
                                <div class="row" v-else-if="sale_code != 'Sale code dint fined' && sale_code.length != 0">
                                    <div class="alert alert-success" role="alert">{{ $t('user.checkout.discount_label', { value: sale_code_discount }) }}</div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import productDradcrumb from './items/BreadcrumbComponent.vue'
	
export default {
    components: {
        productDradcrumb,
    },
    props: [
    ],
    data: function () {
        return {
            selected_payment_type: 'deliverd payment',
            selected_adreses_id: 'your adres',
            shop_payment_enabled: false,

            payment_error: false,
            adreses_error: false,
            enterd_sale_code: '',
            is_code_enterd: false,
            
            adreses: [],

            adding_data: {
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
            errors: {
                demo_name: false,
                region_id: false,
                city: false,
                strit: false,
                number: false,
                floor: false,
            },
            quick_adres: [],
            regions: [],

            sale_code: [],
            sale_code_discount: 0,

            cart_items: [],
            subtotal: 0,
            shiping_region: null,
            shiping_cost: 0,
            is_partner_member: false,
            partner_discount_percent: 0,
        };
    },

    computed: {
        quantity_mismatch_items() {
            return this.cart_items.filter(p => this.is_quantity_mismatch(p))
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
        next_disabled() {
            return this.quantity_mismatch_items.length > 0
                || (!!this.shiping_region && this.min_ship_price_not_met)
        },
        next_disabled_reason() {
            if (this.quantity_mismatch_items.length) return this.$t('user.cart.fix_issues_to_checkout_tooltip')
            if (this.shiping_region && this.min_ship_price_not_met) {
                return this.$t('user.cart.min_order_price_warning', { region: this.shiping_region.region, amount: this.shiping_region.ship_min_price })
            }
            return ''
        },
    },

    mounted() {
        this.get_adres()
        this.get_products_cart()
        this.get_partner_status()

        axios.get('payment/status')
            .then(r => {
                this.shop_payment_enabled = !!r.data.shop_enabled
                if (!this.shop_payment_enabled && this.selected_payment_type === 'online payment') {
                    this.selected_payment_type = 'deliverd payment'
                }
            })
            .catch(() => { this.shop_payment_enabled = false })

        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },

    methods: {
        get_products_cart() {
            axios.get('cart').then(response => {
                this.cart_items = Array.isArray(response.data) ? response.data : []
                this.colculat_subtotal()
            }).catch(error => console.log(error))
        },
        is_quantity_mismatch(product) {
            if (product.is_out_of_stock) return false
            if (product.product && product.product.sale_type === 'produced_by_order') return false
            return product.stock_quantity != null && product.quantity > product.stock_quantity
        },
        update_to_max_quantity(product) {
            axios.post('cart/update_quantity/' + product.id, { quantity: product.stock_quantity })
                .then(() => { this.get_products_cart(); this.$bus.$emit('cart-updated') })
                .catch(error => console.log(error))
        },
        colculat_subtotal() {
            this.subtotal = this.cart_items.reduce((sum, p) => {
                const price = p.option && p.option.price ? parseFloat(p.option.price) : 0
                return sum + price * (p.quantity || 1)
            }, 0)
            this.shiping_cost = this.colculate_shiping_price(this.subtotal)
        },
        colculate_shiping_price(subtotal) {
            if (!this.shiping_region || !this.shiping_region.shiping_price) return 0
            if (this.shiping_region.free_shiping_price_after && subtotal >= parseFloat(this.shiping_region.free_shiping_price_after)) return 0
            return parseFloat(this.shiping_region.shiping_price)
        },
        get_shiping_region(region_id) {
            axios.get('get_shiped_region/get_activ_region/' + region_id)
                .then(response => {
                    this.shiping_region = response.data
                    this.colculat_subtotal()
                })
                .catch(error => {
                    console.log(error)
                    this.shiping_region = null
                })
        },
        get_partner_status() {
            axios.get('partner_organization/my_status')
                .then(response => {
                    this.is_partner_member = !!response.data.is_member
                    this.partner_discount_percent = this.is_partner_member && response.data.organization
                        ? parseFloat(response.data.organization.discount) || 0
                        : 0
                })
                .catch(error => console.log(error))
        },
        check_sale_code(){
            this.is_code_enterd = false
            if(this.enterd_sale_code == ''){
                this.is_code_enterd = true
            }
            else{
                axios
                .post("set_order/check_sale_code", {
                    serching_code: this.enterd_sale_code
                })
                .then(response => {
                    this.sale_code = response.data
                    if(this.sale_code != 'Sale code dint fined'){
                        this.sale_code_discount = response.data.discount
                    }
                })
                .catch(
                    error => console.log(error)
                );
            }
        },
        get_shipd_countries(){
            axios
            .get("get_shiped_region/get_all_shiped_regions")
            .then(response => {
                this.regions = response.data
            })
            .catch(
                error => console.log(error)
            );
        },
        get_activ_adres(adres_id){
            if(adres_id != '' && adres_id != 'new adres' && adres_id != 'your adres'){
                axios
                .get('get_activ_adres/'+adres_id)
                .then(Response => {
                    this.quick_adres = Response.data
                    this.get_shiping_region(Response.data.region_id)
                })
                .catch(error => console.log(error))
            }
            else if(adres_id == 'new adres'){
                this.get_shipd_countries()
                this.shiping_region = null
            }
        },

        get_adres() {
            axios
            .get('get_user_adreses')
            .then(Response => {
                this.adreses = Response.data

                if(this.selected_adreses_id === 'your adres'){
                    // Nothing picked yet — jump straight to the user's default
                    // address instead of making them pick it from the list.
                    const default_adres = this.adreses.find(a => a.is_default)
                    if (default_adres) {
                        this.selected_adreses_id = default_adres.id
                        this.get_activ_adres(default_adres.id)
                    }
                }
            })
            .catch(error => console.log(error))
        },
        go_back(){
            if (window.confirm(this.$t('user.checkout.confirm_go_back'))) {
                this.$router.go(-1)
            }
        },
        go_next(){
            this.payment_error = false
            this.adreses_error = false

            if (this.next_disabled) {
                return
            }

            if(this.selected_payment_type == ''){
                this.payment_error = true
            }
            else if(this.selected_adreses_id == "" || this.selected_adreses_id == "your adres" || this.selected_adreses_id == "new adres"){
                this.adreses_error = true
            }
            else if(this.selected_payment_type && this.selected_adreses_id){
                this.$router.push({
                                    name: "orderDecloration",
                                    params: {
                                        payment: this.selected_payment_type,
                                        adres: this.selected_adreses_id,
                                        discount: this.sale_code_discount,
                                        sale_code: this.sale_code_discount > 0 ? this.enterd_sale_code : '',
                                    }
                });
            }
        },

        add_new_adres() {
            this.errors = {
                demo_name: false,
                region_id: false,
                city: false,
                strit: false,
                number: false,
                floor: false,
            }
            if(
                this.adding_data.demo_name &&
                this.adding_data.region_id &&
                this.adding_data.city &&
                this.adding_data.strit &&
                this.adding_data.number &&
                this.adding_data.floor
            ){
                axios
                .post('add_user_adreses', {
                    adding_data: this.adding_data,

                    _method: 'POST'
                })
                .then(Response => {
                    this.adding_data = {
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
                        is_default: !this.adreses.length,
                    }
                    // The new address's id comes straight back from the API,
                    // so select it directly instead of assuming it's last in
                    // a freshly refetched list.
                    if (Response.data && Response.data.id) {
                        this.selected_adreses_id = Response.data.id
                        this.get_activ_adres(Response.data.id)
                    }
                    this.get_adres()
                })
                .catch(error => console.log(error))
            }
            else{
                if(!this.adding_data.demo_name){
                    this.errors.demo_name = true
                }
                if(!this.adding_data.region_id){
                    this.errors.region_id = true
                }
                if(!this.adding_data.city){
                    this.errors.city = true
                }
                if(!this.adding_data.strit){
                    this.errors.strit = true
                }
                if(!this.adding_data.number){
                    this.errors.number = true
                }
                if(!this.adding_data.floor){
                    this.errors.floor = true
                }
            }
        },
    }
}
</script>

<style>

</style>