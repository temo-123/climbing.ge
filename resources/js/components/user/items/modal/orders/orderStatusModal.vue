<template>
    <StackModal
        v-model="is_order_status_model"
        title="Show order status"
        @close="is_order_status_model=false">
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
    </StackModal>
</template>

<script>
export default {
    components: {},
    data(){
        return {
            is_order_status_model: false,
            activ_order_id: null,
            order_status: {},
        }
    },
    mounted() {
    },
    methods: {
        show_modal(order_id){
            this.activ_order_id = order_id
            this.get_order_status()
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
            .catch(error => console.log(error));
        },
    }
}
</script>
