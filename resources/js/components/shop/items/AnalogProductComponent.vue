<template>
    <div>
        <div class="collection-list col-lg-4 col-md-4 col-sm-4">
            <router-link :to="'../product/'+product.url_title">
                <div class="img">
                    <!-- <div style="background-image: url('https://picsum.photos/64/64/?image=0')" class="i first">
                    </div> -->
                    <!-- <div class="i second" style="background-image: url('https://picsum.photos/64/64/?image=0')">
                    </div> -->
                    <div class="i" :style="'background-image: url(../images/product_img/' + product.image + ')'">
                    </div>
                </div>
                <div class="text">
                    <strong>{{product.locale_article.title}}</strong>
                    <div class="variants">
                        <div class="variant">
                            <div class="var m available">
                                <div>price</div>
                            </div>
                        </div>

                        <div class="variant">
                            <!-- {{product.min_price}} - {{ product.max_price }} -->
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
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
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
</style>
