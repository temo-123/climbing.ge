<template>
    <div class="col-sm-12">

        <h1 class="page_title">{{ $t('shop.title.products') }}</h1>
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
        <h3> <span v-html="this.$siteData.shop_short_description"></span> </h3>

        <!-- <p>{{ $n(10000, 'currency') }}</p>
        <p>{{ $n(10000, 'currency', 'en-US') }}</p>
        <p>{{ $n(10000, 'currency', 'en-US', { useGrouping: false }) }}</p>
        <p>{{ $n(987654321, 'currency', { notation: 'compact' }) }}</p>
        <p>{{ $n(0.99123, 'percent') }}</p>
        <p>{{ $n(0.99123, 'percent', { minimumFractionDigits: 2 }) }}</p>
        <p>{{ $n(12.11612345, 'decimal') }}</p>
        <p>{{ $n(12145281111, 'decimal', 'en-US') }}</p> -->

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
                    <!-- <div class="row"> -->
                        <button class="btn btn-primary" @click="serReangSlider()">Filtr</button>
                    <!-- </div> -->
                </div>
                <!-- <div class="col-md-2 col-md-offset-6 text-right">
                    <div class="btn-group list_btn" id="status" data-toggle="buttons" style="border: 1px; border-style: solid;">
                        <label class="btn btn-default btn-on btn-xs active">
                        <input type="radio" value="1" name="multifeatured_module[module_id][status]" class="product_style_but" checked="checked">
                            <i class="fa fa-table product_style_but_icon"></i>
                        </label>
                        <label class="btn btn-default btn-off btn-xs ">
                        <input type="radio" value="0" name="multifeatured_module[module_id][status]" class="product_style_but">
                            <i class="fa fa-th-list product_style_but_icon"></i>
                        </label>
                    </div>
                </div> -->
            </div>
            <!-- <div class="bar"></div> -->
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

                                        @quick_view="quick_view_model"
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

        <stack-modal
            :show="product_modal"
            title="Product"
            @close="close_product_modal()"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <pre class="language-vue">
                <span v-for="q_product in quick_product" :key='q_product.id'>
                    <h1 class="margin_bottom_top">{{ q_product.locale_product.title }}</h1>

                    <site-img class="margin_bottom_top" v-if="q_product.product_images.length > 0" :src="'/public/images/product_option_img/'+q_product.product_images[0]" :alt='q_product.locale_product.title'/>
                    <site-img class="margin_bottom_top" v-if="q_product.product_images.length == 0" :src="'/public/images/site_img/shop_demo.jpg'" :alt="q_product.locale_product.title" />

                    <p class="margin_bottom_top model_price_text_size" v-if="q_product.min_price != q_product.max_price" >{{ q_product.min_price }} ₾ - {{ q_product.max_price }} ₾</p>
                    <p class="margin_bottom_top model_price_text_size" v-if="q_product.min_price == q_product.max_price">{{ q_product.max_price }} ₾</p>

                    <span class="margin_bottom_top" v-html="q_product.locale_product.text"></span>

                    <router-link :to="'product/'+q_product.global_product.url_title" class="btn btn-primary margin_bottom_top" style="float: left;"> Open product</router-link>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- <router-link :to="'product/'+quick_product[0].global_product.url_title" class="btn btn-primary"> Open product page</router-link> -->
                </div>
            </div>
        </stack-modal>
        
        <metaData 
            :title = " $t('shop.meta.products') "
            :description = "'Climbing products. All products made in Georgia.'"
            :image = "'/public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import catalogItem from '../items/CatalogItemComponent'

    import emptyPageComponent from '../../global_components/EmptyPageComponent'
    import { ContentLoader } from 'vue-content-loader'

    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import lingallery from 'lingallery'; // https://github.com/ChristophAnastasiades/Lingallery

    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            lingallery,
            StackModal,
            SlickItem,
            SlickList,
            catalogItem,
            emptyPageComponent,
            ContentLoader
        },
        // props:[
        //     'site_data',
        // ],
        data: function () {
            return {
                products: [],
                filtred_products: [],
                // site_data: [],
                min_price: 0,
                max_price: 999,
                filter_category: 'All',
                // sortid_products: [],
                categories: [],
                products_loading: true,
                product_modal: false,
                modalClass: '',

                quick_product: [],
            };
        },
        mounted() {
            this.get_products()
            // this.get_site_data()

            this.get_categories()
            // this.get_product_price_interval()
        },
        methods: {
            get_products(){
                axios
                .get('../api/products/'+localStorage.getItem('lang'))
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

            quick_view_model(event){
                // alert(event)

                this.quick_product = []

                axios
                .get('../api/get_quick_product/'+localStorage.getItem('lang')+'/'+event)
                .then(response => {
                    this.quick_product = response.data
                    this.product_modal = true
                })
                .catch(error =>{
                })
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

            // setRangeSlider(){
            //     if (this.min_price > this.max_price) {
            //         let tmp = this.max_price
            //         this.max_price = this.min_price
            //         this.min_price = tmp
            //     }

            //     this.sortByCategories()
            // },

            // addToCart(data){
            //     axios
            //     .get('../api/add_to_cart/'+ data.id)
            //     .then(response => {
            //         console.log(response.data);
            //         if (response.data == 'login') {
            //             window.location.href = 'login';
            //         }
            //         else{
            //             this.cart_products = response.data
            //         }
            //     })
            //     .catch(error =>{
            //     })
            // },

            // get_site_data() {
            //     axios
            //     .get('../get_site_data')
            //     .then(response => {
            //         this.site_data = response.data[0]
            //     })
            //     .catch(error =>{
            //     })
            // },

            get_categories(){
                axios
                .get('../api/product_category')
                .then(response => {
                    this.categories = response.data
                })
                .catch(error =>{
                })
            },

            get_product_price_interval(){
                axios
                .get('../api/products/get_products_price_interval')
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