<template>
    <stack-modal
            :show="is_role_edit_modal"
            :title="$t('admin.users.edit_role_permissions_title')"
            @close="close_role_edit_modal()"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false }"
        >
        <div>
            <span v-show="is_loading">
                <div class="col-md-4">
                    <img :src="'/images/site_img/loading.gif'" alt="loading">
                </div>
            </span>
            <span v-show="!is_loading">
                <form id="edit_role_form">
                    <input
                        type="text"
                        class="form-control mb-2"
                        id="name"
                        v-model="role_data.name"
                        :placeholder="$t('admin.users.enter_role_name_placeholder')"
                        required
                    />

                    <input
                        type="text"
                        class="form-control mb-3"
                        id="short description"
                        v-model="role_data.description"
                        :placeholder="$t('admin.users.enter_short_description_placeholder')"
                        required
                    />
                </form>

                <RolePermissionsPicker
                    :permissions="permissions"
                    v-model="permission_ids"
                />
            </span>
        </div>
        <template #footer>
            <button
                type="button"
                class="btn btn-primary"
                :disabled="is_saving"
                @click="edit_role()"
            >
                {{ is_saving ? $t('admin.articles.updating_ellipsis') : $t('admin.users.save_updated_role_btn') }}
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
                is_role_edit_modal: false,
                is_loading: false,
                is_saving: false,

                role_data: {
                    name: '',
                    description: ''
                },

                permissions: [],
                permission_ids: [],

                action_role: '',
                role_id: 0,
            }
        },
        methods: {
            show_modal(role_id){
                this.is_role_edit_modal = true
                this.action_role = role_id
                this.role_id = role_id
                this.is_loading = true

                Promise.all([
                    this.get_editing_role(role_id),
                    this.get_editing_role_permissions(role_id),
                    this.get_permissions(role_id),
                ])
                .catch(error => {
                    console.log(error)
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('admin.users.toast_title'),
                        message: this.$t('admin.users.role_load_error_message'),
                    })
                })
                .finally(() => {
                    this.is_loading = false
                })
            },
            close_role_edit_modal(){
                this.is_role_edit_modal = false
                this.action_role = ''
                this.role_id = 0
                this.role_data = { name: '', description: '' }
                this.permissions = []
                this.permission_ids = []
            },

            edit_role(){
                this.is_saving = true
                axios
                .post("/set_role/edit_role/" + this.role_id, {
                    role: this.role_data,
                    permission_ids: this.permission_ids,
                })
                .then(response => {
                    this.$bus.$emit('toast', {
                        type: 'success',
                        title: this.$t('admin.users.toast_title'),
                        message: this.$t('admin.users.role_saved_success_message'),
                    })
                    this.close_role_edit_modal()
                    this.$emit('update')
                })
                .catch(error => {
                    console.log(error)
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('admin.users.toast_title'),
                        message: this.$t('admin.users.role_saved_error_message'),
                    })
                })
                .finally(() => this.is_saving = false);
            },

            get_editing_role(role_id){
                return axios
                .get("/set_role/get_editing_role/" + role_id)
                .then(response => {
                    this.role_data = {
                        name: response.data.name,
                        description: response.data.description
                    }
                });
            },

            get_editing_role_permissions(role_id){
                return axios
                .get("/set_role/get_editing_role_permissions/" + role_id)
                .then(response => {
                    this.permission_ids = response.data.map(permission => permission.id)
                });
            },

            get_permissions(role_id){
                return axios
                .get("/set_permission/get_parmisions_for_role/" + role_id)
                .then(response => {
                    this.permissions = response.data
                });
            },
        }
    }
</script>
