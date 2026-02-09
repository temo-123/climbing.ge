<template>
    <stack-modal
        :show="is_show_donation_modal"
        @close="closeModal"
        :saveButton="{
            visible: false,
        }"
        :cancelButton="{
            visible: true,
            title: $t('guide.close'),
            btnClass: { 'btn btn-danger': true },
        }"
    >
        <template #header>
            <h4 class="modal-title text-center">
                <i class="fa fa-heart text-danger mr-2"></i>
                {{ $t('guide.donation.support_title') }}
            </h4>
        </template>
        
        <div class="donation-modal-content">
            <p class="text-muted text-center mb-4">
                {{ $t('guide.donation.description') }}
            </p>
            
            <!-- Predefined Amount Buttons -->
            <div class="donation-amounts mb-4">
                <button 
                    v-for="amount in predefinedAmounts" 
                    :key="amount"
                    @click="selectAmount(amount)"
                    :class="['btn donation-btn', selectedAmount === amount ? 'btn-primary' : 'btn-secondary-custom btn-amount']"
                >
                    {{ amount }} {{ $t('guide.donation.gel') }}
                </button>
            </div>
            
            <!-- Custom Amount Input -->
            <div class="custom-amount-section mb-4">
                <label for="customAmount" class="control-label">{{ $t('guide.donation.custom_amount_label') }}</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-money"></i>
                    </span>
                    <input 
                        type="number" 
                        id="customAmount"
                        v-model="customAmount"
                        @input="onCustomAmountInput"
                        class="form-control" 
                        :placeholder="$t('guide.donation.custom_amount_label')"
                        min="1"
                    >
                    <span class="input-group-addon">{{ $t('guide.donation.gel') }}</span>
                </div>
            </div>
            
            <!-- Selected Amount Display -->
            <div class="selected-amount-display mb-4 text-center">
                <span class="lead">
                    {{ $t('guide.donation.selected') }} <strong class="text-success">{{ displayAmount }} {{ $t('guide.donation.gel') }}</strong>
                </span>
            </div>
            
            <!-- Donate Button -->
            <button 
                @click="processDonation"
                :disabled="!isValidAmount || loading"
                class="btn btn-success btn-lg btn-block donate-submit-btn"
            >
                <span v-if="loading" class="fa fa-spinner fa-spin mr-2"></span>
                <i v-else class="fa fa-heart mr-2"></i>
                {{ loading ? $t('guide.donation.processing') : $t('guide.donation.donate_button') + ' ' + displayAmount + ' ' + $t('guide.donation.gel') }}
            </button>
            
            <!-- Success Message -->
            <div v-if="donationSuccess" class="alert alert-success mt-3 text-center">
                <i class="fa fa-check-circle mr-2"></i>
                {{ $t('guide.donation.success') }}
            </div>
            
            <!-- Error Message -->
            <div v-if="errorMessage" class="alert alert-danger mt-3 text-center">
                <i class="fa fa-exclamation-circle mr-2"></i>
                {{ errorMessage }}
            </div>
        </div>
    </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal';

export default {
    name: 'DonationModalComponent',
    
    components: {
        StackModal,
    },
    
    props: {
        show: {
            type: Boolean,
            default: false,
        },
    },
    
    data: function() {
        return {
            is_show_donation_modal: false,
            predefinedAmounts: [5, 10, 20, 50, 100, 200],
            selectedAmount: null,
            customAmount: null,
            loading: false,
            donationSuccess: false,
            errorMessage: '',
        };
    },
    
    computed: {
        displayAmount() {
            if (this.selectedAmount !== null) {
                return this.selectedAmount;
            }
            if (this.customAmount !== null && this.customAmount !== '') {
                return parseFloat(this.customAmount).toFixed(2);
            }
            return '0';
        },
        
        isValidAmount() {
            const amount = parseFloat(this.displayAmount);
            return amount > 0;
        },
    },
    
    watch: {
        show(newVal) {
            this.is_show_donation_modal = newVal;
        },
    },
    
    mounted() {
        this.is_show_donation_modal = this.show;
    },
    
    methods: {
        selectAmount(amount) {
            this.selectedAmount = amount;
            this.customAmount = null;
            this.errorMessage = '';
            this.donationSuccess = false;
        },
        
        onCustomAmountInput() {
            this.selectedAmount = null;
            this.errorMessage = '';
            this.donationSuccess = false;
        },
        
        closeModal() {
            this.is_show_donation_modal = false;
            this.$emit('close');
            this.resetForm();
        },
        
        resetForm() {
            this.selectedAmount = null;
            this.customAmount = null;
            this.loading = false;
            this.donationSuccess = false;
            this.errorMessage = '';
        },
        
        async processDonation() {
            if (!this.isValidAmount) {
                this.errorMessage = this.$t('guide.donation.invalid_amount');
                return;
            }
            
            this.loading = true;
            this.errorMessage = '';
            this.donationSuccess = false;
            
            const amount = parseFloat(this.displayAmount);
            
            try {
                const response = await axios.post('/set_donation/process', {
                    amount: amount,
                });
                
                // console.log('Donation processed:', response.data);
                this.donationSuccess = true;
                
                // Close modal after short delay
                // setTimeout(() => {
                //     this.closeModal();
                // }, 2000);
                
            } catch (error) {
                console.error('Donation error:', error);
                this.errorMessage = error.response?.data?.message || this.$t('guide.donation.error');
            } finally {
                this.loading = false;
            }
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

/* .btn-amount {
    border: 2px solid #ddd;
    background: #fff;
    color: #555;
} */

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

@media (max-width: 576px) {
    .donation-amounts {
        gap: 8px;
    }
    
    /* .donation-btn {
        min-width: 70px;
        padding: 10px 15px;
    } */
    
    .donation-modal-content {
        padding: 10px 15px;
    }
}
</style>

