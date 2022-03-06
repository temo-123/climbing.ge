<template>
    <div class="col-sm-12">

        <h1 class="page_title">products list</h1>
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
        <h6> <span v-html="this.$siteData.shop_short_description"></span> </h6>

        <div class="col-md-12">
            <!-- <div class="bar"></div> -->
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" v-model="filter_category" name="product_modification_for_cart"  @click="sortByCategories()">
                        <option>All</option>
                        <option v-for="category in categories" :key='category.id' :value="category.id">{{ category.us_name }}</option> 
                    </select>
                </div>
                <!-- <div class="col-md-6">
                    <div class="row">
                        <div class="range-slider">
                            <input class='price_range' type="range" min="0" :max="max_price" step="5">
                        
                            <input class='price_range' type="range" min="0" :max="max_price" step="5">
                        </div>
                    </div>
                    <div class="row price_range_text text-center">
                        <p>Price - {{min_price}} - {{max_price}}</p>
                    </div>
                </div> -->
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

        <div class="col-sm-12">
            <!-- <section class="inner"> -->
                <section class="portfolio inner" id="portfolio">

                        <div class="layout">
                            <!-- <section class="inner"> -->
                                <ul class="grid">

                                    <catalogItem
                                        v-for="product in filtred_products"
                                        :key='product.id'
                                        :product_data="product"

                                        @add_to_cart = "addToCart"
                                    />

                                </ul>
                            <!-- </section> -->
                        </div>
                </section>
            <!-- </section> -->
        </div>
    </div>
</template>

<script>
    import catalogItem from '../items/CatalogItemComponent'
    export default {
        // props:[
        //     'site_data',
        // ],
        data: function () {
            return {
                products: [],
                filtred_products: [],
                // site_data: [],
                min_price: 0,
                max_price: 1000,
                filter_category: 'All',
                sortid_products: [],
                categories: [],
            };
        },
        components: {
            catalogItem,
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
                .get('../api/products')
                .then(response => {
                    this.products = response.data

                    this.sortByCategories()
                })
                .catch(error =>{
                })
            },

            sortByCategories(){
                let vm = this;
                if (vm.filter_category == 'All') {
                    this.filtred_products = this.products
                }else{
                    this.filtred_products = this.products.filter(function (item){
                        return item.category_id == vm.filter_category
                    })
                }
            },

            // setRangeSlider(){
            //     if (this.min_price > this.max_price) {
            //         let tmp = this.max_price
            //         this.max_price = this.min_price
            //         this.min_price = tmp
            //     }

            //     this.sortByCategories()
            // },

            addToCart(data){
                axios
                .get('../add_to_cart/'+ data.id)
                .then(response => {
                    console.log(response.data);
                    if (response.data == 'login') {
                        window.location.href = 'login';
                    }
                    else{
                        this.cart_products = response.data
                    }
                })
                .catch(error =>{
                })
            },

            get_site_data() {
                axios
                .get('../get_site_data')
                .then(response => {
                    this.site_data = response.data[0]
                })
                .catch(error =>{
                })
            },

            get_categories(){
                axios
                .get('../api/categories')
                .then(response => {
                    this.categories = response.data
                })
                .catch(error =>{
                })
            },

            // get_product_price_interval(){
            //     axios
            //     .get('../api/products')
            //     .then(response => {
            //         this.max_price = response.data
            //     })
            //     .catch(error =>{
            //     })
            // }
        }
    }
</script>

<style scoped>
    .list_btn{
        border: 1px solid !important;
        border-radius: 3.5px !important;
        border-color: #b3b3b3 !important;
    }

    .range-slider{
        width: 100%;
        margin: auto 16px;
        text-align: center;
        position: relative;
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
        margin-top: 5%;
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
</style>