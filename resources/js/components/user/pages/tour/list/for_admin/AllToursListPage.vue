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

                        @del_categories="del_categories"
                        @show_edit_category_modal="show_edit_category_modal"
                        @show_add_category_modal="show_add_category_modal"
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
                                                        'Public',
                                                        'User',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['url_title']],
                                                        ['data', ['published'], 'bool'],
                                                        ['action_fun_id', 'show_user_change_modal', 'btn btn-primary', '<i class="fa fa-user" aria-hidden="true"></i>'],
                                                        ['action_router', 'tourEdit', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_tour', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
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
                .get("/tour/category/get_all_categories/")
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
                                                        ['action_fun_id', 'del_categories', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
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
            show_edit_category_modal(category_id){
                this.$refs.tour_category_edit_modal.open_modal(category_id)
            },
            show_add_category_modal(){
                this.$refs.tour_category_add_modal.open_modal()
            },
            del_categories(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/tour/category/del_category/'+id, {
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
                    .post('/tour/del_tour/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_all_tours_data()
                    })
                    .catch(error => console.log(error))
                }
            },
            show_user_change_modal(){
                this.$refs.user_relation_modal.show_modal()
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
                    .post('/tour/change_user_relation/',{
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