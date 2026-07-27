<template>
    <div class="business-relation-form">
        <h4 class="form-section-title">
            <i class="fa fa-briefcase"></i> {{ $t('admin.articles.business_relation.title') }}
        </h4>

        <div v-if="loading" class="text-center p-3">
            <i class="fa fa-spinner fa-spin"></i> {{ $t('admin.export.loading_ellipsis') }}
        </div>

        <div v-else>
            <div v-if="related_businesses.length" class="current-relations">
                <div
                    v-for="business in related_businesses"
                    :key="business.bisnes_id"
                    class="current-relation alert alert-info"
                >
                    <span>{{ business.us_title || business.ka_title || business.bisnes_title }}</span>
                    <button class="btn btn-xs btn-danger pull-right" @click="remove_relation(business.bisnes_id)">
                        <i class="fa fa-times"></i> {{ $t('admin.single_image.remove_btn') }}
                    </button>
                </div>
            </div>
            <div v-else class="alert alert-warning">
                {{ $t('admin.articles.business_relation.no_business_linked') }}
            </div>

            <div class="form-group" v-if="related_businesses.length < max_relations">
                <label>{{ $t('admin.articles.business_relation.link_to_business_label') }}</label>
                <select class="form-control" v-model="selected_bisnes_id">
                    <option :value="null">{{ $t('admin.articles.business_relation.select_business_placeholder') }}</option>
                    <option
                        v-for="business in available_businesses"
                        :key="business.id"
                        :value="business.id"
                    >
                        {{ business_title(business) }}
                    </option>
                </select>

                <button
                    class="btn btn-primary"
                    style="margin-top: 10px;"
                    :disabled="!selected_bisnes_id || saving"
                    @click="add_relation"
                >
                    <i class="fa fa-link"></i>
                    {{ saving ? $t('admin.articles.business_relation.saving_ellipsis') : $t('admin.articles.business_relation.link_business_btn') }}
                </button>
            </div>
            <p v-else class="text-muted small">
                {{ $t('admin.articles.business_relation.max_relations_note', { max: max_relations }) }}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'BusinessRelationFormComponent',
        props: {
            article_id: {
                type: [String, Number],
                required: true
            }
        },
        data() {
            return {
                businesses: [],
                related_businesses: [],
                selected_bisnes_id: null,
                loading: true,
                saving: false,
                max_relations: 2,
            }
        },
        computed: {
            available_businesses() {
                const related_ids = this.related_businesses.map(business => business.bisnes_id);
                return this.businesses.filter(business => !related_ids.includes(business.id));
            }
        },
        mounted() {
            Promise.all([this.fetch_businesses(), this.fetch_current_relations()])
                .finally(() => this.loading = false)
        },
        methods: {
            business_title(business) {
                return (business.us_bisnes && business.us_bisnes.title)
                    || (business.ka_bisnes && business.ka_bisnes.title)
                    || business.url_title
            },
            fetch_businesses() {
                return axios.get('/set_bisnes/get_local_bisneses_list')
                    .then(res => { this.businesses = res.data })
                    .catch(() => {})
            },
            fetch_current_relations() {
                return axios.get('/set_bisnes/get_article_bisnes_relations/' + this.article_id)
                    .then(res => { this.related_businesses = res.data })
                    .catch(() => {})
            },
            add_relation() {
                if (!this.selected_bisnes_id) return
                this.saving = true

                const bisnes_ids = this.related_businesses.map(business => business.bisnes_id)
                bisnes_ids.push(this.selected_bisnes_id)

                axios.post('/set_bisnes/set_article_bisnes_relations', {
                    article_id: parseInt(this.article_id),
                    bisnes_ids,
                })
                .then(res => {
                    this.related_businesses = res.data
                    this.selected_bisnes_id = null
                    this.$bus.$emit('toast', { type: 'success', title: this.$t('admin.articles.business_relation.saved_title'), message: this.$t('admin.articles.business_relation.business_relation_updated_msg') })
                })
                .catch(() => {
                    this.$bus.$emit('toast', { type: 'danger', title: this.$t('admin.articles.business_relation.error_title'), message: this.$t('admin.articles.business_relation.could_not_save_relation') })
                })
                .finally(() => this.saving = false)
            },
            remove_relation(bisnes_id) {
                axios.delete(`/set_bisnes/del_bisnes_article_relation/${this.article_id}/${bisnes_id}`)
                    .then(() => {
                        this.related_businesses = this.related_businesses.filter(business => business.bisnes_id !== bisnes_id)
                        this.$bus.$emit('toast', { type: 'success', title: this.$t('admin.articles.business_relation.removed_title'), message: this.$t('admin.articles.business_relation.business_relation_removed_msg') })
                    })
                    .catch(() => {
                        this.$bus.$emit('toast', { type: 'danger', title: this.$t('admin.articles.business_relation.error_title'), message: this.$t('admin.articles.business_relation.could_not_remove_relation') })
                    })
            }
        }
    }
</script>

<style scoped>
    .business-relation-form {
        margin-top: 24px;
        padding: 16px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: #f8f9fa;
    }

    .form-section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 14px;
        color: #333;
    }

    .current-relations {
        margin-bottom: 14px;
    }

    .current-relation {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
</style>
