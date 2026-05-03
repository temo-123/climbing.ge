<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-show="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>
        <div class="row" v-show="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Back</button>
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
                        <label for="3" >Georgian text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        <label for="4" >Product Options</label>
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

                        <published_item 
                            :published_prop = data.global_product.published
                            @item_data="data.global_product.published = $event" 
                        />

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Sale type </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.sale_type" name="published" >
                                    <option value="" disabled>Select order type</option>
                                    <option value="online_order">Online order (2-4 days)</option>
                                    <option value="produced_by_order">Produced by order (5-9 days)</option>
                                    <option value="custom_production">Custom production</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Mead in Georgia </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="data.global_product.made_in_georgia" >
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Is donation product </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="is_donation_product" name="is_donation_product" v-model="data.global_product.is_donation_product" >
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Brand </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.brand_id" name="brand_id"> 
                                    <option v-bind:value="''" disabled>Select brand</option> 
                                    <option v-for="brand in brands" :key="brand.global_brand.id" v-bind:value="brand.global_brand.id"> {{ brand.us_brand.title }}</option>
                                </select> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Category </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="category_id" name="category_id"  @change="get_category_subcategories()">
                                    <option v-bind:value="0" disabled>Select category</option> 
                                    <option v-for="cat in categories" :key="cat.id" v-bind:value="cat.id"> {{ cat.us_name }}</option>
                                </select> 
                            </div>
                        </div>

                        <div class="form-group clearfix" v-if="category_id != 0">
                            <label for="name" class='col-xs-2 control-label'> Subcategory </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_product.subcategory_id" name="category_id" > 
                                    <option v-bind:value="0" disabled>Select subcategory</option> 
                                    <option v-for="subcat in subcategories" :key="subcat.id" v-bind:value="subcat.id"> {{ subcat.us_name }}</option>
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
                                <input type="checkbox" id="scales" name="scales" v-model="is_change_url_title" @click="change_url_title_in_global_product">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <small_editor v-model="data.us_product.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.us_product.text" />
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
                                <small_editor v-model="data.ka_product.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.ka_product.text" />
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
                
                <!-- Tab 4: Product Options -->
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Product Options</h2>
                            <p class="lead">Manage product variants (sizes, colors, etc.) and their prices.</p>
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
    import productOptionsComponent from '../product_options/items/ProductOptionsComponent.vue'
    
    export default {
        components: {
            // validator_alerts_component,
            published_item,
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
                        is_donation_product: null,
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
                    if(confirm('Are you sure, you want change URL title? It will be bad for SEO optimization')){
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
