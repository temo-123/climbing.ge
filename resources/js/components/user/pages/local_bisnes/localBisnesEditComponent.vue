<template>
    <div class="tabs">
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">{{ $t('common.back') }}</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="edit_bisnes()" >{{ $t('admin.local_business.save_update_btn') }}</button>
            </div>
        </div>
        <div v-if="validation_errors.length > 0" class="alert alert-danger" style="margin-top: 10px;">
            <strong>{{ $t('admin.local_business.validation_failed_title') }}</strong>
            <ul class="mb-0 pl-3">
                <li v-for="(err, i) in validation_errors" :key="i">{{ err }}</li>
            </ul>
        </div>
        <div class="row">
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
                        <input type="radio" id="4" :value="4" v-model="tab_num">
                        
                        <label for="4" >{{ $t('common.georgian_text') }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row width_100" v-show="tab_num == 1">
                    <div class="jumbotron width_100">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="display-4"><span>{{ $t('admin.local_business.service_global_info_title') }}</span></h2>
                                <p class="lead">{{ $t('admin.local_business.service_global_info_subtitle') }}</p>
                            </div>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" id="global_form" ref="myForm" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <published_item
                            :published_prop="data.global_bisnes.published"
                            @item_data="data.global_bisnes.published = $event"
                        />
                        <div class="form-group clearfix" v-if="!data.global_bisnes.public_totaly">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.local_business.published_before_label') }} </label>
                            <div class="col-xs-8">
                                <input type="datetime-local" class="form-control" id="datemin" name="datemin" min="2000-01-02" v-model="data.global_bisnes.published_data" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.local_business.totaly_public_label') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" v-model="data.global_bisnes.public_totaly" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="enable_message_form" class='col-xs-2 control-label'> {{ $t('admin.local_business.enable_message_form_label') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="enable_message_form" name="enable_message_form" v-model="data.global_bisnes.enable_message_form" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="show_in_index" class='col-xs-2 control-label'> {{ $t('admin.local_business.show_in_index_label') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="show_in_index" name="show_in_index" v-model="data.global_bisnes.show_in_index" >
                            </div>
                        </div>
                    </form>

                    <article_bisnes_edit_relatione_tab
                        ref="article_bisnes_edit_relatione_tab"
                        @update_article_relations="update_article_relations"
                        @update_selected_category="update_selected_category"

                        :bisnes_id_prop="business_id"
                        :initial_selected_category_prop="data.global_bisnes.for_article_category"
                        :article_del_route_prop="'bisnes/del_bisnes_article_relation/'"
                        :get_articles_route_prop="'bisnes/get_bisnes_article_relation/'"
                    />
                    <gallery_images_edit
                        @update_gallery_images="update_gallery_images"

                        :image_path_prop="'images/suport_local_bisnes_img/'"
                        :image_del_route_prop="'set_bisnes/del_local_bisnes_image/'"
                        :get_images_route_prop="'get_bisnes/get_bisnes_images/'"
                        :crop_ratio_prop="{ width: 16, height: 9 }"
                    />
                </div>
                <div class="row" v-show="tab_num == 2">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.local_business.service_en_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.local_business.service_en_version_subtitle') }}</p>
                        </div>
                    </div>
                    <form class="width_100" name="contact-form" method="POST" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="name" v-model="data.us_bisnes.title"  class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.change_url_title') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" @click="change_url_title_in_global_bisnes()">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <small_editor v-model="data.us_bisnes.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.us_bisnes.text" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" v-show="tab_num == 4">
                    <div class="width_100 jumbotron jumbotron-fluid">
                        <div class="container">
                            <h2 class="display-4">{{ $t('admin.local_business.service_ka_version_title') }}</h2>
                            <p class="lead">{{ $t('admin.local_business.service_ka_version_subtitle') }}</p>
                        </div>
                    </div>

                    <form class="width_100" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('common.title') }} </label>
                            <div class="col-xs-8">
                                <input type="text" name="value name"  v-model="data.ka_bisnes.title" class="form-control">
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.short_description') }} </label>
                            <div class="col-xs-8">
                                <small_editor v-model="data.ka_bisnes.short_description" />
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.articles.locale_form.text_label') }} </label>
                            <div class="col-xs-8">
                                <big_editor v-model="data.ka_bisnes.text" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import gallery_images_edit from '../../items/gallery/galleryImageEditComponent.vue'
    import published_item from '../../items/form/parts/PublishedValueComponent.vue'

    import article_bisnes_edit_relatione_tab from './items/articleBisnesEditRelationeTabComponent.vue'
    export default {
        mixins: [
            ],
        components: {
            gallery_images_edit,
            published_item,
            article_bisnes_edit_relatione_tab,
        },

        props: [
            // 'back_url',
        ],


        data(){
            return {
                tab_num: 1,

                business_id: null,
                selected_category: null,
                bisnes_new_images: [],
                // bisnes_old_images: [],
                regions: [],

                bisnes_new_article_relations: [],
                // bisnes_old_article_relations: [],

                validation_errors: [],

                editor_config: {
                    us_short_description: {},
                    us_text: {},
                    // us_info: {},
                    // ru_short_description: {},
                    // ru_text: {},
                    // ru_info: {},
                    ka_short_description: {},
                    ka_text: {},
                    // ka_info: {}
                },

                editorConfig: {},

                data: {
                    global_bisnes: {
                        published_data: null,
                    },
                    us_bisnes: {},
                    ka_bisnes: {},
                    // ru_bisnes: {}
                },

                change_url_title: false
            }
        },
        mounted() {
            this.get_editing_bisnes()
            // this.get_region_bisnes()
                
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },
        methods: {
            get_editing_bisnes(){
                this.data_for_tab = []
                this.business_id = this.$route.params.id;
                
                axios
                .get("/set_bisnes/get_editing_local_bisnes_info/"+this.$route.params.id)
                .then(response => {
                    this.editing_bisnes = response.data

                    this.data = {
                        global_bisnes: {
                            ...response.data.global_bisnes,
                            // DB stores these as integer 0/1, but a plain v-model
                            // checkbox checks via `String(value) === String(true)` -
                            // "1" !== "true", so an unconverted 1 renders unchecked
                            // even though the data is really there.
                            public_totaly: !!response.data.global_bisnes.public_totaly,
                            enable_message_form: !!response.data.global_bisnes.enable_message_form,
                            show_in_index: !!response.data.global_bisnes.show_in_index,
                        },

                        us_bisnes: response.data.us_bisnes,
                        // // ru_bisnes: response.data.ru_bisnes,
                        ka_bisnes: response.data.ka_bisnes,
                    }

                    // Load for_article_category for edit component
                    if (response.data.for_article_category) {
                        this.selected_category = response.data.for_article_category;
                    }
                })
                .catch(
                    error => console.log(error)
                );
            },

            change_url_title_in_global_bisnes(){
                if(!this.change_url_title){
                    if(confirm(this.$t('admin.articles.locale_form.confirm_change_url_title'))){
                        this.change_url_title = true
                    }
                }
                else{
                    this.change_url_title = false 
                }
            },


            update_article_relations(articles){
                this.bisnes_new_article_relations = articles
            },

            update_selected_category(category){
                this.selected_category = category;
            },

            update_gallery_images(images){
                this.bisnes_new_images = images
            },

            edit_bisnes() {
                if (this.change_url_title) {
                    this.data.global_bisnes.change_url_title = this.change_url_title
                    this.data.global_bisnes.url_title = this.data.us_bisnes.title
                }
                else{
                    this.data.global_bisnes.change_url_title = false
                }

                // Always create FormData first
                let formData = new FormData();

                if(this.bisnes_new_images != []){
                    var image_loop_num = 0
                    this.bisnes_new_images.forEach(image => {
                        formData.append('bisnes_new_images['+image_loop_num+']', image.image)
                        image_loop_num++
                    });
                    image_loop_num = 0
                }

                if(this.bisnes_new_article_relations != []){
                    var relation_loop_num = 0
                    this.bisnes_new_article_relations.forEach(relation => {
                        formData.append('bisnes_new_article_relations['+relation_loop_num+']', relation.article_id)
                        relation_loop_num++
                    });
                    relation_loop_num = 0
                }

                // Handle category-based relations
                if(this.selected_category){
                    formData.append('selected_category', this.selected_category);
                }

                formData.append('data', JSON.stringify(this.data))

                // Always validate relations if any are selected
                if (this.selected_category || (this.bisnes_new_article_relations && this.bisnes_new_article_relations.length > 0)) {
                    if (this.$refs.article_bisnes_edit_relatione_tab) {
                        this.$refs.article_bisnes_edit_relatione_tab.validateRelationsBeforeSave(formData, this);
                    } else {
                        console.error('Reference to article_bisnes_edit_relatione_tab is not available');
                        this.proceedWithSave(formData);
                    }
                } else {
                    // No relations to validate, proceed directly with save
                    this.proceedWithSave(formData);
                }
            },

            go_back: function(back_action = false) {
                if(back_action == false){
                    if(confirm(this.$t('admin.shop.confirm_go_back'))){
                        this.$router.go(-1)
                    }
                }
                else{
                    // After a successful save, always return to the list explicitly -
                    // router.go(-1) is a no-op (stays on this page) when this page was
                    // opened directly rather than navigated to from the list.
                    this.$router.push({ name: 'localBisnesList' })
                }
            },

            // Flattens the backend's { ka_info_validation: {field: [msgs]}, ... }
            // shape into plain sentences ("Georgian text — Short description: ...")
            // and jumps to the first tab that actually has a problem, instead of
            // leaving the admin to guess which of the 3 tabs failed and why.
            applyValidationErrors(raw) {
                const sectionLabels = {
                    global_info_validation: this.$t('admin.local_business.validation_section_general'),
                    us_info_validation: this.$t('admin.local_business.validation_section_english'),
                    ka_info_validation: this.$t('admin.local_business.validation_section_georgian'),
                };
                const sectionTabs = {
                    global_info_validation: 1,
                    us_info_validation: 2,
                    ka_info_validation: 4,
                };
                const messages = [];
                let firstTab = null;
                for (const section of Object.keys(sectionLabels)) {
                    const fields = raw?.[section];
                    if (!fields) continue;
                    if (firstTab === null) firstTab = sectionTabs[section];
                    for (const msgs of Object.values(fields)) {
                        (Array.isArray(msgs) ? msgs : [msgs]).forEach(msg => {
                            messages.push(sectionLabels[section] + ' — ' + msg);
                        });
                    }
                }
                this.validation_errors = messages.length ? messages : [this.$t('admin.local_business.validation_error_prefix')];
                if (firstTab !== null) this.tab_num = firstTab;
            },

            // Handle validation errors from child component
            handleValidationError(error) {
                if (error.response?.status == 422) {
                    this.applyValidationErrors(error.response.data.content_validation_errors || error.response.data.validation);
                } else {
                    this.validation_errors = [this.$t('admin.local_business.validation_error_prefix') + ' ' + error];
                }
            },

            // Proceed with actual save
            proceedWithSave(formData) {
                this.validation_errors = [];
                axios
                    .post('/set_bisnes/edit_local_bisnes/' + this.business_id, formData)
                    .then(response => {
                        if (response.data.success) {
                            this.go_back(true);
                        } else {
                            this.validation_errors = [this.$t('admin.local_business.save_completed_with_issues') + ' ' + JSON.stringify(response.data)];
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status == 422) {
                                this.applyValidationErrors(error.response.data.content_validation_errors || error.response.data.validation);
                            } else {
                                this.validation_errors = [this.$t('admin.local_business.save_error_prefix') + ' ' + JSON.stringify(error.response.data)];
                            }
                        } else {
                            this.validation_errors = [this.$t('admin.local_business.network_error_prefix') + ' ' + error.message];
                        }
                    });
            },
        }
    }
</script>