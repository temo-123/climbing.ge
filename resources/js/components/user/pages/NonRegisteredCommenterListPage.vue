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
                        @update="get_users"
                        @del_xuser="del_xuser"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../items/data_table/TabsComponent.vue'

    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,
        },
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_users()
        },
        watch: {
            '$route' (to, from) {
                this.data_for_tab = [],
                this.get_users()
                window.scrollTo(0,0)
            }
        },
        methods: {
            del_xuser(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .delete("non_registered_commenter/del_non_registered_commenter/"+id)
                    .then(response => {;
                        this.get_users()
                    })
                    .catch(
                        error => console.log(error)
                    );
                    
                }
            },
            get_users(){
                this.data_for_tab = [],

                axios
                .get("non_registered_commenter/get_non_registered_commenter/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Non Registered Commenter List',
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Email',
                                                        'Confirmed',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['comenter', 'id']],
                                                        ['data', ['comenter', 'email']],
                                                        ['data', ['comenter', 'confirmed'], 'bool'],
                                                        ['action_fun_id', 'del_xuser', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>', ['comenter', 'id']],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['services', 'del'],
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