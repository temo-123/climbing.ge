<template>
    <stack-modal
        :show="showAddOptionModal"
        title="Add Product Option"
        @close="closeAddModal()"
        :modal-class="{ ['']: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }">
        <pre class="language-vue">
            <div v-if="availableProducts.length === 0" class="alert alert-warning">
                No products available. Please add products first.
            </div><div v-else-if="availableOptions.length === 0 && newOption.product_id" class="alert alert-warning">
                No options available for the selected product.
            </div><form v-else @submit.prevent="addProductOption" id="addOptionForm">
                <div class="form-group">
                    <label for="product_id">Select Product</label>
                    <select v-model="newOption.product_id" @change="getAvailableOptions()" class="form-control" required>
                        <option value="">Choose a product...</option>
                        <option v-for="product in availableProducts" :key="product.product.id" :value="product.product.id">
                            {{ product.product.url_title }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="option_id">Select Product Option</label>
                    <select v-model="newOption.product_option_id" class="form-control" required>
                        <option value="">Choose an option...</option>
                        <option v-for="option in availableOptions" :key="option.id" :value="option.id">
                            {{ option.name }} - {{ option.price }} {{ option.currency }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" v-model="newOption.quantity" class="form-control" min="0" required>
                </div>
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeAddModal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="addOptionForm" :disabled="availableProducts.length === 0 || (newOption.product_id && availableOptions.length === 0)">Add Option</button>
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
                showAddOptionModal: false,

                newOption: {
                    product_id: '',
                    product_option_id: '',
                    quantity: 0
                },

                availableProducts: [],
                availableOptions: [],
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
                axios.get(`/product/get_all_products/`)
                .then(response => {
                    this.availableProducts = response.data;
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
            },

            getAvailableOptions() {
                // alert(this.newOption.product_id)
                if (this.newOption.product_id) {
                    axios.get(`/product_option/get_activ_product_options/${this.newOption.product_id}`)
                    .then(response => {
                        this.availableOptions = response.data.options || [];
                    })
                    .catch(error => {
                        console.error('Error fetching product options:', error);
                        this.availableOptions = [];
                    });
                }
            },

            addProductOption() {
                if (!this.newOption.product_id || !this.newOption.product_option_id) {
                    alert('Please select both product and product option');
                    return;
                }

                axios.post('/warehouse/add_product_option_to_warehouse/' + this.$route.params.id, {
                    product_option_id: this.newOption.product_option_id,
                    quantity: this.newOption.quantity || 0
                })
                .then(response => {
                    this.closeAddModal();
                    this.$emit('update');
                })
                .catch(error => {
                    console.log(error);
                    
                    alert(error.error);
                });
            },
        }
    }
</script>

<style>

</style>