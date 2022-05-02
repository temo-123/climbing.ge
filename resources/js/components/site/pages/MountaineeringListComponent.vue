<template>
    <div class="h-recent-work" id="other">
        <div class="container">

            <h2 class='index_h2'>{{ $t('title mountaineering')}}</h2>

            <div class="bar"><i class="fa fa-dribbble"></i></div>
            <h6> <span v-html="this.$siteData.mount_description"></span></h6>

            <div class="row" v-if="this.mounts.length > 0">
                <div class="container mount_filters">
                    <div class="col-md-4">
                        Select mount System and filtred routes
                    </div>
                    <div class="col-md-offset-4 col-md-4" v-if="this.mounts.length > 0">
                        <select class="form-control" v-model="filter_mount" @click="filter_mount_routes()">
                            <option>All</option>
                            <option v-for="mount in mounts" :key='mount.id' :value="mount.id">{{ mount.name }}</option> 
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div v-if="this.filtred_mount_routes.length > 0">
                    <mountCard 
                        v-for="mount_route in filtred_mount_routes"
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
</template>

<script>
    import mountCard from '../items/cards/MountCardComponent'
    import emptyPageComponent from '../items/EmptyPageComponent'
    export default {
        data: function () {
            return {
                mounts: [],
                mount_routes: [],
                filtred_mount_routes: [],
                filter_mount: 'All',
            };
        },
        components: {
            mountCard,
            emptyPageComponent,
        },
        mounted() {
            this.get_mounts(),
            this.get_mount_routes()
            // this.filter_mount_routes()
        },
        methods: {
            get_mounts(){
                axios
                .get('../api/mount')
                .then(response => {
                    this.mounts = response.data
                })
                .catch(error =>{
                })
            },

            filter_mount_routes(){
                let vm = this;
                if (vm.filter_mount == 'All') {
                    this.filtred_mount_routes = this.mount_routes
                }else{
                    this.filtred_mount_routes = this.mount_routes.filter(function (item){
                        return item.mount_id == vm.filter_mount
                    })
                }
            },

            get_mount_routes(){
                axios
                .get('../api/mount_route')
                .then(response => {
                    this.mount_routes = response.data,
                    this.filter_mount_routes()
                })
                .catch(error =>{
                })
            }
        }
    }
</script>

<style>
.mount_filters{
    background-color: rgb(226, 226, 226);
    border-radius: 50px;
    margin-bottom: 2%;
}
</style>