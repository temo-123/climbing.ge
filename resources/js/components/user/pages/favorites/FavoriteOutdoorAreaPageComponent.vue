<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->
        
        <div class="col-sm-12">

            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="row font-italic">
                <div class="col-md-6">
                    <h3 class="mt-3 pb-3 mb-4 ">
                        Faworite climbing spots
                    </h3>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary float-right" @click="go_to_spots_list('/outdoor')">
                        Go to outdoor spot list
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-success float-right" @click="get_faworite_outdoor_region()">
                        Refresh
                    </button>
                </div>
            </div>
            <div class="row"  v-if="spots.length">

                <div class="col-md-4" v-for="spot in spots" :key="spot.id">
                    <div class="card">

                        <span @click="go_to_spots_list('/outdoor/'+spot.url_title)" >
                            <site-img v-if="spot.image != NULL" :src="'/public/images/outdoor_img/'+spot.image" :img_class="'card-img-top'" :alt='spot[0][0].title'/>
                            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'card-img-top'" :alt='spot[0][0].title'/>
                        </span>

                        <div class="card-body">
                            <a @click="go_to_spots_list('/outdoor/'+spot.url_title)"> 
                                <h5 class="card-title border-bottom pb-3">
                                    {{ spot[0][0].title }}
                                </h5> 
                            </a>

                            <a @click="del_from_faworites(spot.id)" class="btn btn-danger">X <i class="fas fa-angle-double-right"></i></a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row" v-else>
                <h2>You dont have faworite climbing spots</h2>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            breadcrumb,
        },
        data: function () {
            return {
                spots: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        mounted() {
            this.get_faworite_outdoor_region()
        },
        methods: {
            get_faworite_outdoor_region(){
                axios
                .get('../api/outdoor/get_faworite_outdoor_region')
                .then(response => {
                    this.spots = response.data
                })
                .catch(error =>{
                })
            },

            go_to_spots_list(page){
                window.open(this.MIX_APP_SSH + '' + this.MIX_SITE_URL + page)
            },

            del_from_faworites(id){
                if(confirm('Are you sure, you want delite this area from your faworites?')){
                    axios
                    .post('../../api/outdoor/del_faworite_outdoor_region/'+id, {
                        article_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_faworite_outdoor_region()
                        // alert("Area delited from your favorite list!");
                    })
                    .catch(error => {
                        alert("Error");
                    })
                }
            }
        }
    }
</script>

<style scoped>
</style>