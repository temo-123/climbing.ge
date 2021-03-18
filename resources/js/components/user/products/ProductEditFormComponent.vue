<template>
<div class="col_md_12">
    <div class="row">
      <div class="form-group">
        <button type="submit" class="btn btn-primary" v-on:click="back()">Beck</button>
      </div>
    </div>
    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" v-on:click="save_all()" >Save</button>
        </div>
    </div>
    <div class="row">
        <div class="tabs">

            <input type="radio" name="tabs" id="1" checked="checked">
            <label for="1" >global info</label>
            <div class="tab" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">Product global information</h2>
                        <p class="lead">Article global information.</p>
                    </div>
                </div>

                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="edit_article"  style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Publish </label>
                        <div class="col-xs-8">
                            <select class="form-control" v-model="published" name="published" > 
                                <option value="0">Not public</option> 
                                <option value="1">Public</option> 
                            </select> 
                            <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                {{ global_article_error.published[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> price </label>
                        <div class="col-xs-6">
                            <input type="text" v-model="price" name="price" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="global_article_error.price">
                                {{ global_article_error.price[0] }}
                            </div>
                        </div>

                        <div class="col-xs-2">
                            <select class="form-control" v-model="currency" name="currency" > 
                                <option value="GEL">GEL</option> 
                                <option value="USD">USD</option> 
                                <option value="EUR">EUR</option> 
                            </select> 
                            <div class="alert alert-danger" role="alert" v-if="global_article_error.currency">
                                {{ global_article_error.currency[0] }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> discount (%) </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="discount" name="discount" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> material</label>
                        <div class="col-xs-8">
                            <input type="text" v-model="material" name="material" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> quantity </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="quantity" name="quantity" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> size </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="size" name="size" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> color </label>
                        <div class="col-xs-8">
                            <select class="form-control" v-model="color" name="color" > 
                                <option value="White">White</option>
                                <option value="Black">Black</option>
                                <option value="Brown">Brown</option>
                                <option value="Red">Red</option>
                                <option value="Orange">Orange</option>
                                <option value="Yellow">Yellow</option>
                                <option value="Green">Green</option>
                                <option value="Blue">Blue</option>
                                <option value="Purple">Purple</option>
                                <option value="Grey">Grey</option>
                            </select> 
                        </div>
                    </div>


                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> category </label>
                        <div class="col-xs-8">
                            <select class="form-control" v-model="category_id"> 
                                <option  v-for="cat in categories" :key="cat.id" v-bind:value="cat.id"> {{ cat.us_name }}</option>
                            </select> 
                            <div class="alert alert-danger" role="alert" v-if="global_article_error.category_id">
                                {{ global_article_error.category_id[0] }}
                            </div>
                        </div>
                    </div>


                </form>


                <div class="container">
                    <div class="row">
                        <div class="card"  v-if='myModal'>
                            <div class="card-header">
                                edit image
                            </div>
                            <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group clearfix">
                                    <button class="btn btn-primary" @click="add_product_image()">seve image</button>
                                    </div>
                                </div>
                                <div class="col-md-6">    
                                    <form @submit="add_product_image()"  ref="myForm">
                                    <div class="form-group clearfix">
                                        <input type="file" name="profile_pic" id="profile_pic" >
                                    </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="form-groupe">
                        <button class="btn btn-primary" @click="showModal()">My modal</button>
                    </div>
                    <div class="form-groupe">
                        <button @click="get_product_image()" class="btn main-btn pull-right" v-if="!image_is_refresh">Refresh ({{image_reset_id}})</button>
                        <span class="badge badge-primare mb-1 pull-right" v-if="image_is_refresh">Updating...</span>
                    </div>
                    </div>
                    
                </div>

                <div class="container">
                    <!-- <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                        <img alt="300x200" @click="showModal()" src="/public/images/site_img/image.png">
                        </div>
                    </div>
                    </div> -->
                    <div class="col-md-3" v-for="image in images" :key="image.id">
                    <div class="row">
                        <div class="col-md-12">
                        <img :alt="'product image - (' + image.image + ')'" class="product_img" :src="'/public/images/product_img/' + image.image">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <button class="btn btn-secondary pull-left" @click="edit_image()">Edit</button>
                        </div>
                        <div class="col-md-6">
                        <button class="btn btn-danger pull-right" @click="del_product_image(image.id)">Del</button>
                        </div>
                    </div>
                    </div>
                </div>

            </div>

            <input type="radio" name="tabs" id="2">
            <label for="2" >english article</label>
            <div class="tab" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">Product english version</h2>
                        <p class="lead">Article english version for site localisation.</p>
                    </div>
                </div>
                <form name="contact-form" method="POST" @submit.prevent="edit_us_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <input type="text" name="name" v-model="us_title"  class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="us_article_error.us_title">
                                {{ us_article_error.us_title[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text" name="short_description" v-model="us_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_short_description" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="us_article_error.us_short_description">
                                {{ us_article_error.us_short_description[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="text" rows="15" v-model="us_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_text" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="us_article_error.us_text">
                                {{ us_article_error.us_text[0] }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Meta keyword </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="meta_keyword" value="meta_keyword" v-model="us_meta_keyword"  class="form-control"> 
                        </div>
                    </div>

                </form>
            </div>

            <input type="radio" name="tabs" id="3">
            <label for="3" >rusian article</label>
            <div class="tab">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">Product rusion version</h2>
                        <p class="lead">Article rusion version for site localisation.</p>
                    </div>
                </div>

                <form name="contact-form" method="POST" @submit.prevent="edit_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="title" v-model="ru_title" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_title">
                                {{ ru_article_error.ru_title[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="short_description" v-model="ru_short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_short_description" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_short_description">
                                {{ ru_article_error.ru_short_description[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="text" rows="15"  v-model="ru_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_text" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_text">
                                {{ ru_article_error.ru_text[0] }}
                            </div>
                        </div>
                    </div>


                    <hr>


                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Meta keyword </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="meta_keyword" value="meta_keyword"  v-model="ru_meta_keyword" class="form-control"> 
                        </div>
                    </div>

                </form>
            </div>

            <input type="radio" name="tabs" id="4">
            <label for="4" >georgian article</label>
            <div class="tab">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">Product georgian version</h2>
                        <p class="lead">Article georgian version for site localisation.</p>
                    </div>
                </div>

                <form name="contact-form" method="POST" @submit.prevent="edit_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="value name"  v-model="ka_title" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_title">
                                {{ ka_article_error.ka_title[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="short_description"  v-model="ka_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_short_description" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_short_description">
                                {{ ka_article_error.ka_short_description[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="txt" rows="15"  v-model="ka_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_text" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_text">
                                {{ ka_article_error.ka_text[0] }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Meta keyword </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="meta_keyword"  v-model="ka_meta_keyword" class="form-control"> 
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
            'editing_article_id'
        ],
        data(){
            return {
                global_article_error: [],
                is_global_article_error: true,
                ka_article_error: [],
                is_ka_article_error: true,
                ru_article_error: [],
                is_ru_article_error: true,
                us_article_error: [],
                is_us_article_error: true,

                image_is_refresh: false,
                image_reset_id: 0,

                categories: [],

                us_title_for_url_title: '',

                published: "",
                currency: "",
                price: "",
                category: "",
                material: "",
                color: "",
                discount: "",
                quantity: "",


                // 
                // 
                // 
                us_title: "",
                us_short_description: "",
                us_text: "",
                us_meta_keyword: "",


                // 
                // 
                // 
                ka_title: "",
                ka_short_description: "",
                ka_text: "",
                ka_meta_keyword: "",


                // 
                // 
                // 
                ru_title: "",
                ru_short_description: "",
                ru_text: "",
                ru_meta_keyword: "",


                // 
                // 
                // 
                us_product_id: 0,
                ru_product_id: 0,
                ka_product_id: 0,

                myModal: false,

                // category_id: ""
            }
        },
        mounted() {
            this.get_product_editing_data()
            this.get_product_category_data()
            this.get_product_image()
        },
        methods: {
            get_product_editing_data() {
                axios
                .post('/products/get_product_editing_data/' + this.editing_article_id, {
                    published: this.published,
                })
                .then((response)=>  {
                    this.editing_product = response.data,

                    this.published = this.editing_product.editing_product['published'],
                    this.price = this.editing_product.editing_product['price'],
                    this.currency = this.editing_product.editing_product['currency'],
                    this.category_id = this.editing_product.editing_product['category_id'],

                    this.material = this.editing_product.editing_product['material'],
                    this.color = this.editing_product.editing_product['color'],
                    this.discount = this.editing_product.editing_product['discount'],
                    this.quantity = this.editing_product.editing_product['quantity'],
                    this.size = this.editing_product.editing_product['size'],

                    this.us_product_id = this.editing_product.editing_product['us_product_id'],
                    this.ru_product_id = this.editing_product.editing_product['ru_product_id'],
                    this.ka_product_id = this.editing_product.editing_product['ka_product_id'],
                    
                    this.us_title = this.editing_product.editing_product_us['title'],
                    this.us_short_description = this.editing_product.editing_product_us['short_description'],
                    this.us_text = this.editing_product.editing_product_us['text'],
                    this.us_meta_keyword = this.editing_product.editing_product_us['meta_keyword'],
                    
                    this.ru_title = this.editing_product.editing_product_ru['title'],
                    this.ru_short_description = this.editing_product.editing_product_ru['short_description'],
                    this.ru_text = this.editing_product.editing_product_ru['text'],
                    this.ru_meta_keyword = this.editing_product.editing_product_ru['meta_keyword'],
                    
                    this.ka_title = this.editing_product.editing_product_ka['title'],
                    this.ka_short_description = this.editing_product.editing_product_ka['short_description'],
                    this.ka_text = this.editing_product.editing_product_ka['text'],
                    this.ka_meta_keyword = this.editing_product.editing_product_ka['meta_keyword']
                })
                .catch(error => console.log(error))
            },

            showModal(){
                this.myModal = !this.myModal
            },

            edit_us_product() {
                axios
                .post('/products/us/edit/' + this.us_product_id, {        
                    us_title: this.us_title,
                    us_short_description: this.us_short_description,
                    us_text: this.us_text,
                    us_meta_keyword: this.us_meta_keyword,
                })
                .then((response)=> { 
                    this.is_us_article_error = false
                    this.if_isset_go_beck(this.is_us_article_error)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.us_article_error = error.response.data.errors
                    }
                    this.is_us_article_error = true
                })
            },
            edit_ru_product() {
                axios
                .post('/products/ru/edit/' + this.ru_product_id, {
                    ru_title: this.ru_title,
                    ru_short_description: this.ru_short_description,
                    ru_text: this.ru_text,
                    ru_meta_keyword: this.ru_meta_keyword,
                })
                .then((response)=> { 
                    this.is_ru_article_error = false
                    this.if_isset_go_beck(this.is_ru_article_error)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.ru_article_error = error.response.data.errors
                    }
                    this.is_ru_article_error = true
                })
            },
            edit_ka_product() {
                axios
                .post('/products/ka/edit/' + this.ka_product_id, {
                    ka_title: this.ka_title,
                    ka_short_description: this.ka_short_description,
                    ka_text: this.ka_text,
                    ka_meta_keyword: this.ka_meta_keyword,
                })
                .then((response)=>  {
                    this.is_ka_article_error = false
                    this.if_isset_go_beck(this.is_ka_article_error)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.ka_article_error = error.response.data.errors
                    }
                    this.is_ka_article_error = true
                })
            },
            edit_global_product() {
                axios
                .post('/products/global/edit/'+ this.editing_article_id, {
                    published: this.published,
                    price: this.price,
                    currency: this.currency,
                    category_id: this.category_id,
                    us_title_for_url_title: this.us_title,

                    material: this.material,
                    color: this.color,
                    discount: this.discount,
                    quantity: this.quantity,
                    size: this.size,
                })
                .then((response)=>  { 
                    this.is_global_article_error = false
                    this.if_isset_go_beck(this.is_global_article_error)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.global_article_error = error.response.data.errors
                    }
                    this.is_global_article_error = true
                })
            },


            get_product_category_data: function(){
                axios
                .get("../../products/get_product_category_data/")
                .then(response => {
                    this.categories = response.data
                    // console.log();
                    // this.category = this.product_category
                })
                .catch(
                    error => console.log(error)
                );
            },


            get_product_image: function () {
                this.image_is_refresh = true
                axios
                .get("/products/get_product_image/" + this.editing_article_id )
                .then(response => {
                    this.product_images = response.data
                    this.images = this.product_images.product_images

                    this.image_is_refresh = false
                    this.image_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            add_product_image: function () {
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '/products/upload_product_image/' + this.editing_article_id,
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    // this.is_image_succes = 1;
                    // alert(response.data.message);
                });
                this.showModal()
                // e.preventDefault();

            },
            del_product_image(imageId) {
                axios
                .post("/products/del_product_image/" + imageId, {
                    image_id: imageId,
                })
                .then(Response => {
                    console.log(response)
                    this.get_data_in_table_1()
                })
                .catch(error => console.log(error))
            },


            back: function() {
                confirm('Are you sure, you want go back?')
                window.location.href = this.back_url;
            },
            save_all() {
                this.edit_us_product()
                this.edit_ka_product()
                this.edit_ru_product()
                this.edit_global_product()
            },
            
            if_isset_go_beck() {
                if (
                    this.is_global_article_error == false &&
                    this.is_ka_article_error == false &&
                    this.is_ru_article_error == false &&
                    this.is_us_article_error == false
                ) {
                    window.location.href = this.back_url;
                }
            }
        }
    }
</script>
