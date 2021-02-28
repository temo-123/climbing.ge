<template>
<div class="col_md_12">
    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" v-on:click="save_all()" >Save all</button>
        </div>
    </div>
    <!-- {{ editing_data.ka_article['map'] }} -->
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
                <div class="form-group" v-if="this.old_data">  
                    <input type="submit" value="save global article" class="btn btn-primary" form="global_form">
                </div>
                <form name="contact-form" method="POST" id="global_form" @submit.prevent="edit_article"  style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Publish </label>
                        <div class="col-xs-8">
                            <select class="form-control" name="published" v-model="published"> 
                                
                                <!-- <option value="0" @if ($data['published'] == 0) selected="" @endif>Not public</option> -->
                                <!-- <option value="1" @if ($data['published'] == 1) selected="" @endif>Public</option> -->
                            
                                <option value="0">Not public</option> 
                                <option value="1">Public</option> 
                                
                            </select> 
                        </div>
                    </div>

                    <div class="form-group clearfix" v-if="this.category == 'event'">
                        <label for="name" class='col-xs-2 control-label'> completed </label>
                        <div class="col-xs-8">
                            <select class="form-control" name="completed" v-model="completed"> 
                                
                                <!-- <option value="0" @if ($data['published'] == 0) selected="" @endif>Not public</option> -->
                                <!-- <option value="1" @if ($data['published'] == 1) selected="" @endif>Public</option> -->
                            
                                <option value="0">No complited</option> 
                                <option value="1">Complited</option> 
                                
                            </select> 
                        </div>
                    </div>


                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Map </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <!-- <input type="text" name="map"  v-model="map" :value="editing_data.global_article['map']" class="form-control">  -->
                            <input type="text" name="map"  v-model="map" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Weather </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="weather" v-model="weather" class="form-control"> 
                        </div>
                    </div>

                    <hr>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Minimal price </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="price_from" value="price_from" v-model="price_from" class="form-control"> 
                            <!-- <ckeditor :editor="editor" v-model="us_price_from" :config="editorConfig"></ckeditor> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Working time </label>
                        <div class="col-xs-8">
                            <input type="text" name="time" value="time" v-model="working_time" class="form-control"> 
                        </div>
                    </div>

                    <hr>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Start data </label>
                        <div class="col-xs-4">
                            <input type="text" name="start_data_day" v-model="start_data_day"  class="form-control"> 
                        </div>
                        <div class="col-xs-4">
                            <input type="text" name="start_data_month" v-model="start_data_month"  class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> End data </label>
                        <div class="col-xs-4">
                            <input type="text" name="and_data_day" v-model="and_data_day"  class="form-control"> 
                        </div>
                        <div class="col-xs-4">
                            <input type="text" name="and_data_month" v-model="and_data_month"  class="form-control"> 
                        </div>
                    </div>

                    <hr>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> facebook / twitter </label>
                        <div class="col-xs-4">
                            <input type="text" name="fb_link"  v-model="fb_link" class="form-control"> 
                        </div>
                        <div class="col-xs-4">
                            <input type="text" name="twit_link"  v-model="twit_link" class="form-control"> 
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> google / instagram </label>
                        <div class="col-xs-4">
                            <input type="text" name="google_link"  v-model="google_link" class="form-control"> 
                        </div>
                        <div class="col-xs-4">
                            <input type="text" name="inst_link"  v-model="inst_link" class="form-control"> 
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> website </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="web_link" v-model="web_link" class="form-control"> 
                        </div>
                    </div>

                    <!-- <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> image </label>
                        <div class="col-xs-4">                    
                            <input type="file" name="fileToUpload" id="fileToUpload">
                        </div>
                        <div class="col-md-4">
                            <img :src="'/public/images/' + category + '_img/'+this.image_name" alt="article image">
                        </div>
                    </div> -->

                </form>

                <form @submit="checkForm" ref="myForm">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> image </label>
                            <div class="col-xs-4">     
                                <input type="file" name="profile_pic" id="profile_pic">               
                            </div>
                            <div class="col-xs-4">
                                <img :src="'/public/images/' + category + '_img/'+this.image_name" alt="article image">
                            </div>
                        </div>
                </form>
            </div>

            <input type="radio" name="tabs" id="2">
            <label for="2" >english article</label>
            <div class="tab" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} article english information</h2>
                        <p class="lead">Article english information.</p>
                    </div>
                </div>
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save georgian article" class="btn btn-primary" form="global_form">
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="us_title" v-model="us_title" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control">gggggggggg</textarea> -->
                            <ckeditor :editor="editor" v-model="us_short_description" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="us_text" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Routes description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="us_route" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="route" name="route" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> How to get hear </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="us_how_get" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="how_get" name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Best time for climbing </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="us_best_time" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="best_time" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> what you need </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="us_what_need" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="what_need" name="what_need" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Info / contact </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="us_info" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Price description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="us_price_text" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="prixe_text" name="prixe_text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <hr>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Meta keyword </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="meta_keyword" value="meta_keyword" v-model="us_meta_keyword" class="form-control"> 
                        </div>
                    </div>

                </form>
            </div>

            <input type="radio" name="tabs" id="3">
            <label for="3" >russian article</label>
            <div class="tab">
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save rusian article" class="btn btn-primary" form="global_form">
                </div>

                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} article russion information</h2>
                        <p class="lead">Article russion information.</p>
                    </div>
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="value name" v-model="ru_title" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_short_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_text" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Routes description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_route" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="route" name="route" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> How to get hear </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_how_get" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="how_get" name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Best time for climbing </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_best_time" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="best_time" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> what you need </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_what_need" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="what_need" name="what_need" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Info / contact </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_info" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <!-- <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Working time </label>
                        <div class="col-xs-8">
                            <input type="text" name="value name" value="old data" class="form-control">
                            <input type="text" name="time" value="time" class="form-control"> 
                        </div>
                    </div> -->

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Price description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ru_price_text" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="prixe_text" name="prixe_text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <!-- <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Minimal price </label>
                        <div class="col-xs-8">
                            <input type="text" name="value name" value="old data" class="form-control">
                            <input type="text" name="price_from" value="price_from" class="form-control"> 
                        </div>
                    </div> -->

                    <hr>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Meta keyword </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="meta_keyword" v-model="ru_meta_keyword" value="meta_keyword" class="form-control"> 
                        </div>
                    </div>

                </form>
            </div>

            <input type="radio" name="tabs" id="4">
            <label for="4" >georgian article</label>
            <div class="tab">
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save anglish article" class="btn btn-primary" form="global_form">
                </div>

                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} article georgian information</h2>
                        <p class="lead">Article georgian information.</p>
                    </div>
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="value name" v-model="ka_title" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_short_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_text" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Routes description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_route" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="route" name="route" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> How to get hear </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_how_get" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="how_get" name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Best time for climbing </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_best_time" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="best_time" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> what you need </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_what_need" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="what_need" name="what_need" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Info / contact </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_info" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <!-- <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Working time </label>
                        <div class="col-xs-8">
                            <input type="text" name="time" value="time" class="form-control"> 
                        </div>
                    </div> -->

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Price description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ka_price_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="prixe_text" name="prixe_text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <!-- <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Minimal price </label>
                        <div class="col-xs-8">
                            <input type="text" name="price_from" value="price_from" class="form-control"> 
                        </div>
                    </div> -->

                    <hr>


                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Meta keyword </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="meta_keyword" value="meta_keyword" v-model='ka_meta_keyword' class="form-control"> 
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    // import Form from 'vform';

    export default {
        props: [
            'back_url',
            'category',
            'editing_article_id'
        ],
        data(){
            return {
                editor: 'editor',
                editorConfig:{},

                editing_url: '/articles/get_editing_data/',
                url: '',

                editing_data: '',

                us_title_for_url_title: "",
                published: "",
                completed: "",
                map: "",
                weather: "",
                start_data_day: "",
                and_data_day: "",
                and_data_month: "",
                and_data_month: "",
                fb_link: "",
                twit_link: "",
                google_link: "",
                inst_link: "",
                web_link: "",
                working_time: "",
                price_from: "",

                name: '',
                image_name: '',
                success: '',


                // 
                // 
                // 
                us_title: "",
                us_short_description: "",
                us_text: "",
                us_route: "",
                us_how_get: "",
                us_best_time: "",
                us_what_need: "",
                us_info: "",
                us_meta_keyword: "",


                // 
                // 
                // 
                ka_title: "",
                ka_short_description: "",
                ka_text: "",
                ka_route: "",
                ka_how_get: "",
                ka_best_time: "",
                ka_what_need: "",
                ka_info: "",
                ka_meta_keyword: "",


                // 
                // 
                // 
                ru_title: "",
                ru_short_description: "",
                ru_text: "",
                ru_route: "",
                ru_how_get: "",
                ru_best_time: "",
                ru_what_need: "",
                ru_info: "",
                ru_meta_keyword: "",

                us_article_id: "",
                ru_article_id: "",
                ka_article_id: "",
            }
        },
    
        mounted() {
            this.get_editing_data()
        },

        methods: {

            edit_global_article() {
                axios
                .post('/articles/global/edit/' + this.editing_article_id, {
                    us_title_for_url_title: this.us_title,

                    published: this.published,
                    completed: this.completed,
                    map: this.map,
                    weather: this.weather,

                    start_data_day: this.start_data_day,
                    and_data_day: this.and_data_day,
                    start_data_month: this.start_data_month,
                    and_data_month: this.and_data_month,

                    working_time: this.working_time,
                    price_from: this.price_from,

                    fb_link: this.fb_link,
                    twit_link: this.twit_link,
                    google_link: this.google_link,
                    inst_link: this.inst_link,
                    web_link: this.web_link,
                })
                .then(Response => {
                    console.log(response)
                })
                .catch(error => console.log(error))
            },
            edit_ru_article() {
                axios
                .post('/articles/ru/edit/' + this.ru_article_id, {
                    ru_title: this.ru_title,
                    ru_short_description: this.ru_short_description,
                    ru_text: this.ru_text,
                    ru_route: this.ru_route,
                    ru_how_get: this.ru_how_get,
                    ru_best_time: this.ru_best_time,
                    ru_what_need: this.ru_what_need,
                    ru_info: this.ru_info,
                    ru_meta_keyword: this.ru_meta_keyword,
                })
                .then(Response => {
                    // alert("Article editied"),
                    // this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            },
            edit_us_article() {
                axios
                .post('/articles/us/edit/' + this.us_article_id, {
                    us_title: this.us_title,
                    us_short_description: this.us_short_description,
                    us_text: this.us_text,
                    us_route: this.us_route,
                    us_how_get: this.us_how_get,
                    us_best_time: this.us_best_time,
                    us_what_need: this.us_what_need,
                    us_info: this.us_info,
                    us_meta_keyword: this.us_meta_keyword,
                })
                .then(Response => {
                    // alert("Article editied"),
                    // this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            },
            edit_ka_article() {
                axios
                .post('/articles/ka/edit/' + this.ka_article_id, {
                    ka_title: this.ka_title,
                    ka_short_description: this.ka_short_description,
                    ka_text: this.ka_text,
                    ka_route: this.ka_route,
                    ka_how_get: this.ka_how_get,
                    ka_best_time: this.ka_best_time,
                    ka_what_need: this.ka_what_need,
                    ka_info: this.ka_info,
                    ka_meta_keyword: this.ka_meta_keyword,
                })
                .then(Response => {
                    // alert("Article editied"),
                    this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            },

            checkForm: function (e) {
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '/articles/update_image/' + this.editing_article_id,
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    // this.is_image_succes = 1;
                    // alert(response.data.message);
                });
                // e.preventDefault();
            },

            get_editing_data() {
                this.url = this.editing_url + this.editing_article_id

                axios
                .get(this.url)
                .then(response => {
                    this.editing_data = response.data
                    
                    this.us_article_id = this.editing_data.global_article['us_article_id'],
                    this.ru_article_id = this.editing_data.global_article['ru_article_id'],
                    this.ka_article_id = this.editing_data.global_article['ka_article_id'],
                    
                    // send data in editing form value
                    this.published = this.editing_data.global_article['published'],
                    this.completed = this.editing_data.global_article['completed'],
                    this.map = this.editing_data.global_article['map'],
                    this.weather = this.editing_data.global_article['weather'],
                    this.start_data_day = this.editing_data.global_article['start_data_day'],
                    this.and_data_day = this.editing_data.global_article['and_data_day'],
                    this.start_data_month = this.editing_data.global_article['start_data_month'],
                    this.and_data_month = this.editing_data.global_article['and_data_month'],
                    this.fb_link = this.editing_data.global_article['fb_link'],
                    this.twit_link = this.editing_data.global_article['twit_link'],
                    this.google_link = this.editing_data.global_article['google_link'],
                    this.inst_link = this.editing_data.global_article['inst_link'],
                    this.web_link = this.editing_data.global_article['web_link'],
                    this.us_price_from = this.editing_data.global_article['price_from'],
                    this.image_name = this.editing_data.global_article['image']

                    this.price_from = this.editing_data.global_article['price_from']
                    this.working_time = this.editing_data.global_article['working_time']
                    
                    // 
                    // 
                    // 
                    this.us_title = this.editing_data.us_article['title'],
                    this.us_short_description = this.editing_data.us_article['short_description'],
                    this.us_text = this.editing_data.us_article['text'],
                    this.us_route = this.editing_data.us_article['route'],
                    this.us_how_get = this.editing_data.us_article['how_get'],
                    this.us_best_time = this.editing_data.us_article['best_time'],
                    this.us_what_need = this.editing_data.us_article['what_need'],
                    this.us_info = this.editing_data.us_article['info'],
                    this.us_meta_keyword = this.editing_data.us_article['meta_keyword'],

                    // 
                    // 
                    // 
                    this.ru_title = this.editing_data.ru_article['title'],
                    this.ru_short_description = this.editing_data.ru_article['short_description'],
                    this.ru_text = this.editing_data.ru_article['text'],
                    this.ru_route = this.editing_data.ru_article['route'],
                    this.ru_how_get = this.editing_data.ru_article['how_get'],
                    this.ru_best_time = this.editing_data.ru_article['best_time'],
                    this.ru_what_need = this.editing_data.ru_article['what_need'],
                    this.ru_info = this.editing_data.ru_article['info'],
                    this.ru_meta_keyword = this.editing_data.ru_article['meta_keyword']

                    // 
                    // 
                    // 
                    this.ka_title = this.editing_data.ka_article['title'],
                    this.ka_short_description = this.editing_data.ka_article['short_description'],
                    this.ka_text = this.editing_data.ka_article['text'],
                    this.ka_route = this.editing_data.ka_article['route'],
                    this.ka_how_get = this.editing_data.ka_article['how_get'],
                    this.ka_best_time = this.editing_data.ka_article['best_time'],
                    this.ka_what_need = this.editing_data.ka_article['what_need'],
                    this.ka_info = this.editing_data.ka_article['info'],
                    this.ka_meta_keyword = this.editing_data.ka_article['meta_keyword']
                })
                .catch(
                    error => console.log(error)
                );
            },

            save_all() {
                this.edit_global_article();
                this.edit_us_article();
                this.edit_ka_article();
                this.edit_ru_article();

                this.checkForm();

                window.location.href = this.back_url;
            }
        }
    }
</script>
