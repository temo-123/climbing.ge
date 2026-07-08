<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-if="is_loading">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>
        <div class="row" v-if="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">{{ $t('common.back') }}</button>
            </div>
        </div>
        <div class="row" v-if="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_event()" >{{ $t('common.save') }}</button>
            </div>
        </div>
        <div class="row" v-if="error.length != 0">
            <validator_alerts_component
                :errors_prop="error"
            />
        </div>
        <div class="row" v-if="!is_loading">
            <div class="col-md-12">
                <div class="row">
                    <div class="col" >
                        <input type="radio" id="1" :value="1" v-model="tab_num">
                        
                        <label for="1" >{{ $t('common.global_info') }}</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >{{ $t('common.english_text') }}</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >{{ $t('common.georgian_text') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>{{ $t('admin.events.event_global_info_title') }}</span></h2>
                                <p class="lead">{{ $t('admin.events.event_global_info_subtitle') }}</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.common.publish') }} </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_data.published" name="published" >
                                    <option value="0">{{ $t('admin.common.not_public') }}</option>
                                    <option value="1">{{ $t('admin.common.public') }}</option>
                                </select>
                                <!-- <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                    {{ global_article_error.published[0] }}
                                </div> -->
                            </div>
                        </div>

                        <div class="form-group clearfix row">
                            <label for="name" class='col-md-4 control-label'> {{ $t('admin.events.change_competition_category_label') }}</label>
                            <div class="col-md-8">
                                <input type="checkbox" id="scales" name="scales" @click="change_event_category()">
                            </div>
                        </div>
                        <!-- <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Category </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_data.category" name="published" > 
                                    <option value="event">Event</option> 
                                    <option value="competition" disabled>Competition</option> 
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.events.event_location_label') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_data.map"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix row">
                            <label for="name" class='col-md-4 control-label'>{{ $t('admin.events.no_concrete_day_label') }}</label>
                            <div class="col-md-8">
                                <input type="checkbox" id="scales" name="scales" v-model="is_event_whithout_day_button" @click="event_whithout_day()">
                            </div>
                        </div>

                        <div class="form-group clearfix" v-if="is_event_whithout_day">
                            <label for="start_datatle" class='col-xs-2 control-label'> {{ $t('admin.events.start_end_data_label') }} </label>
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="month" name="start_datatle" class="form-control" v-model="data.global_data.start_data" :placeholder="$t('admin.events.start_data_placeholder')"> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="month" name="end_data" class="form-control" v-model="data.global_data.end_data" :placeholder="$t('admin.events.end_data_placeholder')"> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group clearfix" v-if="!is_event_whithout_day">
                            <label for="start_datatle" class='col-xs-2 control-label'> {{ $t('admin.events.start_end_data_label') }} </label>
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="start_datatle" class="form-control" v-model="data.global_data.start_data" :placeholder="$t('admin.events.start_data_placeholder')"> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="end_data" class="form-control" v-model="data.global_data.end_data" :placeholder="$t('admin.events.end_data_placeholder')"> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <single_image_edit
                                :title_prop="$t('admin.events.event_image_title')"
                                :existing_image_url_prop="data.global_data.image ? '../../../../images/event_img/'+data.global_data.image : ''"
                                :crop_ratio_prop="{ width: 16, height: 9 }"
                                @update_single_image="image = $event"
                            />
                        </div>
                    </form>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.events.event_en_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.events.event_en_version_subtitle') }}</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_data.title"  class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.change_url_title') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" @click="change_url_title_in_global_event()">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.us_data.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.us_data.text" />
                            </div>
                        </div>


                        <GlobalInfoFormBlock
                            :title_prop="$t('admin.articles.locale_form.contact_info')"
                            :form_value_name_prop="'info'"
                            :form_data_prop=data.us_data.info 
                            :locale_prop="'us'"
                            :block_action_prop="global_blocks.info_block"
                            :block_id_prop="global_blocks.info_block_id"
                            :value_name_prop="'info_block'"

                            :global_data_array_prop="general_infos"
                            :get_data_in_component_prop="false"

                            @get_form_data="get_value_insert_text"
                            @get_global_blocks_status="get_global_blocks_status_action"
                            @get_global_blocks_id="get_global_blocks_id"
                        />
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.events.event_ka_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.events.event_ka_version_subtitle') }}</p>
                        </div>
                    </div>

                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_data.title" class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_data.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <big_editor v-model="data.ka_data.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.ka_data.text" />
                            </div>
                        </div>

                        <GlobalInfoFormBlock
                            :title_prop="$t('admin.articles.locale_form.contact_info')"
                            :form_value_name_prop="'info'"
                            :form_data_prop=data.ka_data.info 
                            :locale_prop="'ka'"
                            :block_action_prop="global_blocks.info_block"
                            :block_id_prop="global_blocks.info_block_id"
                            :value_name_prop="'info_block'"

                            :global_data_array_prop="general_infos"
                            :get_data_in_component_prop="false"

                            @get_form_data="get_value_insert_text"
                            @get_global_blocks_status="get_global_blocks_status_action"
                            @get_global_blocks_id="get_global_blocks_id"
                        />
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    // import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'
    // import { general_info } from '../../../../../mixins/general_info_mixin.js'
    // import GlobalInfoFormBlock from '../../../items/GlobalInfoFormBlockComponent.vue'
    import moment from "moment"; // https://www.npmjs.com/package/vue-moment
    import single_image_edit from '../../../items/single_image/singleImageEditComponent.vue'

    // import validator_alerts_component from '../../../items/validator_alerts_component.vue'
    export default {
        mixins: [
            // general_info,
            // editor_config,
        ],
        props: [
            // 'back_url',
        ],
        components: {
            // GlobalInfoFormBlock,
            moment,
            single_image_edit,
            // validator_alerts_component
        },
        data(){
            return {
                tab_num: 1,

                images: [],
                image: null,
                editorConfig: '',

                // editor_config: {
                //     us_short_description: {},
                //     us_text: {},
                //     ru_short_description: {},
                //     ru_text: {},
                //     ka_short_description: {},
                //     ka_text: {},
                // },

                error: [],

                change_url_title: false,

                is_loading: false,

                is_change_event_category: false,

                data: {
                    global_data: {
                        us_title_for_url_title: '',

                        start_data: '',
                        end_data: '',

                        category: 'event',
                        map: '',

                        change_url_title: '',

                        published: 0,
                    },

                    us_data: {
                        title: "",
                        short_description: "",
                        text: "",
                        info: "",
                    },

                    ka_data: {
                        title: "",
                        short_description: "",
                        text: "",
                        info: "",
                    },

                    ru_data: {
                        title: "",
                        short_description: "",
                        text: "",
                        info: "",
                    }
                },

                global_blocks: {
                    info_block: 'new_info',

                    info_block_id: 0,
                },
                general_infos: [],

                start_time_h: 0,

                is_event_whithout_day: false,
                is_event_whithout_day_button: false
            }
        },
        mounted() {
            this.get_general_info()
            this.get_editing_event()

            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            get_value_insert_text({locale, form_data, form_value_name}) {
                this.data[locale+"_data"][form_value_name] = form_data
                // general_info.get_value_insert_text(locale, form_data, form_value_name)
            },
            get_global_blocks_status_action({value_name, block_action}) {
                this.global_blocks[value_name] = block_action
            },
            get_global_blocks_id({value_name, block_id}) {
                this.global_blocks[value_name+"_id"] = block_id
            },

            event_whithout_day(){
                this.is_event_whithout_day = !this.is_event_whithout_day_button
            },

            get_general_info(){
                axios
                .get('/get_general_info/get_all_general_infos/')
                .then(response => {
                    this.general_infos = response.data          
                })
                .catch(
                    errors => console.log(errors)
                );
            },
            get_editing_event() {
                this.is_loading = true
                axios
                .get('/set_event/get_editing_event/'+this.$route.params.id)
                .then(response => {
                    // this.data = response.data
                    this.data.global_data = response.data.global_data
                    this.data.us_data = response.data.us_data
                    this.data.ru_data = response.data.ru_data
                    this.data.ka_data = response.data.ka_data

                    this.start_time_h = moment(response.data.global_data.start_data).format("H")

                    if(this.start_time_h > 0){
                        this.is_event_whithout_day = false,
                        this.is_event_whithout_day_button = false
                    }
                    else{
                        this.is_event_whithout_day = true,
                        this.is_event_whithout_day_button = true

                        this.data.global_data.start_data = moment(this.data.global_data.start_data).format('YYYY-MM')
                        this.data.global_data.end_data = moment(this.data.global_data.end_data).format('YYYY-MM')
                    }

                    this.global_blocks.info_block = response.data.general_info[0].position.block_action
                    this.global_blocks.info_block_id = response.data.general_info[0].position.info_id
                })
                .catch(
                    error => console.log(error)
                )
                .finally(() => this.is_loading = false);
            },

            change_event_category(){
                if(!this.is_change_event_category){
                    if(confirm(this.$t('admin.events.confirm_change_event_category'))){
                        this.is_change_event_category = true
                    }
                    else{
                        this.is_change_event_category = false 
                    }
                }
                else{
                    this.is_change_event_category = false 
                }

                this.data.is_change_event_category = this.is_change_event_category
            },

            change_url_title_in_global_event(){
                if(!this.change_url_title){
                    if(confirm(this.$t('admin.articles.locale_form.confirm_change_url_title'))){
                        this.change_url_title = true
                    }
                }
                else{
                    this.change_url_title = false 
                }
            },

            edit_event() {
                this.is_loading = true

                if (this.change_url_title) {
                    this.data.global_data.change_url_title = this.change_url_title
                    this.data.global_data.us_title_for_url_title = this.data.us_data.title
                }
                else{
                    this.data.global_data.change_url_title = false
                    this.data.global_data.us_title_for_url_title = false
                }

                if (this.is_change_event_category) {
                    this.data.global_data.change_event_category = this.is_change_event_category
                }
                else{
                    this.data.global_data.change_event_category = false
                }

                let formData = new FormData();

                formData.append('image', this.image)
                formData.append('data', JSON.stringify(this.data))
                formData.append('global_blocks', JSON.stringify(this.global_blocks))

                this.error = []

                axios
                .post('/set_event/edit_event/'+this.$route.params.id, 
                    formData
                )
                .then(response => {
                    this.go_back(true)
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                })
                .finally(() => this.is_loading = false);
            },

            go_back: function(back_action = false) {
                if(back_action == false){
                    if(confirm(this.$t('admin.events.confirm_go_back'))){
                        this.$router.go(-1)
                    }
                }
                else{
                    this.$router.go(-1)
                }
            },
        }
    }
</script>