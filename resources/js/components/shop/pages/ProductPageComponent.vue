<template>
    <div>
        <div class="product-detail">
            <div class="container">
                <div class="_cont detail-top">
                    <div class="cols">
                        <div class="col-md-6">
                            <div class="big">
                                <div class="container" v-if="items.length">
                                    <lingallery :items="items"/>
                                </div>
                                <div class="container" v-else>
                                    <site-img :src="'../../../public/images/site_img/shop_demo.jpg'" :alt="product.local_product[0].title" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-10 product_page_title">
                                    <h1>{{ product.local_product[0].title }}</h1>
                                    <breadcrumb />
                                </div>
                                <div class="col-md-2 favorites_icon" @click="add_to_faworite(product.global_product.id)">
                                    <i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%;"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="price-shipping">
                                        <div class="price" id="price-preview">

                                                <!-- <div class="old_price_page">
                                                    <p :title="old_price">
                                                        ₾ {{ prices[0] }} - {{ prices[1] }}
                                                    </p>
                                                </div>
                                                <div class="price_pege">
                                                    <p :title="price">
                                                        ₾ {{ prices[0] }} - {{ prices[1] }}
                                                    </p>
                                                </div> -->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="product.global_product.sale_category == 'online order'">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 style="margin-bottom: 0;">Add to cart</h3>
                                    </div>
                                    <span v-if="products_quantity == select_product_max_quantyty">
                                        <div class="alert alert-danger" role="alert">
                                            This is maximal quantyty for this product!!!
                                        </div>
                                    </span>
                                    <span v-if="is_adding_in_cart_socsesful">
                                        <div class="alert alert-success" role="alert">
                                            Product add successful!!!
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control" v-model="product_modification_for_cart" name="product_modification_for_cart" @click="select_option()">
                                            <option>All</option>
                                            <option v-for="option in product.product_option" :key='option.option.id' :value="option.option.id" >{{ option.option.name }}</option> 
                                        </select> 
                                    </div>
                                    <div class="col-md-4" v-if="product_modification_for_cart != 'All'">
                                        <input type="number" class="form-control" min="1" :max="select_product_max_quantyty" v-model="products_quantity" />
                                    </div>
                                    <div class="col-md-2" v-if="product_modification_for_cart != 'All'">
                                        <a @click="add_to_cart()" class='text-center'> 
                                            <i class="fa fa-cart-plus" aria-hidden="true" style="font-size: 250%;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-if="product.global_product.sale_category == 'custom production'">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 style="margin-bottom: 0; float: left;">Custom production</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" v-if="product.local_product[0]">
            <div class="row" v-if="product.local_product[0].text">
                <div class="col-md-12">
                    <h2 class='text-center'>Description</h2>
                    <span v-html="product.local_product[0].text"></span>
                </div>
            </div>
            <div class="row" v-if="product.global_product.mead_in_georgia">
                <div class="col-md-12">
                    <img class="mead_in_geo_img" :src="'../images/site_img/mead in georgia.png'" :alt="'Mead in Georgia (' + product.local_product[0].title + ')'"/>
                </div>
            </div>
        </div>

        <!-- <div class="container">
            <div class="row related text-center" v-if="this.samilar_products.length > 0">
                <h2>you might also like</h2>
                <analogProduct
                    v-for="product in samilar_products"
                    :key='product.id'
                    :product="product"
                ></analogProduct>
            </div>
            <div class="row">
                <hr style="margin: 5.5%;">
            </div>
            <div class="row">
                <div class="more-products text-center" id="more-products-wrap">
                    <li><router-link :to="{name: 'catalog'}" exact> <span> All products </span> </router-link></li>
                </div>
            </div>
        </div> -->

        <div class="container">
            <similarProduct :activ_product_id=product.global_product.id />
        </div>
        
        <div class="container">
            <div class="row">
                <div class="more-products" id="more-products-wrap">
                    <li><router-link :to="{name: 'catalog'}" exact> <span> All products </span> </router-link></li>
                </div>
            </div>
        </div>

        <metaData 
            :title = "product.local_product[0].title"
            :description = "product.local_product[0].short_description"
            :image = "'../../../../public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    // import analogProduct from '../items/AnalogProductComponent.vue'
    import lingallery from 'lingallery'; // https://github.com/ChristophAnastasiades/Lingallery
    import VueMeta from 'vue-meta'
    import metaData from '../items/MetaDataComponent'
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    import similarProduct from '../items/SimilarProductComponent.vue'
    export default {
        components: {
            metaData,
            lingallery,
            VueMeta,
            // analogProduct,
            breadcrumb,
            similarProduct
        },
        data () {
            return {
                is_adding_in_cart_socsesful: false,
                // products: [],
                // local_product: [],
                // global_product: [],
                // options: [],
                select_product_max_quantyty: 0,
                product_modification_for_cart: 'All',
                products_quantity: 1,
                add_to_cart_message: '',
                items: [],
                // price: '',
                // old_price: '',
                prices: [],

                product: [],
                // products: [],

                samilar_products: [],
            }
        },
        watch: {
            '$route' (to, from) {
                this.get_product()
                this.get_analog_products()

                window.scrollTo(0,0)

                // this.images = [],
                // this.products = [],
                // this.local_product = [],
                // this.global_product = [],
                // this.options = [],
                // this.product_modification_for_cart = '',
                // this.items = [],
                // this.price = '',
                // this.old_price = '',
                // this.prices = []
            }
        },
        mounted() {
            this.get_product()
            // this.get_analog_products()
        },
        methods: {
            // get_analog_products(product_id){
            //     axios
            //     .get('../api/similar_product/'+localStorage.getItem('lang')+'/'+product_id)
            //     .then(response => {
            //         this.samilar_products = response.data
            //     })
            //     .catch(error =>{
            //     })
            // },

            get_product(){
                this.product = []
                axios
                .get('../api/product/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {

                    this.product = response.data[0]

                    // this.local_product = response.data[0]['local_product'][0];
                    // this.global_product = response.data[0]['global_product'];
                    // this.options = response.data[0]['product_options'];
                    
                    this.product.product_option.forEach(option => {
                        this.prices.push(option.option.price)
                        // console.log("🚀 ~ file: ProductPageComponent.vue ~ line 206 ~ get_product ~ option.images", option.images)
                        if(option.images.length){
                            option.images.forEach(image => {
                                this.items.push({
                                    src: '../images/product_img/'+image.image,
                                    thumbnail: '../images/product_img/'+image.image,
                                    caption: option.option.title,
                                    id:  option.option.id
                                })
                            });
                        }
                        else{
                            this.items = []
                        }
                    });

                    // this.array2 = Math.max.apply(null, this.array1);
                    // if(this.prices.length <= 1){
                    //     this.price = this.prices[0]
                    // }
                    // else{
                    //     this.price = Math.min.apply(null, this.prices) + '-' + Math.max.apply(null, this.prices)
                    // }

                    this.get_analog_products(this.product.global_product.id)
                })
                .catch(error =>{
                })
            },

            select_option(){
                this.items = [];
                this.is_adding_in_cart_socsesful = false
                if (this.product_modification_for_cart == "All") {
                    this.get_product()
                }
                else{
                    this.product.product_option.forEach(option => {

                        this.select_product_max_quantyty = option.option.quantity

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
                    this.is_adding_in_cart_socsesful = false
                    // this.cart_options = {"modification_id": this.product_modification_for_cart, "quantity": this.products_quantity}
                    axios
                    .put('../api/cart/'+this.product_modification_for_cart, {
                        modification_id: this.product_modification_for_cart,
                        quantity: this.products_quantity
                    })
                    .then(response => {
                        this.add_to_cart_message = response
                        this.is_adding_in_cart_socsesful = true
                        // this.add_to_cart_message = "Product added in your cart"
                    })
                    .catch(error =>{
                        this.add_to_cart_message = 'Something went wrong. Try login.'
                    })
                }
            },

            add_to_faworite(product_id){
                axios
                .post('../api/add_to_favorite/'+ product_id)
                .then(response => {
                    alert("Product addid in your favorite list!");
                })
                .catch(error =>{
                    alert("Error");
                })
            }
        }
    }
</script>

<style>
    .lingalleryContainer[data-v-40681078] .lingallery figure {
        height: 100% !important;
    }

    .mead_in_geo_img{
        width: 20%;
        height: auto;
        margin-left: 40%;
    }

    .favorites_icon{
        cursor: pointer;
    }
</style>
