<template>
    <div>
        <div class="row" v-if="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../../../public/images/site_img/loading.gif'" alt="loading" class="article_loader">
            </div>
        </div>
        <div class="product-detail" v-if="!is_loading">
            <div class="container">
                <div class="_cont detail-top">
                    <div class="cols">
                        <div class="col-md-6">
                            <div class="big">
                                <div class="container" v-if="items.length">
                                    <lingallery :items="items"/>
                                </div>
                                <div class="container" v-else>
                                    <shop-img :src="'/public/images/site_img/demo_imgs/shop_demo.jpg'" :alt="product.locale_product.title" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-10 product_page_title">
                                    <h1>{{ product.locale_product.title }}</h1>
                                    <breadcrumb />
                                </div>
                                <div class="col-md-2 favorites_icon" @click="add_to_faworite(product.global_product.id)">
                                    <i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%;"></i>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="price-shipping" v-if="product_modification_for_cart == 'All'">
                                        <div class="price" id="price-preview" v-if="product.global_product.discount != null && product.global_product.discount > 0">
                                            <div class="price_pege">
                                                <p title="price" v-if="product.new_min_price != product.new_max_price">
                                                    ₾ {{ product.new_min_price }} - {{ product.new_max_price }}
                                                </p>
                                                <p title="price" v-else-if="product.new_min_price == product.new_max_price">
                                                    ₾ {{ product.new_min_price }} 
                                                </p>
                                            </div>

                                            <div class="old_price_page">
                                                <p title="old_price" v-if="product.min_price != product.max_price">
                                                    ₾ {{ product.min_price }} - {{ product.max_price }}
                                                </p>
                                                <p title="old_price" v-else-if="product.min_price == product.max_price">
                                                    ₾ {{ product.min_price }} 
                                                </p>
                                            </div>
                                        </div>
                                        <div class="price" id="price-preview" v-else>
                                            <div class="price_pege">
                                                <p title="price" v-if="product.min_price != product.max_price">
                                                    ₾ {{ product.min_price }} - {{ product.max_price }}
                                                </p>
                                                <p title="price" v-else-if="product.min_price == product.max_price">
                                                    ₾ {{ product.min_price }} 
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="price-shipping" v-else>
                                        <div class="price" id="price-preview" v-if="product.global_product.discount != null && product.global_product.discount > 0">
                                            <div class="price_pege">
                                                <p title="price">
                                                    ₾ {{ actyve_price.new_price }} 
                                                </p>
                                            </div>

                                            <div class="old_price_page">
                                                <p title="price">
                                                    ₾ {{ actyve_price.price }} 
                                                </p>
                                            </div>
                                        </div>
                                        <div class="price" id="price-preview" v-else>
                                            <div class="price_pege">
                                                <p title="price">
                                                    ₾ {{ actyve_price.price }} 
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="product.global_product.sale_type == 'online_order'">
                                <div class="row">
                                    <span v-if="products_quantity == select_product_max_quantyty">
                                        <div class="alert alert-danger" role="alert">
                                            {{ $t('shop.max products') }}
                                        </div>
                                    </span>
                                    <span v-if="is_adding_in_cart_socsesful">
                                        <div class="alert alert-success" role="alert">
                                            {{ $t('shop.add successful') }}
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <select class="form-control" v-model="product_modification_for_cart" name="product_modification_for_cart" @click="select_option()">
                                            <option>All</option>
                                            <option v-for="option in product.product_option" :key='option.option.id' :value="option.option.id" >{{ option.option.name }}</option> 
                                        </select> 
                                    </div>
                                    <div class="col-sm-4 col-md-4" v-if="product_modification_for_cart != 'All'">
                                        <input type="number" class="form-control" min="1" :max="select_product_max_quantyty" v-model="products_quantity" />
                                    </div>
                                    <div class="col-sm-2 col-md-2" v-if="product_modification_for_cart != 'All' && user.length != 0">
                                        <a @click="add_to_cart()" class='text-center' v-if="user.length != 0"> 
                                            <i class="fa fa-cart-plus" aria-hidden="true" style="font-size: 250%;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-if="product.global_product.sale_type == 'custom_production'">
                                <div class="col-sm-6 col-md-6">
                                    <select class="form-control" v-model="product_modification_for_cart" name="product_modification_for_cart" @click="select_option()">
                                        <option>All</option>
                                        <option v-for="option in product.product_option" :key='option.option.id' :value="option.option.id" >{{ option.option.name }}</option> 
                                    </select> 
                                </div>
                            </div>

                           <div class="row" v-if="product.global_product.sale_type == 'custom_production'">
                                <div :class="'alert alert-success'" role="alert">
                                    <div class="col-md-12" v-if="product.global_product.sale_type == 'custom_production'">
                                        <p>{{ $t('shop.product.castom_prodaction') }}</p>
                                    </div>
                                </div>

                                <div v-if="
                                    user.length != 0 &&
                                    (user.name == null || user.surname == null || user.country == null || user.city == null || user.phone_number == null || user.email == null)
                                ">
                                    
                                    <div :class="'alert alert-danger cursor_pointer'" @click="goTo('/options')" role="alert">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <strong>{{ $t('shop.product.warning') }}</strong>
                                                <p>{{ $t('shop.product.enter_full_information') }}</p>
                                                <p>{{ $t('shop.product.befor_enter_full_information') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                 <ProductProdaction
                                    v-else-if="user.length != 0"
                                    :product_id_prop = product.global_product.id
                                />
                            </div>
                            
                            <div class="row" v-if="user.length == 0">
                                <div v-if="user.length == 0" :class="'alert alert-danger cursor_pointer'" role="alert" @click="goTo('/login')">
                                    <div class="col-md-12" v-if="product.global_product.sale_type == 'custom_production'">
                                        <p>{{ $t('shop.product.ples_castom_login') }}</p>
                                    </div>
                                    <div class="col-md-12" v-if="product.global_product.sale_type == 'online_order'">
                                        <p>{{ $t('shop.product.online_order_login') }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" v-if="product.locale_product && !is_loading">
            <div class="row" v-if="product.locale_product.text">
                <div class="col-md-12">
                    <h2 class='text-center'>{{ $t('shop.product description') }}</h2>
                    <span v-html="product.locale_product.text"></span>
                </div>
            </div>
            
            <div class="row" v-if="product.global_product.mead_in_georgia">
                <div class="col-md-12">
                    <img class="mead_in_geo_img" :src="this.publicPath + '/public/images/site_img/mead in georgia.png'" :alt="'Mead in Georgia (' + product.locale_product.title + ')'"/>
                </div>
            </div>

            <p v-if="product.global_product.material">{{ $t('shop.product.product_desc.material') }} - {{ product.global_product.material }}</p>
            <p v-if="product.global_product.weight">{{ $t('shop.product.product_desc.weight') }} - {{ product.global_product.weight }}</p>

        </div>

        <div class="container" v-if="!is_loading">
            <div class="row">
                <feedbackForm 
                    :product_id_prop="product.global_product.id"

                    :reviews_count_prop = "product.reviews.count"
                    :reviews_stars_prop = "product.reviews.stars"
                />
            </div>
        </div>

        <div class="container" v-if="!is_loading">
            <similarProduct :activ_product_id_prop=product.global_product.id />
        </div>
        
        <div class="container">
            <div class="row">
                <div class="more-products" id="more-products-wrap">
                    <li style="list-style-type: none">
                        <router-link :to="{name: 'catalog'}" exact>  
                            <span>
                                {{ $t('shop.all products') }}  
                            </span>
                        </router-link>
                    </li>
                </div>
            </div>
        </div>

        <metaData 
            :title = "product.locale_product.title"
            :description = "product.locale_product.short_description"
            :image = "'/public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import lingallery from 'lingallery'; // https://github.com/ChristophAnastasiades/Lingallery
    import VueMeta from 'vue-meta'
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import similarProduct from '../../items/SimilarProductComponent.vue'
    import feedbackForm from '../../items/FeedbacksComponent.vue'
    import ProductProdaction from '../../items/reservation_forms/ProductProdactionFormComponent.vue'

    export default {
        components: {
            metaData,
            lingallery,
            VueMeta,
            breadcrumb,
            similarProduct,
            feedbackForm,
            ProductProdaction,
        },
        data () {
            return {
                is_adding_in_cart_socsesful: false,
                is_loading: false,

                select_product_max_quantyty: 0,
                product_modification_for_cart: 'All',
                products_quantity: 1,
                add_to_cart_message: '',
                items: [],
                actyve_price: {
                    price: '',
                    new_price: ''
                },
                prices: [],

                user: [],

                product: {
                    global_product: [],
                    locale_product: [],
                    max_price: 0,
                    min_price: 0,
                    product_option: []
                },

                // products: [],

                publicPath: window.location.protocol + '//' + window.location.hostname
            }
        },
        watch: {
            '$route' (to, from) {
                this.clear_product_data()

                this.get_product()
                this.get_user_info()

                window.scrollTo(0,0)
            }
        },
        mounted() {
            this.get_product()
            this.get_user_info()
        },
        methods: {
            get_user_info() {
                axios
                .get('/auth_user/')
                .then(response => {
                    this.user = [],
                    this.user = response.data
                })
                .catch()
            },
            goTo(page = '/'){
                window.open(process.env.MIX_APP_SSH + 'user.' + process.env.MIX_SITE_URL + page) ;
            },
            clear_product_data(){
                this.select_product_max_quantyty = 0,
                this.product_modification_for_cart = 'All',
                this.products_quantity = 1,
                this.add_to_cart_message = '',
                this.items = [],
                this.actyve_price = {
                    price: '',
                    new_price: ''
                },
                this.prices = [],

                this.product = {
                    global_product: [],
                    locale_product: [],
                    max_price: 0,
                    min_price: 0,
                    product_option: []
                }

                // this.products = []
            },
            get_product(){
                this.clear_product_data()
                this.is_loading = true
                axios
                .get('/page_product/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.product = response.data

                    this.get_product_options_images()
                })
                .catch(error =>{
                })
                .finally( () => this.is_loading = false)
            },

            get_product_options_images(){
                this.product.product_option.forEach(option => {
                    this.prices.push(option.option.price)
                    if(option.images.length){
                        option.images.forEach(image => {
                            // if(image.image != null){
                                this.items.push({
                                    src: this.publicPath + '/public/images/product_option_img/'+image.image,
                                    thumbnail: this.publicPath + '/public/images/product_option_img/'+image.image,
                                    caption: option.option.title,
                                    id:  option.option.id
                                })
                            // }
                        });
                    }
                    else{
                        this.items = []
                    }
                });
            },

            select_option(){
                this.items = [];
                this.is_adding_in_cart_socsesful = false
                if (this.product_modification_for_cart == "All") {
                    this.get_product_options_images()
                }
                else{
                    this.product.product_option.forEach(option => {

                        this.select_product_max_quantyty = option.option.quantity

                        if (this.product_modification_for_cart == option.option.id) {

                            this.actyve_price.price = option.option.price
                            if(this.product.global_product.discount != null){
                                this.actyve_price.new_price = this.colculate_discount_actyve_price(option.option.price, this.product.global_product.discount)
                            }
                            // else{
                            //     this.actyve_price.new_price = 0
                            // }
                            option.images.forEach(image => {
                                this.items.push({
                                    src: this.publicPath + '/public/images/product_option_img/'+image.image,
                                    thumbnail: this.publicPath + '/public/images/product_option_img/'+image.image,
                                    caption: option.option.title,
                                    id:  option.option.id
                                })
                            });
                        }
                    });
                }
            },

            colculate_discount_actyve_price(price, discount){
                let price_x_sale = discount * price;
                let var_1 = price_x_sale / 100;
                let result = price - var_1;
                return result
            },

            add_to_cart(){
                if(this.product_modification_for_cart == "All"){
                    alert('Please select option!')
                }
                else{
                    this.is_adding_in_cart_socsesful = false
                    axios
                    .put('/cart/'+this.product_modification_for_cart, {
                        modification_id: this.product_modification_for_cart,
                        quantity: this.products_quantity
                    })
                    .then(response => {
                        this.add_to_cart_message = response
                        this.is_adding_in_cart_socsesful = true
                    })
                    .catch(error =>{
                        this.add_to_cart_message = 'Something went wrong. Try login.'
                    })
                }
            },

            add_to_faworite(product_id){
                axios
                .post('/add_to_favorite/'+ product_id)
                .then(response => {
                    alert("Product addid in your favorite list!");
                })
                .catch(error =>{
                    alert(error);
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