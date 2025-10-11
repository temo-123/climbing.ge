<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h1 class='index_h2'>{{ $t('guide.title.mountaineering')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>

            <h2 class="article_list_short_description">
                {{this.$siteData.mount_description}}
            </h2>

            <div class="row" v-if="this.mounts.length > 0">
                <div class="container articles_filter_bar">
                    <div class="col-md-6">
                        <!-- Select mount System and filtred routes -->
                        {{ $t('guide.article.mount_route_filtr') }}
                    </div>
                    <div class="col-md-6" v-if="this.mounts.length > 0">
                        <select class="form-control" v-model="filter_mount" @click="get_mountain_route_articles()">
                            <option value="All">{{ $t('all') }}</option>
                            <option v-for="mount in mounts" :key='mount.global_data.id' :value="mount.global_data.id">{{ mount.locale_data.title }}</option> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="row articles_filter_bar" v-if="filter_mount != 'All'">
                <div class="col-md-12" style="text-align: center;">
                    <h4>{{selected_mount_data.locale_data.title}}</h4>
                    <span v-html="selected_mount_data.locale_data.text"></span>
                </div>
            </div>
            
            <div class="row">
                <div v-if="mount_route_loading">
                    <content-loader
                        viewBox="0 0"
                        primaryColor="#f3f3f3"
                        secondaryColor="#279fbbb0"
                    >

                    </content-loader>
                </div>
                <div v-else>
                    <div v-if="this.mount_routes_by_masiv.length > 0" class="article_card_container">
                        <div class="row width_100" v-for="masiv in mount_routes_by_masiv">
                            <div class="col-md-12">
                                <h2 v-if="masiv.mount" class="article_list_short_description">{{masiv.mount.locale_data.title}}</h2>
                                <h2 v-else class="article_list_short_description">Other</h2>
                            </div>
                            <div class="col-md-12 cards_block">
                                <mountCard 
                                    v-for="mount_route in masiv.mount_route"
                                    :key='mount_route.global_data.id'
                                    :mount="mount_route"
                                    :route="'mountaineering/'+mount_route.global_data.url_title"
                                />
                            </div>
                        </div>
                    </div>
                    <div v-else-if="this.mount_routes.length > 0" class="article_card_container">
                        <mountCard 
                            v-for="mount_route in mount_routes"
                            :key='mount_route.id'
                            :mount="mount_route"
                            :route="'mountaineering/'+mount_route.global_data.url_title"
                        />
                    </div>
                    <div v-else>
                        <emptyPageComponent />
                    </div>
                </div>
            </div>

        </div>
        
        <metaData 
            :title = "$t('guide.meta.mountaineering')"
            :description = "this.$siteData.mount_description"
            :image = "'/public/images/meta_img/mount.jpg'"
        />
    </div>
</template>

<script>
    import mountCard from '../../items/cards/MountCardComponent'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'

    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../../items/MetaDataComponent'
    export default {
        data: function () {
            return {
                mounts: [],
                mount_routes: [],
                mount_routes_by_masiv: [],
                // filtred_mount_routes: [],
                filter_mount: 'All',

                selected_mount_data: [],
                mount_route_loading: false
            };
        },
        components: {
            mountCard,
            emptyPageComponent,
            ContentLoader,
            metaData
        },
        mounted() {
            this.get_mounts(),
            this.get_mountain_route_articles()
        },

        watch: {
            '$route' (to, from) {
                this.get_mounts(),
                this.get_mountain_route_articles()
                window.scrollTo(0,0)
            }
        },
        
        methods: {
            get_filtred_articles(id){
                this.mount_route_loading = true
                this.mount_routes_by_masiv = []
                axios
                // .get("/mount_route/get_filtred_mount_route_for_user/" + localStorage.getItem('lang') + '/' + id)
                .get("/mount_route/get_filtred_mount_routes/" + localStorage.getItem('lang') + '/' + id + '/' + 1)
                .then(response => {
                    this.mount_routes = response.data
                })
                .catch(
                    // error => console.log(error)
                )
                .finally(() => this.mount_route_loading = false)
            },

            get_unfilted_articles(){
                this.mount_route_loading = true
                this.mount_routes = []
                axios
                .get('/mount_route/get_mount_routes_by_maunt/'+localStorage.getItem('lang'))
                .then(response => {
                    this.mount_routes_by_masiv = response.data
                    // this.filter_mount_routes()
                })
                .catch(error =>{
                })
                .finally(() => this.mount_route_loading = false)
            },

            get_mountain_route_articles(){
                if (this.filter_mount === 'all' || this.filter_mount === 'All') {
                    this.get_unfilted_articles()
                }
                else{
                    this.get_filtred_articles(this.filter_mount) 
                    this.get_selected_mount_data(this.filter_mount)
                }
            },

            get_mounts(){
                axios
                .get('/mounts/'+localStorage.getItem('lang'))
                .then(response => {
                    this.mounts = response.data
                })
                .catch(error =>{
                })
            },

            get_selected_mount_data(mount_masiv_id){
                if (this.filter_mount != 'all' || this.filter_mount != 'All') {
                    this.selected_mount_data = []
                    axios 
                    .get('/mount/'+localStorage.getItem('lang')+'/'+mount_masiv_id)
                    .then(response => {
                        this.selected_mount_data = response.data[0]
                    })
                    .catch(error =>{
                    })
                }
                else{
                    this.selected_mount_data = []
                }
            },
        }
    }
</script>
