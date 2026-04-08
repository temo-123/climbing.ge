<template>
    <div class="product-horizontal-card" :class="{ 'out-of-stock': isOutOfStock }">
        <div class="row">
            <div class="col-md-4 col-sm-5">
                <div class="horizontal-card-image-wrapper">
                    <div v-if="isOutOfStock" class="out-of-stock-shadow">
                        <span>{{ $t('shop.product.out_of_stock') }}</span>
                    </div>
                    <div class="image-nav prev-image" v-if="image_num > 0">
                        <button @click="previes_product_image()" aria-label="Previous image" class="nav-btn"><</button>
                    </div>
                    <router-link :to="'product/'+product_data.global_product.url_title" aria-label="View product details">
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
                    <div class="horizontal-card-overlay">
                        <router-link :to="'product/'+product_data.global_product.url_title" class="horizontal-card-link">
                            <i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
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
            </div>
            <div class="col-md-8 col-sm-7">
                <div class="horizontal-card-content">
                    <h3 class="horizontal-card-title">
                        <router-link :to="'product/'+product_data.global_product.url_title">
                            {{ product_data.locale_product ? product_data.locale_product.title : 'Product' }}
                        </router-link>
                    </h3>
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
                    <p class="horizontal-card-description" v-if="product_data.locale_product && product_data.locale_product.short_description">
                        {{ product_data.locale_product.short_description }}
                    </p>
                    <div class="product-actions">
                        <button class="quick-view-btn" @click="product_quick_view(product_data.global_product.id)" aria-label="Quick view">
                            <i class="fa fa-eye"></i> {{ $t('shop.product.quick_view') }}
                        </button>
                        <button class="favorite-btn" @click="favorite_product(product_data.global_product.id)" aria-label="Add to favorites">
                            <i class="fa fa-heart-o"></i> {{ $t('shop.product.add_to_favorite') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
        methods: {
            product_quick_view(product_id){
                this.$refs.quick_view_modal.show_modal(product_id)
            },

            favorite_product(product_id){
                axios
                .post('/add_to_favorite/'+ product_id)
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
                return(active_image);
            },

            goToImage(index){
                this.image_num = index;
            },
        }
    }
</script>

