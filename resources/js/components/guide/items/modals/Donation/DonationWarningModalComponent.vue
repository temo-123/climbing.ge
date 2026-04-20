<template>
    <div>
        <StackModal 
            v-model="is_show_warning_donation_modal" 
            title="Support Title"
            size="md"
            :cancelButton="{
                visible: true,
                title: $t('guide.close'),
                btnClass: { 'btn btn-secondary px-4 py-2': true },
            }"
            @close="closeModal"
        >
            <div class="p-8">
                <h4 class="modal-title text-center mb-8 text-2xl font-bold text-gray-800">
                    <i class="fa fa-heart text-red-500 mr-4 text-3xl"></i>
                    {{ $t('guide.donation.support_title') }}
                </h4>
                
                <div class="space-y-6 text-center">
                    <label class="checkbox-label flex items-center justify-center cursor-pointer mx-auto mb-8">
                        <input type="checkbox" v-model="donation_button_disabled" class="mr-4 w-6 h-6 text-green-600 rounded border-2 border-gray-300" />
                        <span class="text-lg text-gray-700 font-semibold">{{ $t('guide.donation.warning_message') }}</span>
                    </label>
                    
                    <button 
                        type="button" 
                        class="btn btn-success btn-lg px-8 py-4 font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
                        :disabled="!donation_button_disabled"
                        :class="{ 'opacity-50 cursor-not-allowed': !donation_button_disabled }"
                        @click="open_donation_modal"
                    >
                        <i class="fa fa-arrow-right mr-3"></i>
                        {{ $t('guide.continue_donation') }}
                    </button>
                </div>
            </div>
        </StackModal>

        <DonationModalComponent 
            ref="donation_modal"
            @close_warning_modal="closeModal"
        />
    </div>
</template>

<script>
import DonationModalComponent from './DonationModalComponent.vue';
// import StackModal from '@innologica/vue-stackable-modal'  // Global

export default {
    name: 'DonationWarningModalComponent',
    
    components: {
        DonationModalComponent
    },
    
    props: {},
    
    data: function() {
        return {
            is_show_warning_donation_modal: false,
            donation_button_disabled: false,
        };
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
            this.$refs.donation_modal.show();
            this.closeModal();
        },
    },
};
</script>

<style scoped>
.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-success:hover:not(:disabled) {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
}

.checkbox-label input[type="checkbox"]:checked {
    background-color: #28a745;
    border-color: #28a745;
    accent-color: #28a745;
}

.checkbox-label:hover {
  color: #28a745;
}
</style>
