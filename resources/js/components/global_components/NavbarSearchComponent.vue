<template>
    <li class="nav-search-li" :class="{ open: is_open }" ref="root">
        <!-- Search icon toggle -->
        <a class="cg-icon-btn" @click.stop="toggle" :title="$t('global.search') + ' (Ctrl+K)'" role="button" tabindex="0" @keydown.enter.prevent="toggle" :aria-expanded="is_open">
            <i class="fa fa-search" aria-hidden="true"></i>
        </a>

        <!-- Search panel -->
        <div v-show="is_open" class="search-panel" @click.stop>
            <div class="search-field">
                <i class="fa fa-search search-field-icon" aria-hidden="true"></i>
                <!-- Full-width input -->
                <input
                    ref="input"
                    class="form-control search-input"
                    v-model="query"
                    @input="on_input"
                    @keydown.enter.prevent="on_enter"
                    @keydown.down.prevent="move(1)"
                    @keydown.up.prevent="move(-1)"
                    @keydown.escape="close"
                    role="combobox"
                    :aria-expanded="suggestions.length > 0"
                    aria-autocomplete="list"
                    :aria-activedescendant="active >= 0 ? 'cg-sugg-' + active : null"
                    :placeholder="placeholder"
                    autocomplete="off"
                    type="text"
                />
            </div>

            <!-- Language error -->
            <div v-if="lang_error" class="search-lang-error">
                <i class="fa fa-exclamation-triangle"></i>
                {{ $t(site + '.search.lang_error') }}
            </div>

            <!-- Recent searches (empty field) -->
            <div v-if="!query.trim() && recent.length" class="search-recent">
                <small class="search-recent-title">{{ $t('global.search_recent') }}</small>
                <button v-for="r in recent" :key="r" type="button" class="search-recent-chip" @mousedown.prevent="use_suggestion(r)">
                    <i class="fa fa-history" aria-hidden="true"></i> {{ r }}
                </button>
            </div>

            <!-- Live suggestions -->
            <ul v-if="suggestions.length && !lang_error" class="search-suggestions-list" role="listbox">
                <li
                    v-for="(s, i) in suggestions"
                    :key="i"
                    :id="'cg-sugg-' + i"
                    role="option"
                    :aria-selected="i === active"
                    class="suggestion-item"
                    :class="{ 'is-active': i === active }"
                    @mouseenter="active = i"
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
                class="btn btn-primary search-submit-btn"
                @click.prevent="submit"
                :disabled="query.trim().length < 2"
            >
                <i class="fa fa-search" aria-hidden="true"></i>
                {{ $t(site + '.search.placeholder') || 'Search' }}
            </button>
            <div class="search-kbd-hint d-none d-md-block" aria-hidden="true">
                <kbd>↑</kbd><kbd>↓</kbd> {{ $t('global.search_kbd_move') }} · <kbd>Enter</kbd> {{ $t('global.search_kbd_open') }} · <kbd>Esc</kbd> {{ $t('global.close') }} · <kbd>Ctrl</kbd>+<kbd>K</kbd>
            </div>
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
            active:    -1,
            recent:    [],
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
        document.addEventListener('keydown', this.on_shortcut)
    },

    beforeUnmount() {
        document.removeEventListener('click', this.on_outside_click)
        document.removeEventListener('keydown', this.on_shortcut)
        clearTimeout(this.timer)
    },

    methods: {
        toggle() {
            this.is_open = !this.is_open
            if (this.is_open) {
                this.recent = this.read_recent()
                this.$nextTick(() => this.$refs.input?.focus())
            }
        },

        // Ctrl/Cmd+K anywhere, or "/" when not typing, opens the search
        on_shortcut(e) {
            const typing = /^(INPUT|TEXTAREA|SELECT)$/.test(e.target.tagName) || e.target.isContentEditable
            const ctrl_k = (e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')
            if (ctrl_k || (e.key === '/' && !typing)) {
                // only the visible copy (desktop vs. mobile navbar) reacts
                if (!this.$refs.root || !this.$refs.root.offsetParent) return
                e.preventDefault()
                if (!this.is_open) this.toggle()
                else this.$refs.input?.focus()
            }
        },

        move(step) {
            if (!this.suggestions.length) return
            const n = this.suggestions.length
            this.active = this.active < 0 ? (step > 0 ? 0 : n - 1) : (this.active + step + n) % n
        },

        on_enter() {
            if (this.active >= 0 && this.suggestions[this.active]) this.use_suggestion(this.suggestions[this.active].text)
            else this.submit()
        },

        read_recent() {
            try { return JSON.parse(localStorage.getItem('cg_recent_searches_' + this.site) || '[]').slice(0, 5) } catch (e) { return [] }
        },

        save_recent(q) {
            const list = [q].concat(this.read_recent().filter(r => r.toLowerCase() !== q.toLowerCase())).slice(0, 5)
            try { localStorage.setItem('cg_recent_searches_' + this.site, JSON.stringify(list)) } catch (e) {}
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
            this.active      = -1
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
            this.save_recent(q)
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
    top: calc(100% + .55rem);
    z-index: 1050;
    width: 360px;
    padding: .75rem;
    background: #fff;
    border: 1px solid var(--cg-line);
    border-radius: 1rem;
    box-shadow: var(--cg-shadow-lg);
}

.search-field {
    position: relative;
}
.search-field-icon {
    position: absolute;
    left: .85rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--cg-muted);
    pointer-events: none;
}
.search-input {
    width: 100%;
    padding-left: 2.3rem;
    border-radius: 2rem;
    background: var(--cg-chalk);
}

.search-submit-btn {
    width: 100%;
    margin-top: .6rem;
    border-radius: 2rem;
}

.search-suggestions-list {
    list-style: none;
    margin: .5rem 0 0;
    padding: .25rem 0 0;
    border-top: 1px solid var(--cg-line);
}

.suggestion-item.is-active { background: var(--cg-accent-soft); color: var(--cg-accent-dark); }
.search-recent { display: flex; flex-wrap: wrap; gap: .35rem; margin: .6rem 0 .2rem; }
.search-recent-title { width: 100%; color: var(--cg-muted); font-weight: 800; font-size: .72rem; letter-spacing: .08em; text-transform: uppercase; }
.search-recent-chip { border: 1px solid var(--cg-line); background: var(--cg-chalk); border-radius: 2rem; padding: .2rem .65rem; font-size: .82rem; font-weight: 700; color: var(--cg-ink-2); }
.search-recent-chip:hover { border-color: var(--cg-accent); color: var(--cg-accent-dark); }
.search-recent-chip .fa { color: var(--cg-muted); }
.search-kbd-hint { margin-top: .55rem; color: var(--cg-muted); font-size: .72rem; font-weight: 600; text-align: center; }
.search-kbd-hint kbd { padding: .05rem .3rem; border: 1px solid var(--cg-line); border-bottom-width: 2px; border-radius: .3rem; background: var(--cg-chalk); color: var(--cg-ink-2); font-size: .68rem; font-family: inherit; }
.suggestion-item {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .5rem .65rem;
    border-radius: .6rem;
    color: var(--cg-ink-2);
    font-size: .92rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color .15s ease;
}
.suggestion-item i {
    width: 16px;
    text-align: center;
    color: var(--cg-accent);
}
.suggestion-item:hover {
    background: var(--cg-accent-soft);
    color: var(--cg-accent-dark);
}

.suggestion-loading {
    padding: .5rem;
    text-align: center;
    color: var(--cg-muted);
}

.suggestion-empty {
    margin-top: .5rem;
    padding: .5rem;
    text-align: center;
    color: var(--cg-muted);
    font-size: .88rem;
}

.search-lang-error {
    margin-top: .5rem;
    padding: .5rem .75rem;
    border-radius: .6rem;
    background: #fdecea;
    color: #a52a1f;
    font-size: .86rem;
    font-weight: 600;
}

@media (max-width: 1199.98px) {
    .search-panel {
        left: 0;
        right: 0;
        width: auto;
    }
}
</style>
