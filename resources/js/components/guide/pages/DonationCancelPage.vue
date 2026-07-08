<template>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="alert alert-warning p-5">
                    <i class="fa fa-ban fa-4x text-warning mb-3 d-block"></i>
                    <h3>{{ $t('guide.donation.cancelled_title') }}</h3>
                    <p class="text-muted">{{ $t('guide.donation.cancelled_message') }}</p>
                    <p class="text-muted">{{ $t('guide.donation.can_donate_again') }}</p>
                    <a href="/" class="btn btn-success mt-3">
                        <i class="fa fa-heart mr-1"></i> {{ $t('guide.donation.try_again_btn') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {}
</script>
