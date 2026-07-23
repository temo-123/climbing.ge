<template>
    <div class="col-md-12">
        <h1 class="index_h2">{{ $t('shop.title.tours') }}</h1>

        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

        <h2 class="article_list_short_description">
            <span v-html="this.$siteData.data.tour_description"></span>
        </h2>

        <div class="row" v-if="categories.length > 0">
            <div class="container articles_filter_bar">
                <div class="col-md-6 col-sm-6">
                    {{ $t('shop.filters.categories_label') }}
                </div>
                <div class="col-md-6 col-sm-6">
                    <select class="form-control" v-model="filter_category" @change="on_filter_change()">
                        <option value="All">{{ $t('all') }}</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.us_name }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-if="tour_loading">
            <skeleton-loader
                viewBox="0 0"
                primaryColor="#f3f3f3"
                secondaryColor="#27bb7d8c"
            />
        </div>
        <div v-else>
            <div class="row" v-if="filtered_tours.length > 0">
                <TourItem
                    v-for="tour in filtered_tours"
                    :key='tour.id'
                    :tour_data_prop="tour">
                </TourItem>
            </div>
            <div v-else>
                <emptyPageComponent />
            </div>
        </div>

        <metaData 
            :title = " $t('shop.meta.tours') "
            :description = "'Service climbing & mountaineering.'"
            :image = "'/public/images/meta_img/tour.jpg'"
        />
    </div>
</template>

<script>
    import TourItem from '../../items/cards/TourCardComponent'

    import emptyPageComponent from '../../../global_components/EmptyPageComponent'

    import metaData from '../../items/MetaDataComponent'
    export default {
        components: {
            metaData,

            emptyPageComponent,
            TourItem
        },
        data: function () {
            return {
                tours: [],
                categories: [],
                filter_category: 'All',
                site_data: [],
                tour_loading: false,
            };
        },
        computed: {
            filtered_tours() {
                if (this.filter_category === 'All') {
                    return this.tours
                }
                return this.tours.filter(tour => tour.global_data.category_id == this.filter_category)
            }
        },
        watch: {
            '$route.query': {
                handler() {
                    this.loadFiltersFromUrl()
                },
                deep: true
            }
        },
        mounted() {
            this.loadFiltersFromUrl()
            this.get_tours()
            this.get_tour_categories()
        },
        methods: {
            get_tours(){
                this.tour_loading = true
                axios
                .get('/get_tour/get_tours/'+localStorage.getItem('lang'))
                .then(response => {
                    this.tours = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.tour_loading = false);
            },

            get_tour_categories(){
                axios
                .get('/get_tour/get_category/get_all_categories')
                .then(response => {
                    this.categories = response.data
                })
                .catch(error => {
                })
            },

            loadFiltersFromUrl(){
                this.filter_category = this.$route.query.category || 'All'
            },

            updateUrl(){
                let query = {}
                if (this.filter_category !== 'All') query.category = this.filter_category
                this.$router.replace({ query }).catch(() => {})
            },

            on_filter_change(){
                this.updateUrl()
            },
        }
    }
</script>
