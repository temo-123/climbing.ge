<template>
    <div class="summit-relation-form">
        <h4 class="form-section-title">
            <i class="fa fa-mountain"></i> {{ $t('admin.articles.summit_relation.title') }}
        </h4>

        <div v-if="loading" class="text-center p-3">
            <i class="fa fa-spinner fa-spin"></i> {{ $t('admin.export.loading_ellipsis') }}
        </div>

        <div v-else>
            <!-- Current relation -->
            <div v-if="current_relation" class="current-relation alert alert-info">
                <strong>{{ $t('admin.articles.summit_relation.linked_summit_prefix') }}</strong> {{ current_relation.summit_title }}
                <button class="btn btn-xs btn-danger pull-right" @click="remove_relation">
                    <i class="fa fa-times"></i> {{ $t('admin.single_image.remove_btn') }}
                </button>
            </div>
            <div v-else class="alert alert-warning">
                {{ $t('admin.articles.summit_relation.no_summit_linked') }}
            </div>

            <!-- Select summit -->
            <div class="form-group">
                <label>{{ current_relation ? $t('admin.articles.summit_relation.change_summit_label') : $t('admin.articles.summit_relation.link_to_summit_label') }}</label>
                <select class="form-control" v-model="selected_summit_id">
                    <option :value="null">{{ $t('admin.articles.summit_relation.select_summit_placeholder') }}</option>
                    <option
                        v-for="summit in summits"
                        :key="summit.id"
                        :value="summit.id"
                    >
                        {{ summit.title || summit.ka_title }}
                    </option>
                </select>
            </div>

            <button
                class="btn btn-primary"
                :disabled="!selected_summit_id || saving"
                @click="save_relation"
            >
                <i class="fa fa-link"></i>
                {{ saving ? $t('admin.articles.summit_relation.saving_ellipsis') : (current_relation ? $t('admin.articles.summit_relation.update_relation_btn') : $t('admin.articles.summit_relation.link_summit_btn')) }}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SummitRelationFormComponent',
        props: {
            article_id: {
                type: [String, Number],
                required: true
            }
        },
        data() {
            return {
                summits: [],
                current_relation: null,
                selected_summit_id: null,
                loading: true,
                saving: false,
            }
        },
        mounted() {
            Promise.all([this.fetch_summits(), this.fetch_current_relation()])
                .finally(() => this.loading = false)
        },
        methods: {
            fetch_summits() {
                return axios.get('/get_summit_admin/get_summits_list')
                    .then(res => { this.summits = res.data })
                    .catch(() => {})
            },
            fetch_current_relation() {
                return axios.get('/get_summit_admin/get_article_summit_relation/' + this.article_id)
                    .then(res => {
                        this.current_relation = res.data
                        if (res.data) {
                            this.selected_summit_id = res.data.summit_id
                        }
                    })
                    .catch(() => {})
            },
            save_relation() {
                if (!this.selected_summit_id) return
                this.saving = true
                axios.post('/set_summit/add_mount_route_relation', {
                    summit_id: this.selected_summit_id,
                    article_id: parseInt(this.article_id),
                })
                .then(res => {
                    this.current_relation = {
                        relation_id: res.data.id,
                        summit_id: this.selected_summit_id,
                        summit_title: this.summits.find(s => s.id === this.selected_summit_id)?.title
                            || this.summits.find(s => s.id === this.selected_summit_id)?.ka_title,
                    }
                    this.$bus.$emit('toast', { type: 'success', title: this.$t('admin.articles.summit_relation.saved_title'), message: this.$t('admin.articles.summit_relation.summit_relation_updated_msg') })
                })
                .catch(() => {
                    this.$bus.$emit('toast', { type: 'danger', title: this.$t('admin.articles.summit_relation.error_title'), message: this.$t('admin.articles.summit_relation.could_not_save_relation') })
                })
                .finally(() => this.saving = false)
            },
            remove_relation() {
                if (!this.current_relation) return
                axios.delete('/set_summit/remove_mount_route_relation/' + this.current_relation.relation_id)
                    .then(() => {
                        this.current_relation = null
                        this.selected_summit_id = null
                        this.$bus.$emit('toast', { type: 'success', title: this.$t('admin.articles.summit_relation.removed_title'), message: this.$t('admin.articles.summit_relation.summit_relation_removed_msg') })
                    })
                    .catch(() => {
                        this.$bus.$emit('toast', { type: 'danger', title: this.$t('admin.articles.summit_relation.error_title'), message: this.$t('admin.articles.summit_relation.could_not_remove_relation') })
                    })
            }
        }
    }
</script>

<style scoped>
    .summit-relation-form {
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

    .current-relation {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
