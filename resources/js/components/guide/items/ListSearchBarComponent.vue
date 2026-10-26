<template>
    <!-- Search box for the guide list pages (same as the events page): what's
         typed is kept in the URL (?q=) so a search can be shared or survive a
         reload; shows how many items match. The page filters with
         services/guide/listSearch.js. -->
    <div class="cg-list-search" role="search">
        <div class="cg-list-search__box">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input
                type="search"
                class="form-control"
                :value="modelValue"
                :placeholder="placeholder || $t('guide.list_search.placeholder')"
                :aria-label="placeholder || $t('guide.list_search.placeholder')"
                @input="on_input($event.target.value)"
                @keydown.esc="on_input('')"
            />
            <button v-if="modelValue" type="button" class="cg-list-search__clear" :aria-label="$t('guide.list_search.clear')" @click="on_input('')">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
        <span v-if="modelValue.trim() && count !== null" class="cg-list-search__count" aria-live="polite">
            {{ count ? $t('guide.list_search.results', { n: count }) : $t('guide.list_search.none') }}
        </span>
    </div>
</template>

<script>
    export default {
        name: 'ListSearchBar',
        props: {
            modelValue: { type: String, default: '' },
            // matches for the current text (null = don't show)
            count: { type: Number, default: null },
            placeholder: { type: String, default: '' },
        },
        emits: ['update:modelValue'],
        mounted() {
            const q = this.$route.query.q;
            if (typeof q === 'string' && q !== this.modelValue) this.$emit('update:modelValue', q);
        },
        beforeUnmount() { clearTimeout(this.timer); },
        watch: {
            // back / forward between searches
            '$route.query.q'(q) {
                const v = typeof q === 'string' ? q : '';
                if (v !== this.modelValue) this.$emit('update:modelValue', v);
            },
        },
        methods: {
            on_input(v) {
                this.$emit('update:modelValue', v);
                clearTimeout(this.timer);
                this.timer = setTimeout(() => {
                    const query = { ...this.$route.query };
                    if (v.trim()) query.q = v.trim(); else delete query.q;
                    if ((this.$route.query.q || '') !== (query.q || '')) this.$router.replace({ query }).catch(() => {});
                }, 300);
            },
        },
    };
</script>

<style scoped>
    .cg-list-search { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem 1rem; margin: 0 0 1.25rem; }
    .cg-list-search__box { position: relative; flex: 1 1 16rem; max-width: 26rem; }
    .cg-list-search__box > .fa-search { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--cg-muted); pointer-events: none; }
    .cg-list-search__box input { padding-left: 2.4rem; padding-right: 2.4rem; border-radius: 2rem; }
    .cg-list-search__box input::-webkit-search-cancel-button { display: none; }
    .cg-list-search__clear { position: absolute; right: .5rem; top: 50%; transform: translateY(-50%); width: 1.8rem; height: 1.8rem; border: 0; border-radius: 50%; background: var(--cg-chalk); color: var(--cg-stone); }
    .cg-list-search__clear:hover { background: var(--cg-line); color: var(--cg-ink); }
    .cg-list-search__count { color: var(--cg-stone); font-weight: 700; font-size: .9rem; }
    @media (max-width: 575.98px) { .cg-list-search__box { max-width: none; } }
</style>
