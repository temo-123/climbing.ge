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
                <a class="cursor_pointer" @click="go_to_service('product/'+product_data.global_product.url_title)" aria-label="View product details">
                    <div class="item-img">
                        <shop-img v-if="product_data.product_images.length" :src="'/public/images/product_option_img/'+get_product_image()" :alt="product_data.locale_product.title" />
                        <shop-img v-else :src="'/public/images/site_img/demo_imgs/shop_demo.jpg'" :alt="product_data.locale_product.title" />
                    </div>
                </a>
                <div class="image-nav next-image" v-if="image_num < (this.image_length - 1)">
                    <button @click="next_product_image()" aria-label="Next image" class="nav-btn">></button>
                </div>
                <div class="badge discount-badge" v-if="hasDiscount">-{{ maxDiscount }}%</div>
                <div class="badge donation-badge" v-if="product_data.global_product.is_donation_product">{{ $t('shop.product.donation') }}</div>
                <div class="badge new-badge" v-if="product_data.global_product.new_flag">{{ $t('shop.product.new') }}</div>
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
                <div class="product-title">
                    <a class="cursor_pointer" @click="go_to_service('product/'+product_data.global_product.url_title)" aria-label="View product details">
                        <h2>{{ product_data.locale_product.title }}</h2>
                    </a>
                </div>
                <div class="product-made-in-georgia" v-if="product_data.global_product.made_in_georgia">
                    {{ $t('shop.product.made_in_georgia') }}
                </div>
                <div class="product-price" v-if="product_data.global_product.discount != null && product_data.global_product.discount > 0">
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
                <div class="product-actions">
                    <!-- <button class="quick-view-btn" @click="product_quick_view(product_data.global_product.id)" aria-label="Quick view">
                        <i class="fa fa-eye"></i>
                    </button> -->
                    <button class="favorite-btn" @click="favorite_product(product_data.global_product.id)" aria-label="Add to favorites">
                        <i class="fa fa-heart-o"></i>
                    </button>
                </div>
            </div>
        </article>
        <!-- <productQuickViewModal
            ref="quick_view_modal"
        /> -->
    </div>
</template>

<script>
    import productQuickViewModal from '../../../../global_components/modals/ProductQuickViewModal'

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

                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        computed: {
            isOutOfStock() {
                if (!this.product_data.product_option || this.product_data.product_option.length === 0) {
                    return false;
                }
                return this.product_data.product_option.every(option => option.option.quantity <= 0);

                // return true // test asset
            },
            hasDiscount() {
                return this.product_data.product_option && this.product_data.product_option.some(option => option.option.discount > 0);
            },
            maxDiscount() {
                if (!this.hasDiscount) return 0;
                return Math.max(...this.product_data.product_option.map(option => option.option.discount));
            }
        },
        mounted() {
            //
        },
        methods: {
            go_to_service(service){
                window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '/' + service)
            },
            product_quick_view(product_id){
                this.$refs.quick_view_modal.quick_view_modal(product_id)
            },

            favorite_product(product_id){
                axios
                .post('../api/add_to_favorite/'+ product_id)
                .then(response => {
                    alert("Product addid in your favorite list!");
                })
                .catch(error =>{
                    alert("Error");
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


    .product-made-in-georgia {
        color: #a65e5e;
        font-size: 0.9em;
        font-weight: bold;
        margin: 5px 0;
    }

    .out-of-stock-shadow {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgb(0 0 0 / 80%) 0%, #00000073 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 15;
    }

    .out-of-stock-shadow span {
        color: #f0f0f0;
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .out-of-stock-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    .out-of-stock-overlay span {
        color: white;
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
    }

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

    .product-image-container {
        position: relative;
        aspect-ratio: 16 / 9;
        overflow: hidden;
    }

    .item-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .item-img shop-img {
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

    .badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #f54d5c;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        z-index: 10;
    }

    .donation-badge {
        background: #28a745;
        left: auto;
        right: 10px;
        top: 10px;
        font-size: 16px;
    }

    .new-badge {
        left: auto;
        right: 10px;
        top: 40px;
    }

    .product-info {
        padding: 15px;
    }

    .product-title h2 {
        font-size: 1.4em;
        margin: 0 0 10px 0;
        font-weight: bold;
        color: #333;
    }

    .product-title a {
        color: inherit;
        text-decoration: none;
    }

    .product-title a:hover {
        color: #007bff;
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

    .product-actions {
        text-align: right;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .quick-view-btn {
        background: none;
        border: none;
        font-size: 1.5em;
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .quick-view-btn:hover {
        color: #007bff;
    }

    .favorite-btn {
        background: none;
        border: none;
        font-size: 1.5em;
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .favorite-btn:hover {
        color: #f54d5c;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .product-card {
            margin-bottom: 15px;
        }

        .product-info {
            padding: 10px;
        }

        .product-title h2 {
            font-size: 1em;
        }

        .current-price {
            font-size: 1em;
        }

        .old-price {
            font-size: 0.8em;
        }

        .favorite-btn {
            font-size: 1.2em;
        }

        .nav-btn {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }

        .badge {
            font-size: 10px;
            padding: 3px 6px;
        }
    }
</style>
