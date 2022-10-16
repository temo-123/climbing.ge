<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <left-menu />
            </div>
            
            <div class="col-sm-9">

                <div class="row font-italic">
                    <div class="col-md-6">
                        <h3 class="mt-3 pb-3 mb-4 ">
                            Faworite products
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary float-right" @click="go_to_product_page('/')">
                            Go to product list
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button
                            class="btn btn-success float-right"
                            @click="get_products()"
                        >
                            refresh
                        </button>
                    </div>
                </div>

                <div class="row d-flex g-1"  v-if="products.length">

                    <div class="col-md-4 favorite_cart" v-for="product in products" :key='product.id'>
                        <!-- {{ product.global_product.url_title }} -->
                        <div class="product text-center"> 

                            <span class="favorite_cart_del_bottom" @click="del_from_faworites(product.global_product.id)">X</span>

                            <div @click="go_to_product_page('/product/'+product.global_product.url_title)">
                                <site-img v-if="product.product_images[0] != null" :src="'../images/product_img/'+product.product_images[0]" :alt="product.locale_product.title" />
                                <site-img v-else :src="'../../../public/images/site_img/shop_demo.jpg'" :img_class="'img-responsive'" :alt='product.locale_product.title'/>
                            </div >

                            <div class="text-left px-3 favorite_cart_text" >
                                <h4 @click="go_to_product_page('/product/'+product.global_product.url_title)">{{ product.locale_product.title }}</h4> 
                            </div> 
                            <i class="fa fa-close"></i>
                        </div>
                    </div>

                </div>

                <div class="row" v-else>
                    <h2>You dont have faworite products</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        // props:[
        //     'site_data',
        // ],
        data: function () {
            return {
                products: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                // filtred_products: [],
                // // site_data: [],
                // min_price: 0,
                // max_price: 1000,
                // filter_category: 'All',
                // sortid_products: [],
                // categories: [],
                // products_loading: true
            };
        },
        components: {
            // catalogItem,
        },
        mounted() {
            this.get_products()
        },
        methods: {
            get_products(){
                axios
                .get('../api/get_user_favorite_products')
                .then(response => {
                    this.products = response.data
                })
                .catch(error =>{
                })
            },
            go_to_product_page(page){
                window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + page)
            },
            del_from_faworites(product_id){
                if(confirm('Are you sure, you want delite this product from your faworites list?')){
                    axios
                    .post('../api/del_from_favorite/'+ product_id)
                    .then(response => {
                        // alert("Product delited from your favorite list!");
                        this.get_products()
                    })
                    .catch(error =>{
                        alert("Error");
                    })
                }
            },
        }
    }
</script>

<style scoped>
    .product {
        padding: 10px;
        background-color: #fff;
        /* position: relative */
    }

    .about h4 {
        margin-bottom: -3px
    }

    .about h3 {
        color: #e3ce57
    }

    .about span {
        font-size: 13px
    }
    .favorite_cart{
        border: solid 1px #dcdcdc;
        border-radius: 2%;
    }
    .favorite_cart_text h4{
        font-size: 1.2rem;
    }
    .favorite_cart_del_bottom{
        float: right;
    }
    /* .dot {
        height: 40px;
        width: 40px;
        background-color: #e3ce57;
        border-radius: 50%;
        display: inline-block;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        cursor: pointer;
        right: 10%;
        z-index: 10;
        bottom: -5%
    }

    .inner-dot {
        height: 30px;
        width: 30px;
        background-color: #fff;
        border-radius: 50%;
        display: inline-block;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        cursor: pointer
    } */
</style>