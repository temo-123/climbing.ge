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
                        @update="get_user_tours"
                        @del_tour="del_tour"
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
            this.get_user_tours();
        },
         
        methods: {
            get_user_tours: function(){
                this.data_for_tab = []
                axios
                .get("/tour/get_user_tours/")
                .then(response => {
                    this.data_for_tab.push({
                        'id': 1,
                                            'table_name': 'My Tours', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'tourAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'URL Title',
                                                        'Public',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['published'], 'bool'],
                                                        ['action_router', 'tourEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_tour', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['tour', 'edit'],
                                                        ['tour', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            del_tour(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/tour/del_tour/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_user_tours()
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>