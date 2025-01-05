<template>
        <div class="row">
            <!-- <div class="col-sm-3"> -->
                <left-menu />
            <!-- </div> -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-12">
                        <breadcrumb />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <tabsComponent 
                            :table_data="this.data_for_tab"
                            @update="get_orders"

                            @del_region="del_region"
                            @open_editing_modal="open_editing_modal"

                            @open_orderDetalsModal="open_orderDetalsModal"
                            @open_orderStatusModal="open_orderStatusModal"
                            @open_orderTrackingModal="open_orderTrackingModal"

                            @shipedRegionEditModal="shipedRegionEditModal"
                        />
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'

    import orderDetalsModal from '../../items/modal/orders/orderDetalsModal.vue'
    import orderStatusModal from '../../items/modal/orders/orderStatusModal.vue'
    import orderTrackingModal from '../../items/modal/orders/orderTrackingModal.vue'

    import shipedRegionEditModal from "../../items/modal/tab_modals/edit/EditShipingRegionModalComponent.vue";
    export default {
        components: {
            breadcrumb,
            tabsComponent,

            orderDetalsModal,
            orderStatusModal,
            orderTrackingModal,

            shipedRegionEditModal
        },
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_orders()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_orders()
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_orders(){
                this.data_for_tab = []
                axios
                .get("/order/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Orders',
                                            // 'add_action': {
                                            //     'action': 'route',
                                            //     'link': 'articleAdd', 
                                            // },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Order Status',
                                                        'Edit status',
                                                        'Show detals',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['action_fun_id', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_article', 'btn btn-primary', '<i class="fa fa-truck" aria-hidden="true"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['edit_order_status', 'edit'],
                                                    ]
                                                }
                                            },
                                        });
                    this.get_shipd_regions()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_shipd_regions(){
                axios
                .get("/shiped_region/get_all_shiped_regions/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 2,
                                            'table_name': 'Shiped regions',
                                            // 'add_action': {
                                            //     'action': 'route',
                                            //     'link': 'articleAdd', 
                                            // },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Public',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['region']],
                                                        ['data', ['shiping_price']],
                                                        ['action_fun_id', 'open_editing_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_region', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['shiping_region', 'edit'],
                                                        ['shiping_region', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_region(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/shiped_region/del_region/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update()
                    })
                    .catch(error => console.log(error))
                }
            },
            open_editing_modal(actyve_info){
                this.$refs.shipedRegionEditModal.open_editing_modal(actyve_info)
            },
            get_order_status(){
                axios
                .get("/order/get_order_status/" + this.activ_order_id)
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
                .get("/get_activ_order/"+this.activ_order_id)
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
                    .post("/edit_order_status/"+this.activ_order_id,{
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
                .get("/get_order_detals/"+order_id)
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