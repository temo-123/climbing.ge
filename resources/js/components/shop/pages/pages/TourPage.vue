<template>
    <div class="container top_menu_margin h-recent-work">
        <h1 class="page_title">{{ toure.locale_data.title }}</h1>

        <!-- <breadcrumb /> -->

        <div class="row" v-if="toure.toure_images.length > 0">
            <img class="service_page_image" :src="'../../images/toure_img/'+toure.toure_images[0].image" :alt="toure.locale_data.title">
        </div>

        <div class="row service_page_text">
            <span v-html="toure.locale_data.text"></span>
        </div>

        <toureMessageForm />
        
        <gallery 
            :images_prop="toure.toure_images"
            :folder_path_prop="'/public/images/toure_img/'"
        />

        <div class="row" v-if="this.tours.length > 0">
            <hr>

            <h2 class="other_servces">{{ $t('shop.title.similar.tours') }}</h2>

            <toureItem
                v-for="toure in tours"
                :key='toure.global_data.id'
                :toure_data="toure">
            </toureItem>
        </div>

        <metaData 
            :title = "toure.locale_data.title"
            :description = "toure.locale_data.short_description"
            :image = "'/images/toure_img/'+toure.toure_images[0].image"
        />
    </div>
</template>

<script>
    import toureItem from '../../items/cards/SimilarToureCardComponent'
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import gallery from '../../items/GalleryComponent.vue'
    import toureMessageForm from '../../items/ToureMessageFormComponent.vue'

    export default {
        components: {
            metaData,
            toureItem,
            breadcrumb,
            gallery,
            toureMessageForm
        },
        props:[
            'data'
        ],
        data: function () {
            return {
                tours: [],
                toure: [],
            };
        },
        watch: {
            '$route' (to, from) {
                // this.get_tours()
                this.get_toure()
                window.scrollTo(0,0);
            }
        },
        mounted() {
            this.get_toure()
        },
        methods: {
            get_tours(activ_toure_id){
                axios
                .get('/toure/get_similar_tours/'+localStorage.getItem('lang')+'/'+activ_toure_id)
                .then(response => {
                    this.tours = response.data
                })
                .catch(error =>{
                })
            },
            get_toure(){
                axios
                .get('/toure/get_tour/'+localStorage.getItem('lang')+'/'+this.$route.params.url_title)
                .then(response => {
                    this.toure = response.data
                    this.get_tours(this.toure.global_data.id)
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
