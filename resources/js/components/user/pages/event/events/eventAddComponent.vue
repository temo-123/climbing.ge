<template>
    <div class="tabs"> 
        <div class="row justify-content-center" v-if="is_loading">
            <div class="col-md-4">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="row" v-if="!is_loading">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">Beck</button>
            </div>
        </div>
        <div class="row" v-if="!is_loading">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_event()" >Save</button>
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
                        
                        <label for="1" >Global info</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="2" :value="2" v-model="tab_num">
                        
                        <label for="2" >English text</label>
                    </div>
                    <div class="col" >
                        <input type="radio" id="3" :value="3" v-model="tab_num">
                        
                        <label for="3" >Georgian text</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>Event global information</span></h2>
                                <p class="lead">Event global information.</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Publish </label>
                            <div class="col-xs-8">
                                <select class="form-control" v-model="data.global_data.published" name="published" > 
                                    <option value="0">Not public</option> 
                                    <option value="1">Public</option> 
                                </select> 
                                <!-- <div class="alert alert-danger" role="alert" v-if="global_article_error.published">
                                    {{ global_article_error.published[0] }}
                                </div> -->
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
                        <!-- <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Event location </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_data.map"  class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="start_datatle" class='col-xs-2 control-label'> Start and end data </label>
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="start_datatle" class="form-control" v-model="data.global_data.start_data" placeholder="Start data/time"> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="end_data" class="form-control" v-model="data.global_data.end_data" placeholder="End data/time"> 
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Event location </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.global_data.map"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix row">
                            <label for="name" class='col-md-4 control-label'>This event does`not have concrete day (Insert data without day)</label>
                            <div class="col-md-8">
                                <input type="checkbox" id="scales" name="scales" v-model="is_event_whithout_day_button" @click="event_whithout_day()">
                            </div>
                        </div>

                        <div class="form-group clearfix" v-if="is_event_whithout_day">
                            <label for="start_datatle" class='col-xs-2 control-label'> Start and end data </label>
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="month" name="start_datatle" class="form-control" v-model="data.global_data.start_data" placeholder="Start data/time"> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="month" name="end_data" class="form-control" v-model="data.global_data.end_data" placeholder="End data/time"> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group clearfix" v-if="!is_event_whithout_day">
                            <label for="start_datatle" class='col-xs-2 control-label'> Start and end data </label>
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="start_datatle" class="form-control" v-model="data.global_data.start_data" placeholder="Start data/time"> 
                                    </div>
                                    <div class="col-md-6">
                                        <input type="datetime-local" name="end_data" class="form-control" v-model="data.global_data.end_data" placeholder="End data/time"> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="image" class='col-xs-6 control-label'>Upload article image:</label>
                            <div class="col-xs-6">
                                <input type="file" name="image" id="image" required v-on:change="onFileChange">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Event english version</h2>
                            <p class="lead">Event english version for site localisation.</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_data.title"  class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.short_description" :config="editor_config.us_short_description"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.us_data.text" :config="editor_config.us_text"></ckeditor>
                            </div>
                        </div>

                        <GlobalInfoFormBlock 
                            :title_prop="'Contact info'" 
                            :form_data_prop=data.us_data.info 
                            :locale_prop="'us'"
                            :block_action_prop="global_blocks.info_block"
                            :block_id_prop="global_blocks.info_block_id"
                            :value_name_prop="'info_block'"

                            @get_form_data="get_info_insert_text"
                            @get_global_blocks_status="get_global_blocks_status_action"
                            @get_global_blocks_id="get_global_blocks_id"
                        />
                    </form>
                </div>
                <div class="row" v-show="tab_num == 3">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">Event georgian version</h2>
                            <p class="lead">Event georgian version for site localisation.</p>
                        </div>
                    </div>
    
                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Title </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_data.title" class="form-control"> 
                            </div>
                        </div>
    
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> Short description </label>
                            <div class="col-xs-8">
                                <!-- <textarea type="text"  name="short_description"  v-model="data.ka_data.short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                                <ckeditor v-model="data.ka_data.short_description" :config="editor_config.ka_short_description"></ckeditor>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> text </label>
                            <div class="col-xs-8">
                                <ckeditor v-model="data.ka_data.text" :config="editor_config.ka_text"></ckeditor>
                            </div>
                        </div>

                        <GlobalInfoFormBlock 
                            :title_prop="'Contact info'" 
                            :form_data_prop=data.ka_data.info 
                            :locale_prop="'ka'"
                            :block_action_prop="global_blocks.info_block"
                            :block_id_prop="global_blocks.info_block_id"
                            :value_name_prop="'info_block'"

                            @get_form_data="get_info_insert_text"
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
    import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'
    import GlobalInfoFormBlock from '../../../items/GlobalInfoFormBlockComponent.vue'
    import validator_alerts_component from '../../../items/validator_alerts_component.vue'
    export default {
        mixins: [
            editor_config
        ],
        props: [
            // 'back_url',
        ],
        components: {
            GlobalInfoFormBlock,
            validator_alerts_component
        },
        data(){
            return {
                tab_num: 1,

                error: [],

                images: [],
                editorConfig: '',

                is_loading: false,

                editor_config: {
                    us_short_description: editor_config.get_small_editor_config(),
                    us_text: editor_config.get_big_editor_config(),
                    // us_info: editor_config.get_big_editor_config(),
                    ru_short_description: editor_config.get_small_editor_config(),
                    ru_text: editor_config.get_big_editor_config(),
                    // ru_info: editor_config.get_big_editor_config(),
                    ka_short_description: editor_config.get_small_editor_config(),
                    ka_text: editor_config.get_big_editor_config(),
                    // ka_info: editor_config.get_big_editor_config(),
                },

                data: {
                    global_data: {
                        us_title_for_url_title: '',

                        start_data: '',
                        end_data: '',

                        category: 'event',
                        map: '',

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

                    // ru_data: {
                    //     title: "",
                    //     short_description: "",
                    //     text: "",
                    //     info: "",
                    // }
                },

                global_blocks: {
                    info_block: 'new_info',

                    info_block_id: 0,
                },

                is_event_whithout_day: false,
                is_event_whithout_day_button: false
            }
        },
        mounted() {
            this.get_general_info()
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            // get_value_insert_text({locale, form_data, form_value_name}) {
            //     this.data[locale+"_data"][form_value_name] = form_data
            // },
            // get_global_blocks_status_action({value_name, block_action}) {
            //     this.global_blocks[value_name] = block_action
            // },
            // get_global_blocks_id({value_name, block_id}) {
            //     this.global_blocks[value_name+"_id"] = block_id
            // },
            get_info_insert_text({locale, form_data}) {
                this.data[locale+"_data"].info = form_data
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

            onFileChange(event){
                this.image = event.target.files[0];
            },
            add_event() {
                this.is_loading = true

                this.data.global_data.us_title_for_url_title = this.data.us_data.title
                let formData = new FormData();

                formData.append('image', this.image)

                formData.append('data', JSON.stringify(this.data))
                formData.append('global_blocks', JSON.stringify(this.global_blocks))

                this.error = []

                axios
                .post('../api/event/add_event', 
                    formData
                )
                .then(response => {
                    // if(confirm('Do you want send notification about editing article?')){
                    //     // this.sand_notification()
                    // }
                    // else{
                        this.go_back(true)
                    // }
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.error = error.response.data.validation
                    }
                    else{
                        alert(error)
                    }
                })
                .finally(() => this.is_loading = false);
            },

            sand_notification() {
                // this.is_mail_sending_procesing = true

                // axios
                // .post('../../../api/user/notifications/send_event_adding_notification',{
                //     notification_category: this.category
                // } )
                // .then(response => {
                //     this.go_back(true)
                // })
                // .catch(err => {
                //     console.log(err);
                // })
                // .finally(() => this.is_mail_sending_procesing = false);
            },

            get_general_info(){
                axios
                .get('/general_info/')
                .then(response => {
                    this.general_infos = response.data          
                })
                .catch(
                    errors => console.log(errors)
                );
            },

            go_back: function(back_action = false) {
                if(back_action == false){
                    if(confirm('Are you sure, you want go back?')){
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