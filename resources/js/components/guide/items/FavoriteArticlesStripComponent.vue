<template>
    <!-- "My favourites" strip at the top of a guide list page: the logged-in
         climber's favourite articles of that category (get_faworite/
         get_favorite_articles), kept in sync with the hearts on the cards. -->
    <section v-if="items.length" class="cg-favs d-print-none" :aria-label="$t('guide.favorite.my_favorites')">
        <h2 class="cg-favs__title"><i class="fa fa-heart" aria-hidden="true"></i> {{ $t('guide.favorite.my_favorites') }} <small>{{ items.length }}</small></h2>
        <ul class="cg-favs__list">
            <li v-for="a in items" :key="a.global_data.id">
                <router-link :to="link(a)" class="cg-favs__card">
                    <span class="cg-favs__img" :style="a.global_data.image ? { backgroundImage: 'url(' + img(a) + ')' } : null">
                        <i v-if="!a.global_data.image" class="fa fa-picture-o" aria-hidden="true"></i>
                    </span>
                    <strong>{{ a.locale_data.title }}</strong>
                </router-link>
                <favoriteHeart :article_id="a.global_data.id" />
            </li>
        </ul>
    </section>
</template>

<script>
    import favoriteHeart from './FavoriteHeartComponent.vue'
    import { favorites, load_favorites } from '../../../services/guide/favorites.js'

    // category → [list/page path, image folder]
    const PATHS = {
        outdoor: ['/outdoor/', '/public/images/outdoor_img/'],
        ice: ['/ice/', '/public/images/ice_img/'],
        indoor: ['/indoor/', '/public/images/indoor_img/'],
        mount_route: ['/mountaineering/', '/public/images/mount_route_img/'],
        other: ['/other/', '/public/images/other_img/'],
        tech_tip: ['/tech_tip/', '/public/images/tech_tip_img/'],
    };

    // one request per page load, shared by every strip
    let cache = null;

    export default {
        name: 'FavoriteArticlesStrip',
        components: { favoriteHeart },
        props: {
            category: { type: String, required: true },
        },
        data() {
            return { all: [], favorites };
        },
        computed: {
            // drop the ones un-hearted on this page without refetching
            items() {
                return this.all.filter(a => a.global_data && a.global_data.category === this.category && this.favorites.ids.has(Number(a.global_data.id)));
            },
        },
        mounted() {
            if (!localStorage.getItem('auth_token')) return;
            load_favorites();
            const lang = localStorage.getItem('lang') === 'ka' ? 'ka' : 'us';
            cache = cache || axios.get('/get_faworite/get_favorite_articles', { params: { lang } }).then(r => r.data || []).catch(() => []);
            cache.then(list => { this.all = list; });
        },
        methods: {
            link(a) {
                const loc = this.$route.params.locale;
                return (loc ? '/' + loc : '') + (PATHS[a.global_data.category] || PATHS.outdoor)[0] + a.global_data.url_title;
            },
            img(a) { return (PATHS[a.global_data.category] || PATHS.outdoor)[1] + encodeURIComponent(a.global_data.image); },
        },
    };
</script>

<style scoped>
    .cg-favs { margin: 0 0 1.5rem; }
    .cg-favs__title { margin: 0 0 .5rem !important; font-size: 1.15rem !important; color: var(--cg-ink); }
    .cg-favs__title .fa { color: var(--cg-rope, #e0632a); }
    .cg-favs__title small { margin-left: .3rem; color: var(--cg-muted); font-size: .8rem; }
    .cg-favs__list { display: flex; gap: .75rem; margin: 0; padding: .15rem .15rem .6rem; list-style: none; overflow-x: auto; scroll-snap-type: x proximity; }
    .cg-favs__list li { position: relative; flex: 0 0 15.5rem; scroll-snap-align: start; }
    .cg-favs__card { display: flex; align-items: center; gap: .7rem; height: 100%; padding: .5rem 3rem .5rem .5rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r); background: #fff; color: var(--cg-ink); text-decoration: none; transition: border-color .15s; }
    .cg-favs__card:hover { border-color: var(--cg-accent); }
    .cg-favs__card strong { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; font-size: .92rem; line-height: 1.25; }
    .cg-favs__img { flex: 0 0 3.6rem; height: 3.6rem; display: flex; align-items: center; justify-content: center; border-radius: calc(var(--cg-r) - 2px); background: var(--cg-chalk-2) center / cover no-repeat; color: var(--cg-muted); }
    .cg-favs__list :deep(.cg-heart--card) { top: 50%; right: .5rem; transform: translateY(-50%); width: 2.1rem; height: 2.1rem; box-shadow: none; background: var(--cg-chalk); }
</style>
