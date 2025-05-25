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

                        @del_user="del_user"
                        @del_user_pemisino_from_db="del_user_pemisino_from_db"
                        @user_ban="show_user_ban_modal"
                        @edit_user_role="show_edit_user_role_modal"
                        @user_info_modal="show_user_info_modal"

                        @show_edit_role_modal="show_edit_role_modal"
                        @del_role="del_role"
                    />
                </div>
            </div>
        </div>

        <EditUserRoleAndPermissionsModel ref="edit_user_role_modal"/>
        <UserBunModel ref="user_ban_modal"/>
        <UserInfoModal ref="user_info_modal"/>

        <EditRoleModel ref="edit_role_model"/>
    </div>
</template>

<script>
    import tabsComponent  from '../items/data_table/TabsComponent.vue'

    import EditUserRoleAndPermissionsModel from "../items/modal/tab_modals/edit/EditUserRoleAndPermissionsModelComponent"
    import EditRoleModel from "../items/modal/tab_modals/edit/EditRoleModelComponent"
    import UserBunModel from "../items/modal/tab_modals/UserBunModelComponent"
    import UserInfoModal from "../items/modal/tab_modals/UserInfoModalComponent"

    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,

            EditUserRoleAndPermissionsModel,
            EditRoleModel,
            UserBunModel,
            UserInfoModal,
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
            get_users(){
                this.data_for_tab = [],

                axios
                .get("/user/get_all_users/")
                .then(response => {
                    this.data_for_tab.push
                                        ({  
                                            'id': 1,
                                            'table_name': 'Users', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'articleAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Email',
                                                        'Verifay',
                                                        'Role',
                                                        'Ban',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['name'], ['surname'], 'user_info_modal'],
                                                        ['data', ['email']],
                                                        ['data', ['email_verified_at'],'bool_2'],
                                                        ['action_fun_id', 'edit_user_role', 'btn btn-primary', 'Edit Role'],
                                                        ['action_fun_id', 'user_ban', 'btn btn-warning', '<i class="fa fa-times" aria-hidden="true"></i>'],
                                                        ['action_fun_id', 'del_user', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['user', 'edit_permissions'],
                                                        ['user', 'create_ban'],
                                                        ['user', 'del'],
                                                    ]
                                                }
                                            },
                                        })
                                        ;
                    
                    this.get_roles()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_roles(){
                axios
                .get("/role/")
                .then(response => {
                    this.data_for_tab.push
                                        ({  
                                            'id': 3,
                                            'table_name': 'Roles', 
                                            'add_action': {
                                                'action': 'route',
                                                'link': 'articleAdd', 
                                                'class': 'btn btn-primary'
                                            },
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'Name',
                                                        'Slug',
                                                        'Edit',
                                                        'Delite',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data_action_id', ['name']],
                                                        ['data', ['slug']],
                                                        ['action_fun_id', 'show_edit_role_modal', 'btn btn-primary', '<i aria-hidden="true" class="fa fa-pencil"></i>'],
                                                        ['action_fun_id', 'del_role', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                        ['article', 'edit'],
                                                        ['article', 'del'],
                                                    ]
                                                }
                                            },
                                        });
                    
                    this.get_parmisions()
                })
                .catch(
                    error => console.log(error)
                );
            },
            get_parmisions(){
                axios
                .get("/parmisions_list/")
                .then(response => {
                    this.data_for_tab.push
                                        ({  
                                            'id': 2,
                                            'table_name': 'Parmissions', 
                                            'tab_data': {
                                                'data': response.data, 
                                                'tab': {
                                                    'head': [
                                                        'ID',
                                                        'subject',
                                                        'Action',
                                                    ],
                                                    'body': [
                                                        ['data', ['id']],
                                                        ['data', ['subject']],
                                                        ['data', ['action']],
                                                    ],
                                                    'perm': [
                                                        ['no'],
                                                        ['no'],
                                                        ['no'],
                                                    ]
                                                }
                                            },
                                        })
                                        ;
                })
                .catch(
                    error => console.log(error)
                );
            },

            show_edit_role_modal(id){
                this.$refs.edit_role_model.show_modal(id)
            },

            show_edit_user_role_modal(id){
                this.$refs.edit_user_role_modal.show_modal(id)
            },

            show_user_ban_modal(id){
                this.$refs.user_ban_modal.show_modal(id)
            },

            show_user_info_modal(id){
                this.$refs.user_info_modal.show_modal(id)
            },

            del_user(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/user/del_user/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        if(Response.data != 'You don`t can delete yourself! :)'){
                            // alert(Response.data);
                            // this.$emit('restart')
                            this.get_users()
                        }
                        else if(Response.data == 'You don`t can delete yourself! :)'){
                            alert(Response.data);
                        }
                    })
                    .catch(error => console.log(error))
                }
            },

            del_user_pemisino_from_db(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/role/del_user_pemisino/'+id+'/'+this.action_user, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        // this.$emit('restart')
                        this.get_users()
                    })
                    .catch(error => console.log(error))
                }
            },

            del_role(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/role/del_role/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        // this.$emit('restart')
                        this.get_users()
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>