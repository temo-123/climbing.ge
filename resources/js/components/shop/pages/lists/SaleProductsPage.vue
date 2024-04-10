<template>
    <div class="col-sm-12">

        <h1 class="page_title">{{ $t('shop.title.sale_products') }}</h1>
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

        <h2 class="article_list_short_description">
            <span v-html="this.$siteData.shop_short_description"></span>
        </h2>

        <div class="col-md-12"  v-if="products.length > 0">
            <!-- <div class="bar"></div> -->
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <select class="form-control" v-model="filter_category" name="product_modification_for_cart" @click="sortByCategories()">
                        <option>All</option>
                        <option v-for="category in categories" :key='category.id' :value="category.id">{{ category.us_name }}</option> 
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <select class="form-control" name="product_modification_for_cart">
                        <option>All</option>
                        <option :value="'Custom production'">Custom production</option> 
                        <option :value="'Online sale'">Online sale</option> 
                    </select>
                </div>

                <div class="col-md-2 col-sm-6">
                    <div class="row">
                        <!-- <div class="range-slider"> -->
                            <input class='min_price_range price_range' type="range" min="0" max="999" v-model="min_price" step="10">
                        <!-- </div> -->
                    </div>
                    <div class="row price_range_text text-center">
                        <!-- <p>Minimal price - {{min_price}}</p> -->
                        <p>
                            Min price -
                            <input
                                type="text"
                                v-model="min_price"
                                maxlength ="6"
                                :style="'border: 0; width: 60%;'"
                            /> 
                        </p>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6">
                    <!-- <div class="range-slider"> -->
                        <input class="max_price_range price_range" type="range" min="0" max="999" v-model='max_price' value="1000" step="10">
                    <!-- </div> -->
                    <div class="row price_range_text text-center">
                        <!-- <p>Maximal price - {{max_price}}</p> -->
                        <p>
                            Max price -
                            <input
                                type="text"
                                v-model="max_price"
                                maxlength ="6"
                                :style="'border: 0; width: 60%;'"
                            /> 
                        </p>
                    </div>
                </div>

                <div class="col-md-1 col-sm-6">
                        <button class="btn btn-primary" @click="serReangSlider()">Filtr</button>
                </div>
            </div>
        </div>

        <div class="col-md-12" v-if="products_loading">
            <content-loader
                viewBox="0 0"
                primaryColor="#f3f3f3"
                secondaryColor="#27bb7d8c"
            />
        </div>
        <div class="col-sm-12" v-else>
            <!-- <section class="inner"> -->
                <section class="portfolio inner" id="portfolio" v-if="filtred_products.length > 0">


                        <div class="layout">
                            <!-- <section class="inner"> -->
                                <ul class="grid">

                                    <catalogItem
                                        v-for="product in filtred_products"
                                        :key='product.id'
                                        :product_data="product"
                                    />

                                </ul>
                            <!-- </section> -->
                        </div>
                </section>

                <div v-else>
                    <emptyPageComponent />
                </div>
            <!-- </section> -->
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

    export default {
        components: {
            metaData,
            lingallery,
            catalogItem,
            emptyPageComponent,
            ContentLoader,
        },
        data: function () {
            return {
                products: [],
                filtred_products: [],
                
                min_price: 0,
                max_price: 999,
                filter_category: 'All',
                
                categories: [],
                products_loading: true,
                product_modal: false,
                modalClass: '',
            };
        },
        mounted() {
            this.get_products()
            this.get_categories()
        },
        methods: {
            get_products(){
                axios
                .get('/sale_products/'+localStorage.getItem('lang'))
                .then(response => {
                    this.products = response.data

                    this.sortByCategories()
                })
                .catch(error =>{
                })
                .finally(() => this.products_loading = false);
            },

            sortByCategories(){
                let vm = this;
                if (vm.filter_category == 'All') {
                    this.filtred_products = this.products.filter(function (item){
                        return item.max_price >= vm.min_price && item.max_price <= vm.max_price
                    })
                }
                else{
                    let f_products = this.products.filter(function (item){
                        return item.global_product.category_id == vm.filter_category
                    })

                    this.filtred_products = f_products.filter(function (item){
                        return item.max_price >= vm.min_price && item.max_price <= vm.max_price
                    })
                }
            },

            close_product_modal(){
                this.product_modal = false
                this.quick_product = []
            },

            serReangSlider(){
                if(this.min_price == 0 && this.max_price == 0){
                    this.min_price = 0
                    this.max_price = 1000
                }
                else if(this.min_price > this.max_price){
                    let temp = this.max_price
                    this.max_price = this.min_price
                    this.min_price = temp
                }

                this.sortByCategories()
            },

            get_categories(){
                axios
                .get('/product_category')
                .then(response => {
                    this.categories = response.data
                })
                .catch(error =>{
                })
            },

            get_product_price_interval(){
                axios
                .get('/products/get_products_price_interval')
                .then(response => {
                    // this.max_price = response.data
                })
                .catch(error =>{
                })
            }
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