<template>
    <div class="col-sm-12">

        <swiperComponent 
            :category_prop="'shop'"
            :image_path_prop="'/images/head_slider_img/shop/'"
        />

        <div class="row" v-if="products.length > 0">

            <h2 class="index_h2">{{ $t('shop.title.products') }}</h2>
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
            <h2 class="index_h2">{{ $t('shop.title.services') }}</h2>

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
            <h2 class="index_h2">{{ $t('shop.title.tours') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.tour_description"></span>
            </h3>
            <div class="col-sm-12">
                <!-- <div class="row"> -->
                    <tourItem
                        v-for="tour in tours"
                        :key='tour.global_data.id'
                        :tour_data_prop="tour">
                    </tourItem>
                </div>
            <!-- </div> -->
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
import catalogItem from '../items/cards/CatalogItemComponent'
import ServiceItem from '../items/cards/ServiceItemComponent'
import tourItem from '../items/cards/TourCardComponent'

import swiperComponent from '../../global_components/SwiperComponent.vue'

import metaData from '../items/MetaDataComponent'
import emptyPageComponent from '../../global_components/EmptyPageComponent'

export default {
    components: {
        catalogItem,
        ServiceItem,
        metaData,
        tourItem,
        swiperComponent,
        emptyPageComponent,
    },
    data: function () {
        return {
            products: [],
            services: [],
            tours: [],
        };
    },
    mounted() {
        this.get_products()
        this.get_services()
        this.get_tours()
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
        get_tours(){
            axios
            .get('/tour/get_tours/'+localStorage.getItem('lang'))
            .then(response => {
                // this.services = response.data
                this.tours = response.data.slice(0, 3);
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