<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div v-if="loading" class="py-5">
                    <i class="fa fa-spinner fa-spin fa-3x text-success"></i>
                    <p class="mt-3 text-muted">Verifying donation...</p>
                </div>

                <div v-else-if="paid" class="alert alert-success p-5">
                    <i class="fa fa-heart fa-4x text-danger mb-3 d-block"></i>
                    <h3>Thank You!</h3>
                    <p>Your donation of <strong>{{ amount }} GEL</strong> has been received.</p>
                    <p class="text-muted">Your support helps keep Georgian climbing routes alive.</p>
                    <a href="/" class="btn btn-success mt-3">Back to Guidebook</a>
                </div>

                <div v-else-if="failed" class="alert alert-danger p-5">
                    <i class="fa fa-times-circle fa-4x text-danger mb-3 d-block"></i>
                    <h3>Donation Failed</h3>
                    <p class="text-muted">The payment was not completed. Please try again.</p>
                    <a href="/" class="btn btn-outline-secondary mt-3">Back to Guidebook</a>
                </div>

                <div v-else class="alert alert-warning p-5">
                    <i class="fa fa-clock-o fa-4x text-warning mb-3 d-block"></i>
                    <h3>Processing...</h3>
                    <p class="text-muted">Your donation is being processed. Thank you!</p>
                    <a href="/" class="btn btn-outline-secondary mt-3">Back to Guidebook</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            donationId: null,
            loading: false,
            paid: false,
            failed: false,
            amount: null,
        }
    },
    mounted() {
        this.donationId = this.$route.query.donation_id
        if (this.donationId) {
            this.checkStatus()
        }
    },
    methods: {
        checkStatus() {
            this.loading = true
            axios.get('set_donation/status/' + this.donationId)
                .then(res => {
                    const d = res.data.donation
                    this.paid   = d && d.is_paid
                    this.failed = d && ['declined', 'expired', 'failed'].includes(d.status)
                    this.amount = d && d.amount
                })
                .catch(() => { this.failed = false })
                .finally(() => { this.loading = false })
        }
    }
}
</script>
