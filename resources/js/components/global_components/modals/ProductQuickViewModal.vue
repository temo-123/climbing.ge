<template>
    <stack-modal
        :show="product_modal"
        :title="modalTitle"
        @close="close_product_modal()"
        :modal-class="{ 'quick-view-modal': true }"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="loading" class="loading-container">
            <div class="spinner"></div>
            <p>{{ $t('common.loading') }}</p>
        </div>
        <div v-else-if="error" class="error-container">
            <p class="error-message">{{ $t('common.error_loading_product') }}</p>
            <button class="btn btn-primary" @click="retry_load">{{ $t('common.retry') }}</button>
        </div>
        <div v-else class="product-details">
            <div class="product-layout">
                <div class="product-image-section">
                    <div class="image-slider-container">
                        <div v-if="q_product.product_images && q_product.product_images.length > 1" class="image-nav prev-image" @click="prevImage">
                            <button aria-label="Previous image" class="nav-btn">‹</button>
                        </div>
                        <site-img
                            v-if="q_product.product_images && q_product.product_images.length > 0"
                            :src="'/public/images/product_option_img/'+currentImage"
                            :alt="q_product.locale_product ? q_product.locale_product.title : ''"
                            class="product-image"
                        />
                        <site-img
                            v-else
                            :src="'/public/images/site_img/shop_demo.jpg'"
                            :alt="q_product.locale_product ? q_product.locale_product.title : ''"
                            class="product-image"
                        />
                        <div v-if="q_product.product_images && q_product.product_images.length > 1" class="image-nav next-image" @click="nextImage">
                            <button aria-label="Next image" class="nav-btn">›</button>
                        </div>
                    </div>
                    <div v-if="q_product.product_images && q_product.product_images.length > 1" class="image-dots">
                        <button
                            v-for="(image, index) in q_product.product_images"
                            :key="index"
                            :class="{ active: index === current_image_index }"
                            @click="goToImage(index)"
                            class="dot"
                            :aria-label="'Go to image ' + (index + 1)"
                        ></button>
                    </div>
                </div>
                <div class="product-info-section">
                    <h2 class="product-title">{{ q_product.locale_product ? q_product.locale_product.title : '' }}</h2>
                    <div class="product-price">
                        <span v-if="q_product.min_price != q_product.max_price" class="price-range">
                            {{ q_product.min_price }} ₾ - {{ q_product.max_price }} ₾
                        </span>
                        <span v-else class="price-single">
                            {{ q_product.max_price }} ₾
                        </span>
                        <span v-if="q_product.global_product && q_product.global_product.discount" class="discount-badge">
                            -{{ q_product.global_product.discount }}%
                        </span>
                    </div>
                    <div class="product-description" v-html="q_product.locale_product ? q_product.locale_product.text : ''"></div>
                    <div v-if="q_product.global_product && q_product.global_product.made_in_georgia" class="made-in-georgia">
                        {{ $t('shop.product.made_in_georgia') }}
                    </div>
                </div>
            </div>
        </div>
        <div slot="modal-footer" class="modal-footer-custom">
            <router-link v-if="q_product.global_product" :to="'product/'+q_product.global_product.url_title" class="btn btn-primary view-full-btn">
                {{ $t('shop.product.open_product') }}
            </router-link>
        </div>
    </stack-modal>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            // SlickItem,
            // SlickList,
            StackModal
            // productQuickViewModel
        },
        // props:[
        //     'site_data',
        // ],
        data: function () {
            return {
                quick_product: [],
                q_product: [],
                product_modal: false,
                loading: false,
                error: false,
                current_product_id: null,
                current_image_index: 0
            };
        },
        computed: {
            modalTitle() {
                return (this.q_product && this.q_product.locale_product && this.q_product.locale_product.title)
                    ? this.q_product.locale_product.title
                    : this.$t('shop.product.quick_view');
            },
            currentImage() {
                if (this.q_product.product_images && this.q_product.product_images.length > 0) {
                    return this.q_product.product_images[this.current_image_index];
                }
                return '';
            }
        },
        mounted() {
            //
        },
        methods: {
            quick_view_modal(product_id){
                this.current_product_id = product_id;
                this.loading = true;
                this.error = false;
                this.quick_product = [];
                this.q_product = {};

                axios
                .get('/get_product/get_quick_product/'+localStorage.getItem('lang')+'/'+product_id)
                .then(response => {
                    this.quick_product = response.data;
                    this.q_product = response.data[0] || {};
                    this.loading = false;
                    this.product_modal = true;
                })
                .catch(error => {
                    console.error('Error loading product:', error);
                    this.loading = false;
                    this.error = true;
                });
            },
            retry_load(){
                if (this.current_product_id) {
                    this.quick_view_modal(this.current_product_id);
                }
            },
            close_product_modal(){
                this.product_modal = false;
                this.quick_product = [];
                this.q_product = {};
                this.loading = false;
                this.error = false;
                this.current_image_index = 0;
            },
            prevImage(){
                if (this.q_product.product_images && this.q_product.product_images.length > 1) {
                    this.current_image_index = this.current_image_index > 0
                        ? this.current_image_index - 1
                        : this.q_product.product_images.length - 1;
                }
            },
            nextImage(){
                if (this.q_product.product_images && this.q_product.product_images.length > 1) {
                    this.current_image_index = this.current_image_index < this.q_product.product_images.length - 1
                        ? this.current_image_index + 1
                        : 0;
                }
            },
            goToImage(index){
                this.current_image_index = index;
            },
        }
    }
</script>

<style scoped>
    .quick-view-modal .modal-dialog {
        max-width: 800px;
    }

    .loading-container {
        text-align: center;
        padding: 40px;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #007bff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .error-container {
        text-align: center;
        padding: 40px;
    }

    .error-message {
        color: #dc3545;
        margin-bottom: 20px;
        font-size: 1.1em;
    }

    .product-details {
        padding: 20px 0;
    }

    .product-layout {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }

    .product-image-section {
        flex: 0 0 400px;
    }

    .image-slider-container {
        position: relative;
        width: 100%;
        height: 400px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
    }

    .prev-image {
        left: 10px;
    }

    .next-image {
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

    .image-dots {
        display: flex;
        justify-content: center;
        padding: 10px 0;
        background: #fff;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: none;
        background: #ddd;
        margin: 0 5px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .dot.active {
        background: #007bff;
    }

    .dot:hover {
        background: #0056b3;
    }

    .product-info-section {
        flex: 1;
    }

    .product-title {
        font-size: 1.8em;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .product-price {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .price-range,
    .price-single {
        font-size: 1.4em;
        font-weight: bold;
        color: #007bff;
    }

    .discount-badge {
        background: #f54d5c;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9em;
        font-weight: bold;
    }

    .product-description {
        margin-bottom: 20px;
        line-height: 1.6;
        color: #555;
    }

    .made-in-georgia {
        color: #a65e5e;
        font-size: 0.9em;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .modal-footer-custom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .view-full-btn {
        padding: 12px 30px;
        font-size: 1.1em;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
    }

    .view-full-btn:hover {
        text-decoration: none;
        color: #fff;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .product-layout {
            flex-direction: column;
        }

        .product-image-section {
            flex: none;
        }

        .image-slider-container {
            height: 300px;
        }

        .product-title {
            font-size: 1.5em;
        }

        .price-range,
        .price-single {
            font-size: 1.2em;
        }

        .view-full-btn {
            width: 100%;
        }

        .nav-btn {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }

        .dot {
            width: 8px;
            height: 8px;
        }
    }
</style>
