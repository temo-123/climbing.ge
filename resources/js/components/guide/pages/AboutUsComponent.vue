<template>
    <div class="container">
        <!--About text-->
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">
                            <h1 v-html="this.$siteData.data.guid_short_description"></h1>
                            <span v-html="this.$siteData.data.guid_description"></span>
                            
                            <hr>

                            <h2>{{ $t('shop.seller.seller_contact') }}</h2>
                            <p v-if="this.$globalSiteData.data.email">{{ $t('shop.seller.email') }} - {{ this.$globalSiteData.data.email }}</p>
                            <p v-if="this.$globalSiteData.data.number">{{ $t('shop.seller.phone') }} - {{ this.$globalSiteData.data.number }}</p>

                            <hr>
                            
                            <h2>{{ $t('guide.article.other_sites') }}</h2>
                            <span v-for="link in site_social_links" :key="link.id">
                                <p v-if="link.title">
                                    <a :href="link.url" target="_blank">{{ link.title }}</a>
                                </p>
                                <p v-else>
                                    <a :href="link.url" target="_blank">{{ from_user_site_url_get_domen(link.url) }}</a>
                                </p>
                            </span>

                            <hr>

                            <!-- <h2>Alse you can see sport climbing routes authers and them conts.</h2> -->
                            <routesAutersModal />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <servicesListComponent />

        <serviceSliderComponent />
        
        <messageComponent />

        <partnersComponent />
        
        <metaData 
            :title = " $t('guide.meta.about us') "
            :description = "this.$siteData.data.guid_short_description"
            :image = "'/public/images/meta_img/outdoor.jpg'"
        />

    </div>
</template>

<script>
    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import messageComponent from '../../global_components/MessageComponent'
    import metaData from '../items/MetaDataComponent'
    import RoutesAutersModal from '../items/climbing_routes/items/modals/RoutesAutersListModal.vue'
    
    import serviceSliderComponent from '../items/shop_items_for_guide/ServiceSliderComponent'
    import partnersComponent from '../items/PartnersComponent.vue'

    export default {
        components: {
            metaData,
            messageComponent,
            servicesListComponent,
            RoutesAutersModal,
            serviceSliderComponent,
            partnersComponent
        },
        data() {
            return {
                site_social_links: [],
            }
        },
        mounted() { 
            this.get_social_links()
        },
        methods: {
            from_user_site_url_get_domen(url){
                return new URL(url).hostname;
            },

            get_social_links(){
                axios
                .get('/get_site_social_links/get_site_social_links')
                .then(response => {
                    this.site_social_links = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>
