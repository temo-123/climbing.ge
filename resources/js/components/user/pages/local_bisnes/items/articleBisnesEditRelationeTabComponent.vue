<template>
    <div class="width_100" style="margin-bottom: 20px; padding: 20px; border: 1px solid #007bff; border-radius: 5px; background-color: #f8f9fa;" >
        <div class="col-md-12">
            <div class="row">
                <h2>Article Relations</h2>
                <p class="text-muted">Select a category to automatically relate this business with all articles in that category, or manually select specific articles below.</p>
            </div>

            <div class="form-group clearfix">
                <label for="selected_category" class="col-xs-2 control-label">Select option</label>
                <div class="col-xs-8">
                    <select class="form-control" v-model="article_options" @change="onCategoryChange()">
                        <option disabled value="">Select option</option>
                        <option value="individual">Inndividual article</option>
                        <option value="categoriable">All For Options</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Category Selection Section -->
        <div class="col-md-12" v-if="article_options === 'categoriable'">
            <h4>Automatic Category-Based Relations</h4>
            <div class="form-group clearfix">
                <label for="selected_category" class="col-xs-2 control-label">Select Category</label>
                <div class="col-xs-8">

                    <select class="form-control" v-model="selected_category" @change="onCategoryChange()">
                        <option value="">Select a category</option>
                        <option v-for="category in article_categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>
            </div>
            <div v-if="selected_category" class="alert alert-info">
                <strong>Auto-relation enabled:</strong> This business will be related to all articles with category "{{ selected_category }}".
                <button type="button" class="btn btn-danger" @click="clearCategorySelection()" style="margin-left: 10px;">Clear</button>
            </div>
        </div>


        <div class="row" v-if="article_options === 'individual'">

            <div class="col-md-12">
                <div class="form-groupe">
                    <button class="btn btn-primary float-left" @click="add_new_article_value()">Add new article</button>
                </div>
            </div>
            
            <div class="col-md-12">
                <table class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Category</th>
                            <th>Delete item</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(article, index) in new_article_relationes" :key="article.id">
                            <td>
                                <select class="form-control" v-model="article.article_id" @change="article_select(index, $event)">
                                    <option disabled value="">Select article</option>
                                    <option v-for="region in get_filtered_regions()" :key="region.id" :value="region.id">
                                        {{ region.url_title }}
                                    </option>
                                </select>
                            </td>
                            <td>{{ get_article_category(article.article_id) }}</td>
                            <td>
                                <button class="btn btn-danger" @click="del_article(index)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <div class="form-groupe">
                    <button class="btn btn-primary float-left" @click="add_new_article_value()">Add new article</button>
                </div>
            </div>
        </div>

        <!-- Validation Conflicts Report -->
        <div v-if="showValidationConflicts" class="panel panel-warning" style="margin-top: 20px;">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-exclamation-triangle"></i>
                    Article Relation Conflicts Found
                </h3>
            </div>
            <div class="panel-body">
                <div class="alert alert-warning">
                    <strong>Warning:</strong> The following articles already have business relations. 
                    Each article can only be related to one business.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Category</th>
                                <th>Currently Related To</th>
                                <th>Proposed For</th>
                                <th>Action</th>
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
                                    <span class="label label-info">{{ conflict.article_category || 'No Category' }}</span>
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
                                            Skip Article
                                        </button>
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-success"
                                            :class="{'active': validationUserChoices[conflict.article_id] === 'add'}"
                                            @click="setValidationChoice(conflict.article_id, 'add')"
                                        >
                                            Add Anyway
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div v-if="hasUndecidedValidationArticles" class="alert alert-info">
                    <strong>Note:</strong> Please choose an action for all conflicting articles before proceeding.
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
                            Continue with Selected Articles ({{ decidedValidationArticlesCount }} articles)
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-default btn-lg"
                            @click="cancelValidation"
                        >
                            <i class="fa fa-times"></i> 
                            Cancel & Review
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'bisnes_id_prop',
            'initial_selected_category_prop',
        ],

        data() {
            return {
                new_article_relationes: [],
                selected_category: null,
                global_category_filter: '',
                regions: [],
                filtered_regions: [],

                article_options: '',
                bisnes_id: null,
                
                // Required for validation
                article_categories: [],

                // Validation related properties
                showValidationConflicts: false,
                validationConflicts: [],
                validationUserChoices: {},
                pendingFormData: null,
                parentComponent: null,

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
            this.get_region_data()
            this.get_article_categories()

            // Set initial state based on prop
            // if (this.initial_selected_category_prop) {
            //     this.article_options = 'categoriable'
                this.selected_category = this.initial_selected_category_prop
            // } else {
            //     this.article_options = ''
            // }

            // If this is edit mode, load existing data
            if (this.bisnes_id_prop) {
                this.load_existing_relations()
            }
        },

        watch: {
            regions: {
                handler(newRegions) {
                    this.filtered_regions = newRegions
                    this.filterArticlesGlobally()
                },
                immediate: true
            },
            initial_selected_category_prop: function(){
                this.selected_category = this.initial_selected_category_prop
// alert(this.selected_category)
                if (this.initial_selected_category_prop) {
                    this.article_options = 'categoriable'
                    this.selected_category = this.initial_selected_category_prop
                } else {
                    this.article_options = ''
                }
            },
            // article_options: {
            //     handler(newOption) {
            //         // Clear data when switching between modes
            //         if (newOption === 'categoriable') {
            //             this.new_article_relationes = []
            //             this.update_perent_component_data()
            //             this.selected_category = null
            //             this.update_perent_component_category()
            //         } else if (newOption === 'individual') {
            //             this.selected_category = null
            //             this.update_perent_component_category()
            //         }
            //     }
            // },
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
                if (!article_id) return 'No article selected';
                
                const article = this.regions.find(region => region.id == article_id);
                return article ? (article.category || 'No category') : 'Article not found';
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


            load_existing_relations() {
                if (this.bisnes_id_prop) {
                    this.bisnes_id = this.bisnes_id_prop;
                    // Load existing article relations for this business
                    axios
                        .get(`/set_bisnes/get_bisnes_article_relations/${this.bisnes_id}`)
                        .then(response => {
                            const existingRelations = response.data;
                            if (existingRelations && existingRelations.length > 0) {
                                // Check if this is category-based relations or individual relations
                                const hasCategoryRelation = existingRelations.some(relation => relation.category_based === true);

                                if (hasCategoryRelation) {
                                    // This is a category-based relation
                                    const categoryRelation = existingRelations.find(relation => relation.category_based === true);
                                    this.selected_category = categoryRelation.category_id;
                                    if (!this.initial_selected_category_prop) {
                                        this.article_options = 'categoriable';
                                    }
                                    this.update_perent_component_category();
                                } else {
                                    // These are individual article relations
                                    existingRelations.forEach((relation, index) => {
                                        this.new_article_relationes.push({
                                            id: index + 1,
                                            article_id: relation.article_id
                                        });
                                    });
                                    if (!this.initial_selected_category_prop) {
                                        this.article_options = 'individual';
                                    }
                                    this.update_perent_component_data();
                                }
                            }
                        })
                        .catch(error => console.log(error));
                }
            },

            // Validation methods
            validateRelationsBeforeSave(formData, parentComponent) {
                this.parentComponent = parentComponent;
                this.pendingFormData = formData;
                
                console.log('=== VALIDATION CALL ===');
                console.log('Parent business ID:', parentComponent.business_id);
                console.log('Child bisnes_id:', this.bisnes_id);
                console.log('FormData stored for validation selections');
                
                // Use the parent component's business_id for consistency
                const businessId = parentComponent.business_id || this.bisnes_id;
                
                axios
                    .post('/set_bisnes/edit_local_bisnes/' + businessId, formData)
                    .then(response => {
                        console.log('=== VALIDATION RESPONSE ===');
                        console.log('Response:', response);
                        
                        // Check if validation conflicts were returned
                        if (response.data.validation_needed) {
                            console.log('Validation conflicts found:', response.data.conflicting_articles);
                            this.showValidationConflicts = true;
                            this.validationConflicts = response.data.conflicting_articles;
                        } else {
                            console.log('No validation conflicts, proceeding with save...');
                            // No conflicts, proceed with save
                            this.parentComponent.proceedWithSave(formData);
                        }
                    })
                    .catch(error => {
                        console.error('Validation error:', error);
                        console.log('Error response:', error.response);
                        this.parentComponent.handleValidationError(error);
                    });
            },

            // Validation report handlers
            closeValidationReport() {
                this.showValidationConflicts = false;
                this.validationConflicts = [];
                this.validationUserChoices = {};
                this.pendingFormData = null;
                // Don't clear parentComponent here - it might still be needed
                // this.parentComponent = null;
            },

            cancelValidation() {
                this.closeValidationReport();
            },

            // Set user choice for a specific conflict
            setValidationChoice(articleId, choice) {
                this.$set(this.validationUserChoices, articleId, choice);
            },

            // Proceed with user's validation selections
            proceedWithValidationSelections() {
                console.log('Starting proceedWithValidationSelections...');
                
                // Get parent component reference dynamically to avoid losing it
                const parentComponent = this.$parent;
                if (!parentComponent) {
                    console.error('Parent component not found');
                    alert('Error: Parent component not found. Please refresh the page and try again.');
                    return;
                }
                
                // Get all articles that user chose to add from validation conflicts
                const allowedArticleIds = [];
                
                // Use validation conflicts as the source of truth for which articles to add
                this.validationConflicts.forEach(conflict => {
                    if (this.validationUserChoices[conflict.article_id] === 'add') {
                        allowedArticleIds.push(conflict.article_id);
                    }
                });

                console.log('Filtered article IDs from conflicts:', allowedArticleIds);
                console.log('Selected category:', this.selected_category);
                console.log('Parent component data:', parentComponent.data);

                // CRITICAL: Handle change_url_title logic like parent component does
                // Create a copy of parent data to avoid modifying the original
                const dataToSend = JSON.parse(JSON.stringify(parentComponent.data));
                
                // Apply the same URL title change logic as parent component
                if (parentComponent.change_url_title) {
                    dataToSend.global_bisnes.change_url_title = true;
                    dataToSend.global_bisnes.url_title = dataToSend.us_bisnes.title;
                    dataToSend.us_bisnes.is_change_url_title = true;
                } else {
                    dataToSend.global_bisnes.change_url_title = false;
                    dataToSend.us_bisnes.is_change_url_title = false;
                }

                console.log('Data with URL title changes:', dataToSend);

                // Use the existing FormData from parent instead of creating new one
                let formData = this.pendingFormData;
                
                // Clear existing relation data from formData
                const formDataKeys = Array.from(formData.keys());
                formDataKeys.forEach(key => {
                    if (key.includes('bisnes_new_article_relations[')) {
                        formData.delete(key);
                    }
                });

                // Add filtered individual relations to the existing FormData
                if (allowedArticleIds.length > 0) {
                    console.log('Adding article relations:', allowedArticleIds.length);
                    var relation_loop_num = 0;
                    allowedArticleIds.forEach(id => {
                        formData.append('bisnes_new_article_relations['+relation_loop_num+']', id);
                        relation_loop_num++;
                    });
                }

                // Update the main data JSON with any changes
                console.log('Updating data JSON...');
                const dataJson = JSON.stringify(dataToSend);
                console.log('Data to send:', dataJson);
                formData.set('data', dataJson);

                // Debug: Check what we're actually sending
                console.log('FormData entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, ':', value);
                }

                // Close validation report and proceed with save
                this.closeValidationReport();
                console.log('Calling parent proceedWithSave...');
                console.log('Parent business ID:', parentComponent.business_id);
                
                parentComponent.proceedWithSave(formData);
            },
        },
    }
</script>

