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
                            <select class="form-control" name="published" v-model="data.published"> 
                                <option value="0">Not public</option> 
                                <option value="1">Public</option> 
                            </select> 
                            <!-- <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                {{ global_article_error.published[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                        <label for="region" class='col-md-2 control-label '> Regions </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.region_id" name="region" > 
                                <option :value="'select_region'" disabled>Select region</option> 
                                <option :value="null" style="color:red">Whithout Region</option> 
                                <option  v-for="region in regions" :key="region.id" :value="region.id">{{ region.us_name }}</option>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> Mountain </label>
                        <div class="col-md-10">
                            <select class="form-control" v-model="data.mount_id" name="mount_id"> 
                                <option :value="'select_mount'" disabled>Select mount</option> 
                                <option :value="null" style="color:red">Whithout Mount</option> 
                                <!-- <option :value="'select_mount'">Select mount</option>  -->
                                <option v-for="mount in mount_masive" :key="mount.global_data.id" v-bind:value="mount.global_data.id">{{mount.global_data.name}}</option> 
                            </select> 
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category != 'mount_route' && category != 'partners'">
                        <label for="name" class='col-md-2 control-label'> Map </label>
                        <div class="col-md-10">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <!-- <input type="text" name="map"  v-model="data.map" :value="editing_data.global_article['map']" class="form-control">  -->
                            <input type="text" name="map"  v-model="data.map" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                        <label for="name" class='col-md-2 control-label'> Weather </label>
                        <div class="col-md-10">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="weather" v-model="data.weather" class="form-control"> 
                        </div>
                    </div>

                    <hr>

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Minimal price </label>
                        <div class="col-md-10">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="price_from" v-model="data.price_from" class="form-control"> 
                            <!-- <ckeditor :editor="editor" v-model="data.us_price_from" :config="editorConfig"></ckeditor> -->
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
            'global_data_prop',
            'category_prop',

            'title_prop',
            'description_prop',

            // 'region_id_prop',
            // 'mount_id_prop'
        ],
        data(){
            return {
                // editorConfig: {
                //     // toolbar: [ [ 'Bold' ] ]
                // },
                data: this.global_data_prop,

                region_id: 'select_region',
                mount_id: 'select_mount',

                category: this.category_prop,

                error: [],

                regions: [],
                mount_masive: [],
            }
        },
        watch: {
            global_data_prop: function(newVal, oldVal) { 
                // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
                this.data = this.global_data_prop
            },
            category_prop: function(newVal, oldVal) { 
                this.category = this.category_prop
                if (this.category == 'outdoor') {
                    this.get_regions()
                }
                if (this.category == 'mount_route') {
                    this.get_mount_massive_data()
                }
            },
            // region_id_prop: function(newVal, oldVal) { 
            //     this.data.region_id = this.region_id_prop
            // },
            // mount_id_prop: function(newVal, oldVal) { 
            //     this.data.mount_id = this.mount_id_prop
            // },
        },
        mounted() {
            if (this.category == 'outdoor') {
                this.get_regions()
            }
            if (this.category == 'mount_route') {
                this.get_mount_massive_data()
            }

            // if(!this.region_id == 'select_region'){
            //     this.data.region_id = this.region_id
            // }
            // if(!this.mount_id == 'select_mount'){
            //     this.data.mount_id = this.mount_id
            // }

            this.$emit('global_form_data', this.data)
        },
        methods: {
            get_mount_massive_data(){
                axios
                .get("/mount/mount/")
                .then(response => {
                    this.mount_masive = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_regions(){
                axios
                // .get('/outdoor/regions/'+localStorage.getItem('lang'))
                .get("/outdoor/region/")
                .then(response => {
                    this.regions = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>