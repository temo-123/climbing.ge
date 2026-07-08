<template>
    <stack-modal
            :show="is_warehouse_add_model"
            :title="$t('admin.warehouses.add_order_warehouse_title')"
            @close="close_modal"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <form v-on:submit.prevent="add_warehouse" id="add_warehouse">
                <input type="text" class="form-control" v-model="data.name" name="Name" :placeholder="$t('admin.warehouses.name_placeholder')" :title="$t('admin.warehouses.name_placeholder')" required>

                <div class="form-group">
                    <input type="checkbox" id="general" name="general" v-model="data.general">
                    <label for="general">{{ $t('admin.warehouses.is_general_warehouse_question') }}</label>
                </div>
            </form>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    form="add_warehouse"
                    :class="{'btn btn-primary': true}"
                >
                {{ $t('admin.warehouses.add_warehouse_btn') }}
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
        mounted(){
            // console.log(this.table_info)
            // this.get_region_data()
        },
        data(){
            return{
                is_warehouse_add_model: false,

                regions: [],
                data: {
                    name: '',
                    general: false,
                }
            }
        },
        methods: {
            add_warehouse(){
                    axios
                    .post('/set_warehouse/add_warehouse/', {
                        data: this.data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        // alert('Added successfully')
                        this.$emit('update')
                        this.is_warehouse_add_model = false
                        this.clear_form()
                    })
                    .catch(error => console.log(error))
            },
            clear_form(){
                this.data = {
                    name: '',
                    general: false,
                }
            },
            show_modal(){
                this.is_warehouse_add_model = true
            },
            close_modal(){
                if(confirm(this.$t('admin.live_camera.confirm_close_form_discard'))){
                    this.is_warehouse_add_model = false
                    this.clear_form()
                }
            }
        }
    }
</script>

<style>

</style>