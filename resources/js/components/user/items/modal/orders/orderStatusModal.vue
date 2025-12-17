<template>
     <stack-modal
                :show="is_order_status_model"
                title="Show order status"
                @close="is_order_status_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="p-4">
                    <h3>Order Tracking</h3>
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

            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- // -->
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
            is_order_status_model: false
        }
    },
    mounted() {
        //
    },
    methods: {
        show_modal(){
            this.is_order_status_model = true
        },
            get_order_status(){
                axios
                .get("/get_order/get_order_status/" + this.activ_order_id)
                .then(response => {
                    this.order_status = response.data

                    if(!this.order_status.status){
                        this.row_color = 'row_deanger'
                    }
                    else if(this.order_status.status ){
                        this.row_color = 'row_worning'
                    }
                    else if(this.order_status.status ){
                        this.row_color = ''
                    }
                    else{
                        this.row_color = ''
                    }
                })
                .catch(
                    error => console.log(error)
                );
            },
    }
}
</script>