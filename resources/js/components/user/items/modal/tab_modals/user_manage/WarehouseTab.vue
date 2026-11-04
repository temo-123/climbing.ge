<template>
    <div>
        <div v-if="loading" class="text-center py-4 text-muted">
            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
        </div>

        <div v-else-if="!has_permission" class="alert alert-warning mb-0">
            <i class="fa fa-info-circle"></i> {{ $t('admin.users.assign_warehouse_needs_permission_hint') }}
        </div>

        <template v-else>
            <label class="form-label fw-semibold">{{ $t('admin.users.assign_warehouse_select_label') }}</label>
            <select class="form-select" v-model="selected_warehouse_id">
                <option :value="null">{{ $t('admin.users.assign_warehouse_none_option') }}</option>
                <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">
                    {{ warehouse.name }}
                </option>
            </select>
            <small class="form-text text-muted">{{ $t('admin.users.assign_warehouse_hint') }}</small>

            <div class="mt-3">
                <button type="button" class="btn btn-primary" :disabled="saving || selected_warehouse_id === saved_warehouse_id" @click="save">
                    <i class="fa fa-check"></i> {{ saving ? $t('admin.users.saving_ellipsis') : $t('common.save') }}
                </button>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        user_id: { type: [Number, String], required: true },
    },
    data() {
        return {
            loading: true,
            saving: false,
            has_permission: false,
            warehouses: [],
            selected_warehouse_id: null,
            saved_warehouse_id: null,
        }
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
            axios.get('/set_role/get_user_permissions/' + this.user_id)
                .then(response => {
                    const permissions = response.data.permissions || []
                    this.has_permission = permissions.some(p => p.subject === 'warehouse' && p.action === 'sell_own')
                    if (!this.has_permission) return
                    return Promise.all([
                        axios.get('/get_warehouse/get_sale_point_warehouses'),
                        axios.get('/get_warehouse/get_user_warehouse/' + this.user_id),
                    ]).then(([warehouses, assignment]) => {
                        this.warehouses = warehouses.data
                        this.selected_warehouse_id = assignment.data?.warehouse_id || null
                        this.saved_warehouse_id = this.selected_warehouse_id
                    })
                })
                .catch(error => console.log(error))
                .finally(() => this.loading = false)
        },
        save() {
            this.saving = true
            axios.post('/set_warehouse/assign_user_warehouse/' + this.user_id, { warehouse_id: this.selected_warehouse_id })
                .then(() => {
                    this.saved_warehouse_id = this.selected_warehouse_id
                    this.toast('success', this.$t('admin.users.assign_warehouse_saved_message'))
                })
                .catch(error => this.toast('danger', error.response?.data?.error || this.$t('admin.users.role_saved_error_message')))
                .finally(() => this.saving = false)
        },
    },
}
</script>
