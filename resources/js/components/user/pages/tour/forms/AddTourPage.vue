<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row"  v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">{{ $t('common.back') }}</button>
            </div>
        </div>
        <div class="row"  v-show="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_tour()" >{{ $t('common.save') }}</button>
            </div>
        </div>

        <div class="row" v-show="!is_loading" v-if="error.length != 0">
            <validator_alerts_component
                :errors_prop="error"
            />
        </div>

        <div class="row"  v-show="!is_loading">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >{{ $t('common.global_info') }}</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >{{ $t('common.english_text') }}</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >{{ $t('common.georgian_text') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>{{ $t('admin.tour.tour_global_info_title') }}</span></h2>
                                <p class="lead">{{ $t('admin.tour.tour_global_info_subtitle') }}</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">

                        <published_item
                            :published_prop = data.global_tour.published
                            @item_data="data.global_tour.published = $event"
                        />

                        <div class="form-group clearfix">
                            <label for="category" class='col-xs-2 control-label '> {{ $t('common.category') }} </label>
                            <div class="col-xs-10">
                                <select class="form-control" v-model="data.global_tour.category_id" name="category" >
                                    <option :value="''" disabled>{{ $t('admin.shop.select_category') }}</option>
                                    <option  v-for="category in categories" :key="category.id" :value="category.id">{{ category.us_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.tour.min_price_label') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_tour.min_price"  class="form-control">
                            </div>
                        </div>
                    </form>

                    <div class="col-md-12">
                        <gallery_images_add
                            :title_prop="$t('admin.tour.tour_images_title')"
                            :crop_ratio_prop="{ width: 16, height: 9 }"
                            @update_gallery_images="tour_images = $event"
                        />
                    </div>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.tour.tour_en_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.tour.tour_en_version_subtitle') }}</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_tour.title"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.tour.location_label') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_tour.location"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.tour.duration_label') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_tour.duration"  class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <small_editor v-model="data.us_tour.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.us_tour.text" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.tour.tour_ka_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.tour.tour_ka_version_subtitle') }}</p>
                        </div>
                    </div>

                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_tour.title" class="form-control">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.tour.location_label') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.ka_tour.location"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.tour.duration_label') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.ka_tour.duration"  class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_tour.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <small_editor v-model="data.ka_tour.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
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
    import gallery_images_add from '../../../items/gallery/galleryImageAddComponent.vue'

    export default {
        components: {
            published_item,
            gallery_images_add,
        },
        mixins: [],
        props: [
            'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                tour_images: [],
                editorConfig: '',
                is_loading: false,
                editor_config: {
                    us_short_description_text: {},
                    us_text: {},

                    ru_short_description_text: {},
                    ru_text: {},

                    ka_short_description_text: {},
                    ka_text: {},
                },

                // data: {
                //     global_tour: {
                //         // us_title_for_url_title: '',

                //         category_id: '',

                //         // location: '',
                //         // duration: '',
                //         min_price: '',

                //         published: 0,
                //     },

                //     us_tour: {
                //         title: "",
                //         short_description: "",
                //         text: "",
                //     },

                //     ka_tour: {
                //         title: "",
                //         short_description: "",
                //         text: "",
                //     },

                //     ru_tour: {
                //         title: "",
                //         short_description: "",
                //         text: "",
                //     }
                // },

                data: {
                    global_tour: {},
                    us_tour: {},
                    ka_tour: {},
                },
                categories: [],

                myModal: false,

                error: [],
            }
        },
        mounted() {
            this.get_tour_category()
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            add_tour() {
                this.is_loading = true

                // this.data.global_tour.us_title_for_url_title = this.data.us_tour.title
                let formData = new FormData();

                var loop_num = 0
                this.tour_images.forEach(image => {
                    formData.append('tour_images['+loop_num+']', image.image)
                    loop_num++
                });
                loop_num = 0

                formData.append('data', JSON.stringify(this.data))

                axios
                .post('/set_tour/add_tour', 
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


            go_back: function(action = false) {
                this.is_back_action_query = this.$going.back(this, action)
            },
        }
    }
</script>