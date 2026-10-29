<template>
    <!-- "How is it right now": climbers' recent condition reports on an
         outdoor / ice area (dry, wet, ice in, hazard, closure…). Hazard and
         closure reports from the last 30 days also show as a warning banner.
         API: get_area_report/* (public), set_area_report/* (logged in). -->
    <section class="cg-area-reports" id="conditions" :aria-label="$t('guide.conditions.title')">
        <div v-for="w in warnings" :key="'w' + w.id" class="cg-area-reports__warning" :class="'is-' + w.condition" role="alert">
            <i class="fa" :class="meta(w.condition).icon" aria-hidden="true"></i>
            <div>
                <strong>{{ $t('guide.conditions.types.' + w.condition) }}</strong>
                <span v-if="w.note"> — {{ w.note }}</span>
                <small class="d-block">{{ $timeAgo(w.created_at) }}<template v-if="w.user"> · {{ w.user.name }}</template></small>
            </div>
        </div>

        <div class="cg-area-reports__card">
            <div class="cg-area-reports__head">
                <h2 class="cg-area-reports__title">
                    <i class="fa fa-thermometer-half" aria-hidden="true"></i> {{ $t('guide.conditions.title') }}
                    <small>{{ $t('guide.conditions.window', { days: FEED_DAYS }) }}</small>
                </h2>
                <button type="button" class="btn btn-sm btn-hold d-print-none" @click="toggle_form" :aria-expanded="show_form">
                    <i class="fa fa-bullhorn" aria-hidden="true"></i> {{ $t('guide.conditions.report') }}
                </button>
            </div>

            <form v-if="show_form" class="cg-area-reports__form" @submit.prevent="send">
                <p class="cg-area-reports__step"><span>1</span> {{ $t('guide.conditions.how_is_it') }}</p>
                <div class="cg-area-reports__types" role="radiogroup" :aria-label="$t('guide.conditions.how_is_it')">
                    <button
                        v-for="c in offered"
                        :key="c"
                        type="button"
                        role="radio"
                        class="cg-cond-card"
                        :class="['is-' + c, { 'is-active': form.condition === c }]"
                        :aria-checked="form.condition === c"
                        @click="form.condition = c"
                    >
                        <i class="fa" :class="meta(c).icon" aria-hidden="true"></i>
                        <strong>{{ $t('guide.conditions.types.' + c) }}</strong>
                        <small>{{ $t('guide.conditions.hints.' + c) }}</small>
                    </button>
                </div>

                <p class="cg-area-reports__step mt-3"><span>2</span> {{ $t('guide.conditions.note_title') }} <small>{{ $t('guide.conditions.optional') }}</small></p>
                <textarea class="form-control" rows="2" maxlength="500" v-model="form.note"
                          :placeholder="$t('guide.conditions.examples.' + (form.condition || 'info'))"></textarea>
                <div class="cg-area-reports__meta">
                    <span><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $t('guide.conditions.visible_for', { days: FEED_DAYS }) }}</span>
                    <span>{{ form.note.length }} / 500</span>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                    <button type="submit" class="btn btn-primary" :disabled="!form.condition || sending">
                        <span v-if="sending" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        <i v-else class="fa fa-paper-plane" aria-hidden="true"></i> {{ $t('guide.conditions.send') }}
                    </button>
                    <button type="button" class="btn btn-link" @click="show_form = false">{{ $t('guide.conditions.cancel') }}</button>
                    <span v-if="!form.condition" class="cg-area-reports__pick">{{ $t('guide.conditions.pick_first') }}</span>
                    <span v-if="error" class="text-danger small fw-bold">{{ error }}</span>
                </div>
            </form>

            <div v-if="loading" class="cg-area-reports__skeleton" aria-hidden="true">
                <span></span><span></span>
            </div>

            <p v-else-if="!reports.length" class="cg-area-reports__empty">
                <i class="fa fa-question-circle-o" aria-hidden="true"></i> {{ $t('guide.conditions.empty') }}
            </p>

            <ul v-else class="cg-area-reports__list">
                <li v-for="(r, i) in visible" :key="r.id" class="cg-area-reports__item" :class="{ 'is-latest': i === 0 }">
                    <span class="cg-cond is-active" :class="'is-' + r.condition">
                        <i class="fa" :class="meta(r.condition).icon" aria-hidden="true"></i> {{ $t('guide.conditions.types.' + r.condition) }}
                    </span>
                    <span class="cg-area-reports__note" v-if="r.note">{{ r.note }}</span>
                    <small class="cg-area-reports__by">
                        <time :datetime="r.created_at" :title="$formatDate(r.created_at)">{{ $timeAgo(r.created_at) }}</time>
                        <template v-if="r.user"> · <router-link :to="{ name: 'climberProfile', params: { id: r.user.id } }" v-if="has_profile_route">{{ r.user.name }}</router-link><span v-else>{{ r.user.name }}</span></template>
                        <button v-if="mine.includes(r.id)" type="button" class="btn btn-link btn-sm p-0 ms-2 text-danger" @click="remove(r)">{{ $t('guide.conditions.delete') }}</button>
                    </small>
                </li>
            </ul>
            <button v-if="reports.length > 3" type="button" class="btn btn-link btn-sm px-0" @click="all = !all">
                {{ all ? $t('guide.conditions.show_less') : $t('guide.conditions.show_all', { n: reports.length }) }}
            </button>
        </div>
    </section>
</template>

<script>
    const META = {
        dry:      { icon: 'fa-sun-o' },
        wet:      { icon: 'fa-tint' },
        ice_good: { icon: 'fa-snowflake-o' },
        ice_poor: { icon: 'fa-thermometer-three-quarters' },
        crowded:  { icon: 'fa-users' },
        hazard:   { icon: 'fa-exclamation-triangle' },
        closure:  { icon: 'fa-ban' },
        info:     { icon: 'fa-info-circle' },
    };
    const WARNINGS = ['hazard', 'closure'];
    const WARNING_DAYS = 4; // AreaConditionReport::WARNING_DAYS
    const FEED_DAYS = 4;    // AreaConditionReport::FEED_DAYS
    const MINE_KEY = 'cg_my_area_reports';

    export default {
        name: 'AreaConditions',
        props: {
            article_id: { type: [Number, String], required: true },
            category: { type: String, default: 'outdoor' },
        },
        data() {
            return {
                reports: [],
                loading: true,
                show_form: false,
                sending: false,
                error: '',
                all: false,
                form: { condition: '', note: '' },
                mine: this.read_mine(),
                FEED_DAYS,
            };
        },
        computed: {
            offered() {
                return this.category === 'ice'
                    ? ['ice_good', 'ice_poor', 'wet', 'crowded', 'hazard', 'closure', 'info']
                    : ['dry', 'wet', 'crowded', 'hazard', 'closure', 'info'];
            },
            warnings() {
                const since = Date.now() - WARNING_DAYS * 86400000;
                return this.reports.filter(r => WARNINGS.includes(r.condition) && new Date(String(r.created_at).replace(' ', 'T')) >= since).slice(0, 2);
            },
            visible() { return this.all ? this.reports : this.reports.slice(0, 3); },
            has_profile_route() { return !!(this.$router && this.$router.hasRoute && this.$router.hasRoute('climberProfile')); },
        },
        watch: {
            article_id() { this.load(); },
        },
        mounted() { this.load(); },
        methods: {
            meta(c) { return META[c] || META.info; },
            read_mine() {
                try { return JSON.parse(localStorage.getItem(MINE_KEY) || '[]'); } catch (e) { return []; }
            },
            load() {
                this.loading = true;
                axios.get('/get_area_report/get_area_reports/' + this.article_id)
                    .then(r => { this.reports = r.data || []; })
                    .catch(() => { this.reports = []; })
                    .finally(() => { this.loading = false; });
            },
            toggle_form() {
                // reports are signed: log in / sign up in the modal, then the form opens
                if (!localStorage.getItem('auth_token')) {
                    this.$bus.$emit('open-register-modal', {
                        reason: this.$t('guide.conditions.login_reason'),
                        callback: () => { if (localStorage.getItem('auth_token')) { this.show_form = true; this.error = ''; } },
                    });
                    return;
                }
                this.show_form = !this.show_form;
                this.error = '';
            },
            send() {
                this.sending = true;
                this.error = '';
                axios.post('/set_area_report/create_area_report/' + this.article_id, this.form)
                    .then(r => {
                        this.mine = this.mine.concat([r.data.report_id]).slice(-50);
                        try { localStorage.setItem(MINE_KEY, JSON.stringify(this.mine)); } catch (e) {}
                        this.form = { condition: '', note: '' };
                        this.show_form = false;
                        this.$bus.$emit('toast', { type: 'success', duration: 5000, message: this.$t('guide.conditions.thanks', { days: FEED_DAYS }) });
                        this.load();
                    })
                    .catch(e => {
                        this.error = (e.response && e.response.data && e.response.data.message) || this.$t('guide.conditions.error');
                    })
                    .finally(() => { this.sending = false; });
            },
            remove(r) {
                if (!confirm(this.$t('guide.conditions.delete_confirm'))) return;
                axios.delete('/set_area_report/del_my_area_report/' + r.id)
                    .then(() => { this.reports = this.reports.filter(x => x.id !== r.id); })
                    .catch(() => {});
            },
        },
    };
</script>

<style scoped>
    .cg-area-reports { margin: 1rem 0 1.5rem; }
    .cg-area-reports__warning {
        display: flex; gap: .75rem; align-items: flex-start;
        margin-bottom: .75rem; padding: .75rem 1rem;
        border-radius: var(--cg-r); border-left: 5px solid #c43c3c;
        background: #fdecec; color: #7d1f1f; font-size: .92rem;
    }
    .cg-area-reports__warning.is-closure { border-color: #6c757d; background: #eef0f2; color: #343a40; }
    .cg-area-reports__warning > .fa { font-size: 1.3rem; margin-top: .1rem; }
    .cg-area-reports__card {
        padding: 1rem 1.1rem; background: #fff;
        border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm);
    }
    .cg-area-reports__head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; flex-wrap: wrap; margin-bottom: .6rem; }
    .cg-area-reports__title { margin: 0 !important; font-size: 1.3rem; }
    .cg-area-reports__title .fa { color: var(--cg-accent); }
    .cg-area-reports__form { margin-bottom: .9rem; padding: 1rem; border-radius: var(--cg-r); background: var(--cg-chalk); }
    .cg-area-reports__title small { display: block; margin-top: .15rem; color: var(--cg-muted); font-family: var(--cg-font-body); font-size: .8rem; font-weight: 600; }
    .cg-area-reports__step { display: flex; align-items: center; gap: .5rem; margin: 0 0 .5rem; font-weight: 800; color: var(--cg-ink); }
    .cg-area-reports__step > span { display: inline-grid; place-items: center; width: 1.5rem; height: 1.5rem; border-radius: 50%; background: var(--cg-accent); color: #fff; font-size: .8rem; }
    .cg-area-reports__step small { color: var(--cg-muted); font-weight: 600; }
    .cg-area-reports__types { display: grid; grid-template-columns: repeat(auto-fill, minmax(9.5rem, 1fr)); gap: .5rem; }
    .cg-cond-card {
        display: flex; flex-direction: column; align-items: flex-start; gap: .15rem;
        padding: .6rem .75rem; border: 2px solid var(--cg-line); border-radius: var(--cg-r);
        background: #fff; color: var(--cg-ink); text-align: left; line-height: 1.25;
        transition: border-color .15s, background-color .15s, box-shadow .15s;
    }
    .cg-cond-card .fa { font-size: 1.2rem; margin-bottom: .15rem; }
    .cg-cond-card strong { font-size: .9rem; }
    .cg-cond-card small { color: var(--cg-stone); font-size: .76rem; }
    .cg-cond-card:hover { border-color: var(--cg-c, var(--cg-accent)); }
    .cg-cond-card:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .45); outline-offset: 2px; }
    .cg-cond-card.is-active { border-color: var(--cg-c, var(--cg-accent)); background: color-mix(in srgb, var(--cg-c, var(--cg-accent)) 10%, #fff); box-shadow: inset 0 0 0 1px var(--cg-c, var(--cg-accent)); }
    .cg-cond-card .fa { color: var(--cg-c, var(--cg-accent)); }
    .cg-cond-card.is-dry { --cg-c: #e09a1e; }
    .cg-cond-card.is-wet { --cg-c: #3a7fc4; }
    .cg-cond-card.is-ice_good { --cg-c: #2a9fc9; }
    .cg-cond-card.is-ice_poor { --cg-c: #8a6dc7; }
    .cg-cond-card.is-crowded { --cg-c: #7b858e; }
    .cg-cond-card.is-hazard { --cg-c: #c43c3c; }
    .cg-cond-card.is-closure { --cg-c: #343a40; }
    .cg-area-reports__meta { display: flex; justify-content: space-between; gap: 1rem; margin-top: .35rem; color: var(--cg-muted); font-size: .78rem; font-weight: 600; }
    .cg-area-reports__pick { color: var(--cg-stone); font-size: .82rem; }
    .cg-cond {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .3rem .7rem; border-radius: 2rem; border: 1px solid var(--cg-line);
        background: #fff; color: var(--cg-ink-2); font-size: .82rem; font-weight: 700; white-space: nowrap;
    }
    button.cg-cond { cursor: pointer; }
    button.cg-cond:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .45); outline-offset: 2px; }
    .cg-cond.is-active { border-color: transparent; color: #fff; }
    .cg-cond.is-active.is-dry { background: #e09a1e; }
    .cg-cond.is-active.is-wet { background: #3a7fc4; }
    .cg-cond.is-active.is-ice_good { background: #2a9fc9; }
    .cg-cond.is-active.is-ice_poor { background: #8a6dc7; }
    .cg-cond.is-active.is-crowded { background: #7b858e; }
    .cg-cond.is-active.is-hazard { background: #c43c3c; }
    .cg-cond.is-active.is-closure { background: #343a40; }
    .cg-cond.is-active.is-info { background: var(--cg-accent); }
    .cg-area-reports__list { list-style: none; margin: 0; padding: 0; }
    .cg-area-reports__item { display: flex; flex-wrap: wrap; align-items: center; gap: .3rem .6rem; padding: .55rem 0; border-top: 1px dashed var(--cg-line); }
    .cg-area-reports__item:first-child { border-top: 0; }
    .cg-area-reports__item.is-latest .cg-cond { font-size: .95rem; padding: .4rem .9rem; }
    .cg-area-reports__note { color: var(--cg-ink); font-size: .92rem; }
    .cg-area-reports__by { width: 100%; color: var(--cg-muted); font-weight: 600; }
    /* undo the legacy global `time` (event list date badge) rule */
    .cg-area-reports__by time { display: inline; width: auto; padding: 0; color: inherit; text-align: inherit; text-transform: none; }
    .cg-area-reports__empty { margin: 0; color: var(--cg-stone); font-weight: 600; }
    .cg-area-reports__skeleton span { display: block; height: 1.6rem; margin: .4rem 0; border-radius: 2rem; background: linear-gradient(90deg, var(--cg-chalk-2) 25%, var(--cg-chalk) 50%, var(--cg-chalk-2) 75%); background-size: 200% 100%; animation: cg-shimmer 1.2s linear infinite; }
    .cg-area-reports__skeleton span + span { width: 60%; }
    @keyframes cg-shimmer { to { background-position: -200% 0; } }
    @media (prefers-reduced-motion: reduce) { .cg-area-reports__skeleton span { animation: none; } }
</style>
