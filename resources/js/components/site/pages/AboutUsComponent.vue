<template>
    <div class="container top_menu_margin">
        <!--About text-->
        <div class="aboutus-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="aboutus">
                            <h1 v-html="this.$siteData.guid_short_description"></h1>
                            <span v-html="this.$siteData.guid_description"></span>
                            
                            <hr>

                            <h2>{{ $t('shop.seller.seller_contact') }}</h2>
                            <p v-if="this.$globalSiteData.email">{{ $t('shop.seller.email') }} - {{ this.$globalSiteData.email }}</p>
                            <p v-if="this.$globalSiteData.number">{{ $t('shop.seller.phone') }} - {{ this.$globalSiteData.number }}</p>

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

        <hr>

        <div class="row" v-if="services.length > 0">
            <!-- <h2 class="page_title">{{ $t('shop.title.services') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.services_description"></span>
            </h3> -->
            <div class="col-sm-12">
                <ServiceItem
                    v-for="service in services"
                    :key='service.id'
                    :service_data="service">
                </ServiceItem>
            </div>
        </div>
        
        <messageComponent />

        <div class="h-recent-work services" id="services" v-if="partners.length != 0">
            <div class="container">

                <h2 class='index_h2'>{{ $t('guide.title.partner') }}</h2>

                <div class="bar"><i class="fa fa-handshake-o" aria-hidden="true"></i></div>
                <h3 class="article_list_short_description"> <span v-html="this.$siteData.partners"></span> </h3>
                
                <div class="row"  v-for="partner in partners" :key="partner.global_data.id">
                    <div class="partners">
                        <div class="col-xs-6 col-md-3">
                            <router-link :to="'partner/'+partner.global_data.url_title" class="info">
                                <site-img v-if="partner.global_data.image != null" :src="'/public/images/partners_img/'+partner.global_data.image" :img_class="'img-responsive'" :alt='partner.locale_data.title'/>
                                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='partner.locale_data.title'/>
                            </router-link>
                            <div class="row text-center">
                                <router-link :to="'partner/'+partner.global_data.url_title" class="info">
                                    <h3>{{ partner.locale_data.title }}</h3>
                                </router-link>
                                <!-- <p>{{ partner.locale_data.short_description }}</p> -->
                                <span v-html="partner.locale_data.short_description"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="previes_partner_bottom" v-if="partners.length > 4">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>

                <div class="next_partner_bottom" v-if="partners.length > 4">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div> -->
            </div>
        </div>
        
        <metaData 
            :title = " $t('guide.meta.about us') "
            :description = "this.$siteData.guid_short_description"
            :image = "'/public/images/meta_img/outdoor.jpg'"
        />

    </div>
</template>

<script>
    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import messageComponent from '../../global_components/MessageComponent'
    import metaData from '../items/MetaDataComponent'
    import RoutesAutersModal from '../items/climbing_routes/items/modals/RoutesAutersListModal.vue'
    
    import ServiceItem from '../items/shop_items_for_guide/ServiceItemComponent'

    export default {
        components: {
            metaData,
            messageComponent,
            servicesListComponent,
            RoutesAutersModal,
            ServiceItem
        },
        data() {
            return {
                partners: [],
                site_social_links: [],
                services: []
            }
        },
        mounted() { 
            this.get_partners()
            this.get_social_links()
            this.get_services()
        },
        methods: {
            get_partners(){
                axios
                .get('/articles/partners/'+localStorage.getItem('lang'))
                .then(response => {
                    this.partners = response.data
                })
                .catch(error =>{
                })
            },

            from_user_site_url_get_domen(url){
                return new URL(url).hostname;
            },

            get_social_links(){
                axios
                .get('/site_social_links/get_site_social_links')
                .then(response => {
                    this.site_social_links = response.data
                })
                .catch(
                    error => console.log(error)
                );
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
        }
    }
</script>

<style>
.partners{
    margin-left: 5%;
    margin-Right: 5%;
}
.previes_partner_bottom{
    float: left;
}
.next_partner_bottom{
    float: right;
}
.next_partner_bottom, .previes_partner_bottom{
    margin-top: -10%;
    font-size: 150%;
    cursor: pointer;
}
</style>