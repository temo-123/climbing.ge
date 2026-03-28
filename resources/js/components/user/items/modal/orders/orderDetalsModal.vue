<template>
    <StackModal
        v-model="is_order_detals_model"
        title="Show order detals"
        @close="is_order_detals_model=false">
        <div class="space-y-4">
            <div>
                <h1>Order</h1>
                <table class="table table-condensed" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center col-xs-7 col-sm-7">Name</th>
                            <th class="text-center col-xs-1 col-sm-1">Qty</th>
                            <th class="text-center col-xs-3 col-sm-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in order_product_items" :key="item.id">
                            <td class="text-center">{{ item.product.url_title }}</td>
                            <td class="text-right">{{ item.quantity }} Pcs</td>
                            <td class="text-right">{{ colculat_items_price(item.option.price, item.quantity) }} ₾</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" style="background: #fff;"></th>
                            <th class="text-right">Total</th>
                            <th class="text-right">{{ total_price }} ₾</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div>
                <h1>Active order status</h1>
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
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    components: {},
    data(){
        return {
            is_order_detals_model: false
        }
    },
    mounted() {
    },
    methods: {
        show_modal(){
            this.is_order_status_model = true
        },

        get_order_detals(order_id){
            axios
            .get("/get_order/get_order_detals/"+order_id)
            .then(response => {
                this.activ_order_detals = response.data.order
                this.order_product_items = response.data.order_products
                this.colculat_total_price()
                this.is_order_detals_model = true
            })
            .catch(error => console.log(error));
        },

        colculat_total_price() {
            this.total_price = 0
            this.price = 0
            this.order_product_items.forEach(product => {
                if (product.quantity > 1) {
                    this.price = product.quantity * product.option.price
                }
                else{
                    this.price = parseInt(product.option.price)
                }
                this.total_price = this.total_price + this.price
            });
        },
        colculat_items_price(price, quantyty) {
            var colculated_price = price * quantyty
            return colculated_price
        },
    }
}
</script>
