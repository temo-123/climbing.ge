<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <tabsComponent
                        :table_data="data_for_tab"
                        @update="get_purchules()"
                        @show_customer_order_details="show_customer_order_details"
                    />
                </div>
            </div>
        </div>

        <customerOrderDetailsModal ref="customerOrderDetailsModal" />
    </div>
</template>

<script>
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import customerOrderDetailsModal from '../../items/modal/orders/CustomerOrderDetailsModal.vue'

export default {
    components: { breadcrumb, tabsComponent, customerOrderDetailsModal },
    data(){
        return { data_for_tab: [] }
    },
    mounted() { this.get_purchules() },
    watch: {
        '$route'() { this.get_purchules(); window.scrollTo(0,0) }
    },
    methods: {
        show_customer_order_details(order_id) {
            this.$refs.customerOrderDetailsModal.show_modal(order_id)
        },
        get_purchules(){
            axios.get("get_order/get_user_purchules")
            .then(response => {
                this.data_for_tab = []
                this.data_for_tab.push({
                    id: 1,
                    table_name: this.$t('user.orders.my_purchases'),
                    tab_data: {
                        data: response.data,
                        tab: {
                            head: [
                                this.$t('common.id'),
                                this.$t('user.orders.col_status'),
                                this.$t('common.payment'),
                                this.$t('user.orders.col_shipping'),
                                this.$t('user.orders.col_delivery'),
                                this.$t('common.date'),
                                this.$t('user.orders.col_details'),
                            ],
                            body: [
                                ['data', ['id']],
                                ['data', ['status']],
                                ['data', ['payment']],
                                ['data', ['shiping']],
                                ['data', ['delivery_days']],
                                ['data', ['created_at']],
                                ['action_fun_id', 'show_customer_order_details', 'btn btn-info btn-sm', '<i class="fa fa-eye" aria-hidden="true"></i>'],
                            ],
                            perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['no'], ['no']],
                        }
                    },
                })
            })
            .catch(error => console.log(error))
        },
    }
}
</script>
