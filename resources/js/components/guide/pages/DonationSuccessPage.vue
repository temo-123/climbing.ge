<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div v-if="loading" class="py-5">
                    <i class="fa fa-spinner fa-spin fa-3x text-success"></i>
                    <p class="mt-3 text-muted">{{ $t('guide.donation.verifying_donation') }}</p>
                </div>

                <div v-else-if="paid" class="alert alert-success p-5">
                    <i class="fa fa-heart fa-4x text-danger mb-3 d-block"></i>
                    <h3>{{ $t('guide.donation.thank_you_title') }}</h3>
                    <p>{{ $t('guide.donation.donation_of_prefix') }} <strong>{{ amount }} {{ $t('guide.donation.gel') }}</strong> {{ $t('guide.donation.has_been_received_suffix') }}</p>
                    <p class="text-muted">{{ $t('guide.donation.support_helps_georgian_routes') }}</p>
                    <a href="/" class="btn btn-success mt-3">{{ $t('guide.donation.back_to_guidebook_btn') }}</a>
                </div>

                <div v-else-if="failed" class="alert alert-danger p-5">
                    <i class="fa fa-times-circle fa-4x text-danger mb-3 d-block"></i>
                    <h3>{{ $t('guide.donation.donation_failed_title') }}</h3>
                    <p class="text-muted">{{ $t('guide.donation.payment_not_completed_try_again') }}</p>
                    <a href="/" class="btn btn-outline-secondary mt-3">{{ $t('guide.donation.back_to_guidebook_btn') }}</a>
                </div>

                <div v-else class="alert alert-warning p-5">
                    <i class="fa fa-clock-o fa-4x text-warning mb-3 d-block"></i>
                    <h3>{{ $t('guide.donation.processing_ellipsis_title') }}</h3>
                    <p class="text-muted">{{ $t('guide.donation.donation_being_processed_thanks') }}</p>
                    <a href="/" class="btn btn-outline-secondary mt-3">{{ $t('guide.donation.back_to_guidebook_btn') }}</a>
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
