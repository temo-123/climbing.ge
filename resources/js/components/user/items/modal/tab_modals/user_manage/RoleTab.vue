<template>
    <div>
        <div v-if="loading" class="text-center py-4 text-muted">
            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
        </div>

        <template v-else>
            <div class="mb-4">
                <label class="form-label fw-semibold">{{ $t('admin.users.select_user_role_title') }}</label>
                <select class="form-select" v-model="user_role">
                    <option value="no_role" disabled>{{ $t('admin.users.choose_role_placeholder') }}</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                </select>
            </div>

            <h6 class="fw-semibold">{{ $t('admin.users.additional_permissions_title') }}</h6>

            <div v-if="user_permissions.length" class="mb-3">
                <div class="small text-muted mb-1">{{ $t('admin.users.currently_assigned_permissions_title') }}</div>
                <div class="d-flex flex-wrap gap-2">
                    <span v-for="permission in user_permissions" :key="permission.id" class="perm-chip">
                        {{ permission.subject }} · {{ permission.action }}
                        <button
                            type="button"
                            class="perm-chip__remove"
                            :title="$t('admin.users.remove_permission_tooltip')"
                            :aria-label="$t('admin.users.remove_permission_tooltip')"
                            @click="remove_permission(permission.id)"
                        >
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
            </div>

            <div class="small text-muted mb-1">{{ $t('admin.users.add_more_permissions_title') }}</div>
            <RolePermissionsPicker
                :permissions="assignable_permissions"
                v-model="new_permission_ids"
            />

            <div class="mt-3">
                <button type="button" class="btn btn-primary" :disabled="saving" @click="save">
                    <i class="fa fa-check"></i> {{ saving ? $t('admin.users.saving_ellipsis') : $t('admin.users.save_permissions_btn') }}
                </button>
            </div>
        </template>
    </div>
</template>

<script>
import RolePermissionsPicker from '../RolePermissionsPickerComponent.vue'

export default {
    emits: ['saved'],
    components: { RolePermissionsPicker },
    props: {
        user_id: { type: [Number, String], required: true },
    },
    data() {
        return {
            loading: true,
            saving: false,
            roles: [],
            permissions: [],
            user_role: 'no_role',
            user_permissions: [],
            new_permission_ids: [],
        }
    },
    computed: {
        assignable_permissions() {
            const assigned = new Set(this.user_permissions.map(p => p.id))
            return this.permissions.filter(p => !assigned.has(p.id))
        },
    },
    mounted() {
        this.load()
    },
    methods: {
        toast(type, message) {
            this.$bus.$emit('toast', { type, title: this.$t('admin.users.toast_title'), message })
        },
        load() {
            this.loading = true
            Promise.all([
                axios.get('/set_role'),
                axios.get('/parmisions_list'),
                this.load_user_permissions(),
            ]).then(([roles, permissions]) => {
                this.roles = roles.data
                this.permissions = permissions.data
            })
            .catch(error => console.log(error))
            .finally(() => this.loading = false)
        },
        load_user_permissions() {
            return axios.get('/set_role/get_user_permissions/' + this.user_id)
                .then(response => {
                    this.user_role = response.data.role?.id || 'no_role'
                    this.user_permissions = response.data.permissions || []
                })
        },
        save() {
            this.saving = true
            axios.post('/set_role/edit_permissions_and_role/' + this.user_id, {
                new_permissions: this.new_permission_ids.map(permission_id => ({ permission_id })),
                role: this.user_role,
            })
            .then(() => {
                this.toast('success', this.$t('admin.users.role_saved_success_message'))
                this.new_permission_ids = []
                this.$emit('saved')
                return this.load_user_permissions()
            })
            .catch(() => this.toast('danger', this.$t('admin.users.role_saved_error_message')))
            .finally(() => this.saving = false)
        },
        remove_permission(permission_id) {
            if (!confirm(this.$t('admin.users.confirm_remove_permission'))) return
            axios.post('/set_role/del_user_pemisino/' + permission_id + '/' + this.user_id, { _method: 'DELETE' })
                .then(() => {
                    this.$emit('saved')
                    return this.load_user_permissions()
                })
                .catch(() => this.toast('danger', this.$t('admin.users.role_saved_error_message')))
        },
    },
}
</script>

<style scoped>
.perm-chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .2rem .25rem .2rem .6rem;
    border-radius: 999px;
    background: var(--bs-secondary-bg);
    border: 1px solid var(--bs-border-color);
    font-size: .85rem;
}
.perm-chip__remove {
    border: 0;
    background: transparent;
    color: var(--bs-danger);
    width: 1.4rem;
    height: 1.4rem;
    border-radius: 50%;
    line-height: 1;
}
.perm-chip__remove:hover {
    background: var(--bs-danger-bg-subtle);
}
</style>
