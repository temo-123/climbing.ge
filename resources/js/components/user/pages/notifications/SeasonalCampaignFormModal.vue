<template>
    <StackModal
        :show="show"
        :title="data.id ? $t('admin.seasonal.edit_title') : $t('admin.seasonal.add_title')"
        :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        @save="$refs.form.requestSubmit()"
        @close="show = false"
    >
        <div v-if="errors.length" class="alert alert-danger py-2" role="alert">
            <div v-for="(e, i) in errors" :key="i">{{ e }}</div>
        </div>

        <form ref="form" class="sc-form" @submit.prevent="save">
            <div class="mb-3">
                <label class="form-label" for="sc-name">{{ $t('admin.seasonal.f_name') }}</label>
                <input id="sc-name" v-model.trim="data.name" type="text" class="form-control" maxlength="120" required>
            </div>

            <fieldset class="mb-3">
                <legend class="sc-form__legend">{{ $t('admin.seasonal.f_content') }}</legend>
                <div class="row g-2">
                    <div class="col-sm-4">
                        <label class="form-label small" for="sc-type">{{ $t('admin.seasonal.f_type') }}</label>
                        <select id="sc-type" v-model="data.content_type" class="form-select">
                            <option v-for="t in options.content_types" :key="t" :value="t">{{ $t('admin.seasonal.type.' + t) }}</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small" for="sc-cat">{{ $t('admin.seasonal.f_category') }}</label>
                        <select id="sc-cat" v-model="data.category" class="form-select">
                            <option v-for="c in options.categories" :key="c" :value="c">{{ $t('admin.seasonal.category.' + c) }}</option>
                        </select>
                    </div>
                    <div class="col-sm-4" v-if="data.content_type === 'sector'">
                        <label class="form-label small" for="sc-sun">{{ $t('admin.seasonal.f_sun') }}</label>
                        <select id="sc-sun" v-model="data.sun_filter" class="form-select">
                            <option v-for="s in options.sun_filters" :key="s" :value="s">{{ $t('admin.seasonal.sun.' + s) }}</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend class="sc-form__legend">{{ $t('admin.seasonal.f_season') }}</legend>
                <div class="row g-2 align-items-end">
                    <div class="col-6 col-sm-3">
                        <label class="form-label small" for="sc-sm">{{ $t('admin.seasonal.f_from') }}</label>
                        <select id="sc-sm" v-model.number="start.m" class="form-select">
                            <option v-for="(m, i) in months" :key="i" :value="i + 1">{{ m }}</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-2">
                        <input v-model.number="start.d" type="number" min="1" :max="days_in(start.m)" class="form-control" :aria-label="$t('admin.seasonal.f_day')" required>
                    </div>
                    <div class="col-6 col-sm-3 offset-sm-1">
                        <label class="form-label small" for="sc-em">{{ $t('admin.seasonal.f_to') }}</label>
                        <select id="sc-em" v-model.number="end.m" class="form-select">
                            <option v-for="(m, i) in months" :key="i" :value="i + 1">{{ m }}</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-2">
                        <input v-model.number="end.d" type="number" min="1" :max="days_in(end.m)" class="form-control" :aria-label="$t('admin.seasonal.f_day')" required>
                    </div>
                </div>
                <small class="form-text text-muted">{{ $t('admin.seasonal.season_hint') }}</small>
            </fieldset>

            <fieldset class="mb-3">
                <legend class="sc-form__legend">{{ $t('admin.seasonal.f_audience') }}</legend>
                <div class="row g-2">
                    <div class="col-sm-6">
                        <select v-model="data.audience" class="form-select" :aria-label="$t('admin.seasonal.f_audience')">
                            <option v-for="a in options.audiences" :key="a" :value="a">{{ $t('admin.seasonal.audience.' + a) }}</option>
                        </select>
                    </div>
                    <div class="col-sm-6" v-if="data.audience === 'inactive'">
                        <div class="input-group">
                            <input v-model.number="data.inactive_days" type="number" min="7" max="730" class="form-control" required :aria-label="$t('admin.seasonal.f_inactive_days')">
                            <span class="input-group-text">{{ $t('admin.seasonal.days_away') }}</span>
                        </div>
                    </div>
                </div>
                <small class="form-text text-muted">{{ $t('admin.seasonal.audience_hint.' + data.audience) }}</small>
            </fieldset>

            <fieldset class="mb-3">
                <legend class="sc-form__legend">{{ $t('admin.seasonal.f_cadence') }}</legend>
                <div class="row g-2">
                    <div class="col-sm-3">
                        <label class="form-label small" for="sc-every">{{ $t('admin.seasonal.f_every') }}</label>
                        <input id="sc-every" v-model.number="data.every_days" type="number" min="3" max="365" class="form-control" required>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small" for="sc-items">{{ $t('admin.seasonal.f_items') }}</label>
                        <select id="sc-items" v-model.number="data.items_per_mail" class="form-select">
                            <option :value="1">1</option><option :value="2">2</option><option :value="3">3</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small" for="sc-pick">{{ $t('admin.seasonal.f_pick') }}</label>
                        <select id="sc-pick" v-model="data.pick" class="form-select">
                            <option v-for="p in options.picks" :key="p" :value="p">{{ $t('admin.seasonal.pick.' + p) }}</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small" for="sc-max">{{ $t('admin.seasonal.f_max') }}</label>
                        <input id="sc-max" v-model.number="data.max_per_run" type="number" min="1" max="5000" class="form-control" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-3">
                <legend class="sc-form__legend">{{ $t('admin.seasonal.f_text') }}</legend>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small" for="sc-sus">{{ $t('admin.seasonal.f_subject') }} (EN)</label>
                        <input id="sc-sus" v-model.trim="data.subject_us" type="text" class="form-control" maxlength="150" required>
                        <label class="form-label small mt-2" for="sc-ius">{{ $t('admin.seasonal.f_intro') }} (EN)</label>
                        <textarea id="sc-ius" v-model="data.intro_us" class="form-control" rows="3" maxlength="2000"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small" for="sc-ska">{{ $t('admin.seasonal.f_subject') }} (KA)</label>
                        <input id="sc-ska" v-model.trim="data.subject_ka" type="text" class="form-control" maxlength="150">
                        <label class="form-label small mt-2" for="sc-ika">{{ $t('admin.seasonal.f_intro') }} (KA)</label>
                        <textarea id="sc-ika" v-model="data.intro_ka" class="form-control" rows="3" maxlength="2000"></textarea>
                    </div>
                </div>
                <small class="form-text text-muted">{{ $t('admin.seasonal.text_hint') }}</small>
            </fieldset>

            <div class="form-check form-switch">
                <input id="sc-enabled" v-model="data.enabled" class="form-check-input" type="checkbox" role="switch">
                <label class="form-check-label" for="sc-enabled">{{ $t('admin.seasonal.f_enabled') }}</label>
            </div>
        </form>
    </StackModal>
</template>

<script>
    const EMPTY = {
        id: null, name: '', content_type: 'article', category: 'outdoor', sun_filter: 'any',
        start_md: '06-01', end_md: '08-31', every_days: 14, items_per_mail: 2, pick: 'popular',
        audience: 'interested', inactive_days: 45, max_per_run: 500,
        subject_us: '', subject_ka: '', intro_us: '', intro_ka: '', enabled: false,
    }
    const DAYS = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
    const pad = n => String(n).padStart(2, '0')

    export default {
        props: { options: { type: Object, default: () => ({}) } },
        emits: ['saved'],
        data() {
            return { show: false, data: { ...EMPTY }, start: { m: 6, d: 1 }, end: { m: 8, d: 31 }, errors: [] }
        },
        computed: {
            months() {
                const locale = this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'
                return Array.from({ length: 12 }, (_, i) => new Date(2025, i, 1).toLocaleString(locale, { month: 'long' }))
            },
        },
        methods: {
            open(campaign) {
                const c = campaign ? { ...EMPTY, ...campaign } : { ...EMPTY }
                this.data = c
                const [sm, sd] = c.start_md.split('-').map(Number)
                const [em, ed] = c.end_md.split('-').map(Number)
                this.start = { m: sm, d: sd }
                this.end = { m: em, d: ed }
                this.errors = []
                this.show = true
            },
            days_in(m) { return DAYS[(m || 1) - 1] },
            save() {
                const md = p => pad(p.m) + '-' + pad(Math.min(p.d, this.days_in(p.m)))
                const payload = {
                    ...this.data,
                    start_md: md(this.start),
                    end_md: md(this.end),
                    inactive_days: this.data.audience === 'inactive' ? this.data.inactive_days : null,
                }
                const url = this.data.id ? 'set_seasonal_campaign/update/' + this.data.id : 'set_seasonal_campaign/store'
                axios.post(url, payload)
                    .then(() => {
                        this.show = false
                        this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.seasonal.saved') })
                        this.$emit('saved')
                    })
                    .catch(err => {
                        const e = err.response && err.response.data
                        // Laravel 422: { errors: { field: [msg, …] } } → flat list of messages
                        this.errors = (e && e.errors) ? Object.values(e.errors).flat() : [(e && e.message) || this.$t('admin.seasonal.save_error')]
                    })
            },
        },
    }
</script>

<style scoped>
    .sc-form__legend { font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: var(--cg-muted); margin-bottom: .4rem; }
</style>
