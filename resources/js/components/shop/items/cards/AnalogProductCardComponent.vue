<template>
    <article class="analog-card" :class="{ 'is-out-of-stock': product.out_of_stock }" role="article">
        <div class="analog-img-wrap">
            <router-link :to="'../product/' + product.global_product.url_title" aria-label="View product">
                <div
                    class="analog-img"
                    :style="product.product_images && product.product_images[0]
                        ? 'background-image: url(' + publicPath + '/public/images/product_option_img/' + product.product_images[0] + ')'
                        : 'background-image: url(' + publicPath + '/public/images/site_img/demo_imgs/shop_demo.jpg)'"
                ></div>
            </router-link>

            <div v-if="product.out_of_stock" class="oos-overlay">
                <span>{{ $t('shop.product.out_of_stock') }}</span>
            </div>
            <div v-if="product.has_discount" class="discount-chip">-{{ product.max_discount }}%</div>
        </div>

        <div class="analog-info">
            <router-link :to="'../product/' + product.global_product.url_title" class="analog-title-link">
                <h3 class="analog-title">{{ product.locale_product.title }}</h3>
            </router-link>

            <div class="analog-price" v-if="product.has_discount">
                <span class="price-now">
                    {{ product.new_min_price != product.new_max_price
                        ? product.new_min_price + ' – ' + product.new_max_price
                        : product.new_max_price }} ₾
                </span>
                <span class="price-was">
                    {{ product.min_price != product.max_price
                        ? product.min_price + ' – ' + product.max_price
                        : product.max_price }} ₾
                </span>
            </div>
            <div class="analog-price" v-else>
                <span class="price-now">
                    {{ product.min_price != product.max_price
                        ? product.min_price + ' – ' + product.max_price
                        : product.max_price }} ₾
                </span>
            </div>

            <div class="analog-actions">
                <button class="analog-btn eye-btn" @click="product_quick_view(product.global_product.id)" title="Quick view">
                    <i class="fa fa-eye"></i>
                </button>
                <button class="analog-btn fav-btn" @click="favorite_product(product.global_product.id)" title="Add to favorites">
                    <i class="fa fa-heart-o"></i>
                </button>
            </div>
        </div>

        <productQuickViewModal ref="quick_view_modal" />
    </article>
</template>

<script>
    import ProductQuickViewModal from '../../../global_components/modals/ProductQuickViewModal'

    export default {
        props: ['product'],
        components: { productQuickViewModal: ProductQuickViewModal },
        data() {
            return {
                publicPath: window.location.protocol + '//' + window.location.hostname
            };
        },
        methods: {
            product_quick_view(product_id) {
                this.$refs.quick_view_modal.show_modal(product_id);
            },
            favorite_product(product_id) {
                axios.post('add_to_favorite/' + product_id)
                    .then(() => { alert("Product added to your favorite list!"); })
                    .catch(error => {
                        if (error.response && error.response.status === 401) {
                            this.$bus.$emit('open-login-modal', () => this.favorite_product(product_id));
                        } else {
                            alert("Error adding to favorites");
                        }
                    });
            }
        }
    }
</script>

<style scoped>
    .analog-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: box-shadow 0.25s, transform 0.25s;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .analog-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        transform: translateY(-4px);
    }
    .analog-card.is-out-of-stock { opacity: 0.7; }

    /* Image */
    .analog-img-wrap { position: relative; overflow: hidden; }
    .analog-img {
        width: 100%;
        padding-bottom: 70%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transition: transform 0.35s;
    }
    .analog-card:hover .analog-img { transform: scale(1.05); }

    .oos-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 0.85em;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .discount-chip {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #f54d5c;
        color: #fff;
        font-size: 0.75em;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 10px;
        z-index: 2;
    }

    /* Info */
    .analog-info { padding: 12px 14px 14px; flex: 1; display: flex; flex-direction: column; }

    .analog-title-link { text-decoration: none; }
    .analog-title {
        font-size: 0.95em;
        font-weight: 600;
        color: #222;
        margin: 0 0 8px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
    }
    .analog-title-link:hover .analog-title { color: #27bb7d; }

    .analog-price { margin-bottom: 10px; display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; }
    .price-now { font-size: 1em; font-weight: 700; color: #1a1a1a; }
    .price-was { font-size: 0.82em; color: #aaa; text-decoration: line-through; }

    /* Actions */
    .analog-actions {
        display: flex;
        gap: 6px;
        margin-top: auto;
        opacity: 0;
        transition: opacity 0.25s;
    }
    .analog-card:hover .analog-actions { opacity: 1; }

    .analog-btn {
        background: #f5f5f5;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 13px;
        color: #555;
        transition: background 0.2s, color 0.2s, transform 0.15s;
    }
    .eye-btn:hover { background: #27bb7d; color: #fff; transform: scale(1.1); }
    .fav-btn:hover { background: #f54d5c; color: #fff; transform: scale(1.1); }

    @media (max-width: 768px) {
        .analog-actions { opacity: 1; }
        .analog-title { font-size: 0.88em; }
    }
</style>
