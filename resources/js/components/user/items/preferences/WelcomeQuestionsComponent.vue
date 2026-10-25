<template>
    <!-- Welcome questions, shown once in the user panel to climbers who
         haven't answered or skipped them yet (users.onboarded_at is null):
         1) profile photo, 2) what do you climb (turns on matching "new
         content" notifications), 3) language, grade systems and animations.
         Public sites mount it with `public_site`: there it only opens right
         after the quick sign-up in the login modal (sessionStorage flag).
         Saved with get_options/update_preferences. -->
    <teleport to="body">
        <div v-if="open" class="cg-welcome" role="dialog" aria-modal="true" :aria-label="$t('user.welcome.title')" @keydown.esc="skip">
            <div class="cg-welcome__card">
                <div class="cg-welcome__band">
                    <cg-scene name="summit" wide />
                    <div>
                        <h2>{{ $t('user.welcome.title', { name: user.name || '' }) }}</h2>
                        <p>{{ $t('user.welcome.lead') }}</p>
                    </div>
                </div>

                <div class="cg-welcome__steps" aria-hidden="true">
                    <span v-for="n in 3" :key="n" :class="{ 'is-on': step >= n }"></span>
                </div>

                <div class="cg-welcome__body">
                    <template v-if="step === 1">
                        <h3>{{ $t('user.welcome.q_photo') }}</h3>
                        <p class="text-muted small">{{ $t('user.welcome.q_photo_hint') }}</p>
                        <div class="cg-welcome__photo">
                            <span class="cg-welcome__avatar" :style="photo_preview ? { backgroundImage: 'url(' + photo_preview + ')' } : null">
                                <i v-if="!photo_preview" class="fa fa-user" aria-hidden="true"></i>
                            </span>
                            <div>
                                <label class="btn btn-outline-primary mb-1">
                                    <i class="fa fa-camera" aria-hidden="true"></i> {{ photo_preview ? $t('user.welcome.photo_change') : $t('user.welcome.photo_pick') }}
                                    <input type="file" accept="image/*" class="d-none" @change="pick_photo" />
                                </label>
                                <small class="d-block text-muted">{{ $t('user.welcome.photo_hint_small') }}</small>
                                <small v-if="photo_error" class="d-block text-danger fw-bold">{{ photo_error }}</small>
                            </div>
                        </div>
                    </template>
                    <template v-else-if="step === 2">
                        <h3>{{ $t('user.welcome.q_interests') }}</h3>
                        <p class="text-muted small">{{ $t('user.welcome.q_interests_hint') }}</p>
                        <div class="cg-welcome__interests">
                            <label v-for="i in interests" :key="i.key" class="cg-welcome__interest" :class="{ 'is-active': picked.includes(i.key) }">
                                <input type="checkbox" :value="i.key" v-model="picked" />
                                <i class="fa" :class="i.icon" aria-hidden="true"></i>
                                <span>{{ $t('user.welcome.interests.' + i.key) }}</span>
                            </label>
                        </div>
                    </template>
                    <template v-else>
                        <h3>{{ $t('user.welcome.q_defaults') }}</h3>
                        <preferenceFields v-model="prefs" />
                    </template>
                </div>

                <div class="cg-welcome__foot">
                    <button type="button" class="btn btn-link" :disabled="saving" @click="skip">{{ $t('user.welcome.skip') }}</button>
                    <div class="d-flex gap-2">
                        <button v-if="step > 1" type="button" class="btn btn-outline-secondary" @click="step--">{{ $t('user.welcome.back') }}</button>
                        <button v-if="step < 3" type="button" class="btn btn-primary" :disabled="saving" @click="next">{{ step === 1 && !photo_blob ? $t('user.welcome.later') : $t('user.welcome.next') }}</button>
                        <button v-else type="button" class="btn btn-hold" :disabled="saving" @click="finish">
                            <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>{{ $t('user.welcome.finish') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>

<script>
    import preferenceFields from './PreferenceFieldsComponent.vue'
    import { apply_user_preferences } from '../../../../services/preferences.js'
    import { motion_enabled } from '../../../../services/motion.js'
    import { useAuthStore } from '../../../../store/auth.js'

    // square-crop and shrink the picked photo to 600px JPEG before upload
    function square_jpeg(file, size = 600) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                const side = Math.min(img.naturalWidth, img.naturalHeight);
                const c = document.createElement('canvas');
                c.width = c.height = Math.min(size, side);
                c.getContext('2d').drawImage(img, (img.naturalWidth - side) / 2, (img.naturalHeight - side) / 2, side, side, 0, 0, c.width, c.height);
                URL.revokeObjectURL(img.src);
                c.toBlob(b => (b ? resolve(b) : reject()), 'image/jpeg', 0.88);
            };
            img.onerror = reject;
            img.src = URL.createObjectURL(file);
        });
    }

    export default {
        components: { preferenceFields },
        props: {
            // public sites (guide, shop, …): only right after the quick sign-up
            public_site: { type: Boolean, default: false },
        },
        data() {
            return {
                step: 1,
                saving: false,
                dismissed: false,
                just_registered: false,
                photo_blob: null,
                photo_preview: '',
                photo_error: '',
                picked: [],
                prefs: {
                    lang: this.$i18n.locale === 'ka' ? 'ka' : 'us',
                    grade: (localStorage.getItem('grade') || 'yds').toLowerCase() === 'uiaa' ? 'uiaa' : 'yds',
                    mount_grade: localStorage.getItem('mount_grade') || 'russian',
                    animations: motion_enabled(),
                },
                interests: [
                    { key: 'outdoor', icon: 'fa-sun-o' },
                    { key: 'indoor', icon: 'fa-building-o' },
                    { key: 'ice', icon: 'fa-snowflake-o' },
                    { key: 'mountaineering', icon: 'fa-flag' },
                    { key: 'shop', icon: 'fa-shopping-bag' },
                    { key: 'tours', icon: 'fa-compass' },
                    { key: 'blog', icon: 'fa-newspaper-o' },
                    { key: 'films', icon: 'fa-film' },
                ],
            };
        },
        computed: {
            user() { return useAuthStore().user || {}; },
            open() {
                if (this.public_site && !this.just_registered) return false;
                return !this.dismissed && !!this.user.id && !this.user.onboarded_at;
            },
        },
        watch: {
            open(v) { document.body.classList.toggle('cg-sheet-open', v); },
        },
        mounted() {
            this.check_flag();
            // the login modal's quick sign-up: load the new account, then open
            this.onRegistered = () => { this.check_flag(); useAuthStore().fetchUser(); };
            this.$bus.$on('registered', this.onRegistered);
        },
        beforeUnmount() {
            this.$bus.$off('registered', this.onRegistered);
            document.body.classList.remove('cg-sheet-open');
        },
        methods: {
            check_flag() {
                try { this.just_registered = sessionStorage.getItem('cg_just_registered') === '1'; } catch (e) {}
            },
            pick_photo(e) {
                const file = e.target.files && e.target.files[0];
                this.photo_error = '';
                if (!file) return;
                if (!/^image\//.test(file.type)) { this.photo_error = this.$t('user.welcome.photo_bad'); return; }
                square_jpeg(file)
                    .then(blob => {
                        this.photo_blob = blob;
                        if (this.photo_preview) URL.revokeObjectURL(this.photo_preview);
                        this.photo_preview = URL.createObjectURL(blob);
                    })
                    .catch(() => { this.photo_error = this.$t('user.welcome.photo_bad'); });
            },
            // step 1 → upload the photo (if any) before moving on
            next() {
                if (this.step !== 1 || !this.photo_blob) { this.step++; return; }
                this.saving = true;
                const form = new FormData();
                form.append('image', this.photo_blob, 'profile.jpg');
                axios.post('user/user_image_update/' + this.user.id, form, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then(() => { this.photo_blob = null; this.step++; useAuthStore().fetchUser(); })
                    .catch(() => { this.photo_error = this.$t('user.welcome.photo_failed'); })
                    .finally(() => { this.saving = false; });
            },
            send(payload) {
                this.saving = true;
                return axios.post('/get_options/update_preferences', Object.assign({ onboarded: true }, payload))
                    .then(r => {
                        const store = useAuthStore();
                        if (store.user) Object.assign(store.user, r.data);
                        this.dismissed = true;
                        try { sessionStorage.removeItem('cg_just_registered'); } catch (e) {}
                        return r.data;
                    })
                    .catch(() => { this.dismissed = true; })
                    .finally(() => { this.saving = false; });
            },
            skip() { this.send({}); },
            finish() {
                this.send(Object.assign({ interests: this.picked }, this.prefs)).then(data => {
                    if (!data) return;
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('user.welcome.done') });
                    apply_user_preferences(Object.assign({}, this.user, data), { force: true });
                });
            },
        },
    };
</script>

<style scoped>
    .cg-welcome { position: fixed; inset: 0; z-index: 1060; display: flex; align-items: center; justify-content: center; padding: 16px; background: rgba(15, 20, 25, .55); overflow-y: auto; }
    .cg-welcome__card { width: 100%; max-width: 640px; max-height: calc(100vh - 32px); display: flex; flex-direction: column; background: #fff; border-radius: var(--cg-r-lg); box-shadow: 0 20px 60px rgba(0, 0, 0, .3); overflow: hidden; }
    .cg-welcome__band { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: var(--cg-accent); color: #fff; }
    .cg-welcome__band :deep(img) { flex: 0 0 140px; width: 140px; height: auto; border-radius: var(--cg-r); background: rgba(255,255,255,.9); }
    .cg-welcome__band h2 { margin: 0 0 .25rem; color: #fff; font-size: 1.6rem; }
    .cg-welcome__band p { margin: 0; opacity: .9; }
    .cg-welcome__steps { display: flex; gap: .4rem; padding: .75rem 1.25rem 0; }
    .cg-welcome__steps span { flex: 1; height: .3rem; border-radius: 1rem; background: var(--cg-chalk-2); }
    .cg-welcome__steps span.is-on { background: var(--cg-accent); }
    .cg-welcome__body { padding: 1rem 1.25rem; overflow-y: auto; }
    .cg-welcome__body h3 { font-size: 1.2rem; margin-bottom: .35rem; }
    .cg-welcome__photo { display: flex; align-items: center; gap: 1rem; }
    .cg-welcome__avatar { flex: 0 0 6.5rem; height: 6.5rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--cg-chalk-2) center / cover no-repeat; color: var(--cg-muted); font-size: 2.6rem; box-shadow: inset 0 0 0 3px var(--cg-accent-soft); }
    .cg-welcome__photo label.btn { cursor: pointer; }
    .cg-welcome__interests { display: grid; grid-template-columns: repeat(auto-fill, minmax(10.5rem, 1fr)); gap: .5rem; }
    .cg-welcome__interest span { min-width: 0; overflow-wrap: anywhere; }
    .cg-welcome__interest { position: relative; display: flex; align-items: center; gap: .55rem; padding: .7rem .8rem; border: 2px solid var(--cg-line); border-radius: var(--cg-r); cursor: pointer; font-weight: 700; }
    .cg-welcome__interest input { position: absolute; opacity: 0; }
    .cg-welcome__interest .fa { color: var(--cg-accent); width: 1.2rem; text-align: center; }
    .cg-welcome__interest.is-active { border-color: var(--cg-accent); background: var(--cg-accent-soft); }
    .cg-welcome__interest:focus-within { outline: 3px solid rgba(var(--cg-accent-rgb), .35); outline-offset: 2px; }
    .cg-welcome__foot { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: .85rem 1.25rem; border-top: 1px solid var(--cg-line); }
    @media (max-width: 575.98px) { .cg-welcome__band :deep(img) { display: none; } }
</style>
