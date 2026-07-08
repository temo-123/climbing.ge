<template>
        <div class="col-md-12">
            <div class="jumbotron width_100">
                <div class="container">
                    <h2 class="display-4"><span style="text-transform: capitalize">{{this.category_prop}}</span> {{ this.title_prop }}</h2>
                    <p class="lead">{{ this.description_prop }}</p>
                </div>
            </div>

            <div class="wrapper container-fluid container">
                <form method="POST"  :headers="{'x-csrf-token': token}">
                    <div class="form-group clearfix row" >
                        <label for="name" class='col-md-2 control-label'> {{ $t('common.title') }} </label>
                        <div class="col-md-10">
                            <input type="text" name="name" v-model="data.title"  class="form-control"> 
                        </div>
                    </div>
    
                    <div class="form-group clearfix row" v-if="locale_prop == 'us'">
                        <label for="name" class='col-md-4 control-label'> {{ $t('admin.articles.locale_form.change_url_title') }} </label>
                        <div class="col-md-8">
                            <input type="checkbox" id="scales" name="scales" v-model="is_change_url_title" @click="change_url_title_in_global_bisnes()">
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                        <div class="col-md-10">
                            <small_editor v-model="data.short_description" />
                        </div>
                    </div>

                    <div class="form-group clearfix row">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                        <div class="col-md-10">
                            <big_editor v-model="data.text" />
                        </div>
                    </div>

                    <hr v-if="category == 'outdoor'">

                    <div class="form-group clearfix row" v-if="category != 'mount_route' && category != 'partners' && ( category == 'outdoor' || category == 'ice' || category == 'indoor' ) ">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.locale_form.how_to_get_here') }} </label>
                        <div class="col-md-10">
                            <big_editor v-model="data.how_get" />
                        </div>
                    </div>

                    <hr v-if="category != 'partners' && category == 'mount_route' || category == 'outdoor' || category == 'ice'">

                    <span v-if="category != 'partners' && category == 'mount_route' || category == 'outdoor' || category == 'ice'">
                    <GlobalInfoFormBlock
                        :title_prop="$t('admin.articles.locale_form.routes_description')"
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
                    </span>
                    <span v-if="category != 'partners' && category == 'outdoor' || category == 'ice'">
                    <GlobalInfoFormBlock
                        :title_prop="$t('admin.articles.locale_form.best_time_climbing')"
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
                </span>

                    <hr v-if="category != 'partners' && category == 'mount_route' || category == 'outdoor' || category == 'ice' || category == 'mount_route' ">

                    <span v-if="category != 'partners' && category == 'mount_route' || category == 'outdoor' || category == 'ice' || category == 'indoor'">
                    <GlobalInfoFormBlock
                        :title_prop="$t('admin.articles.locale_form.what_you_need')"
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
                    </span>

                    <hr>

                    <span v-if="category != 'partners'">
                    <GlobalInfoFormBlock
                        :title_prop="$t('admin.articles.locale_form.contact_info')"
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
                    </span>
                    <span v-else>
                        <div class="form-group clearfix row">
                            <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.locale_form.info_contact') }} </label>
                            <div class="col-md-10">
                                <div class="col-md-12" >
                                    <big_editor v-model="data.text" />
                                </div>
                            </div>
                        </div>
                    </span>

                    <hr>

                    <div class="form-group clearfix row" v-if="category == 'indoor'">
                        <label for="name" class='col-md-2 control-label'> {{ $t('admin.articles.locale_form.price_description') }} </label>
                        <div class="col-md-10">
                            <big_editor v-model="data.price_text" />
                        </div>
                    </div>

                </form>
            </div>
        <!-- </div> -->
    </div>
</template>

<script>
    export default {
        components: {

        },
        props: [
            'global_blocks_prop',
            'locale_data_prop',
            'category_prop',
            'locale_prop',
            
            'title_prop',
            'description_prop'
        ],
        watch: {
            global_blocks_prop: function(){
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
        data(){
            return {
                // category: 'this.$route.params.article_category',
                category: this.category_prop,

                general_infos: [],

                is_change_url_title: false,
                // error: [],


                token: '',

                data: [],

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
        async mounted() {
            this.global_blocks = this.global_blocks_prop
            this.category = this.category_prop

            this.get_general_info()

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

            change_url_title_in_global_bisnes(){
                if(!this.is_change_url_title){
                    if(confirm(this.$t('admin.articles.locale_form.confirm_change_url_title'))){
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
                .get('/get_general_info/get_all_general_infos/')
                .then(response => {
                    this.general_infos = response.data          
                })
                .catch(
                    errors => console.log(errors)
                );
            },

            send_data(){
                // console.log(this.data)
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