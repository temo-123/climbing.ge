<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_product()" >Save</button>
            </div>
        </div>
        <div class="row" v-if="error.length != 0">
            <div class="col-md-12">
                <!-- <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.published">
                    Published - {{ error.global_info_validation.published[0] }}
                </div> -->

                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.title">
                    English title - {{ error.us_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.short_description">
                    English description - {{ error.us_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.text">
                    English text - {{ error.us_info_validation.text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.title">
                    Georgian title - {{ error.ka_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.short_description">
                    Georgian description - {{ error.ka_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.text">
                    Georgian text - {{ error.ka_info_validation.text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.title">
                    Russion title - {{ error.ru_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.short_description">
                    Russiondescription - {{ error.ru_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.text">
                    Russion text - {{ error.ru_info_validation.text[0] }}
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
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Publish </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.published" name="published" > 
                                    <option value="0">Not public</option> 
                                    <option value="1">Public</option> 
                                </select> 
                                <!-- <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                    {{ global_article_error.published[0] }}
                                </div> -->
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Sale type </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.sale_type" name="published" > 
                                    <option value="custom production">Custom production</option> 
                                    <option value="online order">Online order</option> 
                                </select>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Mead in Georgia </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="data.global_product.mead_in_georgia" >
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> discount (%) </label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.global_product.discount" name="discount" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> material</label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.global_product.material" name="material" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Category </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.category_id" name="category_id" > 
                                    <option disabled>Select category</option> 
                                    <option v-for="cat in categories" :key="cat.id" v-bind:value="cat.id"> {{ cat.us_name }}</option>
                                </select> 
                            </div>
                        </div>
                    </form>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Product english version</h2>
                            <p class="lead">Article english version for site localisation.</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_product.title"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_product.short_description"  :config="editorConfig.us_short_description_text_editor"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_product.text"  :config="editorConfig.us_text_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Product rusion version</h2>
                            <p class="lead">Article rusion version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="title" v-model="data.ru_product.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description" v-model="data.ru_product.short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_product.short_description" :config="editorConfig.ru_short_description_text_editor"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ru_product.text"  :config="editorConfig.ru_text_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Product georgian version</h2>
                            <p class="lead">Article georgian version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_product.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_product.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_product.short_description" :config="editorConfig.ka_short_description_text_editor"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_product.text"  :config="editorConfig.ka_text_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'
    export default {
        mixins: [
            editor_config
        ],
        props: [
            'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                categories: [],

                editorConfig: {
                    us_short_description_text_editor: editor_config.get_small_editor_config(),
                    us_text_editor_config: editor_config.get_big_editor_config(),
                    us_info_editor_config: editor_config.get_big_editor_config(),

                    ru_short_description_text_editor: editor_config.get_small_editor_config(),
                    ru_text_editor_config: editor_config.get_big_editor_config(),
                    ru_info_editor_config: editor_config.get_big_editor_config(),

                    ka_short_description_text_editor: editor_config.get_small_editor_config(),
                    ka_text_editor_config: editor_config.get_big_editor_config(),
                    ka_info_editor_config: editor_config.get_big_editor_config(),
                },

                data: [],

                // data: {
                //     global_data: {
                //         us_title_for_url_title: '',

                //         published: 0,
                //         category_id: "Select category",
                //         material: "",
                //         discount: "",
                //         sale_type: "Custom production",
                //         mead_in_georgia: "",
                //     },

                //     us_data: {
                //         title: "",
                //         short_description: "",
                //         text: "",
                //     },

                //     ka_data: {
                //         title: "",
                //         short_description: "",
                //         text: "",
                //     },

                //     ru_data: {
                //         title: "",
                //         short_description: "",
                //         text: "",
                //     }
                // },

                is_loading: false,

                myModal: false,
            }
        },
        mounted() {
            this.get_product_category_data()
        },
        methods: {
            showModal(){
                this.myModal = !this.myModal
            },

            add_product() {
                this.data.global_product.us_title_for_url_title = this.data.us_product.title

                axios
                .post('/product/add_product/', {        
                    data: this.data,
                })
                .then((response)=> { 
                    if(confirm('Do you want send notification about editing article?')){
                        this.sand_notification()
                    }
                    else{
                        this.go_back(true)
                    }
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.us_article_error = error.response.data.errors
                    }
                })
            },

            sand_notification() {
                this.is_loading = true

                axios
                .post('/user/notifications/send_product_adding_notification')
                .then(response => {
                    this.go_back(true)
                })
                .catch(err => {
                    console.log(err);
                })
                .finally(() => this.is_loading = false);
            },

            get_product_category_data: function(){
                this.is_loading = true
                axios
                .get("/product_category/")
                .then(response => {
                    this.categories = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);;
            },


            go_back: function(back_action = false) {
                if(back_action == false){
                    if(confirm('Are you sure, you want go back?')){
                        this.$router.go(-1)
                    }
                }
                else{
                    this.$router.go(-1)
                }
            },
        }
    }
</script>