<template>
<div class="col_md_12">
    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" v-on:click="edit_category()" >Save</button>
        </div>
    </div>
    <div class="row">
        <div class="tabs">

            <input type="radio" name="tabs" id="1" checked="checked">
            <label for="1" >Global info</label>
            <div class="tab" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">Edit Product Category</h2>
                        <!-- <p class="lead">Article global information.</p> -->
                    </div>
                </div>

                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="edit_category"  style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> us name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="editing_data.us_name" name="us_name" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.us_name">
                                {{ errors.us_name[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ru name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="editing_data.ru_name" name="ru_name" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.ru_name">
                                {{ errors.ru_name[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ka name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="editing_data.ka_name" name="ka_name" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                                {{ errors.ka_name[0] }}
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
        data(){
            return {
                editing_data: {
                    us_name: '',
                    ru_name: '',
                    ka_name: '',
                },

                errors: [],

                editing_category_id: this.$route.params.id,
            }
        },
        mounted() {
            this.get_editing_category_data()
        },
        methods: {
            get_editing_category_data() {
                axios
                .get('../../../api/product_category/' + this.editing_category_id)
                .then(response => {
                    this.editing_data.us_name = response.data['us_name'],
                    this.editing_data.ru_name = response.data['ru_name'],
                    this.editing_data.ka_name = response.data['ka_name']
                })
                .catch(error => console.log(error))
            },
            edit_category() {
                axios
                .post('../../../api/product_category/' + this.editing_category_id, {        
                    editing_data: this.editing_data,
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
            },

            go_back() {
                this.$router.go(-1)
            }
        }
    }
</script>
