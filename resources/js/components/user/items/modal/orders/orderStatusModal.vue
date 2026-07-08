<template>
    <StackModal
        v-model="is_order_status_model"
        :title="$t('admin.orders.show_order_status_title')"
        @close="is_order_status_model=false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div class="p-4">
            <h3>{{ $t('admin.orders.order_tracking_title') }}</h3>
            <table class="table table-bordered track_tbl">
                <thead>
                    <tr>
                        <th>{{ $t('admin.orders.status_label') }}</th>
                        <th>|</th>
                        <th>{{ $t('admin.orders.updating_date_col') }}</th>
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
                this.is_order_status_model = true
            })
            .catch(error => console.log(error));
        },
    }
}
</script>
