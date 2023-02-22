<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_spot_region()" >Save</button>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" v-if="error.global_article_error.published">
                    {{ error.global_article_error.published[0] }}
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
            <div class="row" v-show="tab_num == 1">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region global information</span></h2>
                        <p class="lead">Region global information.</p>
                    </div>
                </div>
                <form >
                    <div class="form-group clearfix row" >
                        <label for="name" class='col-xs-2 control-label'> Map </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="data.map" name="map" class="form-control"> 
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" v-show="tab_num == 2">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region English information</span></h2>
                        <p class="lead">Region English information.</p>
                    </div>
                </div>
                <form >
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> English name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="data.us_name" name="us_name" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="region" class='col-md-2 control-label '> English text </label>
                        
                        <ckeditor v-model="data.us_text" :config="this.$editorConfig"></ckeditor>
                    </div>
                </form>
            </div>
            <div class="row" v-show="tab_num == 3">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region Georgian information</span></h2>
                        <p class="lead">Region Georgian information.</p>
                    </div>
                </div>
                <form >
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Georgian name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="data.ka_name" name="ru_name" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="region" class='col-md-2 control-label '> Georgian text </label>
                        
                        <ckeditor v-model="data.ka_text" :config="this.$editorConfig"></ckeditor>
                    </div>
                </form>
            </div>
            <div class="row" v-show="tab_num == 4">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region Russion information</span></h2>
                        <p class="lead">Region Russion information.</p>
                    </div>
                </div>
                <form>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Russion name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="data.ru_name" name="ka_name" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="region" class='col-md-2 control-label '> Russion text </label>
                        
                        <ckeditor v-model="data.ru_text" :config="this.$editorConfig"></ckeditor>
                    </div>
                </form>
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
                    us_name: '',
                    ru_name: '',
                    ka_name: '',

                    us_text: '',
                    ru_text: '',
                    ka_text: '',

                    map: '',
                },

                tab_num: 1,

                error: {
                    global_article_error: [],
                    ka_article_error: [],
                    ru_article_error: [],
                    us_article_error: [],
                },

                is_back_action: false,

            }
        },
        mounted() {
            this.get_editing_spot_data()
        },
        // beforeRouteLeave (to, from, next) {
        //     if(this.is_back_action = true){
        //         if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
        //             this.is_back_action = false;
        //             next()
        //         } else {
        //             next(false)
        //         }
        //     }
        // },
        methods: {
            get_editing_spot_data(){
                axios
                .get('../../../api/outdoor/get_editing_spot_data/'+this.$route.params.id, {
                    _method: 'GET'
                })
                .then(response => {
                    this.data = response.data
                })
                .catch(err => {
                    //
                })
            },

            edit_spot_region() {
                axios
                .post('../../../api/outdoor/edit_spot/'+this.$route.params.id, {        
                    data: this.data,

                    _method: 'POST'
                })
                .then(response => {
                    this.$router.go(-1)
                })
                .catch(err => {
                    //
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