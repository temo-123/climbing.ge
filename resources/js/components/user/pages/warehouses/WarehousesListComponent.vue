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
                        @update="get_warehouses_data"

                        @show_warehouse_edit_medal="show_warehouse_edit_medal"
                        @show_warehouse_add_medal="show_warehouse_add_medal"
                        @del_warehouse="del_warehouse"
                    />
                </div>
            </div>
        </div>

        <warehouse_add_medal
            ref="show_warehouse_add_medal"
            @update="get_warehouse_data"
        />
        <warehouse_edit_medal
            ref="show_warehouse_edit_medal"
            @update="get_warehouse_data"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'

    import warehouse_add_medal from '../../items/modal/tab_modals/add/AddWarehouseModalComponent.vue'
    import warehouse_edit_medal from '../../items/modal/tab_modals/edit/EditWarehouseModalComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,

            warehouse_add_medal,
            warehouse_edit_medal,
        },
    
        data() {
            return {
                data_for_tab:[],
            }
        },

        mounted() {
            this.get_warehouses_data();
        },
         
        methods: {
            get_warehouses_data: function(){
                this.data_for_tab = []
                axios
                .get("/warehouse/get_warehouses")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Warehouses', 
                                            'add_action': {
                                                'action': 'function',
                                                'link': 'show_warehouse_add_medal', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'General Warehouse',
                                                        'Options',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['name']],
                                                        ['data', ['general'], 'bool'],
                                                        ['action_fun_id', 'warehouseEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'warehouseEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_warehouse', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['warehouse', 'edit'],
                                                        ['warehouse', 'edit'],
                                                        ['warehouse', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },

            show_warehouse_add_medal(){
                this.$refs.show_warehouse_add_medal.show_modal();
            },

            show_warehouse_edit_medal(id){
                this.$refs.show_warehouse_edit_medal.show_modal(id);
            },

            del_warehouse(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/warehous/del_warehouse/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_warehouses_data();
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>