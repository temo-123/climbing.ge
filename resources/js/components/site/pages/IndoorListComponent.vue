<template>
    <div class="h-recent-work">
        <div class="container">

            <h1 class="page_title index_h2" >{{ $t('guide.title.indoor climbing')}}</h1>                    

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <!-- <h6> <span v-html="this.$siteData.indoor_description"></span></h6> -->
            <h2 style="text-align: center;">
                <span v-html="this.$siteData.indoor_description"></span>
            </h2>

            <div class="row featurette" style='margin-bottom: 7%' v-if="this.indoors.length > 0">
                <div v-if="indoor_article_loading">
                    <content-loader
                        viewBox="0 0"
                        primaryColor="#f3f3f3"
                        secondaryColor="#279fbbb0"
                    />
                </div>
                <div v-else>
                    <div>
                        <indoorCard 
                            v-for="indoor in indoors"
                            :key='indoor.id'
                            :indoor="indoor"
                        />
                    </div>
                </div>
            </div>
            <div v-else>
                <emptyPageComponent />
            </div>
        </div>
        
        <metaData 
            :title = "$t('guide.title.indoor climbing')"
            :description = "this.$siteData.indoor_description"
            :image = "'../../../../public/images/meta_images/indoor.jpg'"
        />
    </div>
</template>

<script>
    import indoorCard from '../items/cards/IndoorCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../global_components/EmptyPageComponent'
    
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../items/MetaDataComponent'
    export default {
        data: function () {
            return {
                indoors: [],
                indoor_article_loading: true
            };
        },
        components: {
            indoorCard,
            emptyPageComponent,
            ContentLoader,
            metaData
        },
        watch: {
            '$route' (to, from) {
                this.get_indoors(),
                window.scrollTo(0,0)
            }
        },
        mounted() {
            this.get_indoors()
        },
        methods: {
            get_indoors(){
                axios
                .get('../api/articles/indoor/'+localStorage.getItem('lang'))
                .then(response => {
                    this.indoors = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.indoor_article_loading = false)
            }
        }
    }
</script>

<style>

</style>