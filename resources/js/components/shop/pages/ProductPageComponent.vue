<template>
    <div>
        <div class="product-detail">
            <div class="container">
                <div class="_cont detail-top">
                    <div class="cols">
                        <div class="col-md-6">
                            <div class="big">
                                <div class="container">
                                    <lingallery :items="items"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-10 product_page_title">
                                    <h1>{{ local_product.title }}</h1>
                                </div>
                                <div class="col-md-2">
                                    <a href="" class='text-center'> 
                                        <i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%;"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="price-shipping">
                                        <div class="price" id="price-preview">

                                            <div class="price_pege">
                                                <p :title="price">
                                                ₾ {{ price }}
                                                </p>
                                            </div>

                                            <div class="old_price_page" v-if="old_price">
                                                <p :title="old_price">
                                                ₾ {{ old_price }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Add to cart</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control" v-model="product_modification_for_cart" name="product_modification_for_cart" @click="select_option()">
                                        <option>All</option>
                                        <option v-for="option in options" :key='option.option.id' :value="option.option.id" >{{ option.option.title }}</option> 
                                    </select> 
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" min="1" max='10' v-model="products_quantity" />
                                </div>
                                <div class="col-md-2">
                                    <a @click="add_to_cart()" class='text-center'> 
                                        <i class="fa fa-cart-plus" aria-hidden="true" style="font-size: 250%;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class='text-center'>Description</h2>
                    <!-- <p>{{ local_product.text }}</p> -->
                    <span v-html="local_product.text"></span>
                </div>
            </div>
        </div>
        <div class="container" v-if="this.products.length > 0">
            <div class="row related">
                <h2>{{__ ('you might also like') }}</h2>
                <analogProduct
                    v-for="product in products"
                    :key='product.id'
                    :product="product"
                ></analogProduct>
            </div>
            <div class="row">
                <div class="more-products" id="more-products-wrap">
                    <li><router-link :to="{name: 'catalog'}" exact> <span> All products </span> </router-link></li>
                    <!-- <a href=""><span id="more-products" data-rows_per_page="1">@lang('shop.all products')</span></a> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import analogProduct from '../items/AnalogProductComponent.vue'
    import lingallery from 'lingallery'; // https://github.com/ChristophAnastasiades/Lingallery
    import VueMeta from 'vue-meta'
    export default {
        components: {
            lingallery,
            VueMeta,
            analogProduct
        },
        data () {
            return {
                images: [],
                products: [],
                local_product: [],
                global_product: [],
                options: [],
                // cart_options: [],
                product_modification_for_cart: 'All',
                products_quantity: 1,
                price: '',
                old_price: '',
                prices: [],

                items: [],
            }
        },
        watch: {
            '$route' (to, from) {
                // this.get_analog_products()
                this.get_product()
                window.scrollTo(0,0)
                this.images = [],
                this.products = [],
                this.local_product = [],
                this.global_product = [],
                this.options = [],
                this.product_modification_for_cart = '',
                this.items = [],
                this.price = '',
                this.old_price = '',
                this.prices = []
            }
        },
        mounted() {
            this.get_product()
        },
        methods: {
            get_analog_products(cat_id){
                axios
                .put('../api/products/'+cat_id)
                .then(response => {
                    this.products = response.data
                })
                .catch(error =>{
                })
            },
            get_product(){
                axios
                .get('../api/products/'+this.$route.params.url_title)
                .then(response => {
                    this.local_product = response.data[0]['local_product'][0];
                    this.global_product = response.data[0]['global_product'];
                    this.options = response.data[0]['product_options'];
                    
                    this.options.forEach(option => {
                        this.prices.push(option.option.price)
                        option.images.forEach(image => {
                            this.items.push({
                                src: '../images/product_img/'+image.image,
                                thumbnail: '../images/product_img/'+image.image,
                                caption: option.option.title,
                                id:  option.option.id
                            })
                        });
                    });

                    this.array2 = Math.max.apply(null, this.array1);
                    if(this.prices.length <= 1){
                        this.price = this.prices[0]
                    }
                    else{
                        this.price = Math.min.apply(null, this.prices) + '-' + Math.max.apply(null, this.prices)
                    }

                    this.get_analog_products(this.global_product.id)
                })
                .catch(error =>{
                })
            },
            select_option(){
                this.items = [];
                if (this.product_modification_for_cart == "All") {
                    this.get_product()
                }
                else{
                    this.options.forEach(option => {
                        if (this.product_modification_for_cart == option.option.id) {
                            this.price = option.option.price
                            option.images.forEach(image => {
                                this.items.push({
                                    src: '../images/product_img/'+image.image,
                                    thumbnail: '../images/product_img/'+image.image,
                                    caption: option.option.title,
                                    id:  option.option.id
                                })
                            });
                        }
                    });
                }
            },
            add_to_cart(){
                if(this.product_modification_for_cart == "All"){
                    alert('plis select option')
                }
                else{
                    // this.cart_options = {"modification_id": this.product_modification_for_cart, "quantity": this.products_quantity}
                    axios
                    .put('../api/cart/'+this.product_modification_for_cart, {
                        modification_id: this.product_modification_for_cart,
                        quantity: this.products_quantity
                    })
                    .then(response => {
                    })
                    .catch(error =>{
                    })
                }
            }
        }
    }
</script>

<style>
    .lingalleryContainer[data-v-40681078] .lingallery figure {
        height: 100% !important;
    }
</style>
