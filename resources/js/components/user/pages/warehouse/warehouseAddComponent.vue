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
        <div class="row" v-show="error.length != 0">
            <validator_alerts_component
                :errors_prop="error"
            />
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
                        <div class="form-group clearfix row">
                            <label for="published" class='col-md-2 control-label '> Publish </label>
                            <div class="col-md-10">
                                <select class="form-control" v-model="data.global_bisnes.published" name="published" > 
                                    <option value="0">Not public</option> 
                                    <option value="1">Public</option> 
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix" v-if="!data.global_bisnes.public_totaly">
                            <label for="name" class='col-xs-2 control-label'> Published befor (After this data it`s whil by not public`) </label>
                            <div class="col-xs-8">
                                <input type="date" class="form-control" id="datemin" name="datemin" min="2000-01-02" v-model="data.global_bisnes.published_data" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="scales" class='col-xs-2 control-label'> Totaly public </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" @click="clear_published_time()" v-model="data.global_bisnes.public_totaly" >
                            </div>
                        </div>
                    </form>

                    <!-- <article_bisnes_add_relatione_tab
                        @update_article_relations="update_article_relations"
                    /> 
                    
                    <gallery_images_add
                        @update_gallery_images="update_gallery_images"

                        :image_path_prop="'images/local_bisnes_img/'"
                    /> -->
                   
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
                                <input type="text" name="name" v-model="data.us_bisnes.title"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_bisnes.short_description"  :config="editor_config.us_short_description"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_bisnes.text"  :config="editor_config.us_text"></ckeditor>
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
                                <input type="text" name="value name"  v-model="data.ka_bisnes.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_bisnes.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_bisnes.short_description" :config="editor_config.ka_short_description"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_bisnes.text"  :config="editor_config.ka_text"></ckeditor>
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
    // import gallery_images_add from '../../items//gallery/galleryImageAddComponent.vue'
    // import article_bisnes_add_relatione_tab from './items/articleBisnesAddRelationeTabComponent.vue'

    import validator_alerts_component from '../../items/validator_alerts_component.vue'
    export default {
        mixins: [
            editor_config,
        ],
        components: {
            // gallery_images_add,
            // article_bisnes_add_relatione_tab,
            validator_alerts_component,
        },
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                bisnes_images: [],
                regions: [],

                error: [],

                editor_config: {
                    us_short_description: editor_config.get_small_editor_config(),
                    us_text: editor_config.get_big_editor_config(),
                    ru_short_description: editor_config.get_small_editor_config(),
                    ru_text: editor_config.get_big_editor_config(),
                    ka_short_description: editor_config.get_small_editor_config(),
                    ka_text: editor_config.get_big_editor_config(),
                },

                data: {
                    global_bisnes: {
                        url_title: '',

                        published: 0,
                        published_data: null,
                        public_totaly: 0,
                    },

                    us_bisnes: {
                        title: "",
                        short_description: "",
                        text: "",
                    },

                    ka_bisnes: {
                        title: "",
                        short_description: "",
                        text: "",
                    },

                    ru_bisnes: {
                        title: "",
                        short_description: "",
                        text: "",
                    }
                },

                myModal: false,

                
            }
        },
        mounted() {
            // this.get_bisnes_category_bisnes()
            this.get_region_bisnes()
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            clear_published_time(){
                this.data.global_bisnes.published_data = null
            },
            get_region_bisnes: function () {
                axios
                    .get("../api/article/")
                    .then((response) => {
                        this.regions = response.data;
                    })
                    .catch((error) => console.log(error));
            },

            update_article_relations(articles){
                this.bisnes_new_article_relations = articles
            },

            update_gallery_images(images){
                this.bisnes_images = images
            },
            add_bisnes() {
                this.data.global_bisnes.url_title = this.data.us_bisnes.title
                let formData = new FormData();

                if(this.bisnes_images){
                    var loop_num = 0
                    this.bisnes_images.forEach(image => {
                        formData.append('bisnes_images['+loop_num+']', image.image)
                        loop_num++
                    });
                    loop_num = 0
                }

                if(this.bisnes_new_article_relations){
                    var relation_loop_num = 0
                    this.bisnes_new_article_relations.forEach(relation => {
                        formData.append('bisnes_new_article_relations['+relation_loop_num+']', relation.article_id)
                        relation_loop_num++
                    });
                    relation_loop_num = 0
                }
                
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