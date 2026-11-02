<template>
    <!-- Shop product card (catalog, sale, outlet, search, shop home).
         Photo carousel (swipe on touch, arrows on hover, dots), badges (sale %,
         new, outlet / condition, made in Georgia), favourite heart, quick
         view, title, "from" price with the old price, variants and stock
         hints. The root stays .grid-tile — the catalog grid targets it. -->
    <div class="grid-tile">
        <article class="cg-pc" :class="{ 'is-out': isOutOfStock }">
            <div class="cg-pc__media">
                <!-- photo carousel: swipe on touch, arrows on hover, dots to jump.
                     A swipe never counts as a click on the link. -->
                <router-link
                    v-if="g"
                    :to="link"
                    class="cg-pc__img cg-wm"
                    :aria-label="title"
                    draggable="false"
                    @click.capture="on_link_click"
                    @pointerdown="drag_start"
                    @pointermove="drag_move"
                    @pointerup="drag_end"
                    @pointercancel="drag_end"
                    @keydown.left.prevent="go(index - 1)"
                    @keydown.right.prevent="go(index + 1)"
                >
                    <div class="cg-pc__track" :class="{ 'is-dragging': dragging }" :style="{ transform: 'translateX(calc(' + (-index * 100) + '% + ' + drag_dx + 'px))' }">
                        <template v-if="images.length">
                            <img
                                v-for="(img, i) in images"
                                :key="img + i"
                                :src="'/public/images/product_option_img/' + img"
                                :alt="i === 0 ? title : ''"
                                :loading="i === 0 ? 'eager' : 'lazy'"
                                decoding="async"
                                draggable="false"
                            />
                        </template>
                        <img loading="lazy" decoding="async" v-else src="/public/images/site_img/demo_imgs/shop_demo.jpg" :alt="title" draggable="false" />
                    </div>
                </router-link>

                <template v-if="images.length > 1">
                    <button type="button" class="cg-pc__arrow is-prev" :disabled="index === 0" :aria-label="$t('shop.product_card.prev_photo')" @click.prevent="go(index - 1)"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                    <button type="button" class="cg-pc__arrow is-next" :disabled="index === images.length - 1" :aria-label="$t('shop.product_card.next_photo')" @click.prevent="go(index + 1)"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                </template>

                <div class="cg-pc__badges">
                    <span v-if="hasDiscount" class="cg-pc__badge is-sale">-{{ maxDiscount }}%</span>
                    <span v-if="g && g.new_flag" class="cg-pc__badge is-new">{{ $t('shop.product.new') }}</span>
                    <span v-if="g && g.sale_type === 'outlet'" class="cg-pc__badge is-outlet">{{ $t('shop.product_card.outlet') }}</span>
                </div>

                <favoriteProductButton v-if="g" :product_id="g.id" class="cg-pc__fav" />

                <span v-if="isOutOfStock" class="cg-pc__out">{{ $t('shop.product.out_of_stock') }}</span>

                <button v-if="g" type="button" class="cg-pc__quick" @click.prevent="product_quick_view(g.id)" :aria-label="$t('shop.product_card.quick_view')">
                    <i class="fa fa-eye" aria-hidden="true"></i> <span>{{ $t('shop.product_card.quick_view') }}</span>
                </button>

                <div v-if="images.length > 1" class="cg-pc__dots">
                    <button
                        v-for="(img, i) in images.slice(0, 6)"
                        :key="i"
                        type="button"
                        :class="{ active: i === index }"
                        :aria-label="$t('shop.product_card.photo', { n: i + 1 })"
                        @click.prevent="go(i)"
                    ></button>
                </div>
            </div>

            <div class="cg-pc__body">
                <div class="cg-pc__meta">
                    <span v-if="g && g.made_in_georgia" class="cg-pc__geo"><span class="cg-pc__flag" aria-hidden="true"></span> {{ $t('shop.product.made_in_georgia') }}</span>
                    <span v-if="g && g.condition && g.condition !== 'new'" class="cg-pc__cond">{{ $t('shop.product.product_desc.condition_' + g.condition) }}</span>
                </div>
                <h2 class="cg-pc__title">
                    <router-link v-if="g" :to="link">{{ title }}</router-link>
                </h2>
                <div class="cg-pc__price">
                    <strong v-if="isRange">{{ $t('shop.product_card.from_price', { price: price_now }) }}</strong>
                    <strong v-else>{{ price_now }} ₾</strong>
                    <s v-if="hasDiscount">{{ price_old }} ₾</s>
                </div>
                <div class="cg-pc__foot">
                    <span v-if="variants > 1"><i class="fa fa-th-large" aria-hidden="true"></i> {{ $t('shop.product_card.variants', { n: variants }) }}</span>
                    <span v-if="lowStock" class="is-low"><i class="fa fa-fire" aria-hidden="true"></i> {{ $t('shop.product_page.left', { n: lowStock }) }}</span>
                </div>
            </div>
        </article>
        <productQuickViewModal ref="quick_view_modal" />
    </div>
</template>

<script>
    import productQuickViewModal from '../../../global_components/modals/ProductQuickViewModal'
    import favoriteProductButton from '../FavoriteProductButtonComponent.vue'

    export default {
        components: { productQuickViewModal, favoriteProductButton },
        props: ['product_data'],
        data() {
            return { index: 0, dragging: false, drag_dx: 0, drag_x0: 0, drag_y0: 0, drag_moved: false, drag_id: null };
        },
        computed: {
            g() { return this.product_data.global_product; },
            title() { return this.product_data.locale_product ? this.product_data.locale_product.title : ''; },
            link() {
                const loc = this.$route.params.locale;
                return (loc ? '/' + loc : '') + '/product/' + (this.g ? this.g.url_title : '');
            },
            images() { return this.product_data.product_images || []; },
            isOutOfStock() { return this.product_data.out_of_stock === true; },
            hasDiscount() { return !!this.product_data.has_discount; },
            maxDiscount() { return this.product_data.max_discount || 0; },
            isRange() {
                const d = this.product_data;
                return this.hasDiscount ? d.new_min_price != d.new_max_price : d.min_price != d.max_price;
            },
            price_now() { return this.hasDiscount ? this.product_data.new_min_price : this.product_data.min_price; },
            price_old() { return this.product_data.min_price; },
            options() { return this.product_data.product_option || []; },
            variants() { return this.options.length; },
            // a small total stock (made-to-order items aren't stock-limited)
            lowStock() {
                if (this.isOutOfStock || !this.options.length || (this.g && this.g.sale_type === 'custom_production')) return 0;
                const total = this.options.reduce((s, o) => s + (Number(o.stock_quantity) || 0), 0);
                return total > 0 && total <= 3 ? total : 0;
            },
        },
        methods: {
            go(i) {
                this.index = Math.max(0, Math.min(this.images.length - 1, i));
            },
            // horizontal swipe (touch / pen); vertical moves are left to page scrolling
            drag_start(e) {
                if (e.pointerType === 'mouse' || this.images.length < 2) return;
                this.drag_id = e.pointerId;
                this.drag_x0 = e.clientX; this.drag_y0 = e.clientY;
                this.drag_dx = 0; this.drag_moved = false;
            },
            drag_move(e) {
                if (this.drag_id !== e.pointerId) return;
                const dx = e.clientX - this.drag_x0, dy = e.clientY - this.drag_y0;
                if (!this.dragging) {
                    if (Math.abs(dy) > Math.abs(dx) && Math.abs(dy) > 8) { this.drag_id = null; return; }   // scrolling the page
                    if (Math.abs(dx) < 8) return;
                    this.dragging = true; this.drag_moved = true;
                    try { e.currentTarget.setPointerCapture(e.pointerId); } catch (err) {}
                }
                // resist at the ends
                const end = (this.index === 0 && dx > 0) || (this.index === this.images.length - 1 && dx < 0);
                this.drag_dx = end ? dx / 3 : dx;
            },
            drag_end(e) {
                if (this.drag_id !== e.pointerId) return;
                const width = e.currentTarget.getBoundingClientRect().width || 1;
                if (this.dragging && Math.abs(this.drag_dx) > width * 0.18) this.go(this.index + (this.drag_dx < 0 ? 1 : -1));
                this.dragging = false; this.drag_dx = 0; this.drag_id = null;
            },
            // a swipe ends with a click event — don't open the product then
            on_link_click(e) {
                if (this.drag_moved) { e.preventDefault(); e.stopPropagation(); this.drag_moved = false; }
            },
            product_quick_view(product_id) {
                this.$refs.quick_view_modal.show_modal(product_id);
            },
        },
    }
</script>

<style scoped>
    .cg-pc { display: flex; flex-direction: column; height: 100%; overflow: hidden; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); transition: transform .2s var(--cg-ease, ease), box-shadow .2s; }
    .cg-pc:hover { box-shadow: var(--cg-shadow, 0 8px 22px rgba(0,0,0,.12)); }

    .cg-pc__media { position: relative; background: #fff; }
    .cg-pc__img { display: block; aspect-ratio: 16 / 9; overflow: hidden; background: var(--cg-chalk); touch-action: pan-y; -webkit-user-select: none; user-select: none; }
    .cg-pc__track { display: flex; height: 100%; transition: transform .35s var(--cg-ease, ease); }
    .cg-pc__track.is-dragging { transition: none; }
    .cg-pc__track img { flex: 0 0 100%; width: 100%; height: 100%; object-fit: cover; -webkit-user-drag: none; }
    .is-out .cg-pc__track img { opacity: .55; filter: grayscale(.4); }
    /* arrows: on hover / keyboard focus with a mouse; touch screens swipe instead */
    .cg-pc__arrow { position: absolute; top: 50%; z-index: 3; width: 2rem; height: 2rem; margin-top: -1.6rem; display: flex; align-items: center; justify-content: center; border: 0; border-radius: 50%; background: rgba(255, 255, 255, .92); color: var(--cg-ink); box-shadow: 0 2px 6px rgba(0, 0, 0, .18); font-size: .8rem; opacity: 0; transition: opacity .2s; }
    .cg-pc__arrow.is-prev { left: .5rem; }
    .cg-pc__arrow.is-next { right: .5rem; }
    .cg-pc__arrow:disabled { visibility: hidden; }
    .cg-pc__media:hover .cg-pc__arrow, .cg-pc__arrow:focus-visible { opacity: 1; }
    @media (hover: none) { .cg-pc__arrow { display: none; } }
    .cg-pc__badges { position: absolute; top: .6rem; left: .6rem; z-index: 2; display: flex; flex-direction: column; align-items: flex-start; gap: .3rem; }
    .cg-pc__badge { padding: .2rem .55rem; border-radius: .4rem; font-weight: 800; font-size: .75rem; line-height: 1.3; }
    .cg-pc__badge.is-sale { background: var(--cg-rope, #e0632a); color: #fff; }
    .cg-pc__badge.is-new { background: var(--cg-hold); color: var(--cg-ink); }
    .cg-pc__badge.is-outlet { background: var(--cg-ink); color: #fff; }
    .cg-pc__fav { position: absolute; top: .6rem; right: .6rem; z-index: 2; }
    .cg-pc__out { position: absolute; left: 0; right: 0; top: 50%; z-index: 2; transform: translateY(-50%); padding: .4rem; background: rgba(21, 33, 43, .78); color: #fff; font-weight: 800; text-align: center; font-size: .85rem; letter-spacing: .04em; text-transform: uppercase; }
    /* quick view: slides up on hover (desktop), a round icon on touch screens */
    .cg-pc__quick { position: absolute; left: .6rem; right: .6rem; bottom: .6rem; z-index: 3; display: flex; align-items: center; justify-content: center; gap: .4rem; padding: .45rem; border: 0; border-radius: var(--cg-r); background: rgba(21, 33, 43, .88); color: #fff; font-weight: 800; font-size: .85rem; opacity: 0; transform: translateY(6px); transition: opacity .2s, transform .2s; }
    .cg-pc:hover .cg-pc__quick, .cg-pc__quick:focus-visible { opacity: 1; transform: none; }
    @media (hover: none) {
        .cg-pc__quick { left: auto; right: .6rem; width: 2.4rem; height: 2.4rem; padding: 0; border-radius: 50%; opacity: 1; transform: none; }
        .cg-pc__quick span { display: none; }
    }
    /* photo dots: always clickable — above the quick-view bar while it shows (mouse),
       bottom-centre next to the round quick-view icon on touch screens */
    .cg-pc__dots { position: absolute; left: 0; right: 0; bottom: .45rem; z-index: 4; display: flex; justify-content: center; gap: .15rem; transition: bottom .2s; pointer-events: none; }
    .cg-pc__dots button { width: 1.1rem; height: 1.1rem; padding: 0; border: 0; background: transparent; pointer-events: auto; position: relative; }
    .cg-pc__dots button::before { content: ""; position: absolute; inset: .3rem; border-radius: 50%; background: rgba(21, 33, 43, .3); }
    .cg-pc__dots button.active::before { background: var(--cg-accent); }
    .cg-pc:hover .cg-pc__dots { bottom: 3.1rem; }
    @media (hover: none) { .cg-pc__dots, .cg-pc:hover .cg-pc__dots { bottom: .45rem; justify-content: flex-start; padding-left: .5rem; } }

    .cg-pc__body { display: flex; flex-direction: column; gap: .3rem; flex: 1; padding: .8rem .95rem .9rem; border-top: 1px solid var(--cg-chalk-2); }
    .cg-pc__meta { display: flex; flex-wrap: wrap; gap: .35rem; min-height: 0; }
    .cg-pc__meta:empty { display: none; }
    .cg-pc__geo, .cg-pc__cond { display: inline-flex; align-items: center; gap: .3rem; padding: .1rem .5rem; border-radius: 2rem; background: var(--cg-chalk); color: var(--cg-stone); font-size: .72rem; font-weight: 800; }
    /* tiny Georgian flag: white field, red cross */
    .cg-pc__flag { width: .95rem; height: .65rem; border: 1px solid var(--cg-line); background: linear-gradient(#e0262b, #e0262b) center / 100% 2px no-repeat, linear-gradient(#e0262b, #e0262b) center / 2px 100% no-repeat, #fff; }
    .cg-pc__title { margin: 0 !important; font-size: 1rem !important; line-height: 1.3; font-family: var(--cg-font-body) !important; font-weight: 800; }
    .cg-pc__title a { color: var(--cg-ink); text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .cg-pc__title a:hover { color: var(--cg-accent-dark); }
    .cg-pc__price { display: flex; align-items: baseline; gap: .4rem; flex-wrap: wrap; margin-top: auto; padding-top: .3rem; }
    .cg-pc__price strong { font-family: var(--cg-font-display); font-size: 1.45rem; color: var(--cg-ink); }
    .cg-pc__price small { color: var(--cg-muted); font-weight: 700; }
    .cg-pc__price s { color: var(--cg-muted); font-weight: 600; }
    .cg-pc__foot { display: flex; flex-wrap: wrap; gap: .3rem .75rem; color: var(--cg-stone); font-size: .78rem; font-weight: 700; }
    .cg-pc__foot:empty { display: none; }
    .cg-pc__foot .is-low { color: var(--cg-rope, #e0632a); }
    @media (prefers-reduced-motion: reduce) { .cg-pc, .cg-pc__track, .cg-pc__quick, .cg-pc__arrow { transition: none; } }
</style>
