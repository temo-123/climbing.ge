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
                        @update="get_reservations"
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
            this.get_reservations();
        },
         
        methods: {
            get_reservations: function(){
                this.data_for_tab = []
                axios
                .get("/tour/reservation/get_user_reservations/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'All Tours Reservations',
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Persons',
                                                        'Check In',
                                                        'Reserver name',
                                                        'Reserver Email',
                                                        'Verificate',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['persons']],
                                                        ['data', ['check_in']],
                                                        ['data', ['name'], ['surname']],
                                                        ['data', ['email']],
                                                        ['data', ['verificate'], 'bool'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
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