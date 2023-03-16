<template>
    <tr :class="row_color">
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td @click="show_order_detals_model(table_info.id)" :style="'cursor: zoom-in'">|</td>
        <td @click="show_order_detals_model(table_info.id)" :style="'cursor: zoom-in'">{{table_info.id}}</td>

        <td @click="show_order_detals_model(table_info.id)" :style="'cursor: zoom-in'">|</td>

        <td @click="show_order_detals_model(table_info.id)" :style="'cursor: zoom-in'">{{ order_status.status }}</td>

        <td @click="show_order_detals_model(table_info.id)" :style="'cursor: zoom-in'">
            |
        </td>

        <td v-if="location == 'all_orders'">
            <button class="btn btn-primary" @click="edit_order_status_model()"  v-if="$can('edit_order_status', 'order')">Edit order status</button>
        </td>
        <td v-else-if="location == 'my_orders'">
            <button class="btn btn-primary" @click="show_order_status_model()">Show order status</button>
        </td>
        
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
    </tr>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
        },
        props: [
            'table_info',
        ],
        data(){
            return{
                location: '',
                activ_order_id: 0,
                activ_order_status: [],
                activ_order_detals: [] ,
                is_order_status_edit_model: false,
                is_order_status_model: false,
                is_order_detals_model: false,
                selected_order_status: '',
                row_color: '',

                total_price: 0,
                price: 0,
                order_product_items: [],

                order_status_updating_loader: false,
                order_status: '',
            }
        },
        mounted(){
            var path = window.location.pathname;
            this.location = path.split("/").pop();
            this.activ_order_id = this.table_info.id

            this.get_order_status()
        },
        methods: {
            get_order_status(){
                axios
                .get("../api/order/get_order_status/" + this.activ_order_id)
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

            show_order_status_model(){
                this.get_activ_order('show')
            },

            edit_order_status_model(){
                this.get_activ_order('edit')
            },

            show_order_detals_model(order_id){
                this.get_order_detals(order_id)
            },

            get_activ_order(action){
                // alert(action)
                axios
                .get("../api/get_activ_order/"+this.activ_order_id)
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
                    .post("../api/edit_order_status/"+this.activ_order_id,{
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

            get_order_detals(order_id){
                axios
                .get("../api/get_order_detals/"+order_id)
                .then(response => {
                    this.activ_order_detals = response.data.order
                    // this.get_order_products(response.data.id)

                    this.order_product_items = response.data.order_products
                    // this.user_id = response.data[0]['user_id']
                    // this.is_products_refresh = false
                    // this.products_reset_id++
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

<style>
    /* .row_deanger{

    }
    .row_worning{

    } */


    .row_deanger td{
        background-color: #df8d8d;
    }
    .row_worning td {
        background-color: #dfad8d;
    }
</style>