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
                        @update="get_purchules()"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import tabsComponent  from '../../items/data_table/TabsComponent.vue'
import breadcrumb from '../../items/BreadcrumbComponent.vue'
export default {
    components: {
        breadcrumb,
        tabsComponent ,
        // leftMenu,
    },
    data(){
        return {
            data_for_tab: [],
        }
    },
    mounted() {
        this.get_purchules()
    },
    watch: {
        '$route' (to, from) {
            this.get_purchules()
            window.scrollTo(0,0)
        }
    },
    methods: {
        get_purchules(){
            axios
            .get("/get_order/get_user_purchules/")
            .then(response => {
                this.data_for_tab = [],
                this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'My purchules',
                                            'add_action': {
                                                'action': 'function',
                                                'link': 'addCostomOrder', 
                                                'class': 'btn btn-primary',
                                                'btn_title' : 'Add castom order'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Order Status',
                                                        'Confirm',
                                                        // 'Payment',
                                                        // 'Show detals',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['status']],
                                                        ['data', ['confirm']],
                                                        // ['action_fun_id', 'articleEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        // ['action_fun_id', 'del_article', 'btn btn-primary', '<i class="fa fa-truck" aria-hidden="true"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        // ['no'],
                                                        // ['edit_order_status', 'edit'],
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