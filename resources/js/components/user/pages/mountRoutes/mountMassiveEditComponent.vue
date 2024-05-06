<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-if="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>
        <div class="row" v-if="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row" v-if="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_mount()" >Save</button>
            </div>
        </div>
        <div class="row"  v-if="error.length != 0">
            <validator_alerts_component
                :errors_prop="error"
            />
        </div>
        <div class="row" v-if="!is_loading">
            <!-- <div class="col-xs-12"> -->
                <div class="row width_100">
                    <div class="col-md-3" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >Global info</label>
                    </div>
                    <div class="col-md-3" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >English text</label>
                    </div>
                    <div class="col-md-3" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Georgian text</label>
                    </div>
                <!-- </div> -->
            </div>
            <!-- <div class="col-xs-12"> -->
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-xs-12">
                                <h2 class="display-4"><span>Mount masive global information</span></h2>
                                <p class="lead">Mount masive global information.</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100">
                        <div class="form-group clearfix" >
                            <label for="name" class='col-xs-2 control-label'> Demo name </label>
                            <div class="col-xs-10">
                                <input type="text" v-model="data.global_data.name" name="demo name" class="form-control"> 
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Map </label>
                            <div class="col-xs-10">
                                <input type="text" v-model="data.global_data.map" name="map" class="form-control"> 
                                <!-- <div class="alert alert-danger" role="alert" v-if="errors.map">
                                    {{ errors.map[0] }}
                                </div> -->
                            </div>
                        </div>
    
                        <div class="form-group clearfix" >
                            <label for="name" class='col-xs-2 control-label'> Weather </label>
                            <div class="col-xs-10">
                                <input type="text" v-model="data.global_data.weather" name="weather" class="form-control"> 
                                <!-- <div class="alert alert-danger" role="alert" v-if="errors.weather">
                                    {{ errors.weather[0] }}
                                </div> -->
                            </div>
                        </div>
                    </form>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="jumbotron width_100">
                        <div class="container">
                            <h2 class="display-4"><span>Mount masive English information</span></h2>
                            <p class="lead">Mount masive English information.</p>
                        </div>
                    </div>
                    <form  class="width_100">
                        
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title english </label>
                            <div class="col-xs-10">
                                <input type="text" name="name" v-model="data.us_data.title" class="form-control">
                                <!-- <div class="alert alert-danger" role="alert" v-if="errors.name">
                                    {{ errors.name[0] }}
                                </div> -->
                            </div>
                        </div>
                        <hr>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English text </label>
                            <div class="col-xs-10">
                                    <!-- <textarea type="text"  name="text" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.text" :config="editor_config.us_text_editor_config"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English description </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="description" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.short_description" :config="editor_config.us_short_description_text_editor"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English how get hear </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="how_get" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.how_get" :config="editor_config.us_how_get_editor_config"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English best time </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text" name="best_time" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.best_time" :config="editor_config.us_best_time_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="jumbotron width_100">
                        <div class="container">
                            <h2 class="display-4"><span>Mount masive Georgian information</span></h2>
                            <p class="lead">Mount masive Georgian information.</p>
                        </div>
                    </div>
                    <form  class="width_100">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title Georgia </label>
                            <div class="col-xs-10">
                                <input type="text" name="name_ru" v-model="data.ka_data.title" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgia text </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="text_ru" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.text" :config="editor_config.ka_text_editor_config"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgia description </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="description" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.short_description" :config="editor_config.ka_short_description_text_editor"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgia how get hear </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="how_get" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.how_get" :config="editor_config.ka_how_get_editor_config"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgia best time </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="best_time_ru" rows="15" class="form-cotrol xs-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.best_time" :config="editor_config.ka_best_time_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
        </div>

    </div>
</template>

<script>
    import { editor_config } from '../../../../mixins/editor/editor_config_mixin.js'
    import validator_alerts_component from '../../items/validator_alerts_component.vue'
    export default {
        mixins: [
            editor_config,
        ],
        props: [
            validator_alerts_component
        ],
        data(){
            return {
                data: {
                    us_data: {
                        title: '',
                        short_description: '',
                        text: '',
                        route: '',
                        how_get: '',
                        best_time: '',
                    },
                    ka_data: {
                        title: '',
                        short_description: '',
                        text: '',
                        route: '',
                        how_get: '',
                        best_time: '',
                    },
                    ka_data: {
                        title: '',
                        short_description: '',
                        text: '',
                        route: '',
                        how_get: '',
                        best_time: '',
                    },
                    global_data: {
                        demo_image: "",
                        map: "",
                        weather: "",
                    },
                },

                editor_config: {
                    us_short_description_text_editor: editor_config.get_small_editor_config(),
                    us_text_editor_config: editor_config.get_big_editor_config(),
                    us_info_editor_config: editor_config.get_big_editor_config(),
                    us_how_get_editor_config: editor_config.get_big_editor_config(),
                    us_best_time_editor_config: editor_config.get_big_editor_config(),

                    ka_short_description_text_editor: editor_config.get_small_editor_config(),
                    ka_text_editor_config: editor_config.get_big_editor_config(),
                    ka_info_editor_config: editor_config.get_big_editor_config(),
                    ka_how_get_editor_config: editor_config.get_big_editor_config(),
                    ka_best_time_editor_config: editor_config.get_big_editor_config(),
                },

                is_loading: false,

                tab_num: 1,

                error: [],

                is_back_action: false,
            }
        },
        mounted() {
            this.get_editing_data()
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action == true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
            }
        },
        // created() {
        //     // so nice
        //     // console.log(`${this.$going.back(this.is_back_action, this, true)} is currently logged in.`);
        //     this.$test()
        // },
        methods: {
            edit_mount: function () {
                axios
                .post('/mount/edit_mount_massive/' + this.$route.params.id, {
                    data: this.data,
                })
                .then(Response => { 
                    this.go_back(true)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                    // else {
                    //     alert(error)
                    // }
                })
            },

            get_editing_data: function() {
                axios
                .get('/mount/get_editing_mount_data/'+this.$route.params.id)
                .then(response => {
                    this.data = response.data
                })
                // .catch(
                //     error => alert(error)
                // );
            },

            go_back: function(action = false) {
                this.is_back_action = this.$going.back(this, action)
            },
        }
    }
</script>
