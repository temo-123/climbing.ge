<template>
    <div class="colm-md-12">
    <div class="row">
     <div class="col-md-12" >
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h2 class="display-4">Edit article global information</h2>
                                <p class="lead">Edit article global information.</p>
                            </div>
                        </div>
                        <form @submit.prevent="edit_article"  style="margin-top: 5%;">

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Publish </label>
                                <div class="col-md-10">
                                    <select class="form-control" name="published" v-model="global_data.published"> 
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option> 
                                    </select> 
                                    <!-- <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                        {{ global_article_error.published[0] }}
                                    </div> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                                <label for="name" class='col-md-2 control-label'> Mountain </label>
                                <div class="col-md-10">
                                    <select class="form-control" v-model="global_data.mount_id" name="mount_id"> 
                                        <option  v-for="mount in mount_data" :key="mount.id" v-bind:value="mount.id">{{mount.name}}</option> 
                                    </select> 
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'event'">
                                <label for="name" class='col-md-2 control-label'> completed </label>
                                <div class="col-md-10">
                                    <select class="form-control" name="completed" v-model="global_data.completed"> 
                                        
                                        <!-- <option value="0" @if ($data['published'] == 0) selected="" @endif>Not public</option> -->
                                        <!-- <option value="1" @if ($data['published'] == 1) selected="" @endif>Public</option> -->
                                    
                                        <option value="0">No complited</option> 
                                        <option value="1">Complited</option> 
                                        
                                    </select> 
                                </div>
                            </div>


                            <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                <label for="name" class='col-md-2 control-label'> Map </label>
                                <div class="col-md-10">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <!-- <input type="text" name="map"  v-model="global_data.map" :value="editing_data.global_article['map']" class="form-control">  -->
                                    <input type="text" name="map"  v-model="global_data.map" class="form-control"> 
                                </div>
                            </div>


                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                <label for="name" class='col-md-2 control-label'> Weather </label>
                                <div class="col-md-10">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="weather" v-model="global_data.weather" class="form-control"> 
                                </div>
                            </div>

                            <hr>

                            <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> Minimal price </label>
                                <div class="col-md-10">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="price_from" v-model="global_data.price_from" class="form-control"> 
                                    <!-- <ckeditor :editor="editor" v-model="global_data.us_price_from" :config="editorConfig"></ckeditor> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> Working time </label>
                                <div class="col-md-10">
                                    <input type="text" name="time" v-model="global_data.working_time" class="form-control"> 
                                </div>
                            </div>

                            <hr v-if="this.category == 'event'">

                            <!-- <div class="form-group clearfix row" v-if="this.category == 'event'">
                                <label for="name" class='col-md-2 control-label'> Start data </label>
                                <div class="col-md-4">
                                    <input type="text" name="start_data_day" v-model="global_data.start_data_day"  class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="global_data.start_data_month" name="start_data_month"> 
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select> 
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'event'">
                                <label for="name" class='col-md-2 control-label'> End data </label>
                                <div class="col-md-4">
                                    <input type="text" name="end_data_day" v-model="global_data.end_data_day"  class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="global_data.end_data_month" name="end_data_month"> 
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select> 
                                </div>
                            </div> -->

                            <hr v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">

                            <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> facebook / twitter </label>
                                <div class="col-md-4">
                                    <input type="text" name="fb_link"  v-model="global_data.fb_link" class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="twit_link"  v-model="global_data.twit_link" class="form-control"> 
                                </div>
                            </div>
                            <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> google / instagram </label>
                                <div class="col-md-4">
                                    <input type="text" name="google_link"  v-model="global_data.google_link" class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="inst_link"  v-model="global_data.inst_link" class="form-control"> 
                                </div>
                            </div>
                            <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> website </label>
                                <div class="col-md-10">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="web_link" v-model="global_data.web_link" class="form-control"> 
                                </div>
                            </div>

                        </form>

                        <!-- <form @submit="upload_region_image" ref="sectorRegionImage" v-if="this.category == 'outdoor'">
                            <div class="form-group clearfix">
                                <div class="row">
                                    <label for="email" class='col-md-5 control-label'>Update outdoor climbing area sector image:</label>
                                    <div class="col-md-4">
                                        <input type="file" name="region_sectors_img" id="region_sectors_img">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <img :src="'/public/images/region_sectors_img/'+this.region_sectors_image_name" alt="article image">
                                    </div>
                                </div>
                            </div>
                        </form> -->

                        

                        <!-- <form @submit="checkForm" ref="myForm">
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <label for="name" class='col-md-2 control-label'>Update article image:</label>
                                        <div class="col-md-4">     
                                            <input type="file" name="profile_pic" id="profile_pic">               
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img :src="'/public/images/' + category + '_img/'+this.image_name" alt="article image">
                                        </div>
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
            'global_data',
            'category'
        ],
        data(){
            return {
                // category: this.$route.params.article_category,

                editorConfig: {
                    // toolbar: [ [ 'Bold' ] ]
                },

                error: [],
                regions: [],

                // data: {
                //     // category: this.$route.params.article_category,
                //     us_title_for_url_title: "",
                //     published: 0,
                //     completed: "",
                //     map: "",
                //     weather: "",
                //     open_timen: "",
                //     closed_time: "",
                //     price_from: "",
                //     start_data: "",
                //     end_data: "",
                //     fb_link: "",
                //     twit_link: "",
                //     google_link: "",
                //     inst_link: "",
                //     web_link: "",

                //     region: "select_region",
                //     mount_id: "select_mount",
                // },
            }
        },
        mounted() {
            // if (this.category == 'outdoor') {
            //     this.get_regions('outdoor')
            // }
            // if (this.category == 'mount_route') {
            //     this.get_mount_massive_data('mount_route')
            // }

            // this.$emit('global_form_data', this.data)

            // console.log(this.$route.params.id)
        },
        methods: {
            
        }
    }
</script>

<style>

</style>