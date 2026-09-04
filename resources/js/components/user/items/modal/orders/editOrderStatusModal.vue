<template>
    <StackModal
        v-model="is_order_status_edit_model"
        :title="$t('admin.orders.edit_order_status_title')"
        @close="is_order_status_edit_model=false"
        @save="edit_order_status"
        :saveButton="{ visible: true, title: $t('admin.orders.edit_status_btn'), btnClass: { 'btn btn-primary': true }, onClick: edit_order_status }"
        :cancelButton="{ visible: false, title: $t('common.close'), btnClass: { 'btn btn-danger': true } }"
    >
        <div>
            <h4>{{ $t('admin.orders.active_order_status_title') }}</h4>

            <div v-if="!order_status_updating_loader">
                <div class="p-4">
                    <table class="table table-bordered track_tbl">
                        <thead>
                            <tr>
                                <th>{{ $t('admin.orders.status_label') }}</th>
                                <th>{{ $t('admin.orders.updating_date_col') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ order_status.status || '—' }}</td>
                                <td>{{ order_status.status_updating_data || '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4>{{ $t('admin.orders.edit_order_status_title') }}</h4>

                <select class="form-control" v-model="selected_order_status">
                    <option value="Treatment" disabled>{{ $t('admin.orders.status_option_treatment') }}</option>
                    <option value="Preparation for shipment">{{ $t('admin.orders.status_option_preparation_for_shipment') }}</option>
                    <option value="Ready to ship">{{ $t('admin.orders.status_option_ready_to_ship') }}</option>
                    <option value="Order has been sent">{{ $t('admin.orders.status_option_order_has_been_sent') }}</option>
                    <option value="Transferred to the delivery service">{{ $t('admin.orders.status_option_transferred_to_delivery') }}</option>
                    <option value="Delivered">{{ $t('admin.orders.status_option_delivered') }}</option>
                </select>
            </div>
            <div v-if="order_status_updating_loader" class="d-flex justify-content-center p-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">{{ $t('admin.export.loading_ellipsis') }}</span>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    components: {},
    data(){
        return {
            is_order_status_edit_model: false,
            activ_order_id: null,
            order_status: {},
            selected_order_status: '',
            order_status_updating_loader: false,
        }
    },
    mounted() {
        //
    },
    methods: {
        show_modal(order_id){
            this.activ_order_id = order_id
            this.get_activ_order()
        },

        get_activ_order(){
                axios
                .get("/get_order/get_activ_order/"+this.activ_order_id)
                .then(response => {
                    this.order_status = response.data
                    this.selected_order_status = response.data.status
                    this.is_order_status_edit_model = true
                })
                .catch(
                    error => console.log(error)
                );
            },


            edit_order_status(){
                if(this.selected_order_status){

                    this.order_status_updating_loader = true

                    axios
                    .post("/set_order/edit_order_status/"+this.activ_order_id,{
                        status: this.selected_order_status
                    })
                    .then(response => {
                        this.is_order_status_edit_model = false

                        this.$emit('update')
                    })
                    .catch(
                        error => console.log(error)
                    )
                    .finally(() => this.order_status_updating_loader = false);
                }
                else{
                    alert(this.$t('admin.orders.please_select_order_status'))
                }
            },
    }
}
</script>