<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="container purchase overflow-auto">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary pull-left" @click="go_back">Back</button>
                        </div>
                        <!-- <div class="col-md-6">
                            <router-link :to="{name: 'orderPayment'}" exact>
                                <button class="btn btn-primary float-right" >Next</button>
                            </router-link>
                        </div> -->

                        <div class="col-md-6">
                            <button class="btn btn-primary float-right" @click="create_order()">Complete order decloration</button>
                        </div>
                    </div>
    
                    <div class="row">
                        <productDradcrumb :props_page="'product_list'"/>
                    </div>


                    <div class="row justify-content-center" v-if="create_order_loading">
                        <div class="col-md-4">
                            <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
                        </div>
                    </div>

                    <div class="row" v-else-if="!create_order_loading">
                        <div class="col-md-6">
                            
                            <div class="alert alert-secondary">
                                <h2 class="text-center">Your adres - {{ activ_adres.name }} 
                                    (<span class="cursor_pointre" @click="adres_show = !adres_show" v-if="!adres_show">Show</span>
                                    <span class="cursor_pointre" @click="adres_show = !adres_show" v-else-if="adres_show">Close</span>)
                                </h2>

                                <span v-if="adres_show">
                                    <p>Country - {{ activ_adres.country_id }}</p>
                                    <p>City - {{ activ_adres.city }}</p>
                                    <p>Strit - {{ activ_adres.strit }}</p>
                                    <p>Number - {{ activ_adres.number }}</p>
                                    <p>Floor - {{ activ_adres.floor }}</p>
                                    <p>Flat - {{ activ_adres.flat }}</p>
                                    <p>Entrance - {{ activ_adres.entrance }}</p>
                                    <p>Zip Code - {{ activ_adres.zip_code }}</p>
                                </span>

                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-secondary">
                                <h2>{{ $route.params.payment }}</h2>
                            </div>
                        </div>
                    </div>
                        
                    <!-- {{ $route.params.payment }}
                    {{ $route.params.adres }} -->

                    <div class="row" v-if="!create_order_loading">
                        <div class="col-sm-12 col-xs-12 table-responsive">
                            <table class="table table-condensed" border="0" cellspacing="0" cellpadding="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center col-xs-1 col-sm-1">#</th>
                                        <th class="text-center col-xs-7 col-sm-7">Name</th>
                                        <th class="text-center col-xs-1 col-sm-1">Qty</th>
                                        <th class="text-right col-xs-3 col-sm-3">Amount</th>
                                        <th class="text-right col-xs-3 col-sm-3">Del</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in cart_items" :key="product.id">
                                        <td class="col-xs-1 col-sm-1 text-center">1</td>
                                        <td class="text-center">{{ product.product.url_title }}</td>
                                        <td class="text-center">
                                            {{ product.quantity }} 
                                            <!-- <input type="number" @click="update_quantity(product.id, product.quantity)" v-model="product.quantity" step="1" max="99" min="1" title="Qty" class="qty"size="4"> -->
                                            Pcs
                                        </td>
                                        <td class="text-right">
                                            Price 
                                            {{ colculat_items_price(product.option.price, product.quantity) }} ₾ ({{ product.option.price }} ₾)
                                        </td>
                                        <td class="text-right"><button @click="del_from_cart(product.id)" class="btn btn-danger">X</button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="background: #fff;"></th>
                                        <th class="text-right">Price after discounting</th>
                                        <th class="text-right">333 ₾</th>
                                    </tr>
                                </tfoot>
                                <tfoot>
                                    <tr>
                                        <th colspan="2" style="background: #fff;"></th>
                                        <th class="text-right">Shiping</th>
                                        <th class="text-right"> Free shiping after - {{shiping_country.free_shiping_price_after}} ₾ </th>
                                        <th class="text-right">{{ shiping }} ₾</th>
                                    </tr>
                                </tfoot>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="background: #fff;"></th>
                                        <th class="text-right">Total price</th>
                                        <th class="text-right">{{ total_price }} ₾</th>
                                    </tr>
                                </tfoot>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="background: #fff;"></th>
                                        <th class="text-right">Discount</th>
                                        <th class="text-right">{{ $route.params.discount }} %</th>
                                    </tr>
                                </tfoot>
                            </table>
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
            productDradcrumb
        },
        props: [
            // 'news',
        ],
        data: function () {
            return {
                cart_items: [],
                is_products_refresh: false,
                products_reset_id: 0,

                quantity: '',

                total_price: 0,
                price: 0,
                user_id: 0,
                // test_arr: ['test 1', 'test 2'],
                payment: '',
                adres: '',
                activ_adres: '',
                adres_show: false,

                shiping_country: [],
                shiping: 0,

                order_product_list: '',
                payment_tupe: '',
                adres: '',

                create_order_loading: false,
            };
        },
        mounted() {
            if (this.$route.params.payment || this.$route.params.adres) {
                this.payment = this.$route.params.payment
                this.adres = this.$route.params.adres

                // this.order_product_list = this.cart_items
                // this.payment_tupe = this.$route.params.payment
                // this.adres = this.$route.params.adres
            } 
            else {
                this.go_back()
            }

            this.get_products_cart()
        },

        methods: {
            go_back(){
                this.$router.go(-1)
            },

            get_products_cart: function() {
                axios
                .get("../../../../api/cart/")
                .then(response => {
                    this.cart_items = response.data
                    // this.order_product_list = this.cart_items
                    this.user_id = response.data[0]['user_id']

                    this.get_activ_adres(this.$route.params.adres)
                })
                .catch(
                    error => console.log(error)
                );
            },
            colculat_total_price() {
                this.total_price = 0
                this.price = 0

                this.cart_items.forEach(product => {
                    if (product.quantity > 1) {
                        this.price = product.quantity * product.option.price
                    }
                    else{
                        this.price = parseInt(product.option.price)
                    }
                    this.total_price = this.total_price + this.price
                });

                if(this.shiping_country.free_shiping_price_after && this.shiping_country.free_shiping_price_after <= this.total_price){
                    this.shiping = 0
                }
                else if(this.shiping_country.free_shiping_price_after && this.shiping_country.free_shiping_price_after > this.total_price){
                    this.shiping = this.shiping_country.shiping_price
                }
                else if(!this.shiping_country.free_shiping_price_after){
                    this.shiping = this.shiping_country.shiping_price
                }
                else{
                    this.shiping = this.shiping_country.shiping_price
                }
                this.shiping = Number(this.shiping)

                this.total_price = this.total_price + this.shiping
            },
            colculat_items_price(price, quantyty) {
                var colculated_price = price * quantyty
                return colculated_price
            },
            update_quantity(item_id, quantity){
                axios
                .post("../../../../api/cart/update_quantity/" + item_id, {
                    quantity: quantity,
                })
                .then(response => {
                    this.get_products_cart()
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_from_cart(item_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .delete("../../../../api/cart/" + item_id)
                    .then(response => {
                        this.get_products_cart()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            get_activ_adres(adres_id){
                axios
                .get('../../../api/get_activ_adres/'+adres_id)
                .then(Response => {
                    this.activ_adres = Response.data
                    this.get_activ_country(Response.data.country_id)
                })
                .catch(error => console.log(error))
            },

            get_activ_country(adres_id){
                axios
                .get('../../../api/get_activ_country/'+adres_id)
                .then(Response => {
                    this.shiping_country = Response.data
                    this.colculat_total_price()
                })
                .catch(error => console.log(error))
            },

            create_order(){
                this.create_order_loading = true
                axios
                .post('../../../api/order/',{
                    order_product_list: this.cart_items,
                    payment_tupe: this.$route.params.payment,
                    adres: this.$route.params.adres,
                    shiping: this.shiping,

                    _method: 'POST'
                })
                .then(Response => {
                    this.$router.push({ 
                        name: 'confirmOrder', 
                        params: { 
                            order_decloration: true
                        }
                    })
                })
                .catch(error => console.log(error))
                .finally(() => this.create_order_loading = false);
            },
        }
    }
</script>

<style scoped>
.purchase
{
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px;
    font-family: Times New Roman;
}
.purchase header
{
    padding: 0px 0px 0px 0px;
    margin-bottom: 0px;
    border-bottom: 1px solid #3989c6;
}
.purchase header img
{
    max-width: 200px;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .company-details
{
    text-align: right;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase main
{
    padding: 0px 0px;
    margin-bottom: 0px;
}
.purchase .to-details
{
    text-align: left;
}
.purchase .to-name
{
    font-weight: bold;
}
.purchase .to-name .to-address .to-city
{
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .purchase-info
{
    text-align: right;
}
.purchase-info .info-code
{
    font-weight: bold;
}
.purchase-info .info-code .info-date
{
    margin-top: 0;
    margin-bottom: 0;
}

/* .cursor_pointre */
</style>