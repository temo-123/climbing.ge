<template>
    <!-- Favourite toggle for any guide article (services/guide/favorites.js).
         Used on article pages and on list cards; on cards it stops the click
         so the card link doesn't open. Removing offers Undo in a toast. -->
    <button
        v-if="allowed"
        type="button"
        class="cg-heart"
        :class="[{ 'is-on': on, 'is-busy': busy }, 'cg-heart--' + variant]"
        :aria-pressed="on"
        :aria-label="on ? $t('guide.favorite.remove_label') : $t('guide.favorite.add_label')"
        :title="on ? $t('guide.favorite.remove_label') : $t('guide.favorite.add_label')"
        @click.stop.prevent="toggle"
    >
        <i class="fa" :class="on ? 'fa-heart' : 'fa-heart-o'" aria-hidden="true"></i>
    </button>
</template>

<script>
    import { favorites, load_favorites, is_favorite, toggle_favorite, FAVORITE_CATEGORIES } from '../../../services/guide/favorites.js'

    export default {
        name: 'FavoriteHeart',
        props: {
            article_id: { type: [Number, String], required: true },
            // 'card' = round button over a photo, 'inline' = next to a page title
            variant: { type: String, default: 'card' },
            // optional: hide the heart for categories that can't be favourites (news, special…)
            category: { type: String, default: null },
        },
        data() {
            return { busy: false, favorites, MIX_APP_SSH: process.env.MIX_APP_SSH, MIX_SITE_URL: process.env.MIX_SITE_URL };
        },
        computed: {
            allowed() { return !this.category || FAVORITE_CATEGORIES.includes(this.category); },
            on() { return this.favorites.ids && is_favorite(this.article_id); },
        },
        mounted() { load_favorites(); },
        methods: {
            toggle(undo = false) {
                if (this.busy) return;
                if (!localStorage.getItem('auth_token')) {
                    if (confirm(this.$t('guide.favorite.login_needed'))) window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL);
                    return;
                }
                this.busy = true;
                toggle_favorite(this.article_id)
                    .then(now_on => {
                        if (undo === true) return;
                        this.$bus.$emit('toast', now_on
                            ? { type: 'success', message: this.$t('guide.favorite.added'), duration: 3500 }
                            : { type: 'info', message: this.$t('guide.favorite.removed'), duration: 7000,
                                action: { label: this.$t('guide.favorite.undo'), handler: () => this.toggle(true) } });
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 401) {
                            if (confirm(this.$t('guide.favorite.login_needed'))) window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL);
                        } else {
                            this.$bus.$emit('toast', { type: 'danger', message: this.$t('guide.favorite.error') });
                        }
                    })
                    .finally(() => { this.busy = false; });
            },
        },
    };
</script>

<style scoped>
    .cg-heart {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 50%;
        color: var(--cg-rope, #e0632a);
        transition: transform .15s var(--cg-ease, ease), background .15s;
    }
    .cg-heart--card {
        position: absolute;
        top: .6rem;
        right: .6rem;
        z-index: 3;
        width: 2.4rem;
        height: 2.4rem;
        background: rgba(255, 255, 255, .92);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
        font-size: 1.1rem;
    }
    .cg-heart--inline {
        width: 2.6rem;
        height: 2.6rem;
        margin-left: .4rem;
        background: var(--cg-chalk-2);
        font-size: 1.25rem;
        vertical-align: middle;
    }
    .cg-heart:hover { transform: scale(1.1); }
    .cg-heart.is-on .fa { animation: cg-heart-pop .35s var(--cg-ease, ease); }
    .cg-heart.is-busy { opacity: .6; }
    .cg-heart:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .5); outline-offset: 2px; }
    @keyframes cg-heart-pop { 50% { transform: scale(1.35); } }
    @media (prefers-reduced-motion: reduce) { .cg-heart, .cg-heart.is-on .fa { transition: none; animation: none; } }
</style>
