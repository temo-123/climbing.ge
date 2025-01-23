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
                        @update="get_products_data"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb
        },
        
        data() {
            return {
                data_for_tab:[],
            }
        },

        mounted() {
            this.get_products_data();
        },
         
        methods: {
            get_products_data: function(){
                this.data_for_tab = []
                axios
                .get("/product/get_user_products")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'My Products', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'productAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Public',
                                                        'Options',
                                                        'Edit Options',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['product', 'id']],
                                                        ['data', ['product', 'url_title']],
                                                        ['data', ['product', 'published']],
                                                        ['data', ['options']],
                                                        ['action_router', 'productOptionsControl', 'btn btn-success', '<i class="fa fa-list" aria-hidden="true"></i>', ['product', 'id']],
                                                        ['action_router', 'productEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>', ['product', 'id']],
                                                        ['action_fun_id', 'del_product', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>',['product', 'id']],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['product', 'edit'],
                                                        ['product', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },

            del_product(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/product/del_product/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_products_data()
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>