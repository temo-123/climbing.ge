<template>
    <div class="container top_menu_margin h-recent-work">
        <h1 class="page_title">{{ setvice.local_service.title }}</h1>

        <breadcrumb />

        <div class="row" v-if="setvice.service_images">
            <img class="service_page_image" :src="'../../images/service_img/'+setvice.service_images[0].image" :alt="setvice.local_service.title">
        </div>
        <div class="row service_page_text">
            {{ setvice.local_service.text }}
        </div>

        <div class="row" v-if="this.services.length > 0">
            <hr>
            <h2 class="other_servces">other service</h2>
            <ServiceItem
                v-for="service in services"
                :key='service.id'
                :service_data="service">
            </ServiceItem>
        </div>

        <metaData 
            :title = "setvice.local_service.title"
            :description = "setvice.local_service.short_description"
            :image = "'../../images/service_img/'+setvice.service_images[0].image"
        />
    </div>
</template>

<script>
    import ServiceItem from '../items/ServiceItemComponent'
    import metaData from '../items/MetaDataComponent'
    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            metaData,
            ServiceItem,
            breadcrumb,
        },
        props:[
            'data'
        ],
        data: function () {
            return {
                services: [],
                setvice: [],
            };
        },
        watch: {
            '$route' (to, from) {
                // this.get_services()
                this.get_service()
                window.scrollTo(0,0);
            }
        },
        mounted() {
            this.get_service()
        },
        methods: {
            get_services(activ_service_id){
                axios
                .get('../api/similar_services/'+localStorage.getItem('lang')+'/'+activ_service_id)
                .then(response => {
                    this.services = response.data
                })
                .catch(error =>{
                })
            },
            get_service(){
                axios
                .get('../api/service/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.setvice = response.data[0]
                    this.get_services(this.setvice.global_service.id)
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
