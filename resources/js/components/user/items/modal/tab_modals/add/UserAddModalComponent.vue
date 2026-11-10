<template>
    <!-- Users list → "Add user": creates an account (set_user/create_user, user › add).
         docs/USER_PAGE.md#users -->
    <stack-modal
        :show="is_open"
        :title="$t('admin.users.create_new_user_title')"
        size="lg"
        @close="close"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
    >
        <form class="ua" novalidate @submit.prevent="save">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label" for="ua_name">{{ $t('common.name') }} <span class="text-danger">*</span></label>
                    <input id="ua_name" v-model.trim="form.name" type="text" maxlength="25" class="form-control" :class="{ 'is-invalid': errors.name }" required>
                    <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_surname">{{ $t('admin.users.surname') }} <span class="text-danger">*</span></label>
                    <input id="ua_surname" v-model.trim="form.surname" type="text" maxlength="55" class="form-control" :class="{ 'is-invalid': errors.surname }" required>
                    <div v-if="errors.surname" class="invalid-feedback">{{ errors.surname }}</div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_email">{{ $t('common.email') }} <span class="text-danger">*</span></label>
                    <input id="ua_email" v-model.trim="form.email" type="email" autocomplete="off" class="form-control" :class="{ 'is-invalid': errors.email }" required>
                    <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_password">{{ $t('admin.users.password') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input id="ua_password" v-model="form.password" :type="show_password ? 'text' : 'password'" autocomplete="new-password" minlength="8" class="form-control" :class="{ 'is-invalid': errors.password }" required>
                        <button type="button" class="btn btn-outline-secondary" :title="$t('admin.users.generate_password')" @click="generate"><i class="fa fa-random" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-outline-secondary" @click="show_password = !show_password"><i class="fa" :class="show_password ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i></button>
                    </div>
                    <div v-if="errors.password" class="invalid-feedback d-block">{{ errors.password }}</div>
                    <div v-else class="form-text">{{ $t('admin.users.password_hint') }}</div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_birthday">{{ $t('admin.users.birthday') }}</label>
                    <input id="ua_birthday" v-model="form.birthday" type="date" :max="today" min="1900-01-02" class="form-control" :class="{ 'is-invalid': errors.birthday }">
                    <div v-if="errors.birthday" class="invalid-feedback">{{ errors.birthday }}</div>
                    <div v-else-if="age !== null" class="form-text">{{ $t('admin.users.age_n', { n: age }) }}</div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_phone">{{ $t('admin.users.phone') }}</label>
                    <input id="ua_phone" v-model.trim="form.phone_number" type="tel" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_country">{{ $t('common.country') }}</label>
                    <input id="ua_country" v-model.trim="form.country" type="text" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_city">{{ $t('admin.users.city') }}</label>
                    <input id="ua_city" v-model.trim="form.city" type="text" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ua_role">{{ $t('admin.users.col_role') }}</label>
                    <select id="ua_role" v-model="form.role_id" class="form-select">
                        <option value="">{{ $t('admin.users.default_role') }}</option>
                        <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                    </select>
                </div>
                <div class="col-sm-6 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input id="ua_verified" v-model="form.verified" class="form-check-input" type="checkbox">
                        <label for="ua_verified" class="form-check-label">{{ $t('admin.users.mark_verified') }}</label>
                    </div>
                </div>
            </div>

            <div v-if="server_error" class="alert alert-danger mt-3 mb-0">{{ server_error }}</div>

            <div class="ua__actions">
                <button type="button" class="btn btn-outline-secondary" @click="close">{{ $t('common.close') }}</button>
                <button type="submit" class="btn btn-primary" :disabled="saving">
                    <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    <i v-else class="fa fa-user-plus me-1" aria-hidden="true"></i> {{ $t('admin.users.create_btn') }}
                </button>
            </div>
        </form>
    </stack-modal>
</template>

<script>
    const EMPTY = () => ({ name: '', surname: '', email: '', password: '', birthday: '', phone_number: '', country: '', city: '', role_id: '', verified: true });

    export default {
        name: 'UserAddModalComponent',
        props: { roles: { type: Array, default: () => [] } },
        emits: ['created'],
        data() {
            return { is_open: false, form: EMPTY(), errors: {}, server_error: '', saving: false, show_password: false };
        },
        computed: {
            today() { return new Date().toISOString().slice(0, 10); },
            // full years from the birthday (same rule as the users table / Carbon::age)
            age() {
                const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(this.form.birthday || '');
                if (!m) return null;
                const b = new Date(+m[1], +m[2] - 1, +m[3]), t = new Date();
                let a = t.getFullYear() - b.getFullYear();
                if (t.getMonth() < b.getMonth() || (t.getMonth() === b.getMonth() && t.getDate() < b.getDate())) a--;
                return a >= 0 ? a : null;
            },
        },
        methods: {
            show_modal() {
                this.form = EMPTY();
                this.errors = {};
                this.server_error = '';
                this.show_password = false;
                this.is_open = true;
            },
            close() { this.is_open = false; },
            generate() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
                this.form.password = Array.from({ length: 12 }, () => chars[Math.floor(Math.random() * chars.length)]).join('');
                this.show_password = true;
            },
            validate() {
                const f = this.form, e = {}, t = k => this.$t('admin.users.err_' + k);
                if (!f.name) e.name = t('name');
                if (!f.surname) e.surname = t('surname');
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email)) e.email = t('email');
                if ((f.password || '').length < 8) e.password = t('password');
                if (f.birthday && (f.birthday >= this.today || f.birthday < '1900-01-02')) e.birthday = t('birthday');
                this.errors = e;
                return !Object.keys(e).length;
            },
            save() {
                if (this.saving || !this.validate()) return;
                this.saving = true;
                this.server_error = '';
                axios.post('/set_user/create_user', { data: { ...this.form, role_id: this.form.role_id || null } })
                    .then(r => {
                        this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.users.created', { name: this.form.name }) });
                        this.$emit('created', r.data && r.data.user);
                        this.close();
                    })
                    .catch(err => {
                        const d = (err.response && err.response.data) || {};
                        if (d.errors) {
                            const e = {};
                            Object.keys(d.errors).forEach(k => { e[k] = d.errors[k][0]; });
                            this.errors = e;
                        }
                        this.server_error = d.message || this.$t('admin.tour_guides.failed');
                    })
                    .finally(() => { this.saving = false; });
            },
        },
    };
</script>

<style scoped>
.ua__actions { display: flex; justify-content: flex-end; gap: .5rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--cg-line); }
</style>
