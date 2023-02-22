<template>
    <div class="tabs"> 
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_event()" >Save</button>
            </div>
        </div>
        <div class="row" v-if="error.length != 0">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.published">
                    Published - {{ error.global_info_validation.published[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.start_data">
                    Start data - {{ error.global_info_validation.start_data[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.global_info_validation.end_data">
                    End data - {{ error.global_info_validation.end_data[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.title">
                    English title - {{ error.us_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.short_description">
                    English description - {{ error.us_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.us_info_validation.text">
                    English text - {{ error.us_info_validation.text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.title">
                    Georgian title - {{ error.ka_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.short_description">
                    Georgian description - {{ error.ka_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ka_info_validation.text">
                    Georgian text - {{ error.ka_info_validation.text[0] }}
                </div>

                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.title">
                    Russion title - {{ error.ru_info_validation.title[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.short_description">
                    Russiondescription - {{ error.ru_info_validation.short_description[0] }}
                </div>
                <div class="alert alert-danger" role="alert" v-if="error.ru_info_validation.text">
                    Russion text - {{ error.ru_info_validation.text[0] }}
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
                        
                        <label for="3" >Georgian text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >Russion text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>Event global information</span></h2>
                                <p class="lead">Event global information.</p>
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
                        <!-- <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Category </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_data.category" name="published" > 
                                    <option value="event">Event</option> 
                                    <option value="competition" disabled>Competition</option> 
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Event location </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_data.map"  class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="start_datatle" class='col-xs-2 control-label'> Start and end data </label>
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="start_datatle" class="form-control" v-model="data.global_data.start_data" placeholder="Start data/time"> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="end_data" class="form-control" v-model="data.global_data.end_data" placeholder="End data/time"> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="image" class='col-xs-6 control-label'>Olredi uploaded image:</label>
                            <div class="col-md-6">
                                <img class="img-responsive" :src="'../../../../images/event_img/'+data.global_data.image" :alt="'image'">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="image" class='col-xs-6 control-label'>Upload article image:</label>
                            <div class="col-xs-6">
                                <input type="file" name="image" id="image" required v-on:change="onFileChange">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Event english version</h2>
                            <p class="lead">Event english version for site localisation.</p>
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
                                <input type="checkbox" id="scales" name="scales" @click="change_url_title_in_global_bisnes()">
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.short_description" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.text" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> contact info </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.info" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Event rusion version</h2>
                            <p class="lead">Event rusion version for site localisation.</p>
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
                                <ckeditor v-model="data.ru_data.short_description" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ru_data.text" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> contact info </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ru_data.info" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Event georgian version</h2>
                            <p class="lead">Event georgian version for site localisation.</p>
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
                                <ckeditor v-model="data.ka_data.short_description" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_data.text" :config="this.$editorConfig"></ckeditor>
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> contact info </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_data.info" :config="this.$editorConfig"></ckeditor>
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
        // 'back_url',
    ],
    data(){
        return {
            tab_num: 1,

            images: [],
            editorConfig: '',

            change_url_title: false,

            data: {
                global_data: {
                    us_title_for_url_title: '',

                    start_data: '',
                    end_data: '',

                    category: 'event',
                    map: '',

                    change_url_title: '',

                    published: 0,
                },

                us_data: {
                    title: "",
                    short_description: "",
                    text: "",
                    info: "",
                },

                ka_data: {
                    title: "",
                    short_description: "",
                    text: "",
                    info: "",
                },

                ru_data: {
                    title: "",
                    short_description: "",
                    text: "",
                    info: "",
                }
            },
        }
    },
    mounted() {
        this.get_editing_event()
    },
    methods: {
        onFileChange(event){
            this.image = event.target.files[0];
        },

        get_editing_event() {
            axios
            .get('../../api/event/get_editing_event/'+this.$route.params.id)
            .then(response => {
                this.data = response.data

                this.data.global_data = response.data.global_data
                this.data.us_data = response.data.us_data
                this.data.ru_data = response.data.ru_data
                this.data.ka_data = response.data.ka_data
            })
            .catch(
                error => console.log(error)
            );
        },

        change_url_title_in_global_bisnes(){
            if(!this.change_url_title){
                if(confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')){
                    this.change_url_title = true
                }
            }
            else{
                this.change_url_title = false 
            }
        },

        edit_event() {
            if (this.change_url_title) {
                this.data.global_data.change_url_title = this.change_url_title
                this.data.global_data.us_title_for_url_title = this.data.us_data.title
            }
            else{
                this.data.global_data.change_url_title = false
                this.data.global_data.us_title_for_url_title = false
            }

            let formData = new FormData();

            formData.append('image', this.image)
            formData.append('data', JSON.stringify(this.data))

            axios
            .post('../../api/event/edit_event/'+this.$route.params.id, 
                formData
            )
            .then(response => {
                this.go_back(true)
            })
            .catch(error => {
                if (error.response.status == 422) {
                    this.error = error.response.data.validation
                }
            });
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