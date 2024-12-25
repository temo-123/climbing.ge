<template>
    <stack-modal
                :show="order_detals_modal"
                title="Order status"
                @close="order_detals_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre>
                <div class="container">
                    <p>
                        Order ID - #
                        Status - 
                        Shiping data - 
                    </p>
                    <hr>
                    <p>
                        Address N1 - 
                        Address N2 - 
                        ZIP - 
                        Quntry - 
                        City -
                    </p>
                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>4</td>
                                <td>test</td>
                                <td>5</td>
                                <td>25</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2"> </th>
                                <th class="text-center">Total</th>
                                <th class="text-right">25</th>
                            </tr>
                        </tfoot>
                    </table>
                    <p>
                        Delivery asushistvitsa in the next 7-10 days.
                        Delivery times - 
                    </p>
                </div>
            </pre>
            <div slot="modal-footer">
            </div>
        </stack-modal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
        },
        data(){
            return {
                order_detals_modal: false,
                
                modalClass: ''
            }
        },

        mounted() {
            
        },

        methods: {
            // show_sector_model(sector_id){
            //     this.show_sector_modal=true
            // },
            show_order_detals_madel(order_id){
                this.order_detals_modal = true
                this.get_orders_data(order_id)
            },
        }
    }
</script>

<style>

</style>