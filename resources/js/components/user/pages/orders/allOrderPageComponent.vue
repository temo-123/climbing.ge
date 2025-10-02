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
                            @show_siped_region_edit_modal="show_siped_region_edit_modal"
                            @show_siped_region_add_modal="show_siped_region_add_modal"

                            @show_order_detals_modal="show_order_detals_modal"
                            @show_order_status_modal="show_order_status_modal"
                            @show_order_tracking_odal="show_order_tracking_odal"

                            @add_costom_order="add_costom_order"
                        />
                    </div>
                </div>
            </div>
            <orderDetalsModal ef='orderDetalsModal' @update="get_orders"/>
            <orderStatusModal ef='orderStatusModal' @update="get_orders"/>
            <editOrderStatusModal ef='editOrderStatusModal' @update="get_orders"/>

            <addCustomOrderModal ref="addCustomOrderModal" @orderAdded="get_orders" />

            <shipedRegionEditModal ref='shipedRegionEditModal' @update="get_orders"/>
            <shipedRegionAddModal ref='shipedRegionAddModal' @update="get_orders"/>
        </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'

    import orderDetalsModal from '../../items/modal/orders/orderDetalsModal.vue'
    import orderStatusModal from '../../items/modal/orders/orderStatusModal.vue'
    import editOrderStatusModal from '../../items/modal/orders/editOrderStatusModal.vue'
    import addCustomOrderModal from '../../items/modal/orders/AddCustomOrderModal.vue'

    import shipedRegionEditModal from "../../items/modal/tab_modals/edit/EditShipingRegionModalComponent.vue";
    import shipedRegionAddModal from "../../items/modal/tab_modals/add/AddShipingRegionModalComponent.vue";
    export default {
        components: {
            breadcrumb,
            tabsComponent,

            orderDetalsModal,
            orderStatusModal,
            editOrderStatusModal,
            addCustomOrderModal,

            shipedRegionEditModal,
            shipedRegionAddModal
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
                .get("/order/get_all_orders/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Orders',
                                            'add_action': {
                                                'action': 'function',
                                                'link': 'add_costom_order', 
                                                'class': 'btn btn-primary',
                                                'btn_title' : 'Add castom order'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Order Status',
                                                        'Edit status',
                                                        'Payment',
                                                        'Show detals',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['status']],
                                                        ['data', ['payment']],
                                                        ['action_fun_id', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_article', 'btn btn-primary', '<i class="fa fa-truck" aria-hidden="true"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
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
                                            'add_action': {
                                                'action': 'function',
                                                'link': 'show_siped_region_add_modal', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Deliver price',
                                                        'Free after',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['region']],
                                                        ['data', ['shiping_price']],
                                                        ['data', ['free_shiping_price_after']],
                                                        ['action_fun_id', 'show_siped_region_edit_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_region', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
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
                        this.get_orders()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_siped_region_add_modal(){
                this.$refs.shipedRegionAddModal.show_modal()
            },
            show_siped_region_edit_modal(actyve_info){
                this.$refs.shipedRegionEditModal.open_editing_modal(actyve_info)
            },
            
            show_order_detals_modal(){
                this.$refs.orderDetalsModal.show_modal()
            },

            show_order_status_modal(){
                this.$refs.orderStatusModal.show_modal()
            },

            show_order_tracking_odal(){
                this.$refs.editOrderStatusModal.show_modal()
            },

            add_costom_order(){
                this.$refs.addCustomOrderModal.show_modal()
            },

        }
    }
</script>