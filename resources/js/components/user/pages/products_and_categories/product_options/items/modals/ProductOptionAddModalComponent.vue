<template>
    <stack-modal
            :show="is_add_option_modal"
            size="xxl"
            :title="$t('admin.shop.add_option_title')"
            @close="close_option_add_model"
            @save="add_option"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
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
                        <form v-on:submit.prevent="add_option" id="add_new_option">
                            <label class="form-label">{{ $t('common.name') }}</label>
                            <input type="text" v-model="local_adding_data.name" class="form-control mb-3" :placeholder="$t('common.name')" required>

                            <label class="form-label">{{ $t('admin.shop.price_label') }}</label>
                            <input type="number" v-model="local_adding_data.price" class="form-control mb-3" :placeholder="$t('admin.shop.price_label')" required>

                            <label class="form-label">{{ $t('admin.shop.currency_label') }}</label>
                            <select class="form-control mb-3" v-model="local_adding_data.currency">
                                <option value="₾">₾</option>
                                <option value="$" disabled>$</option>
                                <option value="€" disabled>€</option>
                            </select>

                            <label class="form-label">{{ $t('admin.shop.discount_percent_label') }}</label>
                            <input type="number" max="100" min="0" v-model="local_adding_data.discount" class="form-control mb-3" :placeholder="$t('admin.shop.discount_col')">

                            <label class="form-label">{{ $t('admin.shop.barcode_label') }}</label>
                            <div v-if="!local_adding_data.barcode" class="mb-3">
                                <div class="d-flex gap-2">
                                    <input type="text" v-model="barcode_input" class="form-control" :placeholder="$t('admin.shop.scan_barcode_placeholder')" @keydown.enter.prevent="set_barcode_from_input">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" @click="set_barcode_from_input" :disabled="!barcode_input">{{ $t('admin.shop.set_btn') }}</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" @click="generate_barcode">{{ $t('admin.shop.generate_btn') }}</button>
                                </div>
                            </div>
                            <div v-else class="mb-3">
                                <div class="d-flex align-items-center gap-2 bg-light border rounded p-2">
                                    <code class="flex-grow-1 fw-bold text-primary">{{ local_adding_data.barcode }}</code>
                                    <button type="button" class="btn btn-sm btn-outline-danger" @click="clear_barcode">{{ $t('admin.shop.clear_btn') }}</button>
                                </div>
                                <barcode-image :code="local_adding_data.barcode" class="mt-2" />
                            </div>
                        </form>
                    </div>

                    <!-- Right: image gallery -->
                    <div class="col-md-7">
                        <gallery_images_add
                            :title_prop="$t('admin.shop.option_images_title')"
                            :crop_ratio_prop="{ width: 16, height: 9 }"
                            @update_gallery_images="$emit('update_adding_images', $event)"
                        />
                    </div>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import BarcodeImage from '../BarcodeImageComponent.vue'
import gallery_images_add from '../../../../../items/gallery/galleryImageAddComponent.vue'

export default {
    components: { BarcodeImage, gallery_images_add },
    props: {
        is_add_option_modal: { type: Boolean, required: true },
        adding_data: {
            type: Object,
            default: () => ({ name: '', price: 0, currency: '₾', discount: 0, barcode: '' })
        },
        is_loading: { type: Boolean, default: false }
    },
    data() {
        return { barcode_input: '' }
    },
    computed: {
        local_adding_data: {
            get() { return this.adding_data; }
        }
    },
    methods: {
        generate_barcode() {
            let digits = '';
            for (let i = 0; i < 12; i++) digits += Math.floor(Math.random() * 10).toString();
            let sum = 0;
            for (let i = 0; i < 12; i++) sum += parseInt(digits[i]) * (i % 2 === 0 ? 1 : 3);
            this.local_adding_data.barcode = digits + ((10 - (sum % 10)) % 10);
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
        add_option() {
            this.$emit('add_option')
        }
    }
}
</script>

<style scoped>
.form-label { font-weight: 500; margin-bottom: 4px; }
</style>
