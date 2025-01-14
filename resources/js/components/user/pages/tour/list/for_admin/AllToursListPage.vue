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
                        @update-data="get_all_tours_data"
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
            this.get_all_tours_data();
        },
         
        methods: {
            get_all_tours_data: function(){
                this.data_for_tab = []
                axios
                .get("/tour/get_all_tours/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'Tours', 
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
                                                        'Name',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['title'], 'show_local_image_modal'],
                                                        ['action_router', 'sectorLocalImagesListEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector_local_images', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['sector_local_images', 'edit'],
                                                        ['sector_local_images', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                this.get_all_tours_categories()
                })
                .catch(
                    error => console.log(error)
                );
            },

            get_all_tours_categories: function(){
                axios
                .get("/tour/category/get_all_categories/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 2,
                                            'table_name': 'Tours categories', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'tourCategoryAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['title'], 'show_local_image_modal'],
                                                        ['action_router', 'sectorLocalImagesListEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_sector_local_images', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['sector_local_images', 'edit'],
                                                        ['sector_local_images', 'del'],
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