<template>

    <div class="col_md_12">

        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>

        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_region()" >Save</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >Global</label>
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
            <div class="row" v-show="tab_num == 1">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region global information</span></h2>
                        <p class="lead">Region global information.</p>
                    </div>
                </div>
                <form >
                    <div class="form-group clearfix row" >
                        <label for="name" class='col-md-2 control-label'> Map </label>
                        <div class="col-md-9">
                            <input type="text" v-model="data.map" name="map" class="form-control"> 
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" v-show="tab_num == 2">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region English information</span></h2>
                        <p class="lead">Region English information.</p>
                    </div>
                </div>
                <form >
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> English name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="data.us_name" name="us_name" class="form-control"> 
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.us_name">
                                {{ errors.us_name[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="region" class='col-md-2 control-label '> English text </label>
                        
                        <ckeditor v-model="data.us_text"></ckeditor>
                    </div>
                </form>
            </div>
            <div class="row" v-show="tab_num == 3">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region Georgian information</span></h2>
                        <p class="lead">Region Georgian information.</p>
                    </div>
                </div>
                <form >
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Georgian name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="data.ka_name" name="ru_name" class="form-control"> 
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.ru_name">
                                {{ errors.ru_name[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="region" class='col-md-2 control-label '> Georgian text </label>
                        
                        <ckeditor v-model="data.ka_text"></ckeditor>
                    </div>
                </form>
            </div>
            <div class="row" v-show="tab_num == 4">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Region Russion information</span></h2>
                        <p class="lead">Region Russion information.</p>
                    </div>
                </div>
                <form>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Russion name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="data.ru_name" name="ka_name" class="form-control"> 
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                                {{ errors.ka_name[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="region" class='col-md-2 control-label '> Russion text </label>
                        
                        <ckeditor v-model="data.ru_text"></ckeditor>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- <div class="col_md_12">
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

                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="edit_category"  style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> us name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="us_name" name="us_name" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.us_name">
                                {{ errors.us_name[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ru name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="ru_name" name="ru_name" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.ru_name">
                                {{ errors.ru_name[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ka name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="ka_name" name="ka_name" class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.ka_name">
                                {{ errors.ka_name[0] }}
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div> -->
</template>

<script>
    export default {
        props: [
            'back_url',
            'editing_category_id'
        ],
        data(){
            return {
                us_name: '',
                ru_name: '',
                ka_name: '',

                editing_data: '',

                errors: [],
            }
        },
        mounted() {
            this.get_editing_category_data()
        },
        methods: {
            get_editing_category_data() {
                axios
                .get('/products/get_editing_category_data/' + this.editing_category_id)
                .then(response => { 
                    this.editing_data = response.data
                    this.us_name = this.editing_data.product_category['us_name'],
                    this.ru_name = this.editing_data.product_category['ru_name'],
                    this.ka_name = this.editing_data.product_category['ka_name']
                })
                .catch(error => console.log(error))
            },
            edit_category() {
                axios
                .post('/products/category/edit/' + this.editing_category_id, {        
                    us_name: this.us_name,
                    ru_name: this.ru_name,
                    ka_name: this.ka_name,


                    inst_link: this.inst_link,
                    web_link: this.web_link,
                })
                .then((response)=> { 
                    window.location.href = this.back_url;
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
            },

            save_all() {
                this.edit_category()
            }
        }
    }
</script>
