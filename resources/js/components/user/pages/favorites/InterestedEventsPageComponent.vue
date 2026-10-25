<template>
    <!-- My interested events (the events favourite list; reminder emails go
         out for these). Same cards as the guide, Upcoming / Past tabs; the
         heart on a card removes it (with Undo). -->
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <breadcrumb />

            <div class="ie-head">
                <div>
                    <h3 class="mb-1"><i class="fa fa-heart" aria-hidden="true"></i> {{ $t('user.favorites.events.title') }}</h3>
                    <p class="text-muted mb-0">{{ $t('guide.events.reminder_hint') }}</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <div class="cg-segmented" role="tablist">
                        <button type="button" role="tab" :class="{ active: tab === 'upcoming' }" @click="tab = 'upcoming'">{{ $t('guide.events.when_upcoming') }} <small>{{ upcoming.length }}</small></button>
                        <button type="button" role="tab" :class="{ active: tab === 'past' }" @click="tab = 'past'">{{ $t('guide.events.when_past') }} <small>{{ past.length }}</small></button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" :disabled="is_loading" @click="load" :title="$t('common.refresh')">
                        <i class="fa fa-refresh" :class="{ 'fa-spin': is_loading }" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div v-if="is_loading" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>

            <div v-else-if="shown.length" class="row g-4">
                <div v-for="e in shown" :key="e.global_event.id" class="col-md-6 col-xl-4">
                    <eventCard :event="e" external />
                </div>
            </div>

            <div v-else class="ie-empty">
                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                <h4>{{ tab === 'upcoming' ? $t('user.favorites.events.empty') : $t('user.favorites.events.empty_past') }}</h4>
                <a v-if="tab === 'upcoming'" class="btn btn-primary" :href="events_url" target="_blank" rel="noopener">{{ $t('user.overview.browse_events') }}</a>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import eventCard from '../../../guide/items/cards/EventCardComponent.vue'
    import { interested, load_interested } from '../../../../services/guide/interested.js'
    import { event_status, to_date } from '../../../../services/guide/eventDates.js'

    export default {
        components: { breadcrumb, eventCard },
        data() {
            return { events: [], is_loading: true, tab: 'upcoming', interested };
        },
        computed: {
            // un-hearted cards drop out right away
            current() {
                return this.events.filter(e => !this.interested.loaded || this.interested.ids.has(Number(e.global_event.id)));
            },
            upcoming() {
                return this.current.filter(e => event_status(e.global_event).key !== 'finished')
                    .sort((a, b) => to_date(a.global_event.start_data) - to_date(b.global_event.start_data));
            },
            past() {
                return this.current.filter(e => event_status(e.global_event).key === 'finished')
                    .sort((a, b) => to_date(b.global_event.start_data) - to_date(a.global_event.start_data));
            },
            shown() { return this.tab === 'upcoming' ? this.upcoming : this.past; },
            events_url() {
                return (process.env.MIX_APP_SSH || 'http://') + process.env.MIX_SITE_URL + (this.$i18n.locale === 'ka' ? '/ka' : '') + '/events';
            },
        },
        mounted() { this.load(); },
        methods: {
            load() {
                this.is_loading = true;
                load_interested(true);
                axios.get('/get_faworite/get_interested_events')
                    .then(r => {
                        const ka = this.$i18n.locale === 'ka';
                        this.events = (r.data || []).map(ev => ({
                            global_event: ev,
                            locale_event: (ka ? ev.ka_event : ev.us_event) || ev.us_event || ev.ka_event,
                        }));
                    })
                    .catch(() => this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') }))
                    .finally(() => { this.is_loading = false; });
            },
        },
    }
</script>

<style scoped>
    .ie-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; margin: 1rem 0 1.5rem; }
    .ie-head h3 .fa { color: var(--cg-rope, #e0632a); }
    .ie-head .cg-segmented small { margin-left: .3rem; opacity: .65; }
    .ie-empty { padding: 3rem 1rem; text-align: center; color: var(--cg-stone); }
    .ie-empty .fa { font-size: 2.6rem; color: var(--cg-line); margin-bottom: .75rem; }
</style>
