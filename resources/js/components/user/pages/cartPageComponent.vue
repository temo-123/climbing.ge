<template>
    <!-- <div class="container"> -->
        <div class="row">
            <!-- <div class="col-sm-3"> -->
                <left-menu />
            <!-- </div> -->
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
                                Shipping cart
                                <button class="btn btn-primary float-right" @click="goToShop()">Go to product page</button>
                                <!-- <button type="button" @click="cont_shopping()" class="btn btn-success pull-right">Continiu shopping</button>
                                <div class="clearfix"></div> -->
                            </div>
                            <div class="card-body">
                                    <!-- PRODUCT -->
                                    <div v-for="product in cart_items" :key="product.id">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-3 text-center">
                                                <img v-if="product.product_image" class="img-responsive" :src="'../images/product_option_img/'+product.product_image" :alt="product.product.url_title">
                                                <img v-else class="img-responsive" :src="'../../../public/images/site_img/shop_demo.jpg'" :alt="product.product.url_title">
                                            </div>
                                            <div class="text-sm-center col-sm-12 text-md-left col-md-4">
                                                <h4 class="product-name"><strong>{{product.product.url_title}}</strong></h4>
                                            </div>
                                            <div class="text-sm-center col-md-5 text-md-right row">
                                                <div class="row">
                                                    <div class="col-6 col-sm-6 col-md-6 text-md-right" style="padding-top: 5px">
                                                        <h6><strong>Total price {{ colculat_items_price(product.option.price, product.quantity) }} ₾ (Single product price{{ product.option.price }} ₾)</strong></h6>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-6">
                                                        <div class="row">
                                                            <div class="quantity text-right">
                                                                <!-- Quantity -->
                                                                <input type="number" v-if="!is_quantity_updating" @click="update_quantity(product.id, product.quantity)" v-model="product.quantity" step="1" :max="product.option.quantity" min="1" title="Quantity" class="Quantity" size="4">
                                                                <span class="" v-else-if="is_quantity_updating">Updating...</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button @click="del_from_cart(product.id)" class="btn btn-danger">X</button>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <!-- END PRODUCT -->
                                <div class="pull-right">
                                    <button type="button" @click="get_products_cart" class="btn btn-secondary" v-if="!is_products_refresh">Update cart ({{products_reset_id}})</button>
                                    <span class="badge badge-primare mb-1 pull-right" v-if="is_products_refresh">Updating...</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <!-- <div class="coupon col-md-5 col-sm-5 no-padding-left pull-left">
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" class="form-control" placeholder="cupone code">
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-primary">Primary</button>
                                        </div>'/order/order_decloration/'
                                    </div>
                                </div> -->
                                <div class="pull-right" style="margin: 10px" v-if="cart_items.length">
                                    <router-link :to="{name: 'orderPayment', params: {user_id: user_id}}">
                                        <button type="button" class="btn btn-success pull-right">Continue shopping</button>
                                    </router-link>
                                    <div class="pull-right" style="margin: 5px">
                                        Total price: <b>{{ total_price }} ₾</b>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div> -->
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
        },
        props: [
        ],
        data(){
            return {
                cart_items: [],
                is_products_refresh: false,
                products_reset_id: 0,

                quantity: '',

                total_price: 0,
                price: 0,
                user_id: 0,

                MIX_SHOP_URL: process.env.MIX_SHOP_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                is_quantity_updating: false,
            }
        },
        mounted() {
            this.get_products_cart()
        },
        methods: {
            goToShop(){
                window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL)
            },

            get_products_cart: function() {
                axios
                .get("/cart/")
                .then(response => {
                    this.cart_items = response.data
                    this.user_id = response.data[0]['user_id']
                    // this.is_products_refresh = false
                    this.products_reset_id++
                    this.colculat_total_price()
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
            },
            colculat_items_price(price, quantyty) {
                var colculated_price = price * quantyty
                return colculated_price
            },
            update_quantity(item_id, quantity){
                this.is_quantity_updating = true
                axios
                .post("/cart/update_quantity/" + item_id, {
                    quantity: quantity,
                })
                .then(response => {
                    this.get_products_cart()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_quantity_updating = false);
            },
            del_from_cart(item_id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .delete("/cart/" + item_id)
                    .then(response => {
                        this.get_products_cart()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            // cont_shopping(){
            //     window.location.href = "../order/cont_shopping/";
            // }
        }
    }
</script>