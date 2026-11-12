<template>
    <!-- Users page → Guest commenters tab (commenter › show): people who commented with only an
         e-mail (non_registered_commenters), confirmed or not; delete one or many.
         Was the /non_registered_commenter_list page. docs/FUNCTIONS_DOCS/user/users_dashboard.md -->
    <div>
        <p class="text-muted small mb-2">{{ $t('admin.user_stats.commenters_hint') }}</p>
        <tabsComponent
            :table_data="data_for_tab"
            @update="get_users"
            @del_xuser="del_xuser"
            @delete_selected="bulk_delete_xusers"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../data_table/TabsComponent.vue'

    export default {
        components: {
            tabsComponent ,
        },
        data(){
            return {
                data_for_tab: [],
            }
        },
        mounted() {
            this.get_users()
        },
        methods: {
            del_xuser(id){
                if(confirm(this.$t('admin.common.confirm_delete'))){
                    axios
                    .delete("/set_non_registered_commenter/del_non_registered_commenter/"+id)
                    .then(response => {;
                        this.get_users()
                    })
                    .catch(
                        error => console.log(error)
                    );

                }
            },
            bulk_delete_xusers(ids){
                axios
                .post("/set_non_registered_commenter/bulk_delete", { ids })
                .then(response => {
                    this.get_users()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_users(){
                this.data_for_tab = [],

                axios
                .get("/set_non_registered_commenter/get_non_registered_commenter")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 1,
                                            'table_name': this.$t('admin.comments.non_registered_commenters_table'),
                                            'tab_data': {
                                                // Row checkboxes (used for bulk actions) key off a
                                                // top-level `id`, but this endpoint nests it under
                                                // `comenter.id` — mirror it up so selection works.
                                                'data': (response.data || []).map(item => ({ ...item, id: item.comenter?.id })),
                                                'tab': {
                                                    'head': [
                                                        this.$t('common.id'),
                                                        this.$t('common.email'),
                                                        this.$t('admin.comments.confirmed_col'),
                                                        this.$t('common.delete'),
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