<template>
    <div class="h-recent-work">
        <div class="container">
            <h1 class="page_title index_h2">{{ $t('title outdoor climbing')}}</h1>
                        
            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h6>
                <span v-html="this.$siteData.outdoor_description"></span>
            </h6>
            <div class="row" v-if="this.regions.length > 0">
                <div class="container mount_filters">
                    <div class="col-md-4">
                        Select region and filtred spots by region
                    </div>
                    <div class="col-md-offset-4 col-md-4" v-if="this.regions.length > 0">
                        <select class="form-control" v-model="filter_spot" @click="filter_spots_by_region()">
                            <option>All</option>
                            <option v-for="region in regions" :key='region.id' :value="region.id">{{ region.region_name_us }}</option> 
                        </select>
                    </div>
                </div>
            </div>
            <h6>
                In Georgia are <strong>50</strong> outdoor climbing sectors, <strong>250</strong> sport climbing routes, <strong>50</strong> boulder routes, <strong>20</strong> multy pitch. You can see all outdoor climbing arias info on this page.
            </h6>

            <section class="portfolio" id="portfolio">
                <div class="container-fluid">
                    <div class="row">
                        <div class="gallery_product filter ">

                            <div v-if="this.filtred_spots.length > 0">
                                <outdoorCard 
                                    v-for="outdoor in filtred_spots"
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
            </section>
        </div>
    </div>
</template>

<script>
    import outdoorCard from '../items/cards/OutdoorCardComponent'
    import emptyPageComponent from '../items/EmptyPageComponent'
    export default {
        data: function () {
            return {
                outdoors: [],
                regions: [],
                filtred_spots: [],
                filter_spot: 'All',
            };
        },
        components: {
            outdoorCard,
            emptyPageComponent,
        },
        mounted() {
            this.get_outdoor_articles()
            this.get_regions()
        },
        methods: {
            get_outdoor_articles(){
                axios
                .get('../api/outdoor')
                .then(response => {
                    this.outdoors = response.data
                    this.filter_spots_by_region()
                })
                .catch(error =>{
                })
            },

            get_regions(){
                axios
                .get('../api/region/')
                .then(response => {
                    this.regions = response.data
                })
                .catch(error =>{
                })
            },

            filter_spots_by_region(){
                let vm = this;
                if (vm.filter_spot === 'All') {
                    this.filtred_spots = this.outdoors
                }else{
                    this.filtred_spots = this.outdoors.filter(function (item){
                        return item.area.region_id == vm.filter_spot
                    })
                }
            },
        }
    }
</script>
