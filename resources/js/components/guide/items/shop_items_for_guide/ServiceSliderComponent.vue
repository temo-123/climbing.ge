<template>
    <div class="h-recent-work services" id="services" v-if="services.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ $t('shop.title.services') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.data.services_description"></span>
            </h3>

            <card-slider :items="services" item-key="id" :desktop="3" :mobile="1" :autoplay="5000" slide-class="service-slider-item">
                <template #default="{ item }">
                    <ServiceItem :service_data="item"></ServiceItem>
                </template>
            </card-slider>
        </div>
    </div>
</template>


<script>
    import ServiceItem from './cards/ServiceItemComponent'

    export default {
        components: {
            ServiceItem
        },
        data() {
            return {
                services: [],
            }
        },
        mounted() {
            this.get_services()
        },
        methods: {
            get_services(){
                axios
                .get('/get_service/get_local_services/'+localStorage.getItem('lang'))
                .then(response => {
                    this.services = response.data;
                })
                .catch(error =>{
                })
            },
        }
    }
</script>

<style scoped>
    /* ServiceItem carries its own grid column classes — make it fill the slide */
    :deep(.service-slider-item .col-md-4),
    :deep(.service-slider-item .col-sm-6) {
        width: 100%;
        max-width: 100%;
        padding-left: 0;
        padding-right: 0;
        float: none;
    }
</style>
