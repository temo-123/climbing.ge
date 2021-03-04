<template>
<div class="col_md_12">
    <div class="row">
      <div class="form-group">
        <button type="submit" class="btn btn-primary" v-on:click="back(temporary_product_id)">Beck</button>
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

                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="add_article"  style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Publish </label>
                        <div class="col-xs-8">
                            <select class="form-control" v-model="published" name="published" > 
                                <option value="0">Not public</option> 
                                <option value="1">Public</option> 
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> price </label>
                        <div class="col-xs-6">
                            <input type="text" v-model="price" name="price" class="form-control"> 
                        </div>

                        <div class="col-xs-2">
                            <select class="form-control" v-model="currency" name="currency" > 
                                <option value="GEL">GEL</option> 
                                <option value="USD">USD</option> 
                                <option value="EUR">EUR</option> 
                            </select> 
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
                                <option value="white">White</option>
                                <option value="black">Black</option>
                                <option value="brown">Brown</option>
                                <option value="red">Red</option>
                                <option value="orange">Orange</option>
                                <option value="yellow">Yellow</option>
                                <option value="green">Green</option>
                                <option value="blue">Blue</option>
                                <option value="purple">Purple</option>
                                <option value="grey">Grey</option>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> category_id </label>
                        <div class="col-xs-8">
                            <select class="form-control" v-model="category_id" name="category_id" > 
                                <option  v-for="cat in categories" :key="cat.id" v-bind:value="cat.id"> {{ cat.us_name }}</option>
                            </select> 
                        </div>
                    </div>


                </form>


                <div class="container">
                    <div class="row">
                        <div class="card"  v-if='myModal'>
                            <div class="card-header">
                                Add image
                            </div>
                            <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group clearfix">
                                    <button class="btn btn-primary" @click="add_product_image(temporary_product_id)">seve image</button>
                                    </div>
                                </div>
                                <div class="col-md-6">    
                                    <form @submit="add_product_image(temporary_product_id)"  ref="myForm">
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
                        <button @click="get_product_image(temporary_product_id)" class="btn main-btn pull-right" v-if="!image_is_refresh">Refresh ({{image_reset_id}})</button>
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
                <form name="contact-form" method="POST" @submit.prevent="add_us_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <input type="text" name="name" v-model="us_title"  class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text" name="short_description" v-model="us_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_short_description" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="text" rows="15" v-model="us_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_text" :config="editorConfig"></ckeditor>
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

                <form name="contact-form" method="POST" @submit.prevent="add_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="title" v-model="ru_title" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="short_description" v-model="ru_short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_short_description" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="text" rows="15"  v-model="ru_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_text" :config="editorConfig"></ckeditor>
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

                <form name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="value name"  v-model="ka_title" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="short_description"  v-model="ka_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_short_description" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="txt" rows="15"  v-model="ka_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_text" :config="editorConfig"></ckeditor>
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
            // 'category_id'
        ],
        data(){
            return {
                image_is_refresh: false,
                image_reset_id: 0,

                categories: [],

                us_title_for_url_title: '',

                published: "",
                currency: "",
                price: "",
                category_id: "",
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


                myModal: false,

                temporary_product_id: ""
            }
        },
        mounted() {
            this.create_temporary_product()
            this.get_product_category_data()
        },
        methods: {
            create_temporary_product() {
                axios
                .post('/products/create_temporary_product/', {
                })
                .then((response)=>  {
                    this.get_temporary_product_data()
                    // console.log('georgian product upload successful');
                })
                .catch(error => console.log(error))
            },
            del_temporary_product(temporary_product_id) {
                axios
                .post('/products/del_temporary_product/' + temporary_product_id, {
                    // ka_title: this.ka_title,
                })
                .then((response)=>  {
                    // console.log(response)
                    // this.is_ka_product_succes = 1
                    // console.log('georgian product upload successful');
                })
                .catch(error => console.log(error))
            },

            showModal(){
                this.myModal = !this.myModal
            },

            add_us_product() {
                axios
                .post('/products/us/add/', {        
                    us_title: this.us_title,
                    us_short_description: this.us_short_description,
                    us_text: this.us_text,
                    us_meta_keyword: this.us_meta_keyword,
                })
                .then((response)=> { 
                    // this.add_ru_product()
                })
                .catch(error => console.log(error))
            },
            add_ru_product() {
                axios
                .post('/products/ru/add/', {
                    ru_title: this.ru_title,
                    ru_short_description: this.ru_short_description,
                    ru_text: this.ru_text,
                    ru_meta_keyword: this.ru_meta_keyword,
                })
                .then((response)=> { 
                })
                .catch(error => console.log(error))
            },
            add_ka_product() {
                axios
                .post('/products/ka/add/', {
                    ka_title: this.ka_title,
                    ka_short_description: this.ka_short_description,
                    ka_text: this.ka_text,
                    ka_meta_keyword: this.ka_meta_keyword,
                })
                .then((response)=>  {
                    // this.add_global_product()
                })
                .catch(error => console.log(error))
            },
            add_global_product() {
                // var myFormData = new FormData(this.$refs.myForm)
                // console.log(myFormData);

                axios
                .post('/products/global/add/', {
                    published: this.published,
                    price: this.price,
                    currency: this.currency,
                    category_id: this.category_id,

                    material: this.material,
                    color: this.color,
                    discount: this.discount,
                    quantity: this.quantity,
                    size: this.size,

                    us_title_for_url_title: this.us_title,
                })
                .then((response)=>  { 
                    // this.is_global_product_succes = 1
                    // console.log(response)
                    // alert(response.data.message);
                    // console.log('global product upload successful');

                    // this.checkForm()
                })
                .catch(
                    error => console.log(error)
                )
            },

            get_temporary_product_data: function(){
                axios
                .get("/products/get_temporary_product_editing_data/")
                .then(response => {
                    this.editing_data = response.data
                    this.get_product_image(this.editing_data.last_temporary_product_id)
                    // console.log(this.editing_data.last_temporary_product_id);
                    this.temporary_product_id = this.editing_data.last_temporary_product_id
                })
                .catch(
                error => console.log(error)
                );
            },

            get_product_category_data: function(){
                axios
                .get("../../products/get_product_category_data/")
                .then(response => {
                    this.product_category = response.data
                    this.categories = this.product_category
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_product_image: function (temporary_product_id) {
                this.image_is_refresh = true
                axios
                .get("../../products/get_product_image/" + temporary_product_id )
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
            add_product_image: function (temporary_product_id) {
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '/products/upload_product_image/' + temporary_product_id,
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


            back: function(temporary_product_id) {
                confirm('Are you sure, you want go back?')
                this.del_temporary_product(temporary_product_id)
                window.location.href = this.back_url;
            },
            save_all() {
                this.add_us_product()
                this.add_ka_product()
                this.add_ru_product()
                this.add_global_product()

                window.location.href = this.back_url;
            }
        }
    }
</script>
