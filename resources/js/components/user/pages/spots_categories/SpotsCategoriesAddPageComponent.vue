<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_spot_region()" >Save</button>
            </div>
        </div>
        <div class="row" v-if="errors.length != 0">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" v-if="errors.us_name">
                    English name - {{ errors.us_name[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                    Georgian name - {{ errors.ka_name[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="errors.ru_name">
                    Russion name - {{ errors.ru_name[0] }}
                </div>
            </div>
        </div>
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
                            <label for="name" class='col-xs-2 control-label'> Map </label>
                            <div class="col-xs-9">
                                <input type="text" v-model="data.map" name="map" class="form-control"> 
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
                            <label for="name" class='col-xs-2 control-label'> English name </label>
                            <div class="col-xs-10">
                                <input type="text" v-model="data.us_name" name="us_name" class="form-control"> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <label for="region" class='col-md-2 control-label '> English text </label>
                            
                            <ckeditor v-model="data.us_text" :config="us_text_editor_config"></ckeditor>
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
                            <label for="name" class='col-xs-2 control-label'> Georgian name </label>
                            <div class="col-xs-10">
                                <input type="text" v-model="data.ka_name" name="ru_name" class="form-control"> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <label for="region" class='col-md-2 control-label '> Georgian text </label>
                            
                            <ckeditor v-model="data.ka_text" :config="ka_text_editor_config"></ckeditor>
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
                            <label for="name" class='col-xs-2 control-label'> Russion name </label>
                            <div class="col-xs-10">
                                <input type="text" v-model="data.ru_name" name="ka_name" class="form-control"> 
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <label for="region" class='col-md-2 control-label '> Russion text </label>
                            
                            <ckeditor v-model="data.ru_text" :config="ru_text_editor_config"></ckeditor>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    // import { editor_config } from '../../../../mixins/editor/editor_config_mixin.js'
    // import { going } from '../../../../mixins/easy_navigation_mixin.js'
    export default {
        // mixins: [
        //     going,
        //     editor_config
        // ],
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
                    us_name: '',
                    ru_name: '',
                    ka_name: '',

                    us_text: '',
                    ru_text: '',
                    ka_text: '',

                    map: '',
                },

                us_text_editor_config: this.$editor_config.get_big_editor_config(),
                ru_text_editor_config: this.$editor_config.get_big_editor_config(),
                ka_text_editor_config: this.$editor_config.get_big_editor_config(),

                tab_num: 1,

                errors: [],

                is_back_action: true,

            }
        },
        mounted() {

        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action == true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    this.is_back_action = false;
                    next()
                } else {
                    next(false)
                }
            } else {
                next()
            }
        },
        methods: {
            global_blocks_action(event){
                this.global_blocks = event
            },

            add_spot_region() {
                axios
                .post('/outdoor/add_spot/', {        
                    data: this.data,

                    _method: 'post'
                })
                .then(response => {
                    this.go_back(true)
                })
                .catch(err => {
                    if (err.response.status == 422) {
                        this.errors = err.response.data.validation
                    }
                    // else{
                    //     alert("add_spot_region error => "+err)
                    // }
                })
            },

            go_back: function(action = false) {
                this.is_back_action = this.$going.back(this, action)
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