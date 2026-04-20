<template>
    <div>
        <StockModal 
            v-model="is_show_warning_donation_modal" 
            title="Support Title"
            :cancelButton="{
                visible: true,
                title: $t('guide.close'),
                btnClass: { 'btn btn-danger': true },
            }"
            @close="closeModal"
        >
            <h4 class="modal-title text-center">
                <i class="fa fa-heart text-danger mr-2"></i>
                {{ $t('guide.donation.support_title') }}
            </h4>
            
            <div class="donation-modal-content">
                <input type="checkbox" @click="donation_button_disabled = !donation_button_disabled" />
                <p class="text-center mt-3">
                    <small>{{ $t('guide.donation.warning_message') }}</small>
                </p>
            </div>

            <template #footer>
                <button 
                    type="button" 
                    class="btn btn-success" 
                    :disabled="!donation_button_disabled"
                    @click="open_donation_modal"
                >
                    {{ $t('guide.continue_donation') }}
                </button>
            </template>
        </StockModal>

        <DonationModalComponent 
            ref="donation_modal"
            @close_warning_modal="closeModal"
        />
    </div>
</template>

<script>
import DonationModalComponent from './DonationModalComponent.vue';

export default {
    name: 'DonationWarningModalComponent',
    
    components: {
        // CustomModal global
        DonationModalComponent
    },
    
    props: {},
    
    data: function() {
        return {
            is_show_warning_donation_modal: false,
            donation_button_disabled: false,
        };
    },
    
    mounted() {
    },
    
    methods: {
        show() {
            this.is_show_warning_donation_modal = true;
        },

        closeModal() {
            this.donation_button_disabled = false;
            this.is_show_warning_donation_modal = false;
        },

        open_donation_modal() {
            this.closeModal();
            this.$refs.donation_modal.show();
        },
    },
};
</script>

<style scoped>
.donation-modal-content {
    padding: 15px 20px;
}
</style>
