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
                                    <button class="btn btn-primary float-right" @click="go_next()">{{ $t('user.checkout.next') }}</button>
                                <!-- </router-link> -->
                            </div>
                        </div>

                        <div class="row">
                            <productDradcrumb :props_page="'payment'"/>
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
                                            <option value="online payment">{{ $t('user.checkout.online_payment_option') }}</option>
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
        };
    },

    mounted() {
        this.get_adres()
        
        document.querySelector('body').style.marginLeft = '0';
        document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },

    methods: {
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
                })
                .catch(error => console.log(error))
            }
            else if(adres_id == 'new adres'){
                this.get_shipd_countries()
            }
        },

        get_adres(last_adres = false) {
            axios
            .get('get_user_adreses')
            .then(Response => {
                this.adreses = Response.data

                if(last_adres){
                    this.get_last_adres()
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
                    this.get_adres(true)
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
                    }
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
        get_last_adres(){
            var select_new_adres = this.adreses[this.adreses.length - 1].id
            this.selected_adreses_id = select_new_adres
            this.get_activ_adres(this.selected_adreses_id)
        }
    }
}
</script>

<style>

</style>