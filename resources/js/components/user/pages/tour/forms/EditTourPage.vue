<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>

        <div class="row" v-show="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_tour()" >Save update</button>
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
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Georgian text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>Tour global information</span></h2>
                                <p class="lead">Tour global information.</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        
                        <published_item 
                            :published_prop = data.global_tour.published
                            @item_data="data.global_tour.published = $event" 
                        />

                        <div class="form-group clearfix">
                            <label for="category" class='col-xs-2 control-label '> Category </label>
                            <div class="col-xs-10">
                                <select class="form-control" v-model="data.global_tour.category_id" name="category" > 
                                    <option :value="''" disabled>Select category</option> 
                                    <option  v-for="category in categories" :key="category.id" :value="category.id">{{ category.us_name }}</option>
                                </select> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Min price </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_tour.min_price"  class="form-control"> 
                            </div>
                        </div>
                    </form>
                    
                    <div class="col-md-12">
                        <gallery_images_edit
                            title_prop="Tour Images"
                            image_path_prop="images/tour_img/"
                            get_images_route_prop="/get_tour/get_tour_images/"
                            image_del_route_prop="/set_tour/del_tour_image/"
                            :crop_ratio_prop="{ width: 16, height: 9 }"
                            @update_gallery_images="tour_new_images = $event"
                        />
                    </div>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Tour english version</h2>
                            <p class="lead">Tour english version for site localisation.</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_tour.title"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Change URL title </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="is_change_url_title" @click="change_url_title_in_global_tour()">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Location </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_tour.location"  class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Duration </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_tour.duration"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <small_editor v-model="data.us_tour.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.us_tour.text" />
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Tour georgian version</h2>
                            <p class="lead">Tour georgian version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_tour.title" class="form-control"> 
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Location </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.ka_tour.location"  class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Duration </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.ka_tour.duration"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_tour.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <small_editor v-model="data.ka_tour.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.ka_tour.text" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import published_item from '../../../items/form/parts/PublishedValueComponent.vue'
    import gallery_images_edit from '../../../items/gallery/galleryImageEditComponent.vue'

    export default {
        components: {
            published_item,
            gallery_images_edit,
        },
        mixins: [],
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                tour_new_images: [],

                is_change_url_title: false,

                error: [],

                is_loading: false,

                editor_config: {
                    us_short_description_text: {},
                    us_text: {},
                    // us_info: {},

                    // ru_short_description_text: {},
                    // ru_text: {},
                    // ru_info: {},

                    ka_short_description_text: {},
                    ka_text: {},
                    // ka_info: {},
                },

                data: {
                    global_tour: {},
                    us_tour: {},
                    ka_tour: {},
                    // ru_tour: {}
                },

                categories: [],

                // the_date: moment().format('YYYY-MM-DD'),

                change_url_title: false
            }
        },
        mounted() {
            this.get_editing_tour_data()
            this.get_tour_category()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            get_tour_category: function(){
                axios
                .get("/get_tour/get_category/get_all_categories/")
                .then(response => {
                    this.categories = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_editing_tour_data(){
                this.data_for_tab = []
                this.is_loading = true
                axios
                .get("/set_tour/get_editing_tour/"+this.$route.params.id)
                .then(response => {
                    this.editing_data = response.data

                    this.data = {
                        global_tour: response.data.global_tour,

                        us_tour: response.data.us_tour,
                        ru_tour: response.data.ru_tour,
                        ka_tour: response.data.ka_tour,
                    }

                })
                .catch(
                    error => console.log(error)
                )
                .finally(
                    this.is_loading = false
                )
            },
            change_url_title_in_global_tour(){
                if(!this.is_change_url_title){
                    if(confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')){
                        this.is_change_url_title = true
                    }
                    else{
                        this.is_change_url_title = false 
                    }
                }
                else{
                    this.is_change_url_title = false 
                }

                this.data.us_tour.is_change_url_title = this.is_change_url_title
            },

            edit_tour() {
                this.is_loading = true

                if (this.change_url_title) {
                    this.data.global_tour.change_url_title = this.change_url_title
                    this.data.global_tour.us_title_for_url_title = this.data.us_tour.title
                }
                else{
                    this.data.global_tour.change_url_title = false
                    this.data.global_tour.us_title_for_url_title = false
                }

                let formData = new FormData();

                var loop_num = 0
                this.tour_new_images.forEach(image => {
                    formData.append('tour_new_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                .post('/set_tour/edit_tour/'+this.$route.params.tour_id, 
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