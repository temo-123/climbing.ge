<template>
    <!-- "Notify me when it's back" for an out-of-stock product option.
         API: stock_notification/* (logged in); emails are sent by the hourly
         app:send-back-in-stock command once the option has stock again. -->
    <div class="cg-back-stock">
        <button
            v-if="!subscribed"
            type="button"
            class="btn btn-sm btn-outline-secondary"
            :disabled="busy || !option_id"
            @click="subscribe"
        >
            <i class="fa" :class="busy ? 'fa-spinner fa-spin' : 'fa-bell-o'" aria-hidden="true"></i>
            {{ option_id ? $t('shop.back_in_stock.notify') : $t('shop.back_in_stock.choose_option') }}
        </button>
        <span v-else class="cg-back-stock__done">
            <i class="fa fa-bell" aria-hidden="true"></i> {{ $t('shop.back_in_stock.subscribed') }}
            <button type="button" class="btn btn-link btn-sm p-0 ms-1" :disabled="busy" @click="unsubscribe">{{ $t('shop.back_in_stock.cancel') }}</button>
        </span>
    </div>
</template>

<script>
    export default {
        name: 'BackInStockButton',
        props: {
            product_id: { type: [Number, String], required: true },
            option_id: { type: [Number, String], default: null },
        },
        data() {
            return {
                waiting: [],
                busy: false,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                MIX_SITE_URL: process.env.MIX_SITE_URL,
            };
        },
        computed: {
            subscribed() { return !!this.option_id && this.waiting.map(Number).includes(Number(this.option_id)); },
        },
        mounted() {
            if (!localStorage.getItem('auth_token')) return;
            axios.get('/stock_notification/get_my/' + this.product_id)
                .then(r => { this.waiting = r.data || []; })
                .catch(() => {});
        },
        methods: {
            subscribe() {
                if (!localStorage.getItem('auth_token')) {
                    if (confirm(this.$t('shop.back_in_stock.login_needed'))) window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL);
                    return;
                }
                this.busy = true;
                axios.post('/stock_notification/subscribe/' + this.option_id, { locale: this.$i18n.locale === 'ka' ? 'ka' : 'us' })
                    .then(() => { this.waiting = this.waiting.concat([Number(this.option_id)]); })
                    .catch(() => {})
                    .finally(() => { this.busy = false; });
            },
            unsubscribe() {
                this.busy = true;
                axios.delete('/stock_notification/unsubscribe/' + this.option_id)
                    .then(() => { this.waiting = this.waiting.filter(id => Number(id) !== Number(this.option_id)); })
                    .catch(() => {})
                    .finally(() => { this.busy = false; });
            },
        },
    };
</script>

<style scoped>
    .cg-back-stock { margin: -.25rem 0 1rem; }
    .cg-back-stock__done { display: inline-flex; align-items: center; gap: .35rem; font-size: .9rem; font-weight: 700; color: #1e7a4f; }
</style>
