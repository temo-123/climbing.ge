<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" @click="edit_product()" >Save updating</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading" v-if="errors.length != 0">
            <validator_alerts_component
                :errors_prop="errors"
            />
        </div>
        <div class="row" v-show="!is_loading">
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
                        
                        <label for="3" >Russion text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Georgian text</label>
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
                                <!-- <div class="alert alert-danger" role="alert" v-if="global_article_errors.published">
                                    {{ global_article_errors.published[0] }}
                                </div> -->
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Sale type </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.sale_type" name="published" > 
                                    <option value="" disabled>Select order type</option> 
                                    <option value="custom_production">Custom production</option> 
                                    <option value="online_order">Online order</option> 
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
                                <input type="number" max="100" min="1"  v-model="data.global_product.discount" name="discount" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Category </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.category_id" name="category_id" > 
                                    <option v-bind:value="''" disabled>Select category</option> 
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
                            <label for="name" class='col-xs-2 control-label'> Change URL title </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" @click="change_url_title_in_global_product">
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
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> material</label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.us_product.material" name="material" class="form-control"> 
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
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> material</label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.ru_product.material" name="material" class="form-control"> 
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
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> material</label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.ka_product.material" name="material" class="form-control"> 
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
    import validator_alerts_component from '../../../items/validator_alerts_component.vue'
    export default {
        components: {
            validator_alerts_component
        },
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
                change_url_title: null,
                is_loading: false,

                errors: [],

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

                // data: [],

                data: {
                    global_data: {
                        published: 0,
                        category_id: "",
                        sale_type: "",

                        mead_in_georgia: null,
                        material: "",
                        discount: "",
                    },

                    us_data: [],
                    ka_data: [],
                    ru_data: []
                },

                myModal: false,
            }
        },
        mounted() {
            this.get_product_category_data()
            this.get_product_editing_data()
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            showModal(){
                this.myModal = !this.myModal
            },
            change_url_title_in_global_product(){
                if(!this.change_url_title){
                    if(confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')){
                        this.change_url_title = true
                    }
                }
                else{
                    this.change_url_title = false 
                }

            },

            get_product_editing_data(){
                axios
                .get('/product/get_product_editing_data/'+this.$route.params.id)
                .then((response)=> { 
                    this.data = response.data
                })
                .catch(error =>{
                    // 
                })
            },

            edit_product() {
                this.is_loading = true
                if(this.data.global_product.discount == 0 || this.data.global_product.discount == ''){
                    this.data.global_product.discount = null
                }
                axios
                .post('/product/edit_product/'+this.$route.params.id, {        
                    data: JSON.stringify(this.data),
                    change_url_title: this.change_url_title,
                })
                .then((response)=> { 
                    this.go_back(true)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.validation
                    }
                    else{
                        console.log(error)
                    }
                })
                .finally(() => this.is_loading = false);
            },

            get_product_category_data: function(){
                axios
                .get("/product_category/")
                .then(response => {
                    this.categories = response.data
                })
                .catch(
                    error => console.log(error)
                );
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