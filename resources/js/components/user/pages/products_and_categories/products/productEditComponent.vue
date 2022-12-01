<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" @click="edit_product()" >Save updating</button>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" v-if="error.mount_massive_error.map">
                    {{ error.mount_massive_error.map[0] }}
                </div>
            </div>
        </div> -->
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
                                <select class="form-control" v-model="data.global_data.published" name="published" > 
                                    <option value="0">Not public</option> 
                                    <option value="1">Public</option> 
                                </select> 
                                <!-- <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                    {{ global_article_error.published[0] }}
                                </div> -->
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Sale type </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_data.sale_type" name="published" > 
                                    <option value="custom production">Custom production</option> 
                                    <option value="online order">Online order</option> 
                                </select>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Mead in Georgia </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="data.global_data.mead_in_georgia" >
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> discount (%) </label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.global_data.discount" name="discount" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> material</label>
                            <div class="col-xs-8">
                                <input type="text" v-model="data.global_data.material" name="material" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Category </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_data.category_id" name="category_id" > 
                                    <option disabled>Select category</option> 
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
                                <input type="text" name="name" v-model="data.us_data.title"  class="form-control"> 
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
                                <ckeditor v-model="data.us_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.text" :config="editorConfig"></ckeditor>
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
                                <input type="text" name="title" v-model="data.ru_data.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description" v-model="data.ru_data.short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ru_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ru_data.text" :config="editorConfig"></ckeditor>
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
                                <input type="text" name="value name"  v-model="data.ka_data.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_data.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.short_description" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_data.text" :config="editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: [
        'back_url',
    ],
    data(){
        return {
            tab_num: 1,

            categories: [],
            editorConfig: '',
            change_url_title: null,
            // back_action: false,
            data: {
                global_data: {
                    published: 0,
                    category_id: "Select category",
                    material: "",
                    discount: "",
                    sale_type: "Custom production",
                    mead_in_georgia: "",
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
        this.get_product_category_data()
        this.get_product_editing_data()
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
            .get('../../api/product/'+this.$route.params.id)
            .then((response)=> { 
                this.data = {
                    global_data: {
                        published: response.data.global_product.published,
                        category_id: response.data.global_product.category_id,
                        material: response.data.global_product.material,
                        discount: response.data.global_product.discount,
                        sale_type: response.data.global_product.sale_type,
                        mead_in_georgia: response.data.global_product.mead_in_georgia,
                    },

                    us_data: {
                        title: response.data.us_product.title,
                        short_description: response.data.us_product.short_description,
                        text: response.data.us_product.text,
                    },

                    ka_data: {
                        title: response.data.ka_product.title,
                        short_description: response.data.ka_product.short_description,
                        text: response.data.ka_product.text,
                    },

                    ru_data: {
                        title: response.data.ru_product.title,
                        short_description: response.data.ru_product.short_description,
                        text: response.data.ru_product.text,
                    }
                }
            })
            .catch(error =>{
                // 
            })
        },

        edit_product() {
            axios
            .post('../../api/edit_product_data/'+this.$route.params.id, {        
                data: this.data,
                change_url_title: this.change_url_title,
            })
            .then((response)=> { 
                this.go_back(true)
            })
            .catch(error =>{
                // if (error.response.status == 422) {
                //     this.us_article_error = error.response.data.errors
                // }
                // this.is_us_article_error = true
            })
        },

        get_product_category_data: function(){
            axios
            .get("../../api/product_category/")
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