<template>
    <!-- Mountaineering route helpers, under the grade bar: the grade in plain
         words, a gear checklist that remembers ticks per route (localStorage),
         and an emergency card with 112 + a "share my trip plan" message. -->
    <div class="cg-mtn">
        <section v-if="level" class="cg-mtn__card cg-mtn__grade">
            <h3><i class="fa fa-info-circle" aria-hidden="true"></i> {{ $t('guide.mountain.grade_title', { grade: grade_label }) }}</h3>
            <p class="mb-0">{{ $t('guide.mountain.levels.' + level) }}</p>
        </section>

        <section class="cg-mtn__card cg-mtn__gear">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <h3 class="mb-0"><i class="fa fa-check-square-o" aria-hidden="true"></i> {{ $t('guide.mountain.gear_title') }}</h3>
                <small class="cg-mtn__count">{{ checked_count }} / {{ gear.length }}</small>
            </div>
            <div class="cg-mtn__bar" aria-hidden="true"><span :style="{ width: (gear.length ? checked_count * 100 / gear.length : 0) + '%' }"></span></div>
            <ul class="cg-mtn__list">
                <li v-for="item in gear" :key="item">
                    <label>
                        <input type="checkbox" class="form-check-input" :checked="!!checked[item]" @change="toggle(item)" />
                        <span>{{ $t('guide.mountain.gear.' + item) }}</span>
                    </label>
                </li>
            </ul>
            <button v-if="checked_count" type="button" class="btn btn-link btn-sm px-0" @click="reset">{{ $t('guide.mountain.gear_reset') }}</button>
        </section>

        <section class="cg-mtn__card cg-mtn__sos">
            <h3><i class="fa fa-life-ring" aria-hidden="true"></i> {{ $t('guide.mountain.emergency_title') }}</h3>
            <a class="cg-mtn__call" href="tel:112"><i class="fa fa-phone" aria-hidden="true"></i> 112</a>
            <p class="small mb-2">{{ $t('guide.mountain.emergency_text') }}</p>

            <details class="cg-mtn__plan">
                <summary>{{ $t('guide.mountain.plan_title') }}</summary>
                <div class="row g-2 mt-1">
                    <div class="col-6">
                        <label class="form-label small fw-bold">{{ $t('guide.mountain.plan_start') }}</label>
                        <input type="date" class="form-control form-control-sm" v-model="plan.start" />
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">{{ $t('guide.mountain.plan_back') }}</label>
                        <input type="datetime-local" class="form-control form-control-sm" v-model="plan.back" />
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">{{ $t('guide.mountain.plan_team') }}</label>
                        <input type="text" class="form-control form-control-sm" v-model="plan.team" maxlength="200" />
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-primary mt-2" @click="share_plan">
                    <i class="fa" :class="shared ? 'fa-check' : 'fa-share-alt'" aria-hidden="true"></i>
                    {{ shared ? $t('guide.mountain.plan_copied') : $t('guide.mountain.plan_share') }}
                </button>
            </details>
        </section>
    </div>
</template>

<script>
    import { FRENCH_ALPINE_GRADES, RUSSIAN_ALPINE_GRADES, getBothMountGrades, formatMountGrade } from '../../../mixins/grade_chart_mixin.js'

    // what everyone carries, then what each difficulty level adds
    const BASE_GEAR = ['helmet', 'boots', 'layers', 'headlamp', 'first_aid', 'navigation', 'water_food', 'sun', 'phone'];
    const LEVEL_GEAR = {
        1: ['crampons_axe'],
        2: ['crampons_axe', 'harness', 'rope', 'slings'],
        3: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws'],
        4: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws', 'rack', 'bivy'],
        5: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws', 'rack', 'bivy'],
        6: ['crampons_axe', 'harness', 'rope', 'slings', 'belay_device', 'ice_screws', 'rack', 'bivy'],
    };
    // French alpine grade → Russian category number (1–6)
    const FRENCH_LEVEL = { F: 1, PD: 2, AD: 3, D: 4, TD: 5, ED: 6, ABO: 6 };

    export default {
        name: 'MountainSafety',
        props: {
            grade: { type: String, default: null },
            article_id: { type: [Number, String], required: true },
            title: { type: String, default: '' },
        },
        data() {
            return { checked: {}, plan: { start: '', back: '', team: '' }, shared: false };
        },
        computed: {
            grade_label() { return formatMountGrade(this.grade); },
            level() {
                if (!this.grade) return null;
                const both = getBothMountGrades(this.grade);
                if (both.russian && RUSSIAN_ALPINE_GRADES.includes(both.russian)) return Number(both.russian.charAt(0));
                if (both.french && FRENCH_ALPINE_GRADES.includes(both.french)) {
                    const base = both.french.replace(/[-+]|\d/g, '');
                    return FRENCH_LEVEL[base] || null;
                }
                return null;
            },
            gear() { return BASE_GEAR.concat(LEVEL_GEAR[this.level] || LEVEL_GEAR[1]); },
            checked_count() { return this.gear.filter(g => this.checked[g]).length; },
            storage_key() { return 'cg_gear_' + this.article_id; },
        },
        watch: {
            article_id: { immediate: true, handler() { this.load(); } },
        },
        methods: {
            load() {
                try { this.checked = JSON.parse(localStorage.getItem(this.storage_key) || '{}'); } catch (e) { this.checked = {}; }
            },
            save() {
                try { localStorage.setItem(this.storage_key, JSON.stringify(this.checked)); } catch (e) {}
            },
            toggle(item) {
                this.checked = Object.assign({}, this.checked, { [item]: !this.checked[item] });
                this.save();
            },
            reset() { this.checked = {}; this.save(); },
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
        },
    };
</script>

<style scoped>
    .cg-mtn { display: grid; gap: 1rem; margin: 1rem 0 1.5rem; }
    @media (min-width: 992px) { .cg-mtn { grid-template-columns: 1fr 1fr; } .cg-mtn__grade { grid-column: 1 / -1; } }
    .cg-mtn__card { padding: 1rem 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-mtn__card h3 { margin: 0 0 .5rem; font-size: 1.1rem !important; color: var(--cg-ink) !important; }
    .cg-mtn__card h3 .fa { color: var(--cg-accent); }
    .cg-mtn__grade { border-left: 5px solid var(--cg-accent); }
    .cg-mtn__count { font-weight: 800; color: var(--cg-stone); }
    .cg-mtn__bar { height: .4rem; margin-bottom: .6rem; border-radius: 1rem; background: var(--cg-chalk-2); overflow: hidden; }
    .cg-mtn__bar span { display: block; height: 100%; background: #2fa36b; transition: width .3s; }
    .cg-mtn__list { list-style: none; margin: 0; padding: 0; columns: 2 12rem; column-gap: 1rem; }
    .cg-mtn__list li { break-inside: avoid; }
    .cg-mtn__list label { display: flex; gap: .5rem; align-items: flex-start; padding: .25rem 0; font-size: .92rem; cursor: pointer; }
    .cg-mtn__list input { flex: 0 0 auto; margin-top: .2rem; }
    .cg-mtn__list input:checked + span { color: var(--cg-muted); text-decoration: line-through; }
    .cg-mtn__sos { border-left: 5px solid #c43c3c; }
    .cg-mtn__sos h3 .fa { color: #c43c3c; }
    .cg-mtn__call { display: inline-flex; align-items: center; gap: .5rem; margin-bottom: .5rem; padding: .35rem 1rem; border-radius: 2rem; background: #c43c3c; color: #fff; font-family: var(--cg-font-display); font-size: 1.6rem; font-weight: 700; text-decoration: none; }
    .cg-mtn__call:hover { background: #a52f2f; color: #fff; }
    .cg-mtn__plan summary { font-weight: 800; color: var(--cg-accent-dark); cursor: pointer; }
    @media (prefers-reduced-motion: reduce) { .cg-mtn__bar span { transition: none; } }
</style>
