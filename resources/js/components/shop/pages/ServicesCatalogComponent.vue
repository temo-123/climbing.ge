<template>
    <div class="col-md-12">
        <h1 class="page_title">{{ $t('shop.title.services') }}</h1>

        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
        
        <h2 style="text-align: center;">
            <span v-html="this.$siteData.services_description"></span>
        </h2>

        <span v-if="services.length">
            <div class="row" v-if="services_loading">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#27bb7d8c"
                />
            </div>
            <div class="row" v-else>
                <ServiceItem
                    v-for="service in services"
                    :key='service.id'
                    :service_data="service">
                </ServiceItem>
            </div>
        </span>

        <span v-else>
            <emptyPageComponent />
        </span>

        <metaData 
            :title = " $t('shop.meta.services') "
            :description = "'Service climbing & mountaineering.'"
            :image = "'/public/images/meta_img/services.jpg'"
        />
    </div>
</template>

<script>
    import ServiceItem from '../items/ServiceItemComponent'
    import { ContentLoader } from 'vue-content-loader'
    import emptyPageComponent from '../../global_components/EmptyPageComponent'

    import metaData from '../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            ServiceItem,
            ContentLoader,
            emptyPageComponent,
        },
        data: function () {
            return {
                services: [],
                site_data: [],
                services_loading: true,
            };
        },
        mounted() {
            this.get_services()
        },
        methods: {
            get_services(){
                axios
                .get('../api/services/'+localStorage.getItem('lang'))
                .then(response => {
                    this.services = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.services_loading = false);
            },
        }
    }
</script>
