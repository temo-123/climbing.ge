<template>
    <div class="tabs"> 
        <div class="col_md_12">
            <div class="row">
                <div class="form-group">  
                    <button type="submit" class="btn btn-primary"  form="add_product_category" >{{ $t('common.save') }}</button>
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
                                <h2 class="display-4">{{ $t('admin.shop.add_category_title') }}</h2>
                                <!-- <p class="lead">Article global information.</p> -->
                            </div>
                        </div>

                        <form v-on:submit.prevent="add_category" id="add_product_category" class="form">
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.en_name_label') }} </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.us_name" name="us_name" class="form-control" required>
                                    <div class="alert alert-danger" role="alert" v-if="errors.us_name">
                                        {{ errors.us_name[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> {{ $t('admin.shop.ka_name_label') }} </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.ka_name" name="ka_name" class="form-control" required>
                                    <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                                        {{ errors.ka_name[0] }}
                                    </div>
                                </div>
                            </div>
                        </form>

                        <h2>{{ $t('admin.shop.subcategory_hint') }}</h2>
        
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
                    us_name: '',
                    // ru_name: '',
                    ka_name: '',
                },

                is_back_action_query: true,

                errors: [],
            }
        },
        mounted() {
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm(this.$t('common.confirm_leave_unsaved'))) {
                    this.is_back_action_query = false;
                    next()
                } else {
                    next(false)
                }
            }else {
                next()
            }
        },
        methods: {

            add_category() {
                axios
                .post('/set_product/set_product_category/add_product_category', {
                    data: this.data,
                    _method: 'POST'
                })
                .then((response)=> { 
                    this.go_back(true)
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
            },

            go_back: function(action = false) {
                this.is_back_action_query = this.$going.back(this, action)
            },
        }
    }
</script>
