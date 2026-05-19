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
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import tabsComponent  from '../..//items/data_table/TabsComponent.vue'
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
            this.get_my_ascents_data();
        },
         
        methods: {
            get_my_ascents_data: function(){
                axios
                .get("/get_user_ascents/get_all_my_ascents")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': 'My Ascents', 
                                            list_page: process.env.MIX_APP_SSH
                                                ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '') + '/summits/list'
                                                : window.location.origin + '/summits/list',
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'myAscentAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'Ascent ID',
                                                        'Summit name',
                                                        'Data of ascent',
                                                        'Delete',
                                                    ],
                                                    'body': [
                                                        ['data', ['global_data', 'id']],
                                                        ['data', ['global_data', 'url_title']],
                                                        ['data', ['global_data', 'published'], 'bool'],
                                                        ['action_fun_id', 'del_myAscent', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>', ['global_data', 'id']],
                                                    ],
                                                    'perm': [
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

            del_myAscent: function(id){
                if (confirm('Are you sure you want to delete this ascent?')) {
                    axios
                    .delete("/del_ascent/" + id)
                    .then(response => {
                        alert('Ascent deleted successfully.');
                        this.get_my_ascents_data();
                    })
                    .catch(
                        error => console.log(error)
                    );
                }
            }
        }
    }
</script>