<template>
    <div class="grid-tile">
        <article class="product-card" :class="{ 'out-of-stock': isOutOfStock }" role="article">
            <div class="product-image-container">
                <div v-if="isOutOfStock" class="out-of-stock-shadow">
                    <span>{{ $t('shop.product.out_of_stock') }}</span>
                </div>
                <div class="image-nav prev-image" v-if="image_num > 0">
                    <button @click="previes_product_image()" aria-label="Previous image" class="nav-btn"><</button>
                </div>
                <router-link v-if="product_data.global_product" :to="'product/'+product_data.global_product.url_title" aria-label="View product details">
                    <div class="item-img">
                        <shop-img v-if="product_data.product_images && product_data.product_images.length" :src="'/public/images/product_option_img/'+get_product_image()" :alt="product_data.locale_product ? product_data.locale_product.title : ''" />
                        <shop-img v-else :src="'/public/images/site_img/demo_imgs/shop_demo.jpg'" :alt="product_data.locale_product ? product_data.locale_product.title : ''" />
                    </div>
                </router-link>
                <div class="image-nav next-image" v-if="image_num < (this.image_length - 1)">
                    <button @click="next_product_image()" aria-label="Next image" class="nav-btn">></button>
                </div>
                <div class="badge discount-badge" v-if="hasDiscount">-{{ maxDiscount }}%</div>
                <div class="badge new-badge" v-if="product_data.global_product && product_data.global_product.new_flag">{{ $t('shop.product.new') }}</div>
            </div>
            <div class="image-dots" v-if="image_length > 1">
                <button
                    v-for="(image, index) in product_data.product_images"
                    :key="index"
                    :class="{ active: index === image_num }"
                    @click="goToImage(index)"
                    class="dot"
                    :aria-label="'Go to image ' + (index + 1)"
                ></button>
            </div>
            <div class="product-info">
                <div class="product-title" v-if="product_data.global_product">
                    <router-link :to="'product/'+product_data.global_product.url_title" aria-label="View product details">
                        <h2>{{ product_data.locale_product ? product_data.locale_product.title : 'Product' }}</h2>
                    </router-link>
                </div>
                <div class="product-made-in-georgia" v-if="product_data.global_product && product_data.global_product.made_in_georgia">
                    {{ $t('shop.product.made_in_georgia') }}
                </div>
                <div class="product-price" v-if="product_data.has_discount">
                    <span class="current-price">
                        <span v-if="product_data.new_min_price != product_data.new_max_price">{{ product_data.new_min_price }} ₾ - {{ product_data.new_max_price }} ₾</span>
                        <span v-else>{{ product_data.new_max_price }} ₾</span>
                    </span>
                    <span class="old-price">
                        <span v-if="product_data.min_price != product_data.max_price">{{ product_data.min_price }} ₾ - {{ product_data.max_price }} ₾</span>
                        <span v-else>{{ product_data.max_price }} ₾</span>
                    </span>
                </div>
                <div class="product-price" v-else>
                    <span class="current-price">
                        <span v-if="product_data.min_price != product_data.max_price">{{ product_data.min_price }} ₾ - {{ product_data.max_price }} ₾</span>
                        <span v-else>{{ product_data.max_price }} ₾</span>
                    </span>
                </div>
                <div class="product-actions" v-if="product_data.global_product">
                    <button class="quick-view-btn" @click="product_quick_view(product_data.global_product.id)" aria-label="Quick view">
                        <i class="fa fa-eye"></i>
                    </button>
                    <button class="favorite-btn" @click="favorite_product(product_data.global_product.id)" aria-label="Add to favorites">
                        <i class="fa fa-heart-o"></i>
                    </button>
                </div>
            </div>
        </article>
        <productQuickViewModal
            ref="quick_view_modal"
        />
    </div>
</template>

<script>


    import productQuickViewModal from '../../../global_components/modals/ProductQuickViewModal'

    export default {
        components: {
            productQuickViewModal,
        },
        props:[
            'product_data',
        ],
        data: function () {
            return {
                image_num: 0,
                image_length: 0,
            };
        },
        computed: {
            isOutOfStock() {
                // Use out_of_stock from backend (calculated when ALL options are out of stock)
                return this.product_data.out_of_stock === true;
            },
            hasDiscount() {
                return this.product_data.has_discount;
            },
            maxDiscount() {
                return this.product_data.max_discount || 0;
            }
        },
        mounted() {
            //
        },
        methods: {
            product_quick_view(product_id){
                this.$refs.quick_view_modal.show_modal(product_id)
            },

            favorite_product(product_id){
                axios
                .post('add_to_favorite/'+ product_id)
                .then(() => {
                    alert("Product added to your favorite list!");
                })
                .catch(error => {
                    if (error.response && error.response.status === 401) {
                        this.$bus.$emit('open-login-modal', () => this.favorite_product(product_id))
                    } else {
                        alert("Error adding to favorites");
                    }
                })
            },

            next_product_image(){
                var test_num = 0
                test_num = this.image_num
                this.test_num += 1
                if(test_num < (this.image_length - 1)){
                    this.image_num += 1
                }
            },

            previes_product_image(){
                var test_num = 0
                test_num = this.image_num
                this.test_num -= 1
                if(test_num > 0){
                    this.image_num -= 1
                }
            },

            get_product_image(){
                this.image_length = this.product_data.product_images.length
                var active_image = this.product_data.product_images[this.image_num]
                // alert(active_image);
                return(active_image);
            },

            goToImage(index){
                this.image_num = index;
            },

        }
    }
</script>

<style scoped>
    .grid-tile {
        display: flex;
    }

    .product-card {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        margin-bottom: 1.5rem;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
        transition: transform .3s var(--cg-ease), box-shadow .3s ease, border-color .3s ease;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--cg-shadow);
        border-color: rgba(var(--cg-accent-rgb), .35);
    }

    .product-made-in-georgia {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        width: fit-content;
        margin: 0 0 .5rem;
        padding: .2rem .6rem;
        border-radius: 2rem;
        background: #fbeee9;
        color: #a24a2a;
        font-size: .75rem;
        font-weight: 800;
    }

    .out-of-stock-shadow {
        position: absolute;
        inset: 0;
        z-index: 15;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(21, 33, 43, .6);
    }

    .out-of-stock-shadow span {
        padding: .5rem 1rem;
        border-radius: 2rem;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .3);
        color: #fff;
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .product-image-container {
        position: relative;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: var(--cg-chalk);
    }

    .item-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .item-img :deep(img) {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .6s var(--cg-ease);
    }
    .product-card:hover .item-img :deep(img) {
        transform: scale(1.05);
    }

    .image-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        opacity: 0;
        transition: opacity .25s ease;
    }
    .product-card:hover .image-nav {
        opacity: 1;
    }

    .prev-image {
        left: 10px;
    }

    .next-image {
        right: 10px;
    }

    .nav-btn {
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .95);
        color: var(--cg-ink);
        font-size: 16px;
        font-weight: 800;
        box-shadow: var(--cg-shadow-sm);
        cursor: pointer;
        transition: transform .2s ease;
    }

    .nav-btn:hover {
        transform: scale(1.08);
    }

    .image-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 10px 0 0;
    }

    .dot {
        width: 8px;
        height: 8px;
        border: none;
        border-radius: 50%;
        background: var(--cg-line);
        cursor: pointer;
        transition: all .25s ease;
    }

    .dot.active {
        width: 20px;
        border-radius: 4px;
        background: var(--cg-accent);
    }

    .badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 10;
        padding: .35rem .7rem;
        border-radius: 2rem;
        background: var(--cg-rope);
        color: #fff;
        font-size: .75rem;
        font-weight: 800;
    }

    .new-badge {
        left: auto;
        right: 12px;
        background: var(--cg-accent);
    }

    .product-info {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 1rem 1.15rem 1.1rem;
    }

    .product-title h2 {
        margin: 0 0 .5rem;
        font-size: 1.3rem;
        line-height: 1.2;
        color: var(--cg-ink);
    }

    .product-title a {
        color: inherit;
        text-decoration: none;
    }

    .product-title a:hover h2 {
        color: var(--cg-accent);
    }

    .product-price {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: .2rem .6rem;
        margin-bottom: .75rem;
    }

    .current-price {
        font-family: var(--cg-font-display);
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1;
        color: var(--cg-ink);
    }

    .old-price {
        font-size: .9rem;
        font-weight: 600;
        color: var(--cg-muted);
        text-decoration: line-through;
    }

    .product-actions {
        display: flex;
        justify-content: flex-end;
        gap: .4rem;
        margin-top: auto;
        padding-top: .75rem;
        border-top: 1px dashed var(--cg-line);
    }

    .quick-view-btn,
    .favorite-btn {
        display: inline-grid;
        place-items: center;
        width: 38px;
        height: 38px;
        border: 1px solid var(--cg-line);
        border-radius: 50%;
        background: #fff;
        color: var(--cg-stone);
        font-size: 1rem;
        cursor: pointer;
        transition: color .2s ease, border-color .2s ease, background-color .2s ease;
    }

    .quick-view-btn:hover {
        color: #fff;
        background: var(--cg-accent);
        border-color: var(--cg-accent);
    }

    .favorite-btn:hover {
        color: var(--cg-rope);
        border-color: var(--cg-rope);
    }

    @media (max-width: 768px) {
        .product-info {
            padding: .85rem;
        }

        .product-title h2 {
            font-size: 1.15rem;
        }

        .current-price {
            font-size: 1.3rem;
        }
    }
</style>
