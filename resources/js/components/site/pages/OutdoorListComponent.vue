<template>
    <div class="h-recent-work">
        <div class="container">
            <h1 class="page_title index_h2">{{__ ('title outdoor climbing')}}</h1>
                        
            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h6><span v-html="this.$siteData.outdoor_description"></span></h6>

            <section class="portfolio" id="portfolio">
                <div class="container-fluid">
                    <div class="row">
                        <div class="gallery_product filter ">

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
                outdoors: []
            };
        },
        components: {
            outdoorCard,
            emptyPageComponent,
        },
        mounted() {
            this.get_outdoor_articles()
        },
        methods: {
            get_outdoor_articles(){
                axios
                .get('../api/outdoor')
                .then(response => {
                    this.outdoors = response.data
                })
                .catch(error =>{
                })
            }
        }
    }
</script>
