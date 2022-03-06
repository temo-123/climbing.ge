<template>
    <li class="grid-tile">
        <div class="item">
            <router-link :to="'product/'+product_data.url_title">
                <div class="item-img">
                    <img :src="'../images/product_img/'+product_data.image" :alt="product_data.locale_article.title">
                </div>
            </router-link>
            <div class="item-pnl product-image">
                <div class="discount-percent-badge discount-badge-fourty" v-if="product_data.discount">-{{ product_data.discount }}%</div>
                <div class="pnl-wrapper">
                    <div class="pnl-description">

                        <router-link :to="'product/'+product_data.url_title">
                            <span class="pnl-label">
                                <h2>{{ product_data.locale_article.title }}</h2>
                            </span>
                        </router-link>
                        <!-- <a href="#">
                            <span class="pnl-label"><h2>{{ product_data.locale_article.title }}</h2></span>
                        </a> -->
                            <div class="old_price" v-if="product_data.discount">
                                <span class="pnl-price ">
                                    <span class="pnl-price price" v-if="product_data.min_price == product_data.max_price">₾ {{ product_data.min_price }}</span>
                                    <span class="pnl-price price" v-else>₾ {{ product_data.min_price }} - {{ product_data.max_price }}</span>
                                </span>
                            </div>

                            <div class="price" v-if="product_data.discount">
                                <span class="pnl-price price">
                                    <span class="pnl-price price" v-if="product_data.discount_min_price == product_data.discount_max_price">₾ {{ product_data.discount_min_price }}</span>
                                    <span class="pnl-price price" v-else>₾ {{ product_data.discount_min_price }} - {{ product_data.discount_max_price }}</span>
                                </span>
                            </div>

                            <div class="price" v-else>
                                <span class="pnl-price price">
                                    <span class="pnl-price price" v-if="product_data.min_price == product_data.max_price">₾ {{ product_data.min_price }}</span>
                                    <span class="pnl-price price" v-else>₾ {{ product_data.min_price }} - {{ product_data.max_price }}</span>
                                </span>
                            </div>
                    </div>

                    <div class="pnl-favorites">
                        <i class="fa fa-heart-o favorite_icon" @click="favorite_product()" ></i>
                    </div>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
    export default {
        props:[
            'product_data',
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
            add_to_cart(){
                this.$emit('add_to_cart', this.product_data)
            },
            favorite_product(){
                alert('favorite_product')
            },
        }
    }
</script>

<style scoped>
    /* .cart_icon{
        font-size: 140%;
        float: left;
        margin-right: 2%;
    } */
    .favorite_icon{
        font-size: 150%;
        float: left;
        margin-right: 2%;
    }

    .lingalleryContainer[data-v-40681078] .lingallery figure {
        height: 100% !important;
    }
</style>
