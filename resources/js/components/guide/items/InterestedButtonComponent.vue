<template>
    <!-- "Interested" toggle for an event (services/guide/interested.js) —
         the events favourite list, which also sends the reminder emails.
         card: round heart over the card photo; big: button with the count
         for the event page. Guests get the login / quick sign-up modal. -->
    <button
        type="button"
        class="cg-int"
        :class="['cg-int--' + variant, { 'is-on': on, 'is-busy': busy }]"
        :aria-pressed="on"
        :title="on ? $t('guide.events.interested_remove') : $t('guide.events.interested_add')"
        @click.stop.prevent="toggle"
    >
        <i class="fa" :class="on ? 'fa-heart' : 'fa-heart-o'" aria-hidden="true"></i>
        <template v-if="variant === 'big'">
            <span>{{ on ? $t('guide.events.interested_on') : $t('guide.events.interested_add') }}</span>
            <small v-if="count">{{ $t('guide.events.interested_count', { n: count }) }}</small>
        </template>
        <span v-else class="visually-hidden">{{ on ? $t('guide.events.interested_remove') : $t('guide.events.interested_add') }}</span>
    </button>
</template>

<script>
    import { interested, load_interested, is_interested, toggle_interested, interested_count } from '../../../services/guide/interested.js'

    export default {
        name: 'InterestedButton',
        props: {
            // the global_event (needs id and interested_count)
            event: { type: Object, required: true },
            variant: { type: String, default: 'card' },
        },
        data() {
            return { busy: false, interested };
        },
        computed: {
            on() { return this.interested.ids && is_interested(this.event.id); },
            count() { return this.interested.delta && interested_count(this.event); },
        },
        mounted() { load_interested(); },
        methods: {
            toggle(undo = false) {
                if (this.busy) return;
                if (!localStorage.getItem('auth_token')) {
                    this.$bus.$emit('open-register-modal', {
                        reason: this.$t('guide.events.interested_login'),
                        callback: () => { load_interested(true).then(() => { if (!this.on) this.toggle(); }); },
                    });
                    return;
                }
                this.busy = true;
                toggle_interested(this.event.id)
                    .then(now_on => {
                        if (undo === true) return;
                        this.$bus.$emit('toast', now_on
                            ? { type: 'success', duration: 4000, message: this.$t('guide.events.interested_added') }
                            : { type: 'info', duration: 7000, message: this.$t('guide.events.interested_removed'),
                                action: { label: this.$t('guide.favorite.undo'), handler: () => this.toggle(true) } });
                    })
                    .catch(() => this.$bus.$emit('toast', { type: 'danger', message: this.$t('guide.favorite.error') }))
                    .finally(() => { this.busy = false; });
            },
        },
    };
</script>

<style scoped>
    .cg-int { display: inline-flex; align-items: center; justify-content: center; gap: .45rem; border: 0; color: var(--cg-rope, #e0632a); transition: transform .15s, background .15s, color .15s; }
    .cg-int.is-busy { opacity: .6; }
    .cg-int:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .5); outline-offset: 2px; }
    .cg-int--card {
        position: absolute; top: .6rem; right: .6rem; z-index: 3;
        width: 2.4rem; height: 2.4rem; border-radius: 50%;
        background: rgba(255, 255, 255, .94); box-shadow: 0 2px 8px rgba(0, 0, 0, .18); font-size: 1.1rem;
    }
    .cg-int--card:hover { transform: scale(1.08); }
    .cg-int--big {
        flex-wrap: wrap; max-width: 100%;
        padding: .65rem 1.15rem; border-radius: 2rem;
        background: #fff; border: 2px solid var(--cg-rope, #e0632a); font-weight: 800; font-size: .95rem;
    }
    .cg-int--big small { padding: .1rem .5rem; border-radius: 1rem; background: rgba(224, 99, 42, .12); font-weight: 800; }
    .cg-int--big.is-on { background: var(--cg-rope, #e0632a); color: #fff; }
    .cg-int--big.is-on small { background: rgba(255, 255, 255, .25); }
    .cg-int.is-on .fa { animation: cg-int-pop .35s ease; }
    @keyframes cg-int-pop { 50% { transform: scale(1.35); } }
    @media (prefers-reduced-motion: reduce) { .cg-int, .cg-int.is-on .fa { transition: none; animation: none; } }
</style>
