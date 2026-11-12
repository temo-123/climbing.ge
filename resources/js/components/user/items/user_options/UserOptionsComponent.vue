<template>
    <!-- Profile tab of /options: who I am (avatar + personal data), password, my sites.
         Missing data (birthday first) is called out at the top with a button that opens the edit form. -->
    <div class="uo">
        <div v-if="!loaded" class="text-center py-5">
            <span class="spinner-border text-primary" role="status"></span>
        </div>

        <template v-else>
            <!-- e-mail not verified (e.g. just changed in "Edit my data"): resend the link from here -->
            <div v-if="!user.email_verified_at" class="alert alert-danger uo-alert" role="alert">
                <i class="fa fa-envelope-o uo-alert__icon" aria-hidden="true"></i>
                <div class="uo-alert__text">
                    <strong>{{ $t('user.profile.verify_title') }}</strong>
                    <span>{{ $t('user.profile.verify_text', { email: user.email }) }}</span>
                </div>
                <button type="button" class="btn btn-sm btn-danger" :disabled="resending || resend_cooldown > 0" @click="resend_verification()">
                    <span v-if="resending" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <i v-else class="fa fa-paper-plane" aria-hidden="true"></i>
                    {{ resend_cooldown > 0 ? $t('global.email_verify.sent_wait', { s: resend_cooldown }) : $t('global.email_verify.resend_btn') }}
                </button>
            </div>

            <!-- missing date of birth: its own call-out (required since Nov 2026) -->
            <div v-if="!has_birthday" class="alert alert-warning uo-alert" role="alert">
                <i class="fa fa-birthday-cake uo-alert__icon" aria-hidden="true"></i>
                <div class="uo-alert__text">
                    <strong>{{ $t('user.profile.birthday_missing_title') }}</strong>
                    <span>{{ $t('user.profile.birthday_missing_text') }}</span>
                </div>
                <button type="button" class="btn btn-sm btn-warning" @click="open_edit_modal('birthday')">
                    <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('user.profile.birthday_add_btn') }}
                </button>
            </div>

            <!-- other missing fields -->
            <div v-if="missing_other.length" class="alert alert-info uo-alert" role="alert">
                <i class="fa fa-info-circle uo-alert__icon" aria-hidden="true"></i>
                <div class="uo-alert__text">
                    <strong>{{ $t('user.profile.incomplete_info') }}</strong>
                    <span>{{ $t('user.profile.missing_fields', { fields: missing_other.map(f => f.label).join(', ') }) }}</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" @click="open_edit_modal(missing_other[0].key)">
                    {{ $t('user.profile.complete_btn') }}
                </button>
            </div>

            <div class="row g-4">
                <!-- identity card -->
                <div class="col-lg-4 col-md-5 col-sm-12">
                    <section class="cg-panel uo-id">
                        <button type="button" class="uo-avatar" @click="open_edit_image_modal()" :title="$t('user.profile.edit_image')">
                            <img v-if="user.image" loading="lazy" decoding="async" :src="'/public/images/user_profil_img/' + user.image" :alt="full_name">
                            <span v-else class="uo-avatar__initials">{{ initials }}</span>
                            <span class="uo-avatar__edit"><i class="fa fa-camera" aria-hidden="true"></i></span>
                        </button>
                        <h2 class="uo-id__name">{{ full_name || '—' }}</h2>
                        <p class="uo-id__email text-break">{{ user.email }}</p>
                        <div class="uo-id__badges">
                            <span v-if="user.email_verified_at" class="badge text-bg-success"><i class="fa fa-check" aria-hidden="true"></i> {{ $t('user.profile.email_verified') }}</span>
                            <span v-else class="badge text-bg-warning"><i class="fa fa-exclamation" aria-hidden="true"></i> {{ $t('user.profile.email_not_verified') }}</span>
                            <span v-if="age !== null" class="badge text-bg-light">{{ $t('admin.users.age_n', { n: age }) }}</span>
                        </div>

                        <div class="uo-progress" :title="$t('user.profile.completeness', { n: completeness })">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">{{ $t('user.profile.completeness_label') }}</span>
                                <strong>{{ completeness }}%</strong>
                            </div>
                            <div class="progress" role="progressbar" :aria-valuenow="completeness" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" :style="{ width: completeness + '%' }"></div>
                            </div>
                        </div>

                        <div class="uo-id__actions">
                            <button type="button" class="btn btn-outline-secondary btn-sm" @click="open_edit_image_modal()">
                                <i class="fa fa-camera" aria-hidden="true"></i> {{ $t('user.profile.edit_image') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" @click="edit_password_model()">
                                <i class="fa fa-lock" aria-hidden="true"></i> {{ $t('user.profile.edit_password_btn') }}
                            </button>
                        </div>
                    </section>
                </div>

                <!-- personal data -->
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <section class="cg-panel">
                        <div class="uo-head">
                            <h2 class="uo-head__title"><i class="fa fa-id-card-o" aria-hidden="true"></i> {{ $t('user.profile.personal_title') }}</h2>
                            <button type="button" class="btn btn-primary btn-sm" @click="open_edit_modal()">
                                <i class="fa fa-pencil" aria-hidden="true"></i> {{ $t('user.profile.edit_my_data') }}
                            </button>
                        </div>

                        <dl class="uo-fields">
                            <div v-for="f in fields" :key="f.key" class="uo-field" :class="{ 'uo-field--wide': f.wide, 'uo-field--empty': !f.value }">
                                <dt><i :class="'fa ' + f.icon" aria-hidden="true"></i> {{ f.label }}</dt>
                                <dd>
                                    <template v-if="f.value">{{ f.value }}</template>
                                    <button v-else type="button" class="uo-field__add" @click="open_edit_modal(f.key)">
                                        {{ f.empty }} <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </button>
                                </dd>
                            </div>
                        </dl>
                    </section>
                </div>

                <!-- my sites -->
                <div class="col-12">
                    <section class="cg-panel">
                        <div class="uo-head">
                            <div>
                                <h2 class="uo-head__title"><i class="fa fa-link" aria-hidden="true"></i> {{ $t('user.profile.sites_title') }}</h2>
                                <p class="text-muted small mb-0">{{ $t('user.profile.sites_lead') }}</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" @click="new_user_site_add_model()">
                                <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('user.profile.add_new_site') }}
                            </button>
                        </div>

                        <div v-if="!user_sites.length" class="cg-empty uo-sites-empty">
                            <i class="fa fa-globe" aria-hidden="true"></i>
                            <p class="mb-0">{{ $t('user.profile.sites_empty') }}</p>
                        </div>
                        <ul v-else class="uo-sites">
                            <li v-for="site in user_sites" :key="site.id">
                                <a :href="site.url" target="_blank" rel="noopener nofollow" class="uo-sites__link">
                                    <i :class="'fa ' + site_icon(site.url)" aria-hidden="true"></i>
                                    <span class="text-truncate">{{ from_user_site_url_get_domen(site.url) }}</span>
                                    <i class="fa fa-external-link uo-sites__ext" aria-hidden="true"></i>
                                </a>
                                <div class="uo-sites__actions">
                                    <button type="button" class="btn btn-sm btn-light" @click="get_editing_user_site(site.id)" :title="$t('common.edit')" :aria-label="$t('common.edit')">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light text-danger" @click="del_user_site(site.id)" :title="$t('common.delete')" :aria-label="$t('common.delete')">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </template>

        <StackModal
                v-model="is_add_user_site"
                :title="$t('user.profile.add_site_title')"
                @close="is_add_user_site=false"
                @save="$refs.add_user_site_form.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-light': true } }"
            >
            <form ref="add_user_site_form" class="form" method="POST" v-on:submit.prevent="add_user_site">
                <label for="add_site_url" class="form-label">{{ $t('user.profile.enter_url_label') }}</label>
                <input type="url" class="form-control" name="url" v-model="user_site_form_data.url" id="add_site_url" pattern="https://.*|http://.*" placeholder="https://instagram.com/…" required>
            </form>
        </StackModal>

        <StackModal
                v-model="is_edit_user_site"
                :title="$t('user.profile.edit_site_title')"
                @close="close_edit_modal()"
                @save="$refs.edit_site_form.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-light': true } }"
            >
            <form ref="edit_site_form" class="form" method="POST" v-on:submit.prevent="edit_user_site">
                <label for="edit_site_url" class="form-label">{{ $t('user.profile.enter_url_label') }}</label>
                <input type="url" class="form-control" name="url" v-model="user_site_form_data.url" id="edit_site_url" pattern="https://.*|http://.*" :placeholder="$t('user.profile.enter_url_placeholder')" required>
            </form>
        </StackModal>

        <StackModal
                v-model="is_edit_data"
                :title="$t('user.profile.edit_data_title')"
                size="lg"
                @close="is_edit_data=false"
                @save="$refs.registrationForm.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-light': true } }"
            >
            <form ref="registrationForm" class="form" method="POST" id="registrationForm" v-on:submit.prevent="user_data_update">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="uo_name" class="form-label">{{ $t('common.name') }} *</label>
                        <input type="text" class="form-control" v-model.trim="edit_data.name" id="uo_name" autocomplete="given-name" :placeholder="$t('user.profile.name_placeholder')" required>
                    </div>
                    <div class="col-md-6">
                        <label for="uo_surname" class="form-label">{{ $t('common.surname') }} *</label>
                        <input type="text" class="form-control" v-model.trim="edit_data.surname" id="uo_surname" autocomplete="family-name" :placeholder="$t('user.profile.surname_placeholder')" required>
                    </div>
                    <div class="col-md-6">
                        <label for="uo_email" class="form-label">{{ $t('common.email') }} *</label>
                        <input type="email" class="form-control" :class="{ 'is-invalid': edit_errors.email }" v-model.trim="edit_data.email" id="uo_email" autocomplete="email" :placeholder="$t('user.profile.email_placeholder')" required>
                        <div class="invalid-feedback">{{ edit_errors.email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="uo_phone" class="form-label">{{ $t('common.phone_number') }}</label>
                        <input type="tel" class="form-control" v-model.trim="edit_data.phone_number" id="uo_phone" autocomplete="tel" :placeholder="$t('user.profile.phone_placeholder')">
                    </div>
                    <div class="col-md-6">
                        <label for="uo_birthday" class="form-label">{{ $t('user.profile.birthday_label') }} *</label>
                        <input ref="field_birthday" type="date" class="form-control" :class="{ 'is-invalid': edit_errors.birthday }" v-model="edit_data.birthday" id="uo_birthday" :max="today" min="1900-01-02" autocomplete="bday" required>
                        <div class="invalid-feedback">{{ edit_errors.birthday }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="uo_lang" class="form-label">{{ $t('user.profile.select_language_title') }}</label>
                        <select class="form-select" v-model="edit_data.lang" id="uo_lang">
                            <option value="us">{{ $t('user.profile.lang_english') }}</option>
                            <option value="ka">{{ $t('user.profile.lang_georgian') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="uo_country" class="form-label">{{ $t('user.profile.country_label') }}</label>
                        <input ref="field_country" type="text" class="form-control" v-model.trim="edit_data.country" id="uo_country" autocomplete="country-name" :placeholder="$t('user.profile.country_placeholder')">
                    </div>
                    <div class="col-md-6">
                        <label for="uo_city" class="form-label">{{ $t('user.profile.city_label') }}</label>
                        <input ref="field_city" type="text" class="form-control" v-model.trim="edit_data.city" id="uo_city" autocomplete="address-level2" :placeholder="$t('user.profile.city_placeholder')">
                    </div>
                    <div class="col-12">
                        <label for="uo_bio" class="form-label">{{ $t('user.profile.bio_label') }}</label>
                        <textarea class="form-control" v-model="edit_data.my_bio" id="uo_bio" rows="4" :placeholder="$t('user.profile.bio_placeholder')"></textarea>
                        <p class="form-text mb-0"><i class="fa fa-link" aria-hidden="true"></i> {{ $t('user.profile.links_hint') }}</p>
                    </div>
                </div>
            </form>
        </StackModal>

        <StackModal
                :show="is_change_image"
                :title="$t('user.profile.edit_image_title')"
                @close="is_change_image=false"
                @save="$refs.myForm.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-light': true } }"
            >
            <div>
                <form ref="myForm" v-on:submit.prevent="edit_image" id="profil_image_form">
                    <div class="container">
                        <div class="form-group clearfix row">
                            <single_image_edit
                                :key="(user.id || 0) + '-' + image_reset_id"
                                :title_prop="''"
                                :crop_ratio_prop="{ width: 1, height: 1 }"
                                :existing_image_url_prop="user.image ? ('/public/images/user_profil_img/' + user.image) : ''"
                                @update_single_image="profile_image_file = $event"
                                @processing="profile_image_processing = $event"
                            />
                        </div>
                    </div>
                </form>
            </div>
        </StackModal>

        <StackModal
                :show="is_change_password"
                :title="$t('user.profile.edit_password_title')"
                @close="close_edit_password_model()"
                @save="$refs.edit_password_form.requestSubmit()"
                :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-light': true } }"
            >
            <form ref="edit_password_form" class="form" method="POST" id="edit_password" v-on:submit.prevent="edit_password">
                <div class="alert alert-danger" role="alert" v-if="is_old_pass_error">
                    {{ $t('user.profile.old_password_error') }}
                </div>
                <label for="uo_old_pass" class="form-label">{{ $t('user.profile.old_password_placeholder') }}</label>
                <input type="password" class="form-control mb-3" id="uo_old_pass" autocomplete="current-password" v-model="password_edit_data.old_pass" required>

                <label for="uo_new_pass" class="form-label">{{ $t('user.profile.new_password_placeholder') }}</label>
                <input type="password" class="form-control mb-3" id="uo_new_pass" autocomplete="new-password" minlength="8" v-model="password_edit_data.new_pass" required>

                <label for="uo_new_pass2" class="form-label">{{ $t('user.profile.repeat_password_placeholder') }}</label>
                <input type="password" class="form-control" :class="{ 'is-invalid': is_password_cofirming_error }" id="uo_new_pass2" autocomplete="new-password" minlength="8" v-model="password_edit_data.confirm_new_pass" required>
                <div class="invalid-feedback">{{ $t('user.profile.password_confirm_error') }}</div>
            </form>
        </StackModal>

    </div>
</template>

<script>

    import single_image_edit from '../single_image/singleImageEditComponent.vue'
    import { forget_auth_user } from '../../../../services/authUser.js'

    export default {
        components: { single_image_edit },
        data(){
            return {
                tab_num: 1,

                user: {},
                loaded: false,
                edit_errors: {},
                resending: false,
                resend_cooldown: 0,
                user_is_refresh: false,
                user_reset_id: 0,

                is_edit_data: false,
                is_change_image: false,
                profile_image_file: null,
                profile_image_processing: false,
                image_reset_id: 0,
                is_change_password: false,

                edit_data: {
                    name: '',
                    surname: '',
                    phone_number: '',
                    email: '',
                    city: '',
                    country: '',
                    birthday: '',
                    lang: 'us',
                    my_bio: '',
                },

                password_edit_data: {
                    old_pass: '',
                    new_pass: '',
                    confirm_new_pass: '',
                },
                is_password_cofirming_error: false,
                is_old_pass_error: false,

                user_site_form_data: {
                    url: ''
                },

                user_sites: [],

                is_user_site_refresh: false,
                user_site_reset_id: 0,

                is_add_user_site: false,
                is_edit_user_site: false,

                editing_site_id: 0
            }
        },
        computed: {
            // local YYYY-MM-DD — the birthday must be before today
            today() {
                const d = new Date()
                return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
            },
            full_name() {
                return [this.user.name, this.user.surname].filter(Boolean).join(' ')
            },
            initials() {
                return ((this.user.name || '').charAt(0) + (this.user.surname || '').charAt(0)).toUpperCase() || '?'
            },
            birthday_parts() {
                return /^(\d{4})-(\d{2})-(\d{2})/.exec(this.user.birthday || '')
            },
            has_birthday() {
                return !!this.birthday_parts
            },
            // full years — same rule as the admin users table (Carbon::age)
            age() {
                const m = this.birthday_parts
                if (!m) return null
                const now = new Date()
                let age = now.getFullYear() - +m[1]
                if (now.getMonth() + 1 < +m[2] || (now.getMonth() + 1 === +m[2] && now.getDate() < +m[3])) age--
                return age
            },
            // "14 May 1990 · 36 years old", or null when there is no birthday
            birthday_text() {
                const m = this.birthday_parts
                if (!m) return null
                const date = new Date(+m[1], +m[2] - 1, +m[3]).toLocaleDateString(this.$i18n?.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'long', year: 'numeric' })
                return `${date} · ${this.$t('admin.users.age_n', { n: this.age })}`
            },
            fields() {
                const u = this.user
                const t = (k) => this.$t('user.profile.' + k)
                const not_set = t('not_set')
                return [
                    { key: 'name',         icon: 'fa-user',          label: this.$t('common.name'),         value: u.name,         empty: not_set },
                    { key: 'surname',      icon: 'fa-user-o',        label: this.$t('common.surname'),      value: u.surname,      empty: not_set },
                    { key: 'birthday',     icon: 'fa-birthday-cake', label: t('birthday_label'),            value: this.birthday_text, empty: t('no_birthday') },
                    { key: 'phone_number', icon: 'fa-phone',         label: this.$t('common.phone_number'), value: u.phone_number, empty: not_set },
                    { key: 'email',        icon: 'fa-envelope-o',    label: this.$t('common.email'),        value: u.email,        empty: not_set },
                    { key: 'lang',         icon: 'fa-language',      label: t('select_language_title'),     value: u.lang === 'ka' ? t('lang_georgian') : t('lang_english'), empty: not_set },
                    { key: 'country',      icon: 'fa-flag-o',        label: t('country_label'),             value: u.country,      empty: not_set },
                    { key: 'city',         icon: 'fa-map-marker',    label: t('city_label'),                value: u.city,         empty: not_set },
                    { key: 'my_bio',       icon: 'fa-quote-left',    label: t('bio_label'),                 value: u.my_bio,       empty: t('bio_empty'), wide: true },
                ]
            },
            // fields the incomplete-info alert asks for (birthday has its own alert)
            missing_other() {
                return this.fields.filter(f => ['name', 'surname', 'phone_number', 'email', 'country', 'city'].includes(f.key) && !f.value)
            },
            completeness() {
                const tracked = this.fields.filter(f => f.key !== 'lang')
                return Math.round(100 * tracked.filter(f => f.value).length / tracked.length)
            },
        },
        beforeUnmount() {
            clearInterval(this._resend_timer)
        },
        mounted() {
            this.get_user_data()
        },

        methods: {
            get_user_data: function(){
                this.user_is_refresh = true
                axios
                .get('auth_user')
                .then((response)=>{
                    this.user = response.data || {}
                    this.loaded = true
                    // the sidebar / top-bar account badges re-check (services/menuBadges.js)
                    forget_auth_user()
                    this.$bus.$emit('profile-updated')

                    this.user_is_refresh = false
                    this.user_reset_id++

                    this.get_user_sites()
                })
                .catch(() => {
                    this.user_is_refresh = false
                    this.loaded = true
                });
            },
            user_data_update() {
                this.edit_errors = {}
                axios
                .post('get_options/user_info_update/' + this.user.id, {
                    data: this.edit_data,
                })
                .then(Response => {
                    // a changed e-mail has to be verified again — a new link was sent to it
                    if (Response.data && Response.data.email_changed) {
                        forget_auth_user()
                        this.$bus.$emit('logged-in')   // EmailVerifyNotice re-checks and shows its bar
                        if (Response.data.verification_sent === false) {
                            // mail server refused it — the alert's Resend button is right there
                            this.$bus.$emit('toast', { type: 'danger', message: this.$t('global.email_verify.send_error'), duration: 8000 })
                        } else {
                            this.$bus.$emit('toast', { type: 'warning', message: this.$t('user.profile.email_changed_verify'), duration: 8000 })
                            this.start_resend_cooldown()
                        }
                    }
                    this.get_user_data()

                    this.edit_data = {
                        name: '',
                        surname: '',
                        phone_number: '',
                        email: '',
                        city: '',
                        country: '',
                        birthday: '',
                        lang: 'us',
                        my_bio: '',
                    }

                    this.is_edit_data = false
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('user.profile.saved') })
                })
                .catch(error => {
                    const errors = error.response && error.response.status === 422 && error.response.data.errors
                    if (errors) {
                        this.edit_errors = Object.fromEntries(Object.entries(errors).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v]))
                    } else {
                        this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.profile.save_error') })
                    }
                    console.log(error)
                })
            },

            edit_password_model(){
                this.is_change_password = true
            },

            close_edit_password_model(){
                this.is_change_password = false
                this.password_edit_data.old_pass = ''
                this.password_edit_data.new_pass = ''
                this.password_edit_data.confirm_new_pass = ''
            },

            edit_password(){
                this.is_password_cofirming_error = false
                this.is_old_pass_error = false

                if(this.password_edit_data.new_pass === this.password_edit_data.confirm_new_pass){
                    if(confirm(this.$t('user.profile.confirm_edit_password'))){
                        axios
                        .post("user/update_password",{
                            data: this.password_edit_data,
                        })
                        .then(response => {
                            if(response.data == "Old Password Doesn't match!"){
                                this.password_edit_data.old_pass = ''
                                this.is_old_pass_error = true
                            }
                            else{
                                this.close_edit_password_model()
                                alert(response.data)
                            }
                        })
                        .catch(
                            error => console.log(error)
                        );
                    }
                }
                else{
                    this.is_password_cofirming_error = true
                }
            },

            edit_image(){
                if (this.profile_image_processing) {
                    return
                }
                if (!this.profile_image_file) {
                    this.is_change_image = false
                    return
                }
                this.is_change_image = false

                var myFormData = new FormData()
                myFormData.append('image', this.profile_image_file)
                axios({
                    method: 'post',
                    url: 'user/user_image_update/'+this.user.id,
                    data: myFormData,
                    config: { 
                        headers: {'Content-Type': 'multipart/form-data' },
                    },
                })
                .then((response)=>  {
                    // this.is_add_image = false
                    this.get_user_data()
                });
            },

            resend_verification() {
                if (this.resending || this.resend_cooldown > 0) return
                this.resending = true
                axios.get('email/resend')
                    .then(response => {
                        if (response.data && response.data.message === 'Email already verified') {
                            forget_auth_user()
                            this.get_user_data()
                            this.$bus.$emit('toast', { type: 'success', message: this.$t('global.email_verify.already') })
                            return
                        }
                        this.$bus.$emit('toast', { type: 'success', message: this.$t('global.email_verify.sent'), duration: 6000 })
                        this.start_resend_cooldown()
                    })
                    .catch(() => {})   // 429 / 500 toast globally (bootstrap.js)
                    .finally(() => { this.resending = false })
            },
            start_resend_cooldown() {
                this.resend_cooldown = 60
                clearInterval(this._resend_timer)
                this._resend_timer = setInterval(() => {
                    if (--this.resend_cooldown <= 0) clearInterval(this._resend_timer)
                }, 1000)
            },
            // focus: the field to jump to (e.g. 'birthday' from the missing-birthday alert)
            open_edit_modal(focus = null) {
                this.edit_errors = {}
                this.edit_data = {
                    name: this.user.name,
                    surname: this.user.surname,
                    phone_number: this.user.phone_number,
                    email: this.user.email,
                    city: this.user.city,
                    country: this.user.country,
                    birthday: this.user.birthday ? String(this.user.birthday).slice(0, 10) : '',
                    lang: this.user.lang || 'us',
                    my_bio: this.user.my_bio || '',
                }

                this.is_edit_data = true
                if (focus) {
                    const ids = { name: 'uo_name', surname: 'uo_surname', email: 'uo_email', phone_number: 'uo_phone', birthday: 'uo_birthday', country: 'uo_country', city: 'uo_city', my_bio: 'uo_bio', lang: 'uo_lang' }
                    // wait for the modal to render
                    setTimeout(() => { const el = document.getElementById(ids[focus]); if (el) el.focus() }, 150)
                }
            },
            site_icon(url) {
                let host = ''
                try { host = new URL(url).hostname.replace(/^www\./, '') } catch (e) { return 'fa-globe' }
                const map = { 'instagram.com': 'fa-instagram', 'facebook.com': 'fa-facebook', 'youtube.com': 'fa-youtube-play', 'youtu.be': 'fa-youtube-play', 'twitter.com': 'fa-twitter', 'x.com': 'fa-twitter', 'linkedin.com': 'fa-linkedin', 'vimeo.com': 'fa-vimeo', 'github.com': 'fa-github', 'strava.com': 'fa-bicycle' }
                return map[host] || 'fa-globe'
            },
            open_edit_image_modal() {
                this.profile_image_file = null
                this.profile_image_processing = false
                this.image_reset_id++
                this.is_change_image = true
            },

            get_user_sites() {
                axios
                .get("/user_site/")
                .then(response => {
                    this.user_sites = response.data

                    this.is_user_site_refresh = false
                    this.user_site_reset_id++
                })
                .catch(
                    error => console.log(error)
                );
            },
            from_user_site_url_get_domen(url){
                try { return new URL(url).hostname.replace(/^www\./, '') } catch (e) { return url }
            },
            new_user_site_add_model(){
                this.is_add_user_site = true
            },

            add_user_site(){
                axios
                .post('user_site/', {
                    data: this.user_site_form_data,
                    _method: 'POST'
                })
                .then(response => {
                    this.is_add_user_site = false
                    this.user_site_form_data.url = ''
                    this.get_user_sites()
                })
                .catch(
                    //
                );
            },
            close_edit_modal(){
                this.is_edit_user_site = false
                this.editing_site_id = 0
            },
            edit_user_site(){
                axios
                .post('user_site/' + this.editing_site_id, {        
                    editing_data: this.user_site_form_data,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.is_edit_user_site = false
                    this.get_user_sites()
                    this.editing_site_id = 0
                })
                .catch(
                    //
                );
            },
            get_editing_user_site(site_id){
                axios
                .get("/user_site/"+site_id)
                .then(response => {
                    this.user_site_form_data.url = response.data.url
                    this.is_edit_user_site = true
                    this.editing_site_id = site_id
                })
                .catch(
                    //
                );
            },
            del_user_site(site_id){
                if(confirm(this.$t('user.profile.confirm_delete_site'))){
                    axios
                    .post('user_site/'+site_id, {
                        _method: 'DELETE'
                    })
                    .then(response => {
                        this.get_user_sites()
                    })
                    .catch(error => {
                        console.log('Error deleting site:', error)
                    })
                }
            },
            go_to_user_site(url){
                window.open(url, '_blank');
            }
        }
}
</script>

<style scoped>
    .uo-alert {
        display: flex;
        align-items: center;
        gap: .9rem;
        flex-wrap: wrap;
    }
    .uo-alert__icon { font-size: 1.4rem; }
    .uo-alert__text {
        flex: 1 1 14rem;
        display: flex;
        flex-direction: column;
        gap: .1rem;
    }

    .uo-id {
        text-align: center;
        height: 100%;
    }
    .uo-avatar {
        position: relative;
        display: inline-block;
        width: 128px;
        height: 128px;
        padding: 0;
        border: 3px solid #fff;
        border-radius: 50%;
        overflow: hidden;
        background: var(--cg-accent-soft);
        box-shadow: var(--cg-shadow-sm);
        cursor: pointer;
    }
    .uo-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .uo-avatar__initials {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-family: var(--cg-font-display);
        font-size: 2.6rem;
        font-weight: 700;
        color: var(--cg-accent-dark);
    }
    .uo-avatar__edit {
        position: absolute;
        inset: auto 0 0 0;
        padding: .3rem 0;
        background: rgba(0, 0, 0, .45);
        color: #fff;
        opacity: 0;
        transition: opacity .2s ease;
    }
    .uo-avatar:hover .uo-avatar__edit,
    .uo-avatar:focus-visible .uo-avatar__edit { opacity: 1; }

    .uo-id__name {
        margin: .9rem 0 .15rem;
        font-family: var(--cg-font-display);
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--cg-ink);
    }
    .uo-id__email { color: var(--cg-stone); margin-bottom: .6rem; }
    .uo-id__badges { display: flex; justify-content: center; flex-wrap: wrap; gap: .35rem; }
    .uo-progress { margin: 1.2rem 0; text-align: left; }
    .uo-progress .progress { height: .5rem; }
    .uo-progress .progress-bar { background: var(--cg-accent); }
    .uo-id__actions { display: flex; flex-direction: column; gap: .5rem; }

    .uo-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
        margin-bottom: 1.1rem;
    }
    .uo-head__title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--cg-ink);
    }
    .uo-head__title .fa { color: var(--cg-accent); margin-right: .3rem; }

    .uo-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .75rem;
        margin: 0;
    }
    .uo-field {
        padding: .7rem .85rem;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r);
        background: var(--cg-chalk);
        min-width: 0;
    }
    .uo-field--wide { grid-column: 1 / -1; }
    .uo-field dt {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: var(--cg-muted);
        margin-bottom: .2rem;
    }
    .uo-field dt .fa { width: 1rem; }
    .uo-field dd {
        margin: 0;
        color: var(--cg-ink);
        font-weight: 600;
        overflow-wrap: anywhere;
        white-space: pre-line;
    }
    .uo-field--empty { border-style: dashed; background: #fff; }
    .uo-field__add {
        padding: 0;
        border: 0;
        background: none;
        color: var(--cg-accent-dark);
        font-weight: 600;
        font-style: italic;
    }
    .uo-field__add:hover { text-decoration: underline; }

    .uo-sites {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(16rem, 1fr));
        gap: .6rem;
    }
    .uo-sites li {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .45rem .5rem .45rem .85rem;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r);
        background: var(--cg-chalk);
    }
    .uo-sites__link {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        gap: .5rem;
        color: var(--cg-ink);
        font-weight: 600;
        text-decoration: none;
    }
    .uo-sites__link > .fa:first-child { color: var(--cg-accent); font-size: 1.15rem; width: 1.2rem; text-align: center; }
    .uo-sites__ext { color: var(--cg-muted); font-size: .75rem; }
    .uo-sites__link:hover span { text-decoration: underline; }
    .uo-sites__actions { display: flex; gap: .25rem; }
    .uo-sites-empty { padding: 1.5rem; text-align: center; color: var(--cg-stone); }
    .uo-sites-empty .fa { font-size: 1.8rem; color: var(--cg-muted); margin-bottom: .4rem; }

    @media (max-width: 575.98px) {
        .uo-fields { grid-template-columns: 1fr; }
    }
</style>
