<template>
    <div class="container top_menu_margin">
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">

                            <h1 v-html="this.$siteData.shop_short_description"></h1>
                            <span v-html="this.$siteData.shop_description"></span>

                            <hr>

                            <h3>{{ $t('shop.seller.seller contact') }}</h3>
                            <p v-if="this.$globalSiteData.email">{{ $t('shop.seller.email') }} - {{ this.$globalSiteData.email }}</p>
                            <p v-if="this.$globalSiteData.number">{{ $t('shop.seller.phone') }} - {{ this.$globalSiteData.number }}</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <servicesListComponent />
        
        <messageComponent />

        <metaData 
            :title = " $t('shop.meta.about us') "
            :description = "'Climbing products. All products made in Georgia.'"
            :image = "'/public/images/meta_img/shop.jpg'"
        />
    </div>
</template>

<script>
    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import messageComponent from '../../global_components/MessageComponent.vue'
    
    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            messageComponent,
            servicesListComponent,
        },
        data: function () {
            return {
                cart_products: [],
                site_data: [],
            };
        },
        mounted() {
            // this.get_site_data()
        },
        methods: {
            // get_site_data() {
            //     axios
            //     .get('../get_site_data')
            //     .then(response => {
            //         this.site_data = response.data[0]
            //     })
            //     .catch(error =>{
            //     })
            // }
        }
    }
</script>
