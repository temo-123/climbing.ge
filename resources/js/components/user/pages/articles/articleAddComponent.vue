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
                <div class="alert alert-danger" role="alert" v-if="error.global_article_error.published">
                    {{ error.global_article_error.published[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_short_description">
                    {{ error.us_article_error.us_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_short_description">
                    {{ error.us_article_error.us_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_text">
                    {{ error.us_article_error.us_text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_short_description">
                    {{ error.ka_article_error.ka_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_short_description">
                    {{ error.ka_article_error.ka_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_text">
                    {{ error.ka_article_error.ka_text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_short_description">
                    {{ error.ru_article_error.ru_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_short_description">
                    {{ error.ru_article_error.ru_short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_text">
                    {{ error.ru_article_error.ru_text[0] }}
                </div>
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
            </div>
            <div class="row" v-if="tab_num == 1">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="jumbotron jumbotron-fluid">
                                <div class="container">
                                    <h2 class="display-4">{{this.category}} article global information</h2>
                                    <p class="lead">Article global information.</p>
                                </div>
                            </div>

                            <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="add_article"  style="margin-top: 5%;" enctyp ="multipart/form-data">

                                <div class="form-group clearfix row">
                                    <label for="published" class='col-md-2 control-label '> Publish </label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="article_data.global_data.published" name="published" > 
                                            <option value="0">Not public</option> 
                                            <option value="1">Public</option> 
                                        </select> 
                                        <div class="alert alert-danger" role="alert" v-if="error.global_article_error.published">
                                            {{ error.global_article_error.published[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="region" class='col-md-2 control-label '> Regions </label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="article_data.global_data.region" name="region" > 
                                            <option  v-for="region in regions" :key="region.id" :value="region.id">{{ region.region_name_us }}</option>
                                        </select> 
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'mount_route'">
                                    <label for="name" class='col-md-2 control-label'> Mountain </label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="article_data.global_data.mount_id" name="mount_id"> 
                                            <option  v-for="mount in mount_data" :key="mount.id" v-bind:value="mount.id">{{mount.name}}</option> 
                                        </select> 
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'event'">
                                    <label for="name" class='col-md-2 control-label'> Completed </label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="article_data.global_data.completed" name="completed"> 
                                            <option value="0">No complited</option> 
                                            <option value="1">Complited</option> 
                                        </select> 
                                    </div>
                                </div>


                                <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                    <label for="name" class='col-md-2 control-label'> Map </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="article_data.global_data.map" name="map" class="form-control"> 
                                    </div>
                                </div>


                                <div class="form-group clearfix row"  v-if="this.category == 'outdoor' || this.category == 'ice'">
                                    <label for="name" class='col-md-2 control-label'> Weather </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="article_data.global_data.weather" name="weather" class="form-control"> 
                                    </div>
                                </div>


                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> Minimal price </label>
                                    <div class="col-md-9">
                                        <input type="text" name="price_from"  v-model="article_data.global_data.price_from" class="form-control"> 
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> Working time </label>
                                    <div class="col-md-9">
                                        <input type="text" name="open_timen" v-model="article_data.global_data.open_timen" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> Working time </label>
                                    <div class="col-md-9">
                                        <input type="text" name="closed_time" v-model="article_data.global_data.closed_time" class="form-control"> 
                                    </div>
                                </div>


                                <hr v-if="this.category == 'event'">

                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> Start_data </label>
                                    <div class="col-md-9">
                                        <input type="text" name="start_data" v-model="article_data.global_data.start_data" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> End data </label>
                                    <div class="col-md-9">
                                        <input type="text" name="end_data" v-model="article_data.global_data.end_data" class="form-control"> 
                                    </div>
                                </div>

                                <hr v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">

                                <div class="form-group clearfix row"  v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> facebook / twitter </label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="article_data.global_data.fb_link" name="fb_link" class="form-control"> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" v-model="article_data.global_data.twit_link" name="twit_link" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> google / instagram </label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="article_data.global_data.google_link" name="google_link" class="form-control"> 
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" v-model="article_data.global_data.inst_link" name="inst_link" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> website </label>
                                    <div class="col-md-9">
                                        <!-- <input type="text" v-model="article_data.global_data.name" name="value name" value="old data" class="form-control"> -->
                                        <input type="text" v-model="article_data.global_data.web_link" name="web_link" class="form-control"> 
                                    </div>
                                </div>
                            </form>

                            <form @submit="upload_mount_route_image" ref="mountRouteImage" v-if="this.category == 'mount_route'">
                                <div class="form-group clearfix row">
                                    <label for="email" class='col-md-5 control-label'>Upload mount route image:</label>
                                    <div class="col-md-6">
                                        <input type="file" name="mount_route_img" id="mount_route_img">
                                    </div>
                                </div>
                            </form>

                            <form @submit="checkForm" ref="myForm">
                                <div class="form-group clearfix row">
                                    <label for="email" class='col-md-5 control-label'>Upload article image:</label>
                                    <div class="col-md-6">
                                        <input type="file" name="profile_pic" id="profile_pic">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="row">
                        <div class="container">
                            <label for="email" class='col-md-12 control-label'>Upload outdoor climbing area sector image:</label>

                            <div class="container">
                                <div class="alert alert-danger" role="alert" v-if="image_errors.image">
                                    {{ image_errors.image[0] }}
                                </div>
                                <div class="row">
                                    <div class="form-groupe">
                                        <button class="btn btn-primary mb-4"  @click="add_image_modal()">Add image</button>
                                    </div>
                                    <div class="form-groupe">
                                        <button @click="get_spost_sectors_image(temporary_spost_sectors_id)" class="btn main-btn float-right" v-if="!image_is_refresh">Refresh ({{image_reset_id}})</button>
                                        <span class="badge badge-primare mb-1 float-right" v-if="image_is_refresh">Updating...</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <button class="btn btn-secondary float-left" @click="save_spost_sectors_images_sequence()">Save spost_sectors images sequence</button>
                                </div>
                            </div>
                            
                        </div>

                        <div class="container">
                            <div class="root">
                            <SlickList lockAxis="y" v-model="images" tag="table" style="width: 100%">
                                <tr>
                                    <td>ID</td>
                                    <td>Number</td>
                                    <td>Image</td>
                                    <td>Delete</td>
                                </tr>
                                <SlickItem v-for="(image, index) in images" :index='index' :key="index" tag="tr">
                                    <td>{{ image.id }}</td>
                                    <td v-if="image.num == NULL">0</td>
                                    <td v-else>{{ image.num }}</td>
                                    <td><img :alt="'Spost sectors image - (' + image.image + ')'" class="spost_sectors_image_in_table" :src="'/public/images/spost_sectors_img/' + image.image"></td>
                                    <td><button class="btn btn-danger" @click="del_spost_sectors_image(image.id)">Del</button></td>
                                </SlickItem>
                            </SlickList>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="tab_num == 2">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="jumbotron jumbotron-fluid">
                                <div class="container">
                                    <h2 class="display-4">{{this.category}} article english version</h2>
                                    <p class="lead">Article english version for site localisation.</p>
                                </div>
                            </div>
                            <form name="contact-form" method="POST" @submit.prevent="add_us_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Title </label>
                                    <div class="col-md-9">
                                        <input type="text" name="name" v-model="us_title"  class="form-control"> 
                                        <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_title">
                                            {{ error.us_article_error.us_title[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Short description </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text" name="short_description" v-model="article_data.global_data.us_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.us_data.us_short_description" :config="editorConfig"></ckeditor>
                                        <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_short_description">
                                            {{ error.us_article_error.us_short_description[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> text </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="text" rows="15" v-model="article_data.us_data.us_text" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.us_data.us_text" :config="editorConfig"></ckeditor>
                                        <div class="alert alert-danger" role="alert" v-if="error.us_article_error.us_text">
                                            {{ error.us_article_error.us_text[0] }}
                                        </div>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor'">

                                <div class="row" v-if="this.category == 'outdoor'">
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_new_info" name="fav_language" value="new_info" v-model="routes_info">
                                        <label for="routes_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_befor" name="fav_language" value="befor" v-model="routes_info">
                                        <label for="routes_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_after" name="fav_language" value="after" v-model="routes_info">
                                        <label for="routes_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_instead" name="fav_language" value="instead" v-model="routes_info">
                                        <label for="routes_instead">Instead</label>
                                    </div>
                                </div>
                                <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                                    <label for="name" class='col-md-2 control-label'> Routes description </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="routes_info == 'befor'" >
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="routes_info == 'befor' || routes_info == 'after' || routes_info == 'new_info'">
                                            <ckeditor v-model="article_data.us_data.us_route" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="routes_info == 'after' || routes_info == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                    <label for="name" class='col-md-2 control-label'> How to get hear </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="how_get" rows="15"  v-model="article_data.us_data.us_how_get" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.us_data.us_how_get" :config="editorConfig"></ckeditor>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor' || this.category == 'ice'">

                                <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                    <div class="col-md-2">
                                        <input type="radio" id="time_new_info" name="fav_language" value="new_info" v-model="best_time">
                                        <label for="time_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_befor" name="fav_language" value="befor" v-model="best_time">
                                        <label for="time_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_after" name="fav_language" value="after" v-model="best_time">
                                        <label for="time_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_instead" name="fav_language" value="instead" v-model="best_time">
                                        <label for="time_instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                    <label for="name" class='col-md-2 control-label'> Best time for climbing </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="best_time == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="best_time == 'befor' || best_time == 'after' || best_time == 'new_info'">
                                            <ckeditor v-model="article_data.us_data.us_best_time" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="best_time == 'after' || best_time == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">

                                <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                    <div class="col-md-2">
                                        <input type="radio" id="need_new_info" name="fav_language" value="new_info" v-model="what_need_info">
                                        <label for="need_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_befor" name="fav_language" value="befor" v-model="what_need_info">
                                        <label for="need_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_after" name="fav_language" value="after" v-model="what_need_info">
                                        <label for="need_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_instead" name="fav_language" value="instead" v-model="what_need_info">
                                        <label for="need_instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                    <label for="name" class='col-md-2 control-label'> what you need </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="what_need_info == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="what_need_info == 'befor' || what_need_info == 'after' || what_need_info == 'new_info'">
                                            <ckeditor v-model="article_data.us_data.us_what_need" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="what_need_info == 'after' || what_need_info == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="radio" id="new_info" name="fav_language" value="new_info" v-model="info_block">
                                        <label for="new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="befor" name="fav_language" value="befor" v-model="info_block">
                                        <label for="befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="after" name="fav_language" value="after" v-model="info_block">
                                        <label for="after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="instead" name="fav_language" value="instead" v-model="info_block">
                                        <label for="instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Info / contact </label>
                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="info_block == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="info_block == 'befor' || info_block == 'after' || info_block == 'new_info'">
                                            <ckeditor v-model="article_data.us_data.us_info" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="info_block == 'after' || info_block == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_us }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> Price description </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text" name="prise_text" rows="15" v-model="article_data.us_data.us_price_text"  class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.us_data.us_price_text" :config="editorConfig"></ckeditor>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="tab_num == 3">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="jumbotron jumbotron-fluid">
                                <div class="container">
                                    <h2 class="display-4">{{this.category}} article rusion version</h2>
                                    <p class="lead">Article rusion version for site localisation.</p>
                                </div>
                            </div>
                            
                            <form name="contact-form" method="POST" @submit.prevent="add_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Title </label>
                                    <div class="col-md-9">
                                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                        <input type="text" name="title" v-model="article_data.ru_data.ru_title" class="form-control"> 
                                        <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_title">
                                            {{ error.ru_article_error.ru_title[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Short description </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="short_description" v-model="article_data.ru_data.ru_short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ru_data.ru_short_description" :config="editorConfig"></ckeditor>
                                        <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_short_description">
                                            {{ error.ru_article_error.ru_short_description[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> text </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="text" rows="15"  v-model="article_data.ru_data.ru_text" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ru_data.ru_text" :config="editorConfig"></ckeditor>
                                        <div class="alert alert-danger" role="alert" v-if="error.ru_article_error.ru_text">
                                            {{ error.ru_article_error.ru_text[0] }}
                                        </div>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor'">

                                <div class="row" v-if="this.category == 'outdoor'">
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_new_info" name="fav_language" value="new_info" v-model="routes_info">
                                        <label for="routes_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_befor" name="fav_language" value="befor" v-model="routes_info">
                                        <label for="routes_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_after" name="fav_language" value="after" v-model="routes_info">
                                        <label for="routes_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_instead" name="fav_language" value="instead" v-model="routes_info">
                                        <label for="routes_instead">Instead</label>
                                    </div>
                                </div>
                                <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                                    <label for="name" class='col-md-2 control-label'> Routes description </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="routes_info == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="routes_info == 'befor' || routes_info == 'after' || routes_info == 'new_info'">
                                            <ckeditor v-model="article_data.ru_data.ru_route" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="routes_info == 'after' || routes_info == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                    <label for="name" class='col-md-2 control-label'> How to get hear </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="how_get" rows="15"  v-model="article_data.ru_data.ru_how_get" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ru_data.ru_how_get" :config="editorConfig"></ckeditor>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor' || this.category == 'ice'">

                                <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                    <div class="col-md-2">
                                        <input type="radio" id="time_new_info" name="fav_language" value="new_info" v-model="best_time">
                                        <label for="time_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_befor" name="fav_language" value="befor" v-model="best_time">
                                        <label for="time_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_after" name="fav_language" value="after" v-model="best_time">
                                        <label for="time_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_instead" name="fav_language" value="instead" v-model="best_time">
                                        <label for="time_instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                    <label for="name" class='col-md-2 control-label'> Best time for climbing </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="best_time == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="best_time == 'befor' || best_time == 'after' || best_time == 'new_info'">
                                            <ckeditor v-model="article_data.ru_data.ru_best_time" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="best_time == 'after' || best_time == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">

                                <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                    <div class="col-md-2">
                                        <input type="radio" id="need_new_info" name="fav_language" value="new_info" v-model="what_need_info">
                                        <label for="need_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_befor" name="fav_language" value="befor" v-model="what_need_info">
                                        <label for="need_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_after" name="fav_language" value="after" v-model="what_need_info">
                                        <label for="need_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_instead" name="fav_language" value="instead" v-model="what_need_info">
                                        <label for="need_instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                    <label for="name" class='col-md-2 control-label'> what you need </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="what_need_info == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="what_need_info == 'befor' || what_need_info == 'after' || what_need_info == 'new_info'">
                                            <ckeditor v-model="article_data.ru_data.ru_what_need" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="what_need_info == 'after' || what_need_info == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="radio" id="new_info" name="fav_language" value="new_info" v-model="info_block">
                                        <label for="new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="befor" name="fav_language" value="befor" v-model="info_block">
                                        <label for="befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="after" name="fav_language" value="after" v-model="info_block">
                                        <label for="after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="instead" name="fav_language" value="instead" v-model="info_block">
                                        <label for="instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Info / contact </label>
                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="info_block == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="info_block == 'befor' || info_block == 'after' || info_block == 'new_info'">
                                            <ckeditor v-model="article_data.ru_data.ru_info" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="info_block == 'after' || info_block == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ru }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> Price description </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text" name="price_text" rows="15"  v-model="article_data.ru_data.ru_price_text" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ru_data.ru_price_text" :config="editorConfig"></ckeditor>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="tab_num == 4">
                <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="jumbotron jumbotron-fluid">
                                <div class="container">
                                    <h2 class="display-4">{{this.category}} article georgian version</h2>
                                    <p class="lead">Article georgian version for site localisation.</p>
                                </div>
                            </div>

                            <form name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Title </label>
                                    <div class="col-md-9">
                                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                                        <input type="text" name="value name"  v-model="article_data.ka_data.ka_title" class="form-control"> 
                                        <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_text">
                                            {{ error.ka_article_error.ka_text[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Short description </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="short_description"  v-model="article_data.ka_data.ka_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ka_data.ka_short_description" :config="editorConfig"></ckeditor>
                                        <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_short_description">
                                            {{ error.ka_article_error.ka_short_description[0] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> text </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="txt" rows="15"  v-model="article_data.ka_data.ka_text" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ka_data.ka_text" :config="editorConfig"></ckeditor>
                                        <div class="alert alert-danger" role="alert" v-if="error.ka_article_error.ka_text">
                                            {{ error.ka_article_error.ka_text[0] }}
                                        </div>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor'">

                                <div class="row" v-if="this.category == 'outdoor'">
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_new_info" name="fav_language" value="new_info" v-model="routes_info">
                                        <label for="routes_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_befor" name="fav_language" value="befor" v-model="routes_info">
                                        <label for="routes_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_after" name="fav_language" value="after" v-model="routes_info">
                                        <label for="routes_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="routes_instead" name="fav_language" value="instead" v-model="routes_info">
                                        <label for="routes_instead">Instead</label>
                                    </div>
                                </div>
                                <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                                    <label for="name" class='col-md-2 control-label'> Routes description </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="routes_info == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="routes_info == 'befor' || routes_info == 'after' || routes_info == 'new_info'">
                                            <ckeditor v-model="article_data.ka_data.ka_route" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="routes_info == 'after' || routes_info == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                                    <label for="name" class='col-md-2 control-label'> How to get hear </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text"  name="how_get" rows="15"  v-model="article_data.ka_data.ka_how_get" class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ka_data.ka_how_get" :config="editorConfig"></ckeditor>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor' || this.category == 'ice'">

                                <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                    <div class="col-md-2">
                                        <input type="radio" id="time_new_info" name="fav_language" value="new_info" v-model="best_time">
                                        <label for="time_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_befor" name="fav_language" value="befor" v-model="best_time">
                                        <label for="time_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_after" name="fav_language" value="after" v-model="best_time">
                                        <label for="time_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="time_instead" name="fav_language" value="instead" v-model="best_time">
                                        <label for="time_instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                                    <label for="name" class='col-md-2 control-label'> Best time for climbing </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="best_time == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="best_time == 'befor' || best_time == 'after' || best_time == 'new_info'">
                                            <ckeditor v-model="article_data.ka_data.ka_best_time" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="best_time == 'after' || best_time == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <hr v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">

                                <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                    <div class="col-md-2">
                                        <input type="radio" id="need_new_info" name="fav_language" value="new_info" v-model="what_need_info">
                                        <label for="need_new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_befor" name="fav_language" value="befor" v-model="what_need_info">
                                        <label for="need_befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_after" name="fav_language" value="after" v-model="what_need_info">
                                        <label for="need_after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="need_instead" name="fav_language" value="instead" v-model="what_need_info">
                                        <label for="need_instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                                    <label for="name" class='col-md-2 control-label'> what you need </label>

                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="what_need_info == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="what_need_info == 'befor' || what_need_info == 'after' || what_need_info == 'new_info'">
                                            <ckeditor v-model="article_data.ka_data.ka_what_need" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="what_need_info == 'after' || what_need_info == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="radio" id="new_info" name="fav_language" value="new_info" v-model="info_block">
                                        <label for="new_info">New info</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="befor" name="fav_language" value="befor" v-model="info_block">
                                        <label for="befor">Befor</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="after" name="fav_language" value="after" v-model="info_block">
                                        <label for="after">After</label><br>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" id="instead" name="fav_language" value="instead" v-model="info_block">
                                        <label for="instead">Instead</label>
                                    </div>
                                </div>

                                <div class="form-group clearfix row">
                                    <label for="name" class='col-md-2 control-label'> Info / contact </label>
                                    <div class="col-md-9">
                                        <div class="col-md-12" v-if="info_block == 'befor'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="col-md-12" v-if="info_block == 'befor' || info_block == 'after' || info_block == 'new_info'">
                                            <ckeditor v-model="article_data.ka_data.ka_info" :config="editorConfig"></ckeditor>
                                        </div>

                                        <div class="col-md-12" v-if="info_block == 'after' || info_block == 'instead'">
                                            <select class="form-control"> 
                                                <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title_ka }}</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                                    <label for="name" class='col-md-2 control-label'> Price description </label>
                                    <div class="col-md-9">
                                        <!-- <textarea type="text" name="prixe_text" rows="15" v-model="article_data.ka_data.ka_price_text"  class="form-cotrol md-textarea form-control"></textarea> -->
                                        <ckeditor v-model="article_data.ka_data.ka_price_text" :config="editorConfig"></ckeditor>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <stack-modal
                :show="is_add_image"
                title="Add image"
                @close="is_add_image=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <form ref="myForm">
                    <div class="container">
                        <div class="form-group clearfix row">
                            <input type="file" name="image" id="image" value="image">
                        </div>
                    </div>
                </form>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="add_image()"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

    </div>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        props: [
            // 'back_url',
            // 'category'
        ],
        data(){
            return {
                myModal: false,
                // errors: [],
                image_errors: [],
                without_info: false,

                is_add_image: false,

                tab_num: 1,
                category: this.$route.params.article_category,
                editorConfig: {
                    // toolbar: [ [ 'Bold' ] ]
                },
                error: {
                    global_article_error: [],
                    ka_article_error: [],
                    ru_article_error: [],
                    us_article_error: [],
                },

                image_is_refresh: false,
                image_reset_id: 0,

                info_block: 'new_info',
                routes_info: 'new_info',
                what_need_info: 'new_info',
                best_time: 'new_info',

                general_infos: [],
                regions: [],
                // ru_article_error: [],
                // us_article_error: [],

                // name: '',
                images: [],
                // success: '',

                back_is_action: false,

                // mount_data: '',

                us_title: null,

                article_data: {
                    global_data: {
                        category: this.$route.params.article_category,
                        us_title_for_url_title: null,
                        published: 0,
                        completed: null,
                        map: null,
                        weather: null,
                        open_timen: null,
                        closed_time: null,
                        price_from: null,
                        start_data: null,
                        end_data: null,
                        fb_link: null,
                        twit_link: null,
                        google_link: null,
                        inst_link: null,
                        web_link: null,
                        region: null
                    },

                    us_data: {
                        us_title: null,
                        us_short_description: null,
                        us_text: null,
                        us_route: null,
                        us_how_get: null,
                        us_best_time: null,
                        us_what_need: null,
                        us_info: null,
                        us_time: null,
                        us_meta_keyword: null,
                    },

                    ka_data: {
                        ka_title: null,
                        ka_short_description: null,
                        ka_text: null,
                        ka_route: null,
                        ka_how_get: null,
                        ka_best_time: null,
                        ka_what_need: null,
                        ka_info: null,
                        ka_time: null,
                        ka_meta_keyword: null,
                    },

                    ru_data: {
                        ru_title: null,
                        ru_short_description: null,
                        ru_text: null,
                        ru_route: null,
                        ru_how_get: null,
                        ru_best_time: null,
                        ru_what_need: null,
                        ru_info: null,
                        ru_time: null,
                        ru_meta_keyword: null,
                    }
                },

                // temporary_article_id: '',
            }
        },
        mounted() {
            // this.create_temporary_article()
            // this.check_permission()
            if (this.category == 'outdoor') {
                this.get_regions()
            }
            this.get_general_info()
        },
        // beforeDestroy () {
        //     document.removeEventListener("backbutton", this.yourCallBackFunction());
        // },
        // watch:{
        //     '$route' (to) {
        //         if(to.currentRoute.meta.reload==true){
        //             alert('test')
        //         }
        //     }
        // },
        beforeRouteLeave (to, from, next) {
            // const answer = window.confirm('Added information will be deleted!!! Are you sure, you want go back?')
            if(this.back_is_action = true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
                    // this.del_temporary_article(this.temporary_article_id)
                    this.back_is_action = false;
                    next()
                } else {
                    next(false)
                }
            }
        },
        methods: {
            add_image_modal(){
                this.is_add_image = true
            },

            add_image(){
                var myFormData = new FormData(this.$refs.myForm)
                axios({
                    method: 'post',
                    url: '/gallery/add/',
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    this.is_add_image = false
                    this.get_gallery_data()
                });
            },
            // yourCallBackFunction(){
            //     alert('test')
            // },
            // showModal(){
            //     this.myModal = !this.myModal
            // },
            // create_temporary_article() {
            //     axios
            //     .post('../../api/temporary_article/'+this.$route.params.article_category,{
            //         _method: 'PATCH'
            //     })
            //     .then((response)=>  {
            //         this.get_temporary_article_data()
            //         console.log(response)
            //     })
            //     .catch(error => console.log(error))
            // },
            // del_temporary_article(temporary_article_id) {
            //     axios
            //     .post('../../../api/article/' + temporary_article_id, {
            //         _method: 'DELETE'
            //     })
            //     .then((response)=>  {
            //         // if(!this.back_is_action){
            //         //     this.$router.go(-1)
            //         // }

            //         // this.back_is_action = false;
            //     })
            //     .catch(error => console.log(error))
            // },
            get_temporary_article_data: function(){
                axios
                .get("../../api/temporary_article/")
                .then(response => {
                    this.editing_data = response.data
                    this.temporary_article_id = this.editing_data.last_temporary_article_id
                })
                .catch(
                error => console.log(error)
                );
            },


            // get_mount_massive_data: function(){
            //     axios
            //     .get("/mountaineering/get_mount_data/")
            //     .then(response => {
            //         this.mount_data = response.data
            //     })
            //     .catch(
            //     error => console.log(error)
            //     );
            // },

            get_regions(category){
                if(this.category == 'outdoor'){
                    axios
                    .get("/api/region/")
                    .then(response => {
                        this.regions = response.data
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            },

            get_general_info(){
                axios
                .get('/api/general_info')
                .then(response => {
                    this.general_infos = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },


            add_article() {
                this.is_us_article_error = []
                this.article_data.global_data.us_title_for_url_title = this.us_title,
                this.article_data.us_data.us_title = this.us_title,
                axios
                .post('../../../api/article/' + this.category, {        
                    data: this.article_data,
                    _method: 'PATCH'
                })
                .then((response)=> { 
                    // console.log(this.article_data);
                    // this.is_us_article_error = false
                    // this.if_isset_go_beck(this.is_us_article_error)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.error.global_article_error = [],
                        this.error.ka_article_error = [],
                        this.error.ru_article_error = [],
                        this.error.us_article_error = [],

                        this.error.global_article_error = error.response.data['global_data']
                        this.error.ka_article_error = error.response.data['ka_data']
                        this.error.ru_article_error = error.response.data['ru_data']
                        this.error.us_article_error = error.response.data['us_data']

                        // this.error = error.response.data.errors
                        // console.log(error.response.data['ka_data']);
                    }
                    this.is_us_article_error = true
                })
            },

            checkForm: function () {
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
                    
                });
            },
            upload_region_image: function () {
                // var mySectorRegionImageData = new Array(this.$refs.sectorRegionImage)
                var mySectorRegionImageData = new FormData(this.$refs.sectorRegionImage)
                axios({
                    method: 'post',
                    url: '/articles/region_sectors_image_upload/',
                    data: mySectorRegionImageData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    
                });
            },
            // upload_mount_route_image: function () {
            //     var myMountRouteImageData = new FormData(this.$refs.mountRouteImage)
            //     axios({
            //         method: 'post',
            //         url: '/articles/mount_route_image_upload/',
            //         data: myMountRouteImageData,
            //         config: { 
            //             headers: {'Content-Type': 'multipart/form-data' },
            //         },
            //     })
            //     .then((response)=>  {
                    
            //     });
            // },
            
            go_back: function() {
                this.back_is_action = true,
                this.$router.go(-1)
            },
            save_all() {
                this.add_article()
                router.push({ name: 'articlelist', params: { article_category: this.category } })
                this.$router.go(-1)
            },

            if_isset_go_beck() {
                // if (
                //     this.is_global_article_error == false &&
                //     this.is_ka_article_error == false &&
                //     this.is_ru_article_error == false &&
                //     this.is_us_article_error == false
                // ) {
                //     window.location.href = this.back_url;
                // }
            },

            // check_permission() {
            //     axios
            //     .get('/users/get_user_parmisions/', { })
            //     .then((response)=> { 
                    
            //     })
            //     .catch(error =>{
                    
            //     })
            // }
        }
    }
</script>
<style scoped>
    .tabs {
        /* display: flex; */
        flex-wrap: wrap;
    }
    .tabs label {
        order: 1;
        display: block;
        padding: 1rem 2rem;
        margin-right: 0.2rem;
        cursor: pointer;
        background: #ccced0;
        font-weight: bold;
        transition: background ease 0.2s;
    }
    .tabs .tab {
        order: 99;
        flex-grow: 1;
        width: 100%;
        display: none;
        padding: 1rem;
        background: #fff;
        /* border: 1px solid #ccc!important; */
    }
    .tabs input[type="radio"] {
        display: none;
    }
    .tabs input[type="radio"]:checked + label {
        background: #fff;
        border: 1px solid #ccc !important;
    }
    .tabs input[type="radio"]:checked + label + .tab {
        display: block;
    }

    @media (max-width: 45em) {
        .tabs .tab,
        .tabs label {
            order: initial;
        }
        .tabs label {
            width: 100%;
            margin-right: 0;
            margin-top: 0.2rem;
        }
    }
</style>