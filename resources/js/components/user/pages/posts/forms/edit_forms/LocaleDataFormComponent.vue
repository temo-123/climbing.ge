<template>
    <div class="col-md-12">
        <div class="jumbotron width_100">
            <div class="container">
                <h2 class="display-4"><span style="text-transform: capitalize">{{this.category_prop}}</span> {{ this.title_prop }}</h2>
                <p class="lead">{{ this.description_prop }}</p>
            </div>
        </div>
        <div class="wrapper container-fluid container">
            <form method="POST" :headers="{'x-csrf-token': token}">
                <div class="form-group clearfix row" >
                    <label for="name" class='col-md-2 control-label'> Title </label>
                    <div class="col-md-10">
                        <input type="text" name="name" v-model="data.title" class="form-control"> 
                    </div>
                </div>
                <div class="form-group clearfix row" v-if="locale_prop == 'us'">
                    <label for="name" class='col-md-4 control-label'> Change URL title </label>
                    <div class="col-md-8">
                        <input type="checkbox" id="scales" name="scales" v-model="is_change_url_title" @click="change_url_title_in_post()">
                    </div>
                </div>
                <div class="form-group clearfix row">
                    <label for="name" class='col-md-2 control-label'> Short description </label>
                    <div class="col-md-10">
                        <ckeditor v-model="data.short_description" :config="editor_config.short_description_text"></ckeditor>
                    </div>
                </div>
                <div class="form-group clearfix row">
                    <label for="name" class='col-md-2 control-label'> Text </label>
                    <div class="col-md-10">
                        <ckeditor v-model="data.text" :config="editor_config.text" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'locale_data_prop',
            'category_prop',
            'locale_prop',
            'title_prop',
            'description_prop'
        ],
        watch: {
            category_prop(newVal) { this.category = newVal; },
            locale_data_prop(newVal) { this.data = newVal; }
        },
        data(){
            return {
                category: this.category_prop,
                is_change_url_title: false,
                editor_config: {
                    short_description_text: this.$editor_config.get_small_editor_config(),
                    text: this.$editor_config.get_big_editor_config(),
                },
                token: '',
                data: this.locale_data_prop,
            }
        },
        mounted() {
            this.category = this.category_prop;
        },
        methods: {
            change_url_title_in_post(){
                if(!this.is_change_url_title){
                    if(confirm('Are you sure, you want change URL title? It will be bad for SEO optimization')){
                        this.is_change_url_title = true
                    } else {
                        this.is_change_url_title = false 
                    }
                } else {
                    this.is_change_url_title = false 
                }
                this.data.is_change_url_title = this.is_change_url_title
            },
            send_data(){
                this.$emit('send_data', {
                    locale: this.locale_prop, 
                    data: this.data
                })
            },
        }
    }
</script>

<style>

</style>