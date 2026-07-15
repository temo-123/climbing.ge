<template>
    <div class="width_100" style="margin-bottom: 20px; padding: 20px; border: 1px solid #007bff; border-radius: 5px; background-color: #f8f9fa;" >
        <div class="col-md-12">
            <div class="row">
                <h2>{{ $t('admin.local_business.article_relations_title') }}</h2>
                <p class="text-muted">{{ $t('admin.local_business.article_relations_hint') }}</p>
            </div>

            <div class="form-group clearfix">
                <label class="col-xs-2 control-label">{{ $t('admin.local_business.select_option_label') }}</label>
                <div class="col-xs-8">
                    <div class="btn-group" role="group">
                        <button
                            type="button"
                            class="btn"
                            :class="use_category ? 'btn-primary' : 'btn-outline-primary'"
                            @click="toggleCategoryMode()"
                        >{{ $t('admin.local_business.all_for_options_option') }}</button>
                        <button
                            type="button"
                            class="btn"
                            :class="use_individual ? 'btn-primary' : 'btn-outline-primary'"
                            @click="toggleIndividualMode()"
                        >{{ $t('admin.local_business.individual_article_option') }}</button>
                    </div>
                    <p class="text-muted small" style="margin-top: 6px;">{{ $t('admin.local_business.combine_modes_hint') }}</p>
                </div>
            </div>
        </div>

        <!-- Category Selection Section -->
        <div class="col-md-12" v-if="use_category">
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

            <div v-if="selected_category && categoryArticlesOverview.length > 0" class="table-responsive" style="margin-top: 15px;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ $t('admin.comments.col_article') }}</th>
                            <th>{{ $t('admin.local_business.relation_slot_label', { n: 1 }) }}</th>
                            <th>{{ $t('admin.local_business.relation_slot_label', { n: 2 }) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="article in categoryArticlesOverview" :key="article.article_id">
                            <td>{{ article.article_title }}</td>
                            <td v-for="slot in [0, 1]" :key="slot">
                                <span v-if="relationAt(article.related_businesses, slot)" class="label label-default">
                                    {{ relationAt(article.related_businesses, slot).bisnes_title }}
                                    <a href="#" @click.prevent="deleteRelation(article.article_id, relationAt(article.related_businesses, slot).bisnes_id)" :title="$t('admin.local_business.delete_relation_btn')" style="color: inherit; margin-left: 4px;">&times;</a>
                                </span>
                                <span v-else class="text-muted">&mdash;</span>
                                <div v-if="slot === 1 && article.related_businesses.length > maxRelations" class="small text-danger" style="margin-top: 4px;">
                                    {{ $t('admin.local_business.extra_relations_note', { count: article.related_businesses.length - maxRelations }) }}
                                    <a href="#" @click.prevent="deleteRelation(article.article_id, lastRelation(article.related_businesses).bisnes_id)">{{ $t('admin.local_business.delete_last_relation_btn') }}</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="row" v-if="use_individual">

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
                            <th>{{ $t('admin.local_business.relation_slot_label', { n: 1 }) }}</th>
                            <th>{{ $t('admin.local_business.relation_slot_label', { n: 2 }) }}</th>
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
                            <td v-for="slot in [0, 1]" :key="slot">
                                <span v-if="article.article_id && relationAt(getRelatedBusinesses(article.article_id), slot)" class="label label-default">
                                    {{ relationAt(getRelatedBusinesses(article.article_id), slot).bisnes_title }}
                                    <a href="#" @click.prevent="deleteRelation(article.article_id, relationAt(getRelatedBusinesses(article.article_id), slot).bisnes_id)" :title="$t('admin.local_business.delete_relation_btn')" style="color: inherit; margin-left: 4px;">&times;</a>
                                </span>
                                <span v-else class="text-muted">&mdash;</span>
                                <div v-if="slot === 1 && article.article_id && getRelatedBusinesses(article.article_id).length > maxRelations" class="small text-danger" style="margin-top: 4px;">
                                    {{ $t('admin.local_business.extra_relations_note', { count: getRelatedBusinesses(article.article_id).length - maxRelations }) }}
                                    <a href="#" @click.prevent="deleteRelation(article.article_id, lastRelation(getRelatedBusinesses(article.article_id)).bisnes_id)">{{ $t('admin.local_business.delete_last_relation_btn') }}</a>
                                </div>
                            </td>
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

                use_category: false,
                use_individual: false,

                // Required for validation
                article_categories: [],
                maxRelations: 2,

                // Relation overview (shows ALL current relations for the relevant articles,
                // not just conflicts)
                categoryArticlesOverview: [],
                individualArticlesOverview: {},

                parentComponent: null,
            }
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
            // Category and individual relations are combinable - toggling one off
            // clears just its own data, without touching the other.
            use_category(enabled) {
                if (!enabled) {
                    this.selected_category = null
                    this.update_perent_component_category()
                }
            },
            use_individual(enabled) {
                if (!enabled) {
                    this.new_article_relationes = []
                    this.update_perent_component_data()
                }
            },
            selected_category: {
                handler(newCategory) {
                    this.fetchCategoryOverview(newCategory)
                },
                immediate: true
            },
            new_article_relationes: {
                handler() {
                    this.fetchIndividualOverview()
                },
                deep: true
            },
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

            // Every article in the selected category, with ALL of its current relations
            // (not just conflicts) - so the admin can see the full picture before saving.
            fetchCategoryOverview(category) {
                if (!category) {
                    this.categoryArticlesOverview = [];
                    return;
                }
                axios
                    .post('/set_bisnes/get_article_relations_overview', { category })
                    .then(response => {
                        this.categoryArticlesOverview = response.data.articles;
                    })
                    .catch(error => console.log(error));
            },

            // Current relations for every article picked in individual mode.
            fetchIndividualOverview() {
                const articleIds = this.new_article_relationes
                    .map(relation => relation.article_id)
                    .filter(id => id);

                if (articleIds.length === 0) {
                    this.individualArticlesOverview = {};
                    return;
                }

                axios
                    .post('/set_bisnes/get_article_relations_overview', { article_ids: articleIds })
                    .then(response => {
                        const overview = {};
                        response.data.articles.forEach(article => {
                            overview[article.article_id] = article.related_businesses;
                        });
                        this.individualArticlesOverview = overview;
                    })
                    .catch(error => console.log(error));
            },

            getRelatedBusinesses(articleId) {
                return this.individualArticlesOverview[articleId] || [];
            },

            relationAt(relations, index) {
                return (relations && relations[index]) || null;
            },

            lastRelation(relations) {
                return relations[relations.length - 1];
            },

            deleteRelation(articleId, bisnesId) {
                if (!confirm(this.$t('admin.common.confirm_delete'))) return;
                axios
                    .delete(`/set_bisnes/del_bisnes_article_relation/${articleId}/${bisnesId}`)
                    .then(() => {
                        this.fetchCategoryOverview(this.selected_category);
                        this.fetchIndividualOverview();
                    })
                    .catch(error => console.log(error));
            },

            toggleCategoryMode() {
                this.use_category = !this.use_category
            },

            toggleIndividualMode() {
                this.use_individual = !this.use_individual
            },

            onCategoryChange() {
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
        }
    }
</script>

