<template>
    <stack-modal
        :show="is_order_status_edit_model"
        title="Edit order status"
        @close="is_order_status_edit_model=false"
        :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
    >
        <pre class="language-vue">
            <h1>Active order status</h1>

            <span v-if="!order_status_updating_loader">
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

                <h1>Edit order status</h1>
                
                <select class="form-control" v-model="selected_order_status" name="comment delete cause" > 
                    <option value="Treatment" disabled>Treatment</option>
                    <option value="Preparation for shipment">Preparation for shipment</option>
                    <option value="Ready to ship">Ready to ship</option>
                    <option value="Order has been sent">Order has been sent</option>
                    <option value="Transferred to the delivery service">Transferred to the delivery service</option>
                    <option value="Delivered">delivered</option>
                </select> 
            </span>
            <span v-if="order_status_updating_loader">
                <div class="justify-content-center">
                    <div class="col-md-4">
                        <img :src="'../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>
            </span>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="button"
                    :class="{'btn btn-primary': true}"
                    @click="edit_order_status()"
                >
                Edit status
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

export default {
    components: {
        StackModal,
    },
    data(){
        return {
            is_order_status_edit_model: false
        }
    },
    mounted() {
        //
    },
    methods: {
        show_modal(){
            this.is_order_status_model = true
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