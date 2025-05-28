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
                        @update="get_local_bisnes_data"
                        @del_local_bisnes="del_bisnes"
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
            tabsComponent ,
            breadcrumb
        },
        
        data() {
            return {
                data_for_tab:[],
            }
        },

        mounted() {
            this.get_local_bisnes_data();
        },
         
        methods: {
            get_local_bisnes_data: function(){
                this.data_for_tab = []
                axios
                .get("/bisnes/get_local_bisneses")
                .then(response => {
                    this.data_for_tab.push({'id': 1,
                                            'table_name': 'Local bisnes' ,
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'localBisnesAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Title',
                                                        'Public',
                                                        'Public data',
                                                        'Total public',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title'],],
                                                        ['data', ['published'], 'bool'],
                                                        ['data', ['published_data']],
                                                        ['data', ['public_totaly'], 'bool'],
                                                        ['action_router', 'localBisnesEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_local_bisnes', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['local_bisnes', 'edit'],
                                                        ['local_bisnes', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                })
                .catch(
                    error => console.log(error)
                );
            },
            row_action(data){
                    let end_day = Number(moment(data).format("D"))
                    let end_month = Number(moment(data).format("MM"))
                    let end_year = Number(moment(data).format("YYYY"))

                    if( new Date().getDate() > end_day && 
                        new Date().getMonth() >= end_month && 
                        new Date().getFullYear() >= end_year
                    ){
                        return 'completed_event'
                    }
                    if( new Date().getDate() > end_day && 
                        new Date().getMonth() == end_month && 
                        new Date().getFullYear() == end_year
                    ){
                        return 'completed_event'
                    }
                    else if( 
                        new Date().getDate() == end_day && 
                        new Date().getMonth() >= end_month && 
                        new Date().getFullYear() >= end_year
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getMonth() > end_month && 
                        new Date().getFullYear() > end_year
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getMonth() > end_month
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getFullYear() > end_year
                    ){
                        return 'completed_event'
                    }
            },
            del_bisnes(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/bisnes/del_local_bisnes/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>