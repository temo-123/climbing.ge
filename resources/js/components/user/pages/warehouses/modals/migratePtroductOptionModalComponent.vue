<template>
    <stack-modal
        :show="showMigrateModal"
        title="Add Product Option"
        @close="closeMigrateModal()"
        :modal-class="{ ['']: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <pre class="language-vue">
            <form @submit.prevent="confirmMigrate" id="migrateForm">
                <div class="form-group">
                    <label for="to_warehouse_id">Select Target Warehouse</label>
                    <select v-model="migrateData.to_warehouse_id" class="form-control" required>
                        <option value="">Choose a warehouse...</option>
                        <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">
                            {{ warehouse.name }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" v-model="migrateData.quantity" class="form-control" min="0" :max="maxQuantity" required>
                </div>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeMigrateModal">Cancel</button>
                <button type="submit" form="migrateForm" class="btn btn-primary">Migrate</button>
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
                maxQuantity: 0
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
                this.showMigrateModal = true;

                this.maxQuantity = maxQuantity;
            },

            closeMigrateModal() {
                this.showMigrateModal = false;
                this.migrateData = {
                    to_warehouse_id: '',
                    quantity: 0
                };
            },

            confirmMigrate() {
                if (!this.migrateData.to_warehouse_id || this.migrateData.quantity < 0) {
                    alert('Please select a warehouse and enter a valid quantity');
                    return;
                }

                axios.post(`/warehouse/migrate_product_option/${this.$route.params.id}/${this.migrateData.product_option_id}`, {
                    to_warehouse_id: this.migrateData.to_warehouse_id,
                    quantity: this.migrateData.quantity
                })
                .then(response => {
                    this.closeMigrateModal();
                    this.$emit('update');
                })
                .catch(error => {
                    alert(error);
                });
            }
        }
    }
</script>

<style>

</style>