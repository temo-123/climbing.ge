<template>
    <div class="col-md-12" >
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">{{this.category}}</span> article global information</h2>
                        <p class="lead">Article global information.</p>
                    </div>
                </div>
                <form method="POST" @submit.prevent="add_article">
                    <div class="form-group clearfix row">
                        <label for="published" class='col-md-2 control-label '> Publish </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.published" name="published" > 
                                <option value="0">Not public</option> 
                                <option value="1">Public</option> 
                            </select> 
                            <div class="alert alert-danger" role="alert" v-if="error.published">
                                {{ error.published[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                        <label for="region" class='col-md-2 control-label '> Regions </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="region_id" name="region" > 
                                <option :value="'select_region'" disabled>Select region</option> 
                                <option :value="null" style="color:red">Whithout Region</option> 
                                <option  v-for="region in regions" :key="region.id" :value="region.id">{{ region.us_name }}</option>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> Mountain </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="mount_id" name="mount_id"> 
                                <option :value="'select_mount'" disabled>Select mount</option> 
                                <option :value="null" style="color:red">Whithout Mount</option> 
                                <option v-for="mount in mount_masive" :key="mount.id" :value="mount.global_data.id">{{mount.global_data.name}}</option> 
                            </select> 
                        </div>
                    </div>

                    <!-- <div class="form-group clearfix row" v-if="this.category == 'event'">
                        <label for="name" class='col-md-2 control-label'> Completed </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.completed" name="completed"> 
                                <option value="0">No complited</option> 
                                <option value="1">Complited</option> 
                            </select> 
                        </div>
                    </div> -->


                    <div class="form-group clearfix row" v-if="this.category != 'mount_route' && category != 'partners'">
                        <label for="name" class='col-md-2 control-label'> Map </label>
                        <div class="col-md-10">
                            <input type="text" v-model="data.map" name="map" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix row"  v-if="this.category == 'outdoor' || this.category == 'ice'">
                        <label for="name" class='col-md-2 control-label'> Weather </label>
                        <div class="col-md-10">
                            <input type="text" v-model="data.weather" name="weather" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Minimal price </label>
                        <div class="col-md-10">
                            <input type="text" name="price_from"  v-model="data.price_from" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Working time </label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="time" name="open_time" class="form-control" v-model="data.open_time" placeholder="Start data/time"> 
                                </div>
                                <div class="col-md-6">
                                    <input type="time" name="closed_time" class="form-control" v-model="data.closed_time" placeholder="End data/time"> 
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        props: [
            // 'back_url',
            // 'category'
        ],
        data(){
            return {
                category: this.$route.params.article_category,

                editorConfig: {
                    // toolbar: [ [ 'Bold' ] ]
                },

                error: [],
                
                regions: [],
                mount_masive: [],

                data: {
                    category: this.$route.params.article_category,
                    // us_title_for_url_title: "",
                    published: 0,
                    // completed: "",
                    // map: "",
                    // weather: "",
                    // open_timen: "",
                    // closed_time: "",
                    // price_from: "",
                    // start_data: "",
                    // end_data: "",
                    // fb_link: "",
                    // twit_link: "",
                    // google_link: "",
                    // inst_link: "",
                    // web_link: "",

                    // region_id: "select_region",
                    // mount_id: "select_mount",
                },
                region_id: "select_region",
                mount_id: "select_mount",
            }
        },
        mounted() {
            if (this.category == 'outdoor') {
                this.get_regions('outdoor')
            }
            if (this.category == 'mount_route') {
                this.get_mount_massive_data('mount_route')
            }

            this.$emit('global_form_data', this.data)
        },
        methods: {
            get_mount_massive_data: function(category){
                if(category == 'mount_route'){
                    axios
                    .get("/mount/mount/")
                    .then(response => {
                        this.mount_masive = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            get_regions(category){
                if(category == 'outdoor'){
                    axios
                    .get("/outdoor/region/")
                    .then(response => {
                        this.regions = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
        }
    }
</script>

<style>

</style>