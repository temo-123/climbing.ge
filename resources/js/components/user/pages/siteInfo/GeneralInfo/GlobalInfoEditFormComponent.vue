<template>
    <div class="tabs"> 
        <div class="col_md_12">
            <div class="row">
                <div class="form-group">  
                    <button type="submit" class="btn btn-primary"  form="edit_genral_info_form" >Save</button>
                    <button type="submit" class="btn btn-primary" v-on:click="go_back()" >Go back</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
        
                    <input type="radio" name="tabs" id="1" checked="checked">
                    <label for="1" >Global info</label>
                    <div class="tab" >
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h2 class="display-4">Add Global info</h2>
                                <!-- <p class="lead">Add information for using this info in many articles.</p> -->
                            </div>
                        </div>
        
                        <form v-on:submit.prevent="edit_general_info" id="edit_genral_info_form" class="form">
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> Title </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.title" name="us_name" class="form-control" required> 
                                <div class="alert alert-danger" role="alert" v-if="errors.title">
                                    {{ errors.title[0] }}
                                </div>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> English name </label>
                                <div class="col-xs-10">
                                    <ckeditor v-model="data.us_text" :config="this.$editorConfig"></ckeditor>
                                </div>
                                <div class="alert alert-danger" role="alert" v-if="errors.us_text">
                                    {{ errors.us_text[0] }}
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> Georgian name </label>
                                <div class="col-xs-10">
                                    <ckeditor v-model="data.ka_text" :config="this.$editorConfig"></ckeditor>
                                </div>
                                <div class="alert alert-danger" role="alert" v-if="errors.ka_text">
                                    {{ errors.ka_text[0] }}
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> Russian name </label>
                                <div class="col-xs-10">
                                    <ckeditor v-model="data.ru_text" :config="this.$editorConfig"></ckeditor>
                                </div>
                                <div class="alert alert-danger" role="alert" v-if="errors.ru_text">
                                    {{ errors.ru_text[0] }}
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
        data(){
            return {
                data: {
                    title: '',

                    us_text: '',
                    ru_text: '',
                    ka_text: '',
                },

                is_loading: false,

                errors: [],

                editing_general_info_id: this.$route.params.id,
            }
        },
        mounted() {
            this.get_editing_general_info_data()
    
            document.querySelector('body').style.marginLeft = '0';

            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            get_editing_general_info_data() {
                this.is_loading = true
                axios
                .get('../../api/general_info/' + this.editing_general_info_id)
                .then(response => {
                    this.data.title = response.data['title']
                    this.data.us_text = response.data['text_us']
                    this.data.ru_text = response.data['text_ru']
                    this.data.ka_text = response.data['text_ka']
                })
                .catch(error => console.log(error))
                .finally(() => this.is_loading = false);
            },
            edit_general_info() {
                this.is_loading = true

                axios
                .post('../../api/general_info/' + this.editing_general_info_id, {        
                    data: this.data,
                    _method: 'PATCH'
                })
                .then((response)=> { 
                    this.go_back()
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
                .finally(() => this.is_loading = false);
            },

            go_back() {
                this.$router.go(-1)
            }
        }
    }
</script>
