<template>
    <div class="tabs">
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-primary" @click="go_back()">{{ $t('common.back') }}</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group">  
                <button type="submit" class="btn btn-primary" v-on:click="add_bisnes()" >{{ $t('common.save') }}</button>
            </div>
        </div>
        <div class="row" v-show="error.length != 0">
            <validator_alerts_component
                :errors_prop="error"
            />
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
                        <div class="form-group clearfix row">
                            <label for="published" class='col-md-2 control-label '> {{ $t('admin.common.publish') }} </label>
                            <div class="col-md-10">
                                <select class="form-control" v-model="data.global_bisnes.published" name="published" >
                                    <option value="0">{{ $t('admin.common.not_public') }}</option>
                                    <option value="1">{{ $t('admin.common.public') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix" v-if="!data.global_bisnes.public_totaly">
                            <label for="name" class='col-xs-2 control-label'> {{ $t('admin.local_business.published_before_label') }} </label>
                            <div class="col-xs-8">
                                <input type="date" class="form-control" id="datemin" name="datemin" min="2000-01-02" v-model="data.global_bisnes.published_data" >
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="scales" class='col-xs-2 control-label'> {{ $t('admin.local_business.totaly_public_label') }} </label>
                            <div class="col-xs-8">
                                <input type="checkbox" id="scales" name="scales" @click="clear_published_time()" v-model="data.global_bisnes.public_totaly" >
                            </div>
                        </div>
                    </form>

                    <article_bisnes_add_relatione_tab
                        ref="article_bisnes_add_relatione_tab"
                        @update_article_relations="update_article_relations"
                        @update_selected_category="update_selected_category"
                        @validation-choice-made="handleValidationChoice"
                    />
                    
                    <!-- Validation Conflicts Report -->
                    <div v-if="showValidationConflicts" class="panel panel-warning" style="margin-top: 20px;">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-exclamation-triangle"></i>
                                {{ $t('admin.local_business.conflicts_found_title') }}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-warning">
                                {{ $t('admin.local_business.conflicts_warning') }}
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ $t('admin.comments.col_article') }}</th>
                                            <th>{{ $t('common.category') }}</th>
                                            <th>{{ $t('admin.local_business.col_currently_related_to') }}</th>
                                            <th>{{ $t('admin.local_business.col_proposed_for') }}</th>
                                            <th>{{ $t('admin.local_business.col_action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(conflict, index) in validationConflicts" :key="`conflict-${conflict.article_id}-${index}`">
                                            <td>
                                                <strong>{{ conflict.article_title }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ conflict.article_id }}</small>
                                            </td>
                                            <td>
                                                <span class="label label-info">{{ conflict.article_category || $t('admin.local_business.no_category_fallback') }}</span>
                                            </td>
                                            <td>
                                                <span class="label label-default">{{ conflict.current_business }}</span>
                                            </td>
                                            <td>
                                                <span class="label label-warning">{{ conflict.proposed_business }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-danger"
                                                        :class="{'active': validationUserChoices[conflict.article_id] === 'skip'}"
                                                        @click="setValidationChoice(conflict.article_id, 'skip')"
                                                    >
                                                        {{ $t('admin.local_business.skip_article_btn') }}
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-success"
                                                        :class="{'active': validationUserChoices[conflict.article_id] === 'add'}"
                                                        @click="setValidationChoice(conflict.article_id, 'add')"
                                                    >
                                                        {{ $t('admin.local_business.add_anyway_btn') }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div v-if="hasUndecidedValidationArticles" class="alert alert-info">
                                {{ $t('admin.local_business.choose_action_note') }}
                            </div>

                            <div class="text-center">
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-lg"
                                        :disabled="hasUndecidedValidationArticles"
                                        @click="proceedWithValidationSelections"
                                    >
                                        <i class="fa fa-check"></i>
                                        {{ $t('admin.local_business.continue_selected_articles_btn', { count: decidedValidationArticlesCount }) }}
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-default btn-lg"
                                        @click="cancelValidation"
                                    >
                                        <i class="fa fa-times"></i>
                                        {{ $t('admin.local_business.cancel_review_btn') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <gallery_images_add
                        @update_gallery_images="update_gallery_images"

                        :image_path_prop="'images/local_bisnes_img/'"
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
    import gallery_images_add from '../../items//gallery/galleryImageAddComponent.vue'
    import article_bisnes_add_relatione_tab from './items/articleBisnesAddRelationeTabComponent.vue'

    // import validator_alerts_component from '../../items/validator_alerts_component.vue'
    export default {
        mixins: [
            ],
        components: {
            gallery_images_add,
            article_bisnes_add_relatione_tab,
            // validator_alerts_component
        },
        props: [
            // 'back_url',
        ],
        data(){
            return {
                tab_num: 1,

                bisnes_new_article_relations: [],
                bisnes_images: [],
                // regions: [],
                article_categories: [],
                selected_category: null,

                error: [],
                
                // Validation related properties
                showValidationConflicts: false,
                validationConflicts: [],
                validationUserChoices: {},
                editor_config: {
                    us_short_description: {},
                    us_text: {},
                    ru_short_description: {},
                    ru_text: {},
                    ka_short_description: {},
                    ka_text: {},
                },

                data: {
                    global_bisnes: {
                        url_title: '',

                        published: 0,
                        published_data: null,
                        public_totaly: 0,
                    },

                    us_bisnes: {
                        title: "",
                        short_description: "",
                        text: "",
                    },

                    ka_bisnes: {
                        title: "",
                        short_description: "",
                        text: "",
                    },

                    ru_bisnes: {
                        title: "",
                        short_description: "",
                        text: "",
                    }
                },

                myModal: false,

                
            }
        },
        computed: {
            hasUndecidedValidationArticles() {
                return this.validationConflicts.some(conflict => !this.validationUserChoices[conflict.article_id]);
            },
            decidedValidationArticlesCount() {
                return Object.keys(this.validationUserChoices).filter(id => this.validationUserChoices[id] === 'add').length;
            }
        },

        mounted() {
            // this.get_bisnes_category_bisnes()
            this.get_region_bisnes()
            this.get_article_categories()
            
            document.querySelector('body').style.marginLeft = '0';
            document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
        },

        methods: {
            clear_published_time(){
                this.data.global_bisnes.published_data = null
            },
            // get_region_bisnes: function () {
            //     axios
            //         .get("../api/article/")
            //         .then((response) => {
            //             this.regions = response.data;
            //         })
            //         .catch((error) => console.log(error));
            // },


            update_article_relations(articles){
                this.bisnes_new_article_relations = articles
            },

            update_selected_category(category){
                this.selected_category = category
            },

            update_gallery_images(images){
                this.bisnes_images = images
            },

            add_bisnes() {
                this.data.global_bisnes.url_title = this.data.us_bisnes.title
                let formData = new FormData();

                if(this.bisnes_images){
                    var loop_num = 0
                    this.bisnes_images.forEach(image => {
                        formData.append('bisnes_images['+loop_num+']', image.image)
                        loop_num++
                    });
                    loop_num = 0
                }

                if(this.selected_category){
                    formData.append('selected_category', this.selected_category)
                }

                if(this.bisnes_new_article_relations){
                    var relation_loop_num = 0
                    this.bisnes_new_article_relations.forEach(relation => {
                        formData.append('bisnes_new_article_relations['+relation_loop_num+']', relation.article_id)
                        relation_loop_num++
                    });
                    relation_loop_num = 0
                }

                formData.append('data', JSON.stringify(this.data))

                // First validate relations if any are selected
                if (this.selected_category || (this.bisnes_new_article_relations && this.bisnes_new_article_relations.length > 0)) {
                    this.validateRelationsBeforeSave(formData);
                } else {
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
                    this.$router.go(-1)
                }
            },

            // Validation report handlers
            closeValidationReport() {
                this.showValidationConflicts = false;
                this.validationConflicts = [];
                this.validationUserChoices = {};
            },

            cancelValidation() {
                this.closeValidationReport();
            },

            handleValidationProceed(filteredRelations) {
                this.closeValidationReport();

                // Prepare form data with filtered relations
                let formData = new FormData();
                formData.append('data', JSON.stringify(this.data));

                // Add filtered individual relations
                if (filteredRelations.add && filteredRelations.add.length > 0) {
                    filteredRelations.add.forEach((id, index) => {
                        formData.append('bisnes_new_article_relations[' + index + ']', id);
                    });
                }

                // Add category if selected
                if (this.selected_category) {
                    formData.append('selected_category', this.selected_category);
                }

                // Add images
                if (this.bisnes_images) {
                    var image_loop_num = 0;
                    this.bisnes_images.forEach(image => {
                        formData.append('bisnes_images[' + image_loop_num + ']', image.image);
                        image_loop_num++;
                    });
                }

                // Proceed with save
                this.proceedWithSave(formData);
            },

            // Set user choice for a specific conflict
            setValidationChoice(articleId, choice) {
                this.validationUserChoices[articleId] = choice;
            },

            // Proceed with user's validation selections
            proceedWithValidationSelections() {
                // Filter out articles that user chose to skip
                const allowedArticleIds = [];
                const allowedRelations = [];
                
                this.bisnes_new_article_relations.forEach(relation => {
                    if (this.validationUserChoices[relation.article_id] === 'add') {
                        allowedArticleIds.push(relation.article_id);
                        allowedRelations.push(relation);
                    }
                });

                // Prepare form data with filtered relations - same as in add_bisnes()
                this.data.global_bisnes.url_title = this.data.us_bisnes.title;
                let formData = new FormData();

                // Add images
                if (this.bisnes_images) {
                    var loop_num = 0;
                    this.bisnes_images.forEach(image => {
                        formData.append('bisnes_images[' + loop_num + ']', image.image);
                        loop_num++;
                    });
                    loop_num = 0;
                }

                // Add category if selected
                if (this.selected_category) {
                    formData.append('selected_category', this.selected_category);
                }

                // Add filtered individual relations
                allowedArticleIds.forEach((id, index) => {
                    formData.append('bisnes_new_article_relations[' + index + ']', id);
                });

                formData.append('data', JSON.stringify(this.data));

                // Close validation report and proceed with save
                this.closeValidationReport();
                this.proceedWithSave(formData);
            },

            // Handle validation errors from child component
            handleValidationError(error) {
                if (error.response.status == 422) {
                    this.error = error.response.data.validation;
                } else {
                    alert(this.$t('admin.local_business.validation_error_prefix') + ' ' + error);
                }
            },

            // Handle validation choice made in child component
            handleValidationChoice(articleId, choice) {
                this.validationUserChoices[articleId] = choice;
            },

            // Validate relations before save
            validateRelationsBeforeSave(formData) {
                axios
                    .post('/set_bisnes/add_local_bisnes', formData)
                    .then(response => {
                        // Check if validation conflicts were returned
                        if (response.data.validation_needed) {
                            this.showValidationConflicts = true;
                            this.validationConflicts = response.data.conflicting_articles;
                        } else {
                            // No conflicts, proceed with save
                            this.proceedWithSave(formData);
                        }
                    })
                    .catch(error => {
                        this.handleValidationError(error);
                    });
            },

            // Proceed with actual save
            proceedWithSave(formData) {
                axios
                    .post('/set_bisnes/add_local_bisnes', formData)
                    .then(response => {
                        this.go_back(true);
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.error = error.response.data.validation;
                        } else {
                            alert(error);
                        }
                    });
            },
        }
    }
</script>