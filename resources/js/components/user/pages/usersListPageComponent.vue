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
            <div class="row mb-2">
                <div class="col-sm-12">
                    <button v-if="$can('edit', 'role')" class="btn btn-warning btn-sm" @click="sync_admin_permissions">
                        <i class="fa fa-refresh"></i> Sync Admin Permissions
                    </button>
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

                        @add_permission="show_add_permission_modal"
                        @del_permission="del_permission"

                        @reset_user_password="show_reset_password_modal"
                    />
                </div>
            </div>
        </div>

        <EditUserRoleAndPermissionsModel ref="edit_user_role_modal"

            @show_team_member_modal="$refs.teamMemberStatusModal.show_modal($event)"
            @update="get_users"
        />
        <UserBunModel ref="user_ban_modal" @update="get_users"/>
        <UserInfoModal ref="user_info_modal"/>

        <EditRoleModel ref="edit_role_model"/>
        <AddPermissionModal ref="add_permission_modal" @saved="get_parmisions"/>
        <ResetUserPasswordModal ref="reset_password_modal"/>

        <MemberStatusModal 
            ref="teamMemberStatusModal"

            @go_back="show_edit_user_role_modal($event)"
            @update="get_users"
        />
    </div>
</template>

<script>
    import tabsComponent  from '../items/data_table/TabsComponent.vue'

    import MemberStatusModal from '../items/modal/tab_modals/edit/EditUserMemberStatusModalComponent.vue'

    import EditUserRoleAndPermissionsModel from "../items/modal/tab_modals/edit/EditUserRoleAndPermissionsModelComponent"
    import EditRoleModel from "../items/modal/tab_modals/edit/EditRoleModelComponent"
    import AddPermissionModal from "../items/modal/tab_modals/AddPermissionModalComponent"
    import UserBunModel from "../items/modal/tab_modals/UserBunModelComponent"
    import UserInfoModal from "../items/modal/tab_modals/UserInfoModalComponent"
    import ResetUserPasswordModal from "../items/modal/tab_modals/ResetUserPasswordModalComponent"

    import breadcrumb from '../items/BreadcrumbComponent.vue'
    export default {
        components: {
            tabsComponent ,
            breadcrumb,

            MemberStatusModal,

            EditUserRoleAndPermissionsModel,
            EditRoleModel,
            AddPermissionModal,
            UserBunModel,
            UserInfoModal,
            ResetUserPasswordModal,
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
                this.data_for_tab = []
                this.get_roles()
                this.get_parmisions()

                axios
                .get("/get_user/get_all_users/")
                .then(response => {
                    const existing = this.data_for_tab.findIndex(t => t.id === 1)
                    const tab = {
                        'id': 1,
                        'table_name': 'Users',
                        'tab_data': {
                            'data': response.data,
                            'tab': {
                                'head': [
                                    'ID',
                                    'Name',
                                    'Email',
                                    'Team',
                                    'Verifay',
                                    'Banned',
                                    'Role',
                                    'Ban',
                                    'Reset',
                                    'Delite',
                                ],
                                'body': [
                                    ['data', ['id']],
                                    ['data_action_id', ['name'], ['surname'], 'user_info_modal'],
                                    ['data', ['email']],
                                    ['data', ['is_team_member'],'bool'],
                                    ['data', ['email_verified_at'],'bool_2'],
                                    ['data', ['is_banned'],'bool'],
                                    ['action_fun_id', 'edit_user_role', 'btn btn-primary btn-sm', 'Edit Role'],
                                    ['action_fun_id', 'user_ban', 'btn btn-warning btn-sm', '<i class="fa fa-ban" aria-hidden="true"></i>'],
                                    ['action_fun_id', 'reset_user_password', 'btn btn-secondary btn-sm', '<i class="fa fa-key" aria-hidden="true"></i>'],
                                    ['action_fun_id', 'del_user', 'btn btn-danger btn-sm', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                                ],
                                'perm': [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['user', 'edit_permissions'],
                                    ['user', 'create_ban'],
                                    ['user', 'edit'],
                                    ['user', 'del'],
                                ]
                            }
                        },
                    }
                    if (existing !== -1) {
                        this.data_for_tab.splice(existing, 1, tab)
                    } else {
                        this.data_for_tab.push(tab)
                    }
                })
                .catch(error => {
                    console.log(error)
                    if (this.data_for_tab.findIndex(t => t.id === 1) === -1) {
                        this.data_for_tab.push({ 'id': 1, 'table_name': 'Users', 'tab_data': { 'data': [], 'tab': { 'head': [], 'body': [], 'perm': [] } } })
                    }
                });
            },
            get_roles(){
                axios
                .get("/set_role")
                .then(response => {
                    const existing = this.data_for_tab.findIndex(t => t.id === 3)
                    const tab = {
                        'id': 3,
                        'table_name': 'Roles',
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
                                    ['role', 'edit'],
                                    ['role', 'del'],
                                ]
                            }
                        },
                    }
                    if (existing !== -1) {
                        this.data_for_tab.splice(existing, 1, tab)
                    } else {
                        this.data_for_tab.push(tab)
                    }
                })
                .catch(error => {
                    console.log(error)
                    if (this.data_for_tab.findIndex(t => t.id === 3) === -1) {
                        this.data_for_tab.push({ 'id': 3, 'table_name': 'Roles', 'tab_data': { 'data': [], 'tab': { 'head': [], 'body': [], 'perm': [] } } })
                    }
                });
            },
            get_parmisions(){
                axios
                .get("/parmisions_list")
                .then(response => {
                    const existing = this.data_for_tab.findIndex(t => t.id === 2)
                    const tab = {
                        'id': 2,
                        'table_name': 'Permissions',
                        'add_action': {
                            'action': 'fun',
                            'link': 'add_permission',
                            'class': 'btn btn-primary',
                            'btn_title': 'Add Permission'
                        },
                        'tab_data': {
                            'data': response.data,
                            'tab': {
                                'head': ['ID', 'Subject', 'Action', 'Delete'],
                                'body': [
                                    ['data', ['id']],
                                    ['data', ['subject']],
                                    ['data', ['action']],
                                    ['action_fun_id', 'del_permission', 'btn btn-danger', '<i class="fa fa-trash"></i>'],
                                ],
                                'perm': [
                                    ['no'],
                                    ['no'],
                                    ['no'],
                                    ['role', 'del'],
                                ]
                            }
                        },
                    }
                    if (existing !== -1) {
                        this.data_for_tab.splice(existing, 1, tab)
                    } else {
                        this.data_for_tab.push(tab)
                    }
                })
                .catch(error => {
                    console.log(error)
                    if (this.data_for_tab.findIndex(t => t.id === 2) === -1) {
                        this.data_for_tab.push({ 'id': 2, 'table_name': 'Permissions', 'tab_data': { 'data': [], 'tab': { 'head': [], 'body': [], 'perm': [] } } })
                    }
                });
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
                    .post('/set_user/del_user/'+id, {
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
                    .post('/set_role/del_user_pemisino/'+id+'/'+this.action_user, {
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
                    .post('/set_role/del_role/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_users()
                    })
                    .catch(error => console.log(error))
                }
            },

            show_add_permission_modal(){
                this.$refs.add_permission_modal.show_modal()
            },

            show_reset_password_modal(id){
                this.$refs.reset_password_modal.show_modal(id)
            },

            del_permission(id){
                if(!confirm('Delete this permission? It will be removed from all roles and users.')) return
                axios.post('/set_permission/destroy/'+id, { _method: 'DELETE' })
                    .then(() => this.get_parmisions())
                    .catch(error => console.log(error))
            },

            sync_admin_permissions(){
                if(!confirm('This will add all missing permissions to the Admin role. Continue?')) return
                axios.post('/set_role/sync_admin_permissions')
                    .then(res => {
                        alert(`Done. Added ${res.data.added} missing permission(s) to Admin role.`)
                        this.get_users()
                    })
                    .catch(error => console.log(error))
            },
        }
    }
</script>

<style>

</style>