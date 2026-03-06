<template>
    <div class="col-sm-12">

        <h1 class="index_h2">{{ $t('shop.title.products') }}</h1>
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

        <h2 class="article_list_short_description">
            <span v-html="this.$siteData.data.shop_short_description"></span>
        </h2>

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
                <section class="portfolio inner" id="portfolio">
                    <filtrViewControlsComponent
                        :viewMode="viewMode"
                        :filters="currentFilters"
                        @update:viewMode="viewMode = $event"
                        @apply-filters="applyFilters"
                        @remove-filter="removeFilter"
                        @clear-filters="clear_filtrs"
                    />

                    <div class="layout" v-if="filtred_products.length > 0">
                        <!-- Grid View -->
                        <template v-if="viewMode === 'grid'">
                            <div class="grid">
                                <catalogItem
                                    v-for="product in filtred_products"
                                    :key='product.id'
                                    :product_data="product"
                                />
                            </div>
                        </template>
                        <!-- List View -->
                        <template v-else>
                            <div class="list-view-container">
                                <catalogHorizontalItem
                                    v-for="product in filtred_products"
                                    :key="product.id"
                                    :product_data="product"
                                />
                            </div>
                        </template>
                    </div>
                </section>

                <div v-if="filtred_products.length == 0">
                    <emptyPageComponent />
                </div>
            </div>
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
    import catalogHorizontalItem from '../../items/cards/CatalogHorizontalItemComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../../items/MetaDataComponent'
    import filtrViewControlsComponent from '../../items/FiltrViewControlsComponent'

    export default {
        components: {
            metaData,
            catalogItem,
            catalogHorizontalItem,
            emptyPageComponent,
            ContentLoader,
            filtrViewControlsComponent,
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
                },
                viewMode: 'grid' // 'grid' or 'list'
            };
        },
        mounted() {
            this.loadFiltersFromUrl();
            this.get_products();
        },
        watch: {
            '$route.query': {
                handler(newQuery) {
                    this.loadFiltersFromUrl();
                },
                deep: true
            }
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
                if (this.currentFilters.price_min !== null && this.currentFilters.price_min !== undefined && this.currentFilters.price_min > 0) {
                    filtered = filtered.filter(p => (p.min_price !== undefined ? p.min_price : 0) >= this.currentFilters.price_min);
                }
                if (this.currentFilters.price_max !== null && this.currentFilters.price_max !== undefined && this.currentFilters.price_max > 0) {
                    filtered = filtered.filter(p => (p.max_price !== undefined ? p.max_price : 0) <= this.currentFilters.price_max);
                }
                return filtered;
            },
            hasActiveFilters() {
                return this.currentFilters.sale_type || this.currentFilters.brand_id || this.currentFilters.subcategory_id || this.currentFilters.price_min || this.currentFilters.price_max;
            },
            activeFilterCount() {
                return Object.values(this.currentFilters).filter(v => v !== null && v !== undefined && v !== '').length;
            }
        },
        methods: {
            get_products(){
                this.products_loading = true
                axios
                .get('/get_product/get_local_products/'+localStorage.getItem('lang'))
                .then(response => {
                    this.products = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.products_loading = false);
            },

            updateUrl(){
                let query = {};
                if (this.currentFilters.sale_type && this.currentFilters.sale_type !== '0') query.sale_type = this.currentFilters.sale_type;
                if (this.currentFilters.brand_id && this.currentFilters.brand_id !== 0) query.brand_id = this.currentFilters.brand_id;
                if (this.currentFilters.subcategory_id && this.currentFilters.subcategory_id !== 0) query.subcategory_id = this.currentFilters.subcategory_id;
                if (this.currentFilters.price_min !== null && this.currentFilters.price_min !== 0 && this.currentFilters.price_min !== undefined) query.price_min = this.currentFilters.price_min;
                if (this.currentFilters.price_max !== null && this.currentFilters.price_max !== 0 && this.currentFilters.price_max !== undefined && this.currentFilters.price_max < 5000) query.price_max = this.currentFilters.price_max;
                this.$router.replace({ query });
            },

            loadFiltersFromUrl(){
                const query = this.$route.query;
                this.currentFilters = {
                    sale_type: query.sale_type || null,
                    brand_id: query.brand_id ? Number(query.brand_id) : null,
                    subcategory_id: query.subcategory_id ? Number(query.subcategory_id) : null,
                    price_min: query.price_min ? Number(query.price_min) : null,
                    price_max: query.price_max ? Number(query.price_max) : null
                };
            },

            applyFilters(filters) {
                this.currentFilters = {
                    sale_type: filters.sale_type || null,
                    brand_id: filters.brand_id || null,
                    subcategory_id: filters.subcategory_id || null,
                    price_min: filters.price_min !== null && filters.price_min !== undefined ? filters.price_min : null,
                    price_max: filters.price_max !== null && filters.price_max !== undefined ? filters.price_max : null
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

            removeFilter(key) {
                if (key === 'sale_type') {
                    this.currentFilters.sale_type = null;
                } else if (key === 'brand_id') {
                    this.currentFilters.brand_id = null;
                } else if (key === 'subcategory_id') {
                    this.currentFilters.subcategory_id = null;
                } else if (key === 'price_min') {
                    this.currentFilters.price_min = null;
                } else if (key === 'price_max') {
                    this.currentFilters.price_max = null;
                }
                this.updateUrl();
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

