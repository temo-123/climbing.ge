<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h1 class='index_h2'>{{ $t('title mountaineering')}}</h1>

            <div class="bar"><i class="fa fa-dribbble"></i></div>

            <h2 style="text-align: center;">
                <span v-html="this.$siteData.mount_description"></span>
            </h2>

            <div class="row" v-if="this.mounts.length > 0">
                <div class="container articles_filter_bar">
                    <div class="col-md-6">
                        Select mount System and filtred routes
                    </div>
                    <div class="col-md-6" v-if="this.mounts.length > 0">
                        <select class="form-control" v-model="filter_mount" @click="get_mountain_route_articles()">
                            <option>All</option>
                            <option v-for="mount in mounts" :key='mount.global_mount.id' :value="mount.global_mount.id">{{ mount.locale_mount.title }}</option> 
                        </select>
                    </div>
                </div>
            </div>

            <div class="row articles_filter_bar" v-if="filter_mount != 'All'">
                <div class="col-md-12" style="text-align: center;">
                    <h4>{{selected_mount_data.locale_mount.title}}</h4>
                    <p>{{selected_mount_data.locale_mount.text}}</p>
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
                    <div v-if="this.mount_routes.length > 0">
                        <mountCard 
                            v-for="mount_route in mount_routes"
                            :key='mount_route.id'
                            :image_dir="'images/mount_route_img/'"
                            :mount="mount_route"
                            :route="'mountaineering/'+mount_route.url_title"
                        />
                    </div>
                    <div v-else>
                        <emptyPageComponent />
                    </div>
                </div>
            </div>

        </div>
        
        <metaData 
            :title = "'Mountaineering in Georgia'"
            :description = "'Mountaineering climbing routes and mount masiv in Georgia'"
            :image = "'../../../../public/images/meta_img/mount.jpg'"
        />
    </div>
</template>

<script>
    import mountCard from '../items/cards/MountCardComponent'
    import emptyPageComponent from '../../global_components/EmptyPageComponent'

    import { ContentLoader } from 'vue-content-loader'
    import metaData from '../items/MetaDataComponent'
    export default {
        data: function () {
            return {
                mounts: [],
                mount_routes: [],
                // filtred_mount_routes: [],
                filter_mount: 'All',

                selected_mount_data: [],
                mount_route_loading: true
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
                axios
                .get("../api/mount_route/get_filtred_mount_route_for_user/" + localStorage.getItem('lang') + '/' + id)
                .then(response => {
                    this.mount_routes = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.mount_route_loading = false)
            },

            get_unfilted_articles(){
                axios
                .get('../api/articles/mount_route/'+localStorage.getItem('lang'))
                .then(response => {
                    this.mount_routes = response.data
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

            // filter_mount_routes(){
            //     let vm = this;
            //     if (vm.filter_mount === 'All') {
            //         this.filtred_mount_routes = this.mount_routes
            //     }else{
            //         this.filtred_mount_routes = this.mount_routes.filter(function (item){
            //             return item.mount_id == vm.filter_mount
            //         })
            //     }
            // },

            // get_mount_routes(){
            //     axios
            //     .get('../api/articles/mount_route/'+localStorage.getItem('lang'))
            //     .then(response => {
            //         this.mount_routes = response.data,
            //         this.filter_mount_routes()
            //     })
            //     .catch(error =>{
            //     })
            // },

            get_mounts(){
                axios
                .get('../api/mounts/'+localStorage.getItem('lang'))
                .then(response => {
                    this.mounts = response.data
                })
                .catch(error =>{
                })
            },

            get_selected_mount_data(mount_masiv_id){
                console.log(this.filter_mount);
                if (this.filter_mount != 'all' || this.filter_mount != 'All') {
                    this.selected_mount_data = []
                    axios 
                    .get('../api/mount/'+localStorage.getItem('lang')+'/'+mount_masiv_id)
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
