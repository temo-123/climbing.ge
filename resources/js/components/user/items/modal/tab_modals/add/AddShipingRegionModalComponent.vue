<template>
    <stack-modal
            :show="is_order_region_add_model"
            :title="$t('admin.shop.add_shipping_region_title')"
            @close="close_modal"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <h1>{{ $t('admin.shop.add_shipping_region_title') }}</h1>
            <form id="add_region" v-on:submit.prevent="add_region" >
                <input type="text" class="form-control" v-model="adding_data.region" name="region name" :placeholder="$t('admin.shop.region_placeholder')" :title="$t('admin.shop.region_placeholder')" required>
                <input type="number" class="form-control" v-model="adding_data.shiping_price" name="shiping price" :placeholder="$t('admin.shop.shipping_price_label')" :title="$t('admin.shop.shipping_price_label')" required>

                <input type="number" class="form-control" v-model="adding_data.free_shiping_price_after" name="free shiping price after" :placeholder="$t('admin.shop.free_shipping_price_after_label')" :title="$t('admin.shop.free_shipping_price_after_label')">
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="add_region"
                    :class="{'btn btn-primary': true}"
                >
                {{ $t('admin.shop.add_region_btn') }}
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
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        data(){
            return{
                is_order_region_edit_model: false,
                is_order_region_add_model: false,

                adding_data: {
                    region: '',
                    shiping_price: '',
                    free_shiping_price_after: '',
                }
            }
        },
        methods: {
            add_region(){
                    axios
                    .post('/set_shiped_region/add_region/', {
                        adding_data: this.adding_data
                    })
                    .then(Response => {
                        // alert('region added socsesfuly')
                        this.$emit('update')
                        this.is_order_region_add_model = false
                    })
                    .catch(error => console.log(error))
            },
            show_modal(){
                this.is_order_region_add_model = true
            },
            close_modal(){
                if(confirm(this.$t('admin.live_camera.confirm_close_form_discard'))){
                    this.is_order_region_add_model = false

                    this.adding_data = {
                        region: '',
                        shiping_price: '',
                        free_shiping_price_after: '',
                    }
                }
            }
        }
    }
</script>

<style>

</style>