<template>
    <li class="nav-search-li" :class="{ open: is_open }" ref="root">
        <!-- Search icon toggle -->
        <a class="margin_right cursor_pointer" @click.stop="toggle">
            <span><i class="fa fa-search" aria-hidden="true"></i></span>
        </a>

        <!-- Search panel -->
        <div v-show="is_open" class="search-panel shadows" @click.stop>
            <!-- Full-width input -->
            <input
                ref="input"
                class="form-control search-input"
                v-model="query"
                @input="on_input"
                @keyup.enter.prevent="submit"
                @keydown.escape="close"
                :placeholder="placeholder"
                autocomplete="off"
                type="text"
            />

            <!-- Language error -->
            <div v-if="lang_error" class="search-lang-error">
                <i class="fa fa-exclamation-triangle"></i>
                {{ $t(site + '.search.lang_error') }}
            </div>

            <!-- Live suggestions -->
            <ul v-if="suggestions.length && !lang_error" class="search-suggestions-list">
                <li
                    v-for="(s, i) in suggestions"
                    :key="i"
                    class="suggestion-item"
                    @mousedown.prevent="use_suggestion(s.text)"
                >
                    <i :class="type_icon(s.type)" aria-hidden="true"></i>
                    <span>{{ s.text }}</span>
                </li>
            </ul>

            <!-- Loading indicator -->
            <div v-if="loading" class="suggestion-loading">
                <i class="fa fa-spinner fa-spin"></i>
            </div>

            <!-- Nothing found -->
            <div v-if="fetched && !loading && !lang_error && suggestions.length === 0 && query.trim().length >= 2" class="suggestion-empty">
                <i class="fa fa-frown-o"></i> {{ $t(site + '.search.no_results') }}
            </div>

            <!-- Full-width search button -->
            <button
                class="btn btn-success search-submit-btn"
                @click.prevent="submit"
                :disabled="query.trim().length < 2"
            >
                <span class="glyphicon glyphicon-search"></span>
                {{ $t(site + '.search.placeholder') || 'Search' }}
            </button>
        </div>
    </li>
</template>

<script>
export default {
    name: 'NavbarSearch',

    props: {
        site:         { type: String, required: true },
        search_route: { type: String, required: true },
    },

    data() {
        return {
            query:     '',
            suggestions: [],
            loading:   false,
            fetched:   false,
            is_open:   false,
            lang_error: false,
            timer:     null,
        }
    },

    computed: {
        locale() {
            return this.$i18n.locale === 'ka' ? 'ka' : 'us'
        },
        placeholder() {
            const map = {
                guide:  this.$t('guide.search.placeholder'),
                shop:   this.$t('shop.search.placeholder'),
                blog:   this.$t('blog.search.placeholder'),
                summit: this.$t('summit.search.placeholder'),
            }
            return map[this.site] || '...'
        },
    },

    mounted() {
        document.addEventListener('click', this.on_outside_click)
    },

    beforeUnmount() {
        document.removeEventListener('click', this.on_outside_click)
        clearTimeout(this.timer)
    },

    methods: {
        toggle() {
            this.is_open = !this.is_open
            if (this.is_open) {
                this.$nextTick(() => this.$refs.input?.focus())
            }
        },

        close() {
            this.is_open    = false
            this.suggestions = []
        },

        on_outside_click(e) {
            if (this.$refs.root && !this.$refs.root.contains(e.target)) {
                this.close()
            }
        },

        is_valid_lang(text) {
            // Only English (A-Za-z), Georgian (U+10D0–U+10FF), digits, spaces, basic punctuation
            return /^[A-Za-zა-ჿ0-9\s\-.,!?'"()]+$/.test(text.trim())
        },

        on_input() {
            clearTimeout(this.timer)
            this.suggestions = []
            this.fetched     = false
            const q = this.query.trim()
            if (q.length < 1) {
                this.loading    = false
                this.lang_error = false
                return
            }
            if (!this.is_valid_lang(q)) {
                this.lang_error = true
                this.loading    = false
                return
            }
            this.lang_error = false
            if (q.length < 2) {
                this.loading = false
                return
            }
            this.loading = true
            this.timer = setTimeout(() => this.fetch_suggestions(), 350)
        },

        fetch_suggestions() {
            axios.post('/search/suggest', {
                query:  this.query,
                locale: this.locale,
                site:   this.site,
            }).then(res => {
                this.suggestions = res.data
            }).catch(() => {
                this.suggestions = []
            }).finally(() => {
                this.loading = false
                this.fetched = true
            })
        },

        submit() {
            const q = this.query.trim()
            if (q.length < 2) return
            if (!this.is_valid_lang(q)) {
                this.lang_error = true
                return
            }
            this.$router.push({ name: this.search_route, query: { query: q } })
            this.query       = ''
            this.suggestions = []
            this.is_open     = false
        },

        use_suggestion(text) {
            this.query       = text
            this.suggestions = []
            this.submit()
        },

        type_icon(type) {
            const map = {
                article:     'fa fa-map-marker',
                sector:      'fa fa-th-large',
                route:       'fa fa-road',
                product:     'fa fa-tag',
                tour:        'fa fa-compass',
                service:     'fa fa-wrench',
                post:        'fa fa-file-text-o',
                news:        'fa fa-newspaper-o',
                summit:      'fa fa-flag',
                mount_route: 'fa fa-map',
            }
            return (map[type] || 'fa fa-search') + ' suggestion-icon'
        },
    },
}
</script>

<style scoped>
.nav-search-li {
    position: relative;
    list-style: none;
}

.search-panel {
    position: absolute;
    right: 0;
    top: 100%;
    width: 340px;
    background: #dfdfdf;
    border-radius: 0 0 20px 20px;
    padding: 12px;
    z-index: 9999;
}

.search-input {
    width: 100%;
    box-sizing: border-box;
}

.search-submit-btn {
    display: block;
    width: 100%;
    margin-top: 8px;
}

.search-suggestions-list {
    list-style: none;
    margin: 8px 0 0;
    padding: 0;
    border-top: 1px solid #bbb;
}

.suggestion-item {
    padding: 7px 10px;
    cursor: pointer;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.93em;
    color: #333;
    transition: background 0.15s;
}

.suggestion-item:hover {
    background: #c8e6ed;
}

.suggestion-icon {
    color: #278da5;
    width: 14px;
    text-align: center;
}

.suggestion-loading {
    text-align: center;
    padding: 6px;
    color: #888;
    font-size: 0.9em;
}

.suggestion-empty {
    margin-top: 8px;
    padding: 6px 10px;
    color: #666;
    font-size: 0.88em;
    text-align: center;
}

.search-lang-error {
    margin-top: 8px;
    padding: 6px 10px;
    background: #f8d7da;
    color: #721c24;
    border-radius: 4px;
    font-size: 0.88em;
}
</style>
