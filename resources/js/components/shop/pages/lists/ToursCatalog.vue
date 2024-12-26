<template>
    <div class="col-md-12">
        <h1 class="page_title">{{ $t('shop.title.tours') }}</h1>

        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

        <h2 class="article_list_short_description">
            <span v-html="this.$siteData.tour_description"></span>
        </h2>

        <span v-if="tours.length">
            <div class="row" v-if="tour_loading">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#27bb7d8c"
                />
            </div>
            <div class="row" v-else>
                <TourItem
                    v-for="tour in tours"
                    :key='tour.id'
                    :tour_data_prop="tour">
                </TourItem>
            </div>
        </span>

        <span v-else>
            <emptyPageComponent />
        </span>

        <metaData 
            :title = " $t('shop.meta.tours') "
            :description = "'Service climbing & mountaineering.'"
            :image = "'/public/images/meta_img/tour.jpg'"
        />
    </div>
</template>

<script>
    import TourItem from '../../items/cards/TourCardComponent'
    import { ContentLoader } from 'vue-content-loader'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'

    import metaData from '../../items/MetaDataComponent'
    export default {
        components: {
            metaData,
            ContentLoader,
            emptyPageComponent,
            TourItem
        },
        data: function () {
            return {
                tours: [],
                site_data: [],
                tour_loading: false,
            };
        },
        mounted() {
            this.get_tours()
        },
        methods: {
            get_tours(){
                this.tour_loading = true
                axios
                .get('/tour/get_tours/'+localStorage.getItem('lang'))
                .then(response => {
                    this.tours = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.tour_loading = false);
            },
        }
    }
</script>
