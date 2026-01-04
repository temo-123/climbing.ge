<template>
    <div class="tabs"> 
        <div class="col_md_12">
            <div class="row">
                <div class="form-group">  
                    <button type="submit" class="btn btn-primary"  form="edi_product_category" >Save</button>
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
                                <h2 class="display-4">Edit Product Category</h2>
                                <!-- <p class="lead">Article global information.</p> -->
                            </div>
                        </div>

                        <form v-on:submit.prevent="edit_category" id="edi_product_category" class="form">
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
                                <label for="name" class='col-xs-2 control-label'> ka name </label>
                                <div class="col-xs-10">
                                    <input type="text" v-model="data.ka_name" name="ka_name" class="form-control" required> 
                                    <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                                        {{ errors.ka_name[0] }}
                                    </div>
                                </div>
                            </div>
                        </form>
        
                        <subcategoryControl :category_id_prop="data.id"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import subcategoryControl from './subcategories/subcategoryControlComponent.vue'

    export default {
        components: {
            subcategoryControl,
        },
        data(){
            return {
                data: {
                    us_name: '',
                    // ru_name: '',
                    ka_name: '',
                },

                is_back_action_query: true,

                errors: [],

                editing_category_id: this.$route.params.id,
            }
        },
        mounted() {
            this.get_editing_category_data()
        
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        beforeRouteLeave (to, from, next) {
            if(this.is_back_action_query == true){
                if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
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
            get_editing_category_data() {
                axios
                .get('/set_product/set_product_category/get_editing_product_category/' + this.editing_category_id)
                .then(response => {
                    this.data = response.data
                })
                .catch(error => console.log(error))
            },
            edit_category() {
                axios
                .post('/set_product/set_product_category/edit_product_category/' + this.editing_category_id, {        
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
