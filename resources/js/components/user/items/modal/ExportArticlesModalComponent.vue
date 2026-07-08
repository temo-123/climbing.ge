<template>
    <StackModal
        :show="is_show_modal"
        :title="$t('admin.export.export_articles_pdf_title')"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
        @close="close_modal"
    >
        <div class="container">
            <div v-if="loading" class="text-center">
                <p>{{ $t('admin.export.loading_ellipsis') }}</p>
            </div>
            <div v-else>
                <div class="mb-3">
                    <label for="categorySelect" class="form-label">{{ $t('admin.export.select_category_label') }}</label>
                    <select
                        id="categorySelect"
                        class="form-select"
                        v-model="selectedCategory"
                        @change="loadArticlesByCategory"
                    >
                        <option value="">{{ $t('admin.export.all_categories_option') }}</option>
                        <option v-for="category in categories" :key="category" :value="category">
                            {{ category }}
                        </option>
                    </select>
                </div>

                <div v-if="selectedCategory && (selectedCategory === 'outdoor' || selectedCategory === 'ice')" class="mb-3">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="exportSectors"
                            v-model="exportSectors"
                        />
                        <label class="form-check-label" for="exportSectors">
                            {{ $t('admin.export.export_sectors_routes_label') }}
                        </label>
                        <small class="form-text text-muted">
                            {{ $t('admin.export.export_sectors_hint') }}
                        </small>
                    </div>
                </div>
                <div v-else-if="selectedCategory && selectedCategory !== 'outdoor' && selectedCategory !== 'ice'" class="mb-3">
                    <small class="text-muted">
                        {{ $t('admin.export.sectors_export_unavailable_hint') }}
                    </small>
                </div>
                <div class="mb-3">
                    <label for="localeSelect" class="form-label">{{ $t('admin.export.select_language_label') }}</label>
                    <select
                        id="localeSelect"
                        class="form-select"
                        v-model="selectedLocale"
                    >
                        <option value="">{{ $t('admin.export.select_export_language_placeholder') }}</option>
                        <option value="ka">{{ $t('admin.export.georgian_option') }}</option>
                        <option value="us">{{ $t('admin.export.english_option') }}</option>
                        <!-- <option value="ru">Русский</option> -->
                        <option value="all">{{ $t('admin.export.all_languages_option') }}</option>
                    </select>
                </div>
                <div v-if="articles.length === 0" class="text-center">
                    <p>{{ $t('admin.export.no_articles_available') }}</p>
                </div>
                <div v-else>
                    <div class="mb-3">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="selectAll"
                                @change="selectAllArticles"
                                v-model="selectAll"
                            />
                            <label class="form-check-label" for="selectAll">
                                {{ $t('admin.export.select_all_articles') }}
                            </label>
                        </div>
                    </div>
                    <div class="article-list" style="max-height: 400px; overflow-y: auto;">
                        <div v-for="article in articles" :key="article.id" class="article-item mb-2">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    :id="'article-' + article.id"
                                    v-model="selectedArticles"
                                    :value="article.id"
                                />
                                <label class="form-check-label" :for="'article-' + article.id">
                                    {{ $t('admin.export.url_title_prefix') }} <strong>{{ article.url_title || $t('admin.export.no_title_fallback') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $t('admin.export.category_prefix') }} {{ article.category || $t('admin.export.na_fallback') }} |
                                        {{ $t('admin.export.published_prefix') }} {{ article.published ? $t('admin.export.yes_label') : $t('admin.site_info.no_label') }}
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template #footer>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="close_modal"
                >
                    {{ $t('admin.comments.cancel_btn') }}
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    :disabled="selectedArticles.length === 0 || !selectedLocale"
                    @click="exportSelectedArticles"
                >
                    {{ $t('admin.export.export_selected_articles_btn', { count: selectedArticles.length }) }}
                </button>
            </div>
        </template>
    </StackModal>
</template>

<script>
// import StackModal from '@innologica/vue-stackable-modal'  // Global now

export default {
    name: 'ExportArticlesModal',
    props: {
        showModal: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            is_show_modal: false,
            categories: [],
            articles: [],
            selectedArticles: [],
            selectAll: false,
            loading: true,
            selectedCategory: '',
            exportSectors: false,
            selectedLocale: '',
        }
    },
    watch: {
        showModal(newVal) {
            this.is_show_modal = newVal
            if (newVal) {
                this.loadCategories()
            }
        }
    },
    methods: {
        close_modal() {
            this.$emit('close')
            this.resetModal()
        },
        resetModal() {
            this.categories = []
            this.articles = []
            this.selectedArticles = []
            this.selectAll = false
            this.loading = true
            this.selectedCategory = ''
            this.exportSectors = false
            this.selectedLocale = 'ka'
        },
        loadCategories() {
            this.loading = true
            axios
            .get("/set_export/categories")
            .then(response => {
                this.categories = response.data
                this.loading = false
            })
            .catch(
                error => console.log(error)
            )
        },
        loadArticlesByCategory() {
            if (!this.selectedCategory) {
                this.articles = []
                return
            }
            this.loading = true
            axios
            .get(`/set_export/articles/${this.selectedCategory}`)
            .then(response => {
                this.articles = response.data
                this.loading = false
            })
            .catch(
                error => console.log(error)
            )
        },
        selectAllArticles() {
            if (this.selectAll) {
                this.selectedArticles = this.articles.map(article => article.id)
            } else {
                this.selectedArticles = []
            }
        },
        exportSelectedArticles() {
            if (!this.selectedCategory) {
                alert(this.$t('admin.export.please_select_category'))
                return
            }
            if (!this.selectedLocale) {
                alert(this.$t('admin.export.please_select_language'))
                return
            }
            if (this.selectedArticles.length === 0) {
                alert(this.$t('admin.export.please_select_article'))
                return
            }

            const payload = {
                article_ids: this.selectedArticles,
                export_sectors: this.exportSectors,
                locale: this.selectedLocale
            }

            this.loading = true

            axios
                .post('/set_export/articles_pdf', payload, {
                    responseType: 'blob'
                })
                .then(response => {
                    const contentDisposition = response.headers['content-disposition'];
                    const filename = contentDisposition ? contentDisposition.split('filename=')[1].replace(/"/g, '') : 'selected_articles.pdf';
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', filename)
                    document.body.appendChild(link)
                    link.click()
                    document.body.removeChild(link)
                    this.close_modal()
                })
                .catch(error => {
                    console.log(error)
                    alert(this.$t('admin.export.export_error'))
                })
                .finally(() => {
                    this.loading = false
                })
        },
    }
}
</script>

<style scoped>
.article-list {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
}

.article-item {
    border-bottom: 1px solid #f8f9fa;
    padding-bottom: 0.5rem;
}

.article-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.form-check-label {
    cursor: pointer;
    width: 100%;
}

.form-check-label small {
    font-size: 0.875rem;
}
</style>
