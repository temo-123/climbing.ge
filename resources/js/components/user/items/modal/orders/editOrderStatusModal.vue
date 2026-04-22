<template>
    <StackModal
        v-model="is_order_status_edit_model"
        title="Edit order status"
        @close="is_order_status_edit_model=false"
        :saveButton="{ visible: true, title: 'Edit status', btnClass: { 'btn btn-primary': true }, onClick: edit_order_status }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <div>
            <h4>Active order status</h4>

            <div v-if="!order_status_updating_loader">
                <div class="p-4">
                    <table class="table table-bordered track_tbl">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>|</th>
                                <th>Updating Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ order_status.status }}</td>
                                <td>|</td>
                                <td>{{ order_status.status_updating_data }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4>Edit order status</h4>

                <select class="form-control" v-model="selected_order_status">
                    <option value="Treatment" disabled>Treatment</option>
                    <option value="Preparation for shipment">Preparation for shipment</option>
                    <option value="Ready to ship">Ready to ship</option>
                    <option value="Order has been sent">Order has been sent</option>
                    <option value="Transferred to the delivery service">Transferred to the delivery service</option>
                    <option value="Delivered">Delivered</option>
                </select>
            </div>
            <div v-if="order_status_updating_loader" class="d-flex justify-content-center p-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
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
            activ_order_status: {},
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
            this.get_activ_order('edit')
        },

        get_activ_order(action){
                // alert(action)
                axios
                .get("/get_order/get_activ_order/"+this.activ_order_id)
                .then(response => {
                    this.activ_order_status = response.data
                    // this.selected_order_status = response.data.status

                    if(response.data.treatment){
                        this.selected_order_status = 'Treatment'
                    }
                    if(response.data.preparation_for_shipment){
                        this.selected_order_status = 'Preparation for shipment'
                    }
                    if(response.data.ready_to_ship){
                        this.selected_order_status = 'Ready to ship'
                    }
                    if(response.data.order_has_been_sent){
                        this.selected_order_status = 'Order has been sent'
                    }
                    if(response.data.transferred_to_the_delivery_service){
                        this.selected_order_status = 'Transferred to the delivery service'
                    }
                    if(response.data.delivered){
                        this.selected_order_status = 'Delivered'
                    }

                    if(action == 'edit'){
                        this.is_order_status_edit_model = true
                    }
                    else if(action == 'show'){
                        this.is_order_status_model = true
                    }
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
                        alert('Order updated!')
                    })
                    .catch(
                        error => console.log(error)
                    )
                    .finally(() => this.order_status_updating_loader = false);
                }
                else{
                    alert('Plees select order status')
                }
            },
    }
}
</script>