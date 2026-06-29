<template>
    <stack-modal
        :show="is_add_modal"
        size="xxl"
        title="Add Combinate Product Option"
        @close="close"
        @save="save"
        :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false }"
    >
        <div v-show="is_loading" class="row justify-content-center">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div v-show="!is_loading" class="row g-3">

            <!-- Col 1: fields -->
            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input type="text" v-model="local_data.name" class="form-control mb-3" placeholder="Name">

                <label class="form-label">Price</label>
                <input type="number" v-model="local_data.price" class="form-control mb-3" placeholder="Price">

                <label class="form-label">Currency</label>
                <select class="form-control mb-3" v-model="local_data.currency">
                    <option value="₾">₾</option>
                    <option value="$" disabled>$</option>
                    <option value="€" disabled>€</option>
                </select>

                <label class="form-label">Discount (%)</label>
                <input type="number" min="0" max="100" v-model="local_data.discount" class="form-control mb-3" placeholder="Discount">

                <label class="form-label">Barcode</label>
                <div v-if="!local_data.barcode" class="mb-3">
                    <div class="d-flex gap-2">
                        <input type="text" v-model="barcode_input" class="form-control"
                               placeholder="Scan or type..." @keydown.enter.prevent="set_barcode_from_input">
                        <button type="button" class="btn btn-outline-secondary btn-sm" @click="set_barcode_from_input" :disabled="!barcode_input">Set</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" @click="generate_barcode">Gen</button>
                    </div>
                </div>
                <div v-else class="mb-3">
                    <div class="d-flex align-items-center gap-2 bg-light border rounded p-2">
                        <code class="flex-grow-1 fw-bold text-primary">{{ local_data.barcode }}</code>
                        <button type="button" class="btn btn-sm btn-outline-danger" @click="local_data.barcode = ''">✕</button>
                    </div>
                </div>

                <!-- Selected tags -->
                <div v-if="local_data.selected_options.length > 0">
                    <label class="form-label">Selected options ({{ local_data.selected_options.length }})</label>
                    <div class="d-flex flex-wrap gap-1">
                        <span v-for="opt in local_data.selected_options" :key="opt.id" class="opt-tag">
                            {{ opt.name }}
                            <span class="opt-tag-x" @click="remove_option(opt.id)">✕</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Col 2: product select + options -->
            <div class="col-md-4">
                <label class="form-label">Select Product</label>
                <select class="form-control mb-3" v-model="selected_product_id"
                        @change="on_product_change" :disabled="is_loading_products">
                    <option value="">— Choose a product —</option>
                    <option v-for="p in products_list" :key="p.id" :value="p.id">{{ p.title }}</option>
                </select>

                <div v-if="is_loading_options" class="text-center py-3">
                    <img :src="'/images/site_img/loading.gif'" style="width:28px;" alt="">
                </div>
                <div v-else-if="selected_product_id && product_options.length === 0" class="text-muted py-2">
                    No options for this product
                </div>
                <div v-else-if="product_options.length > 0" class="options-list border rounded">
                    <div v-for="opt in product_options" :key="opt.id"
                         class="opt-row d-flex align-items-center gap-2 p-2 border-bottom"
                         :class="{ 'opt-selected': is_selected(opt.id) }"
                         @click="toggle_option(opt)">
                        <input type="checkbox" :checked="is_selected(opt.id)" @click.stop="toggle_option(opt)">
                        <div>
                            <div class="fw-bold" style="font-size:13px;">{{ opt.name }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ opt.price }} {{ opt.currency }}</div>
                        </div>
                    </div>
                </div>
                <small class="text-muted">Select a product then tick its options. Switch products to pick from multiple.</small>
            </div>

            <!-- Col 3: images -->
            <div class="col-md-4">
                <gallery_images_add
                    title_prop="Combination Images"
                    @update_gallery_images="$emit('update_adding_images', $event)"
                />
            </div>
        </div>
    </stack-modal>
</template>

<script>
import axios from 'axios'
import gallery_images_add from '../../../../../items/gallery/galleryImageAddComponent.vue'

export default {
    components: { gallery_images_add },
    props: {
        is_add_modal: { type: Boolean, required: true },
        adding_data: { type: Object, default: () => ({ name: '', price: 0, currency: '₾', discount: 0, barcode: '', selected_options: [] }) },
        is_loading: { type: Boolean, default: false },
        product_id: { type: [String, Number], required: true }
    },
    data() {
        return {
            barcode_input: '',
            selected_product_id: '',
            products_list: [],
            product_options: [],
            is_loading_products: false,
            is_loading_options: false
        }
    },
    computed: { local_data() { return this.adding_data } },
    watch: {
        is_add_modal(val) { if (val) this.load_products() }
    },
    methods: {
        async load_products() {
            this.is_loading_products = true
            try {
                const res = await axios.get('/set_product/set_product_combination/search_products', { params: { query: '' } })
                this.products_list = res.data.products || []
                if (this.product_id) {
                    this.selected_product_id = String(this.product_id)
                    await this.load_product_options(this.product_id)
                }
            } catch (e) { console.error(e) } finally { this.is_loading_products = false }
        },
        async on_product_change() {
            this.product_options = []
            if (this.selected_product_id) await this.load_product_options(this.selected_product_id)
        },
        async load_product_options(id) {
            this.is_loading_options = true
            try {
                const res = await axios.get('/set_product/set_product_combination/get_product_options/' + id)
                this.product_options = res.data.options || []
            } catch (e) { console.error(e) } finally { this.is_loading_options = false }
        },
        is_selected(id) { return this.local_data.selected_options.some(o => o.id === id) },
        toggle_option(opt) {
            if (this.is_selected(opt.id)) { this.remove_option(opt.id) }
            else {
                const p = this.products_list.find(p => p.id == this.selected_product_id)
                this.local_data.selected_options.push({ ...opt, product_name: p?.title ?? '' })
            }
        },
        remove_option(id) {
            const i = this.local_data.selected_options.findIndex(o => o.id === id)
            if (i !== -1) this.local_data.selected_options.splice(i, 1)
        },
        generate_barcode() {
            let d = ''; for (let i = 0; i < 12; i++) d += Math.floor(Math.random() * 10)
            let s = 0; for (let i = 0; i < 12; i++) s += parseInt(d[i]) * (i % 2 === 0 ? 1 : 3)
            this.local_data.barcode = d + ((10 - (s % 10)) % 10); this.barcode_input = ''
        },
        set_barcode_from_input() {
            if (this.barcode_input.trim()) { this.local_data.barcode = this.barcode_input.trim(); this.barcode_input = '' }
        },
        close() {
            this.barcode_input = ''; this.selected_product_id = ''; this.product_options = []; this.products_list = []
            this.$emit('close_add_modal')
        },
        save() { this.$emit('add_combination') }
    }
}
</script>

<style scoped>
.form-label { font-weight: 500; margin-bottom: 4px; }
.options-list { max-height: 340px; overflow-y: auto; }
.opt-row { cursor: pointer; transition: background .12s; }
.opt-row:hover { background: #f0f4ff; }
.opt-selected { background: #e8f0fe; }
.opt-tag { display:inline-flex; align-items:center; gap:4px; background:#0d6efd; color:#fff; border-radius:4px; padding:3px 8px; font-size:12px; }
.opt-tag-x { cursor:pointer; opacity:.8; }
.opt-tag-x:hover { opacity:1; }
</style>
