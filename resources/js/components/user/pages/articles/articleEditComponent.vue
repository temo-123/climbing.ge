<template>
  <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>

        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="save_all()" >Save</button>
            </div>
        </div>
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
                <div class="ro">
                    <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_info">
                        {{ ka_article_error.ka_info[0] }}
                    </div>
                </div>
            </div>
            <div class="row" v-if="tab_num == 1">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h2 class="display-4">{{this.category}} article global information</h2>
                                <p class="lead">Edit Article global information.</p>
                            </div>
                        </div>
                        <form name="contact-form" method="POST" id="global_form" @submit.prevent="edit_article"  style="margin-top: 5%;" enctyp ="multipart/form-data">

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Publish </label>
                                <div class="col-md-8">
                                    <select class="form-control" name="published" v-model="published"> 
                                        <option value="0">Not public</option> 
                                        <option value="1">Public</option> 
                                    </select> 
                                    <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                        {{ global_article_error.published[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                                <label for="name" class='col-md-2 control-label'> Mountain </label>
                                <div class="col-md-8">
                                    <select class="form-control" v-model="mount_id" name="mount_id"> 
                                        <option  v-for="mount in mount_data" :key="mount.id" v-bind:value="mount.id">{{mount.name}}</option> 
                                    </select> 
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'event'">
                                <label for="name" class='col-md-2 control-label'> completed </label>
                                <div class="col-md-8">
                                    <select class="form-control" name="completed" v-model="completed"> 
                                        
                                        <!-- <option value="0" @if ($data['published'] == 0) selected="" @endif>Not public</option> -->
                                        <!-- <option value="1" @if ($data['published'] == 1) selected="" @endif>Public</option> -->
                                    
                                        <option value="0">No complited</option> 
                                        <option value="1">Complited</option> 
                                        
                                    </select> 
                                </div>
                            </div>


                            <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                <label for="name" class='col-md-2 control-label'> Map </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <!-- <input type="text" name="map"  v-model="map" :value="editing_data.global_article['map']" class="form-control">  -->
                                    <input type="text" name="map"  v-model="map" class="form-control"> 
                                </div>
                            </div>


                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                <label for="name" class='col-md-2 control-label'> Weather </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="weather" v-model="weather" class="form-control"> 
                                </div>
                            </div>

                            <hr>

                            <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> Minimal price </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="price_from" value="price_from" v-model="price_from" class="form-control"> 
                                    <!-- <ckeditor :editor="editor" v-model="us_price_from" :config="editorConfig"></ckeditor> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> Working time </label>
                                <div class="col-md-8">
                                    <input type="text" name="time" value="time" v-model="working_time" class="form-control"> 
                                </div>
                            </div>

                            <hr v-if="this.category == 'event'">

                            <!-- <div class="form-group clearfix row" v-if="this.category == 'event'">
                                <label for="name" class='col-md-2 control-label'> Start data </label>
                                <div class="col-md-4">
                                    <input type="text" name="start_data_day" v-model="start_data_day"  class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="start_data_month" name="start_data_month"> 
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select> 
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'event'">
                                <label for="name" class='col-md-2 control-label'> End data </label>
                                <div class="col-md-4">
                                    <input type="text" name="end_data_day" v-model="end_data_day"  class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="end_data_month" name="end_data_month"> 
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select> 
                                </div>
                            </div> -->

                            <hr v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">

                            <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> facebook / twitter </label>
                                <div class="col-md-4">
                                    <input type="text" name="fb_link"  v-model="fb_link" class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="twit_link"  v-model="twit_link" class="form-control"> 
                                </div>
                            </div>
                            <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> google / instagram </label>
                                <div class="col-md-4">
                                    <input type="text" name="google_link"  v-model="google_link" class="form-control"> 
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="inst_link"  v-model="inst_link" class="form-control"> 
                                </div>
                            </div>
                            <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> website </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="web_link" v-model="web_link" class="form-control"> 
                                </div>
                            </div>

                        </form>

                        <form @submit="upload_region_image" ref="sectorRegionImage" v-if="this.category == 'outdoor'">
                            <div class="form-group clearfix">
                                <div class="row">
                                    <label for="email" class='col-md-5 control-label'>Update outdoor climbing area sector image:</label>
                                    <div class="col-md-4">
                                        <input type="file" name="region_sectors_img" id="region_sectors_img">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <img :src="'/public/images/region_sectors_img/'+this.region_sectors_image_name" alt="article image">
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form @submit="upload_mount_route_image" ref="mountRouteImage" v-if="this.category == 'mount_route'">
                            <div class="form-group clearfix">
                                <div class="row">
                                    <label for="email" class='col-md-5 control-label'>Update mount route image:</label>
                                    <div class="col-md-4">
                                        <input type="file" name="mount_route_img" id="mount_route_img">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <img :src="'/public/images/mount_route_description_img/'+this.mount_route_description_image_name" alt="article image">
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form @submit="checkForm" ref="myForm">
                                <div class="form-group clearfix">
                                    <div class="row">
                                        <label for="name" class='col-md-2 control-label'>Update article image:</label>
                                        <div class="col-md-4">     
                                            <input type="file" name="profile_pic" id="profile_pic">               
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img :src="'/public/images/' + category + '_img/'+this.image_name" alt="article image">
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" v-if="tab_num == 2">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h2 class="display-4">{{this.category}} article english version</h2>
                                <p class="lead">Article english version for site localisation.</p>
                            </div>
                        </div>
                        <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Title </label>
                                <div class="col-md-5">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="us_title" v-model="us_title" class="form-control"> 
                                    <div class="alert alert-danger" role="alert" v-if="us_article_error.us_title">
                                        {{ us_article_error.us_title[0] }}
                                    </div>
                                </div>
                                <label for="name" class='col-md-2 control-label'> Edit URL </label>
                                <div class="col-md-1">
                                    <input type="checkbox" >
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Short description </label>
                                <div class="col-md-8">
                                    <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control">gggggggggg</textarea> -->
                                    <ckeditor :editor="editor" v-model="us_short_description" :config="editorConfig"></ckeditor>
                                    <div class="alert alert-danger" role="alert" v-if="us_article_error.us_short_description">
                                        {{ us_article_error.us_short_description[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> text </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="us_text" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                    <div class="alert alert-danger" role="alert" v-if="us_article_error.us_text">
                                        {{ us_article_error.us_text[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                                <label for="name" class='col-md-2 control-label'> Routes description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="us_route" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="route" name="route" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                <label for="name" class='col-md-2 control-label'> How to get hear </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="us_how_get" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="how_get" name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                <label for="name" class='col-md-2 control-label'> Best time for climbing </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="us_best_time" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="best_time" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                <label for="name" class='col-md-2 control-label'> what you need </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="us_what_need" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="what_need" name="what_need" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Info / contact </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="us_info" :config="editorConfig"></ckeditor>
                                    <div class="alert alert-danger" role="alert" v-if="us_article_error.us_info">
                                        {{ us_article_error.us_info[0] }}
                                    </div>
                                    <!-- <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> Price description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="us_price_text" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="prixe_text" name="prixe_text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <hr>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Meta keyword </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="meta_keyword" value="meta_keyword" v-model="us_meta_keyword" class="form-control"> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" v-if="tab_num == 3">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h2 class="display-4">{{this.category}} article rusion version</h2>
                                <p class="lead">Article rusion version for site localisation.</p>
                            </div>
                        </div>
                        <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Title </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="value name" v-model="ru_title" class="form-control"> 
                                    <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_title">
                                        {{ ru_article_error.ru_title[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Short description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_short_description" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                    <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_short_description">
                                        {{ ru_article_error.ru_short_description[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> text </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_text" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                    <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_text">
                                        {{ ru_article_error.ru_text[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                                <label for="name" class='col-md-2 control-label'> Routes description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_route" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="route" name="route" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                <label for="name" class='col-md-2 control-label'> How to get hear </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_how_get" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="how_get" name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                <label for="name" class='col-md-2 control-label'> Best time for climbing </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_best_time" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="best_time" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                <label for="name" class='col-md-2 control-label'> what you need </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_what_need" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="what_need" name="what_need" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Info / contact </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_info" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                    <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_info">
                                        {{ ru_article_error.ru_info[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> Price description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ru_price_text" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="prixe_text" name="prixe_text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <hr>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Meta keyword </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="meta_keyword" v-model="ru_meta_keyword" value="meta_keyword" class="form-control"> 
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="row" v-if="tab_num == 4">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h2 class="display-4">{{this.category}} article georgian version</h2>
                                <p class="lead">Article georgian version for site localisation.</p>
                            </div>
                        </div>
                        <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Title </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="value name" v-model="ka_title" class="form-control"> 
                                    <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_text">
                                        {{ ka_article_error.ka_text[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Short description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_short_description" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                    <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_short_description">
                                        {{ ka_article_error.ka_short_description[0] }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> text </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_text" :config="editorConfig"></ckeditor>
                                    <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_text">
                                        {{ ka_article_error.ka_text[0] }}
                                    </div>
                                    <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                                <label for="name" class='col-md-2 control-label'> Routes description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_route" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="route" name="route" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                <label for="name" class='col-md-2 control-label'> How to get hear </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_how_get" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="how_get" name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                <label for="name" class='col-md-2 control-label'> Best time for climbing </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_best_time" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="best_time" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                <label for="name" class='col-md-2 control-label'> what you need </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_what_need" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="what_need" name="what_need" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Info / contact </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_info" :config="editorConfig"></ckeditor>
                                    <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_info">
                                        {{ ka_article_error.ka_info[0] }}
                                    </div>
                                    <!-- <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                <label for="name" class='col-md-2 control-label'> Price description </label>
                                <div class="col-md-8">
                                    <ckeditor :editor="editor" v-model="ka_price_description" :config="editorConfig"></ckeditor>
                                    <!-- <textarea type="text" id="prixe_text" name="prixe_text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                </div>
                            </div>

                            <hr>

                            <div class="form-group clearfix row">
                                <label for="name" class='col-md-2 control-label'> Meta keyword </label>
                                <div class="col-md-8">
                                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                    <input type="text" name="meta_keyword" value="meta_keyword" v-model='ka_meta_keyword' class="form-control"> 
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'back_url',
            'category',
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

                editor: 'editor',
                editorConfig:{},

                permission: '',

                editing_url: '/articles/get_editing_data/',
                url: '',

                editing_data: '',

                us_title_for_url_title: "",
                published: "",
                completed: "",
                map: "",
                weather: "",
                start_data_day: "",
                end_data_day: "",
                end_data_month: "",
                end_data_month: "",
                fb_link: "",
                twit_link: "",
                google_link: "",
                inst_link: "",
                web_link: "",
                working_time: "",
                price_from: "",

                name: '',
                image_name: '',
                region_sectors_image_name: '',
                mount_route_description_image_name: '',
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
            if (this.category == 'mount_route') {
                this.get_mount_massive_data()
            }
            this.check_permission()
        },

        methods: {
            check_permission(){
                axios
                .get('../../../check_permission/')
                .then(Response => {
                    this.permission =  Response.data
                })
                .catch(error =>{
                })
            },

            edit_global_article() {
                axios
                .post('/articles/global/edit/' + this.editing_article_id, {
                    us_title_for_url_title: this.us_title,

                    published: this.published,
                    mount_id: this.mount_id,
                    completed: this.completed,
                    map: this.map,
                    weather: this.weather,

                    start_data_day: this.start_data_day,
                    end_data_day: this.end_data_day,
                    start_data_month: this.start_data_month,
                    end_data_month: this.end_data_month,

                    working_time: this.working_time,
                    price_from: this.price_from,

                    fb_link: this.fb_link,
                    twit_link: this.twit_link,
                    google_link: this.google_link,
                    inst_link: this.inst_link,
                    web_link: this.web_link,
                })
                .then(Response => {
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

            get_mount_massive_data: function(){
                axios
                .get("/mountaineering/get_mount_data/")
                .then(response => {
                    this.mount_data = response.data
                })
                .catch(
                error => console.log(error)
                );
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
            upload_region_image: function () {
                // var mySectorRegionImageData = new Array(this.$refs.sectorRegionImage)
                var mySectorRegionImageData = new FormData(this.$refs.sectorRegionImage)
                axios({
                    method: 'post',
                    url: '/articles/region_sectors_image_update/' + this.editing_article_id,
                    data: mySectorRegionImageData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    
                });
            },
            upload_mount_route_image: function () {
                var myMountRouteImageData = new FormData(this.$refs.mountRouteImage)
                axios({
                    method: 'post',
                    url: '/articles/mount_route_image_update/' + this.editing_article_id,
                    data: myMountRouteImageData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    
                });
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
                    this.mount_id = this.editing_data.global_article['mount_id'],
                    this.completed = this.editing_data.global_article['completed'],
                    this.map = this.editing_data.global_article['map'],
                    this.weather = this.editing_data.global_article['weather'],
                    this.start_data_day = this.editing_data.global_article['start_data_day'],
                    this.end_data_day = this.editing_data.global_article['end_data_day'],
                    this.start_data_month = this.editing_data.global_article['start_data_month'],
                    this.end_data_month = this.editing_data.global_article['end_data_month'],
                    this.fb_link = this.editing_data.global_article['fb_link'],
                    this.twit_link = this.editing_data.global_article['twit_link'],
                    this.google_link = this.editing_data.global_article['google_link'],
                    this.inst_link = this.editing_data.global_article['inst_link'],
                    this.web_link = this.editing_data.global_article['web_link'],
                    this.us_price_from = this.editing_data.global_article['price_from'],
                    this.image_name = this.editing_data.global_article['image'],
                    this.region_sectors_image_name = this.editing_data.global_article['climbing_area_image']
                    this.mount_route_description_image_name = this.editing_data.global_article['mount_route_image']

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

                if (this.category == 'outdoor') {
                    this.upload_region_image()
                }
                else if (this.category == 'mount_route') {
                    this.upload_mount_route_image()
                }
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