<template>
    <div class="collection-list col-lg-4 col-md-4 col-sm-4">
        <router-link :to="'../product/'+product.global_product.url_title">
            <div class="img">
                <div v-if="product.product_images[0]" class="i first" :style="'background-image: url(../../images/product_option_img/' + product.product_images[0] + ')'"></div>
                <div v-else class="i first" :style="'background-image: url(../../../public/images/site_img/demo_imgs/shop_demo.jpg)'"></div>
            </div>
            <div class="text">
                <strong>{{product.locale_product.title}}</strong>
                <div class="variants">
                    <div class="variant">
                        <div class="var m available">
                            <div>price</div>
                        </div>
                    </div>

                    {{product.min_price}} - {{ product.max_price }}

                    <!-- <div class="variant">
                            <div class="old_price" v-if="product.discount">
                            <div class="pnl-price ">
                                <div class="pnl-price price" v-if="product.min_price == product.max_price">₾ {{ product.min_price }}</div>
                                <div class="pnl-price price" v-else>₾ {{ product.min_price }} - {{ product.max_price }}</div>
                            </div>
                        </div>

                        <div class="price" v-if="product.discount">
                            <div class="pnl-price price">
                                <div class="pnl-price price" v-if="product.discount_min_price == product.discount_max_price">₾ {{ product.discount_min_price }}</div>
                                <div class="pnl-price price" v-else>₾ {{ product.discount_min_price }} - {{ product.discount_max_price }}</div>
                            </div>
                        </div>

                        <div class="price" v-else>
                            <div class="pnl-price price">
                                <div class="pnl-price price" v-if="product.min_price == product.max_price">₾ {{ product.min_price }}</div>
                                <div class="pnl-price price" v-else>₾ {{ product.min_price }} - {{ product.max_price }}</div>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </router-link>
    </div>

                    
    <!-- <div class="collection-list col-lg-4 col-md-4 col-sm-4">
        <a class="product-box" href="https://shop.climbing.ge/product/home_exercise_stand">
            <span class="img">
                <span style="background-image: url('http://shop.climbing.ge/images/product_option_img/2022-03-01-20-03-43.png')" class="i first">
                </span>
                <span class="i second" style="background-image: url('http://shop.climbing.ge/images/product_option_img/2022-03-01-20-03-43.png')">
                </span>
            </span>
            <span class="text">
                <strong href="http://shop.climbing.ge/product/home_exercise_stand">Home exercise stand</strong>
                <span>GEL 600</span>
                <div class="variants">
                    <div class="variant">
                        <div class="var m available">
                            <div></div>
                        </div>
                    </div>
                </div>
            </span>
        </a>
    </div> -->
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
