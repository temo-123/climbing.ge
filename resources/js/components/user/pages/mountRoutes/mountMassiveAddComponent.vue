<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_mount_massive_region()" >Save</button>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" v-if="error.mount_massive_error.map">
                    {{ error.mount_massive_error.map[0] }}
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >Global info</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >English text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Georgian text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Russion text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>Region global information</span></h2>
                                <p class="lead">Region global information.</p>
                            </div>
                        </div>
                    </div>
                    <form >
                        <div class="form-group clearfix row" >
                            <label for="name" class='col-xs-2 control-label'> Demo name </label>
                            <div class="col-xs-10">
                                <input type="text" v-model="data.global_data.demo_name" name="demo name" class="form-control"> 
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
                            <h2 class="display-4"><span>Region English information</span></h2>
                            <p class="lead">Region English information.</p>
                        </div>
                    </div>
                    <form >
                        
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title english </label>
                            <div class="col-xs-10">
                                <input type="text" name="name" v-model="data.us_data.name" class="form-control">
                                <!-- <div class="alert alert-danger" role="alert" v-if="errors.name">
                                    {{ errors.name[0] }}
                                </div> -->
                            </div>
                        </div>
                        <hr>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English text </label>
                            <div class="col-xs-10">
                                    <!-- <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.text" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English description </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English how get hear </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.how_get" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> English best time </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.us_data.best_time" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="jumbotron width_100">
                        <div class="container">
                            <h2 class="display-4"><span>Region Georgian information</span></h2>
                            <p class="lead">Region Georgian information.</p>
                        </div>
                    </div>
                    <form >
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title rusian </label>
                            <div class="col-xs-10">
                                <input type="text" name="name_ru" v-model="data.ru_data.name" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Rusian text </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="text_ru" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_data.text" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Rusian description </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Rusian how get hear </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_data.how_get" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Rusian best time </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="best_time_ru" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_data.best_time" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="jumbotron width_100">
                        <div class="container">
                            <h2 class="display-4"><span>Region Russion information</span></h2>
                            <p class="lead">Region Russion information.</p>
                        </div>
                    </div>
                    <form>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title georgian </label>
                            <div class="col-xs-10">
                                <input type="text" name="name_ka" v-model="data.ka_data.name" class="form-control"> 
                            </div>
                        </div>
                        <hr>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgian text </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="text_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.text" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgian description </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="description_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgian how get hear </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="how_get_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.how_get" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Georgian best time </label>
                            <div class="col-xs-10">
                                <!-- <textarea type="text"  name="best_time_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.best_time" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    export default {
        components: {
            // StackModal,
        },
        props: [
            // 'back_url',
            // 'category'
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
                    ru_data: {
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

                tab_num: 1,

                error: [],

                is_back_action: false,

            }
        },
        mounted() {

        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action = true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action = false;
                    next()
                } else {
                    next(false)
                }
            }
        },
        methods: {
            global_blocks_action(event){
                this.global_blocks = event
            },

            add_mount_massive_region() {
                axios
                .post('../../api/mount/', {        
                    data: this.data,

                    _method: 'post'
                })
                .then(response => {
                    this.$router.go(-1)
                })
                .catch(err => {
                    console.log(err);
                })
            },
            
            go_back: function() {
                this.is_back_action = true

                this.$router.go(-1)
            },

        }
    }
</script>

<style scoped>
    .tabs {
        /* display: flex; */
        flex-wrap: wrap;
    }
    .tabs label {
        order: 1;
        display: block;
        padding: 1rem 2rem;
        margin-right: 0.2rem;
        cursor: pointer;
        background: #ccced0;
        font-weight: bold;
        transition: background ease 0.2s;
    }
    .tabs .tab {
        order: 99;
        flex-grow: 1;
        width: 100%;
        display: none;
        padding: 1rem;
        background: #fff;
        /* border: 1px solid #ccc!important; */
    }
    .tabs input[type="radio"] {
        display: none;
    }
    .tabs input[type="radio"]:checked + label {
        background: #fff;
        border: 1px solid #ccc !important;
    }
    .tabs input[type="radio"]:checked + label + .tab {
        display: block;
    }

    @media (max-width: 45em) {
        .tabs .tab,
        .tabs label {
            order: initial;
        }
        .tabs label {
            width: 100%;
            margin-right: 0;
            margin-top: 0.2rem;
        }
    }
</style>