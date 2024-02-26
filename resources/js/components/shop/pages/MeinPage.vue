<template>
    <div class="col-sm-12">

        <swiperComponent 
            v-if="slides.length > 0"
            :images_prop="slides"
        />

        <div class="row" v-if="products.length > 0">

            <h2 class="page_title">{{ $t('shop.title.products') }}</h2>
            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.shop_short_description"></span>
            </h3>

            <div class="col-sm-12">
                <section class="portfolio inner" id="portfolio" >
                    <div class="layout">
                        <ul class="grid">
                            <catalogItem
                                v-for="product in products"
                                :key='product.id'
                                :product_data="product"

                                @quick_view="quick_view_model"
                            />
                        </ul>
                    </div>
                </section>
            </div>
        </div>
        
        <div class="row" v-if="services.length > 0">
            <h2 class="page_title">{{ $t('shop.title.services') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.services_description"></span>
            </h3>
            <div class="col-sm-12">
                <ServiceItem
                    v-for="service in services"
                    :key='service.id'
                    :service_data="service">
                </ServiceItem>
            </div>
        </div>

        <div class="row" v-if="tours.length > 0">
            <h2 class="page_title">{{ $t('shop.title.tours') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.toure_description"></span>
            </h3>
            <div class="col-sm-12">
                <div class="row">
                    <ToureItem
                        v-for="toure in tours"
                        :key='toure.id'
                        :toure_data="toure">
                    </ToureItem>
                </div>
            </div>
        </div>

        <div v-if="tours.length == 0 && services.length == 0 && products.length == 0">
            <emptyPageComponent />
        </div>

        <metaData 
            :title = " $t('shop.meta.mein page') "
            :description = "'Climbing.ge product, services & tours.'"
            :image = "'/public/images/meta_img/services.jpg'"
        />
    </div>
</template>

<script>
import catalogItem from '../items/CatalogItemComponent'
import ServiceItem from '../items/ServiceItemComponent'
import ToureItem from '../items/cards/ToureCardComponent'

import swiperComponent from '../../global_components/SwiperComponent.vue'

import metaData from '../items/MetaDataComponent'
import emptyPageComponent from '../../global_components/EmptyPageComponent'

export default {
    components: {
        catalogItem,
        ServiceItem,
        metaData,
        ToureItem,
        swiperComponent,
        emptyPageComponent,
    },
    data: function () {
        return {
            products: [],
            services: [],
            tours: [],
            slides: []
        };
    },
    mounted() {
        this.get_products()
        this.get_services()
        this.get_toures()
        this.get_swiper_slides()
    },
    methods: {
        get_products(){
            axios
            .get('/products/'+localStorage.getItem('lang'))
            .then(response => {
                this.products = response.data.slice(0, 3);
                
            })
            .catch(error =>{
            })
            // .finally(() => this.products_loading = false);
        },
        get_services(){
            axios
            .get('/services/'+localStorage.getItem('lang'))
            .then(response => {
                // this.services = response.data
                this.services = response.data.slice(0, 4);
            })
            .catch(error =>{
            })
            // .finally(() => this.services_loading = false);
        },
        get_toures(){
            axios
            .get('/toure/get_tours/'+localStorage.getItem('lang'))
            .then(response => {
                // this.services = response.data
                this.tours = response.data.slice(0, 3);
            })
            .catch(error =>{
            })
            // .finally(() => this.services_loading = false);
        },
        get_swiper_slides(){
            axios
            .get('/store_slides/get_slides/')
            .then(response => {
                this.slides = response;
            })
            .catch(error =>{
            })
            // .finally(() => this.services_loading = false);
        },
    }
}
</script>

<style lang="scss" scoped>

</style>