<template>
    <div class="col-md-12">
        <div v-if="is_loading_page" class="row justify-content-center">
            <div class="col-md-4">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div v-else>
            <div class="row mb-3">
                <div class="col-md-6">
                    <button class="btn btn-primary" @click="open_add_modal">{{ $t('admin.shop.add_combination_btn') }}</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success float-right" @click="load_combinations">{{ $t('common.refresh') }}</button>
                </div>
            </div>

            <product-combinations-table-component
                :combinations="combinations"
                :is_loading_editing_modal="is_loading_editing_modal"
                @edit_combination_modal="open_edit_modal"
                @del_combination="del_combination"
            />
        </div>

        <product-combination-add-modal-component
            :is_add_modal="is_add_modal"
            :adding_data="adding_data"
            :is_loading="is_loading"
            :product_id="product_id"
            @close_add_modal="close_add_modal"
            @update_adding_images="adding_images = $event"
            @add_combination="save_combination"
        />

        <product-combination-edit-modal-component
            :is_edit_modal="is_edit_modal"
            :editing_data="editing_data"
            :editing_images="editing_images"
            :is_loading="is_loading"
            :is_loading_modal="is_loading_editing_modal"
            @close_edit_modal="close_edit_modal"
            @update_adding_images="adding_images = $event"
            @edit_combination="update_combination"
            @del_combination_image="del_combination_image"
        />
    </div>
</template>

<script>
import axios from 'axios'
import productCombinationsTableComponent from './ProductCombinationsTableComponent.vue'
import productCombinationAddModalComponent from './modals/ProductCombinationAddModalComponent.vue'
import productCombinationEditModalComponent from './modals/ProductCombinationEditModalComponent.vue'

export default {
    components: {
        productCombinationsTableComponent,
        productCombinationAddModalComponent,
        productCombinationEditModalComponent
    },
    data() {
        return {
            product_id: null,
            combinations: [],
            is_add_modal: false,
            is_edit_modal: false,
            is_loading: false,
            is_loading_page: false,
            is_loading_editing_modal: false,
            adding_data: this.fresh_adding_data(),
            editing_data: {},
            editing_images: [],
            adding_images: []
        }
    },
    mounted() {
        this.product_id = this.$route.params.id
        if (this.product_id) this.load_combinations()
    },
    methods: {
        fresh_adding_data() {
            return { name: '', price: 0, currency: '₾', discount: 0, barcode: '', selected_options: [] }
        },

        open_add_modal() {
            this.adding_data = this.fresh_adding_data()
            this.adding_images = []
            this.is_add_modal = true
        },
        close_add_modal() {
            this.is_add_modal = false
            this.adding_data = this.fresh_adding_data()
            this.adding_images = []
        },

        open_edit_modal(id) {
            this.is_loading_editing_modal = true
            this.fetch_editing_combination(id)
        },
        close_edit_modal() {
            this.is_edit_modal = false
            this.editing_data = {}
            this.editing_images = []
            this.adding_images = []
        },

        del_combination(id) {
            if (confirm(this.$t('admin.shop.confirm_delete_combination'))) this.delete_combination(id)
        },
        del_combination_image(image_id) {
            this.delete_combination_image(image_id)
        },

        // API
        async load_combinations() {
            try {
                const res = await axios.get('/set_product/set_product_combination/get_combinations/' + this.product_id)
                this.combinations = res.data.combinations || []
            } catch (e) {
                console.error(e)
                alert(this.$t('admin.shop.failed_load_combinations'))
            }
        },

        async fetch_editing_combination(id) {
            try {
                const res = await axios.get('/set_product/set_product_combination/get_editing_combination/' + id)
                this.editing_data = {
                    ...res.data.combination,
                    selected_options: res.data.selected_options || []
                }
                this.editing_images = res.data.combination_images || []
                this.is_edit_modal = true
            } catch (e) {
                console.error(e)
                alert(this.$t('admin.shop.failed_load_combination_data'))
            } finally {
                this.is_loading_editing_modal = false
            }
        },

        async save_combination() {
            this.is_loading = true
            try {
                const formData = new FormData()
                formData.append('product_id', this.product_id)
                const payload = {
                    ...this.adding_data,
                    option_ids: this.adding_data.selected_options.map(o => o.id)
                }
                formData.append('data', JSON.stringify(payload))
                this.adding_images.forEach((img, i) => {
                    if (img.image) formData.append('images[' + i + ']', img.image)
                })
                await axios.post('/set_product/set_product_combination/add_combination', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                this.close_add_modal()
                await this.load_combinations()
                alert(this.$t('admin.shop.combination_added'))
            } catch (e) {
                console.error(e)
                alert(this.$t('admin.shop.failed_add_combination'))
            } finally {
                this.is_loading = false
            }
        },

        async update_combination() {
            this.is_loading = true
            try {
                const formData = new FormData()
                const payload = {
                    ...this.editing_data,
                    option_ids: (this.editing_data.selected_options || []).map(o => o.id)
                }
                formData.append('data', JSON.stringify(payload))
                this.adding_images.forEach((img, i) => {
                    if (img.image) formData.append('images[' + i + ']', img.image)
                })
                await axios.post('/set_product/set_product_combination/edit_combination/' + this.editing_data.id, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                this.close_edit_modal()
                await this.load_combinations()
                alert(this.$t('admin.shop.combination_updated'))
            } catch (e) {
                console.error(e)
                alert(this.$t('admin.shop.failed_update_combination'))
            } finally {
                this.is_loading = false
            }
        },

        async delete_combination(id) {
            try {
                await axios.delete('/set_product/set_product_combination/del_combination/' + id)
                await this.load_combinations()
                alert(this.$t('admin.shop.combination_deleted'))
            } catch (e) {
                console.error(e)
                alert(this.$t('admin.shop.failed_delete_generic'))
            }
        },

        async delete_combination_image(image_id) {
            try {
                await axios.delete('/set_product/set_product_combination/del_combination_image/' + image_id)
                if (this.is_edit_modal && this.editing_data.id) {
                    await this.fetch_editing_combination(this.editing_data.id)
                }
            } catch (e) {
                console.error(e)
                alert(this.$t('admin.shop.failed_delete_image'))
            }
        }
    }
}
</script>
