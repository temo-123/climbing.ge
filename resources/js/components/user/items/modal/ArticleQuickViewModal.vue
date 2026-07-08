<template>
    <StackModal
        v-model="is_show_modal"
        :title="modalTitle"
        :modal-class="{ 'modal-lg': true }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        :saveButton="{ visible: !!articleUrl, title: $t('admin.articles.quick_view.go_to_article_btn'), btnClass: { 'btn btn-primary': true } }"
        @save="goToArticle"
        @close="close_modal"
    >
        <div v-if="article" class="qv-wrap">

            <!-- Image -->
            <div v-if="article.image" class="qv-image-wrap">
                <img
                    :src="'/public/images/' + article.category + '_img/' + article.image"
                    :alt="localeTitle"
                    class="qv-image"
                    @error="$event.target.style.display='none'"
                />
            </div>

            <!-- Meta badges -->
            <div class="qv-badges">
                <span :class="article.published ? 'badge-published' : 'badge-draft'">
                    {{ article.published ? $t('admin.articles.quick_view.published_label') : $t('admin.articles.quick_view.draft_label') }}
                </span>
                <span class="badge-category">{{ article.category }}</span>
                <span class="badge-id">{{ $t('admin.articles.quick_view.id_prefix') }} {{ article.id }}</span>
            </div>

            <!-- Title -->
            <h4 class="qv-title">{{ localeTitle }}</h4>

            <!-- URL slug -->
            <p class="qv-slug">
                <i class="fa fa-link"></i>
                <span>{{ article.url_title }}</span>
            </p>

            <!-- Short description -->
            <div v-if="localeShortDesc" class="qv-description">
                <strong>{{ $t('admin.articles.quick_view.description_prefix') }}</strong>
                <p>{{ localeShortDesc }}</p>
            </div>

            <!-- Extra fields -->
            <div class="qv-meta-grid">
                <div v-if="article.price_from" class="qv-meta-item">
                    <i class="fa fa-tag"></i>
                    <span>{{ $t('admin.articles.quick_view.price_from_prefix') }} <strong>{{ article.price_from }}</strong></span>
                </div>
                <div v-if="article.open_timen || article.closed_time" class="qv-meta-item">
                    <i class="fa fa-clock-o"></i>
                    <span>
                        {{ article.open_timen || '—' }} – {{ article.closed_time || '—' }}
                    </span>
                </div>
                <div v-if="article.map" class="qv-meta-item">
                    <i class="fa fa-map-marker"></i>
                    <span>{{ article.map }}</span>
                </div>
            </div>

        </div>

        <div v-else class="qv-empty">
            <i class="fa fa-exclamation-circle"></i>
            {{ $t('admin.articles.quick_view.no_article_data') }}
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_show_modal: false,
            article: null,
            category: null,
        }
    },

    computed: {
        localeTitle() {
            return this.article?.global_article_us?.title
                || this.article?.locale_data?.title
                || this.article?.url_title
                || this.$t('admin.export.no_title_fallback')
        },
        localeShortDesc() {
            return this.article?.global_article_us?.short_description
                || this.article?.locale_data?.description
                || null
        },
        modalTitle() {
            return this.localeTitle
        },
        articleUrl() {
            if (!this.article?.url_title) return null
            const base = (process.env.MIX_APP_SSH || '') + (process.env.MIX_GUIDBOOK_URL || '')
            return base + '/' + (this.category || this.article.category) + '/' + this.article.url_title
        },
    },

    methods: {
        show_modal(article, category) {
            this.article = article
            this.category = category || null
            this.is_show_modal = true
        },
        close_modal() {
            this.is_show_modal = false
        },
        goToArticle() {
            if (this.articleUrl) {
                window.open(this.articleUrl, '_blank')
            }
        },
    },
}
</script>

<style scoped>
.qv-wrap {
    padding: 4px 0;
}

.qv-image-wrap {
    text-align: center;
    margin-bottom: 16px;
    background: #f4f4f4;
    border-radius: 8px;
    overflow: hidden;
    max-height: 320px;
}

.qv-image {
    max-width: 100%;
    max-height: 320px;
    object-fit: cover;
    display: block;
    margin: 0 auto;
}

.qv-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.badge-published {
    background: #28a745;
    color: white;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 12px;
}

.badge-draft {
    background: #6c757d;
    color: white;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 12px;
}

.badge-category {
    background: #17a2b8;
    color: white;
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 12px;
    text-transform: capitalize;
}

.badge-id {
    background: #e9ecef;
    color: #495057;
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 12px;
    font-family: monospace;
}

.qv-title {
    font-size: 20px;
    font-weight: 700;
    color: #212529;
    margin: 0 0 8px 0;
}

.qv-slug {
    font-size: 13px;
    color: #6c757d;
    margin: 0 0 14px 0;
    display: flex;
    align-items: center;
    gap: 6px;
    word-break: break-all;
}

.qv-description {
    background: #f8f9fa;
    border-left: 3px solid #dee2e6;
    padding: 10px 14px;
    border-radius: 0 6px 6px 0;
    margin-bottom: 14px;
}

.qv-description p {
    margin: 6px 0 0 0;
    color: #495057;
    font-size: 14px;
    line-height: 1.6;
}

.qv-meta-grid {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.qv-meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #495057;
}

.qv-meta-item i {
    color: #adb5bd;
    width: 16px;
    text-align: center;
}

.qv-empty {
    text-align: center;
    padding: 30px;
    color: #6c757d;
    font-size: 15px;
}

.qv-empty i {
    font-size: 28px;
    color: #dc3545;
    display: block;
    margin-bottom: 10px;
}
</style>
