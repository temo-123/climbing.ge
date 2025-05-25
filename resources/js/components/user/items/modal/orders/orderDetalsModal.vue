<template>
    <stack-modal
            :show="is_order_detals_model"
            title="Show order detals"
            @close="is_order_detals_model=false"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
                <h1>Order</h1>

                <table class="table table-condensed" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <thead>
                        <tr>
                            <!-- <th class="text-center col-xs-1 col-sm-1">#</th> -->
                            <th class="text-center col-xs-7 col-sm-7">Name</th>
                            <th class="text-center col-xs-1 col-sm-1">Qty</th>
                            <th class="text-center col-xs-3 col-sm-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td class="col-xs-1 col-sm-1 text-center">1</td>
                            <td class="text-center">Item bought number 1</td>
                            <td class="text-center">5 Pcs</td>
                            <td class="text-right">50.000,00</td>
                        </tr> -->
                        <tr v-for="item in order_product_items" :key="item.id">
                            <!-- <td class="text-center">2</td> -->
                            <td class="text-center">{{ item.product.url_title }}</td>
                            <td class="text-right">{{ item.quantity }} Pcs</td>
                            <td class="text-right">{{ colculat_items_price(item.option.price, item.quantity) }} ₾</td>
                        </tr>
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" style="background: #fff;">
                                <!-- Information<br>
                                Information content -->
                            </th>
                            <th class="text-right">Total</th>
                            <th class="text-right">{{ total_price }} ₾</th>
                        </tr>
                    </tfoot>
                </table>

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
            is_order_detals_model: false
        }
    },
    mounted() {
        //
    },
    methods: {
        show_modal(){
            this.is_order_status_model = true
        },

        get_order_detals(order_id){
                axios
                .get("/order/get_order_detals/"+order_id)
                .then(response => {
                    this.activ_order_detals = response.data.order
                    // this.get_order_products(response.data.id)

                    this.order_product_items = response.data.order_products
                    this.colculat_total_price()

                    this.is_order_detals_model = true
                })
                .catch(
                    error => console.log(error)
                );
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