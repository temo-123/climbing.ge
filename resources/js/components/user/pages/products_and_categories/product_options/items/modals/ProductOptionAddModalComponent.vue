<template>
    <stack-modal
            :show="is_add_option_modal"
            :title="'Add option'" 
            @close="close_option_add_model"
            @save="add_option"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <div>
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <form v-on:submit.prevent="add_option" id="add_new_option"  v-show="!is_loading">
                    Name
                    <input type="text" v-model="local_adding_data.name" class="form-control" name="name" placeholder="Name" title="enter your name" required>
                    Price
                    <input type="number" v-model="local_adding_data.price" class="form-control" name="price" placeholder="Price" title="enter price" required>
                    Currency
                    <select class="form-control" v-model="local_adding_data.currency" name="currency">
                        <option value="₾">₾</option>
                        <option value="$" disabled>$</option>
                        <option value="€" disabled>€</option>
                    </select>
                    Discount (%)
                    <input type="number" max="100" min="0" v-model="local_adding_data.discount" class="form-control" name="discount" placeholder="Discount" title="enter discount">
                    Barcode
                    <div v-if="!local_adding_data.barcode" class="barcode-empty-state">
                        <div class="barcode-actions">
                            <input type="text" v-model="barcode_input" class="form-control barcode-input" placeholder="Scan barcode here..." @keydown.enter.prevent="set_barcode_from_input">
                            <button type="button" class="btn btn-outline-secondary" @click="set_barcode_from_input" :disabled="!barcode_input">
                                <i class="fa fa-check"></i> Set
                            </button>
                            <button type="button" class="btn btn-outline-primary" @click="generate_barcode">
                                <i class="fa fa-barcode"></i> Generate
                            </button>
                        </div>
                    </div>
                    <div v-else class="barcode-set-state">
                        <div class="barcode-display">
                            <span class="barcode-value">{{ local_adding_data.barcode }}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="clear_barcode">
                                <i class="fa fa-times"></i> Clear
                            </button>
                        </div>
                        <barcode-image :code="local_adding_data.barcode" />
                    </div>
                </form>

                <div v-show="!is_loading">
                    <button class="btn btn-primary float-left" @click="add_new_option_image_value()">Add new mount route image</button>
                    <div v-if="adding_option_images.length > 0">
                        <table class="table table-hover" id="dev-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>|</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="option_image in adding_option_images" :key="option_image.id">
                                    <td>
                                        <form ref="myForm">
                                            <input type="file" name="image" id="image" v-on:change="onFileChange($event, option_image.id)">
                                        </form> 
                                    </td>
                                    <td>|</td>
                                    <td>
                                        <button class="btn btn-danger" @click="del_option_image(option_image.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <div v-else>
                        <p class="text-muted mt-2">No images added yet</p>
                    </div>
                </div>
            </div>
            <!-- <div slot="modal-footer">
                <div class="modal-footer">
                    <button type="submit" form="add_new_option" class="btn btn-primary">Add new option</button>
                </div>
            </div> -->
        </stack-modal>
</template>

<script>
// import StackModal from '@innologica/vue-stackable-modal'  // Global now
import BarcodeImage from '../BarcodeImageComponent.vue'

export default {
    components: {
        BarcodeImage
    },
    props: {
        is_add_option_modal: {
            type: Boolean,
            required: true
        },
        adding_data: {
            type: Object,
            default: () => ({
                name: '',
                price: 0,
                currency: '₾',
                discount: 0,
                barcode: ''
            })
        },
        adding_option_images: {
            type: Array,
            default: () => []
        },
        is_loading: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            barcode_input: ''
        }
    },
    computed: {
        local_adding_data: {
            get() {
                return this.adding_data;
            }
        }
    },
    methods: {
        generate_barcode() {
            let digits = '';
            for (let i = 0; i < 12; i++) {
                digits += Math.floor(Math.random() * 10).toString();
            }
            let sum = 0;
            for (let i = 0; i < 12; i++) {
                sum += parseInt(digits[i]) * (i % 2 === 0 ? 1 : 3);
            }
            const check = (10 - (sum % 10)) % 10;
            this.local_adding_data.barcode = digits + check;
            this.barcode_input = '';
        },
        set_barcode_from_input() {
            if (this.barcode_input.trim()) {
                this.local_adding_data.barcode = this.barcode_input.trim();
                this.barcode_input = '';
            }
        },
        clear_barcode() {
            this.local_adding_data.barcode = '';
            this.barcode_input = '';
        },
        close_option_add_model() {
            this.barcode_input = '';
            this.$emit('close_option_add_model')
        },
        add_new_option_image_value() {
            this.$emit('add_new_option_image_value')
        },
        onFileChange(event, imageId) {
            this.$emit('onFileChange', event, imageId)
        },
        del_option_image(imageId) {
            this.$emit('del_option_image', imageId)
        },
        add_option() {
            this.$emit('add_option')
        }
    }
}
</script>

<style scoped>
.language-vue { padding: 15px; }
.form-control { margin-bottom: 15px; }
.table { margin-top: 15px; }
.table th { background-color: #f5f5f5; font-weight: 600; text-align: center; }
.table td { vertical-align: middle; text-align: center; }
.float-left { float: left; }
.modal-footer { width: 100%; }

.barcode-empty-state { margin-bottom: 15px; }
.barcode-actions {
    display: flex;
    gap: 6px;
    align-items: center;
}
.barcode-input {
    margin-bottom: 0;
    flex: 1;
}
.barcode-set-state { margin-bottom: 15px; }
.barcode-display {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f0f4ff;
    border: 1px solid #c0cfff;
    border-radius: 6px;
    padding: 8px 12px;
    margin-bottom: 6px;
}
.barcode-value {
    font-family: monospace;
    font-size: 15px;
    font-weight: 600;
    color: #2c4a9e;
    flex: 1;
}
</style>

