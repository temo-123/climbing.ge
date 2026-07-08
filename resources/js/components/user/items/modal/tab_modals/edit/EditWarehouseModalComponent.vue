<template>
    <stack-modal
            :show="is_warehouse_edit_model"
            :title="$t('admin.warehouses.edit_order_warehouse_title')"
            @close="close_modal"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
        >
        <div>
            <form v-on:submit.prevent="edit_warehouse" id="edit_warehouse">
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
                    form="edit_warehouse"
                    :class="{'btn btn-primary': true}"
                >
                {{ $t('admin.warehouses.save_warehouse_btn') }}
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
            // this.get_warehouse_data()
        },
        data(){
            return{
                is_warehouse_edit_model: false,

                warehouse_id: '',
                data: {
                    name: '',
                    general: false,
                }
            }
        },
        methods: {
            get_warehouse_data(){
                    axios
                    .get('/get_warehouse/get_warehouse_data/' + this.warehouse_id)
                    .then(response => {

                        this.is_warehouse_edit_model = true
                        this.data = response.data
                    })
                    .catch(error => console.log(error))
            },
            edit_warehouse(){
                    axios
                    .post('/set_warehouse/edit_warehouse/' + this.warehouse_id, {
                        data: this.data,

                        _method: 'Post'
                    })
                    .then(Response => {
                        this.$emit('update')
                        this.clear_form()
                this.is_warehouse_edit_model = false
                    })
                    .catch(error => console.log(error))
            },
            clear_form(){
                this.data = {
                    name: '',
                    general: false,
                }
            },
            show_modal(id){
                this.warehouse_id = id
                this.get_warehouse_data()
            },
            close_modal(){
                if(confirm(this.$t('admin.live_camera.confirm_close_form_discard'))){
                    this.is_warehouse_edit_model = false
                    this.clear_form()
                }
            }
        }
    }
</script>

<style>

</style>