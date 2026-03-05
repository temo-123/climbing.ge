<template>
    <div class="collection-list col-lg-4 col-md-4 col-sm-4">
        <article class="product-card" role="article">
            <div class="product-image-container">
                <router-link :to="'../product/'+product.global_product.url_title" aria-label="View product details">
                    <div class="product-image">
                        <div v-if="product.product_images[0]" class="image-bg" :style="'background-image: url(../../images/product_option_img/' + product.product_images[0] + ')'"></div>
                        <div v-else class="image-bg" :style="'background-image: url(../../../public/images/site_img/demo_imgs/shop_demo.jpg)'"></div>
                    </div>
                </router-link>
            </div>
            <div class="product-info">
                <router-link :to="'../product/'+product.global_product.url_title" aria-label="View product details">
                    <h3 class="product-title">{{product.locale_product.title}}</h3>
                </router-link>
                <div class="product-price" v-if="product.global_product.discount != null && product.global_product.discount > 0">
                    <span class="current-price">
                        <span v-if="product.new_min_price != product.new_max_price">{{ product.new_min_price }} ₾ - {{ product.new_max_price }} ₾</span>
                        <span v-else>{{ product.new_max_price }} ₾</span>
                    </span>
                    <span class="old-price">
                        <span v-if="product.min_price != product.max_price">{{ product.min_price }} ₾ - {{ product.max_price }} ₾</span>
                        <span v-else>{{ product.max_price }} ₾</span>
                    </span>
                </div>
                <div class="product-price" v-else>
                    <span class="current-price">
                        <span v-if="product.min_price != product.max_price">{{ product.min_price }} ₾ - {{ product.max_price }} ₾</span>
                        <span v-else>{{ product.max_price }} ₾</span>
                    </span>
                </div>
                <div class="product-actions">
                    <button class="quick-view-btn" @click="product_quick_view(product.global_product.id)" aria-label="Quick view">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button class="favorite-btn" @click="favorite_product(product.global_product.id)" aria-label="Add to favorites">
                        <i class="fa fa-heart-o"></i>
                    </button>
                </div>
            </div>
        </article>
        <productQuickViewModal ref="quick_view_modal" />
    </div>
</template>

<script>
    import ProductQuickViewModal from '../../../global_components/modals/ProductQuickViewModal'

    export default {
        props:[
            'product',
        ],
        data: function () {
            return {

            };
        },
        components: {
            productQuickViewModal: ProductQuickViewModal
        },
        mounted() {
        },
        methods: {
            product_quick_view(product_id){
                this.$refs.quick_view_modal.quick_view_modal(product_id);
            },
            favorite_product(product_id){
                // Implement favorite functionality here
                console.log('Add to favorites:', product_id);
            }
        }
    }
</script>

<style scoped>
    .product-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        position: relative;
    }

    .product-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        transform: translateY(-5px);
    }

    .product-image-container {
        position: relative;
        overflow: hidden;
    }

    .product-image {
        position: relative;
        aspect-ratio: 16 / 9;
        overflow: hidden;
    }

    .image-bg {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .product-info {
        padding: 15px;
        position: relative;
    }

    .product-title {
        font-size: 1.2em;
        font-weight: bold;
        margin: 0 0 10px 0;
        color: #333;
    }

    .product-title a {
        text-decoration: none;
        color: inherit;
    }

    .product-title a:hover {
        color: #007bff;
    }

    .product-price {
        margin-bottom: 15px;
    }

    .current-price {
        font-size: 1.1em;
        font-weight: bold;
        color: #333;
    }

    .old-price {
        font-size: 0.9em;
        color: #999;
        text-decoration: line-through;
        margin-left: 10px;
    }

    .product-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .product-actions {
        opacity: 1;
    }

    .quick-view-btn,
    .favorite-btn {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .quick-view-btn:hover {
        background: #007bff;
        color: #fff;
        transform: scale(1.1);
    }

    .favorite-btn:hover {
        background: #dc3545;
        color: #fff;
        transform: scale(1.1);
    }

    .quick-view-btn i,
    .favorite-btn i {
        font-size: 14px;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .product-card {
            margin-bottom: 15px;
        }

        .product-info {
            padding: 10px;
        }

        .product-title {
            font-size: 1em;
        }

        .current-price {
            font-size: 1em;
        }

        .old-price {
            font-size: 0.8em;
        }

        .product-actions {
            opacity: 1;
            position: static;
            justify-content: center;
            margin-top: 10px;
        }

        .quick-view-btn,
        .favorite-btn {
            width: 30px;
            height: 30px;
        }
    }
</style>
