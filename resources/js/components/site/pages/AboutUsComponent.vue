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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <servicesListComponent />
        
        <messageComponent />

        <div class="h-recent-work services" id="services" v-if="partners.length != 0">
            <div class="container">

                <h2 class='index_h2'>{{ $t('guide.title.partner') }}</h2>

                <div class="bar"><i class="fa fa-handshake-o" aria-hidden="true"></i></div>
                <h3 class="article_list_short_description"> <span v-html="this.$siteData.partners"></span> </h3>
                
                <div class="row"  v-for="partner in partners" :key="partner.id">
                    <div class="partners">
                        <div class="col-xs-6 col-md-3">
                            <router-link :to="'partner/'+partner.url_title" class="info">
                                <site-img v-if="partner.image != null" :src="'/public/images/partners_img/'+partner.image" :img_class="'img-responsive'" :alt='partner[0][0].title'/>
                                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='partner[0][0].title'/>
                            </router-link>
                            <div class="row text-center">
                                <router-link :to="'partner/'+partner.url_title" class="info">
                                    <h3>{{ partner[0][0].title }}</h3>
                                </router-link>
                                <p>{{ partner[0][0].short_description }}</p>
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
    export default {
        components: {
            metaData,
            messageComponent,
            servicesListComponent
        },
        data() {
            return {
                partners: [],
            }
        },
        mounted() { 
            this.get_partners()
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
            }
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