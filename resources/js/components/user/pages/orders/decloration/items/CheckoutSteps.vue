<template>
    <nav class="co-steps" :aria-label="$t('user.co.steps_label')">
        <ol>
            <li v-for="(s, i) in steps" :key="s.key" :class="'is-' + state(i)" :aria-current="i === current ? 'step' : null">
                <component :is="s.to && i < current ? 'router-link' : 'span'" :to="s.to" class="co-steps__item">
                    <span class="co-steps__dot">
                        <i v-if="i < current" class="fa fa-check" aria-hidden="true"></i>
                        <template v-else>{{ i + 1 }}</template>
                    </span>
                    <span class="co-steps__label">{{ s.label }}</span>
                </component>
            </li>
        </ol>
    </nav>
</template>

<script>
// Checkout progress: Cart → Delivery & payment → Review → Done.
// Finished steps are links back (cart only — later steps need their params).
export default {
    props: {
        current: { type: Number, required: true },   // 0-based
    },
    computed: {
        steps() {
            return [
                { key: 'cart',    label: this.$t('user.co.step_cart'),     to: { name: 'cart' } },
                { key: 'details', label: this.$t('user.co.step_details'),  to: null },
                { key: 'review',  label: this.$t('user.co.step_review'),   to: null },
                { key: 'done',    label: this.$t('user.co.step_done'),     to: null },
            ]
        },
    },
    methods: {
        state(i) {
            return i < this.current ? 'done' : (i === this.current ? 'current' : 'todo')
        },
    },
}
</script>

<style scoped>
.co-steps ol {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem;
}
.co-steps li {
    flex: 1;
    position: relative;
}
.co-steps li:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 15px;
    left: calc(50% + 20px);
    right: calc(-50% + 20px);
    height: 2px;
    background: var(--bs-border-color);
}
.co-steps li.is-done::after { background: var(--cg-accent); }
.co-steps__item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .35rem;
    text-decoration: none;
    color: var(--bs-secondary-color);
    font-size: .82rem;
    text-align: center;
}
a.co-steps__item:hover .co-steps__label { text-decoration: underline; }
.co-steps__dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border: 2px solid var(--bs-border-color);
    background: var(--bs-body-bg);
    position: relative;
    z-index: 1;
}
.is-done .co-steps__dot {
    background: var(--cg-accent);
    border-color: var(--cg-accent);
    color: #fff;
}
.is-current .co-steps__dot {
    border-color: var(--cg-accent);
    color: var(--cg-accent);
    box-shadow: 0 0 0 4px color-mix(in srgb, var(--cg-accent) 18%, transparent);
}
.is-current .co-steps__label {
    color: var(--bs-emphasis-color);
    font-weight: 600;
}
@media (max-width: 575.98px) {
    .co-steps__label { font-size: .7rem; }
}
</style>

<style>
/* Shared by both checkout steps (orderPayment / orderDecloration) — lives here
   because both pages load this component; unscoped on purpose, prefixed .co */
.co { padding-top: 1rem; padding-bottom: 2.5rem; }
.co-card {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-body-bg);
    padding: 1.1rem 1.25rem;
    margin-bottom: 1rem;
}
.co-card.has-error { border-color: var(--bs-danger); }
.co-card__head {
    display: flex;
    align-items: center;
    gap: .65rem;
    margin-bottom: .9rem;
}
.co-card__head h2 { font-size: 1.1rem; font-weight: 700; margin: 0; }
.co-card__head .co-card__edit { margin-left: auto; }
.co-card__num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--cg-accent);
    color: #fff;
    font-weight: 700;
    font-size: .85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.co-error { color: var(--bs-danger-text-emphasis); font-size: .9rem; }
.co-options { display: flex; flex-direction: column; gap: .6rem; }
.co-options--grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); }
.co-option {
    display: flex;
    align-items: flex-start;
    gap: .7rem;
    margin: 0;
    padding: .85rem 1rem;
    border: 2px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-body-bg);
    cursor: pointer;
    text-align: left;
    transition: border-color .15s, background-color .15s;
}
.co-option:hover { border-color: var(--bs-secondary-border-subtle, var(--bs-secondary)); }
.co-option.is-active { border-color: var(--cg-accent); background: color-mix(in srgb, var(--cg-accent) 7%, var(--bs-body-bg)); }
.co-option:focus-within { outline: 2px solid var(--cg-accent); outline-offset: 2px; }
.co-option__radio {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    border-radius: 50%;
    border: 2px solid var(--bs-border-color);
    flex-shrink: 0;
    position: relative;
}
.co-option.is-active .co-option__radio { border-color: var(--cg-accent); }
.co-option.is-active .co-option__radio::after {
    content: '';
    position: absolute;
    inset: 3px;
    border-radius: 50%;
    background: var(--cg-accent);
}
.co-option__icon { font-size: 1.25rem; color: var(--bs-secondary-color); margin-top: 1px; width: 22px; text-align: center; }
.co-option__body { display: flex; flex-direction: column; gap: .1rem; min-width: 0; }
.co-option__title { font-weight: 600; }
.co-option__text { font-size: .88rem; }
.co-option--add {
    align-items: center;
    justify-content: center;
    border-style: dashed;
    color: var(--cg-accent);
    font-weight: 600;
    min-height: 96px;
}
.co-form {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-tertiary-bg);
}
.co-form__title { font-size: 1rem; font-weight: 700; margin-bottom: .75rem; }
.co-code { display: flex; gap: .5rem; flex-wrap: wrap; }
.co-code .form-control { flex: 1; min-width: 180px; }
.co-msg { margin: .6rem 0 0; font-size: .9rem; }
.co-tbc {
    display: inline-block;
    background: #00529b;
    color: #fff;
    font-weight: 800;
    font-size: .7rem;
    padding: 1px 6px;
    border-radius: 4px;
    letter-spacing: 1px;
    vertical-align: middle;
}
.co-block { font-size: .82rem; color: var(--bs-secondary-color); text-align: center; margin: .5rem 0 0; }
</style>
