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
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import breadcrumb from '../../items/BreadcrumbComponent.vue'
export default {
    components: { breadcrumb, tabsComponent },
    data(){
        return { data_for_tab: [] }
    },
    mounted() { this.get_purchules() },
    watch: {
        '$route'() { this.get_purchules(); window.scrollTo(0,0) }
    },
    methods: {
        get_purchules(){
            axios.get("get_order/get_user_purchules")
            .then(response => {
                this.data_for_tab = []
                this.data_for_tab.push({
                    id: 1,
                    table_name: 'My Purchases',
                    tab_data: {
                        data: response.data,
                        tab: {
                            head: ['ID', 'Custom', 'Status', 'Payment', 'Shipping', 'Delivery', 'Date'],
                            body: [
                                ['data', ['id']],
                                ['data', ['is_custom'], 'bool'],
                                ['data', ['status']],
                                ['data', ['payment']],
                                ['data', ['shiping']],
                                ['data', ['delivery_days']],
                                ['data', ['created_at']],
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
