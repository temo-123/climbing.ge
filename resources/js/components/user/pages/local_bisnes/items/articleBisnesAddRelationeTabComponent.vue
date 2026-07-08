<template>
    <div class="width_100" style="margin-bottom: 20px; padding: 20px; border: 1px solid #007bff; border-radius: 5px; background-color: #f8f9fa;" >
        <div class="col-md-12">
            <div class="row">
                <h2>{{ $t('admin.local_business.article_relations_title') }}</h2>
                <p class="text-muted">{{ $t('admin.local_business.article_relations_hint') }}</p>
            </div>

            <div class="form-group clearfix">
                <label for="selected_category" class="col-xs-2 control-label">{{ $t('admin.local_business.select_option_label') }}</label>
                <div class="col-xs-8">
                    <select class="form-control" v-model="article_options" @change="onCategoryChange()">
                        <option disable value="">{{ $t('admin.local_business.select_option_label') }}</option>
                        <option value="individual">{{ $t('admin.local_business.individual_article_option') }}</option>
                        <option value="categoriable">{{ $t('admin.local_business.all_for_options_option') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Category Selection Section -->
        <div class="col-md-12" v-if="article_options === 'categoriable'">
            <h4>{{ $t('admin.local_business.auto_category_relations_title') }}</h4>
            <div class="form-group clearfix">
                <label for="selected_category" class="col-xs-2 control-label">{{ $t('admin.export.select_category_label') }}</label>
                <div class="col-xs-8">

                    <select class="form-control" v-model="selected_category" @change="onCategoryChange()">
                        <option value="">{{ $t('admin.local_business.select_a_category_placeholder') }}</option>
                        <option v-for="category in article_categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>
            </div>
            <div v-if="selected_category" class="alert alert-info">
                <strong>{{ $t('admin.local_business.auto_relation_enabled_prefix') }}</strong> {{ $t('admin.local_business.auto_relation_enabled_text', { category: selected_category }) }}
                <button type="button" class="btn btn-danger" @click="clearCategorySelection()" style="margin-left: 10px;">{{ $t('admin.local_business.clear_btn') }}</button>
            </div>
        </div>


        <div class="row" v-if="article_options === 'individual'">

            <div class="col-md-12">
                <div class="form-groupe">
                    <button class="btn btn-primary float-left" @click="add_new_article_value()">{{ $t('admin.local_business.add_new_article_btn') }}</button>
                </div>
            </div>

            <div class="col-md-12">
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>{{ $t('admin.comments.col_article') }}</th>
                            <th>{{ $t('common.category') }}</th>
                            <th>{{ $t('admin.local_business.col_delete_item') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(article, index) in new_article_relationes" :key="article.id">
                            <td>
                                <select class="form-control" v-model="article.article_id" @change="article_select(index, $event)">
                                    <option disabled value="">{{ $t('admin.local_business.select_article_placeholder') }}</option>
                                    <option v-for="region in get_filtered_regions()" :key="region.id" :value="region.id">
                                        {{ region.url_title }}
                                    </option>
                                </select>
                            </td>
                            <td>{{ get_article_category(article.article_id) }}</td>
                            <td>
                                <button class="btn btn-danger" @click="del_article(index)">{{ $t('common.delete') }}</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <div class="form-groupe">
                    <button class="btn btn-primary float-left" @click="add_new_article_value()">{{ $t('admin.local_business.add_new_article_btn') }}</button>
                </div>
            </div>
        </div>

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
    </div>
</template>

<script>
    export default {
        // props: [
        //     'article_categories',
        // ],

        data() {
            return {
                new_article_relationes: [],
                selected_category: null,
                global_category_filter: '',
                regions: [],
                filtered_regions: [],

                article_options: '',
                
                // Required for validation
                article_categories: [],

                parentComponent: null,
            }
        },

        computed: {
            // Validation computed properties are handled by parent component
        },
        mounted() {
            this.get_region_data()
            this.get_article_categories()
        },

        watch: {
            regions: {
                handler(newRegions) {
                    this.filtered_regions = newRegions
                    this.filterArticlesGlobally()
                },
                immediate: true
            },
            article_options: {
                handler(newOption) {
                    // Clear data when switching between modes
                    if (newOption === 'categoriable') {
                        this.new_article_relationes = []
                        this.update_perent_component_data()
                    } else if (newOption === 'individual') {
                        this.selected_category = null
                        this.update_perent_component_category()
                    }
                }
            }
        },
        methods: {
            get_region_data: function () {
                axios
                    .get("/get_article/get_articles_for_bisnes_suport")
                    .then((response) => {
                        this.regions = response.data;
                        this.filtered_regions = response.data;
                    })
                    .catch((error) => console.log(error));
            },

            get_article_categories: function () {
                axios
                    .get("/set_bisnes/get_article_categories")
                    .then((response) => {
                        this.article_categories = response.data;
                    })
                    .catch((error) => console.log(error));
            },

            onCategoryChange() {
                // Clear manual selections when category is selected
                if (this.selected_category) {
                    this.new_article_relationes = []
                    this.update_perent_component_data()
                    // No need to trigger validation here - it will be handled when save is clicked
                }
                this.update_perent_component_category()
            },




            clearCategorySelection() {
                this.selected_category = null
                this.update_perent_component_category()
            },

            add_new_article_value() {
                var new_item_id = this.new_article_relationes.length + 1
                this.new_article_relationes.push({
                    id: new_item_id,
                    article_id: '',
                });
            },


            filterArticlesGlobally() {
                if (this.global_category_filter === '') {
                    this.filtered_regions = this.regions;
                } else {
                    this.filtered_regions = this.regions.filter(region => region.category === this.global_category_filter);
                }
            },

            get_filtered_regions() {
                return this.filtered_regions;
            },


            get_article_category(article_id) {
                if (!article_id) return this.$t('admin.local_business.no_article_selected');

                const article = this.regions.find(region => region.id == article_id);
                return article ? (article.category || this.$t('admin.local_business.no_category_fallback')) : this.$t('admin.local_business.article_not_found');
            },

            article_select(index, event) {
                console.log('article_select called with index:', index, 'value:', event.target.value);
                if (index >= 0 && index < this.new_article_relationes.length) {
                    this.new_article_relationes[index]['article_id'] = event.target.value;
                    this.update_perent_component_data();
                }
            },

            del_article(index) {
                console.log('del_article called with index:', index);
                if (index >= 0 && index < this.new_article_relationes.length) {
                    this.new_article_relationes.splice(index, 1);
                    this.update_perent_component_data();
                }
            },

            update_perent_component_data() {
                this.$emit("update_article_relations", this.new_article_relationes);
            },

            update_perent_component_category() {
                this.$emit("update_selected_category", this.selected_category);
            },

            // Set user choice for a specific conflict
            setValidationChoice(articleId, choice) {
                // Emit event to parent component
                this.$emit('validation-choice-made', articleId, choice);
            },
        }
    }
</script>

