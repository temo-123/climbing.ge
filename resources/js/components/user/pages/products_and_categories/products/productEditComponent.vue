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
            <div class="form-group col-md-12">
                <notify_subscribers :notify_mode_prop="notify_mode" @item_data="notify_mode = $event" />
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="edit_product()" >{{ $t('admin.shop.save_updating') }}</button>
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
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        <label for="4" >{{ $t('admin.shop.product_options_tab') }}</label>
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
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.sale_type_label') }} </label>
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

                        <div class="form-group clearfix" v-if="category_id != 0">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.subcategory_label') }} </label>
                            <div class="col-xs-8">
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
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.change_url_title') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="is_change_url_title" @click="change_url_title_in_global_product">
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

                <!-- Tab 4: Product Options -->
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.shop.product_options_tab') }}</h2>
                            <p class="lead">{{ $t('admin.shop.manage_variants_subtitle') }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <product-options-component></product-options-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import validator_alerts_component from '../../../items/validator_alerts_component.vue'
    import published_item from '../../../items/form/parts/PublishedValueComponent.vue'
    import notify_subscribers from '../../../items/form/parts/NotifySubscribersComponent.vue'
    import productOptionsComponent from '../product_options/items/ProductOptionsComponent.vue'

    export default {
        components: {
            // validator_alerts_component,
            published_item,
            notify_subscribers,
            productOptionsComponent,
        },
        mixins: [],
        props: [
            'back_url',
        ],
        data(){
            return {
                tab_num: 1,
                product_id: null,

                categories: [],
                is_change_url_title: false,
                is_loading: false,
                notify_mode: 'none',

                errors: [],

                category_id: 0,

                editorConfig: {
                    us_short_description_text_editor: {},
                    us_text_editor_config: {},
                    us_info_editor_config: {},

                    ka_short_description_text_editor: {},
                    ka_text_editor_config: {},
                    ka_info_editor_config: {},
                },

                all_subcategories: [],
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
                    us_product: [],
                    ka_product: []
                },

                myModal: false,
            }
        },
        mounted() {
            this.loadAllData()
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            showModal(){
                this.myModal = !this.myModal
            },

            loadAllData() {
                this.is_loading = true;
                this.product_id = this.$route.params.id;
                
                axios
                .get("/get_product/get_product_category/get_all_product_category/")
                .then(categoryResponse => {
                    this.categories = categoryResponse.data;
                    return Promise.all([
                        axios.get("/get_product/get_product_category/get_subcategory/get_all_subcategories"),
                        axios.get("/get_product/get_brand/get_all_brands")
                    ]);
                })
                .then(([subcategoryResponse, brandResponse]) => {
                    this.all_subcategories = subcategoryResponse.data;
                    this.brands = brandResponse.data;
                    return axios.get('/set_product/get_product_editing_data/'+this.$route.params.id);
                })
                .then(productResponse => {
                    this.data = productResponse.data;
                    if (this.data.global_product.subcategory_id) {
                        let action_subcategory = this.all_subcategories.find(item => item.id === this.data.global_product.subcategory_id);
                        if (action_subcategory) {
                            this.category_id = action_subcategory.category_id;
                            this.subcategories = this.all_subcategories.filter(item => item.category_id === this.category_id);
                        }
                    }
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.is_loading = false;
                });
            },

            change_url_title_in_global_product(){
                if(!this.is_change_url_title){
                    if(confirm(this.$t('admin.articles.locale_form.confirm_change_url_title'))){
                        this.is_change_url_title = true
                    }
                    else{
                        this.is_change_url_title = false 
                    }
                }
                else{
                    this.is_change_url_title = false 
                }
                this.data.us_product.is_change_url_title = this.is_change_url_title
            },

            edit_product() {
                this.is_loading = true
                axios
                .post('/set_product/edit_product/'+this.$route.params.id, {
                    data: JSON.stringify(this.data),
                    change_url_title: this.change_url_title,
                    notify_mode: this.notify_mode,
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

            get_category_subcategories(){
                this.data.global_product.subcategory_id = 0
                axios
                .get("/get_product/get_product_category/get_subcategory/get_subcategories_for_category/" + this.category_id)
                .then(response => {
                    this.subcategories = response.data
                    this.$nextTick(() => {
                        window.scrollTo({
                            top: document.body.scrollHeight,
                            behavior: 'smooth'
                        })
                    })
                })
                .catch(error => console.log(error))
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
