<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_bisnes()" >Save</button>
            </div>
        </div>
        <div class="row" v-if="error.length != 0">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.published">
                    Published - {{ error.global_info_validation.published[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.start_data">
                    Start data - {{ error.global_info_validation.start_data[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.end_data">
                    End data - {{ error.global_info_validation.end_data[0] }}
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
                    Russion description - {{ error.ru_info_validation.short_description[0] }}
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
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Georgian text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Russion text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>Service global information</span></h2>
                                <p class="lead">Service global information.</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix" v-if="!data.global_data.public_totaly">
                            <label for="name" class='col-xs-2 control-label'> Published befor (After this data it`s whil by not public`) </label>
                            <div class="col-xs-8">
                                <input type="datetime-local" class="form-control" id="datemin" name="datemin" min="2000-01-02" v-model="data.global_data.published_data" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="scales" class='col-xs-2 control-label'> Totaly public </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="data.global_data.public_totaly" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="article_id" class="col-xs-2 control-label"> Article </label>
                            <div class="col-xs-8">
                                <select class="form-control" name="article_id" v-model="data.global_data.article_id" >
                                    <option v-for="region in regions" :key="region" v-bind:value="region.id"> {{ region.url_title }} </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-groupe">
                                    <button class="btn btn-primary float-left" @click="add_bisnes_new_image_value()" v-if="(bisnes_images.length < 8)">Add new bisnes image</button>
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
                                        <tr v-for="bisnes_image in bisnes_images" :key="bisnes_image.id">
                                            <td>
                                                <form ref="myForm">
                                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, bisnes_image.id)">
                                                </form> 
                                            </td>
                                            <td>|</td>
                                            <td>
                                                <button class="btn btn-danger" @click="del_bisnes_image(bisnes_image.id)">Delete</button>
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
                            <h2 class="display-4">Service english version</h2>
                            <p class="lead">Service english version for site localisation.</p>
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
                                <ckeditor v-model="data.us_data.short_description"  :config="us_short_description_text_editor"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.text"  :config="us_text_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Service rusion version</h2>
                            <p class="lead">Service rusion version for site localisation.</p>
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
                                <ckeditor v-model="data.ru_data.short_description" :config="ru_short_description_text_editor"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ru_data.text"  :config="ru_text_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Service georgian version</h2>
                            <p class="lead">Service georgian version for site localisation.</p>
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
                                <ckeditor v-model="data.ka_data.short_description" :config="ka_short_description_text_editor"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_data.text"  :config="ka_text_editor_config"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { editor_config } from '../../../../mixins/editor/editor_config_mixin.js'
    export default {
        mixins: [
            editor_config
        ],
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                bisnes_images: [],
                regions: [],
                // editorConfig: '',

                error: [],

                us_short_description_text_editor: editor_config.get_small_editor_config(),
                us_text_editor_config: editor_config.get_big_editor_config(),
                us_info_editor_config: editor_config.get_big_editor_config(),

                ru_short_description_text_editor: editor_config.get_small_editor_config(),
                ru_text_editor_config: editor_config.get_big_editor_config(),
                ru_info_editor_config: editor_config.get_big_editor_config(),
                
                ka_short_description_text_editor: editor_config.get_small_editor_config(),
                ka_text_editor_config: editor_config.get_big_editor_config(),
                ka_info_editor_config: editor_config.get_big_editor_config(),

                data: {
                    global_data: {
                        us_title_for_url_title: '',

                        published_data: '',
                        public_totaly: 0,
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

                myModal: false,

                
            }
        },
        mounted() {
            // this.get_bisnes_category_data()
            this.get_region_data()
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            get_region_data: function () {
                axios
                    .get("../api/article/")
                    .then((response) => {
                        this.regions = response.data;
                    })
                    .catch((error) => console.log(error));
            },
            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.bisnes_images[id]['image'] = image
            },
            add_bisnes_new_image_value(){
                var new_item_id = this.bisnes_images.length+1
                this.bisnes_images.push(
                    {
                        id: new_item_id,
                        image: '',
                    }
                );
            },
            del_bisnes_image(id){
                this.removeObjectWithId(this.bisnes_images, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },

            // showModal(){
            //     this.myModal = !this.myModal
            // },

            add_bisnes() {
                this.data.global_data.us_title_for_url_title = this.data.us_data.title
                let formData = new FormData();

                var loop_num = 0
                this.bisnes_images.forEach(image => {
                    formData.append('bisnes_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                .post('../api/bisnes/add_local_bisnes', 
                    formData
                )
                .then(response => {
                    this.go_back(true)
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                    else{
                        alert(error)
                    }
                });
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