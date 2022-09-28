<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <left-menu />
            </div>
            
            <div class="col-sm-9">

                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary pull-left" @click="go_to_product_page('/')">
                                Go to product list
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="form-groupe float-right">
                                <button
                                    class="btn btn-success"
                                    @click="get_products()"
                                >
                                    refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex g-1">

                        <div class="col-md-4 favorite_cart" v-for="product in products" :key='product.id'>
                            <!-- {{ product.global_product.url_title }} -->
                            <div class="product text-center"> 

                                <span class="favorite_cart_del_bottom" @click="del_from_faworites(product.global_product.id)">X</span>

                                <div @click="go_to_product_page('/product/'+product.global_product.url_title)">
                                    <site-img v-if="product.product_images[0] != null" :src="'../images/product_img/'+product.product_images[0]" :alt="product.locale_product.title" />
                                    <site-img v-else :src="'../../../public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='product.locale_product.title'/>
                                </div >

                                <div class="text-left px-3 favorite_cart_text" >
                                    <h4 @click="go_to_product_page('/product/'+product.global_product.url_title)">{{ product.locale_product.title }}</h4> 
                                </div> 
                                <i class="fa fa-close"></i>
                            </div>
                        </div>

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

                    // this.sortByCategories()
                })
                .catch(error =>{
                })
                .finally(() => this.products_loading = false);
            },
            go_to_product_page(page){
                window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + page)
            },
            del_from_faworites(product_id){
                axios
                .post('../api/del_from_favorite/'+ product_id)
                .then(response => {
                    alert("Product delited from your favorite list!");
                    this.get_products()
                })
                .catch(error =>{
                    alert("Error");
                })
            },
            // go_product_list(){
            //     window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL)
            // },
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