<template>
   <div class="row">
            <div class="col-md-12">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">{{category}}</span> {{ title }}</h2>
                        <p class="lead">{{ description }}</p>
                    </div>
                </div>
                <form method="POST" @submit.prevent="edit_locale_article">
                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Title </label>
                        <div class="col-md-10">
                            <input type="text" name="name" v-model="data.title"  class="form-control"> 
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.title">
                                {{ errors.title[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Short description </label>
                        <div class="col-md-10">
                            <ckeditor v-model="data.short_description"></ckeditor>
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.short_description">
                                {{ errors.short_description[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> text </label>
                        <div class="col-md-10">
                            <ckeditor v-model="data.text"></ckeditor>
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.text">
                                {{ errors.text[0] }}
                            </div> -->
                        </div>
                    </div>

                    <hr v-if="category == 'outdoor'">

                    <div  v-if="general_infos.length">
                        <div class="row" v-if="category == 'outdoor'">
                            <div class="col-md-2">
                                <input type="radio" id="routes_new_info" name="fav_language" value="new_info" @click="routes_action('new_info')">
                                <label for="routes_new_info">New info</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="routes_befor" name="fav_language" value="befor" @click="routes_action('befor')">
                                <label for="routes_befor">Befor</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="routes_after" name="fav_language" value="after" @click="routes_action('after')">
                                <label for="routes_after">After</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="routes_instead" name="fav_language" value="instead" @click="routes_action('instead')">
                                <label for="routes_instead">Instead</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="category == 'outdoor'">
                        <label for="name" class='col-md-2 control-label'> Routes description </label>

                        <div class="col-md-10">
                            <div class="col-md-12" v-if="global_blocks.routes_info == 'befor'" >
                                <select class="form-control"  v-model="global_blocks.routes_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        
                            <div class="col-md-12" v-if="global_blocks.routes_info == 'befor' || global_blocks.routes_info == 'after' || global_blocks.routes_info == 'new_info'">
                                <ckeditor v-model="data.route"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.routes_info == 'after' || global_blocks.routes_info == 'instead'">
                                <select class="form-control"  v-model="global_blocks.routes_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="category != 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> How to get hear </label>
                        <div class="col-md-10">
                            <!-- <ckeditor v-model="data.how_get" :config="editorConfig"></ckeditor> -->
                            <ckeditor v-model="data.how_get"></ckeditor>
                        </div>
                    </div>

                    <hr v-if="category == 'outdoor' || category == 'ice'">

                    <div  v-if="general_infos.length">
                        <div class="row" v-if="category == 'outdoor' || category == 'ice'">
                            <div class="col-md-2">
                                <input type="radio" id="time_new_info" name="fav_language" value="new_info" @click="best_time_action('new_info')">
                                <label for="time_new_info">New info</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="time_befor" name="fav_language" value="befor" @click="best_time_action('befor')">
                                <label for="time_befor">Befor</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="time_after" name="fav_language" value="after" @click="best_time_action('after')">
                                <label for="time_after">After</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="time_instead" name="fav_language" value="instead" @click="best_time_action('instead')">
                                <label for="time_instead">Instead</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="category == 'outdoor' || category == 'ice'">
                        <label for="name" class='col-md-2 control-label'> Best time for climbing </label>

                        <div class="col-md-10">
                            <div class="col-md-12" v-if="global_blocks.best_time == 'befor'">
                                <select class="form-control"  v-model="global_blocks.best_time_id" >
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        
                            <div class="col-md-12" v-if="global_blocks.best_time == 'befor' || global_blocks.best_time == 'after' || global_blocks.best_time == 'new_info'">
                                <ckeditor v-model="data.best_time"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.best_time == 'after' || global_blocks.best_time == 'instead'">
                                <select class="form-control"  v-model="global_blocks.best_time_id" >
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div>

                    <hr v-if="category == 'outdoor' || category == 'ice' || category == 'mount_route' ">

                    <div  v-if="general_infos.length">
                        <div class="row" v-if="category == 'outdoor' || category == 'ice' || category == 'mount_route' ">
                            <div class="col-md-2">
                                <input type="radio" id="need_new_info" name="fav_language" value="new_info" @click="what_need_block_action('new_info')">
                                <label for="need_new_info">New info</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="need_befor" name="fav_language" value="befor" @click="what_need_block_action('befor')">
                                <label for="need_befor">Befor</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="need_after" name="fav_language" value="after" @click="what_need_block_action('after')">
                                <label for="need_after">After</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="need_instead" name="fav_language" value="instead" @click="what_need_block_action('instead')">
                                <label for="need_instead">Instead</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="category == 'outdoor' || category == 'ice' || category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> what you need </label>

                        <div class="col-md-10">
                            <div class="col-md-12" v-if="global_blocks.what_need_info == 'befor'">
                                <select class="form-control"  v-model="global_blocks.what_need_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        
                            <div class="col-md-12" v-if="global_blocks.what_need_info == 'befor' || global_blocks.what_need_info == 'after' || global_blocks.what_need_info == 'new_info'">
                                <ckeditor v-model="data.what_need"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.what_need_info == 'after' || global_blocks.what_need_info == 'instead'">
                                <select class="form-control"  v-model="global_blocks.what_need_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div  v-if="general_infos.length">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="radio" id="new_info" name="fav_language" value="new_info" @click="info_block_action('new_info')">
                                <label for="new_info">New info</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="befor" name="fav_language" value="befor" @click="info_block_action('befor')">
                                <label for="befor">Befor</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="after" name="fav_language" value="after" @click="info_block_action('after')">
                                <label for="after">After</label><br>
                            </div>
                            <div class="col-md-2">
                                <input type="radio" id="instead" name="fav_language" value="instead" @click="info_block_action('instead')">
                                <label for="instead">Instead</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Info / contact </label>
                        <div class="col-md-10">
                            <div class="col-md-12" v-if="global_blocks.info_block == 'befor'">
                                <select v-model="global_blocks.info_block_id"  class="form-control"> 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        
                            <div class="col-md-12" v-if="global_blocks.info_block == 'befor' || global_blocks.info_block == 'after' || global_blocks.info_block == 'new_info'">
                                <ckeditor v-model="data.info"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.info_block == 'after' || global_blocks.info_block == 'instead'">
                                <select v-model="global_blocks.info_block_id"  class="form-control"> 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    

                    <div class="form-group clearfix row" v-if="category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Price description </label>
                        <div class="col-md-10">
                            <ckeditor v-model="data.price_text"></ckeditor>
                        </div>
                    </div>

                </form>
            </div>
        </div>
</template>

<script>
    
    export default {
        components: {
            // StackModal,
        },
        props: [
            'locale_data',
            'category',
            'global_blocks_prop',
            'title',
            'description'
        ],
        data(){
            return {
                // category: this.$route.params.article_category,

                editorConfig: [],

                general_infos: [],
                data: [],
                global_blocks: []
            }
        },
        mounted() {
            this.data = this.locale_data
            this.global_blocks = this.global_blocks_prop
            this.get_general_info()
        },
        methods: {
            // edit_ka_article(){

            // },

            get_general_info(){
                axios
                .get('../../../api/general_info/')
                .then(response => {
                    this.general_infos = response.data
                    // console.log(response.data)
                })
                .catch(
                    errors => console.log(errors)
                );
            },

            info_block_action(status){
                this.global_blocks.info_block = status
                this.$emit('global_blocks', this.global_blocks)
            },
            best_time_action(status){
                this.global_blocks.best_time = status
                this.$emit('global_blocks', this.global_blocks)
            },
            routes_action(status){
                this.global_blocks.routes_info = status
                this.$emit('global_blocks', this.global_blocks)
            },
            what_need_block_action(status){
                this.global_blocks.what_need_info = status
                this.$emit('global_blocks', this.global_blocks)
            }
        }
    }
</script>


<style>

</style>