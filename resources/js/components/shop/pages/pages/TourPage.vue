<template>
    <div class="tour-container container h-recent-work">
        <div class="tour-header">
            <h1 class="tour-title">{{ tour.locale_data.title }}</h1>
        </div>

        <!-- <breadcrumb /> -->

        <div class="tour-image-section" v-if="tour.tour_images.length != 0">
            <img class="tour-image" :src="'../../images/tour_img/'+tour.tour_images[0].image" :alt="tour.locale_data.title" />
        </div>

        <div class="tour-details-section">
            <div class="tour-detail" v-if="tour.locale_data.location">
                <strong>{{ $t('shop.tour.location') }} - <span v-html="tour.locale_data.location"></span></strong>
            </div>
            <div class="tour-detail" v-if="tour.locale_data.duration">
                <strong>{{ $t('shop.tour.duration') }} - <span v-html="tour.locale_data.duration"></span></strong>
            </div>
        </div>

        <div class="tour-description-section">
            <div class="tour-description" v-html="tour.locale_data.text"></div>
        </div>

        <div class="tour-guide-section">
            <h2 class="section-title">{{ $t('shop.tour.guide') }}</h2>
            <usersIconsComponent
                :users_prop="[tour.user]"
            />
        </div>

        <div class="tour-form-section">
            <tourMessageForm
                :tour_id_prop="tour.global_data.id"
            />
        </div>

        <div class="gallery-section">
            <gallery
                :images_prop="tour.tour_images"
                :folder_path_prop="'/public/images/tour_img/'"
            />
        </div>

        <div class="similar-tours-section" v-if="this.tours.length != 0">
            <hr class="section-divider" />

            <h2 class="section-title">{{ $t('shop.title.similar.tours') }}</h2>

            <div class="tours-list">
                <tourItem
                    v-for="tr in tours"
                    :key='tr.global_data.id'
                    :tour_data_prop="tr">
                </tourItem>
            </div>
        </div>

        <metaData
            v-if="tour.tour_images.length != 0"
            :title="tour.locale_data.title"
            :description="tour.locale_data.short_description"
            :image="'/images/tour_img/'+tour.tour_images[0].image"
        />

    </div>
</template>

<script>
    import tourItem from '../../items/cards/TourCardComponent'
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import gallery from '../../items/GalleryComponent.vue'
    import tourMessageForm from '../../items/reservation_forms/TourMessageFormComponent.vue'

    import usersIconsComponent from '../../../global_components/UsersIconsComponent.vue'

    export default {
        components: {
            metaData,
            tourItem,
            breadcrumb,
            gallery,
            tourMessageForm,
            usersIconsComponent
        },
        props:[
            'data'
        ],
        data: function () {
            return {
                tours: [],
                tour: [],
            };
        },
        watch: {
            '$route' (to, from) {
                this.get_tour()
                window.scrollTo(0,0);
            }
        },
        mounted() {
            this.get_tour()
        },
        methods: {
            get_tours(activ_tour_id){
                axios
                .get('/get_tour/get_similar_tours/'+localStorage.getItem('lang')+'/'+activ_tour_id)
                .then(response => {
                    this.tours = response.data
                })
                .catch(error =>{
                })
            },
            get_tour(){
                axios
                .get('/get_tour/get_tour/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.tour = response.data
                    this.get_tours(response.data.global_data.id)
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style scoped>
    .tour-container {
        width: 100%;
        /* padding: 20px; */
    }

    .tour-header {
        margin-bottom: 20px;
    }

    .tour-title {
        text-align: center;
    }

    .tour-image-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .tour-image {
        max-width: 100%;
        height: auto;
    }

    .tour-details-section {
        margin-bottom: 20px;
    }

    .tour-detail {
        margin-bottom: 10px;
    }

    .tour-description-section {
        margin-bottom: 20px;
    }

    .tour-description {
        /* max-width: 800px; */
        margin: 0 auto;
    }

    .tour-guide-section {
        margin-bottom: 20px;
    }

    .section-title {
        text-align: center;
        margin-bottom: 10px;
    }

    .tour-form-section {
        margin-bottom: 20px;
    }

    .gallery-section {
        margin-bottom: 20px;
    }

    .similar-tours-section {
        margin-top: 20px;
    }

    .section-divider {
        margin-bottom: 20px;
    }

    .tours-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .tour-container {
            padding: 15px;
        }

        .tour-header {
            margin-bottom: 15px;
        }

        .tour-image-section {
            margin-bottom: 15px;
        }

        .tour-details-section {
            margin-bottom: 15px;
        }

        .tour-detail {
            margin-bottom: 8px;
        }

        .tour-description-section {
            margin-bottom: 15px;
        }

        .tour-guide-section {
            margin-bottom: 15px;
        }

        .section-title {
            margin-bottom: 8px;
        }

        .tour-form-section {
            margin-bottom: 15px;
        }

        .gallery-section {
            margin-bottom: 15px;
        }

        .similar-tours-section {
            margin-top: 15px;
        }

        .tours-list {
            gap: 15px;
        }
    }
</style>
