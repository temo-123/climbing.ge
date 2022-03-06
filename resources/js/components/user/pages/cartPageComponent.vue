<template>
    <div class="container">
        <div class="card shopping-cart">
            <div class="card-header bg-dark text-light">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                Shipping cart
                <button type="button" @click="cont_shopping()" class="btn btn-success pull-right">Continiu shopping</button>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                    <!-- PRODUCT -->
                    <div v-for="product in products" :key="product.id">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-3 text-center">
                                <img class="img-responsive" :src="'../images/product_img/'+product.product_image" :alt="product[0][0].url_title">
                            </div>
                            <div class="col-12 text-sm-center col-sm-12 text-md-left col-md-5">
                                <h4 class="product-name"><strong>{{product[0][0].url_title}}</strong></h4>
                            </div>
                            <div class="col-12 col-sm-12 text-sm-center col-md-4 text-md-right row">
                                <div class="row">
                                <div class="col-3 col-sm-3 col-md-6 text-md-right" style="padding-top: 5px">
                                    <h6><strong>{{product[0][0].price}} {{product[0][0].currency}} </strong></h6>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4">
                                    <div class="quantity">
                                        <input type="number" @click="update_quantity(product.id, product.quantity)" v-model="product.quantity" step="1" max="99" min="1" title="Qty" class="qty"
                                            size="4">
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
                        </div>
                    </div>
                </div> -->
                <div class="pull-right" style="margin: 10px">
                    <button type="button" @click="cont_shopping()" class="btn btn-success pull-right">Continiu shopping</button>
                    <div class="pull-right" style="margin: 5px">
                        Total price: <b>{{ total_price }}€</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
        ],
        data(){
            return {
                products: [],
                is_products_refresh: false,
                products_reset_id: 0,

                total_price: 0,

                price: 0,

                quantity: '',
            }
        },
        mounted() {
            this.get_products_cart()
        },
        methods: {
            get_products_cart: function() {
                axios
                .get("../api/cart/")
                .then(response => {
                    // this.products = response.data
                    // this.is_products_refresh = false
                    // this.products_reset_id++
                    // this.colculat_total_price()
                })
                .catch(
                    error => console.log(error)
                );
            },
            colculat_total_price() {
                this.total_price = 0
                this.price = 0
                this.products.forEach(product => {
                    if (product.quantity > 1) {
                        this.price = product.quantity * product[0][0].price
                    }
                    else{
                        this.price = parseInt(product[0][0].price)
                    }
                    this.total_price = this.total_price + this.price
                });
            },
            update_quantity(item_id, quantity){
                axios
                .post("./api/cart/update_quantity/" + item_id, {
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
                axios
                .post("./api/cart/del_item/" + item_id)
                .then(response => {
                    this.get_products_cart()
                })
                .catch(
                    error => console.log(error)
                );
            },

            cont_shopping(){
                window.location.href = "../order/cont_shopping/";
            }
        }
    }
</script>