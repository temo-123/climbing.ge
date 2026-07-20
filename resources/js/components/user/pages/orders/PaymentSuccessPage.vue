<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div v-if="loading" class="py-5">
                    <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
                    <p class="mt-3 text-muted">{{ $t('user.checkout.verifying_payment') }}</p>
                </div>

                <div v-else-if="paid" class="alert alert-success p-5">
                    <i class="fa fa-check-circle fa-4x text-success mb-3 d-block"></i>
                    <h3>{{ $t('user.checkout.payment_successful') }}</h3>
                    <p class="text-muted">{{ $t('user.checkout.order_paid_confirmed', { id: orderId }) }}</p>
                    <router-link :to="{ name: 'userPurchases' }" class="btn btn-success mt-3">
                        {{ $t('user.checkout.view_my_orders') }}
                    </router-link>
                </div>

                <div v-else-if="failed" class="alert alert-danger p-5">
                    <i class="fa fa-times-circle fa-4x text-danger mb-3 d-block"></i>
                    <h3>{{ $t('user.checkout.payment_failed') }}</h3>
                    <p class="text-muted">{{ $t('user.checkout.payment_not_completed', { id: orderId }) }}</p>
                    <router-link :to="{ name: 'userPurchases' }" class="btn btn-outline-secondary mt-3">
                        {{ $t('user.checkout.view_my_orders') }}
                    </router-link>
                </div>

                <div v-else class="alert alert-warning p-5">
                    <i class="fa fa-clock-o fa-4x text-warning mb-3 d-block"></i>
                    <h3>{{ $t('user.checkout.payment_pending') }}</h3>
                    <p class="text-muted">{{ $t('user.checkout.payment_processing', { id: orderId }) }}</p>
                    <router-link :to="{ name: 'userPurchases' }" class="btn btn-outline-secondary mt-3">
                        {{ $t('user.checkout.view_my_orders') }}
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            orderId: null,
            loading: false,
            paid: false,
            failed: false,
        }
    },
    mounted() {
        this.orderId = this.$route.query.order_id
        if (this.orderId) {
            this.checkStatus()
        }
    },
    methods: {
        checkStatus() {
            this.loading = true
            axios.get('payment/shop/status/' + this.orderId)
                .then(res => {
                    const s = res.data.tbc_pay_status
                    this.paid   = s === 'SUCCEEDED'
                    this.failed = ['FAILED', 'REJECTED', 'EXPIRED', 'CANCELLED'].includes(s)

                    // sessionStorage guard — GA4 doesn't dedupe purchase events by
                    // transaction_id, so a page refresh would otherwise double-count revenue.
                    const trackedKey = 'ga_purchase_' + this.orderId
                    if (this.paid && this.$gtag && !sessionStorage.getItem(trackedKey)) {
                        this.$gtag.event('purchase', {
                            transaction_id: String(this.orderId),
                            currency: 'GEL',
                            value: res.data.total,
                        })
                        sessionStorage.setItem(trackedKey, '1')
                    }
                })
                .catch(() => { this.failed = false })
                .finally(() => { this.loading = false })
        }
    }
}
</script>
