<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.ice climbing')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            
            <h2 class="article_list_short_description">
                {{this.$siteData.data.ice_description}}
            </h2>

            <sectorQuantyt :route_categories_prop="route_categories"/>

            <div class="row otdoor_buttoms">
                <div class="col-md-6">
                    <routesAutersModal :route_categories_prop="route_categories"/>
                </div>

                <div class="col-md-6">
                    <mostPopularRoutesModal :route_categories_prop="route_categories" :default_route_type_prop="'ice'"/>
                </div>
            </div>

            <!-- View Controls -->
            <div class="row view_controls_bar">
                <div class="col-md-12 text-left pull-left">
                    <div class="btn-group" role="group" aria-label="View Mode">
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': viewMode === 'grid', 'btn-default': viewMode !== 'grid'}"
                            @click="viewMode = 'grid'"
                        >
                            <i class="fa fa-th-large"></i> {{ $t('guide.view.grid') || 'Grid' }}
                        </button>
                        <button 
                            type="button" 
                            class="btn" 
                            :class="{'btn-primary active': viewMode === 'list', 'btn-default': viewMode !== 'list'}"
                            @click="viewMode = 'list'"
                        >
                            <i class="fa fa-list-ul"></i> {{ $t('guide.view.list') || 'List' }}
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="indoor_article_loading">
                <!--  -->
            </div>
            <div v-else>
                <div v-if="this.ices.length > 0" class="article_card_container" :class="{'list-view': viewMode === 'list'}">
                    <!-- Grid View -->
                    <template v-if="viewMode === 'grid'">
                        <articleComponent 
                            v-for="ice in ices"
                            :key='ice.id'
                            :image_dir="'images/ice_img/'"
                            :article="ice"
                            :route="'ice/'+ice.global_data.url_title"
                        />
                    </template>
                    <!-- List View -->
                    <template v-else>
                        <articleHorithontalCardComponent
                            v-for="ice in ices"
                            :key="ice.id"
                            :image_dir="'images/ice_img/'"
                            :article="ice"
                            :route="'ice/'+ice.global_data.url_title"
                        />
                    </template>
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div>
        
        <metaData 
            :title = "$t('guide.meta.ice climbing')"
            :description = "this.$siteData.data.ice_description"
            :image = "'/public/images/meta_img/ice.jpg'"
        />
    </div>
</template>

<script>
    import articleComponent from '../../items/cards/ArticleCardComponent'
    import articleHorithontalCardComponent from '../../items/cards/ArticleHorithontalCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'

    import metaData from '../../items/MetaDataComponent'

    import axios_mixin from '../../../../mixins/axios_mixin'

    import sectorQuantyt from '../../items/climbing_routes/SectorsQuantytyComponent'
    import routesAutersModal from '../../items/climbing_routes/items/modals/RoutesAutersListModal.vue'
    import mostPopularRoutesModal from '../../items/climbing_routes/items/modals/MostPopularRoutesModal.vue'

    export default {
        mixins: [
            axios_mixin
        ],
        data: function () {
            return {
                ices: [],
                indoor_article_loading: true,

                viewMode: 'grid', // 'grid' or 'list'

                route_categories: [
                    { value: 'dry', label: 'guide.sector.dry' },
                    { value: 'ice', label: 'guide.sector.ice' }
                ]
            };
        },
        components: {
            articleComponent,
            articleHorithontalCardComponent,
            emptyPageComponent,

            metaData,
            
            sectorQuantyt,
            routesAutersModal,
            mostPopularRoutesModal
        },
        mounted() {
            this.get_ices()
        },
        watch: {
            '$route' (to, from) {
                this.get_ices(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_ices(){
                this.get_articles('ice', localStorage.getItem('lang'),
                    (data) => {
                        this.ices = data;
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
    .otdoor_buttoms{
        margin: 1em 0;
    }
</style>