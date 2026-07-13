<template>
    <div v-if="donationEnabled" :class="['donation-section', 'my-4', 'donation-' + position]">

        <!-- <h4 class="modal-title">
            <i class="fa fa-heart text-danger mr-2"></i>
            {{ $t('guide.donation.support_title') }}
        </h4> -->

        <button
            @click="showDonationModal"
            class="btn btn-success donation-float-btn"
            :title="$t('guide.donation.support')"
        >
            <i class="fa fa-heart mr-2"></i>
            {{ $t('guide.donation.support') }}
        </button>

        <!-- <p class="donation-hint mt-2">
            <small>{{ $t('guide.donation.hint') }}</small>
        </p> -->

        <!-- <div class="donation-modal-overlay"> -->
            <donationWarningModal
                ref="donation_warning_modal"
            />
        <!-- </div> -->
    </div>
</template>

<script>
    import donationWarningModal from './modals/Donation/DonationWarningModalComponent.vue'

    export default {
        name: 'DonationComponent',

        components: {
            donationWarningModal
        },

        props: {
            position: {
                type: String,
                default: 'center',
                validator: function (value) {
                    return ['left', 'right', 'center'].indexOf(value) !== -1
                }
            },
        },

        data: function () {
            return {
                donationEnabled: false,
            }
        },

        mounted() {
            axios.get('payment/status')
                .then(r => { this.donationEnabled = !!r.data.donation_enabled })
                .catch(() => { this.donationEnabled = false })
        },

        methods: {
            showDonationModal() {
                this.$refs.donation_warning_modal.show();
            },

            // closeDonationModal() {
            //     this.is_donation_modal_open = false;
            // },
        }
    }
</script>

<style scoped>
.donation-section {
    margin: 20px 0;
}

.donation-center {
    text-align: center;
}

.donation-left {
    text-align: left;
}

.donation-right {
    text-align: right;
}

.donation-float-btn {
    padding: 12px 30px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
}

.donation-float-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.donation-float-btn i {
    font-size: 18px;
}

.donation-hint {
    color: #6c757d;
    font-size: 12px;
}

/* .donation-modal-overlay { */
    /* position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999; */
/* } */

@media (max-width: 768px) {
    .donation-float-btn {
        padding: 10px 24px;
        font-size: 14px;
    }
    
    .donation-left,
    .donation-right {
        text-align: center;
    }
}
</style>
