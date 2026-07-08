<template>
    <div class="tabs"> 
        <div class="col_md_12">
            <div class="row">
                <div class="form-group">  
                    <button type="submit" class="btn btn-primary"  form="add_genral_info_form" >{{ $t('common.save') }}</button>
                    <button type="submit" class="btn btn-primary" v-on:click="go_back()" >{{ $t('admin.shop.go_back') }}</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
        
                    <input type="radio" name="tabs" id="1" checked="checked">
                    <label for="1" >{{ $t('common.global_info') }}</label>
                    <div class="tab" >
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h2 class="display-4">{{ $t('admin.site_info.add_global_info_title') }}</h2>
                                <!-- <p class="lead">Add information for using this info in many articles.</p> -->
                            </div>
                        </div>
        
                        <form v-on:submit.prevent="add_global_info" id="add_genral_info_form" class="form">
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.title" name="us_name" class="form-control" required>
                                    <div class="alert alert-danger" role="alert" v-if="errors.title">
                                        {{ errors.title[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> {{ $t('admin.site_info.is_show_action_label') }} </label>
                                <div class="col-xs-10">
                                    <input type="checkbox" v-model="data.is_show" name="is_show" class="form-control">
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> {{ $t('common.english_text') }} </label>
                                <div class="col-xs-10">
                                    <big_editor v-model="data.us_text" />
                                </div>
                                <div class="alert alert-danger" role="alert" v-if="errors.us_text">
                                    {{ errors.us_text[0] }}
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> {{ $t('common.georgian_text') }} </label>
                                <div class="col-xs-10">
                                    <big_editor v-model="data.ka_text" />
                                </div>
                                <div class="alert alert-danger" role="alert" v-if="errors.ka_text">
                                    {{ errors.ka_text[0] }}
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
        ],
        data(){
            return {
                data: {
                    title: '',
                    is_show: false,

                    us_text: '',
                    ru_text: '',
                    ka_text: '',
                },

                is_loading: false,

                errors: [],
            }
        },
        mounted() {
            document.querySelector('body').style.marginLeft = '0';

            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {

            add_global_info() {
                this.is_loading = true
                axios
                .post('/set_general_info/add_general_info/', {
                    data: this.data,
                    _method: 'POST'
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
    