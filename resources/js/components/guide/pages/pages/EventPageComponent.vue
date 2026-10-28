<template>
    <div class="container cg-event-page">
        <span v-if="article_loading">
            <articlePreloader />
        </span>

        <template v-else-if="event.global_event">
            <breadcrumb />

            <!-- hero: photo, type, status, title, interested + share -->
            <header class="cg-ev-hero" :class="'is-' + status.key">
                <div class="cg-ev-hero__img">
                    <site-img v-if="g.image" :src="'/public/images/event_img/' + g.image" :img_class="'img-responsive'" :alt="event.locale_event.title" />
                    <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt="event.locale_event.title" />
                </div>
                <div class="cg-ev-hero__text">
                    <div class="cg-ev-hero__chips">
                        <span class="cg-ev-hero__cat">
                            <i class="fa" :class="g.category === 'competition' ? 'fa-trophy' : 'fa-music'" aria-hidden="true"></i>
                            {{ g.category === 'competition' ? $t('guide.events.competition_flag') : $t('guide.events.event_festival_flag') }}
                        </span>
                        <span class="cg-ev-hero__when"><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ range }}</span>
                    </div>
                    <h1>{{ event.locale_event.title }}</h1>
                    <div class="cg-ev-hero__actions">
                        <interestedButton v-if="status.key !== 'finished'" :event="g" variant="big" />
                        <span v-else class="cg-ev-hero__done"><i class="fa fa-flag-checkered" aria-hidden="true"></i> {{ $t('guide.event_dates.finished') }}</span>
                        <share-button :title="event.locale_event.title" :text="event.locale_event.short_description" content-type="event" :item-id="g.id" />
                    </div>
                </div>
            </header>

            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- when: status + start → finish + add to calendar -->
                    <event-dates :start="g.start_data" :end="g.end_data" :title="event.locale_event.title" :details="event.locale_event.short_description" />

                    <div class="cg-ev-body" v-if="event.locale_event.text" v-html="event.locale_event.text"></div>

                    <section v-if="event.locale_event.info || event.general_info" class="cg-ev-section">
                        <h2 id="how_to_get_there">{{ $t('guide.article.title.info') }}</h2>
                        <span v-if="!event.general_info" v-html="event.locale_event.info"></span>
                        <template v-else>
                            <template v-if="event.general_info.info_block.block_action == 'befor'">
                                <span v-html="event.general_info.info_block.text"></span>
                                <span v-html="event.locale_event.info"></span>
                            </template>
                            <template v-if="event.general_info.info_block.block_action == 'after'">
                                <span v-html="event.locale_event.info"></span>
                                <span v-html="event.general_info.info_block.text"></span>
                            </template>
                            <span v-if="event.general_info.info_block.block_action == 'instead'" v-html="event.general_info.info_block.text"></span>
                        </template>
                    </section>

                    <section v-if="g.map" class="cg-ev-section">
                        <h2 id="map">{{ $t('guide.article.title.locatione') }}</h2>
                        <div class="cg-ev-map" v-html="g.map"></div>
                    </section>
                </div>

                <!-- at a glance (sticky on wide screens) -->
                <aside class="col-lg-4">
                    <div class="cg-ev-glance">
                        <h2>{{ $t('guide.events.at_a_glance') }}</h2>
                        <dl>
                            <dt><i class="fa fa-calendar-o" aria-hidden="true"></i> {{ $t('guide.events.dates') }}</dt>
                            <dd>{{ range }}<template v-if="time"> · {{ time }}</template></dd>
                            <dt><i class="fa fa-tag" aria-hidden="true"></i> {{ $t('guide.events.type') }}</dt>
                            <dd>{{ g.category === 'competition' ? $t('guide.events.competition_flag') : $t('guide.events.event_festival_flag') }}</dd>
                            <dt><i class="fa fa-heart" aria-hidden="true"></i> {{ $t('guide.events.interested_title') }}</dt>
                            <dd>{{ count ? $t('guide.events.interested_count', { n: count }) : $t('guide.events.be_first') }}</dd>
                        </dl>
                        <interestedButton v-if="status.key !== 'finished'" :event="g" variant="big" class="w-100" />
                        <p class="cg-ev-glance__hint" v-if="status.key !== 'finished'">{{ $t('guide.events.reminder_hint') }}</p>
                        <a v-if="g.map" href="#map" class="btn btn-outline-secondary btn-sm w-100 mt-2" @click.prevent="jump('map')"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $t('guide.events.see_map') }}</a>
                    </div>
                </aside>
            </div>

            <section v-if="more.length" class="cg-ev-more">
                <h2 class="cg-section-title cg-section-title--left">{{ $t('guide.events.more_upcoming') }}</h2>
                <div class="row g-4">
                    <div v-for="e in more" :key="e.global_event.id" class="col-md-6 col-lg-4">
                        <eventCard :event="e" />
                    </div>
                </div>
            </section>

            <metaData
                :title="event.locale_event.title"
                :description="event.locale_event.short_description"
                :image="'/public/images/event_img/' + g.image"
            />
        </template>

        <div v-else class="py-5">
            <emptyPageComponent />
        </div>
    </div>
</template>

<script>
    import EventDates from '../../items/EventDatesComponent.vue'
    import articlePreloader from "../../items/article/ArticlePreloaderComponent.vue";
    import metaData from '../../items/MetaDataComponent'
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import interestedButton from '../../items/InterestedButtonComponent.vue'
    import eventCard from '../../items/cards/EventCardComponent.vue'
    import emptyPageComponent from '../../../global_components/EmptyPageComponent'
    import { event_status, range_label, has_time, to_date, fmt, intl_locale } from '../../../../services/guide/eventDates.js'
    import { interested, interested_count, load_interested } from '../../../../services/guide/interested.js'

    export default {
        components: { EventDates, articlePreloader, metaData, breadcrumb, interestedButton, eventCard, emptyPageComponent },
        data() {
            return {
                event: {},
                // gated: the first render must not read event.locale_event before the fetch
                article_loading: true,
                more: [],
                interested,
            };
        },
        computed: {
            g() { return this.event.global_event || {}; },
            status() { return event_status(this.g); },
            range() { return range_label(this.g, intl_locale(this.$i18n)); },
            time() { return has_time(this.g) ? fmt(to_date(this.g.start_data), intl_locale(this.$i18n), { hour: '2-digit', minute: '2-digit', hour12: false }) : ''; },
            count() { return this.interested.delta && interested_count(this.g); },
        },
        mounted() {
            this.get_event();
            load_interested();
        },
        watch: {
            '$route.params.url_title'() {
                this.get_event();
                window.scrollTo(0, 0);
            },
        },
        methods: {
            get_event() {
                this.article_loading = true;
                axios
                    .get('/get_event/get_event_on_site_page/' + localStorage.getItem('lang') + '/' + this.$route.params.url_title)
                    .then(response => {
                        this.event = response.data && response.data.global_event ? response.data : {};
                        this.get_more();
                    })
                    .catch(() => { this.event = {}; })
                    .finally(() => { this.article_loading = false; });
            },
            // other events that haven't finished, soonest first
            get_more() {
                axios.get('/get_event/get_event_on_site_list/' + localStorage.getItem('lang'))
                    .then(r => {
                        this.more = (r.data || [])
                            .filter(e => e.global_event.id !== this.g.id && event_status(e.global_event).key !== 'finished')
                            .slice(0, 3);
                    })
                    .catch(() => { this.more = []; });
            },
            jump(id) {
                const el = document.getElementById(id);
                if (el) window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 90, behavior: window.cgReducedMotion() ? 'auto' : 'smooth' });
            },
        },
    }
</script>

<style scoped>
    .cg-event-page { margin-top: 20px; }
    .cg-ev-hero { position: relative; margin: .5rem 0 1.5rem; min-height: 20rem; display: flex; align-items: flex-end; overflow: hidden; border-radius: var(--cg-r-lg); background: var(--cg-ink); box-shadow: var(--cg-shadow); }
    .cg-ev-hero__img { position: absolute; inset: 0; }
    .cg-ev-hero__img :deep(img) { width: 100%; height: 100%; object-fit: cover; }
    .cg-ev-hero::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(15, 20, 25, .05) 20%, rgba(15, 20, 25, .82) 100%); }
    .cg-ev-hero.is-finished .cg-ev-hero__img :deep(img) { filter: grayscale(.5); }
    .cg-ev-hero__text { position: relative; z-index: 1; width: 100%; padding: 1.5rem 1.75rem; color: #fff; }
    .cg-ev-hero__text h1 { margin: .5rem 0 1rem; color: #fff !important; font-size: clamp(1.8rem, 4vw, 3rem); line-height: 1.1; text-shadow: 0 2px 10px rgba(0, 0, 0, .35); }
    .cg-ev-hero__chips { display: flex; flex-wrap: wrap; gap: .5rem; }
    .cg-ev-hero__cat, .cg-ev-hero__when { display: inline-flex; align-items: center; gap: .4rem; padding: .3rem .75rem; border-radius: 2rem; font-weight: 800; font-size: .85rem; }
    .cg-ev-hero__cat { background: var(--cg-hold); color: var(--cg-ink); }
    .cg-ev-hero__when { background: rgba(255, 255, 255, .18); backdrop-filter: blur(2px); }
    .cg-ev-hero__actions { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem; }
    .cg-ev-hero__actions :deep(.share-icon-btn) { width: 2.9rem; height: 2.9rem; border-radius: 50%; background: rgba(255, 255, 255, .92); color: var(--cg-ink); border: 0; }
    .cg-ev-hero__done { font-weight: 800; padding: .55rem 1rem; border-radius: 2rem; background: rgba(255, 255, 255, .18); }
    .cg-ev-body { font-size: 1.02rem; line-height: 1.65; }
    .cg-ev-section { margin-top: 2rem; }
    .cg-ev-map :deep(iframe) { width: 100%; min-height: 320px; border: 0; border-radius: var(--cg-r-lg); }
    .cg-ev-glance { position: sticky; top: calc(var(--cg-nav-h, 64px) + 1rem); padding: 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-top: 4px solid var(--cg-accent); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-ev-glance h2 { margin: 0 0 .75rem !important; font-size: 1.2rem !important; }
    .cg-ev-glance dl { margin: 0 0 1rem; }
    .cg-ev-glance dt { color: var(--cg-muted); font-size: .78rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .cg-ev-glance dt .fa { color: var(--cg-accent); width: 1rem; }
    .cg-ev-glance dd { margin: .1rem 0 .7rem; color: var(--cg-ink); font-weight: 700; }
    .cg-ev-glance__hint { margin: .5rem 0 0; color: var(--cg-stone); font-size: .82rem; text-align: center; }
    .cg-ev-more { margin: 2rem 0 2rem; }
    @media (max-width: 575.98px) { .cg-ev-hero { min-height: 16rem; } .cg-ev-hero__text { padding: 1.1rem; } }
</style>
