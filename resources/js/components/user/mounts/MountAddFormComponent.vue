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
            <label for="1" >georgian article</label>
            <div class="tab">

                <form name="contact-form" method="POST" acrion='#' style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Publish </label>
                        <div class="col-xs-8">
                            <select class="form-control" v-model="published" name="published" > 
                                <option value="0">Not public</option> 
                                <option value="1">Public</option> 
                            </select> 
                            <div class="alert alert-danger" role="alert" v-if="errors.published">
                                {{ errors.published[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Map </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="map" name="map" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.map">
                                {{ errors.map[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix"  v-if="this.category != 'event' || this.category != 'indoor' || this.category != 'partner' || this.category != 'event' || this.category != 'news'">
                        <label for="name" class='col-xs-2 control-label'> Weather </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="weather" name="weather" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.weather">
                                {{ errors.weather[0] }}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <input type="radio" name="tabs" id="2">
            <label for="2" >english info</label>
            <div class="tab" >
                <form id="contact-form form-horizontal" name="contact-form" method="POST" action="#" style="margin-top: 5%;" enctype ="multipart/form-data">

                    
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title english </label>
                        <div class="col-xs-8">
                            <input type="text" name="name" v-model="name" class="form-control">
                            <div class="alert alert-danger" role="alert" v-if="errors.name">
                                {{ errors.name[0] }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> English text </label>
                        <div class="col-xs-8">
                                <!-- <textarea type="text"  name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="text" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.text">
                                {{ errors.text[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> English description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="short_description" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.short_description">
                                {{ errors.short_description[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> English how get hear </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="how_get" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.how_get">
                                {{ errors.how_get[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> English best time </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="best_time" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.best_time">
                                {{ errors.best_time[0] }}
                            </div>
                        </div>
                    </div>

                </form>


            </div>

            <input type="radio" name="tabs" id="3">
            <label for="3" >rusian article</label>
            <div class="tab" >

                <form name="contact-form" method="POST" @submit.prevent="add_us_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title rusian </label>
                        <div class="col-xs-8">
                            <input type="text" name="name_ru" v-model="name_ru" class="form-control">
                            <div class="alert alert-danger" role="alert" v-if="errors.name_ru">
                                {{ errors.name_ru[0] }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Rusian text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="text_ru" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="text_ru" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.text_ru">
                                {{ errors.text_ru[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Rusian description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="description_ru" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="short_description_ru" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.short_description_ru">
                                {{ errors.short_description_ru[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Rusian how get hear </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="how_get_ru" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="how_get_ru" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.how_get_ru">
                                {{ errors.how_get_ru[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Rusian best time </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="best_time_ru" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="best_time_ru" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.best_time_ru">
                                {{ errors.best_time_ru[0] }}
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <input type="radio" name="tabs" id="4">
            <label for="4" >georgian article</label>
            <div class="tab">

                <form name="contact-form" method="POST" @submit.prevent="add_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title georgian </label>
                        <div class="col-xs-8">
                            <input type="text" name="name_ka" v-model="name_ka" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.name_ka">
                                {{ errors.name_ka[0] }}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Georgian text </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="text_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="text_ka" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.text_ka">
                                {{ errors.text_ka[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Georgian description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="description_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="short_description_ka" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.short_description_ka">
                                {{ errors.short_description_ka[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Georgian how get hear </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="how_get_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="how_get_ka" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.how_get_ka">
                                {{ errors.how_get_ka[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Georgian best time </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text"  name="best_time_ka" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                            <ckeditor v-model="best_time_ka" :config="editorConfig"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.best_time_ka">
                                {{ errors.best_time_ka[0] }}
                            </div>
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
                map: "",
                weather: "",
                published: "",

                errors: [],

                // 
                // 
                // 
                name: "",
                short_description: "",
                text: "",
                route: "",
                how_get: "",
                best_time: "",


                // 
                // 
                // 
                name_ka: "",
                short_description_ka: "",
                text_ka: "",
                route_ka: "",
                how_get_ka: "",
                best_time_ka: "",


                // 
                // 
                // 
                name_ru: "",
                short_description_ru: "",
                text_ru: "",
                how_get_ru: "",
                best_time_ru: "",
            }
        },
        mounted() {
            // console.log(this.catagory)
        },
        methods: {
            add_mount: function () {
                axios
                .post('/mountaineering/mount_add/', {
                    map: this.map,
                    weather: this.weather,
                    published: this.published,

                    name: this.name,
                    short_description: this.short_description,
                    text: this.text,
                    how_get: this.how_get,
                    best_time: this.best_time,

                    name_ka: this.name_ka,
                    short_description_ka: this.short_description_ka,
                    text_ka: this.text_ka,
                    how_get_ka: this.how_get_ka,
                    best_time_ka: this.best_time_ka,

                    name_ru: this.name_ru,
                    short_description_ru: this.short_description_ru,
                    text_ru: this.text_ru,
                    how_get_ru: this.how_get_ru,
                    best_time_ru: this.best_time_ru,
                })
                .then(Response => { 
                    window.location.href = '../../mountaineering';
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
            },


            save_all() {
                this.add_mount();
            }
        }
    }
</script>
