<template>
    <div class="col-sm-12">

        <h1 class="index_h2">{{ $t('shop.title.products') }}</h1>
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

        <h2 class="article_list_short_description">
            <span v-html="this.$siteData.shop_short_description"></span>
        </h2>
        <!-- <p>{{ $n(10000, 'currency') }}</p>
        <p>{{ $n(10000, 'currency', 'en-US') }}</p>
        <p>{{ $n(10000, 'currency', 'en-US', { useGrouping: false }) }}</p>
        <p>{{ $n(987654321, 'currency', { notation: 'compact' }) }}</p>
        <p>{{ $n(0.99123, 'percent') }}</p>
        <p>{{ $n(0.99123, 'percent', { minimumFractionDigits: 2 }) }}</p>
        <p>{{ $n(12.11612345, 'decimal') }}</p>
        <p>{{ $n(12145281111, 'decimal', 'en-US') }}</p> -->


        <div class="col-md-12" v-if="products_loading">
            <div class="col-md-12">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#27bb7d8c"
                />
            </div>
        </div>

        <div class="col-md-12"  v-else>
            <div class="col-sm-12 ">
                <!-- <section class="inner"> -->
                    <section class="portfolio inner" id="portfolio">

                            <button type="submit" class="btn btn-default btn-send main-btn" @click="open_menu()">Filter products</button>

                            <div class="layout" v-if="filtred_products.length > 0">
                                <!-- <section class="inner"> -->
                                    <div class="grid">

                                        <catalogItem
                                            v-for="product in filtred_products"
                                            :key='product.id'
                                            :product_data="product"
                                        />

                                    </div>
                                <!-- </section> -->
                            </div>
                    </section>

                    <div v-if="filtred_products.length == 0">
                        <emptyPageComponent />
                    </div>
                <!-- </section> -->
            </div>

            <productLeftMenu 
                ref="left_menu"

                @sort_by_sale_type="sort_by_sale_type"
                @sort_by_brand="sort_by_brand"
                @sort_by_subcategories="sort_by_subcategories"
                @clear_filtrs="clear_filtrs"
            />
        </div>
        
        <metaData 
            :title = " $t('shop.meta.products') "
            :description = "'Climbing products. All products made in Georgia.'"
            :image = "'/public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import catalogItem from '../../items/cards/CatalogItemComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import { ContentLoader } from 'vue-content-loader'
    import lingallery from 'lingallery'; // https://github.com/ChristophAnastasiades/Lingallery
    import metaData from '../../items/MetaDataComponent'

    import productLeftMenu from '../../items/navbars/LeftMenuComponent'

    export default {
        components: {
            metaData,
            lingallery,
            catalogItem,
            emptyPageComponent,
            ContentLoader,
            productLeftMenu
        },
        data: function () {
            return {
                products: [],
                filtred_products: [],
                
                products_loading: true,
                product_modal: false,
                modalClass: '',
            };
        },
        mounted() {
            this.get_products()
            // this.get_categories()
        },
        methods: {
            get_products(){
                this.products_loading = true
                axios
                .get('../api/products/'+localStorage.getItem('lang'))
                .then(response => {
                    this.products = response.data

                    this.filtred_products = this.products
                    // this.sortByCategories()
                })
                .catch(error =>{
                })
                .finally(() => this.products_loading = false);
            },

            open_menu(){
                this.$refs.left_menu.open_menu()
            },
            
            sort_by_sale_type(sale_type){
                if(sale_type == 0){
                    this.get_products()
                }
                else{
                    // if(this.filtred_products.length == this.products.length){
                        this.filtred_products = this.products.filter(function (item){
                            return item.global_product.sale_type == sale_type
                        })
                    // }
                    // else{
                    //     this.filtred_products = this.filtred_products.filter(function (item){
                    //         return item.global_product.sale_type == sale_type
                    //     })
                    // }
                }
            },
            
            sort_by_brand(filter_brand){
                if(filter_brand == 0){
                    this.get_products()
                }
                else{
                    // if(this.filtred_products.length == this.products.length){
                        this.filtred_products = this.products.filter(function (item){
                            return item.global_product.brand_id == id
                        })
                    // }
                    // else{
                    //     this.filtred_products = this.filtred_products.filter(function (item){
                    //         return item.global_product.brand_id == filter_brand
                    //     })
                    // }
                }
            },

            sort_by_subcategories(subcategory_id){
                if(subcategory_id == 0){
                    this.get_products()
                }
                else{
                    // if(this.filtred_products.length == this.products.length){
                        this.filtred_products = this.products.filter(function (item){
                            return item.global_product.subcategory_id == subcategory_id
                        })
                    // }
                    // else{
                    //     this.filtred_products = this.filtred_products.filter(function (item){
                    //         return item.global_product.subcategory_id == subcategory_id
                    //     })
                    // }
                }
            },

            clear_filtrs(){
                this.get_products()
            },
        }
    }
</script>

<style scoped>
    .model_price_text_size{
        font-size: 2.5em;
    }
    .margin_bottom_top{
        margin-top: -3%;
        margin-bottom: -3%;
    }
    .list_btn{
        border: 1px solid !important;
        border-radius: 3.5px !important;
        border-color: #b3b3b3 !important;
    }

    .range-slider{
        width: 100%;
        /* margin: auto 16px;
        text-align: center;
        position: relative; */
    }

    .range-slider svg, .range-slider input[type = range]{
        position: absolute;
        left: 0;
        right: 0;
    }

    input[type=range]::-webkit-slider-thumb{
        z-index: 2;
        position: relative;
        top: 2px;
        margin-top: -7px;
    }

    .price_range{
        width: 100%
    }

    .price_range_text p{
        /* margin-top: 5%; */
        margin-bottom: 0% !important;
    }

    /* .max_price_range{
        -webkit-transform: rotateY(180deg);
        -moz-transform: rotateY(180deg);
        -ms-transform: rotateY(180deg);
        -o-transform: rotateY(180deg);
        transform: rotateY(180deg);
    } */

    /* Switch button */
    .btn-default.btn-on.active{
        background-color: #8ee48e;
    }
    .btn-default.btn-off.active{
        background-color: #fc8480;
    }

    .product_style_but_icon{
        font-size: 180%;
    }



    .modal-content {
        margin-top: 50% !important;
    }
</style>