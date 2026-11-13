<template>
    <!-- Notifications → Seasonal campaigns. docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md -->
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="sc-dash">
                <header class="sc-dash__head">
                    <div>
                        <h1 class="h3 mb-1">{{ $t('admin.seasonal.title') }}</h1>
                        <p class="text-muted mb-0">{{ $t('admin.seasonal.subtitle') }}</p>
                    </div>
                    <button v-if="can.add" type="button" class="btn btn-primary btn-sm" @click="open_form(null)">
                        <i class="fa fa-plus" aria-hidden="true"></i> {{ $t('admin.seasonal.add') }}
                    </button>
                </header>

                <div v-if="loading && !campaigns.length" class="text-muted py-4">{{ $t('admin.seasonal.loading') }}</div>

                <template v-else>
                    <!-- tiles -->
                    <div class="sc-tiles">
                        <div class="sc-tile">
                            <div class="sc-tile__label">{{ $t('admin.seasonal.tile_running') }}</div>
                            <div class="sc-tile__value">{{ running.length }}</div>
                            <div class="sc-tile__note">{{ running.map(c => c.name).join(' · ') || $t('admin.seasonal.none') }}</div>
                        </div>
                        <div class="sc-tile">
                            <div class="sc-tile__label">{{ $t('admin.seasonal.tile_mails_30d') }}</div>
                            <div class="sc-tile__value">{{ fmt(sum('mails_30d')) }}</div>
                            <div class="sc-tile__note">{{ $t('admin.seasonal.tile_all_time', { n: fmt(sum('mails_total')) }) }}</div>
                        </div>
                        <div class="sc-tile">
                            <div class="sc-tile__label">{{ $t('admin.seasonal.tile_next') }}</div>
                            <div class="sc-tile__value sc-tile__value--sm">{{ next_up ? next_up.name : '—' }}</div>
                            <div class="sc-tile__note" v-if="next_up">{{ date(next_up.next_start) }} · {{ $t('admin.seasonal.in_days', { n: days_until(next_up.next_start) }) }}</div>
                        </div>
                    </div>

                    <!-- year timeline -->
                    <section class="sc-card" v-if="campaigns.length">
                        <h2 class="h6 sc-card__title">{{ $t('admin.seasonal.timeline_title') }}</h2>
                        <div class="sc-year">
                            <div class="sc-year__row sc-year__row--head">
                                <div class="sc-year__label"></div>
                                <div class="sc-year__track">
                                    <span v-for="(m, i) in months" :key="m" class="sc-year__month" :style="{ left: month_left(i) + '%' }">{{ m }}</span>
                                </div>
                            </div>
                            <div v-for="c in campaigns" :key="c.id" class="sc-year__row" role="button" tabindex="0"
                                @click="can.edit && open_form(c)" @keydown.enter="can.edit && open_form(c)">
                                <div class="sc-year__label" :title="c.name">{{ c.name }}</div>
                                <div class="sc-year__track">
                                    <span v-for="(seg, i) in segments(c)" :key="i" class="sc-year__bar"
                                        :class="bar_class(c)" :style="{ left: seg[0] + '%', width: Math.max(seg[1] - seg[0], 0.8) + '%' }"></span>
                                </div>
                            </div>
                            <div class="sc-year__today" :style="{ left: 'calc(var(--sc-label) + (100% - var(--sc-label)) * ' + (today_pos / 100) + ')' }">
                                <span>{{ $t('admin.seasonal.today') }}</span>
                            </div>
                        </div>
                        <div class="sc-legend">
                            <span><i class="sc-year__bar is-live"></i> {{ $t('admin.seasonal.legend_live') }}</span>
                            <span><i class="sc-year__bar is-on"></i> {{ $t('admin.seasonal.legend_on') }}</span>
                            <span><i class="sc-year__bar is-off"></i> {{ $t('admin.seasonal.legend_off') }}</span>
                        </div>
                    </section>

                    <div v-if="!campaigns.length" class="sc-card text-muted">{{ $t('admin.seasonal.empty') }}</div>

                    <!-- campaigns -->
                    <div class="sc-list">
                        <article v-for="c in campaigns" :key="c.id" class="sc-card sc-campaign" :class="{ 'is-off': !c.enabled }">
                            <div class="sc-campaign__top">
                                <div>
                                    <h3 class="h6 mb-1">{{ c.name }}</h3>
                                    <span class="badge" :class="status(c).cls">{{ status(c).label }}</span>
                                    <span class="sc-muted ms-2">{{ season_text(c) }}</span>
                                </div>
                                <div class="form-check form-switch m-0" v-if="can.edit">
                                    <input class="form-check-input" type="checkbox" role="switch" :id="'sc-on-' + c.id"
                                        :checked="c.enabled" :disabled="busy[c.id]" @change="toggle(c)">
                                    <label class="form-check-label small" :for="'sc-on-' + c.id">{{ c.enabled ? $t('admin.seasonal.on') : $t('admin.seasonal.off') }}</label>
                                </div>
                            </div>

                            <dl class="sc-facts">
                                <div>
                                    <dt>{{ $t('admin.seasonal.f_content') }}</dt>
                                    <dd>
                                        {{ $t('admin.seasonal.category.' + c.category) }} · {{ $t('admin.seasonal.type.' + c.content_type) }}<template v-if="c.content_type === 'sector' && c.sun_filter !== 'any'"> · {{ $t('admin.seasonal.sun.' + c.sun_filter) }}</template>
                                        <span class="sc-muted" :class="{ 'text-danger': !c.content_count }">({{ $t('admin.seasonal.items_available', { n: c.content_count }) }})</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt>{{ $t('admin.seasonal.f_audience') }}</dt>
                                    <dd>{{ audience_text(c) }} <span class="sc-muted">({{ $t('admin.seasonal.people', { n: fmt(c.audience_count) }) }})</span></dd>
                                </div>
                                <div>
                                    <dt>{{ $t('admin.seasonal.f_cadence') }}</dt>
                                    <dd>{{ $t('admin.seasonal.cadence', { days: c.every_days, n: c.items_per_mail }) }} · {{ $t('admin.seasonal.pick.' + c.pick) }}</dd>
                                </div>
                                <div>
                                    <dt>{{ $t('admin.seasonal.f_sent') }}</dt>
                                    <dd>{{ $t('admin.seasonal.sent_stats', { d30: fmt(c.stats.mails_30d), all: fmt(c.stats.mails_total), people: fmt(c.stats.users_total) }) }}</dd>
                                </div>
                                <div>
                                    <dt>{{ $t('admin.seasonal.f_last_run') }}</dt>
                                    <dd>{{ c.last_run_at ? datetime(c.last_run_at) + ' · ' + $t('admin.seasonal.last_run_sent', { n: c.last_run_sent }) : $t('admin.seasonal.never') }}</dd>
                                </div>
                                <div>
                                    <dt>{{ $t('admin.seasonal.f_subject') }}</dt>
                                    <dd class="text-truncate">{{ c.subject_us }}</dd>
                                </div>
                            </dl>

                            <div class="sc-actions">
                                <button type="button" class="btn btn-outline-secondary btn-sm" :disabled="busy[c.id]" @click="preview(c)">
                                    <i class="fa fa-eye" aria-hidden="true"></i> {{ $t('admin.seasonal.preview') }}
                                </button>
                                <button v-if="can.edit" type="button" class="btn btn-outline-secondary btn-sm" :disabled="busy[c.id]" @click="send_test(c)">
                                    <i class="fa fa-paper-plane-o" aria-hidden="true"></i> {{ $t('admin.seasonal.send_test') }}
                                </button>
                                <button v-if="can.edit" type="button" class="btn btn-outline-secondary btn-sm" :disabled="busy[c.id] || !c.in_season" :title="c.in_season ? '' : $t('admin.seasonal.run_out_of_season')" @click="run_now(c)">
                                    <i class="fa fa-play" aria-hidden="true"></i> {{ $t('admin.seasonal.run_now') }}
                                </button>
                                <span class="flex-grow-1"></span>
                                <button v-if="can.edit" type="button" class="btn btn-link btn-sm" @click="open_form(c)">{{ $t('common.edit') }}</button>
                                <button v-if="can.del" type="button" class="btn btn-link btn-sm text-danger" @click="remove(c)">{{ $t('common.delete') }}</button>
                            </div>
                        </article>
                    </div>

                    <p class="sc-rules">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        {{ $t('admin.seasonal.rules', { gap: gap_days }) }}
                    </p>
                </template>
            </div>
        </div>

        <!-- preview -->
        <StackModal :show="!!preview_data" :title="$t('admin.seasonal.preview_title')"
            :saveButton="{ visible: false }" :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
            @close="preview_data = null">
            <template v-if="preview_data">
                <p class="mb-2"><strong>{{ preview_data.subject }}</strong></p>
                <p v-if="!preview_data.in_season" class="small text-warning mb-2">{{ $t('admin.seasonal.preview_out_of_season') }}</p>
                <p v-if="!preview_data.due" class="small text-muted mb-2">{{ $t('admin.seasonal.preview_not_due') }}</p>
                <div v-if="!preview_data.items.length" class="text-muted">{{ $t('admin.seasonal.preview_empty') }}</div>
                <div v-for="item in preview_data.items" :key="item.type + item.id" class="sc-preview">
                    <img v-if="item.image" :src="item.image" alt="" class="sc-preview__img" loading="lazy">
                    <div>
                        <a :href="item.url" target="_blank" rel="noopener"><strong>{{ item.title }}</strong></a>
                        <p class="small text-muted mb-0">{{ item.text }}</p>
                    </div>
                </div>
            </template>
        </StackModal>

        <seasonal-campaign-form ref="form" :options="options" @saved="load" />
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import SeasonalCampaignForm from './SeasonalCampaignFormModal.vue'

    const MONTH_STARTS = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334]   // day-of-year, non-leap

    export default {
        components: { breadcrumb, 'seasonal-campaign-form': SeasonalCampaignForm },
        data() {
            return {
                loading: false,
                campaigns: [],
                options: {},
                can: { add: false, edit: false, del: false },
                gap_days: 3,
                today: null,
                busy: {},
                preview_data: null,
            }
        },
        computed: {
            months() {
                const locale = this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'
                return Array.from({ length: 12 }, (_, i) => new Date(2025, i, 1).toLocaleString(locale, { month: 'short' }))
            },
            running() { return this.campaigns.filter(c => c.enabled && c.in_season) },
            next_up() {
                return this.campaigns.filter(c => c.enabled && c.next_start)
                    .sort((a, b) => a.next_start.localeCompare(b.next_start))[0] || null
            },
            today_pos() { return this.today ? this.md_pos(this.today.slice(5)) : 0 },
        },
        mounted() { this.load() },
        methods: {
            load() {
                this.loading = true
                return axios.get('set_seasonal_campaign/list')
                    .then(res => {
                        this.campaigns = res.data.campaigns || []
                        this.options = res.data.options || {}
                        this.can = res.data.can || this.can
                        this.gap_days = res.data.gap_days
                        this.today = res.data.today
                    })
                    .catch(() => this.toast('danger', this.$t('admin.seasonal.load_error')))
                    .finally(() => { this.loading = false })
            },
            // ── timeline ──
            md_pos(md) {
                const [m, d] = md.split('-').map(Number)
                return ((MONTH_STARTS[m - 1] + Math.min(d, 28 + (m === 2 ? 0 : 3)) - 1) / 365) * 100
            },
            month_left(i) { return (MONTH_STARTS[i] / 365) * 100 },
            segments(c) {
                const s = this.md_pos(c.start_md), e = this.md_pos(c.end_md) + 100 / 365
                return s <= e ? [[s, e]] : [[s, 100], [0, e]]   // wraps the new year
            },
            bar_class(c) { return !c.enabled ? 'is-off' : (c.in_season ? 'is-live' : 'is-on') },
            status(c) {
                if (!c.enabled) return { cls: 'text-bg-secondary', label: this.$t('admin.seasonal.status_off') }
                if (c.in_season) return { cls: 'text-bg-success', label: this.$t('admin.seasonal.status_running') }
                return { cls: 'text-bg-info', label: this.$t('admin.seasonal.status_waiting') }
            },
            season_text(c) {
                const range = this.md_text(c.start_md) + ' – ' + this.md_text(c.end_md)
                if (c.in_season && c.season_end) return range + ' · ' + this.$t('admin.seasonal.ends_in', { n: this.days_until(c.season_end) })
                if (c.next_start) return range + ' · ' + this.$t('admin.seasonal.starts_in', { n: this.days_until(c.next_start) })
                return range
            },
            md_text(md) {
                const [m, d] = md.split('-').map(Number)
                return new Date(2025, m - 1, d).toLocaleString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short' })
            },
            audience_text(c) {
                const a = this.$t('admin.seasonal.audience.' + c.audience)
                return c.audience === 'inactive' ? a + ' ' + this.$t('admin.seasonal.inactive_days', { n: c.inactive_days }) : a
            },
            days_until(iso) {
                const ms = new Date(iso + 'T00:00:00') - new Date((this.today || new Date().toISOString().slice(0, 10)) + 'T00:00:00')
                return Math.max(0, Math.round(ms / 864e5))
            },
            sum(key) { return this.campaigns.reduce((t, c) => t + ((c.stats && c.stats[key]) || 0), 0) },
            fmt(v) { return new Intl.NumberFormat(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB').format(Number(v) || 0) },
            date(iso) { return iso ? new Date(iso + 'T00:00:00').toLocaleDateString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '' },
            datetime(iso) { return iso ? new Date(iso).toLocaleString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }) : '' },
            toast(type, message) { this.$bus.$emit('toast', { type, message }) },
            // ── actions ──
            open_form(c) { this.$refs.form.open(c) },
            with_busy(c, fn) {
                this.busy = { ...this.busy, [c.id]: true }
                return fn().finally(() => { this.busy = { ...this.busy, [c.id]: false } })
            },
            toggle(c) {
                this.with_busy(c, () => axios.post('set_seasonal_campaign/toggle/' + c.id)
                    .then(res => { c.enabled = res.data.enabled; this.load() })
                    .catch(() => this.toast('danger', this.$t('admin.seasonal.save_error'))))
            },
            preview(c) {
                this.with_busy(c, () => axios.get('set_seasonal_campaign/preview/' + c.id)
                    .then(res => { this.preview_data = res.data })
                    .catch(() => this.toast('danger', this.$t('admin.seasonal.load_error'))))
            },
            send_test(c) {
                this.with_busy(c, () => axios.post('set_seasonal_campaign/send_test/' + c.id)
                    .then(res => this.toast('success', res.data.message))
                    .catch(err => this.toast('danger', (err.response && err.response.data && err.response.data.message) || this.$t('admin.seasonal.save_error'))))
            },
            run_now(c) {
                // count first, then ask — a run really mails people
                this.with_busy(c, () => axios.post('set_seasonal_campaign/run/' + c.id, { dry: 1 })
                    .then(res => {
                        if (!res.data.sent) {
                            this.toast('info', this.$t('admin.seasonal.run_nobody'))
                            return
                        }
                        if (!confirm(this.$t('admin.seasonal.run_confirm', { n: res.data.sent }))) return
                        return axios.post('set_seasonal_campaign/run/' + c.id).then(r => {
                            this.toast('success', this.$t('admin.seasonal.run_done', { n: r.data.sent }))
                            this.load()
                        })
                    })
                    .catch(() => this.toast('danger', this.$t('admin.seasonal.save_error'))))
            },
            remove(c) {
                if (!confirm(this.$t('admin.seasonal.delete_confirm', { name: c.name }))) return
                this.with_busy(c, () => axios.delete('set_seasonal_campaign/delete/' + c.id)
                    .then(() => this.load())
                    .catch(() => this.toast('danger', this.$t('admin.seasonal.save_error'))))
            },
        },
    }
</script>

<style scoped>
    .sc-dash { --sc-label: 190px; padding: 0 0 2rem; }
    .sc-dash__head { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; }
    .sc-tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .75rem; margin-bottom: 1rem; }
    .sc-tile, .sc-card { padding: 1rem; background: var(--cg-surface, #fff); border: 1px solid var(--cg-line); border-radius: var(--cg-r); }
    .sc-card { margin-bottom: 1rem; }
    .sc-card__title { margin-bottom: .75rem; text-transform: uppercase; letter-spacing: .04em; color: var(--cg-muted); }
    .sc-tile__label { font-size: .85rem; color: var(--cg-muted); }
    .sc-tile__value { font-size: 1.75rem; font-weight: 600; color: var(--cg-ink); line-height: 1.2; }
    .sc-tile__value--sm { font-size: 1.1rem; padding: .3rem 0; }
    .sc-tile__note { font-size: .82rem; color: var(--cg-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sc-muted { font-size: .85rem; color: var(--cg-muted); }

    /* year timeline */
    .sc-year { position: relative; }
    .sc-year__row { display: flex; align-items: center; min-height: 28px; border-radius: 6px; cursor: pointer; }
    .sc-year__row:hover:not(.sc-year__row--head) { background: var(--cg-accent-soft); }
    .sc-year__row--head { cursor: default; min-height: 22px; }
    .sc-year__label { flex: 0 0 var(--sc-label); padding-right: .75rem; font-size: .85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sc-year__track { position: relative; flex: 1; height: 28px; border-left: 1px solid var(--cg-line); }
    .sc-year__row--head .sc-year__track { height: 22px; border-left: 0; }
    .sc-year__month { position: absolute; top: 2px; font-size: .75rem; color: var(--cg-muted); padding-left: 3px; border-left: 1px solid var(--cg-line); height: 18px; }
    .sc-year__bar { position: absolute; top: 8px; height: 12px; border-radius: 6px; display: inline-block; }
    .sc-year__bar.is-live { background: var(--cg-accent); }
    .sc-year__bar.is-on { background: var(--cg-accent); opacity: .35; }
    .sc-year__bar.is-off { background: repeating-linear-gradient(45deg, var(--cg-line), var(--cg-line) 4px, transparent 4px, transparent 8px); border: 1px solid var(--cg-line); }
    .sc-year__today { position: absolute; top: 0; bottom: 0; border-left: 2px dashed var(--bs-danger); pointer-events: none; }
    .sc-year__today span { position: absolute; top: -2px; left: 4px; font-size: .7rem; font-weight: 600; color: var(--bs-danger); background: var(--cg-surface, #fff); padding: 0 2px; }
    .sc-legend { display: flex; flex-wrap: wrap; gap: 1rem; margin-top: .75rem; font-size: .8rem; color: var(--cg-muted); }
    .sc-legend .sc-year__bar { position: static; width: 22px; vertical-align: middle; margin-right: 4px; }

    /* campaign cards */
    .sc-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(420px, 1fr)); gap: 1rem; }
    .sc-campaign { margin-bottom: 0; display: flex; flex-direction: column; }
    .sc-campaign.is-off { background: var(--cg-chalk, #f8f7f4); }
    .sc-campaign__top { display: flex; justify-content: space-between; gap: .75rem; align-items: flex-start; margin-bottom: .75rem; }
    .sc-facts { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem 1rem; margin: 0 0 .75rem; font-size: .88rem; }
    .sc-facts dt { font-weight: 600; font-size: .75rem; text-transform: uppercase; letter-spacing: .03em; color: var(--cg-muted); }
    .sc-facts dd { margin: 0; }
    .sc-actions { display: flex; flex-wrap: wrap; gap: .4rem; align-items: center; margin-top: auto; }
    .sc-rules { font-size: .85rem; color: var(--cg-muted); margin-top: 1rem; }
    .sc-preview { display: flex; gap: .75rem; padding: .6rem 0; border-top: 1px solid var(--cg-line); }
    .sc-preview__img { width: 96px; height: 64px; object-fit: cover; border-radius: 6px; flex: 0 0 auto; }

    @media (max-width: 767px) {
        .sc-dash { --sc-label: 110px; }
        .sc-list { grid-template-columns: 1fr; }
        .sc-facts { grid-template-columns: 1fr; }
    }
</style>
