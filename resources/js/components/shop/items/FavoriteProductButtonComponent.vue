<template>
    <!-- Favourite heart for a product (services/shop/favoriteProducts.js):
         filled when saved, toast with Undo on removal, login modal for guests. -->
    <button
        type="button"
        class="cg-fp"
        :class="{ 'is-on': on, 'is-busy': busy }"
        :aria-pressed="on"
        :title="on ? $t('shop.product.remove_from_favorites') : $t('shop.product.add_to_favorites')"
        :aria-label="on ? $t('shop.product.remove_from_favorites') : $t('shop.product.add_to_favorites')"
        @click.stop.prevent="toggle"
    >
        <i class="fa" :class="busy ? 'fa-spinner fa-spin' : (on ? 'fa-heart' : 'fa-heart-o')" aria-hidden="true"></i>
    </button>
</template>

<script>
    import { favorite_products, load_favorite_products, is_favorite_product, toggle_favorite_product } from '../../../services/shop/favoriteProducts.js'

    export default {
        name: 'FavoriteProductButton',
        props: {
            product_id: { type: [Number, String], required: true },
        },
        data() {
            return { busy: false, favorite_products };
        },
        computed: {
            on() { return this.favorite_products.ids && is_favorite_product(this.product_id); },
        },
        mounted() { load_favorite_products(); },
        methods: {
            toggle(undo = false) {
                if (this.busy) return;
                if (!localStorage.getItem('auth_token')) {
                    this.$bus.$emit('open-login-modal', () => load_favorite_products(true).then(() => { if (!this.on) this.toggle(); }));
                    return;
                }
                this.busy = true;
                toggle_favorite_product(this.product_id)
                    .then(now_on => {
                        if (undo === true) return;
                        this.$bus.$emit('toast', now_on
                            ? { type: 'success', duration: 3500, message: this.$t('shop.product.favorite_added') }
                            : { type: 'info', duration: 7000, message: this.$t('shop.product.favorite_removed'),
                                action: { label: this.$t('guide.favorite.undo'), handler: () => this.toggle(true) } });
                    })
                    .catch(() => this.$bus.$emit('toast', { type: 'danger', message: this.$t('guide.favorite.error') }))
                    .finally(() => { this.busy = false; });
            },
        },
    };
</script>

<style scoped>
    .cg-fp { display: inline-flex; align-items: center; justify-content: center; width: 2.4rem; height: 2.4rem; border: 0; border-radius: 50%; background: rgba(255, 255, 255, .94); color: var(--cg-rope, #e0632a); box-shadow: 0 2px 8px rgba(0, 0, 0, .15); font-size: 1.05rem; transition: transform .15s; }
    .cg-fp:hover { transform: scale(1.08); }
    .cg-fp.is-on .fa-heart { animation: cg-fp-pop .35s ease; }
    .cg-fp:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .5); outline-offset: 2px; }
    @keyframes cg-fp-pop { 50% { transform: scale(1.35); } }
    @media (prefers-reduced-motion: reduce) { .cg-fp { transition: none; } .cg-fp.is-on .fa-heart { animation: none; } }
</style>
