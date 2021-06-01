<template>
<div class="col_md_12">

    <div class="row">
      <div class="form-group">
        <button type="submit" class="btn btn-primary" v-on:click="back(temporary_article_id)">Beck</button>
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
                        <h2 class="display-4">{{this.category}} article global information</h2>
                        <p class="lead">Article global information.</p>
                    </div>
                </div>

                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="add_article"  style="margin-top: 5%;" enctyp ="multipart/form-data">

                    <div class="form-group clearfix row">
                        <label for="published" class='col-md-2 control-label '> Publish </label>
                        <div class="col-md-8">
                            <select class="form-control" v-model="published" name="published" > 
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
                        <label for="name" class='col-md-2 control-label'> Completed </label>
                        <div class="col-md-8">
                            <select class="form-control" v-model="completed" name="completed"> 
                                <option value="0">No complited</option> 
                                <option value="1">Complited</option> 
                            </select> 
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> Map </label>
                        <div class="col-md-8">
                            <input type="text" v-model="map" name="map" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix row"  v-if="this.category == 'outdoor' || this.category == 'ice' || this.category != 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> Weather </label>
                        <div class="col-md-8">
                            <input type="text" v-model="weather" name="weather" class="form-control"> 
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Minimal price </label>
                        <div class="col-md-8">
                            <input type="text" name="price_from" value="price_from" v-model="price_from" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Working time </label>
                        <div class="col-md-8">
                            <input type="text" name="working_time" v-model="working_time" class="form-control"> 
                        </div>
                    </div>


                    <hr v-if="this.category == 'event'">

                    <div class="form-group clearfix row"  v-if="this.category == 'event'">
                        <label for="name" class='col-md-2 control-label'> Start data </label>
                        <div class="col-md-4">
                            <input type="text" v-model="start_data_day" name="start_data_day" class="form-control"  placeholder="start day data"> 
                        </div>
                        <div class="col-md-4">
                            <!-- <input type="text" v-model="start_data_month" name="start_data_month" class="form-control"  placeholder="start month">  -->
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
                            <input type="text" v-model="and_data_day" name="and_data_day" class="form-control"  placeholder="and day data"> 
                        </div>
                        <div class="col-md-4">
                            <!-- <input type="text" v-model="and_data_month" name="and_data_month" class="form-control"  placeholder="and month">  -->
                            <select class="form-control" v-model="and_data_month" name="and_data_month"> 
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

                    <hr v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">

                    <div class="form-group clearfix row"  v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> facebook / twitter </label>
                        <div class="col-md-4">
                            <input type="text" v-model="fb_link" name="fb_link" class="form-control"> 
                        </div>
                        <div class="col-md-4">
                            <input type="text" v-model="twit_link" name="twit_link" class="form-control"> 
                        </div>
                    </div>
                    <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> google / instagram </label>
                        <div class="col-md-4">
                            <input type="text" v-model="google_link" name="google_link" class="form-control"> 
                        </div>
                        <div class="col-md-4">
                            <input type="text" v-model="inst_link" name="inst_link" class="form-control"> 
                        </div>
                    </div>
                    <div class="form-group clearfix row" v-if="this.category == 'event' || this.category == 'partner' || this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> website </label>
                        <div class="col-md-8">
                            <!-- <input type="text" v-model="name" name="value name" value="old data" class="form-control"> -->
                            <input type="text" v-model="web_link" name="web_link" class="form-control"> 
                        </div>
                    </div>
                </form>

                <form @submit="upload_region_image" ref="sectorRegionImage" v-if="this.category == 'outdoor'">
                    <div class="form-group clearfix row">
                        <label for="email" class='col-md-5 control-label'>Upload outdoor climbing area sector images:</label>
                        <div class="col-md-6">
                            <input type="file" name="region_sectors_img" id="region_sectors_img">
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
                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Title </label>
                        <div class="col-md-8">
                            <input type="text" name="name" v-model="us_title"  class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="us_article_error.us_title">
                                {{ us_article_error.us_title[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Short description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text" name="short_description" v-model="us_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_short_description" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="us_article_error.us_short_description">
                                {{ us_article_error.us_short_description[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> text </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="text" rows="15" v-model="us_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_text" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="us_article_error.us_text">
                                {{ us_article_error.us_text[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                        <label for="name" class='col-md-2 control-label'> Routes description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="route" rows="15" v-model="us_route" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_route" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> How to get hear </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="how_get" rows="15" v-model="us_how_get" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_how_get" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> Best time for climbing </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="best_time" rows="15" v-model="us_best_time" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_best_time" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> what you need </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="what_need" rows="15" v-model="us_what_need" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_what_need" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Info / contact </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="info" rows="15" v-model="us_info" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_info" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="us_article_error.us_info">
                                {{ us_article_error.us_info[0] }}
                            </div>
                        </div>
                    </div>

                    

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Price description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text" name="prise_text" rows="15" v-model="us_price_text"  class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_price_text" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <hr>


                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Meta keyword </label>
                        <div class="col-md-8">
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
                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Title </label>
                        <div class="col-md-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="title" v-model="ru_title" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_title">
                                {{ ru_article_error.ru_title[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Short description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="short_description" v-model="ru_short_description"  rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_short_description" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_short_description">
                                {{ ru_article_error.ru_short_description[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> text </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="text" rows="15"  v-model="ru_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_text" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_text">
                                {{ ru_article_error.ru_text[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                        <label for="name" class='col-md-2 control-label'> Routes description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="route" rows="15"  v-model="ru_route" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_route" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> How to get hear </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="how_get" rows="15"  v-model="ru_how_get" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="us_how_get" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> Best time for climbing </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="best_time" rows="15"  v-model="ru_best_time" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_best_time" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> what you need </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="what_need" rows="15" v-model="ru_what_need" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_what_need" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Info / contact </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="info" rows="15" v-model="ru_info" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_info" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ru_article_error.ru_info">
                                {{ ru_article_error.ru_info[0] }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Price description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text" name="price_text" rows="15"  v-model="ru_price_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ru_price_text" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <hr>


                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Meta keyword </label>
                        <div class="col-md-8">
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
                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Title </label>
                        <div class="col-md-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="value name"  v-model="ka_title" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_text">
                                {{ ka_article_error.ka_text[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Short description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="short_description"  v-model="ka_short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_short_description" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_short_description">
                                {{ ka_article_error.ka_short_description[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> text </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="txt" rows="15"  v-model="ka_text" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_text" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_text">
                                {{ ka_article_error.ka_text[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                        <label for="name" class='col-md-2 control-label'> Routes description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="route" rows="15"  v-model="ka_route" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_route" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> How to get hear </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="how_get" rows="15"  v-model="ka_how_get" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_how_get" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> Best time for climbing </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="best_time" rows="15"  v-model="ka_best_time" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_best_time" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> what you need </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="what_need" rows="15"  v-model="ka_what_need" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_what_need" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Info / contact </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text"  name="info" rows="15"  v-model="ka_info" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_info" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="ka_article_error.ka_info">
                                {{ ka_article_error.ka_info[0] }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Price description </label>
                        <div class="col-md-8">
                            <!-- <textarea type="text" name="prixe_text" rows="15" v-model="ka_price_text"  class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="ka_price_text" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <hr>


                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Meta keyword </label>
                        <div class="col-md-8">
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
                global_article_error: [],
                is_global_article_error: true,
                ka_article_error: [],
                is_ka_article_error: true,
                ru_article_error: [],
                is_ru_article_error: true,
                us_article_error: [],
                is_us_article_error: true,

                us_title_for_url_title: '',
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

                name: '',
                image: '',
                success: '',

                mount_data: '',


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
                us_time: "",
                us_price_from: "",
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
                ka_time: "",
                ka_price_from: "",
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
                ru_time: "",
                ru_price_from: "",
                ru_meta_keyword: "",

                temporary_article_id: '',
            }
        },
        mounted() {
            this.create_temporary_article()
            this.check_permission()
            if (this.category == 'mount_route') {
                this.get_mount_massive_data()
            }
        },
        methods: {
            create_temporary_article() {
                axios
                .post('/articles/create_temporary_article/' + this.category, {
                    // ka_title: this.ka_title,
                })
                .then((response)=>  {
                    this.get_temporary_article_data()
                    // console.log(response)
                    // this.is_ka_article_succes = 1
                    // console.log('georgian article upload successful');
                })
                .catch(error => console.log(error))
            },
            del_temporary_article(temporary_article_id) {
                axios
                .post('/articles/del_temporary_article/' + temporary_article_id, {
                    // ka_title: this.ka_title,
                })
                .then((response)=>  {
                    // this.is_ka_article_succes = 1
                    // console.log('georgian article upload successful');
                })
                .catch(error => console.log(error))
            },
            get_temporary_article_data: function(){
                axios
                .get("/articles/get_temporary_article_editing_data/")
                .then(response => {
                    this.editing_data = response.data
                    this.temporary_article_id = this.editing_data.last_temporary_article_id
                })
                .catch(
                error => console.log(error)
                );
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

            add_us_article() {
                this.is_us_article_error = false
                axios
                .post('/articles/us/add/' + this.category, {        
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
            add_ru_article() {
                axios
                .post('/articles/ru/add/' + this.category, {
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
            add_ka_article() {
                axios
                .post('/articles/ka/add/' + this.category, {
                    ka_title: this.ka_title,
                    ka_short_description: this.ka_short_description,
                    ka_text: this.ka_text,
                    ka_route: this.ka_route,
                    ka_how_get: this.ka_how_get,
                    ka_best_time: this.ka_best_time,
                    ka_what_need: this.ka_what_need,
                    ka_info: this.ka_info,
                    ka_time: this.ka_time,
                    ka_price_text: this.ka_price_text,
                    ka_price_from: this.ka_price_from,
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
            add_global_article() {
                axios
                .post('/articles/global/add/' + this.category, {
                    us_title_for_url_title: this.us_title,

                    published: this.published,
                    mount_id: this.mount_id,
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
            
            back: function(temporary_article_id) {
                confirm('Are you sure, you want go back?')
                this.del_temporary_article(temporary_article_id)
                window.location.href = this.back_url;
            },
            save_all() {
                this.add_us_article()
                this.add_ka_article()
                this.add_ru_article()
                this.add_global_article()

                this.checkForm()
                this.upload_region_image()
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
            },

            check_permission() {
                axios
                .get('/users/get_user_parmisions/', { })
                .then((response)=> { 
                    
                })
                .catch(error =>{
                    
                })
            }
        }
    }
</script>
