<template>
  <StackModal
    v-model="is_show_donation_modal"
    :title="$t('guide.donation.support')"
    size="lg"
    :cancelButton="{ visible: false }"
    :saveButton="{ visible: false }"
    @close="close"
  >
    <div class="modal-body p-4">

      <!-- Header -->
      <div class="text-center mb-4">
        <i class="fa fa-heart text-danger fa-2x mb-2 d-block"></i>
        <h4 class="font-bold mb-1">{{ $t('guide.donation.support_title') }}</h4>
        <span v-html="this.$siteData.data.donation_description" class="block mb-6 text-gray-700 text-lg" style="text-align: center;"></span>
      </div>

      <!-- Payment method tabs -->
      <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
          <a class="nav-link" :class="{ active: activeTab === 'card' }" href="#" @click.prevent="activeTab = 'card'">
            <i class="fa fa-credit-card mr-1"></i> {{ $t('guide.donation.card_tab') }}
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" :class="{ active: activeTab === 'bank' }" href="#" @click.prevent="switchToBank">
            <i class="fa fa-bank mr-1"></i> {{ $t('guide.donation.bank_transfer_tab') }}
          </a>
        </li>
      </ul>

      <!-- ── CARD PAYMENT TAB ── -->
      <div v-show="activeTab === 'card'">

        <!-- Donator Info -->
        <div class="bg-light p-3 rounded mb-4">
          <h5 class="font-weight-bold mb-3">
            <i class="fa fa-user mr-2 text-primary"></i>{{ $t('guide.donation.donator_info') }}
          </h5>

          <!-- Logged-in user: show read-only summary -->
          <div v-if="authUser" class="alert alert-info mb-0">
            <i class="fa fa-check-circle mr-2"></i>
            {{ $t('guide.donation.donating_as_prefix') }} <strong>{{ authUser.name }} {{ authUser.surname }}</strong> ({{ authUser.email }})
          </div>

          <!-- Guest: manual form -->
          <template v-else>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">{{ $t('common.name') }}</label>
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
                <input v-model="donator.email" type="email" class="form-control" :placeholder="$t('guide.donation.email_placeholder')">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">{{ $t('common.phone_number') }}</label>
                <input v-model="donator.phone_number" type="tel" class="form-control" :placeholder="$t('guide.donation.phone_placeholder')">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">{{ $t('common.country') }}</label>
                <input v-model="donator.country" type="text" class="form-control" :placeholder="$t('guide.donation.country_placeholder')">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">{{ $t('guide.donation.age') }}</label>
                <input v-model="donator.age" type="number" class="form-control" :placeholder="$t('guide.donation.age_placeholder')" min="1" max="150">
              </div>
            </div>
          </template>
        </div>

        <!-- Amount Selection -->
        <div class="bg-light p-3 rounded mb-4">
          <h5 class="font-weight-bold mb-3 text-center">
            <i class="fa fa-money mr-2 text-warning"></i>{{ $t('guide.donation.select_amount') }}
          </h5>

          <div class="d-flex flex-wrap justify-content-center mb-3">
            <button
              v-for="amount in predefinedAmounts"
              :key="amount"
              @click="selectAmount(amount)"

              class="btn btn-primary rounded-pill"
              :class="{ 'btn-primary text-white': selectedAmount === amount }"

              style="max-width: 100%; margin-right: 2%; margin-bottom: 10px;"
            >
              {{ amount }} {{ $t('guide.donation.gel') }}
            </button>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-success text-white"><i class="fa fa-money"></i></span>
            <input
              type="number"
              v-model="customAmount"
              @input="onCustomAmountInput"
              class="form-control"
              :placeholder="$t('guide.donation.custom_amount_placeholder')"
              min="1"
            >
            <span class="input-group-text bg-success text-white font-weight-bold">GEL</span>
          </div>

          <div class="alert alert-success text-center mb-0">
            <strong>{{ $t('guide.donation.selected') }}: {{ displayAmount }} {{ $t('guide.donation.gel') }}</strong>
          </div>
        </div>

        <!-- Optional comment -->
        <div class="mb-4">
          <label class="form-label">{{ $t('guide.donation.comment') }}</label>
          <textarea v-model="comment" class="form-control" rows="2" :placeholder="$t('guide.donation.comment_placeholder')"></textarea>
        </div>

        <!-- Donate Button -->
        <button
          @click="processDonation"
          :disabled="!isValidAmount || loading"
          class="btn btn-success btn-lg btn-block mb-3"
        >
          <span v-if="loading" class="spinner-border spinner-border-sm mr-2"></span>
          <i v-else class="fa fa-heart mr-2"></i>
          {{ $t('guide.donation.donate_button') + ' ' + displayAmount + ' ' + $t('guide.donation.gel') }}
        </button>

        <div v-if="donationSuccess" class="alert alert-success text-center">
          <i class="fa fa-check-circle mr-2"></i> {{ $t('guide.donation.success') }}
        </div>
        <div v-if="errorMessage" class="alert alert-danger text-center">
          <i class="fa fa-exclamation-circle mr-2"></i> {{ errorMessage }}
        </div>
      </div>

      <!-- ── BANK TRANSFER TAB ── -->
      <div v-show="activeTab === 'bank'">

        <div v-if="bankLoading" class="text-center py-5">
          <span class="spinner-border text-success"></span>
          <p class="mt-2 text-muted">{{ $t('guide.donation.checking_availability') }}</p>
        </div>

        <!-- Not allowed (outside Georgia) -->
        <div v-else-if="bankInfo && !bankInfo.allowed" class="text-center py-4">
          <i class="fa fa-map-marker fa-3x text-muted mb-3 d-block"></i>
          <h5 class="text-muted">{{ $t('guide.donation.bank_only_georgia') }}</h5>
          <p class="text-muted">{{ $t('guide.donation.use_card_payment_hint') }}</p>
        </div>

        <!-- Georgian user — show IBAN -->
        <div v-else-if="bankInfo && bankInfo.allowed">
          <div class="alert alert-info mb-4">
            <i class="fa fa-info-circle mr-2"></i>
            {{ $t('guide.donation.bank_transfer_instructions') }}
          </div>

          <div class="bank-detail-card p-4 rounded mb-2">
            <div class="bank-detail-row">
              <span class="bank-detail-label">{{ $t('guide.donation.bank_label') }}</span>
              <span class="bank-detail-value">{{ bankInfo.bank_name }}</span>
            </div>
            <div class="bank-detail-row">
              <span class="bank-detail-label">{{ $t('guide.donation.swift_bic_label') }}</span>
              <span class="bank-detail-value">{{ bankInfo.bank_code }}</span>
            </div>
            <div class="bank-detail-row">
              <span class="bank-detail-label">{{ $t('guide.donation.account_name_label') }}</span>
              <span class="bank-detail-value">{{ bankInfo.account_name }}</span>
            </div>
            <div class="bank-detail-row iban-row">
              <span class="bank-detail-label">{{ $t('guide.donation.iban_label') }}</span>
              <span class="bank-detail-value iban-value">{{ bankInfo.iban }}</span>
              <button class="btn btn-sm btn-outline-secondary ml-2 copy-btn" @click="copyIban" :class="{ 'btn-success text-white': ibanCopied }">
                <i class="fa" :class="ibanCopied ? 'fa-check' : 'fa-copy'"></i>
                {{ ibanCopied ? $t('guide.donation.copied_label') : $t('guide.donation.copy_label') }}
              </button>
            </div>
            <div class="bank-detail-row">
              <span class="bank-detail-label">{{ $t('guide.donation.currency_label') }}</span>
              <span class="bank-detail-value">{{ $t('guide.donation.currency_value') }}</span>
            </div>
          </div>

          <div class="alert alert-warning mt-3 mb-0">
            <i class="fa fa-exclamation-triangle mr-2"></i>
            {{ $t('guide.donation.payment_description_prefix') }} <strong>donation</strong> {{ $t('guide.donation.payment_description_suffix') }}
          </div>
        </div>

        <div v-else-if="bankError" class="alert alert-danger text-center">
          <i class="fa fa-exclamation-circle mr-2"></i> {{ bankError }}
        </div>

      </div>

    </div>
  </StackModal>
</template>

<script>
export default {
    name: 'DonationModalComponent',

    data: function() {
        return {
            is_show_donation_modal: false,
            activeTab: 'card',

            predefinedAmounts: [5, 10, 20, 50, 100, 200],
            selectedAmount: null,
            customAmount: null,
            loading: false,
            donationSuccess: false,
            errorMessage: '',
            authUser: null,
            donator: {
                name: '',
                surname: '',
                email: '',
                phone_number: '',
                country: '',
                age: null,
            },

            comment: '',

            bankLoading: false,
            bankInfo: null,
            bankError: '',
            ibanCopied: false,
        };
    },

    computed: {
        displayAmount() {
            if (this.selectedAmount !== null) return this.selectedAmount;
            if (this.customAmount !== null && this.customAmount !== '') return parseFloat(this.customAmount).toFixed(2);
            return '0';
        },
        isValidAmount() {
            return parseFloat(this.displayAmount) > 0;
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
            axios.get('auth_user').then(r => { this.authUser = r.data; }).catch(() => { this.authUser = null; });
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
            this.activeTab = 'card';
            this.bankInfo = null;
            this.bankError = '';
            this.ibanCopied = false;
            this.donator = { name: '', surname: '', email: '', phone_number: '', country: '', age: null };
            this.comment = '';
        },

        switchToBank() {
            this.activeTab = 'bank';
            if (!this.bankInfo && !this.bankLoading) {
                this.fetchBankInfo();
            }
        },

        fetchBankInfo() {
            this.bankLoading = true;
            this.bankError = '';
            axios.get('get_donation/tbc_info')
                .then(response => { this.bankInfo = response.data; })
                .catch(() => { this.bankError = this.$t('guide.donation.bank_transfer_error'); })
                .finally(() => { this.bankLoading = false; });
        },

        copyIban() {
            if (!this.bankInfo?.iban) return;
            navigator.clipboard.writeText(this.bankInfo.iban).then(() => {
                this.ibanCopied = true;
                setTimeout(() => { this.ibanCopied = false; }, 1000);
            });
        },

        processDonation() {
            if (!this.isValidAmount) {
                this.errorMessage = this.$t('guide.donation.invalid_amount');
                return;
            }
            this.loading = true;
            this.errorMessage = '';

            const payload = { amount: parseFloat(this.displayAmount), comment: this.comment };
            if (!this.authUser) {
                Object.assign(payload, this.donator);
            }

            axios.post('set_donation/process', payload)
                .then(response => {
                    if (response.data.checkout_url) {
                        window.location.href = response.data.checkout_url;
                    } else {
                        this.donationSuccess = true;
                        this.$bus.$emit('donation-completed');
                        setTimeout(() => { this.close(); }, 1000);
                    }
                })
                .catch(err => {
                    this.errorMessage = err?.response?.data?.message || this.$t('guide.donation.error');
                })
                .finally(() => { this.loading = false; });
        },
    },
};
</script>

<style scoped>
.modal-body {
  max-height: 80vh;
  overflow-y: auto;
}

.bank-detail-card {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
}

.bank-detail-row {
  display: flex;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #e9ecef;
}
.bank-detail-row:last-child {
  border-bottom: none;
}

.bank-detail-label {
  width: 140px;
  font-weight: 600;
  color: #495057;
  flex-shrink: 0;
}

.bank-detail-value {
  flex: 1;
  color: #212529;
}

.iban-value {
  font-family: monospace;
  font-size: 1.05rem;
  letter-spacing: 1px;
  font-weight: 600;
}

.copy-btn {
  flex-shrink: 0;
}

@media (max-width: 576px) {
  .iban-row {
    flex-wrap: wrap;
    gap: 6px;
  }
  .bank-detail-label {
    width: 110px;
  }
}
</style>
