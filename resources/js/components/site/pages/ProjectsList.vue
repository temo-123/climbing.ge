<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.spots projects')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            
            <h2 class="article_list_short_description">
                {{this.$siteData.project_description}}
            </h2>

            <div v-if="indoor_article_loading">
                <content-loader
                    viewBox="0 0"
                    primaryColor="#f3f3f3"
                    secondaryColor="#279fbbb0"
                />
            </div>
            <div v-else>
                <div class="article_card_container" v-if="this.projects.length > 0">
                    <articleComponent 
                        v-for="project in projects"
                        :key='project.id'
                        :image_dir="'images/project_img/'"
                        :article="project"
                        :route="'spot_project/'+project.global_data.url_title"
                    />
                </div>
                <div v-else>
                    <emptyPageComponent />
                </div>
            </div>
        </div>
        
        <metaData 
            :title = "$t('guide.meta.spots projects')"
            :description = "this.$siteData.project_description"
            :image = "'/public/images/meta_img/project.jpg'"
        />
    </div>
</template>

<script>
    import articleComponent from '../items/cards/ArticleCardComponent'
    // import emptyPageComponent from '../items/EmptyPageComponent'
    import emptyPageComponent from '../../global_components/EmptyPageComponent'
    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../items/MetaDataComponent'

    export default {
        data: function () {
            return {
                projects: [],
                indoor_article_loading: true
            };
        },
        components: {
            articleComponent,
            emptyPageComponent,
            ContentLoader,
            metaData
        },
        mounted() {
            this.get_projects()
        },
        watch: {
            '$route' (to, from) {
                this.get_projects(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_projects(){
                axios
                .get('/articles/spot_projects/'+localStorage.getItem('lang'))
                .then(response => {
                    this.projects = response.data
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