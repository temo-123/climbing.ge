<template>
    <div class="collection-list col-lg-4 col-md-4 col-sm-4">
        <article class="product-card" role="article">
            <router-link :to="'../product/'+product.global_product.url_title" aria-label="View product details">
                <div class="product-image">
                    <div v-if="product.product_images[0]" class="image-bg" :style="'background-image: url(../../images/product_option_img/' + product.product_images[0] + ')'"></div>
                    <div v-else class="image-bg" :style="'background-image: url(../../../public/images/site_img/demo_imgs/shop_demo.jpg)'"></div>
                </div>
                <div class="product-info">
                    <h3 class="product-title">{{product.locale_product.title}}</h3>
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
                </div>
            </router-link>
        </article>
    </div>
</template>

<script>
    export default {
        props:[
            'product',
        ],
        data: function () {
            return {

            };
        },
        components: {
        },
        mounted() {
        },
        methods: {
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
    }

    .product-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        transform: translateY(-5px);
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
    }

    .product-title {
        font-size: 1.2em;
        font-weight: bold;
        margin: 0 0 10px 0;
        color: #333;
    }

    .product-price {
        margin-bottom: 10px;
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
    }
</style>
