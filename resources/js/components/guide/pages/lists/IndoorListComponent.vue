<template>
    <div class="h-recent-work">
        <div class="container">

            <h1 class="index_h2" >{{ $t('guide.title.indoor climbing')}}</h1>                    

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            
            <h2 class="article_list_short_description">
                {{this.$siteData.data.indoor_description}}
            </h2>

            <div class="row" v-if="indoor_regions.length > 0">
                <div class="container articles_filter_bar">
                    <div class="col-md-6 col-sm-6">
                        {{ $t('guide.article.region_filtr') }}
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <select class="form-control" v-model="filter_region" @change="on_filter_change()">
                            <option value="All">{{ $t('all') }}</option>
                            <option v-for="region in indoor_regions" :key="region.id" :value="region.id">{{ region.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" v-if="filtered_indoors.length > 0">
                <div v-if="indoor_article_loading">
                <!--  -->
                </div>
                <div v-else>
                    <!-- <div> -->
                        <indoorCard 
                            v-for="indoor in filtered_indoors"
                            :key='indoor.id'
                            :indoor="indoor"
                        />
                    <!-- </div> -->
                </div>
            </div>
            <div v-else>
                <emptyPageComponent />
            </div>
        </div>
        
        <metaData 
            :title = "$t('guide.meta.indoor climbing')"
            :description = "this.$siteData.data.indoor_description"
            :image = "'/public/images/meta_images/indoor.jpg'"
        />
    </div>
</template>

<script>
    import indoorCard from '../../items/cards/IndoorCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    

    import metaData from '../../items/MetaDataComponent'

    import axios_mixin from '../../../../mixins/axios_mixin'

    export default {
        mixins: [
            axios_mixin
        ],
        data: function () {
            return {
                indoors: [],
                indoor_article_loading: true,
                regions: [],
                filter_region: this.$route.query.region ? Number(this.$route.query.region) : 'All',
            };
        },
        computed: {
            // Only regions that have at least one indoor gym
            indoor_regions() {
                const ids = new Set(this.indoors.flatMap(indoor => (indoor.global_data.outdoor_region || []).map(region => region.id)))
                return this.regions.filter(region => ids.has(region.id))
            },
            filtered_indoors() {
                if (this.filter_region === 'All') return this.indoors
                return this.indoors.filter(indoor => (indoor.global_data.outdoor_region || []).some(region => region.id === this.filter_region))
            },
        },
        components: {
            indoorCard,
            emptyPageComponent,

            metaData
        },
        watch: {
            '$route' (to, from) {
                // Region filter only changes the query — no refetch needed
                if (to.path === from.path) return
                this.get_indoors(),
                window.scrollTo(0,0)
            }
        },
        mounted() {
            this.get_indoors()
            this.get_regions()
        },
        methods: {
            get_regions() {
                axios
                .get('/get_region/get_local_regions/' + localStorage.getItem('lang'))
                .then(response => {
                    this.regions = response.data
                })
                .catch(error => console.log(error))
            },
            on_filter_change() {
                const query = { ...this.$route.query }
                if (this.filter_region === 'All') delete query.region
                else query.region = this.filter_region
                this.$router.replace({ query })
            },
            get_indoors(){
                this.get_articles('indoor', localStorage.getItem('lang'),
                    (data) => {
                        this.indoors = data;
                    },
                    (error) => {
                        console.error('Error fetching articles:', error);
                    },
                    () => {
                        this.indoor_article_loading = false;
                    }
                );
            }
        }
    }
</script>

<style>

</style>