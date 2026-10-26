<template>
    <!-- Mountaineering route helpers, under the route text: the grade in plain
         words and a full-width emergency card (call 112, what to tell the
         dispatcher + copy my coordinates, share my trip plan). The gear
         checklist lives in "What You Need" (GearChecklistComponent). -->
    <div class="cg-mtn">
        <section v-if="level" class="cg-mtn__card cg-mtn__grade">
            <h3><i class="fa fa-info-circle" aria-hidden="true"></i> {{ $t('guide.mountain.grade_title', { grade: grade_label }) }}</h3>
            <p class="mb-0">{{ $t('guide.mountain.levels.' + level) }}</p>
        </section>

        <section class="cg-mtn__card cg-sos" :aria-label="$t('guide.mountain.emergency_title')">
            <!-- 1. call -->
            <div class="cg-sos__call">
                <h3><i class="fa fa-life-ring" aria-hidden="true"></i> {{ $t('guide.mountain.emergency_title') }}</h3>
                <a class="cg-sos__number" href="tel:112"><i class="fa fa-phone" aria-hidden="true"></i> 112</a>
                <p class="cg-sos__lead">{{ $t('guide.mountain.emergency_lead') }}</p>
                <p class="cg-sos__app"><i class="fa fa-mobile" aria-hidden="true"></i> {{ $t('guide.mountain.emergency_app') }}</p>
            </div>

            <!-- 2. what to say -->
            <div class="cg-sos__say">
                <h4>{{ $t('guide.mountain.say_title') }}</h4>
                <ol>
                    <li><strong>{{ $t('guide.mountain.say_where') }}</strong> {{ $t('guide.mountain.say_where_text', { route: title }) }}</li>
                    <li><strong>{{ $t('guide.mountain.say_what') }}</strong> {{ $t('guide.mountain.say_what_text') }}</li>
                    <li><strong>{{ $t('guide.mountain.say_phone') }}</strong> {{ $t('guide.mountain.say_phone_text') }}</li>
                </ol>
                <button type="button" class="btn btn-sm btn-outline-danger" :disabled="locating" @click="copy_location">
                    <span v-if="locating" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    <i v-else class="fa fa-crosshairs" aria-hidden="true"></i>
                    {{ $t('guide.mountain.my_location') }}
                </button>
                <p v-if="location" class="cg-sos__coords">
                    <code>{{ location }}</code>
                    <small>{{ location_copied ? $t('guide.mountain.plan_copied') : '' }}</small>
                </p>
                <p v-if="location_error" class="cg-sos__err">{{ location_error }}</p>
                <p class="cg-sos__tip"><i class="fa fa-signal" aria-hidden="true"></i> {{ $t('guide.mountain.no_signal') }}</p>
            </div>

            <!-- 3. before you go: the trip plan (logged-in climbers) -->
            <div class="cg-sos__plan">
                <h4>{{ $t('guide.mountain.plan_title') }}</h4>
                <p class="cg-sos__hint">{{ $t('guide.mountain.plan_hint') }}</p>
                <template v-if="logged_in">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold" :for="uid + 'start'">{{ $t('guide.mountain.plan_start') }}</label>
                            <input :id="uid + 'start'" type="date" class="form-control form-control-sm" v-model="plan.start" />
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold" :for="uid + 'back'">{{ $t('guide.mountain.plan_back') }}</label>
                            <input :id="uid + 'back'" type="datetime-local" class="form-control form-control-sm" v-model="plan.back" />
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold" :for="uid + 'team'">{{ $t('guide.mountain.plan_team') }}</label>
                            <input :id="uid + 'team'" type="text" class="form-control form-control-sm" v-model="plan.team" maxlength="200" />
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-2" @click="share_plan">
                        <i class="fa" :class="shared ? 'fa-check' : 'fa-share-alt'" aria-hidden="true"></i>
                        {{ shared ? $t('guide.mountain.plan_copied') : $t('guide.mountain.plan_share') }}
                    </button>
                </template>
                <button v-else type="button" class="btn btn-sm btn-primary" @click="ask_login">
                    <i class="fa fa-sign-in" aria-hidden="true"></i> {{ $t('guide.mountain.plan_login') }}
                </button>
            </div>
        </section>
    </div>
</template>

<script>
    import { formatMountGrade } from '../../../mixins/grade_chart_mixin.js'
    import { mountain_level } from '../../../services/guide/mountainGear.js'

    let uid = 0;

    export default {
        name: 'MountainSafety',
        props: {
            grade: { type: String, default: null },
            article_id: { type: [Number, String], required: true },
            title: { type: String, default: '' },
        },
        data() {
            return {
                plan: { start: '', back: '', team: '' }, shared: false,
                logged_in: !!localStorage.getItem('auth_token'),
                locating: false, location: '', location_copied: false, location_error: '',
                uid: 'cg-sos-' + (++uid) + '-',
            };
        },
        computed: {
            grade_label() { return formatMountGrade(this.grade); },
            level() { return mountain_level(this.grade); },
        },
        methods: {
            // the trip plan is for logged-in climbers: log in / sign up, then the form opens
            ask_login() {
                this.$bus.$emit('open-register-modal', {
                    reason: this.$t('guide.mountain.plan_login_reason'),
                    callback: () => { this.logged_in = !!localStorage.getItem('auth_token'); },
                });
            },
            share_plan() {
                const lines = [
                    this.$t('guide.mountain.plan_message_intro'),
                    '⛰ ' + this.title + (this.grade_label ? ' (' + this.grade_label + ')' : ''),
                    this.plan.start ? this.$t('guide.mountain.plan_start') + ': ' + this.plan.start : null,
                    this.plan.back ? this.$t('guide.mountain.plan_back') + ': ' + this.plan.back.replace('T', ' ') : null,
                    this.plan.team ? this.$t('guide.mountain.plan_team') + ': ' + this.plan.team : null,
                    this.$t('guide.mountain.plan_message_sos'),
                    window.location.href,
                ].filter(Boolean).join('\n');
                const done = () => { this.shared = true; setTimeout(() => { this.shared = false; }, 2000); };
                if (navigator.share) navigator.share({ title: this.title, text: lines }).then(done).catch(() => {});
                else if (navigator.clipboard) navigator.clipboard.writeText(lines).then(done).catch(() => window.prompt('', lines));
                else window.prompt('', lines);
            },
            // GPS coordinates to read out to the dispatcher (and copied)
            copy_location() {
                this.location_error = '';
                if (!navigator.geolocation) { this.location_error = this.$t('guide.mountain.location_error'); return; }
                this.locating = true;
                navigator.geolocation.getCurrentPosition(pos => {
                    const { latitude, longitude, accuracy } = pos.coords;
                    this.location = latitude.toFixed(5) + ', ' + longitude.toFixed(5) + ' (±' + Math.round(accuracy) + ' m)';
                    this.locating = false;
                    if (navigator.clipboard) navigator.clipboard.writeText(this.location).then(() => {
                        this.location_copied = true;
                        setTimeout(() => { this.location_copied = false; }, 2000);
                    }).catch(() => {});
                }, () => {
                    this.locating = false;
                    this.location_error = this.$t('guide.mountain.location_error');
                }, { enableHighAccuracy: true, timeout: 20000, maximumAge: 60000 });
            },
        },
    };
</script>

<style scoped>
    .cg-mtn { display: grid; gap: 1rem; margin: 1rem 0 1.5rem; }
    .cg-mtn__card { padding: 1rem 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-mtn__card h3 { margin: 0 0 .5rem; font-size: 1.1rem !important; color: var(--cg-ink) !important; }
    .cg-mtn__card h3 .fa { color: var(--cg-accent); }
    .cg-mtn__grade { border-left: 5px solid var(--cg-accent); }

    /* emergency: one full-width card, three columns on wide screens */
    .cg-sos { display: grid; gap: 1.25rem; border-left: 5px solid #c43c3c; }
    @media (min-width: 992px) { .cg-sos { grid-template-columns: minmax(12rem, .8fr) 1.2fr 1fr; } .cg-sos > div + div { padding-left: 1.25rem; border-left: 1px solid var(--cg-line); } }
    @media (max-width: 991.98px) { .cg-sos > div + div { padding-top: 1rem; border-top: 1px solid var(--cg-line); } }
    .cg-sos h3 .fa { color: #c43c3c !important; }
    .cg-sos h4 { margin: 0 0 .5rem; font-size: .95rem !important; font-weight: 800; color: var(--cg-ink) !important; }
    .cg-sos__number { display: inline-flex; align-items: center; gap: .5rem; margin-bottom: .6rem; padding: .3rem 1.1rem; border-radius: 2rem; background: #c43c3c; color: #fff; font-family: var(--cg-font-display); font-size: 1.9rem; font-weight: 700; text-decoration: none; box-shadow: 0 4px 12px rgba(196, 60, 60, .3); }
    .cg-sos__number:hover { background: #a52f2f; color: #fff; }
    .cg-sos__lead { margin: 0 0 .5rem; font-size: .9rem; }
    .cg-sos__app { margin: 0; color: var(--cg-stone); font-size: .82rem; }
    .cg-sos__say ol { margin: 0 0 .75rem; padding-left: 1.2rem; font-size: .9rem; }
    .cg-sos__say li { margin-bottom: .35rem; }
    .cg-sos__say li::marker { color: #c43c3c; font-weight: 800; }
    .cg-sos__coords { margin: .5rem 0 0; }
    .cg-sos__coords code { padding: .2rem .45rem; border-radius: 4px; background: var(--cg-chalk); color: var(--cg-ink); font-size: .9rem; user-select: all; }
    .cg-sos__coords small { margin-left: .4rem; color: #2fa36b; font-weight: 700; }
    .cg-sos__err { margin: .5rem 0 0; color: #c43c3c; font-size: .82rem; font-weight: 700; }
    .cg-sos__tip { margin: .75rem 0 0; color: var(--cg-stone); font-size: .82rem; }
    .cg-sos__hint { margin: 0 0 .6rem; color: var(--cg-stone); font-size: .85rem; }
</style>
