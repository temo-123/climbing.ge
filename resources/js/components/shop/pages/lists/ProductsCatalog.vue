<template>
    <div class="col-sm-12">

        <h1 class="index_h2">{{ $t('shop.title.products') }}</h1>
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

        <h2 class="article_list_short_description">
            <span v-html="this.$siteData.data.shop_short_description"></span>
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
                            <div class="filters-bar">
                                <button type="button" class="btn btn-primary filter-btn" @click="open_menu()">
                                    <i class="fa fa-filter"></i> Filter products
                                    <span v-if="activeFilterCount > 0" class="filter-count">({{ activeFilterCount }})</span>
                                </button>
                                <div v-if="hasActiveFilters" class="filter-summary">
                                    <span>Filtered by:</span>
                                    <span v-if="currentFilters.brand_id" class="filter-tag">
                                        Brand: {{ currentFilters.brand_id }}
                                        <button @click="removeFilter('brand_id')" class="remove-btn">×</button>
                                    </span>
                                    <span v-if="currentFilters.sale_type" class="filter-tag">
                                        Sale Type: {{ currentFilters.sale_type }}
                                        <button @click="removeFilter('sale_type')" class="remove-btn">×</button>
                                    </span>
                    <span v-if="currentFilters.subcategory_id" class="filter-tag">
                        Subcategory: {{ currentFilters.subcategory_id }}
                        <button @click="removeFilter('subcategory_id')" class="remove-btn">×</button>
                    </span>
                    <span v-if="currentFilters.price_min || currentFilters.price_max" class="filter-tag">
                        Price: {{ currentFilters.price_min ? '$' + currentFilters.price_min : 'Min' }} - {{ currentFilters.price_max ? '$' + currentFilters.price_max : 'Max' }}
                        <button @click="removePriceFilter()" class="remove-btn">×</button>
                    </span>
                                    <button @click="clear_filtrs()" class="clear-all-btn">Clear All</button>
                                </div>
                            </div>
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

                @apply_filters="applyFilters"
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
    import metaData from '../../items/MetaDataComponent'

    import productLeftMenu from '../../items/navbars/LeftMenuComponent'

    export default {
        components: {
            metaData,
            catalogItem,
            emptyPageComponent,
            ContentLoader,
            productLeftMenu
        },
        data: function () {
            return {
                products: [],
                products_loading: true,
                currentFilters: {
                    sale_type: null,
                    brand_id: null,
                    subcategory_id: null,
                    price_min: null,
                    price_max: null
                }
            };
        },
        mounted() {
            this.currentFilters = { ...this.$route.query };
            this.get_products();
            // this.get_categories()
        },
        computed: {
            filtred_products() {
                let filtered = this.products;
                if (this.currentFilters.sale_type) {
                    filtered = filtered.filter(p => p.global_product.sale_type == this.currentFilters.sale_type);
                }
                if (this.currentFilters.brand_id) {
                    filtered = filtered.filter(p => p.global_product.brand_id == this.currentFilters.brand_id);
                }
                if (this.currentFilters.subcategory_id) {
                    filtered = filtered.filter(p => p.global_product.subcategory_id == this.currentFilters.subcategory_id);
                }
                if (this.currentFilters.price_min) {
                    filtered = filtered.filter(p => p.global_product.price >= this.currentFilters.price_min);
                }
                if (this.currentFilters.price_max) {
                    filtered = filtered.filter(p => p.global_product.price <= this.currentFilters.price_max);
                }
                return filtered;
            },
            hasActiveFilters() {
                return this.currentFilters.sale_type || this.currentFilters.brand_id || this.currentFilters.subcategory_id || this.currentFilters.price_min || this.currentFilters.price_max;
            },
            activeFilterCount() {
                return Object.values(this.currentFilters).filter(v => v !== null && v !== undefined).length;
            }
        },
        methods: {
            get_products(){
                this.products_loading = true
                axios
                .get('../api/products/'+localStorage.getItem('lang'))
                .then(response => {
                    this.products = response.data
                    // this.sortByCategories()
                })
                .catch(error =>{
                })
                .finally(() => this.products_loading = false);
            },

            open_menu(){
                this.$refs.left_menu.open_menu()
            },

            updateUrl(){
                let query = {};
                if (this.currentFilters.sale_type) query.sale_type = this.currentFilters.sale_type;
                if (this.currentFilters.brand_id) query.brand_id = this.currentFilters.brand_id;
                if (this.currentFilters.subcategory_id) query.subcategory_id = this.currentFilters.subcategory_id;
                if (this.currentFilters.price_min) query.price_min = this.currentFilters.price_min;
                if (this.currentFilters.price_max) query.price_max = this.currentFilters.price_max;
                this.$router.replace({ query });
            },

            applyFilters(filters) {
                this.currentFilters = {
                    sale_type: filters.sale_type || null,
                    brand_id: filters.brand_id || null,
                    subcategory_id: filters.subcategory_id || null,
                    price_min: filters.price_min || null,
                    price_max: filters.price_max || null
                };
                this.updateUrl();
            },

            clear_filtrs(){
                this.currentFilters = {
                    sale_type: null,
                    brand_id: null,
                    subcategory_id: null,
                    price_min: null,
                    price_max: null
                };
                this.updateUrl();
            },

            removeFilter(filterKey) {
                this.currentFilters[filterKey] = null;
                this.updateUrl();
            },
            removePriceFilter() {
                this.currentFilters.price_min = null;
                this.currentFilters.price_max = null;
                this.updateUrl();
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

    .filter-summary {
        margin: 10px 0;
        padding: 10px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .filter-summary span {
        font-weight: bold;
    }

    .filter-tag {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .remove-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
    }

    .clear-all-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .clear-all-btn:hover {
        background-color: #c82333;
    }

    .filter-btn {
        position: relative;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .filter-count {
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.8em;
        margin-left: 5px;
    }

    .filters-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>
