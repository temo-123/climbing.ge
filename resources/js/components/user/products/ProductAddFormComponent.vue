<template>
<div class="col_md_12">
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
                        <h2 class="display-4">{{this.category}} article global information</h2>
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
                            <select class="form-control" v-model="published" name="published" > 
                                <option value="GEL">GEL</option> 
                                <option value="USD">USD</option> 
                                <option value="EUR">EUR</option> 
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> category </label>
                        <div class="col-xs-8">
                            <select class="form-control" v-model="published" name="published" > 
                                <option value="climbing">climbing</option> 
                                <option value="mountaineering">mountaineering</option> 
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
                                    <button class="btn btn-primary" @click="add_sector_image(temporary_sector_id)">seve image</button>
                                    </div>
                                </div>
                                <div class="col-md-6">    
                                    <form @submit="add_sector_image(temporary_sector_id)"  ref="myForm">
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
                            <button @click="get_sector_image(temporary_sector_id)" class="btn main-btn pull-right" v-if="!image_is_refresh">Refresh ({{image_reset_id}})</button>
                            <span class="badge badge-primare mb-1 pull-right" v-if="image_is_refresh">Updating...</span>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                        <img alt="300x200" @click="showModal()" src="/public/images/site_img/image.png">
                        </div>
                    </div>
                    </div>
                    <div class="col-md-3" v-for="image in images" :key="image.id">
                    <div class="row">
                        <div class="col-md-12">
                        <img :alt="'sector image - (' + image.image + ')'" class="sector_img" :src="'/public/images/sector_img/' + image.image">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <button class="btn btn-secondary pull-left" @click="edit_image()">Edit</button>
                        </div>
                        <div class="col-md-6">
                        <button class="btn btn-danger pull-right" @click="del_sector_image(image.id)">Del</button>
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
                        <h2 class="display-4">{{this.category}} article english version</h2>
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
                        <h2 class="display-4">{{this.category}} article rusion version</h2>
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
                        <h2 class="display-4">{{this.category}} article georgian version</h2>
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
            'category'
        ],
        data(){
            return {
                us_title_for_url_title: '',
                published: "",

                name: '',
                image: '',
                success: '',


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
            }
        },
        mounted() {
            this.create_temporary_product()
        },
        methods: {
            create_temporary_product() {
                axios
                .post('/products/create_temporary_product/', {
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
                .post('/products/us/add/' + this.category, {        
                    us_title: this.us_title,
                    us_short_description: this.us_short_description,
                    us_text: this.us_text,
                    us_route: this.us_route,
                    us_how_get: this.us_how_get,
                    us_best_time: this.us_best_time,
                    us_what_need: this.us_what_need,
                    us_info: this.us_info,
                    us_time: this.us_time,
                    us_price_text: this.us_price_text,
                    us_price_from: this.us_price_from,
                    us_meta_keyword: this.us_meta_keyword,
                })
                .then((response)=> { 
                    // console.log(response)
                    // this.is_us_product_succes = 1
                    // console.log('englihs product upload successful');

                    this.add_ru_product()
                })
                .catch(error => console.log(error))
            },
            add_ru_product() {
                axios
                .post('/products/ru/add/' + this.category, {
                    ru_title: this.ru_title,
                    ru_short_description: this.ru_short_description,
                    ru_text: this.ru_text,
                    ru_route: this.ru_route,
                    ru_how_get: this.ru_how_get,
                    ru_best_time: this.ru_best_time,
                    ru_what_need: this.ru_what_need,
                    ru_info: this.ru_info,
                    ru_time: this.ru_time,
                    ru_price_text: this.ru_price_text,
                    ru_price_from: this.ru_price_from,
                    ru_meta_keyword: this.ru_meta_keyword,
                })
                .then((response)=> { 
                    // this.is_ru_product_succes = 1
                    // console.log(this.is_ru_product_succes);
                    // console.log(response) 
                    // console.log('rusian product upload successful');
                    this.add_ka_product()
                })
                .catch(error => console.log(error))
            },
            add_ka_product() {
                axios
                .post('/products/ka/add/' + this.category, {
                    ka_title: this.ka_title,
                    ka_short_description: this.ka_short_description,
                    ka_text: this.ka_text,
                    ka_route: this.ka_route,
                    ka_how_get: this.ka_how_get,
                    ka_best_time: this.ka_best_time,
                    ka_what_need: this.ka_what_need,
                    ka_: this.ka_,
                    ka_time: this.ka_time,
                    ka_price_text: this.ka_price_text,
                    ka_price_from: this.ka_price_from,
                    ka_meta_keyword: this.ka_meta_keyword,
                })
                .then((response)=>  {
                    // console.log(response)
                    // this.is_ka_product_succes = 1
                    // console.log('georgian product upload successful');

                    this.add_global_product()
                })
                .catch(error => console.log(error))
            },
            add_global_product() {
                // var myFormData = new FormData(this.$refs.myForm)
                // console.log(myFormData);

                axios
                .post('/products/global/add/' + this.category, {
                    us_title_for_url_title: this.us_title,

                    published: this.published,
                    completed: this.completed,
                    map: this.map,
                    weather: this.weather,

                    start_data_day: this.start_data_day,
                    and_data_day: this.and_data_day,
                    start_data_month: this.start_data_month,
                    and_data_month: this.and_data_month,

                    fb_link: this.fb_link,
                    twit_link: this.twit_link,
                    google_link: this.google_link,
                    inst_link: this.inst_link,
                    web_link: this.web_link,

                    // image: this.image,

                    // data: myFormData,
                    // config: { 
                    //     headers: {
                    //         'Content-Type': 'multipart/form-data' 
                    //     }
                    // },
                })
                .then((response)=>  { 
                    // this.is_global_product_succes = 1
                    // console.log(response)
                    // alert(response.data.message);
                    // console.log('global product upload successful');

                    this.checkForm()
                })
                .catch(
                    error => console.log(error)
                )
            },

            checkForm: function (e) {
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '/articles/upload_image/',
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    // this.is_image_succes = 1;
                    // alert(response.data.message);
                });
                // e.preventDefault(); // if this line is not comentid method - (window.location.href) dase not work in method (save_all)
            },

            save_all() {
                this.add_us_article()

                window.location.href = this.back_url;
            }
        }
    }
</script>
