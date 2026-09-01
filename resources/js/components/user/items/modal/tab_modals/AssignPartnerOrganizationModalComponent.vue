<template>
    <StackModal
        v-model="is_modal_open"
        :title="modalTitle"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        @close="close_modal()"
    >
        <div v-if="loading" class="text-center py-4">
            <i class="fa fa-spinner fa-spin"></i>
        </div>

        <div v-else>
            <div class="alert alert-success" v-if="status.is_member">
                {{ $t('admin.partner_organizations.currently_member_of') }} <strong>{{ status.organization_name }}</strong>.
            </div>
            <div class="alert alert-warning" v-else>
                {{ $t('admin.partner_organizations.not_a_member') }}
            </div>

            <div class="form-group mt-3">
                <label>{{ $t('admin.partner_organizations.select_organization') }}</label>
                <select class="form-control" v-model="selected_organization_id">
                    <option :value="null" disabled>{{ $t('admin.partner_organizations.select_organization') }}</option>
                    <option v-for="organization in organizations" :key="organization.id" :value="organization.id">{{ organization.name }}</option>
                </select>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button
                    class="btn btn-primary"
                    :disabled="!selected_organization_id || saving"
                    @click="assign()"
                >
                    {{ status.is_member ? $t('admin.partner_organizations.change_organization_btn') : $t('admin.partner_organizations.assign_organization_btn') }}
                </button>
                <button
                    v-if="status.is_member"
                    class="btn btn-danger"
                    :disabled="saving"
                    @click="unassign()"
                >
                    {{ $t('admin.partner_organizations.remove_from_organization_btn') }}
                </button>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    emits: ['update'],
    data() {
        return {
            is_modal_open: false,
            loading: false,
            saving: false,
            user_id: null,
            user_name: '',
            organizations: [],
            selected_organization_id: null,
            status: { is_member: false, organization_id: null, organization_name: null },
        };
    },
    computed: {
        modalTitle() {
            return this.user_name
                ? this.$t('admin.partner_organizations.assign_prefix', { name: this.user_name })
                : this.$t('admin.partner_organizations.assign_title');
        }
    },
    methods: {
        show_modal(user_id, user_name = '') {
            this.user_id = user_id;
            this.user_name = user_name;
            this.selected_organization_id = null;
            this.is_modal_open = true;
            this.load();
        },

        close_modal() {
            this.is_modal_open = false;
        },

        load() {
            this.loading = true;
            Promise.all([
                axios.get('/set_partner_organization/get_all_organizations'),
                axios.get('/set_partner_organization_member/get_user_status/' + this.user_id),
            ]).then(([organizations, status]) => {
                this.organizations = organizations.data;
                this.status = status.data;
                this.selected_organization_id = status.data.organization_id;
            }).catch(() => {})
            .finally(() => this.loading = false);
        },

        assign() {
            if (!this.selected_organization_id) return;
            this.saving = true;
            axios.post('/set_partner_organization_member/assign_user/' + this.user_id, { organization_id: this.selected_organization_id })
                .then(() => {
                    this.$emit('update');
                    this.load();
                })
                .catch(() => {})
                .finally(() => this.saving = false);
        },

        unassign() {
            this.saving = true;
            axios.delete('/set_partner_organization_member/unassign_user/' + this.user_id)
                .then(() => {
                    this.$emit('update');
                    this.load();
                })
                .catch(() => {})
                .finally(() => this.saving = false);
        },
    }
}
</script>
