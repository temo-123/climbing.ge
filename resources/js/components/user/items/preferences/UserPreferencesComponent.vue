<template>
    <!-- Options page → Preferences: the account defaults applied on every
         device after login (services/preferences.js). -->
    <div class="col-md-12">
        <div class="cg-prefs-card">
            <h3 class="mb-1">{{ $t('user.preferences.title') }}</h3>
            <p class="text-muted mb-3">{{ $t('user.preferences.lead') }}</p>

            <div v-if="loading" class="py-4 text-center"><div class="spinner-border text-primary" role="status"></div></div>
            <template v-else>
                <preferenceFields v-model="prefs" />
                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-primary" :disabled="saving" @click="save">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>{{ $t('common.save') }}
                    </button>
                    <span v-if="saved" class="text-success fw-bold"><i class="fa fa-check" aria-hidden="true"></i> {{ $t('user.preferences.saved') }}</span>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import preferenceFields from './PreferenceFieldsComponent.vue'
    import { apply_user_preferences } from '../../../../services/preferences.js'
    import { motion_enabled } from '../../../../services/motion.js'
    import { useAuthStore } from '../../../../store/auth.js'

    export default {
        components: { preferenceFields },
        data() {
            return { prefs: { lang: 'us', grade: 'yds', mount_grade: 'russian', animations: true }, loading: true, saving: false, saved: false };
        },
        mounted() {
            axios.get('/get_options/get_preferences')
                .then(r => {
                    const d = r.data || {};
                    // fall back to what this device uses now
                    this.prefs = {
                        lang: d.lang || (this.$i18n.locale === 'ka' ? 'ka' : 'us'),
                        grade: d.grade || ((localStorage.getItem('grade') || 'yds').toLowerCase() === 'uiaa' ? 'uiaa' : 'yds'),
                        mount_grade: d.mount_grade || localStorage.getItem('mount_grade') || 'russian',
                        animations: typeof d.animations === 'boolean' ? d.animations : motion_enabled(),
                    };
                })
                .catch(() => {})
                .finally(() => { this.loading = false; });
        },
        methods: {
            save() {
                this.saving = true;
                this.saved = false;
                axios.post('/get_options/update_preferences', this.prefs)
                    .then(r => {
                        this.saved = true;
                        const store = useAuthStore();
                        const user = Object.assign({}, store.user || {}, r.data);
                        if (store.user) Object.assign(store.user, r.data);
                        apply_user_preferences(user, { force: true });
                    })
                    .catch(() => this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.preferences.error') }))
                    .finally(() => { this.saving = false; });
            },
        },
    };
</script>

<style scoped>
    .cg-prefs-card { margin-top: 1rem; padding: 1.25rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
</style>
