<template>
    <div class="service-container container h-recent-work">
        <div class="service-header">
            <h1 class="service-title">{{ service.locale_data.title }}</h1>
        </div>

        <!-- <breadcrumb /> -->

        <div class="service-image-section" v-if="service.service_images.length > 0">
            <img class="service-image" :src="'../../images/service_img/'+service.service_images[0].image" :alt="service.locale_data.title" />
        </div>

        <div class="service-description-section">
            <div class="service-description" v-html="service.locale_data.text"></div>
        </div>

        <div class="gallery-section">
            <gallery
                :images_prop="service.service_images"
                :folder_path_prop="'/public/images/service_img/'"
            />
        </div>

        <div class="similar-services-section" v-if="this.services.length > 0">
            <hr class="section-divider" />

            <h2 class="section-title">{{ $t('shop.title.similar.services') }}</h2>

            <div class="services-list">
                <ServiceItem
                    v-for="service in services"
                    :key='service.global_data.id'
                    :service_data="service">
                </ServiceItem>
            </div>
        </div>

        <metaData
            :title="service.locale_data.title"
            :description="service.locale_data.short_description"
            :image="'/images/service_img/'+service.service_images[0].image"
        />
    </div>
</template>

<script>
    import ServiceItem from '../../items/cards/ServiceItemComponent'
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
                .get('/get_service/get_similar_services/'+localStorage.getItem('lang')+'/'+activ_service_id)
                .then(response => {
                    this.services = response.data
                })
                .catch(error =>{
                })
            },
            get_service(){
                axios
                .get('/get_service/get_local_service_in_page/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
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

<style scoped>
    .service-container {
        width: 100%;
    }

    .service-header {
        margin-bottom: 20px;
    }

    .service-title {
        text-align: center;
    }

    .service-image-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .service-image {
        max-width: 100%;
        height: auto;
    }

    .service-description-section {
        margin-bottom: 20px;
    }

    .service-description {
        /* max-width: 800px; */
        margin: 0 auto;
    }

    .gallery-section {
        margin-bottom: 20px;
    }

    .similar-services-section {
        margin-top: 20px;
    }

    .section-divider {
        margin-bottom: 20px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 20px;
    }

    .services-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .service-header {
            margin-bottom: 15px;
        }

        .service-image-section {
            margin-bottom: 15px;
        }

        .service-description-section {
            margin-bottom: 15px;
        }

        .gallery-section {
            margin-bottom: 15px;
        }

        .similar-services-section {
            margin-top: 15px;
        }

        .section-title {
            margin-bottom: 15px;
        }

        .services-list {
            gap: 15px;
        }
    }
</style>
