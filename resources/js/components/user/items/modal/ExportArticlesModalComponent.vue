<template>
    <stack-modal
        :show="showModal"
        title="Export Articles to PDF"
        @close="closeModal"
        :modal-class="{ 'export-modal': true }"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <div class="container">
            <div v-if="loading" class="text-center">
                <p>Loading...</p>
            </div>
            <div v-else>
                <div class="mb-3">
                    <label for="categorySelect" class="form-label">Select Category</label>
                    <select
                        id="categorySelect"
                        class="form-select"
                        v-model="selectedCategory"
                        @change="loadArticlesByCategory"
                    >
                        <option value="">All Categories</option>
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
                            Export Sectors and Routes (with images and MTPs)
                        </label>
                        <small class="form-text text-muted">
                            Includes sector information, routes table, and MTPs if available
                        </small>
                    </div>
                </div>
                <div v-else-if="selectedCategory && selectedCategory !== 'outdoor' && selectedCategory !== 'ice'" class="mb-3">
                    <small class="text-muted">
                        Sectors export is only available for outdoor and ice categories
                    </small>
                </div>
                <div class="mb-3">
                    <label for="localeSelect" class="form-label">Select Language</label>
                    <select
                        id="localeSelect"
                        class="form-select"
                        v-model="selectedLocale"
                    >
                        <option value="">Select export language</option>
                        <option value="ka">Georgian (ქართული)</option>
                        <option value="us">English</option>
                        <!-- <option value="ru">Русский</option> -->
                        <option value="all">All Languages</option>
                    </select>
                </div>
                <div v-if="articles.length === 0" class="text-center">
                    <p>No articles available for export.</p>
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
                                Select All Articles
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
                                    URL Title: <strong>{{ article.url_title || 'No Title' }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Category: {{ article.category || 'N/A' }} |
                                        Published: {{ article.published ? 'Yes' : 'No' }}
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="closeModal"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    :disabled="selectedArticles.length === 0 || !selectedLocale"
                    @click="exportSelectedArticles"
                >
                    Export Selected Articles ({{ selectedArticles.length }})
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'

export default {
    name: 'ExportArticlesModal',
    components: {
        StackModal,
    },
    props: {
        showModal: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
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
            if (newVal) {
                this.loadCategories()
            }
        }
    },
    methods: {
        closeModal() {
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
                alert('Please select a category.')
                return
            }
            if (!this.selectedLocale) {
                alert('Please select a language.')
                return
            }
            if (this.selectedArticles.length === 0) {
                alert('Please select at least one article to export.')
                return
            }

            const payload = {
                article_ids: this.selectedArticles,
                export_sectors: this.exportSectors,
                locale: this.selectedLocale
            }

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
                    this.closeModal()
                })
                .catch(error => {
                    console.log(error)
                    alert('An error occurred while exporting the articles.')
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
