<template>
    <stack-modal
            :show="is_role_add_modal"
            :title="$t('admin.users.add_new_role_title')"
            @close="close_role_add_modal()"
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
                <form id="add_role_form">
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
                @click="add_role()"
            >
                {{ is_saving ? $t('admin.articles.updating_ellipsis') : $t('admin.users.save_role_btn') }}
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
            return{
                is_role_add_modal: false,
                is_loading: false,
                is_saving: false,

                permissions: [],
                permission_ids: [],

                role_data: {
                    name: '',
                    description: ''
                },
            }
        },
        methods: {
            show_modal(){
                this.is_role_add_modal = true
                this.is_loading = true
                this.get_permissions()
                    .catch(error => {
                        console.log(error)
                        this.$bus.$emit('toast', {
                            type: 'danger',
                            title: this.$t('admin.users.toast_title'),
                            message: this.$t('admin.users.role_load_error_message'),
                        })
                    })
                    .finally(() => this.is_loading = false)
            },
            close_role_add_modal(){
                this.is_role_add_modal = false
                this.permissions = []
                this.permission_ids = []
                this.role_data = { name: '', description: '' }
            },

            add_role(){
                this.is_saving = true
                axios
                .post("/set_role/create_role/", {
                    role_data: this.role_data,
                    permission_ids: this.permission_ids,
                })
                .then(response => {
                    this.$bus.$emit('toast', {
                        type: 'success',
                        title: this.$t('admin.users.toast_title'),
                        message: this.$t('admin.users.role_created_success_message'),
                    })
                    this.close_role_add_modal()
                    this.$emit('update')
                })
                .catch(error => {
                    console.log(error)
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('admin.users.toast_title'),
                        message: this.$t('admin.users.role_created_error_message'),
                    })
                })
                .finally(() => this.is_saving = false);
            },

            get_permissions(){
                return axios
                .get("/parmisions_list")
                .then(response => {
                    this.permissions = response.data
                });
            },
        }
    }
</script>
