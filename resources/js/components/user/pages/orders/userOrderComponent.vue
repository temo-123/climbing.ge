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
                        />
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    import orderDetalsModal from '../../items/modal/orders/orderDetalsModal.vue'
    import orderStatusModal from '../../items/modal/orders/orderStatusModal.vue'

    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            orderDetalsModal,
            orderStatusModal,

            breadcrumb,
            tabsComponent,
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
                axios
                .get("/order/get_user_orders/")
                .then(response => {
                    this.data_for_tab.push({ 
                                            'id': 1,
                                            'table_name': 'My Orders',
                                            'add_action': {
                                                'action': 'function',
                                                'link': 'open_siped_region_add_modal', 
                                                'class': 'btn btn-primary',
                                                'btn_title' : 'Add castom order'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Status',
                                                        'Edit status',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['Status']],
                                                        ['action_fun_id', 'open_siped_region_edit_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['order', 'edit'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>