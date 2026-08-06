<template>
    <stack-modal
            :show="is_role_editing_modal"
            :title="$t('admin.users.manage_user_roles_permissions_title')"
            @close="close_modal()"
            :saveButton="{ visible: true, title: $t('admin.users.save_changes_btn'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: $t('admin.comments.cancel_btn'), btnClass: { 'btn btn-secondary': true } }"
        >
        <div>
            <span v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                </div>
            </span>
            <span v-show="!is_loading">
                <h2>{{ $t('admin.users.select_user_role_title') }}</h2>

                <select class="form-control" v-model="user_role" v-if="!role_loading">
                    <option value="no_role" disabled>{{ $t('admin.users.choose_role_placeholder') }}</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                </select>

                <div class="row justify-content-center" v-if="role_loading">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <h2>{{ $t('admin.users.additional_permissions_title') }}</h2>

                <h3 v-if="user_permissions.length != 0">{{ $t('admin.users.currently_assigned_permissions_title') }}</h3>
                <table v-if="user_permissions.length != 0" class="table table-hover" id="dev-table">
                    <thead>
                        <tr>
                            <th>{{ $t('admin.users.col_permission') }}</th>
                            <th></th>
                            <th>{{ $t('admin.users.col_remove') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="permission in user_permissions" :key="permission.id">
                            <td>
                                {{ permission.subject }} {{ permission.action }}
                            </td>
                            <td></td>
                            <td>
                                <button type="button" class="btn btn-danger" @click="del_user_permission_from_db(permission.id)" :title="$t('admin.users.remove_permission_tooltip')"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h3>{{ $t('admin.users.add_more_permissions_title') }}</h3>

                <RolePermissionsPicker
                    v-if="!perm_loading"
                    :permissions="assignablePermissions"
                    v-model="new_permission_ids"
                />
                <div class="row justify-content-center" v-if="perm_loading">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>
            </span>
        </div>
        <template #footer>
            <button type="button" class="btn btn-success" @click="show_team_member_modal()">
                {{ $t('admin.users.edit_team_member_btn') }}
            </button>
            <button v-show="!is_loading" type="button" class="btn btn-primary" @click="edit_permissions(user_id)">
                {{ $t('admin.users.save_permissions_btn') }}
            </button>
        </template>
    </stack-modal>
</template>


<script>
    import RolePermissionsPicker from '../RolePermissionsPickerComponent.vue'

    export default {
        components: {
            RolePermissionsPicker,
        },
        data(){
            return {
                is_role_editing_modal: false,

                perm_loading: false,
                role_loading: false,
                is_loading: false,

                user_role: 'no_role',
                user_permissions: [],

                roles: [],
                permissions: [],

                new_permission_ids: [],

                user_id: '',
            }
        },
        computed: {
            assignablePermissions(){
                const assignedIds = new Set(this.user_permissions.map(permission => permission.id))
                return this.permissions.filter(permission => !assignedIds.has(permission.id))
            },
        },
        methods: {
            show_modal(id){
                this.is_role_editing_modal = true
                this.user_id = id
                this.get_user_permissions_and_roles()
                this.get_roles()
                this.get_permissions()
            },
            close_modal(){
                this.is_role_editing_modal = false

                this.user_role = 'no_role'
                this.user_permissions = []

                this.roles = []
                this.permissions = []

                this.new_permission_ids = []

                this.user_id = ''
            },

            show_team_member_modal(){
                this.$emit('show_team_member_modal', this.user_id);
                this.close_modal();
            },

            edit_permissions(){
                this.is_loading = true
                axios
                .post("/set_role/edit_permissions_and_role/"+this.user_id, {
                    new_permissions: this.new_permission_ids.map(permission_id => ({ permission_id })),
                    role: this.user_role,
                })
                .then(response => {
                    this.$bus.$emit('toast', {
                        type: 'success',
                        title: this.$t('admin.users.toast_title'),
                        message: this.$t('admin.users.role_saved_success_message'),
                    })
                    this.close_modal()

                    this.$emit('update');
                })
                .catch(error => {
                    console.log('Error saving permissions and role:', error)
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('admin.users.toast_title'),
                        message: this.$t('admin.users.role_saved_error_message'),
                    })
                })
                .finally(() => this.is_loading = false);
            },

            del_user_permission_from_db(id){
                if(confirm(this.$t('admin.users.confirm_remove_permission'))){
                    axios
                    .post('/set_role/del_user_pemisino/'+id+'/'+this.user_id, {
                        _method: 'DELETE'
                    })
                    .then(response => {
                        this.get_user_permissions_and_roles()
                    })
                    .catch(error => {
                        console.log('Error removing permission:', error)
                        this.$bus.$emit('toast', {
                            type: 'danger',
                            title: this.$t('admin.users.toast_title'),
                            message: this.$t('admin.users.role_saved_error_message'),
                        })
                    })
                }
            },

            get_user_permissions_and_roles(){
                axios
                .get("/set_role/get_user_permissions/"+this.user_id)
                .then(response => {
                    if(response.data.role && response.data.role.id){
                        this.user_role = response.data.role.id
                    }
                    if(response.data.permissions && response.data.permissions.length != 0){
                        this.user_permissions = response.data.permissions
                    }
                })
                .catch(
                    error => console.log('Error fetching user permissions and roles:', error)
                );
            },

            get_roles(){
                this.role_loading = true
                axios
                .get("/set_role")
                .then(response => {
                    this.roles = response.data
                })
                .catch(
                    error => console.log('Error fetching roles:', error)
                )
                .finally(() => this.role_loading = false);
            },

            get_permissions(){
                this.perm_loading = true
                axios
                .get("/parmisions_list")
                .then(response => {
                    this.permissions = response.data
                })
                .catch(
                    error => console.log('Error fetching permissions:', error)
                )
                .finally(() => this.perm_loading = false);
            },
        }
    }
</script>