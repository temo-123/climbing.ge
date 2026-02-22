<template>
    <div>
        <stack-modal
            :show="is_show_warning_donation_modal"
            @close="closeModal"

            :cancelButton="{
                visible: true,
                title: $t('guide.close'),
                btnClass: { 'btn btn-danger': true },
            }"
        >
            <!-- <template #header> -->
                <h4 class="modal-title text-center">
                    <i class="fa fa-heart text-danger mr-2"></i>
                    {{ $t('guide.donation.support_title') }}
                </h4>
            <!-- </template> -->
            
            <div class="donation-modal-content">
                <input type="checkbox" @click="donation_button_disabled = !donation_button_disabled" />
                <p class="text-center mt-3">
                    <small>{{ $t('guide.donation.warning_message') }}</small>
                </p>
            </div>

            <div slot="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-success" 
                    :disabled="!donation_button_disabled"
                    @click="open_donation_modal"
                >
                    {{ $t('guide.continue_donation') }}
                </button>
            </div>
        </stack-modal>

        <DonationModalComponent 
            ref="donation_modal"
            @close_warning_modal="closeModal"
        />
    </div>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal';
import DonationModalComponent from './DonationModalComponent.vue';

export default {
    name: 'DonationWarningModalComponent',
    
    components: {
        StackModal,
        DonationModalComponent
    },
    
    props: {
        // show: {
        //     type: Boolean,
        //     default: false,
        // },
    },
    
    data: function() {
        return {
            is_show_warning_donation_modal: false,

            donation_button_disabled: false,
        };
    },
    
    watch: {
        // show(newVal) {
        //     this.is_show_warning_donation_modal = newVal;
        // },
    },
    
    mounted() {
        // this.is_show_warning_donation_modal = this.show;
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

.modal-title {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.donation-amounts {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

.donation-btn {
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 80px;
}

.btn-amount:hover {
    border-color: #28a745;
    border-color: #28a745;
    color: #28a745;
}

.donation-btn.btn-primary {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}

.donation-btn.btn-primary:hover {
    background: #218838;
    border-color: #218838;
}

.custom-amount-section label {
    font-weight: 500;
    color: #555;
}

.input-group-addon {
    background: #f5f5f5;
    border: 1px solid #ddd;
    color: #777;
    font-weight: 600;
}

.input-group .form-control {
    border: 1px solid #ddd;
}

.input-group .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
}

.selected-amount-display {
    padding: 15px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px dashed #ddd;
}

.donate-submit-btn {
    font-weight: 600;
    padding: 14px 24px;
    transition: all 0.3s ease;
    border-radius: 6px;
}

.donate-submit-btn:hover:not(:disabled) {
    background: #218838;
    border-color: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.donate-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.alert {
    border-radius: 8px;
    font-weight: 500;
    padding: 12px 15px;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

/* Donator Info Section Styles */
.donator-info-section {
    padding: 15px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.donator-info-section h5 {
    color: #495057;
    font-weight: 600;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 10px;
}

.donator-info-section .form-group {
    margin-bottom: 12px;
}

.donator-info-section .control-label {
    font-weight: 500;
    color: #555;
    font-size: 14px;
}

.donator-info-section .input-group-addon {
    background: #fff;
    border: 1px solid #ddd;
    border-right: none;
    color: #777;
    min-width: 40px;
    justify-content: center;
}

.donator-info-section .form-control {
    border: 1px solid #ddd;
    border-left: none;
}

.donator-info-section .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
}

@media (max-width: 576px) {
    .donation-amounts {
        gap: 8px;
    }
    
    .donation-modal-content {
        padding: 10px 15px;
    }
    
    .donator-info-section {
        padding: 10px;
    }
}
</style>

