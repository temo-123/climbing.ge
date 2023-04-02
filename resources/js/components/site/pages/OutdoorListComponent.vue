<template>
    <div class="h-recent-work">
        <div class="container">

            <h1 class="page_title index_h2">{{ $t('guide.title.outdoor climbing')}}</h1>
                        
            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h2 class="article_list_short_descriptio">
                {{this.$siteData.outdoor_description}}
            </h2>
            
            <sectorQuantyt />

            <div v-if="region_loading">
                <content-loader
                    :width="100"
                    :height="5"
                    primaryColor="#f3f3f3"
                    secondaryColor="#279fbbb0"
                >
                    <rect x="0" y="0" rx="2" ry="2" width="100%" height="3" />
                </content-loader>
            </div>

            <div v-else>
                <div class="row" v-if="this.regions.length > 0">
                    <div class="container articles_filter_bar">
                        <div class="col-md-6 col-sm-6">
                            Select region and filtred spots by region
                        </div>
                        <div class="col-md-6 col-sm-6" v-if="this.regions.length > 0">
                            <select class="form-control" v-model="filter_spot" @click="get_outdoor_articles()">
                                <option>All</option>
                                <option v-for="region in regions" :key='region.id' :value="region.id">{{ region.name }}</option> 
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row articles_filter_bar" v-if="filter_spot != 'All'">
                <div class="col-md-12" style="text-align: center;">
                    <h4>{{selected_region_data.name}}</h4>
                    <!-- <p>{{selected_region_data.text}}</p> -->
                    <span v-html="selected_region_data.text"></span>
                </div>

                <div class="col-md-12" style="text-align: center;" v-if="selected_region_data.map != null">
                    <button class="btn btn-default btn-send main-btn" @click="map_modal()">Show map</button>
                </div>
            </div>

            <section class="portfolio" id="portfolio">
                <div class="container-fluid">
                    <div class="row">
                        <div class="gallery_product filter ">

                            <div v-if="oudoor_loading">
                                <content-loader
                                    viewBox="0 0"
                                    primaryColor="#f3f3f3"
                                    secondaryColor="#279fbbb0"
                                >

                                </content-loader>
                            </div>

                            <div v-else>
                                <div v-if="this.outdoors.length > 0">
                                    <outdoorCard 
                                        v-for="outdoor in outdoors"
                                        :key='outdoor.id'
                                        :image_dir="'images/outdoor_img/'"
                                        :article="outdoor"
                                    />
                                </div>
                                <div v-else>
                                    <emptyPageComponent />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <stack-modal
                    :show="show_map_modal"
                    :title="selected_region_data.name + 'map'"
                    @close="show_map_modal=false"
                    :modal-class="''"
                    :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                    :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
                >
                <div class="model-body">
                    <div class="container">
                        <div class="row">
                            <!-- <h2>{{ selected_region_data.map }}</h2> -->
                            <span v-html="selected_region_data.map"></span>
                        </div>
                    </div>
                </div>
                <div slot="modal-footer">
                    <div class="modal-footer">
                        <!-- footer -->
                    </div>
                </div>
            </stack-modal>
        </div>

        <metaData 
            :title = "$t('guide.meta.outdoor climbing')"
            :description = "this.$siteData.outdoor_description"
            :image = "'../../../../public/images/meta_img/outdoor.jpg'"
        />
    </div>
</template>

<script>
    import outdoorCard from '../items/cards/OutdoorCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../global_components/EmptyPageComponent'
    import sectorQuantyt from '../items/SectorsQuantytyComponent'
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import metaData from '../items/MetaDataComponent'
    import { ContentLoader } from 'vue-content-loader'

    export default {
        data: function () {
            return {
                outdoors: [],
                regions: [],
                // filtred_spots: [],
                filter_spot: 'All',

                show_map_modal: false,
                selected_region_data: [],

                oudoor_loading: true,
                // sector_quantyt: true,
                region_loading: true,
            };
        },
        components: {
            outdoorCard,
            emptyPageComponent,
            sectorQuantyt,
            StackModal,
            metaData,
            ContentLoader
        },
        mounted() {
            this.get_outdoor_articles()
            this.get_regions()

            if(location.hash != ''){
                this.create_url(location.hash)
            }
        },

        watch: {
            '$route' (to, from) {
                this.get_outdoor_articles()
                this.get_regions()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_filtred_articles(id){
                axios
                .get("../api/outdoor/get_filtred_outdoor_spots_for_gest/" + localStorage.getItem('lang') + '/' + id)
                .then(response => {
                    this.outdoors = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.article_loading = false);
            },

            get_unfilted_articles(){
                axios
                .get('../api/articles/outdoor/'+localStorage.getItem('lang'))
                .then(response => {
                    this.outdoors = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.oudoor_loading = false)
            },

            get_outdoor_articles(){
                if (this.filter_spot === 'all' || this.filter_spot === 'All') {
                    this.get_unfilted_articles()

                    this.delete_url_hash()
                }
                else{
                    this.get_filtred_articles(this.filter_spot) 
                    this.get_region_selected_data(this.filter_spot)
                }
            },

            delete_url_hash(){
                // https://gist.github.com/azu/36ba5a80feb857c77a3a
                var noHashURL = location.href.replace(/#.*$/, '');
                history.replaceState('', document.title, noHashURL) 
            },

            create_url_hash (category) {	
                // https://www.tutorialsplane.com/vue-js-set-hash-url/	
                location.hash = "#" + category;
            },

            get_regions(){
                axios
                .get('../api/regions/'+localStorage.getItem('lang'))
                .then(response => {
                    this.regions = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.region_loading = false)
            },

            map_modal(){
                // this.selected_region_data = []
                this.show_map_modal = true
                // this.get_region_selected_data(region)
            },

            get_region_selected_data(region_id){
                if (this.filter_spot != 'all' || this.filter_spot != 'All') {
                    this.selected_region_data = []
                    axios
                    .get('../api/region/'+localStorage.getItem('lang')+'/'+region_id)
                    .then(response => {
                        this.selected_region_data = response.data[0]

                        this.create_url_hash(this.selected_region_data.name)
                    })
                    .catch(error =>{
                    })
                }
                else{
                    this.selected_region_data = []
                }
            }
        }
    }
</script>

<style scoped>
    .modal-dialog {
        width: 100% !important;
    }
    @media screen and (min-width: 768px){
        .modal-dialog {
            width: 100% !important;
        }
    }
    .modal .fade .modal-dialog {
        width: 100% !important;
    }
</style>
