<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="alert alert-warning p-5">
                    <i class="fa fa-ban fa-4x text-warning mb-3 d-block"></i>
                    <h3>Payment Cancelled</h3>
                    <p class="text-muted">
                        You cancelled the payment for order #{{ orderId }}.
                        The order has been saved — you can pay later or choose a different payment method.
                    </p>
                    <router-link :to="{ name: 'userPurchases' }" class="btn btn-primary mt-3">
                        View My Orders
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
