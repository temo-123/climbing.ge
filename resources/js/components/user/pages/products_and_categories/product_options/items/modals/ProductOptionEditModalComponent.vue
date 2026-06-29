<template>
<stack-modal
            :show="is_edit_option_modal"
            size="xxl"
            :title="'Edit option'"
            @close="close_option_edit_model"
            @save="edit_option"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
            <div>
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <div v-show="!is_loading" class="row g-4">
                    <!-- Left: form fields -->
                    <div class="col-md-5">
                        <form v-on:submit.prevent="edit_option" id="edit_new_option">
                            <label class="form-label">Name</label>
                            <input type="text" v-model="local_editing_data.name" class="form-control mb-3" placeholder="Name" required>

                            <label class="form-label">Price</label>
                            <input type="number" v-model="local_editing_data.price" class="form-control mb-3" placeholder="Price" required>

                            <label class="form-label">Currency</label>
                            <select class="form-control mb-3" v-model="local_editing_data.currency">
                                <option value="₾">₾</option>
                                <option value="$" disabled>$</option>
                                <option value="€" disabled>€</option>
                            </select>

                            <label class="form-label">Discount (%)</label>
                            <input type="number" max="100" min="0" v-model="local_editing_data.discount" class="form-control mb-3" placeholder="Discount">

                            <label class="form-label">Barcode</label>
                            <div v-if="!local_editing_data.barcode" class="mb-3">
                                <div class="d-flex gap-2">
                                    <input type="text" v-model="barcode_input" class="form-control" placeholder="Scan barcode here..." @keydown.enter.prevent="set_barcode_from_input">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" @click="set_barcode_from_input" :disabled="!barcode_input">Set</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" @click="generate_barcode">Generate</button>
                                </div>
                            </div>
                            <div v-else class="mb-3">
                                <div class="d-flex align-items-center gap-2 bg-light border rounded p-2">
                                    <code class="flex-grow-1 fw-bold text-primary">{{ local_editing_data.barcode }}</code>
                                    <button type="button" class="btn btn-sm btn-outline-danger" @click="clear_barcode">✕ Clear</button>
                                </div>
                                <barcode-image :code="local_editing_data.barcode" class="mt-2" />
                            </div>
                        </form>
                    </div>

                    <!-- Right: images -->
                    <div class="col-md-7">
                        <gallery_images_edit
                            title_prop="Option Images"
                            image_path_prop="images/product_option_img/"
                            get_images_route_prop="/set_product/set_product_option/get_option_images/"
                            image_del_route_prop="/set_product/set_product_option/del_option_image/"
                            :item_id_prop="local_editing_data.id"
                            @update_gallery_images="$emit('update_adding_images', $event)"
                        />
                    </div>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import BarcodeImage from '../BarcodeImageComponent.vue'
import gallery_images_edit from '../../../../../items/gallery/galleryImageEditComponent.vue'

export default {
    components: { BarcodeImage, gallery_images_edit },
    props: {
        is_edit_option_modal: { type: Boolean, required: true },
        editing_data: { type: Object, default: () => ({}) },
        is_loading: { type: Boolean, default: false },
        is_loading_editing_modal: { type: Boolean, default: false },
    },
    data() {
        return { barcode_input: '' }
    },
    computed: {
        local_editing_data: {
            get() { return this.editing_data; }
        }
    },
    methods: {
        generate_barcode() {
            let digits = '';
            for (let i = 0; i < 12; i++) digits += Math.floor(Math.random() * 10).toString();
            let sum = 0;
            for (let i = 0; i < 12; i++) sum += parseInt(digits[i]) * (i % 2 === 0 ? 1 : 3);
            this.local_editing_data.barcode = digits + ((10 - (sum % 10)) % 10);
            this.barcode_input = '';
        },
        set_barcode_from_input() {
            if (this.barcode_input.trim()) {
                this.local_editing_data.barcode = this.barcode_input.trim();
                this.barcode_input = '';
            }
        },
        clear_barcode() {
            this.local_editing_data.barcode = '';
            this.barcode_input = '';
        },
        close_option_edit_model() {
            this.barcode_input = '';
            this.$emit('close_option_edit_model');
        },
        edit_option() { this.$emit('edit_option'); },
    }
}
</script>

<style scoped>
.form-label { font-weight: 500; margin-bottom: 4px; }
</style>
