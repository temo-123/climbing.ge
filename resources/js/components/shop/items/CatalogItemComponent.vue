<template>
    <li class="grid-tile">
        <div class="item">
            <div class="product_image">
                <div class="previes_image" v-if="image_num > 0">
                    <a @click="previes_product_image()"><</a>
                </div>
                <router-link :to="'product/'+product_data.global_product.url_title">
                    <div class="item-img">
                        <site-img v-if="product_data.product_images != null" :src="'../images/product_img/'+get_product_image()" :alt="product_data.locale_product.title" />
                        <site-img v-else :src="'../../../public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='event[0][0].title'/>
                    </div>
                </router-link>
                <div class="next_image" v-if="image_num < (this.image_length - 1)">
                    <a @click="next_product_image()">></a>
                </div>
                <div class="product_quick_view" @click="product_quick_view(product_data.global_product.id)">Quick view</div>
            </div>
            <div class="item-pnl product-image">
                <div class="discount-percent-badge discount-badge-fourty" v-if="product_data.discount">-{{ product_data.discount }}%</div>
                <div class="pnl-wrapper">
                    <div class="pnl-description">

                        <router-link :to="'product/'+product_data.global_product.url_title">
                            <span class="pnl-label">
                                <h2>{{ product_data.locale_product.title }}</h2>
                            </span>
                        </router-link>

                        <div class="price">
                            <span class="pnl-price price">
                                <span class="pnl-price price" v-if="product_data.min_price != product_data.max_price" >{{ product_data.min_price }} ₾ - {{ product_data.max_price }} ₾</span>
                                <span class="pnl-price price" v-else-if="product_data.min_price == product_data.max_price">{{ product_data.max_price }} ₾</span>
                            </span>
                        </div>
                    </div>

                    <div class="pnl-favorites">
                        <i class="fa fa-heart-o favorite_icon" @click="favorite_product(product_data.global_product.id)" ></i>
                    </div>

                    <!-- <div class="pnl-favorites">
                        <i class="fa fa-cart-plus favorite_icon" @click="add_to_cart(product_data.global_product.id)"></i>
                    </div> -->
                </div>
            </div>
        </div>
    </li>
</template>

<script>
    import lingallery from 'lingallery'; // https://github.com/ChristophAnastasiades/Lingallery

    export default {
        components: {
            lingallery,
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
        mounted() {
            //
        },
        methods: {
            // add_to_cart(){
                // this.$emit('add_to_cart', this.product_data)
                
            // },

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
                return(this.product_data.product_images[this.image_num]);
            },

            product_quick_view(product_id){
                this.$emit('quick_view', product_id)
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

    .product_quick_view{
        text-align: center;
        /* margin-top: -10%; */
        background-color: #1d080830;
        /* display: none; */
        position: relative;
    }

    .product_quick_view:hover {
        background-color: #97b67030;
    }
    .favorite_icon{
        font-size: 150%;
        float: left;
        margin-right: 2%;
    }
    .previes_image{
        position: absolute;
        font-size: 250%;
        margin-top: 25%;
        float: left;
        text-shadow: #abababb5 1px 1px 0;
        /* display: none; */
    }
    .next_image{
        position: absolute;
        font-size: 250%;
        float: right;
        left: 92%;
        bottom: 55%;
        text-shadow: #abababb5 1px 1px 0;
        /* display: none; */
    }

    .lingalleryContainer[data-v-40681078] .lingallery figure {
        height: 100% !important;
    }
</style>
