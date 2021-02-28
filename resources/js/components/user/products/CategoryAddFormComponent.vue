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
            <label for="1" >global info</label>
            <div class="tab" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} article global information</h2>
                        <p class="lead">Article global information.</p>
                    </div>
                </div>

                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="add_category"  style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> us name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="us_name" name="us_name" class="form-control"> 
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ru name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="ru_name" name="ru_name" class="form-control"> 
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ka name </label>
                        <div class="col-xs-8">
                            <input type="text" v-model="ka_name" name="ka_name" class="form-control"> 
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
        ],
        data(){
            return {
                us_name: '',
                ru_name: '',
                ka_name: '',
            }
        },
        mounted() {
        },
        methods: {

            add_category() {
                axios
                .post('/products/category/add/', {        
                    us_name: this.us_name,
                    ru_name: this.ru_name,
                    ka_name: this.ka_name,
                })
                .then((response)=> { 
                    // ...
                })
                .catch(error => console.log(error))
            },

            save_all() {
                this.add_category()

                window.location.href = this.back_url;
            }
        }
    }
</script>
