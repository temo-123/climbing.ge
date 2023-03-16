<template>
    <div class="col-md-12" >
        <!-- <h2 @click="test()">test</h2> -->
        <!-- {{ category }} -->
        <!-- <div class="row"> -->
            <div class="col-md-12">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">{{this.category_prop}}</span> {{ this.title_prop }}</h2>
                        <p class="lead">{{ this.description_prop }}</p>
                    </div>
                </div>

            <div class="wrapper container-fluid container">
                <form method="POST">
                    <div class="form-group clearfix row" >
                        <label for="name" class='col-md-2 control-label'> Title </label>
                        <div class="col-md-10">
                            <input type="text" name="name" v-model="data.title"  class="form-control"> 
                        </div>
                    </div>
    
                    <div class="form-group clearfix row" v-if="locale_prop == 'us'">
                        <label for="name" class='col-md-4 control-label'> Change URL title </label>
                        <div class="col-md-8">
                            <input type="checkbox" id="scales" name="scales" v-model="is_change_url_title" @click="change_url_title_in_global_bisnes()">
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Short description </label>
                        <div class="col-md-10">
                            <ckeditor v-model="data.short_description" :config="short_description_text_editor"></ckeditor>
                            <!-- <div class="alert alert-danger" role="alert" v-if="errors.short_description">
                                {{ errors.short_description[0] }}
                            </div> -->
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> text </label>
                        <div class="col-md-10">
                            <!-- <ckeditor v-model="data.text"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.text">
                                {{ errors.text[0] }}
                            </div> -->

                            <ckeditor
                                v-model="data.text"
                                :config="text_editor_config"
                            />
                        </div>
                    </div>

                    <hr v-if="this.category == 'outdoor'">

                    <div  v-if="general_infos.length">
                        <div class="row" v-if="this.category == 'outdoor'">
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

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor'">
                        <label for="name" class='col-md-2 control-label'> Routes description </label>

                        <div class="col-md-10">
                            <div class="col-md-12" v-if="global_blocks.routes_info == 'befor'" >
                                <select class="form-control"  v-model="global_blocks.routes_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        
                            <div class="col-md-12" v-if="global_blocks.routes_info == 'befor' || global_blocks.routes_info == 'after' || global_blocks.routes_info == 'new_info'">
                                <ckeditor v-model="data.route" 
                                :config="route_description_editor_config"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.routes_info == 'after' || global_blocks.routes_info == 'instead'">
                                <select class="form-control"  v-model="global_blocks.routes_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> How to get hear </label>
                        <div class="col-md-10">
                            <!-- <ckeditor v-model="data.how_get" :config="editorConfig" :config="this.$editorConfig"></ckeditor> -->
                            <ckeditor v-model="data.how_get" :config="how_get_editor_config"></ckeditor>
                        </div>
                    </div>

                    <hr v-if="this.category == 'outdoor' || this.category == 'ice'">

                    <div  v-if="general_infos.length">
                        <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice'">
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

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice'">
                        <label for="name" class='col-md-2 control-label'> Best time for climbing </label>

                        <div class="col-md-10">
                            <div class="col-md-12" v-if="global_blocks.best_time == 'befor'">
                                <select class="form-control"  v-model="global_blocks.best_time_id" >
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        
                            <div class="col-md-12" v-if="global_blocks.best_time == 'befor' || global_blocks.best_time == 'after' || global_blocks.best_time == 'new_info'">
                                <ckeditor v-model="data.best_time" :config="best_time_editor_config"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.best_time == 'after' || global_blocks.best_time == 'instead'">
                                <select class="form-control"  v-model="global_blocks.best_time_id" >
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div>

                    <hr v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">

                    <div  v-if="general_infos.length">
                        <div class="row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
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

                    <div class="form-group clearfix row" v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">
                        <label for="name" class='col-md-2 control-label'> what you need </label>

                        <div class="col-md-10">
                            <div class="col-md-12" v-if="global_blocks.what_need_info == 'befor'">
                                <select class="form-control"  v-model="global_blocks.what_need_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        
                            <div class="col-md-12" v-if="global_blocks.what_need_info == 'befor' || global_blocks.what_need_info == 'after' || global_blocks.what_need_info == 'new_info'">
                                <ckeditor v-model="data.what_need" :config="what_need_editor_config"></ckeditor>
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
                                <ckeditor v-model="data.info" :config="this.info_editor_config"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.info_block == 'after' || global_blocks.info_block == 'instead'">
                                <select v-model="global_blocks.info_block_id"  class="form-control"> 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Price description </label>
                        <div class="col-md-10">
                            <ckeditor v-model="data.price_text" :config="this.price_text_editor_config"></ckeditor>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    // import { exampleMixin } from '../../../../../services/editor/editor_config.js'
    import { editor_config } from '../../../../../../mixins/editor/editor_config_mixin.js'

    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        mixins: [
            editor_config
        ],
        props: [
            'global_blocks_prop',
            'locale_data_prop',
            'category_prop',
            'locale_prop',
            
            'title_prop',
            'description_prop'
        ],
        data(){
            return {
                // category: 'this.$route.params.article_category',
                category: this.category_prop,

                general_infos: [],

                is_change_url_title: false,
                // error: [],

                short_description_text_editor: editor_config.get_small_editor_config(),
                text_editor_config: editor_config.get_big_editor_config(),
                route_description_editor_config: editor_config.get_big_editor_config(), 
                how_get_editor_config: editor_config.get_big_editor_config(),
                best_time_editor_config: editor_config.get_big_editor_config(),
                what_need_editor_config: editor_config.get_big_editor_config(),
                info_editor_config: editor_config.get_big_editor_config(),
                price_text_editor_config: editor_config.get_big_editor_config(),

                data: {
                    change_url_title: false,
                    title: '',
                    short_description: '',
                    text: '',
                    route: '',
                    how_get: '',
                    best_time: '',
                    what_need: '',
                    info: '',
                    time: '',
                },

                global_blocks: {
                    info_block: '',
                    routes_info: '',
                    what_need_info: '',
                    best_time: '',

                    info_block_id: 0,
                    routes_info_id: 0,
                    what_need_info_id: 0,
                    best_time_id: 0,
                }
            }
        },
        mounted() {
            this.global_blocks = this.global_blocks_prop

            this.get_general_info()

            // this.text_editor_config = this.get_config()
            // this.route_description_editor_config = this.get_config()
        },
        watch: {
            global_block: function(){
                this.global_blocks = this.global_blocks_prop
            },
            category_prop: function(newVal, oldVal) { 
                // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
                this.category = this.category_prop
            },
            locale_data_prop: function(newVal, oldVal) { 
                // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
                this.data = this.locale_data_prop
            }
        },
        methods: {
            get_config(){
                return {
                    filebrowserUploadUrl: "../../../../api/ckeditor/upload",

                    extraPlugins: 'embed,autoembed',
                    
                    embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',

                    image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
                    image2_disableResizer: true,
                    removeButtons: 'PasteFromWord'
                }
            },
            change_url_title_in_global_bisnes(){
                if(!this.is_change_url_title){
                    if(confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')){
                        this.is_change_url_title = true
                    }
                    else{
                        this.is_change_url_title = false 
                    }
                }
                else{
                    this.is_change_url_title = false 
                }

                this.data.is_change_url_title = this.is_change_url_title
            },
            get_general_info(){
                axios
                .get('../../../api/general_info/')
                .then(response => {
                    this.general_infos = response.data          
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