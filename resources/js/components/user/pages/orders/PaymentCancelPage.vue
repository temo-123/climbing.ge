<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="alert alert-warning p-5">
                    <i class="fa fa-ban fa-4x text-warning mb-3 d-block"></i>
                    <h3>{{ $t('user.checkout.payment_cancelled') }}</h3>
                    <p class="text-muted">
                        {{ $t('user.checkout.payment_cancelled_notice', { id: orderId }) }}
                    </p>
                    <router-link :to="{ name: 'userPurchases' }" class="btn btn-primary mt-3">
                        {{ $t('user.checkout.view_my_orders') }}
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        orderId() {
            return this.$route.query.order_id || ''
        }
    }
}
</script>
