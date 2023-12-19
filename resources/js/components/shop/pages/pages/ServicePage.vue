<template>
    <div class="container top_menu_margin h-recent-work">
        <h1 class="page_title">{{ service.locale_data.title }}</h1>

        <!-- <breadcrumb /> -->

        <div class="row" v-if="service.service_images.length > 0">
            <img class="service_page_image" :src="'../../images/service_img/'+service.service_images[0].image" :alt="service.locale_data.title">
        </div>

        <div class="row service_page_text">
            <!-- {{ service.locale_data.text }} -->
            <span v-html="service.locale_data.text"></span>
        </div>

        <gallery 
            :images_prop="service.service_images"
            :folder_path_prop="'/public/images/service_img/'"
        />

        <div class="row" v-if="this.services.length > 0">
            <hr>

            <h2 class="other_servces">{{ $t('shop.title.similar.services') }}</h2>

            <ServiceItem
                v-for="service in services"
                :key='service.global_data.id'
                :service_data="service">
            </ServiceItem>
        </div>

        <metaData 
            :title = "service.locale_data.title"
            :description = "service.locale_data.short_description"
            :image = "'/images/service_img/'+service.service_images[0].image"
        />
    </div>
</template>

<script>
    import ServiceItem from '../../items/ServiceItemComponent'
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import gallery from '../../items/GalleryComponent.vue'

    export default {
        components: {
            metaData,
            ServiceItem,
            breadcrumb,
            gallery,
        },
        props:[
            'data'
        ],
        data: function () {
            return {
                services: [],
                service: [],
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
                    this.service = response.data
                    this.get_services(this.service.global_data.id)
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
