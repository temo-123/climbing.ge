<template>
<StackModal
    v-model="is_show_donation_modal"
    title="Donation"
    :saveButton="{ visible: false }"
    :cancelButton="{
      visible: true,
      title: 'Close', 
      btnClass: { 'btn btn-secondary': true }
    }"
    @close="close_modal"
  >
    <div class="p-6">
      <!-- Donation form content -->
      <div class="text-center">
        <h4>{{ $t('donation.title') }}</h4>
        <p>{{ $t('donation.message') }}</p>
        <!-- Add payment buttons etc. -->
      </div>
    </div>
  </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_show_donation_modal: false,
        };
    },
    methods: {
        show_modal() {
            this.is_show_donation_modal = true;
        },
        close_modal() {
            this.is_show_donation_modal = false;
        },
    },
};
</script>
