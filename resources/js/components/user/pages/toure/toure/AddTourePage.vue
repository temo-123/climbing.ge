<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row"  v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row"  v-show="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_toure()" >Save</button>
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="col-md-12" v-if="error.length != 0">
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.published">
                    Published - {{ error.global_info_validation.published[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.category_id">
                    Select category - {{ error.global_info_validation.category_id[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.us_title_for_url_title">
                    URL title - {{ error.global_info_validation.us_title_for_url_title[0] }}
                </div>

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

        <div class="row"  v-show="!is_loading">
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
                                <h2 class="display-4"><span>Toure global information</span></h2>
                                <p class="lead">Toure global information.</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Publish </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_data.published" name="published" > 
                                    <option value="0">Not public</option> 
                                    <option value="1">Public</option> 
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="category" class='col-xs-2 control-label '> Category </label>
                            <div class="col-xs-10">
                                <select class="form-control" v-model="data.global_data.category_id" name="category" > 
                                    <option :value="''" disabled>Select category</option> 
                                    <option  v-for="category in categories" :key="category.id" :value="category.id">{{ category.us_name }}</option>
                                </select> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Location </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_data.location"  class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Duration </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_data.duration"  class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Min price </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_data.min_price"  class="form-control"> 
                            </div>
                        </div>
                    </form>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-groupe">
                                    <button class="btn btn-primary float-left" @click="add_toure_new_image_value()" v-if="(toure_images.length < 8)">Add new toure image</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover" id="dev-table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>|</th>
                                            <th>Delite</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="toure_image in toure_images" :key="toure_image.id">
                                            <td>
                                                <form ref="myForm">
                                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, toure_image.id)">
                                                </form> 
                                            </td>
                                            <td>|</td>
                                            <td>
                                                <button class="btn btn-danger" @click="del_toure_image(toure_image.id)">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Toure english version</h2>
                            <p class="lead">Toure english version for site localisation.</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_data.title"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.short_description"  :config="editor_config.us_short_description_text"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.text"  :config="editor_config.us_text"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Toure rusion version</h2>
                            <p class="lead">Toure rusion version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="title" v-model="data.ru_data.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description" v-model="data.ru_data.short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_data.short_description" :config="editor_config.ru_short_description_text"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ru_data.text"  :config="editor_config.ru_text"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Toure georgian version</h2>
                            <p class="lead">Toure georgian version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_data.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_data.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.short_description" :config="editor_config.ka_short_description_text"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_data.text"  :config="editor_config.ka_text"></ckeditor>
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

                toure_images: [],
                editorConfig: '',
                is_loading: false,

                editor_config: {
                    us_short_description_text: editor_config.get_small_editor_config(),
                    us_text: editor_config.get_big_editor_config(),

                    ru_short_description_text: editor_config.get_small_editor_config(),
                    ru_text: editor_config.get_big_editor_config(),

                    ka_short_description_text: editor_config.get_small_editor_config(),
                    ka_text: editor_config.get_big_editor_config(),
                },

                data: {
                    global_data: {
                        us_title_for_url_title: '',

                        category_id: '',

                        location: '',
                        duration: '',
                        min_price: '',

                        published: 0,
                    },

                    us_data: {
                        title: "",
                        short_description: "",
                        text: "",
                    },

                    ka_data: {
                        title: "",
                        short_description: "",
                        text: "",
                    },

                    ru_data: {
                        title: "",
                        short_description: "",
                        text: "",
                    }
                },
                categories: [],

                myModal: false,

                error: [],
            }
        },
        mounted() {
            this.get_toure_category()
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.toure_images[id]['image'] = image
            },
            add_toure_new_image_value(){
                var new_item_id = this.toure_images.length+1
                this.toure_images.push(
                    {
                        id: new_item_id,
                        image: '',
                    }
                );
            },
            del_toure_image(id){
                this.removeObjectWithId(this.toure_images, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },

            add_toure() {
                this.is_loading = true

                this.data.global_data.us_title_for_url_title = this.data.us_data.title
                let formData = new FormData();

                var loop_num = 0
                this.toure_images.forEach(image => {
                    formData.append('toure_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                .post('/toure/add_toure', 
                    formData
                )
                .then(response => {
                    this.go_back(true)
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                })
                .finally(
                    this.is_loading = false
                )
            },

            get_toure_category: function(){
                axios
                .get("/toure/category/get_all_categories/")
                .then(response => {
                    this.categories = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },


            go_back: function(action = false) {
                this.is_back_action_query = this.$going.back(this, action)
            },
        }
    }
</script>