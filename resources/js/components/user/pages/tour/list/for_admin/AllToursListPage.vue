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
                        @update="get_all_tours_data"

                        @del_category="del_category"
                        @show_edit_category_modal="show_edit_category_modal"
                        @show_add_category_modal="show_add_category_modal"

                        @show_user_change_modal="show_user_change_modal"
                        @del_tour="del_tour"
                    />
                </div>
            </div>
        </div>
        
        <editTourCategoryModal 
            ref="tour_category_edit_modal"
            @restart="get_all_tours_data"
        />
        <addTourCategoryModal 
            ref="tour_category_add_modal"
            @restart="get_all_tours_data"
        />

        <change_user_modal
            :active_user_prop="table_info['user']"
            ref="user_relation_modal"
            @update_relation="update_relation"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../../../../items/data_table/TabsComponent.vue'
    import breadcrumb from '../../../../items/BreadcrumbComponent.vue'

    import editTourCategoryModal from "../../../../items/modal/tab_modals/edit/EditTourCategoryModal"
    import addTourCategoryModal from "../../../../items/modal/tab_modals/add/AddTourCategoryModal"

    import change_user_modal from '../../../../items/modal/tab_modals/ChangeUserModalComponent.vue'
    export default {
        components:{
            editTourCategoryModal,
            addTourCategoryModal,
            tabsComponent,
            breadcrumb,
            change_user_modal
        },
    
        data() {
            return {
                data_for_tab: [],
                table_info: []
            }
        },

        mounted() {
            this.get_all_tours_data();
        },
         
        methods: {
            get_all_tours_data: function(){
                this.data_for_tab = []
                axios
                .get("/get_tour/get_all_tours/")
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
                                                        'URL Title',
                                                        'Public',
                                                        'User',
                                                        'Edit user',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['tour', 'id']],
                                                        ['data', ['tour', 'url_title']],
                                                        ['data', ['tour', 'published'], 'bool'],
                                                        ['data', [['user', 'name'], ['user', 'surname']]],
                                                        ['action_fun_id', 'show_user_change_modal', 'btn btn-secondary', '<i class="fa fa-user-plus" aria-hidden="true"></i>', [['user', 'id'], ['tour', 'id']]],
                                                        ['action_router', 'tourEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>', ['tour', 'id']],
                                                        ['action_fun_id', 'del_tour', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>', ['tour', 'id']],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['tour', 'edit'],
                                                        ['tour', 'edit'],
                                                        ['tour', 'edit'],
                                                        ['tour', 'del'],
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
                .get("/get_tour/get_category/get_all_categories/")
                .then(response => {
                    this.data_for_tab.push({
                                            'id': 2,
                                            'table_name': 'Tours categories', 
                                            'add_action': {
                                                'action': 'fun',
                                                'link': 'show_add_category_modal', 
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
                                                        ['data', ['us_name']],
                                                        ['action_fun_id', 'show_edit_category_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_category', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
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
            show_edit_category_modal(category_id){
                this.$refs.tour_category_edit_modal.open_modal(category_id)
            },
            show_add_category_modal(){
                this.$refs.tour_category_add_modal.open_modal()
            },
            del_category(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_tour/set_category/del_category/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_all_tours_data()
                    })
                    .catch(error => console.log(error))
                }
            },
            del_tour(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/set_tour/del_tour/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_all_tours_data()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_user_change_modal(id){
                this.$refs.user_relation_modal.show_modal(id)
            },
            update_relation(user_id){
                if(confirm('Are you sure, you want change user?')){
                    let data
                    if(this.table_info['user'].length != 0){
                        data = {
                            "tour_id": this.table_info[0].id,
                            "new_user_id": user_id,
                            "old_user_id": this.table_info['user'][0].id
                        }
                    }
                    else{
                        data = {
                            "tour_id": this.table_info[0].id,
                            "new_user_id": user_id,
                            "old_user_id": []
                        }
                    }
                    axios
                    .post('/set_tour/change_user_relation/',{
                        data: data
                    })
                    .then(Response => {
                        this.$emit('restart')
                        this.$refs.user_relation_modal.close_modal()
                    })
                    .catch(error => console.log(error))
                }

            },
        }
    }
</script>