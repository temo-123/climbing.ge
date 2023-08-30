<template>
    <div class="tabs"> 
        <div class="col_md_12">
            <div class="row">
                <div class="form-group">  
                    <button type="submit" class="btn btn-primary"  form="add_product_category" >Save</button>
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
                                <h2 class="display-4">Add Product Category</h2>
                                <!-- <p class="lead">Article global information.</p> -->
                            </div>
                        </div>

                        <form v-on:submit.prevent="add_category" id="add_product_category" class="form">
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> us name </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.us_name" name="us_name" class="form-control" required> 
                                    <div class="alert alert-danger" role="alert" v-if="errors.us_name">
                                        {{ errors.us_name[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> ru name </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.ru_name" name="ru_name" class="form-control" required> 
                                    <div class="alert alert-danger" role="alert" v-if="errors.ru_name">
                                        {{ errors.ru_name[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group clearfix">
                                <label for="name" class='col-xs-2 control-label'> ka name </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.ka_name" name="ka_name" class="form-control" required> 
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
                    ru_name: '',
                    ka_name: '',
                },

                errors: [],
            }
        },
        mounted() {
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {

            add_category() {
                axios
                .post('../../api/product_category/', {
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
            },

            go_back() {
                this.$router.go(-1)
            }
        }
    }
</script>
