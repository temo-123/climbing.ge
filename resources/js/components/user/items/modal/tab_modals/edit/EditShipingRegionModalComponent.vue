<template>
    <stack-modal
                :show="is_order_region_edit_model"
                :title="$t('admin.shop.edit_shipping_region_title')"
                @close="close_modal"
                @save="edit_region"
                :saveButton="{ visible: true, title: $t('admin.shop.edit_region_btn'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
            >
            <div>
                <h1>{{ $t('admin.shop.edit_shipping_region_title') }}</h1>
                <input type="text" class="form-control" v-model="editing_data.region" name="region name" :placeholder="$t('admin.shop.region_placeholder')" :title="$t('admin.shop.region_placeholder')">
                <input type="number" class="form-control" v-model="editing_data.shiping_price" name="shiping price" :placeholder="$t('admin.shop.shipping_price_label')" :title="$t('admin.shop.shipping_price_label')">
                <input type="number" class="form-control" v-model="editing_data.ship_min_price" name="ship min price" :placeholder="$t('admin.shop.ship_min_price_label')" :title="$t('admin.shop.ship_min_price_label')">
                <input type="number" class="form-control" v-model="editing_data.free_shiping_price_after" name="free shiping price after" :placeholder="$t('admin.shop.free_shipping_price_after_label')" :title="$t('admin.shop.free_shipping_price_after_label')">
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
        props: [
            // 'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        data(){
            return{
                is_order_region_edit_model: false,

                editing_data: {
                    id: '',
                    region: '',
                    shiping_price: '',
                    ship_min_price: '',
                    free_shiping_price_after: '',
                },
            }
        },
        methods: {
            show_modal(editing_info = null){
                if (editing_info) {
                    this.editing_data.id = editing_info.id || ''
                    this.editing_data.region = editing_info.region || ''
                    this.editing_data.shiping_price = editing_info.shiping_price || ''
                    this.editing_data.ship_min_price = editing_info.ship_min_price || ''
                    this.editing_data.free_shiping_price_after = editing_info.free_shiping_price_after || ''
                }
                this.is_order_region_edit_model = true
            },
            close_modal(){
                this.editing_data.id = ''
                this.editing_data.region = ''
                this.editing_data.shiping_price = ''
                this.editing_data.ship_min_price = ''
                this.editing_data.free_shiping_price_after = ''
                this.is_order_region_edit_model = false
            },
            edit_region(){
                axios
                .post('/set_shiped_region/edit_region/'+this.editing_data.id, {
                    editing_data: this.editing_data,
                })
                .then(Response => {
                    this.close_modal()

                    this.$emit('update')
                })
                .catch(error => console.log(error))
            },
        }
    }
</script>

<style>

</style>