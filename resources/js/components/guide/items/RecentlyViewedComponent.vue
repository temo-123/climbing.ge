<template>
    <!-- "Recently viewed" strip: the guidebook pages this browser opened last
         (services/guide/recentlyViewed.js). Optional `category` limits it to
         one list (outdoor, ice, indoor, mount_route). -->
    <section v-if="items.length" class="cg-recent d-print-none" :aria-label="$t('guide.recently_viewed.title')">
        <div class="cg-recent__head">
            <h2 class="cg-recent__title"><i class="fa fa-history" aria-hidden="true"></i> {{ $t('guide.recently_viewed.title') }}</h2>
            <button type="button" class="btn btn-link btn-sm p-0" @click="clear">{{ $t('guide.recently_viewed.clear') }}</button>
        </div>
        <ul class="cg-recent__list">
            <li v-for="item in items" :key="item.category + item.url_title">
                <router-link :to="link(item)" class="cg-recent__card">
                    <span class="cg-recent__img" :style="item.image ? { backgroundImage: 'url(' + img(item) + ')' } : null">
                        <i v-if="!item.image" class="fa fa-picture-o" aria-hidden="true"></i>
                    </span>
                    <span class="cg-recent__text">
                        <strong>{{ item.title }}</strong>
                        <small>{{ $t('guide.recently_viewed.categories.' + item.category) }} · {{ $timeAgo(new Date(item.at).toISOString()) }}</small>
                    </span>
                </router-link>
            </li>
        </ul>
    </section>
</template>

<script>
    import { read_viewed, forget_viewed, CATEGORY_PATHS } from '../../../services/guide/recentlyViewed.js'

    export default {
        name: 'RecentlyViewed',
        props: {
            category: { type: String, default: null },
            limit: { type: Number, default: 6 },
        },
        data() {
            return { all: read_viewed() };
        },
        computed: {
            items() {
                return this.all
                    .filter(i => CATEGORY_PATHS[i.category] && (!this.category || i.category === this.category))
                    .slice(0, this.limit);
            },
        },
        methods: {
            link(item) {
                const loc = this.$route && this.$route.params.locale;
                const prefix = loc ? '/' + loc : '';
                return prefix + CATEGORY_PATHS[item.category][0] + item.url_title;
            },
            img(item) { return CATEGORY_PATHS[item.category][1] + encodeURIComponent(item.image); },
            clear() { forget_viewed(); this.all = []; },
        },
    };
</script>

<style scoped>
    .cg-recent { margin: 0 0 1.5rem; }
    .cg-recent__head { display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; margin-bottom: .5rem; }
    .cg-recent__title { margin: 0 !important; font-size: 1.15rem !important; color: var(--cg-ink); }
    .cg-recent__title .fa { color: var(--cg-accent); }
    .cg-recent__list {
        display: flex;
        gap: .75rem;
        margin: 0;
        padding: .15rem .15rem .6rem;
        list-style: none;
        overflow-x: auto;
        scroll-snap-type: x proximity;
        scrollbar-width: thin;
    }
    .cg-recent__list li { flex: 0 0 15.5rem; scroll-snap-align: start; }
    .cg-recent__card {
        display: flex;
        align-items: center;
        gap: .7rem;
        height: 100%;
        padding: .5rem;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r);
        background: #fff;
        color: var(--cg-ink);
        text-decoration: none;
        transition: border-color .15s, transform .15s;
    }
    .cg-recent__card:hover { border-color: var(--cg-accent); transform: translateY(-2px); }
    .cg-recent__card:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .45); outline-offset: 2px; }
    .cg-recent__img {
        flex: 0 0 3.6rem;
        height: 3.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: calc(var(--cg-r) - 2px);
        background: var(--cg-chalk-2) center / cover no-repeat;
        color: var(--cg-muted);
    }
    .cg-recent__text { display: flex; flex-direction: column; min-width: 0; line-height: 1.25; }
    .cg-recent__text strong { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; font-size: .92rem; }
    .cg-recent__text small { margin-top: .2rem; color: var(--cg-muted); font-weight: 600; font-size: .75rem; }
    @media (prefers-reduced-motion: reduce) { .cg-recent__card { transition: none; } .cg-recent__card:hover { transform: none; } }
</style>
