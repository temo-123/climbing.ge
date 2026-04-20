<template>
  <StockModal
    v-model="is_show_donation_modal"
    :title="$t('guide.donation.support_title')"
    :cancelButton="{
      visible: true,
      title: $t('guide.close'),
      btnClass: { 'btn btn-danger': true },
    }"
    @close="close"
  >
    <h4 class="modal-title text-center">
      <i class="fa fa-heart text-danger mr-2"></i>
      {{ $t('guide.donation.support_title') }}
    </h4>

    <div class="donation-modal-content">
      <p class="text-muted text-center mb-4">
        {{ $t('guide.donation.description') }}
      </p>

      <div class="donator-info-section mb-4">
        <h5 class="mb-3">
          <i class="fa fa-user mr-2"></i>
          {{ $t('guide.donation.donator_info') || 'Donator Information' }}
        </h5>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="donatorName" class="control-label">{{ $t('guide.donation.name') || 'Name' }}</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-user"></i>
                </span>
                <input 
                  type="text" 
                  id="donatorName"
                  v-model="donator.name"
                  class="form-control" 
                  :placeholder="$t('guide.donation.name_placeholder') || 'Enter your name'"
                >
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="donatorSurname" class="control-label">{{ $t('guide.donation.surname') || 'Surname' }}</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-user"></i>
                </span>
                <input 
                  type="text" 
                  id="donatorSurname"
                  v-model="donator.surname"
                  class="form-control" 
                  :placeholder="$t('guide.donation.surname_placeholder') || 'Enter your surname'"
                >
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="donatorEmail" class="control-label">{{ $t('guide.donation.email') || 'Email' }} *</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-envelope"></i>
                </span>
                <input 
                  type="email" 
                  id="donatorEmail"
                  v-model="donator.email"
                  class="form-control" 
                  :placeholder="$t('guide.donation.email_placeholder') || 'Enter your email'"
                  required
                >
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="donatorPhone" class="control-label">{{ $t('guide.donation.phone') || 'Phone Number' }}</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-phone"></i>
                </span>
                <input 
                  type="tel" 
                  id="donatorPhone"
                  v-model="donator.phone_number"
                  class="form-control" 
                  :placeholder="$t('guide.donation.phone_placeholder') || 'Enter your phone number'"
                >
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="donatorCountry" class="control-label">{{ $t('guide.donation.country') || 'Country' }}</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-globe"></i>
                </span>
                <input 
                  type="text" 
                  id="donatorCountry"
                  v-model="donator.country"
                  class="form-control" 
                  :placeholder="$t('guide.donation.country_placeholder') || 'Enter your country'"
                >
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="donatorAge" class="control-label">{{ $t('guide.donation.age') || 'Age' }}</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-birthday-cake"></i>
                </span>
                <input 
                  type="number" 
                  id="donatorAge"
                  v-model="donator.age"
                  class="form-control" 
                  :placeholder="$t('guide.donation.age_placeholder') || 'Enter your age'"
                  min="1"
                  max="150"
                >
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="donation-amount-section mb-4">
        <h5 class="mb-3">
          <i class="fa fa-money mr-2"></i>
          {{ $t('guide.donation.select_amount') || 'Select Donation Amount' }}
        </h5>

        <div class="donation-amounts mb-3">
          <button 
            v-for="amount in predefinedAmounts" 
            :key="amount"
            @click="selectAmount(amount)"
            :class="['btn donation-btn', selectedAmount === amount ? 'btn-primary' : 'btn-secondary-custom btn-amount']"
          >
            {{ amount }} {{ $t('guide.donation.gel') }}
          </button>
        </div>

        <div class="custom-amount-section">
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

        <div class="selected-amount-display mt-3 text-center">
          <span class="lead">
            {{ $t('guide.donation.selected') }} <strong class="text-success">{{ displayAmount }} {{ $t('guide.donation.gel') }}</strong>
          </span>
        </div>
      </div>

      <button 
        @click="processDonation"
        :disabled="!isValidAmount || loading"
        class="btn btn-success btn-lg btn-block donate-submit-btn"
      >
        <span v-if="loading" class="fa fa-spinner fa-spin mr-2"></span>
        <i v-else class="fa fa-heart mr-2"></i>
        {{ loading ? $t('guide.donation.processing') : $t('guide.donation.donate_button') + ' ' + displayAmount + ' ' + $t('guide.donation.gel') }}
      </button>

      <div v-if="donationSuccess && checkoutUrl" class="alert alert-success mt-3 text-center">
        <i class="fa fa-check-circle mr-2"></i>
        {{ $t('guide.donation.redirecting') || 'Redirecting to payment...' }}
      </div>

      <div v-if="donationSuccess && !checkoutUrl" class="alert alert-success mt-3 text-center">
        <i class="fa fa-check-circle mr-2"></i>
        {{ $t('guide.donation.success') }}
      </div>

      <div v-if="errorMessage" class="alert alert-danger mt-3 text-center">
        <i class="fa fa-exclamation-circle mr-2"></i>
        {{ errorMessage }}
      </div>
    </div>
  </StockModal>
</template>

<script>
export default {
    name: 'DonationModalComponent',

    props: {},

    data: function() {
        return {
            is_show_donation_modal: false,
            predefinedAmounts: [5, 10, 20, 50, 100, 200],
            selectedAmount: null,
            customAmount: null,
            loading: false,
            donationSuccess: false,
            errorMessage: '',
            checkoutUrl: '',
            donator: {
                name: '',
                surname: '',
                email: '',
                phone_number: '',
                country: '',
                age: null,
            },
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

        show() {
            this.is_show_donation_modal = true;
            this.$emit('close_warning_modal');
        },

        close() {
            this.is_show_donation_modal = false;
            this.resetForm();
        },

        resetForm() {
            this.selectedAmount = null;
            this.customAmount = null;
            this.loading = false;
            this.donationSuccess = false;
            this.errorMessage = '';
            this.checkoutUrl = '';
            this.donator = {
                name: '',
                surname: '',
                email: '',
                phone_number: '',
                country: '',
                age: null,
            };
        },

        async processDonation() {
            if (!this.isValidAmount) {
                this.errorMessage = this.$t('guide.donation.invalid_amount');
                return;
            }

            this.loading = true;
            this.errorMessage = '';
            this.donationSuccess = false;
            this.checkoutUrl = '';

            const amount = parseFloat(this.displayAmount);

            const donatorData = {
                amount: amount,
            };

            if (this.donator.name) donatorData.name = this.donator.name;
            if (this.donator.surname) donatorData.surname = this.donator.surname;
            if (this.donator.email) donatorData.email = this.donator.email;
            if (this.donator.phone_number) donatorData.phone_number = this.donator.phone_number;
            if (this.donator.country) donatorData.country = this.donator.country;
            if (this.donator.age) donatorData.age = this.donator.age;

            try {
                const response = await axios.post('/set_donation/process', donatorData);

                console.log('Donation processed:', response.data);

                if (response.data.checkout_url) {
                    this.checkoutUrl = response.data.checkout_url;
                    this.donationSuccess = true;

                    setTimeout(() => {
                        window.location.href = this.checkoutUrl;
                    }, 1500);
                } else {
                    this.donationSuccess = true;
                }

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

.btn-amount:hover {
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
