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
                                <div class="custom-image-gallery" v-if="items.length">
                                    <div class="main-image-container">
                                        <img
                                            :src="currentImage.src"
                                            :alt="currentImage.caption || product.locale_product.title"
                                            class="main-gallery-image"
                                            :class="{ 'zoomed': isZoomed }"
                                            @click="toggleZoom"
                                        />
                                        <div v-if="items.length > 1 && !isZoomed" class="gallery-nav prev">
                                            <button class="nav-btn" @click="prevImage">‹</button>
                                        </div>
                                        <div v-if="items.length > 1 && !isZoomed" class="gallery-nav next">
                                            <button class="nav-btn" @click="nextImage">›</button>
                                        </div>
                                    </div>
                                    <div v-if="items.length > 1" class="gallery-thumbnails">
                                        <div class="thumbnails-wrapper">
                                            <img
                                                v-for="(item, index) in items"
                                                :key="index"
                                                :src="item.thumbnail"
                                                :alt="item.caption || 'Product image ' + (index + 1)"
                                                class="thumbnail-image"
                                                :class="{ active: index === currentImageIndex }"
                                                @click="setCurrentImage(index)"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="container" v-else>
                                    <shop-img :src="'/public/images/site_img/demo_imgs/shop_demo.jpg'" :alt="product.locale_product.title" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="product-header">
                                <div class="product-title-section">
                                    <h1 class="product-title">{{ product.locale_product.title }}</h1>
                                    <div class="discount-badge-large" v-if="product.has_discount">
                                        -{{ product.max_discount }}%
                                    </div>
                                    <breadcrumb />
                                </div>
                                <div class="action-icons">
                                    <button class="icon-btn share-btn" @click="shareProduct" aria-label="Share product" title="Share product">
                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                    </button>
                                    <button class="icon-btn favorite-btn" @click="add_to_faworite(product.global_product.id)" :class="{ disabled: addingToFavorite }" aria-label="Add to favorites" title="Add to favorites">
                                        <i v-if="addingToFavorite" class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                        <i v-else class="fa fa-heart-o" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="price-shipping" v-if="product_modification_for_cart == 'All'">
                                        <div class="price" id="price-preview" v-if="product.has_discount">
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
                                        <div class="price" id="price-preview" v-if="selectedOptionHasDiscount">
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
                                    <div v-if="isOutOfStock" class="alert alert-danger alert-with-icon" role="alert">
                                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        {{ $t('shop.product.out_of_stock') }}
                                    </div>
                                    <div v-if="isOutOfStockGlobal" class="alert alert-danger alert-with-icon" role="alert">
                                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        {{ $t('shop.product.out_of_stock') }}
                                    </div>
                                    <div v-if="products_quantity >= select_product_max_quantyty && select_product_max_quantyty > 0 && showMaxProductsAlert" class="alert alert-danger alert-dismissible alert-with-icon" role="alert">
                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                        {{ $t('shop.max products') }}
                                        <button type="button" class="close" @click="hideAlert('maxProducts')" aria-label="Close alert">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div v-if="select_product_max_quantyty > 0 && select_product_max_quantyty <= 5 && product_modification_for_cart != 'All'" class="alert alert-warning alert-with-icon" role="alert">
                                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                        Only {{ select_product_max_quantyty }} exist
                                    </div>
                                    <div v-if="is_adding_in_cart_socsesful && showAddSuccessAlert" class="alert alert-success alert-dismissible alert-with-icon" role="alert">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        {{ $t('shop.add successful') }}
                                        <button type="button" class="close" @click="hideAlert('addSuccess')" aria-label="Close alert">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <select class="form-control" v-model="product_modification_for_cart" name="product_modification_for_cart" @change="select_option()">
                                            <option>All</option>
                                            <option v-for="option in product.product_option" :key='option.option.id' :value="option.option.id" >{{ option.option.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 col-md-4" v-if="product_modification_for_cart != 'All' && select_product_max_quantyty > 0">
                                        <div class="quantity-controls">
                                            <button @click="decreaseQuantity" class="quantity-btn" :disabled="products_quantity <= 1" aria-label="Decrease quantity">-</button>
                                            <input type="number" class="quantity-input" min="1" :max="select_product_max_quantyty" v-model="products_quantity" aria-label="Quantity" />
                                            <button @click="increaseQuantity" class="quantity-btn" :disabled="products_quantity >= select_product_max_quantyty" aria-label="Increase quantity">+</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4" v-else-if="product_modification_for_cart != 'All' && select_product_max_quantyty == 0">
                                        <div class="alert alert-danger alert-with-icon" role="alert">
                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                            {{ $t('shop.product.out_of_stock') }}
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2" v-if="product_modification_for_cart != 'All' && select_product_max_quantyty > 0 && user.length != 0">
                                        <button @click="add_to_cart()" class="add-to-cart-btn" :class="{ disabled: addingToCart }" aria-label="Add to cart" title="Add to cart">
                                            <i v-if="addingToCart" class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                            <i v-else class="fa fa-cart-plus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-if="product.global_product.sale_type == 'custom_production'">
                                <div class="col-sm-6 col-md-6">
                                    <select class="form-control" v-model="product_modification_for_cart" name="product_modification_for_cart" @change="select_option()">
                                        <option>All</option>
                                        <option v-for="option in product.product_option" :key='option.option.id' :value="option.option.id" >{{ option.option.name }}</option>
                                    </select>
                                </div>
                            </div>

                           <!-- Delivery time badge -->
                           <div class="row" v-if="product.global_product.sale_type === 'online_order'">
                                <div class="col-12 mb-2">
                                    <div class="alert alert-info alert-with-icon py-2 mb-0">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <strong> Delivery: 2-4 business days</strong>
                                    </div>
                                </div>
                            </div>
                           <div class="row" v-if="product.global_product.sale_type === 'produced_by_order'">
                                <div class="col-12 mb-2">
                                    <div class="alert alert-warning alert-with-icon py-2 mb-0">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <strong> Made to order: 5-9 business days</strong>
                                    </div>
                                </div>
                            </div>

                           <div class="row" v-if="product.global_product.sale_type == 'custom_production'">
                                <div class="alert alert-success alert-with-icon" role="alert">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <div class="col-md-12" v-if="product.global_product.sale_type == 'custom_production'">
                                        <p>{{ $t('shop.product.castom_prodaction') }}</p>
                                    </div>
                                </div>

                                <div v-if="
                                    user.length != 0 &&
                                    (user.name == null || user.surname == null || user.country == null || user.city == null || user.phone_number == null || user.email == null)
                                ">
                                    <div class="alert alert-danger alert-with-icon cursor-pointer" @click="goTo('/options')" role="alert">
                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
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
                                <div class="alert alert-danger alert-with-icon cursor-pointer" role="alert" @click="open_login_modal">
                                    <i class="fa fa-sign-in" aria-hidden="true"></i>
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
        <div class="container product-description-section" v-if="product.locale_product && !is_loading">
            <div class="row" v-if="product.locale_product.text">
                <div class="col-md-12">
                    <h2 class="section-title text-center">{{ $t('shop.product description') }}</h2>
                    <div class="description-content" v-html="product.locale_product.text"></div>
                </div>
            </div>
            
            <div class="row" v-if="product.global_product.mead_in_georgia">
                <div class="col-md-12">
                    <img class="mead_in_geo_img" :src="this.publicPath + '/public/images/site_img/mead in georgia.png'" :alt="'Mead in Georgia (' + product.locale_product.title + ')'"/>
                </div>
            </div>

            <div class="product-details">
                <p v-if="product.global_product.material" class="detail-item"><strong>{{ $t('shop.product.product_desc.material') }}:</strong> {{ product.global_product.material }}</p>
                <p v-if="product.global_product.weight" class="detail-item"><strong>{{ $t('shop.product.product_desc.weight') }}:</strong> {{ product.global_product.weight }}</p>
            </div>

        </div>

        <div class="container reviews-section" v-if="!is_loading">
            <div class="row">
                <feedbackForm
                    :product_id_prop="product.global_product.id"
                    :reviews_count_prop="product.reviews ? product.reviews.count : 0"
                    :reviews_stars_prop="product.reviews ? product.reviews.stars : 0"
                />
            </div>
        </div>

        <div class="container similar-products-section" v-if="!is_loading">
            <similarProduct :activ_product_id_prop="product.global_product.id" />
        </div>

        <div class="container all-products-section">
            <div class="row">
                <div class="more-products" id="more-products-wrap">
                    <router-link :to="{name: 'catalog'}" exact class="all-products-link">
                        {{ $t('shop.all products') }}
                    </router-link>
                </div>
            </div>
        </div>

        <metaData
            :title="product.locale_product.title"
            :description="product.locale_product.short_description"
            :image="items.length ? items[0].src : '/public/images/meta_img/shop.jpg'"
        />

    </div>
</template>

<script>
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import similarProduct from '../../items/SimilarProductComponent.vue'
    import feedbackForm from '../../items/FeedbacksComponent.vue'
    import ProductProdaction from '../../items/reservation_forms/ProductProdactionFormComponent.vue'
    export default {
        components: {
            metaData,
            breadcrumb,
            similarProduct,
            feedbackForm,
            ProductProdaction,
        },
        data () {
            return {
                is_adding_in_cart_socsesful: false,
                is_loading: false,
                addingToCart: false,
                addingToFavorite: false,
                showMaxProductsAlert: true,
                showAddSuccessAlert: true,

                select_product_max_quantyty: 0,
                product_modification_for_cart: 'All',
                products_quantity: 1,
                add_to_cart_message: '',
                items: [],
                currentImageIndex: 0,
                isZoomed: false,
                actyve_price: {
                    price: '',
                    new_price: ''
                },
                prices: [],

                user: [],

                product: {
                    global_product: {},
                    locale_product: {},
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
            },
            items: {
                handler() {
                    this.currentImageIndex = 0;
                },
                deep: true
            },
            products_quantity(val) {
                if (this.select_product_max_quantyty > 0 && val > this.select_product_max_quantyty) {
                    this.products_quantity = this.select_product_max_quantyty;
                }
                if (val < 1) {
                    this.products_quantity = 1;
                }
            }
        },
        mounted() {
            this.get_product()
            this.get_user_info()
            this.$bus.$on('logged-in', () => this.get_user_info())
        },
        computed: {
            isOutOfStock() {
                // If product data is not loaded yet, return false
                if (!this.product || !this.product.global_product) {
                    return false;
                }
                
                // Use out_of_stock from backend for global product status
                // This is true only when ALL options are out of stock
                if (this.product.out_of_stock === true) {
                    return true;
                }
                
                // If a specific option is selected, check if that option is out of stock
                if (this.product_modification_for_cart != 'All' && this.product.product_option) {
                    const selectedOption = this.product.product_option.find(
                        option => option.option && option.option.id == this.product_modification_for_cart
                    );
                    if (selectedOption && selectedOption.is_out_of_stock === true) {
                        return true;
                    }
                }
                
                return false;
            },
            isOutOfStockGlobal() {
                // Use out_of_stock from backend for global product status
                if (!this.product || !this.product.global_product) {
                    return false;
                }
                return this.product.out_of_stock === true;
            },
            selectedOptionHasDiscount() {
                if (this.product_modification_for_cart == 'All') {
                    return this.product.has_discount;
                }
                const selectedOption = this.product.product_option.find(
                    option => option.option.id == this.product_modification_for_cart
                );
                return selectedOption && selectedOption.option.discount > 0;
            },
            currentImage() {
                return this.items[this.currentImageIndex] || {};
            }
        },
        methods: {
            get_user_info() {
                axios
                .get('auth_user')
                .then(response => {
                    this.user = []
                    this.user = response.data
                })
                .catch(() => {})
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
                    global_product: {},
                    locale_product: {},
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
                .get('/get_product/get_local_product_in_page/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
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
                this.select_product_max_quantyty = 0; // Reset quantity
                if (this.product_modification_for_cart == "All") {
                    this.get_product_options_images()
                }
                else if (this.product && this.product.product_option) {
                    this.product.product_option.forEach(option => {
                        if (option.option && this.product_modification_for_cart == option.option.id) {
                            // Use stock_quantity from backend instead of global_product.quantity
                            this.select_product_max_quantyty = option.stock_quantity || 0

                            this.actyve_price.price = option.option.price
                            if(option.option.discount > 0){
                                this.actyve_price.new_price = this.colculate_discount_actyve_price(option.option.price, option.option.discount)
                            } else {
                                this.actyve_price.new_price = option.option.price
                            }
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
                    const qty = Math.min(this.products_quantity, this.select_product_max_quantyty)
                    if (qty < 1) return
                    this.products_quantity = qty
                    this.is_adding_in_cart_socsesful = false
                    this.addingToCart = true
                    this.showAddSuccessAlert = true
                    this.showMaxProductsAlert = true
                    axios
                    .put('/cart/'+this.product_modification_for_cart, {
                        modification_id: this.product_modification_for_cart,
                        quantity: qty
                    })
                    .then(response => {
                        this.add_to_cart_message = response
                        this.is_adding_in_cart_socsesful = true
                        this.$bus.$emit('cart-updated')
                    })
                    .catch(error =>{
                        if (error.response && error.response.data) {
                            const available = error.response.data.available
                            if (available !== undefined) {
                                this.select_product_max_quantyty = available
                                this.products_quantity = Math.min(this.products_quantity, available)
                            }
                            this.add_to_cart_message = error.response.data.error || 'Something went wrong.'
                        } else {
                            this.add_to_cart_message = 'Something went wrong. Try login.'
                        }
                    })
                    .finally(() => {
                        this.addingToCart = false
                    })
                }
            },

            add_to_faworite(product_id){
                this.addingToFavorite = true
                axios
                .post('add_to_favorite/'+ product_id)
                .then(() => {
                    alert("Product added to your favorite list!");
                })
                .catch(error => {
                    if (error.response && error.response.status === 401) {
                        this.$bus.$emit('open-login-modal', () => this.add_to_faworite(product_id))
                    } else {
                        alert("Error adding to favorites");
                    }
                })
                .finally(() => {
                    this.addingToFavorite = false
                })
            },
            increaseQuantity() {
                if (this.products_quantity < this.select_product_max_quantyty) {
                    this.products_quantity++;
                }
            },
            decreaseQuantity() {
                if (this.products_quantity > 1) {
                    this.products_quantity--;
                }
            },
            hideAlert(type) {
                if (type === 'maxProducts') {
                    this.showMaxProductsAlert = false;
                } else if (type === 'addSuccess') {
                    this.showAddSuccessAlert = false;
                }
            },
            shareProduct() {
                const url = window.location.href;
                const title = this.product.locale_product.title;
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: url
                    });
                } else {
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Product link copied to clipboard!');
                    });
                }
            },
            prevImage() {
                if (this.items.length > 1) {
                    this.currentImageIndex = this.currentImageIndex > 0
                        ? this.currentImageIndex - 1
                        : this.items.length - 1;
                }
            },
            nextImage() {
                if (this.items.length > 1) {
                    this.currentImageIndex = this.currentImageIndex < this.items.length - 1
                        ? this.currentImageIndex + 1
                        : 0;
                }
            },
            setCurrentImage(index) {
                this.currentImageIndex = index;
                this.isZoomed = false; // Reset zoom when switching images
            },
            toggleZoom() {
                this.isZoomed = !this.isZoomed;
            },
            open_login_modal() {
                const callback = this.product.global_product.sale_type === 'online_order' && this.product_modification_for_cart !== 'All'
                    ? () => this.add_to_cart()
                    : null
                this.$bus.$emit('open-login-modal', callback)
            },
        }
    }
</script>

<style>
    .custom-image-gallery {
        position: relative;
    }

    .main-image-container {
        position: relative;
        width: 100%;
        height: 400px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .main-gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: zoom-in;
        transition: transform 0.3s ease;
    }

    .main-gallery-image.zoomed {
        cursor: zoom-out;
        transform: scale(1.5);
    }

    .main-gallery-image:hover:not(.zoomed) {
        transform: scale(1.02);
    }

    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        pointer-events: none;
    }

    .gallery-nav button {
        pointer-events: auto;
    }

    .gallery-nav.prev {
        left: 10px;
    }

    .gallery-nav.next {
        right: 10px;
    }

    .nav-btn {
        background: rgba(0,0,0,0.5);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 18px;
        cursor: pointer;
        transition: background 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-btn:hover {
        background: rgba(0,0,0,0.8);
    }

    .gallery-thumbnails {
        margin-top: 15px;
    }

    .thumbnails-wrapper {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .thumbnail-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 3px solid transparent;
        border-radius: 4px;
        transition: border-color 0.3s ease;
    }

    .thumbnail-image.active {
        border-color: #007bff;
    }

    .thumbnail-image:hover {
        border-color: #0056b3;
    }

    .mead_in_geo_img {
        width: 20%;
        height: auto;
        margin-left: 40%;
    }

    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .product-title-section {
        flex: 1;
    }

    .product-title {
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 10px;
        line-height: 1.2;
    }

    .discount-badge-large {
        display: inline-block;
        background: #f54d5c;
        color: #fff;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .action-icons {
        display: flex;
        gap: 15px;
    }

    .icon-btn {
        border: none;
        background: none;
        font-size: 1.5em;
        cursor: pointer;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .icon-btn:hover {
        background-color: rgba(0,0,0,0.1);
    }

    .share-btn, .favorite-btn, .add-to-cart-btn {
        font-size: 1.5em;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .quantity-btn {
        width: 40px;
        height: 40px;
        border: 1px solid #ccc;
        background: #f8f9fa;
        font-size: 1.2em;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .quantity-btn:hover:not(:disabled) {
        background-color: #e9ecef;
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quantity-input {
        text-align: center;
        width: 60px;
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
    }

    .add-to-cart-btn {
        border: none;
        background: none;
        cursor: pointer;
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .add-to-cart-btn:hover {
        background-color: rgba(0,0,0,0.1);
    }

    .alert-with-icon {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .alert-with-icon i {
        font-size: 1.2em;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .section-title {
        font-size: 1.8em;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .description-content {
        line-height: 1.6;
        font-size: 1.1em;
        margin-bottom: 20px;
    }

    .product-details {
        margin-top: 20px;
    }

    .detail-item {
        margin: 10px 0;
        font-size: 1em;
    }

    /* .product-description-section,
    .reviews-section,
    .similar-products-section,
    .all-products-section { */
        /* padding: 30px 0; */
        /* border-top: 1px solid #eee; */
    /* } */

    .all-products-link {
        display: inline-block;
        padding: 10px 20px;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .all-products-link:hover {
        background-color: #e9ecef;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.6;
    }

    /* Desktop enhancements */
    @media (min-width: 769px) {
        .product-detail .container {
            padding: 20px;
        }

        .big {
            margin-bottom: 20px;
        }

        .price-shipping {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .product-description-section,
        .reviews-section,
        .similar-products-section,
        .all-products-section {
            padding: 40px 0;
        }
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .product-detail .cols {
            flex-direction: column;
        }

        .product-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .action-icons {
            margin-top: 15px;
        }

        .product-title {
            font-size: 1.5em;
        }

        .icon-btn {
            font-size: 1.2em;
            padding: 8px;
        }

        .share-btn, .favorite-btn, .add-to-cart-btn {
            font-size: 1.2em;
        }

        .price-shipping {
            text-align: center;
            margin-bottom: 15px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            font-size: 1em;
        }

        .quantity-input {
            width: 50px;
            height: 35px;
            font-size: 1em;
        }

        .alert-with-icon {
            font-size: 14px;
            padding: 10px;
        }

        .section-title {
            font-size: 1.4em;
        }

        .description-content {
            font-size: 1em;
        }

        .mead_in_geo_img {
            width: 50%;
            margin-left: 25%;
        }

        .product-description-section,
        .reviews-section,
        .similar-products-section,
        .all-products-section {
            padding: 20px 0;
        }
    }
</style>
