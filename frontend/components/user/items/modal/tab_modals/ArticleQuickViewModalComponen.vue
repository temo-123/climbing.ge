<template>
    <stack-modal
                :show="edit_order_modal"
                title="Edit order status"
                @close="edit_order_modal=false"
                :modal-class="{ [modalClass]: true }"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="container">
                    <div class="clearfix row">
                        <p v-if="modal_order.length > 0">
                            Order ID - {{  modal_order.id }}
                            Shiping data - {{  modal_order.order_data }}
                            Status - {{ modal_order.status }}
                            User Id - {{  modal_order.user_id }}
                        </p>
                    </div>
                    <hr>
                    <div class="clearfix row">
                        <p>
                            Adres ID -
                            Address N1 - 
                            Address N2 - 
                            ZIP - 
                            Quntry - 
                            City -
                        </p>
                    </div>
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
                    <form ref="myForm">
                            <div class="clearfix row">
                                <div class="col-md-12 image_add_modal_form">
                                    <select class="form-control" name="status" id="status">
                                            <option value="1">Order Processed</option> 
                                            <option value="2">Order Shipped</option>
                                            <option value="3">Order En Route</option>
                                            <option value="4">Order Arrived</option>
                                    </select> 
                                </div>
                        </div>
                    </form>
                    <form >
                        <div class="clearfix row">
                            <div class="col-md-12 image_add_modal_form">
                                <input type="email" class="form-control" v-model="email" name="email" id="email" placeholder="email" title="enter your email.">
                            </div>
                        </div>
                        <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                        Send invois to mail
                        </button>
                    </form>
                </div>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                            type="button"
                            :class="{'btn btn-primary': true}"
                            @click="edit_permission(user_id_for_rditing_parmission)"
                        >
                    Save
                    </button>
                </div>
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
                edit_order_modal: false,
                modalClass: '',
                modal_order: [],
                email: ''
            }
        },

        mounted() {
            
        },

        methods: {
            show_order_status_edit_madel(order_id){
                this.edit_order_modal = true
                // this.get_orders_data(order_id)
            },
        }
    }
</script>

<style>

</style>