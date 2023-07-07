<template>
    <!-- <div class="col-md-12" > -->
        <!-- <div class="row"> -->
        <div class="col-md-12">
            <div class="jumbotron width_100">
                <div class="container">
                    <h2 class="display-4"><span style="text-transform: capitalize">{{this.category}}</span> {{ this.title }}</h2>
                    <p class="lead">{{ this.description }}</p>
                </div>
            </div>

            <div class="wrapper container-fluid container">
                <form method="POST" @submit.prevent="add_article">
                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Title </label>
                        <div class="col-md-10">
                            <input type="text" name="name" v-model="data.title"  class="form-control"> 
                            <div class="alert alert-danger" role="alert" v-if="errors.title">
                                {{ errors.title[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> Short description </label>
                        <div class="col-md-10">
                            <ckeditor v-model="data.short_description" :config="short_description_text_editor"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.short_description">
                                {{ errors.short_description[0] }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> text </label>
                        <div class="col-md-10">
                            <!-- <ckeditor v-model="data.text"></ckeditor>
                            <div class="alert alert-danger" role="alert" v-if="errors.text">
                                {{ errors.text[0] }}
                            </div> -->

                            <!-- <ckeditor v-model="data.text" /> -->

                            <ckeditor id="text" :editor="'text'" v-model="data.text" :config="text_editor_config" ></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix row" v-if="this.category != 'mount_route'">
                        <label for="name" class='col-md-2 control-label'> How to get hear </label>
                        <div class="col-md-10">
                            <!-- <ckeditor v-model="data.how_get" :config="editorConfig" :config="this.$editorConfig"></ckeditor> -->
                            <ckeditor v-model="data.how_get" :config="this.$editorConfig"></ckeditor>
                        </div>
                    </div>

                    <hr v-if="this.category == 'outdoor'">

                    <!-- <div  v-if="general_infos.length">
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
                                <ckeditor id="route" v-model="data.route" :editor="'route'" :config="route_description_editor_config"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.routes_info == 'after' || global_blocks.routes_info == 'instead'">
                                <select class="form-control"  v-model="global_blocks.routes_info_id" > 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
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
                                <ckeditor v-model="data.best_time" :config="this.$editorConfig"></ckeditor>
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
                                <ckeditor v-model="data.what_need" :config="this.$editorConfig"></ckeditor>
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
                                <ckeditor v-model="data.info" :config="this.$editorConfig"></ckeditor>
                            </div>

                            <div class="col-md-12" v-if="global_blocks.info_block == 'after' || global_blocks.info_block == 'instead'">
                                <select v-model="global_blocks.info_block_id"  class="form-control"> 
                                    <option v-for="general_info in general_infos" :key="general_info.id" :value="general_info.id">{{ general_info.title }}</option>
                                </select> 
                            </div>
                        </div>
                    </div> -->

                    <GlobalInfoFormBlock 
                        :title_prop="'Routes description'" 
                        :form_value_name_prop="'route'"
                        :form_data_prop=data.route 
                        :locale_prop="locale_prop"
                        :block_action_prop="global_blocks.routes_info"
                        :block_id_prop="global_blocks.routes_info_id"
                        :value_name_prop="'routes_info'"

                        :global_data_array_prop="general_infos"
                        :get_data_in_component_prop="false"

                        @get_form_data="get_value_insert_text"
                        @get_global_blocks_status="get_global_blocks_status_action"
                        @get_global_blocks_id="get_global_blocks_id"
                    />

                    <GlobalInfoFormBlock
                        :title_prop="'Best time for climbing'" 
                        :form_value_name_prop="'best_time'"
                        :form_data_prop=data.best_time 
                        :locale_prop="locale_prop"
                        :block_action_prop="global_blocks.best_time"
                        :block_id_prop="global_blocks.best_time_id"
                        :value_name_prop="'best_time'"

                        :global_data_array_prop="general_infos"
                        :get_data_in_component_prop="false"

                        @get_form_data="get_value_insert_text"
                        @get_global_blocks_status="get_global_blocks_status_action"
                        @get_global_blocks_id="get_global_blocks_id"
                    />

                    <hr v-if="this.category == 'outdoor' || this.category == 'ice' || this.category == 'mount_route' ">

                    <GlobalInfoFormBlock
                        :title_prop="'What you need'" 
                        :form_value_name_prop="'what_need'"
                        :form_data_prop=data.what_need 
                        :locale_prop="locale_prop"
                        :block_action_prop="global_blocks.what_need_info"
                        :block_id_prop="global_blocks.what_need_info_id"
                        :value_name_prop="'what_need_info'"

                        :global_data_array_prop="general_infos"
                        :get_data_in_component_prop="false"

                        @get_form_data="get_value_insert_text"
                        @get_global_blocks_status="get_global_blocks_status_action"
                        @get_global_blocks_id="get_global_blocks_id"
                    />

                    <hr>

                    <GlobalInfoFormBlock
                        :title_prop="'Contact info'" 
                        :form_value_name_prop="'info'"
                        :form_data_prop=data.info 
                        :locale_prop="locale_prop"
                        :block_action_prop="global_blocks.info_block"
                        :block_id_prop="global_blocks.info_block_id"
                        :value_name_prop="'info_block'"

                        :global_data_array_prop="general_infos"
                        :get_data_in_component_prop="false"

                        @get_form_data="get_value_insert_text"
                        @get_global_blocks_status="get_global_blocks_status_action"
                        @get_global_blocks_id="get_global_blocks_id"
                    />
                    

                    <div class="form-group clearfix row" v-if="this.category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> Price description </label>
                        <div class="col-md-10">
                            <ckeditor v-model="data.price_text" :config="this.$editorConfig"></ckeditor>
                        </div>
                    </div>

                </form>
            </div>
        <!-- </div> -->
    </div>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort';
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import GlobalInfoFormBlock from '../../../../items/GlobalInfoFormBlockComponent.vue'
    // import { editor_config } from '../../../../../../mixins/editor/editor_config_mixin.js'

    export default {
        components: {
            StackModal,
            GlobalInfoFormBlock,
            // SlickItem,
            // SlickList,
        },
        mixins: [
            // editor_config
        ],
        props: [
            // 'info_block_prop',
            // 'routes_info_prop',
            // 'what_need_info_prop',
            // 'best_time_prop',

            'global_blocks_prop',

            'title',
            'description'
        ],
        data(){
            return {
                // category: 'this.$route.params.article_category',
                category: this.$route.params.article_category,

                errors: [],
                error: [],
                
                editor_config: {
                    short_description_text: this.$editor_config.get_small_editor_config(),
                    text: this.$editor_config.get_big_editor_config(),
                    how_get: this.$editor_config.get_big_editor_config(),
                    price_text: this.$editor_config.get_big_editor_config(),
                },

                data: {
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

                general_infos: [],

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
            // this.routes_info = this.routes_info_prop
            // this.what_need_info = this.what_need_info_prop
            // this.best_time = this.best_time_prop

            this.get_general_info()

            this.$emit('locale_form_data', this.data)
        },
        watch: {
            global_block: function(){
                this.global_blocks = this.global_blocks_prop
            },
        },
        methods: {
            get_value_insert_text({locale, form_data, form_value_name}) {
                this.data[form_value_name] = form_data
                this.send_data()
            },
            get_global_blocks_status_action({value_name, block_action}) {
                this.global_blocks[value_name] = block_action
                this.$emit('global_blocks', this.global_blocks)
            },
            get_global_blocks_id({value_name, block_id}) {
                this.global_blocks[value_name+"_id"] = block_id
                this.$emit('global_blocks', this.global_blocks)
            },
            
            uploader(editor)
            {
                editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                    return new UploadAdapter( loader );
                };
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