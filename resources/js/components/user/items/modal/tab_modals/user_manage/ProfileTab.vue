<template>
    <div>
        <form @submit.prevent="save">
            <fieldset :disabled="!can_edit || saving">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('common.name') }} *</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }" v-model.trim="form.name" required>
                        <div class="invalid-feedback">{{ first(errors.name) }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('common.surname') }} *</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.surname }" v-model.trim="form.surname" required>
                        <div class="invalid-feedback">{{ first(errors.surname) }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('common.email') }} *</label>
                        <input type="email" class="form-control" :class="{ 'is-invalid': errors.email }" v-model.trim="form.email" required>
                        <div class="invalid-feedback">{{ first(errors.email) }}</div>
                        <small v-if="email_changed" class="form-text text-warning">
                            <i class="fa fa-exclamation-triangle"></i> {{ $t('admin.users.email_change_unverify_hint') }}
                        </small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('admin.orders.phone_label') }}</label>
                        <input type="text" class="form-control" :class="{ 'is-invalid': errors.phone_number }" v-model.trim="form.phone_number">
                        <div class="invalid-feedback">{{ first(errors.phone_number) }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('admin.users.country_label') }}</label>
                        <input type="text" class="form-control" v-model.trim="form.country">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('admin.orders.city_label') }}</label>
                        <input type="text" class="form-control" v-model.trim="form.city">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $t('admin.users.birthday') }}</label>
                        <input type="date" class="form-control" :class="{ 'is-invalid': errors.birthday }" v-model="form.birthday" :max="today" min="1900-01-02">
                        <div class="invalid-feedback">{{ first(errors.birthday) }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ $t('admin.users.bio_label') }}</label>
                        <textarea class="form-control" rows="3" v-model="form.my_bio"></textarea>
                    </div>
                </div>
            </fieldset>

            <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                <button v-if="can_edit" type="submit" class="btn btn-primary" :disabled="saving || !profile_dirty">
                    <i class="fa fa-check"></i> {{ saving ? $t('admin.users.saving_ellipsis') : $t('common.save') }}
                </button>
                <button v-if="can_edit && profile_dirty" type="button" class="btn btn-light" :disabled="saving" @click="reset_form">
                    {{ $t('common.cancel') }}
                </button>

                <span class="ms-auto"></span>

                <template v-if="!user.email_verified_at">
                    <span class="text-danger small">
                        <i class="fa fa-exclamation-circle"></i> {{ $t('admin.users.not_verified_badge') }}
                    </span>
                    <button
                        v-if="can_edit"
                        type="button"
                        class="btn btn-sm btn-outline-secondary"
                        :disabled="resending || verifying"
                        @click="resend_verification"
                    >
                        <i class="fa fa-paper-plane"></i> {{ $t('admin.users.resend_verification_btn') }}
                    </button>
                    <button
                        v-if="can_edit"
                        type="button"
                        class="btn btn-sm btn-outline-success"
                        :disabled="verifying"
                        @click="verify"
                    >
                        <i class="fa fa-check"></i> {{ $t('admin.notification_analytics.verify_user_btn') }}
                    </button>
                </template>
                <span v-else class="text-success small">
                    <i class="fa fa-check-circle"></i> {{ $t('admin.tour.verification_completed') }}
                </span>
            </div>
        </form>

        <!-- Team membership -->
        <div v-if="$can('edit', 'team')" class="border rounded p-3 mt-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="user_manage_team" v-model="team.is_team_member">
                <label class="form-check-label fw-semibold" for="user_manage_team">{{ $t('admin.users.is_team_member_label') }}</label>
            </div>
            <small class="form-text text-muted">{{ $t('admin.users.is_team_member_hint') }}</small>

            <div v-if="team.is_team_member" class="mt-2">
                <label class="form-label" for="user_manage_member_status">{{ $t('admin.users.member_status_label') }}</label>
                <textarea
                    id="user_manage_member_status"
                    class="form-control"
                    rows="2"
                    v-model="team.member_status"
                    :placeholder="$t('admin.users.member_status_placeholder')"
                ></textarea>
            </div>

            <button
                type="button"
                class="btn btn-sm btn-primary mt-2"
                :disabled="saving_team || !team_dirty"
                @click="save_team"
            >
                {{ saving_team ? $t('admin.users.saving_ellipsis') : $t('admin.users.save_team_btn') }}
            </button>
        </div>
    </div>
</template>

<script>
const PROFILE_FIELDS = ['name', 'surname', 'email', 'phone_number', 'country', 'city', 'birthday', 'my_bio']

export default {
    emits: ['saved'],
    props: {
        user: { type: Object, required: true },
    },
    data() {
        return {
            form: {},
            team: { is_team_member: false, member_status: '' },
            errors: {},
            saving: false,
            saving_team: false,
            verifying: false,
            resending: false,
        }
    },
    computed: {
        // local YYYY-MM-DD — the birthday must be before today
        today() {
            const d = new Date()
            return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
        },
        can_edit() {
            return this.$can('edit', 'user')
        },
        email_changed() {
            return (this.form.email || '') !== (this.user.email || '')
        },
        profile_dirty() {
            return PROFILE_FIELDS.some(f => (this.form[f] ?? '') !== (this.user[f] ?? ''))
        },
        team_dirty() {
            return !!this.team.is_team_member !== !!this.user.is_team_member
                || (this.team.member_status || '') !== (this.user.member_status || '')
        },
    },
    created() {
        this.reset_form()
        this.team = {
            is_team_member: !!this.user.is_team_member,
            member_status: this.user.member_status || '',
        }
    },
    methods: {
        first(list) {
            return Array.isArray(list) ? list[0] : list
        },
        reset_form() {
            const form = {}
            PROFILE_FIELDS.forEach(f => { form[f] = this.user[f] ?? '' })
            this.form = form
            this.errors = {}
        },
        toast(type, message) {
            this.$bus.$emit('toast', { type, title: this.$t('admin.users.toast_title'), message })
        },
        save() {
            this.saving = true
            this.errors = {}
            axios.post('/set_user/update_user_info/' + this.user.id, { ...this.form, birthday: this.form.birthday || null })
                .then(response => {
                    this.toast('success', this.$t('admin.users.profile_saved_message'))
                    this.$emit('saved', response.data.user)
                    this.$nextTick(() => this.reset_form())
                })
                .catch(error => {
                    if (error.response?.status === 422) {
                        this.errors = error.response.data.errors || {}
                    } else {
                        this.toast('danger', this.$t('admin.users.profile_save_error_message'))
                    }
                })
                .finally(() => this.saving = false)
        },
        save_team() {
            this.saving_team = true
            axios.post('/set_user/team/edit_member_status/' + this.user.id, this.team)
                .then(() => {
                    this.toast('success', this.$t('admin.users.team_saved_message'))
                    this.$emit('saved', { ...this.team })
                })
                .catch(() => this.toast('danger', this.$t('admin.users.role_saved_error_message')))
                .finally(() => this.saving_team = false)
        },
        verify() {
            if (!confirm(this.$t('admin.users.verify_confirm'))) return
            this.verifying = true
            axios.post('/set_user/verify_user/' + this.user.id)
                .then(response => {
                    this.toast('success', this.$t('admin.users.verified_message'))
                    this.$emit('saved', { email_verified_at: response.data.email_verified_at })
                })
                .catch(() => this.toast('danger', this.$t('admin.users.verify_error')))
                .finally(() => this.verifying = false)
        },
        resend_verification() {
            this.resending = true
            axios.post('/set_user/resend_verification/' + this.user.id)
                .then(response => {
                    if (response.data.email_verified_at) {
                        this.$emit('saved', { email_verified_at: response.data.email_verified_at })
                        return
                    }
                    this.toast('success', this.$t('admin.users.resend_verification_sent'))
                })
                .catch(() => {})   // 500 / 429 toast globally
                .finally(() => this.resending = false)
        },
    },
}
</script>
