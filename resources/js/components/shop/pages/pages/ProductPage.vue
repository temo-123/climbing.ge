<template>
    <div>
        <div class="row" v-if="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading" class="article_loader">
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
                        <div class="col-md-6 product-info-panel">
                            <div class="product-header">
                                <div class="product-title-section">
                                    <breadcrumb />
                                    <h1 class="product-page-title">{{ product.locale_product.title }}</h1>
                                </div>
                                <div class="action-icons">
                                    <button class="icon-btn share-btn" @click="shareProduct" :aria-label="$t('shop.product.share_product')" :title="$t('shop.product.share_product')">
                                        <i class="fa fa-share-alt" aria-hidden="true"></i>
                                    </button>
                                    <button class="icon-btn favorite-btn" @click="add_to_faworite(product.global_product.id)" :class="{ disabled: addingToFavorite }" :aria-label="$t('shop.product.add_to_favorites')" :title="$t('shop.product.add_to_favorites')">
                                        <i v-if="addingToFavorite" class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                        <i v-else class="fa fa-heart-o" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Price block -->
                            <div class="price-block">
                                <template v-if="product_modification_for_cart == 'All'">
                                    <template v-if="product.has_discount">
                                        <span class="price-current">
                                            ₾ {{ product.new_min_price != product.new_max_price ? product.new_min_price + ' – ' + product.new_max_price : product.new_min_price }}
                                        </span>
                                        <span class="price-old">
                                            ₾ {{ product.min_price != product.max_price ? product.min_price + ' – ' + product.max_price : product.min_price }}
                                        </span>
                                        <span class="price-discount-tag">-{{ product.max_discount }}%</span>
                                    </template>
                                    <template v-else>
                                        <span class="price-current">
                                            ₾ {{ product.min_price != product.max_price ? product.min_price + ' – ' + product.max_price : product.max_price }}
                                        </span>
                                    </template>
                                </template>
                                <template v-else>
                                    <template v-if="selectedOptionHasDiscount">
                                        <span class="price-current">₾ {{ actyve_price.new_price }}</span>
                                        <span class="price-old">₾ {{ actyve_price.price }}</span>
                                    </template>
                                    <template v-else>
                                        <span class="price-current">₾ {{ actyve_price.price }}</span>
                                    </template>
                                </template>
                            </div>

                            <!-- Delivery badge -->
                            <div class="delivery-badge" v-if="product.global_product.sale_type === 'online_order'">
                                <i class="fa fa-truck" aria-hidden="true"></i> {{ $t('shop.product.delivery_online_order') }}
                            </div>
                            <div class="delivery-badge delivery-badge--warning" v-if="product.global_product.sale_type === 'produced_by_order'">
                                <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $t('shop.product.delivery_produced_by_order') }}
                            </div>

                            <div class="product-divider"></div>

                            <!-- online_order controls -->
                            <div v-if="product.global_product.sale_type == 'online_order'">
                                <div v-if="isOutOfStock" class="alert alert-danger alert-with-icon" role="alert">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    {{ $t('shop.product.out_of_stock') }}
                                </div>
                                <div v-if="products_quantity >= select_product_max_quantyty && select_product_max_quantyty > 0 && showMaxProductsAlert" class="alert alert-warning alert-dismissible alert-with-icon" role="alert">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $t('shop.max products') }}
                                    <button type="button" class="close" @click="hideAlert('maxProducts')" :aria-label="$t('shop.product.close_alert')"><span>&times;</span></button>
                                </div>
                                <div v-if="select_product_max_quantyty > 0 && select_product_max_quantyty <= 5 && product_modification_for_cart != 'All'" class="alert alert-warning alert-with-icon" role="alert">
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                    {{ $t('shop.product.only_left', { quantity: select_product_max_quantyty }) }}
                                </div>
                                <div v-if="is_adding_in_cart_socsesful && showAddSuccessAlert" class="alert alert-success alert-dismissible alert-with-icon" role="alert">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    {{ $t('shop.add successful') }}
                                    <button type="button" class="close" @click="hideAlert('addSuccess')" :aria-label="$t('shop.product.close_alert')"><span>&times;</span></button>
                                </div>

                                <div class="option-row">
                                    <div class="option-select-wrap">
                                        <label class="option-label">{{ $t('shop.product.select_variant') }}</label>
                                        <select class="form-control option-select" v-model="product_modification_for_cart" name="product_modification_for_cart" @change="select_option()">
                                            <option value="All" disabled>{{ $t('shop.product.select_variant_placeholder') }}</option>
                                            <option v-for="option in product.product_option" :key='option.option.id' :value="option.option.id">{{ option.option.name }}</option>
                                        </select>
                                    </div>

                                    <template v-if="product_modification_for_cart != 'All'">
                                        <div v-if="select_product_max_quantyty > 0" class="quantity-controls">
                                            <button @click="decreaseQuantity" class="quantity-btn" :disabled="products_quantity <= 1" :aria-label="$t('shop.product.decrease_quantity')">−</button>
                                            <input type="number" class="quantity-input" min="1" :max="select_product_max_quantyty" v-model="products_quantity" :aria-label="$t('shop.product.quantity')" />
                                            <button @click="increaseQuantity" class="quantity-btn" :disabled="products_quantity >= select_product_max_quantyty" :aria-label="$t('shop.product.increase_quantity')">+</button>
                                        </div>
                                    </template>
                                </div>

                                <div v-if="product_modification_for_cart != 'All' && select_product_max_quantyty == 0 && !isOutOfStock" class="alert alert-danger alert-with-icon" role="alert">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    {{ $t('shop.product.out_of_stock') }}
                                </div>

                                <div v-if="user.length != 0 && product_modification_for_cart != 'All' && select_product_max_quantyty > 0" class="add-to-cart-row">
                                    <button @click="add_to_cart()" class="add-to-cart-btn-full" :class="{ disabled: addingToCart }">
                                        <i v-if="addingToCart" class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                                        <i v-else class="fa fa-cart-plus" aria-hidden="true"></i>
                                        {{ addingToCart ? $t('shop.adding') : $t('shop.add to cart') }}
                                    </button>
                                </div>
                            </div>

                            <!-- custom_production controls -->
                            <div v-if="product.global_product.sale_type == 'custom_production'">
                                <div class="alert alert-success alert-with-icon" role="alert">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span>{{ $t('shop.product.castom_prodaction') }}</span>
                                </div>

                                <label class="option-label">{{ $t('shop.product.select_variant') }}</label>
                                <select class="form-control option-select mb-3" v-model="product_modification_for_cart" name="product_modification_for_cart" @change="select_option()">
                                    <option value="All" disabled>{{ $t('shop.product.select_variant_placeholder') }}</option>
                                    <option v-for="option in product.product_option" :key='option.option.id' :value="option.option.id">{{ option.option.name }}</option>
                                </select>

                                <div v-if="user.length != 0 && (user.name == null || user.surname == null || user.country == null || user.city == null || user.phone_number == null || user.email == null)">
                                    <div class="alert alert-warning alert-with-icon cursor-pointer" @click="goTo('/options')" role="alert">
                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                        <div>
                                            <strong>{{ $t('shop.product.warning') }}</strong>
                                            <p class="mb-0">{{ $t('shop.product.enter_full_information') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <ProductProdaction v-else-if="user.length != 0" :product_id_prop="product.global_product.id" />
                            </div>

                            <!-- Login prompt -->
                            <div v-if="user.length == 0 && (product.global_product.sale_type == 'online_order' || product.global_product.sale_type == 'custom_production')" class="login-prompt" @click="open_login_modal">
                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                                <span v-if="product.global_product.sale_type == 'custom_production'">{{ $t('shop.product.castom_prodaction_login') }}</span>
                                <span v-else>{{ $t('shop.product.online_order_login') }}</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-divider"></div>

        <div class="container product-description-section" v-if="product.locale_product && !is_loading">
            <div class="row" v-if="product.locale_product.text">
                <div class="col-md-12">
                    <h2 class="section-title">{{ $t('shop.product description') }}</h2>
                    <div class="description-content" v-html="product.locale_product.text"></div>
                </div>
            </div>

            <div class="product-specs" v-if="product.global_product.material || product.global_product.weight">
                <div class="spec-item" v-if="product.global_product.material">
                    <span class="spec-label">{{ $t('shop.product.product_desc.material') }}</span>
                    <span class="spec-value">{{ product.global_product.material }}</span>
                </div>
                <div class="spec-item" v-if="product.global_product.weight">
                    <span class="spec-label">{{ $t('shop.product.product_desc.weight') }}</span>
                    <span class="spec-value">{{ product.global_product.weight }}</span>
                </div>
            </div>

            <div class="row" v-if="product.global_product.mead_in_georgia">
                <div class="col-md-12 text-center">
                    <img class="mead_in_geo_img" :src="this.publicPath + '/public/images/site_img/mead in georgia.png'" :alt="'Mead in Georgia (' + product.locale_product.title + ')'"/>
                </div>
            </div>
        </div>

        <GalleryComponent
            v-if="product.gallery_images && product.gallery_images.length"
            :images_prop="product.gallery_images"
            :folder_path_prop="'/public/images/product_img/'"
        />

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
            <router-link :to="{name: 'catalog'}" exact class="all-products-link">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                {{ $t('shop.all products') }}
            </router-link>
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
    import GalleryComponent from '../../items/GalleryComponent.vue'
    export default {
        components: {
            metaData,
            breadcrumb,
            similarProduct,
            feedbackForm,
            ProductProdaction,
            GalleryComponent,
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
                // Option images only — gallery images shown separately below
                this.product.product_option.forEach(option => {
                    this.prices.push(option.option.price)
                    option.images.forEach(image => {
                        this.items.push({
                            src: this.publicPath + '/public/images/product_option_img/' + image.image,
                            thumbnail: this.publicPath + '/public/images/product_option_img/' + image.image,
                            caption: option.option.title,
                            id: option.option.id
                        })
                    })
                })
            },

            select_option(){
                this.items = []
                this.is_adding_in_cart_socsesful = false
                this.select_product_max_quantyty = 0
                if (this.product_modification_for_cart == "All") {
                    this.get_product_options_images()
                }
                else if (this.product && this.product.product_option) {
                    this.product.product_option.forEach(option => {
                        if (option.option && this.product_modification_for_cart == option.option.id) {
                            this.select_product_max_quantyty = option.stock_quantity || 0
                            this.actyve_price.price = option.option.price
                            if (option.option.discount > 0) {
                                this.actyve_price.new_price = this.colculate_discount_actyve_price(option.option.price, option.option.discount)
                            } else {
                                this.actyve_price.new_price = option.option.price
                            }
                            option.images.forEach(image => {
                                this.items.push({
                                    src: this.publicPath + '/public/images/product_option_img/' + image.image,
                                    thumbnail: this.publicPath + '/public/images/product_option_img/' + image.image,
                                    caption: option.option.title,
                                    id: option.option.id
                                })
                            })
                        }
                    })
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
    /* ── Gallery ── */
    .custom-image-gallery { position: relative; }

    .main-image-container {
        position: relative;
        width: 100%;
        height: 420px;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        background: #f5f5f5;
    }

    .main-gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: zoom-in;
        transition: transform 0.35s ease;
    }
    .main-gallery-image.zoomed { cursor: zoom-out; transform: scale(1.5); }
    .main-gallery-image:hover:not(.zoomed) { transform: scale(1.03); }

    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        pointer-events: none;
    }
    .gallery-nav button { pointer-events: auto; }
    .gallery-nav.prev { left: 12px; }
    .gallery-nav.next { right: 12px; }

    .nav-btn {
        background: rgba(0,0,0,0.45);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 42px;
        height: 42px;
        font-size: 20px;
        cursor: pointer;
        transition: background 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .nav-btn:hover { background: rgba(0,0,0,0.75); }

    .gallery-thumbnails { margin-top: 12px; }
    .thumbnails-wrapper {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .thumbnail-image {
        width: 72px;
        height: 72px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 6px;
        transition: border-color 0.2s, opacity 0.2s;
        opacity: 0.75;
    }
    .thumbnail-image.active { border-color: #27bb7d; opacity: 1; }
    .thumbnail-image:hover { border-color: #1a9460; opacity: 1; }

    /* ── Info panel ── */
    .product-info-panel { padding: 0 0 0 20px; }

    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .product-title-section { flex: 1; }

    .product-page-title {
        font-size: 1.75em;
        font-weight: 700;
        margin: 6px 0 0;
        line-height: 1.25;
        color: #222;
    }

    .action-icons { display: flex; gap: 6px; flex-shrink: 0; }

    .icon-btn {
        border: none;
        background: none;
        font-size: 1.4em;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        color: #666;
        transition: background-color 0.2s, color 0.2s;
    }
    .icon-btn:hover { background-color: #f0f0f0; color: #333; }
    .favorite-btn:hover { color: #f54d5c; }

    /* ── Price block ── */
    .price-block {
        display: flex;
        align-items: baseline;
        gap: 10px;
        flex-wrap: wrap;
        margin: 8px 0 12px;
    }
    .price-current {
        font-size: 1.8em;
        font-weight: 700;
        color: #1a1a1a;
    }
    .price-old {
        font-size: 1.1em;
        color: #999;
        text-decoration: line-through;
    }
    .price-discount-tag {
        background: #f54d5c;
        color: #fff;
        font-size: 0.85em;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 12px;
    }

    /* ── Delivery badge ── */
    .delivery-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85em;
        color: #0c7cd5;
        background: #e8f4fd;
        border-radius: 6px;
        padding: 5px 11px;
        margin-bottom: 14px;
        font-weight: 500;
    }
    .delivery-badge--warning { color: #856404; background: #fff3cd; }

    /* ── Divider ── */
    .product-divider {
        border: none;
        border-top: 1px solid #ebebeb;
        margin: 14px 0;
    }

    /* ── Option select ── */
    .option-label {
        font-size: 0.85em;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 5px;
        display: block;
    }
    .option-select { border-radius: 6px; }

    .option-row {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .option-select-wrap { flex: 1; min-width: 160px; }

    /* ── Quantity controls ── */
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }
    .quantity-btn {
        width: 38px;
        height: 38px;
        border: 1px solid #d0d0d0;
        background: #f5f5f5;
        font-size: 1.2em;
        cursor: pointer;
        border-radius: 6px;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .quantity-btn:hover:not(:disabled) { background-color: #e2e2e2; }
    .quantity-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .quantity-input {
        text-align: center;
        width: 54px;
        height: 38px;
        border: 1px solid #d0d0d0;
        border-radius: 6px;
        font-size: 1em;
    }

    /* ── Add to cart ── */
    .add-to-cart-row { margin-top: 6px; }
    .add-to-cart-btn-full {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #27bb7d;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 11px 26px;
        font-size: 1em;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        width: 100%;
        justify-content: center;
    }
    .add-to-cart-btn-full:hover:not(.disabled) { background: #1fa36b; transform: translateY(-1px); }
    .add-to-cart-btn-full.disabled { opacity: 0.6; pointer-events: none; }

    /* ── Login prompt ── */
    .login-prompt {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f0f7ff;
        border: 1px solid #b8d9f8;
        color: #0c6fb5;
        border-radius: 8px;
        padding: 12px 16px;
        cursor: pointer;
        font-weight: 500;
        margin-top: 10px;
        transition: background 0.2s;
    }
    .login-prompt:hover { background: #daeeff; }

    /* ── Alerts ── */
    .alert-with-icon {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        border-radius: 6px;
        margin-bottom: 12px;
    }
    .alert-with-icon i { font-size: 1.1em; flex-shrink: 0; }
    .cursor-pointer { cursor: pointer; }

    /* ── Section divider ── */
    .section-divider {
        border: none;
        border-top: 1px solid #ebebeb;
        margin: 10px 0 0;
    }

    /* ── Description section ── */
    .section-title {
        font-size: 1.4em;
        font-weight: 700;
        margin-bottom: 16px;
        color: #222;
        padding-bottom: 8px;
        border-bottom: 2px solid #27bb7d;
        display: inline-block;
    }
    .description-content {
        line-height: 1.7;
        font-size: 1.05em;
        color: #444;
        margin-bottom: 20px;
    }

    /* ── Specs ── */
    .product-specs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 16px 0 20px;
    }
    .spec-item {
        background: #f7f7f7;
        border-radius: 6px;
        padding: 8px 14px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 120px;
    }
    .spec-label {
        font-size: 0.75em;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #888;
        font-weight: 600;
    }
    .spec-value { font-size: 0.95em; color: #333; font-weight: 500; }

    .mead_in_geo_img { width: 18%; height: auto; margin: 10px auto; display: block; }

    /* ── All products link ── */
    .all-products-section { padding: 30px 0; }
    .all-products-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        color: #444;
        font-weight: 600;
        font-size: 0.95em;
        transition: background 0.2s, color 0.2s;
    }
    .all-products-link:hover { background: #e8e8e8; color: #222; text-decoration: none; }

    .disabled { pointer-events: none; opacity: 0.6; }

    /* ── Desktop ── */
    @media (min-width: 769px) {
        .product-detail .container { padding: 24px; }
        .product-description-section,
        .reviews-section,
        .similar-products-section,
        .all-products-section { padding: 40px 0; }
    }

    /* ── Mobile ── */
    @media (max-width: 768px) {
        .product-detail .cols { flex-direction: column; }
        .product-info-panel { padding: 16px 0 0; }

        .product-header { flex-direction: column; }
        .action-icons { margin-top: 10px; }
        .product-page-title { font-size: 1.4em; }
        .price-current { font-size: 1.5em; }

        .option-row { flex-direction: column; align-items: stretch; }
        .quantity-controls { justify-content: flex-start; }

        .add-to-cart-btn-full { padding: 13px; font-size: 1em; }

        .alert-with-icon { font-size: 14px; padding: 10px; }
        .section-title { font-size: 1.2em; }
        .description-content { font-size: 1em; }
        .mead_in_geo_img { width: 45%; }

        .product-description-section,
        .reviews-section,
        .similar-products-section { padding: 20px 0; }

        .main-image-container { height: 270px; }
        .quantity-input { font-size: 16px; }
        .quantity-btn { min-width: 42px; min-height: 42px; }
    }
</style>
