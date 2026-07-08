<template>
    <stack-modal
        :show="showAddOptionModal"
        :title="$t('admin.warehouses.add_product_option_btn')"
        @close="closeAddModal()"
        :modal-class="{ ['']: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }">
            <p class="text-muted mb-3">{{ $t('admin.warehouses.add_option_hint') }}</p>

            <div v-if="availableProducts.length === 0" class="alert alert-warning">
                {{ $t('admin.warehouses.no_products_available') }}
            </div>
            <div v-else-if="availableOptions.length === 0 && newOption.product_id && !loadingOptions" class="alert alert-warning">
                {{ $t('admin.warehouses.no_options_available_for_product') }}
            </div>
            <div v-if="errors.general" class="alert alert-danger">
                {{ errors.general }}
            </div>
            <form v-else @submit.prevent="addProductOption" id="addOptionForm">
                <div class="form-group">
                    <label for="product_id">{{ $t('admin.warehouses.select_product_label') }}</label>
                    <select v-model="newOption.product_id" @change="getAvailableOptions()" class="form-control" :class="{ 'is-invalid': errors.product }" required>
                        <option value="">{{ $t('admin.warehouses.choose_a_product_placeholder') }}</option>
                        <option v-for="product in availableProducts" :key="product.id" :value="product.id">
                            {{ product.title }}
                        </option>
                    </select>
                    <div v-if="errors.product" class="invalid-feedback">{{ errors.product }}</div>
                </div>
                <div class="form-group">
                    <label for="option_id">{{ $t('admin.warehouses.select_product_option_label') }}</label>
                    <select v-model="newOption.product_option_id" class="form-control" :class="{ 'is-invalid': errors.option }" required :disabled="loadingOptions">
                        <option value="">{{ $t('admin.warehouses.choose_an_option_placeholder') }}</option>
                        <option v-for="option in availableOptions" :key="option.id" :value="option.id">
                            {{ option.name }} - {{ option.price }} {{ option.currency }}
                        </option>
                    </select>
                    <div v-if="loadingOptions" class="text-muted">{{ $t('admin.warehouses.loading_options') }}</div>
                    <div v-if="errors.option" class="invalid-feedback">{{ errors.option }}</div>
                </div>
                <div class="form-group">
                    <label for="quantity">{{ $t('admin.warehouses.col_quantity') }}</label>
                    <input type="number" v-model="newOption.quantity" class="form-control" :class="{ 'is-invalid': errors.quantity }" min="0" required>
                    <div v-if="errors.quantity" class="invalid-feedback">{{ errors.quantity }}</div>
                </div>
            </form>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeAddModal">{{ $t('admin.comments.cancel_btn') }}</button>
                    <button type="submit" class="btn btn-primary" form="addOptionForm" :disabled="availableProducts.length === 0 || (newOption.product_id && availableOptions.length === 0) || submitting">
                        <span v-if="submitting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{ submitting ? $t('admin.warehouses.adding_ellipsis') : $t('admin.warehouses.add_option_btn') }}
                    </button>
                </div>
            </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
// import StackModal from '@innologica/vue-stackable-modal'  // Global now
    export default {
        components: {
            // StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                showAddOptionModal: false,

                newOption: {
                    product_id: '',
                    product_option_id: '',
                    quantity: 1
                },

                availableProducts: [],
                availableOptions: [],
                loadingOptions: false,
                submitting: false,
                errors: {}
            }
        },

        mounted() {
            
        },

        methods: {
            show_modal(){
                this.showAddOptionModal = true;
                this.getAvailableProducts();
            },
            closeAddModal() {
                this.showAddOptionModal = false;
                this.newOption = {
                    product_id: '',
                    product_option_id: '',
                    quantity: 0
                };
                this.availableOptions = [];
            },

            getAvailableProducts() {
                axios.get(`/get_product/get_current_products/`)
                .then(response => {
                    this.availableProducts = response.data;
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
            },

            getAvailableOptions() {
                if (this.newOption.product_id) {
                    this.loadingOptions = true;
                    this.availableOptions = [];
                    axios.get(`/get_product/get_product_option/get_activ_product_options/${this.newOption.product_id}`)
                    .then(response => {
                        this.availableOptions = response.data.options || [];
                        this.loadingOptions = false;
                    })
                    .catch(error => {
                        console.error('Error fetching product options:', error);
                        this.availableOptions = [];
                        this.loadingOptions = false;
                    });
                } else {
                    this.availableOptions = [];
                }
            },

            addProductOption() {
                this.errors = {};
                if (!this.newOption.product_id) {
                    this.errors.product = this.$t('admin.warehouses.please_select_product');
                    return;
                }
                if (!this.newOption.product_option_id) {
                    this.errors.option = this.$t('admin.warehouses.please_select_product_option');
                    return;
                }
                if (this.newOption.quantity < 0) {
                    this.errors.quantity = this.$t('admin.warehouses.quantity_cannot_be_negative');
                    return;
                }

                this.submitting = true;
                axios.post('/set_warehouse/add_product_option_to_warehouse/' + this.$route.params.id, {
                    product_option_id: this.newOption.product_option_id,
                    quantity: this.newOption.quantity || 0
                })
                .then(response => {
                    this.closeAddModal();
                    this.$emit('update');
                })
                .catch(error => {
                    console.log(error);
                    this.errors.general = error.response?.data?.error || this.$t('admin.warehouses.error_adding_option');
                })
                .finally(() => {
                    this.submitting = false;
                });
            },
        }
    }
</script>

<style>

</style>