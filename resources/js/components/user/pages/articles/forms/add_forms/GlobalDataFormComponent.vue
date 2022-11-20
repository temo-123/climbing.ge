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
                            <select class="form-control" v-model="data.region" name="region" > 
                                <option :value="'select_region'" disabled>Select region</option> 
                                <option  v-for="region in regions" :key="region.id" :value="region.id">{{ region.us_name }}</option>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> Mountain </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.mount_id" name="mount_id"> 
                                <option :value="'select_mount'" disabled>Select mount</option> 
                                <option v-for="mount in mount_data" :key="mount.id" v-bind:value="mount.id">{{mount.name}}</option> 
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'event'">
                        <label for="name" class='col-md-2 control-label'> Completed </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.completed" name="completed"> 
                                <option value="0">No complited</option> 
                                <option value="1">Complited</option> 
                            </select> 
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
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
                            <input type="text" name="open_timen" v-model="data.open_timen" class="form-control"> 
                        </div>
                    </div>
                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Working time </label>
                        <div class="col-md-10">
                            <input type="text" name="closed_time" v-model="data.closed_time" class="form-control"> 
                        </div>
                    </div>


                    <hr v-if="this.category == 'event'">

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Start_data </label>
                        <div class="col-md-10">
                            <input type="text" name="start_data" v-model="data.start_data" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> End data </label>
                        <div class="col-md-10">
                            <input type="text" name="end_data" v-model="data.end_data" class="form-control"> 
                        </div>
                    </div>

                    <hr v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">

                    <div class="form-group clearfix row"  v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> facebook / twitter </label>
                        <div class="col-md-4">
                            <input type="text" v-model="data.fb_link" name="fb_link" class="form-control"> 
                        </div>
                        <div class="col-md-4">
                            <input type="text" v-model="data.twit_link" name="twit_link" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> google / instagram </label>
                        <div class="col-md-4">
                            <input type="text" v-model="data.google_link" name="google_link" class="form-control"> 
                        </div>
                        <div class="col-md-4">
                            <input type="text" v-model="data.inst_link" name="inst_link" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> website </label>
                        <div class="col-md-10">
                            <!-- <input type="text" v-model="data.name" name="value name" value="old data" class="form-control"> -->
                            <input type="text" v-model="data.web_link" name="web_link" class="form-control"> 
                        </div>
                    </div>
                </form>

                <!-- <form @submit="upload_mount_route_image" ref="mountRouteImage" v-if="this.category == 'mount_route'">
                    <div class="form-group clearfix row">
                        <label for="email" class='col-md-5 control-label'>Upload mount route image:</label>
                        <div class="col-md-6">
                            <input type="file" name="mount_route_img" id="mount_route_img">
                        </div>
                    </div>
                </form> -->

                <!-- <form @submit="checkForm" ref="myForm">
                    <div class="form-group clearfix row">
                        <label for="email" class='col-md-5 control-label'>Upload article image:</label>
                        <div class="col-md-6">
                            <input type="file" name="image" id="image">
                        </div>
                    </div>
                </form> -->

                

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

                data: {
                    category: this.$route.params.article_category,
                    us_title_for_url_title: "",
                    published: 0,
                    completed: "",
                    map: "",
                    weather: "",
                    open_timen: "",
                    closed_time: "",
                    price_from: "",
                    start_data: "",
                    end_data: "",
                    fb_link: "",
                    twit_link: "",
                    google_link: "",
                    inst_link: "",
                    web_link: "",

                    region: "select_region",
                    mount_id: "select_mount",
                },
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
                    .get("../../../api/mountaineering/get_mount_data/")
                    .then(response => {
                        this.mount_data = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            get_regions(category){
                if(category == 'outdoor'){
                    axios
                    .get("../../../api/region/")
                    .then(response => {
                        this.regions = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            // checkForm: function () {
            //     var myFormData = new FormData(this.$refs.myForm)
            //     axios({
            //         method: 'post',
            //         url: '../../api/articles/upload_image/',
            //         data: myFormData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
                    
            //     });
            // },

            // upload_mount_route_image: function () {
            //     var myMountRouteImageData = new FormData(this.$refs.mountRouteImage)
            //     axios({
            //         method: 'post',
            //         url: '/articles/mount_route_image_upload/',
            //         data: myMountRouteImageData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
                    
            //     });
            // },
        }
    }
</script>

<style>

</style>