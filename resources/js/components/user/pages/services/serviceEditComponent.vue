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
                <button type="submit" class="btn btn-primary" v-on:click="edit_service()" >Save update</button>
            </div>
        </div>

        <div class="row"  v-show="!is_loading" v-if="error.length != 0">
            <validator_alerts_component
                :errors_prop="error"
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
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Publish </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_service.published" name="published" > 
                                    <option value="0">Not public</option> 
                                    <option value="1">Public</option> 
                                </select>
                            </div>
                        </div>
                    </form>
                    
                    <div class="col-md-12">
                        <div class="row">
                            Olredy added
                        </div>
                    </div>

                    <div class="col-md-12">
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
                                        <tr v-for="service_old_image in service_old_images" :key="service_old_image.id">
                                            <td>
                                                <!-- <form ref="myForm">
                                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, service_old_image.id)">
                                                </form>  -->

                                                <img class="img-responsive" :src="'../../../../images/service_img/'+service_old_image.image" :alt="service_old_image.title">
                                            </td>
                                            <td>|</td>
                                            <td>
                                                <button class="btn btn-danger" @click="del_service_image_from_db(service_old_image.id)">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            New images
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-groupe">
                                    <button class="btn btn-primary float-left" @click="add_service_new_image_value()" >Add new service imagee</button>
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
                                        <tr v-for="service_image in service_new_images" :key="service_image.id">
                                            <td>
                                                <form ref="myForm">
                                                    <input type="file" name="image" id="image" v-on:change="onFileChange($event, service_image.id)">
                                                </form> 
                                            </td>
                                            <td>|</td>
                                            <td>
                                                <button class="btn btn-danger" @click="del_service_image(service_image.id)">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-groupe">
                                    <button class="btn btn-primary float-left" @click="add_service_new_image_value()">Add new service imagee</button>
                                </div>
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
                                <input type="text" name="name" v-model="data.us_service.title"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Change URL title </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" @click="change_url_title_in_global_service()">
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_service.short_description"  :config="editor_config.us_short_description_text"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_service.text"  :config="editor_config.us_text"></ckeditor>
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
                                <input type="text" name="value name"  v-model="data.ka_service.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_service.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_service.short_description" :config="editor_config.ka_short_description_text"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_service.text"  :config="editor_config.ka_text"></ckeditor>
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
    import validator_alerts_component from '../../items/validator_alerts_component.vue'
    export default {
        components: {
            validator_alerts_component,
        },
        mixins: [
            editor_config
        ],
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                service_new_images: [],
                service_old_images: [],
                // regions: [],

                error: [],

                is_loading: false,

                editor_config: {
                    us_short_description_text: editor_config.get_small_editor_config(),
                    us_text: editor_config.get_big_editor_config(),
                    // us_info: editor_config.get_big_editor_config(),

                    ru_short_description_text: editor_config.get_small_editor_config(),
                    ru_text: editor_config.get_big_editor_config(),
                    // ru_info: editor_config.get_big_editor_config(),

                    ka_short_description_text: editor_config.get_small_editor_config(),
                    ka_text: editor_config.get_big_editor_config(),
                    // ka_info: editor_config.get_big_editor_config(),
                },

                data: {
                    global_service: {},
                    us_service: {},
                    ka_service: {},
                    ru_service: {}
                },

                // the_date: moment().format('YYYY-MM-DD'),

                change_url_title: false
            }
        },
        mounted() {
            this.get_editing_service_data()
            // this.get_region_data()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            // get_region_data: function () {
            //     axios
            //         .get("/article/")
            //         .then((response) => {
            //             this.regions = response.data;
            //         })
            //         .catch((error) => console.log(error));
            // },
            onFileChange(event, item_id){
                let image = event.target.files[0]
                let id = item_id - 1 
                this.service_new_images[id]['image'] = image
            },
            add_service_new_image_value(){
                if(this.service_old_images){
                    if(this.service_new_images.length + this.service_old_images.length < 8){
                        var new_item_id = this.service_new_images.length+1

                        this.service_new_images.push(
                            {
                                id: new_item_id,
                                image: '',
                            }
                        );
                    }
                }
                else{
                    if(this.service_new_images.length < 8){
                        var new_item_id = this.service_new_images.length+1

                        this.service_new_images.push(
                            {
                                id: new_item_id,
                                image: '',
                            }
                        );
                    }
                }
            },
            get_editing_service_data(){
                this.data_for_tab = []
                this.is_loading = true
                axios
                .get("/set_service/get_editing_service/"+this.$route.params.id)
                .then(response => {
                    this.editing_data = response.data

                    this.data = {
                        global_service: response.data.global_service,

                        us_service: response.data.us_service,
                        ru_service: response.data.ru_service,
                        ka_service: response.data.ka_service,
                    }

                    this.service_old_images = response.data.service_images

                    this.get_service_images()

                    // if(this.data.global_service.published_data != null){
                    //     this.the_date = this.data.global_service.published_data
                    // }
                })
                .catch(
                    error => console.log(error)
                )
                .finally(
                    this.is_loading = false
                )
            },
            get_service_images(){
                this.data_for_tab = []
                axios
                .get("/set_service/get_service_images/"+this.$route.params.id)
                .then(response => {
                    this.service_old_images = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_service_image_from_db(image_id){
                if(confirm('Are you sure, you want delite this image?')){
                    axios
                    .delete("/set_service/del_service_image/"+image_id)
                    .then(response => {
                        this.get_service_images()
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },
            del_service_image(id){
                this.removeObjectWithId(this.service_new_images, id);
            },
            removeObjectWithId(arr, id) {
                const objWithIdIndex = arr.findIndex((obj) => obj.id === id);
                arr.splice(objWithIdIndex, 1);

                return arr;
            },

            change_url_title_in_global_service(){
                if(!this.change_url_title){
                    if(confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')){
                        this.change_url_title = true
                    }
                }
                else{
                    this.change_url_title = false 
                }
            },

            edit_service() {
                this.is_loading = true

                if (this.change_url_title) {
                    this.data.global_service.change_url_title = this.change_url_title
                    this.data.global_service.us_title_for_url_title = this.data.us_service.title
                }
                else{
                    this.data.global_service.change_url_title = false
                    this.data.global_service.us_title_for_url_title = false
                }

                let formData = new FormData();

                var loop_num = 0
                this.service_new_images.forEach(image => {
                    formData.append('service_new_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                .post('/service/edit_service/'+this.$route.params.id, 
                    formData
                )
                .then(response => {
                    this.go_back(true)
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                    // else{
                    //     alert(error)
                    // }
                })
                .finally(
                    this.is_loading = false
                )
            },

            go_back: function(action = false) {
                this.is_back_action_query = this.$going.back(this, action)
            },
        }
    }
</script>