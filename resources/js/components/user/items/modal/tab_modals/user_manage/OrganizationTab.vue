<template>
    <div>
        <div v-if="loading" class="text-center py-4 text-muted">
            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
        </div>

        <template v-else>
            <div class="alert py-2" :class="status.is_member ? 'alert-success' : 'alert-secondary'">
                <template v-if="status.is_member">
                    {{ $t('admin.partner_organizations.currently_member_of') }} <strong>{{ status.organization_name }}</strong>.
                </template>
                <template v-else>{{ $t('admin.partner_organizations.not_a_member') }}</template>
            </div>

            <label class="form-label fw-semibold">{{ $t('admin.partner_organizations.select_organization') }}</label>
            <select class="form-select" v-model="selected_organization_id">
                <option :value="null" disabled>{{ $t('admin.partner_organizations.select_organization') }}</option>
                <option v-for="organization in organizations" :key="organization.id" :value="organization.id">{{ organization.name }}</option>
            </select>

            <div class="mt-3 d-flex flex-wrap gap-2">
                <button
                    type="button"
                    class="btn btn-primary"
                    :disabled="!selected_organization_id || selected_organization_id === status.organization_id || saving"
                    @click="assign"
                >
                    {{ status.is_member ? $t('admin.partner_organizations.change_organization_btn') : $t('admin.partner_organizations.assign_organization_btn') }}
                </button>
                <button v-if="status.is_member" type="button" class="btn btn-outline-danger" :disabled="saving" @click="unassign">
                    {{ $t('admin.partner_organizations.remove_from_organization_btn') }}
                </button>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    emits: ['saved'],
    props: {
        user_id: { type: [Number, String], required: true },
    },
    data() {
        return {
            loading: true,
            saving: false,
            organizations: [],
            selected_organization_id: null,
            status: { is_member: false, organization_id: null, organization_name: null },
        }
    },
    mounted() {
        this.load()
    },
    methods: {
        load() {
            this.loading = true
            Promise.all([
                axios.get('/set_partner_organization/get_all_organizations'),
                axios.get('/set_partner_organization_member/get_user_status/' + this.user_id),
            ]).then(([organizations, status]) => {
                this.organizations = organizations.data
                this.status = status.data
                this.selected_organization_id = status.data.organization_id
            })
            .catch(error => console.log(error))
            .finally(() => this.loading = false)
        },
        assign() {
            this.saving = true
            axios.post('/set_partner_organization_member/assign_user/' + this.user_id, { organization_id: this.selected_organization_id })
                .then(() => { this.$emit('saved'); this.load() })
                .catch(error => console.log(error))
                .finally(() => this.saving = false)
        },
        unassign() {
            this.saving = true
            axios.delete('/set_partner_organization_member/unassign_user/' + this.user_id)
                .then(() => { this.$emit('saved'); this.load() })
                .catch(error => console.log(error))
                .finally(() => this.saving = false)
        },
    },
}
</script>
