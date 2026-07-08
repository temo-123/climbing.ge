<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">{{ $t('common.back') }}</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_product()" >{{ $t('common.save') }}</button>
            </div>
        </div>
        <div class="row" v-show="!is_loading" v-if="errors.length != 0">
            <validator_alerts_component
                :errors_prop="errors"
            />
        </div>
        <div class="row">
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
                                <h2 class="display-4"><span>{{ $t('admin.shop.global_product_info_title') }}</span></h2>
                                <p class="lead">{{ $t('admin.shop.global_product_info_subtitle') }}</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">

                        <published_item
                            :published_prop = data.global_product.published
                            @item_data="data.global_product.published = $event"
                        />

                        <div class="form-group clearfix">
                            <label for="sale_type" class='col-xs-2 control-label'> {{ $t('admin.shop.sale_type_label') }} </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.sale_type" name="sale_type" >
                                    <option value="" disabled>{{ $t('admin.shop.select_order_type') }}</option>
                                    <option value="online_order">{{ $t('admin.shop.online_order') }}</option>
                                    <option value="produced_by_order">{{ $t('admin.shop.produced_by_order') }}</option>
                                    <option value="custom_production">{{ $t('admin.shop.custom_production') }}</option>
                                    <option value="donation">{{ $t('admin.shop.donation_option') }}</option>
                                    <option value="outlet">{{ $t('admin.shop.outlet_option') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.made_in_georgia_label') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="data.global_product.made_in_georgia" >
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.brand_label') }} </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.brand_id" name="brand_id">
                                    <option v-bind:value="''" disabled>{{ $t('admin.shop.select_brand') }}</option>
                                    <option v-for="brand in brands" :key="brand.global_brand.id" v-bind:value="brand.global_brand.id"> {{ brand.us_brand.title }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.category_label') }} </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="category_id" name="category_id"  @change="get_category_subcategories()">
                                    <option v-bind:value="0" disabled>{{ $t('admin.shop.select_category') }}</option>
                                    <option v-for="cat in categories" :key="cat.id" v-bind:value="cat.id"> {{ cat.us_name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label' v-if="category_id != 0"> {{ $t('admin.shop.subcategory_label') }} </label>
                            <div class="col-xs-8" v-if="category_id != 0">
                                <select class="form-control" v-model="data.global_product.subcategory_id" name="category_id" >
                                    <option v-bind:value="0" disabled>{{ $t('admin.shop.select_subcategory') }}</option>
                                    <option v-for="subcat in subcategories" :key="subcat.id" v-bind:value="subcat.id"> {{ subcat.us_name }}</option>
                                </select>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.shop.product_en_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.shop.product_en_version_subtitle') }}</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_product.title"  class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <small_editor v-model="data.us_product.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.us_product.text" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.material_label') }}</label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.us_product.material" name="material" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.shop.product_ka_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.shop.product_ka_version_subtitle') }}</p>
                        </div>
                    </div>

                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_product.title" class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_product.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <small_editor v-model="data.ka_product.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.ka_product.text" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.material_label') }}</label>
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
    // import validator_alerts_component from '../../../items/validator_alerts_component.vue'
    import published_item from '../../../items/form/parts/PublishedValueComponent.vue'
    export default {
        components: {
            // validator_alerts_component,
            published_item
        },
        mixins: [],
        props: [
            'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                categories: [],

                editorConfig: {
                    us_short_description_text_editor: {},
                    us_text_editor_config: {},
                    us_info_editor_config: {},

                    ka_short_description_text_editor: {},
                    ka_text_editor_config: {},
                    ka_info_editor_config: {},
                },

                errors: [],

                category_id: 0,

                subcategories: [],
                brands: [],

                data: {
                    global_product: {
                        published: 0,
                        subcategory_id: 0,
                        brand_id: '',
                        sale_type: "",

                        made_in_georgia: null,
                    },

                    us_product: {
                        title: "",
                        short_description: "",
                        text: "",
                        material: "",
                    },

                    ka_product: {
                        title: "",
                        short_description: "",
                        text: "",
                        material: "",
                    },

                    // ru_product: {
                    //     title: "",
                    //     short_description: "",
                    //     text: "",
                    //     material: "",
                    // }
                },

                is_loading: false,

                myModal: false,
            }
        },
        mounted() {
            this.get_product_categories()
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            showModal(){
                this.myModal = !this.myModal
            },

            add_product() {
                // this.data.global_product.us_title_for_url_title = this.data.us_product.title

                axios
                .post('/set_product/add_product/', {        
                    data: JSON.stringify(this.data),
                })
                .then((response)=> { 
                    // if(confirm('Do you want send notification about editing article?')){
                    //     this.sand_notification()
                    // }
                    // else{
                        this.go_back(true)
                    // }
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.validation
                    }
                    this.is_loading = true
                })
                .finally(() => this.is_loading = false);
            },

            // sand_notification() {
            //     this.is_loading = true

            //     axios
            //     .post('/user/notifications/send_product_adding_notification')
            //     .then(response => {
            //         this.go_back(true)
            //     })
            //     .catch(err => {
            //         console.log(err);
            //     })
            //     .finally(() => this.is_loading = false);
            // },

            get_product_brabds(){
                this.is_loading = true
                axios
                .get("/get_product/get_brand/get_all_brands")
                .then(response => {
                    this.brands = response.data
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            get_product_categories: function(){
                this.is_loading = true
                axios
                .get("/get_product/get_product_category/get_all_product_category/")
                .then(response => {
                    this.categories = response.data
                    this.get_product_brabds()
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            get_category_subcategories(){
                this.data.global_product.subcategory_id = 0
                axios
                .get("/get_product/get_product_category/get_subcategory/get_subcategories_for_category/" + this.category_id)
                .then(response => {
                    this.subcategories = response.data
                    // Scroll to bottom of page to show subcategory dropdown
                    this.$nextTick(() => {
                        window.scrollTo({
                            top: document.body.scrollHeight,
                            behavior: 'smooth'
                        })
                    })
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            go_back: function(back_action = false) {
                if(back_action == false){
                    if(confirm(this.$t('admin.shop.confirm_go_back'))){
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