<template>
  <StackModal
    v-model="is_show_donation_modal"
    title="Support"
    size="lg"
    :cancelButton="{ visible: true, title: $t('guide.close'), btnClass: { 'btn btn-secondary': true } }"
    @close="close"
  >
    <div class="modal-body p-4 md:p-8">
      
      <!-- Header -->
      <div class="text-center mb-8">
        <i class="fa fa-heart text-4xl text-danger mb-4 block"></i>
        <h4 class="text-2xl font-bold mb-2">{{ $t('guide.donation.support_title') }}</h4>
        <p class="text-muted">{{ $t('guide.donation.description') }}</p>
      </div>

      <!-- Donator Info -->
      <div class="bg-light p-6 rounded mb-6">
        <h5 class="font-weight-bold mb-5">
          <i class="fa fa-user mr-2 text-primary"></i>{{ $t('guide.donation.donator_info') }}
        </h5>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ $t('guide.donation.name') }}</label>
            <input v-model="donator.name" type="text" class="form-control" :placeholder="$t('guide.donation.name_placeholder')">
          </div>
          
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ $t('guide.donation.surname') }}</label>
            <input v-model="donator.surname" type="text" class="form-control" :placeholder="$t('guide.donation.surname_placeholder')">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">{{ $t('guide.donation.email') }} *</label>
            <input v-model="donator.email" type="email" class="form-control" :placeholder="$t('guide.donation.email_placeholder')" required>
          </div>
          
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ $t('guide.donation.phone') }}</label>
            <input v-model="donator.phone_number" type="tel" class="form-control" :placeholder="$t('guide.donation.phone_placeholder')">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ $t('guide.donation.country') }}</label>
            <input v-model="donator.country" type="text" class="form-control" :placeholder="$t('guide.donation.country_placeholder')">
          </div>
          
          <div class="col-md-6 mb-3">
            <label class="form-label">{{ $t('guide.donation.age') }}</label>
            <input v-model="donator.age" type="number" class="form-control" :placeholder="$t('guide.donation.age_placeholder')" min="1" max="150">
          </div>
        </div>
      </div>

      <!-- Amount Selection -->
      <div class="bg-light p-6 rounded mb-6">
        <h5 class="font-weight-bold mb-5 text-center">
          <i class="fa fa-money mr-2 text-warning"></i>{{ $t('guide.donation.select_amount') }}
        </h5>

        <div class="d-flex flex-wrap gap-3 mb-6 justify-content-center donation_button_group">
          <button 
            v-for="amount in predefinedAmounts" 
            :key="amount"
            @click="selectAmount(amount)"
            class="btn btn-outline-primary btn-lg px-5 py-4 m-2 rounded-pill shadow-sm hover:shadow-md transition-shadow donation_btn"
            :class="{ 'btn-primary text-white shadow-lg': selectedAmount === amount }"
          >
            {{ amount }} {{ $t('guide.donation.gel') }}
          </button>
        </div>

        <div class="input-group mb-6">
          <span class="input-group-text bg-success text-white border-0 px-4" style="min-width: 55px;">
            <i class="fa fa-money"></i>
          </span>
          <input 
            type="number" 
            v-model="customAmount"
            @input="onCustomAmountInput"
            class="form-control form-control-lg border-start-0 rounded-end-pill"
            placeholder="Enter custom amount (GEL)"
            min="1"
          >
          <span class="input-group-text bg-success text-white border-0 rounded-end-pill px-4" style="min-width: 70px; font-weight: bold;">
            GEL
          </span>
        </div>

        <div class="alert alert-success text-center p-4 rounded-pill">
          <strong>{{ $t('guide.donation.selected') }}: {{ displayAmount }} {{ $t('guide.donation.gel') }}</strong>
        </div>
      </div>

      <!-- Donate Button -->
      <button 
        @click="processDonation"
        :disabled="!isValidAmount || loading"
        class="btn btn-success btn-lg btn-block mb-4 px-6 py-5 font-weight-bold rounded-pill shadow-lg border-0 position-relative overflow-hidden"
        style="background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #28a745 100%) !important; font-size: 1.25rem;"
      >
        <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
        <i v-else class="fa fa-heart me-3"></i>
        {{ loading ? $t('guide.donation.processing') : $t('guide.donation.donate_button') }} {{ displayAmount }} {{ $t('guide.donation.gel') }}
        <span class="position-absolute top-0 start-0 w-100 h-100 opacity-0 hover:opacity-100 transition-opacity d-flex align-items-center justify-content-center">
          <i class="fa fa-arrow-right fa-2x"></i>
        </span>
      >
        <i v-if="loading" class="fa fa-spinner fa-spin mr-2"></i>
        <i v-else class="fa fa-heart mr-2"></i>
        {{ loading ? $t('guide.donation.processing') : $t('guide.donation.donate_button') + ' ' + displayAmount + ' ' + $t('guide.donation.gel') }}
      </button>

      <!-- Messages -->
      <div v-if="donationSuccess && checkoutUrl" class="alert alert-success text-center p-4 rounded">
        <i class="fa fa-check-circle fa-2x mr-2 text-success"></i>
        {{ $t('guide.donation.redirecting') }}
      </div>

      <div v-if="donationSuccess && !checkoutUrl" class="alert alert-success text-center p-4 rounded">
        <i class="fa fa-check-circle fa-2x mr-2 text-success"></i>
        {{ $t('guide.donation.success') }}
      </div>

      <div v-if="errorMessage" class="alert alert-danger text-center p-4 rounded">
        <i class="fa fa-exclamation-circle fa-2x mr-2 text-danger"></i>
        {{ errorMessage }}
      </div>
    </div>
  </StackModal>
</template>

<script>
import axios from 'axios';
// import StackModal from '@innologica/vue-stackable-modal'  // Global

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
                this.errorMessage = $t('guide.donation.invalid_amount');
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
                this.errorMessage = error.response?.data?.message || $t('guide.donation.error');
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.modal-body {
  max-height: 80vh;
  overflow-y: auto;
}

.donation-amounts {
  max-width: 500px;
  margin: 0 auto;
}

@media (max-width: 768px) {
  .modal-body {
    padding: 1.5rem;
  }
  
  .btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
  }
}
.donation_button_group{
  margin-bottom: 1.5rem;
}
.donation_btn {
  margin: 0.2rem; 
}
</style>
