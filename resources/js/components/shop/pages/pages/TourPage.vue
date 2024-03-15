<template>
    <div class="container top_menu_margin h-recent-work">
        <h1 class="page_title">{{ tour.locale_data.title }}</h1>

        <!-- <breadcrumb /> -->

        <div class="row" v-if="tour.tour_images.length != 0">
            <img class="service_page_image" :src="'../../images/tour_img/'+tour.tour_images[0].image" :alt="tour.locale_data.title">
        </div>

        <div class="row service_page_text">
            <span v-html="tour.locale_data.text"></span>
        </div>

        <tourMessageForm 
            :tour_id_prop = tour.global_data.id
        />
        
        <gallery 
            :images_prop="tour.tour_images"
            :folder_path_prop="'/public/images/tour_img/'"
        />

        <div class="row" v-if="this.tours.length != 0">
            <hr>

            <h2 class="other_servces">{{ $t('shop.title.similar.tours') }}</h2>

            <tourItem
                v-for="tr in tours"
                :key='tr.global_data.id'
                :tour_data="tr">
            </tourItem>
        </div>

        <metaData 
            v-if="tour.tour_images.length != 0"
            :title = "tour.locale_data.title"
            :description = "tour.locale_data.short_description"
            :image = "'/images/tour_img/'+tour.tour_images[0].image"
        />
        <metaData 
            v-else
            :title = "tour.locale_data.title"
            :description = "tour.locale_data.short_description"
            :image = "'/'"
        />
    </div>
</template>

<script>
    import tourItem from '../../items/cards/SimilarTourCardComponent'
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import gallery from '../../items/GalleryComponent.vue'
    import tourMessageForm from '../../items/reservation_forms/TourMessageFormComponent.vue'

    export default {
        components: {
            metaData,
            tourItem,
            breadcrumb,
            gallery,
            tourMessageForm
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
                // this.get_tours()
                this.get_tour()
                window.scrollTo(0,0);
            }
        },
        mounted() {
            // this.get_tours()
            this.get_tour()
        },
        methods: {
            get_tours(activ_tour_id){
                axios
                .get('/tour/get_similar_tours/'+localStorage.getItem('lang')+'/'+activ_tour_id)
                .then(response => {
                    this.tours = response.data
                })
                .catch(error =>{
                })
            },
            get_tour(){
                axios
                .get('/tour/get_tour/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
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
