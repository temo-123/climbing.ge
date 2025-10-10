<template>
    <stack-modal
        :show="showMigrateModal"
        title="Migrate Product Option"
        @close="closeMigrateModal()"
        :modal-class="{ ['']: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
            <p class="text-muted mb-3">Move this product option to another warehouse with the specified quantity. If the option already exists in the target warehouse, the quantity will be updated.</p>

            <div v-if="errors.general" class="alert alert-danger">
                {{ errors.general }}
            </div>
            <form @submit.prevent="confirmMigrate" id="migrateForm">
                <div class="form-group">
                    <label for="to_warehouse_id">Select Target Warehouse</label>
                    <select v-model="migrateData.to_warehouse_id" class="form-control" :class="{ 'is-invalid': errors.warehouse }" required>
                        <option value="">Choose a warehouse...</option>
                        <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">
                            {{ warehouse.name }}
                        </option>
                    </select>
                    <div v-if="errors.warehouse" class="invalid-feedback">{{ errors.warehouse }}</div>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity (max: {{ maxQuantity }})</label>
                    <input type="number" v-model="migrateData.quantity" class="form-control" :class="{ 'is-invalid': errors.quantity }" min="0" :max="maxQuantity" required>
                    <div v-if="errors.quantity" class="invalid-feedback">{{ errors.quantity }}</div>
                </div>
            </form>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeMigrateModal">Cancel</button>
                    <button type="submit" form="migrateForm" class="btn btn-primary" :disabled="submitting">
                        <span v-if="submitting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{ submitting ? 'Migrating...' : 'Migrate' }}
                    </button>
                </div>
            </div>
    </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                showMigrateModal: false,
                migrateData: {
                    to_warehouse_id: '',
                    quantity: 0
                },
                warehouses: [],
                maxQuantity: 0,
                submitting: false,
                errors: {}
            }
        },

        mounted() {
                this.getWarehouses();
        },

        methods: {
            getWarehouses() {
                axios.get('/warehouse/get_warehouses')
                .then(response => {
                    this.warehouses = response.data.filter(w => w.id != this.$route.params.id);
                })
                .catch(error => {
                    console.error('Error fetching warehouses:', error);
                });
            },

            show_modal(option, maxQuantity) {
                this.migrateData.product_option_id = option.id;
                this.maxQuantity = maxQuantity;
                this.migrateData.quantity = maxQuantity; // Default to max
                this.showMigrateModal = true;
            },

            closeMigrateModal() {
                this.showMigrateModal = false;
                this.migrateData = {
                    to_warehouse_id: '',
                    quantity: 0
                };
            },

            confirmMigrate() {
                this.errors = {};
                if (!this.migrateData.to_warehouse_id) {
                    this.errors.warehouse = 'Please select a target warehouse.';
                    return;
                }
                if (this.migrateData.quantity < 0) {
                    this.errors.quantity = 'Quantity cannot be negative.';
                    return;
                }
                if (this.migrateData.quantity > this.maxQuantity) {
                    this.errors.quantity = 'Quantity cannot exceed the available amount.';
                    return;
                }

                this.submitting = true;
                axios.post(`/warehouse/migrate_product_option/${this.$route.params.id}/${this.migrateData.product_option_id}`, {
                    to_warehouse_id: this.migrateData.to_warehouse_id,
                    quantity: this.migrateData.quantity
                })
                .then(response => {
                    this.closeMigrateModal();
                    this.$emit('update');
                })
                .catch(error => {
                    this.errors.general = error.response?.data?.error || 'An error occurred while migrating the option.';
                })
                .finally(() => {
                    this.submitting = false;
                });
            }
        }
    }
</script>

<style>

</style>