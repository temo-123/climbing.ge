<template>
    <!-- 1–5 star picker (v-model). Hover / focus previews, the word label follows.
         Used by the order review page; size: 'lg' | 'sm'. -->
    <div class="star-in" :class="['star-in--' + size, { 'is-invalid': invalid, 'is-disabled': disabled }]">
        <div class="star-in__stars" role="radiogroup" :aria-label="label" @mouseleave="hover = 0">
            <button
                v-for="n in 5"
                :key="n"
                type="button"
                role="radio"
                class="star-in__star"
                :class="{ 'is-on': n <= (hover || modelValue) }"
                :aria-checked="modelValue === n"
                :aria-label="n + ' / 5 — ' + word(n)"
                :disabled="disabled"
                @mouseenter="hover = n"
                @focus="hover = n"
                @blur="hover = 0"
                @click="$emit('update:modelValue', n)"
            ><i class="fa fa-star" aria-hidden="true"></i></button>
        </div>
        <span v-if="show_word" class="star-in__word">{{ (hover || modelValue) ? word(hover || modelValue) : $t('shop.product.feedback.rate_hint') }}</span>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: { type: Number, default: 0 },
        label: { type: String, default: '' },
        size: { type: String, default: 'lg' },
        invalid: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
        show_word: { type: Boolean, default: true },
    },
    emits: ['update:modelValue'],
    data() {
        return { hover: 0 }
    },
    methods: {
        word(n) {
            return this.$t('shop.product.feedback.stars_' + n)
        },
    },
}
</script>

<style scoped>
    .star-in { display: inline-flex; align-items: center; flex-wrap: wrap; gap: .3rem .75rem; }
    .star-in__stars { display: inline-flex; gap: .1rem; }
    .star-in__star {
        padding: .05rem .12rem;
        border: 0;
        background: none;
        color: var(--cg-line);
        line-height: 1;
        cursor: pointer;
        transition: transform .15s ease, color .15s ease;
    }
    .star-in--lg .star-in__star { font-size: 2rem; }
    .star-in--sm .star-in__star { font-size: 1.35rem; }
    .star-in__star.is-on { color: var(--cg-hold); }
    .star-in__star:hover:not(:disabled) { transform: scale(1.12); }
    .star-in__star:focus-visible { outline: 2px solid var(--cg-accent); border-radius: .3rem; }
    .star-in.is-disabled .star-in__star { cursor: default; }
    .star-in__word { font-weight: 700; color: var(--cg-stone); font-size: .9rem; }
    .star-in.is-invalid .star-in__word { color: #dc3545; }
    @media (prefers-reduced-motion: reduce) {
        .star-in__star { transition: none; }
        .star-in__star:hover:not(:disabled) { transform: none; }
    }
</style>
