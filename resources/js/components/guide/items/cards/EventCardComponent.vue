<template>
    <!-- Event card (events list + homepage): photo with a tear-off date tag,
         category, status (starts in N days / today / happening now /
         finished), date range, short text, interested heart + count. Replaces
         the old timeline card and IndexEventCardComponent. -->
    <article class="cg-ev" :class="'is-' + status.key">
        <div class="cg-ev__media">
            <component :is="tag" v-bind="link_attrs" class="cg-ev__img" :aria-label="title">
                <site-img v-if="g.image" :src="'/public/images/event_img/' + g.image" :img_class="'img-responsive'" :alt="title" />
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt="title" />
            </component>
            <span class="cg-ev__cat" :class="'is-' + g.category">
                <i class="fa" :class="g.category === 'competition' ? 'fa-trophy' : 'fa-music'" aria-hidden="true"></i>
                {{ g.category === 'competition' ? $t('guide.events.competition_flag') : $t('guide.events.event_festival_flag') }}
            </span>
            <interestedButton v-if="status.key !== 'finished'" :event="g" />
            <span v-if="start" class="cg-ev__tag" aria-hidden="true">
                <strong>{{ start.getDate() }}</strong>
                <small>{{ month }}</small>
            </span>
        </div>

        <div class="cg-ev__body">
            <span class="cg-ev__status" :class="'is-' + status.key">
                <i class="fa" :class="status_icon" aria-hidden="true"></i> {{ status_text }}
            </span>
            <h3 class="cg-ev__title"><component :is="tag" v-bind="link_attrs">{{ title }}</component></h3>
            <p class="cg-ev__when">
                <i class="fa fa-calendar-o" aria-hidden="true"></i> {{ range }}
                <template v-if="time"> · <i class="fa fa-clock-o" aria-hidden="true"></i> {{ time }}</template>
            </p>
            <p v-if="excerpt" class="cg-ev__text">{{ excerpt }}</p>
            <div class="cg-ev__foot">
                <span v-if="count" class="cg-ev__count"><i class="fa fa-heart" aria-hidden="true"></i> {{ $t('guide.events.interested_count', { n: count }) }}</span>
                <component :is="tag" v-bind="link_attrs" class="cg-ev__more">{{ $t('guide.events.details') }} <i class="fa fa-angle-right" aria-hidden="true"></i></component>
            </div>
        </div>
    </article>
</template>

<script>
    import interestedButton from '../InterestedButtonComponent.vue'
    import { interested, interested_count } from '../../../../services/guide/interested.js'
    import { to_date, event_status, has_time, range_label, fmt, intl_locale } from '../../../../services/guide/eventDates.js'

    export default {
        name: 'EventCard',
        components: { interestedButton },
        props: {
            event: { type: Object, required: true },   // { global_event, locale_event }
            // outside the guide (user panel): full guide URL in a new tab instead of a router link
            external: { type: Boolean, default: false },
        },
        data() {
            return { interested };
        },
        computed: {
            g() { return this.event.global_event; },
            title() { return (this.event.locale_event && this.event.locale_event.title) || this.g.url_title; },
            locale() { return intl_locale(this.$i18n); },
            link() {
                const loc = this.$route.params.locale;
                return (loc ? '/' + loc : '') + '/event/' + this.g.url_title;
            },
            tag() { return this.external ? 'a' : 'router-link'; },
            link_attrs() {
                if (!this.external) return { to: this.link };
                const prefix = this.$i18n.locale === 'ka' ? '/ka' : '';
                return { href: (process.env.MIX_APP_SSH || 'http://') + process.env.MIX_SITE_URL + prefix + '/event/' + this.g.url_title, target: '_blank', rel: 'noopener' };
            },
            start() { return to_date(this.g.start_data); },
            month() { return fmt(this.start, this.locale, { month: 'short' }); },
            status() { return event_status(this.g); },
            status_icon() {
                return { upcoming: 'fa-hourglass-half', today: 'fa-bell', live: 'fa-circle', finished: 'fa-flag-checkered' }[this.status.key];
            },
            status_text() {
                const s = this.status;
                if (s.key === 'upcoming') return s.days === 1 ? this.$t('guide.event_dates.tomorrow') : this.$t('guide.event_dates.starts_in', { n: s.days });
                if (s.key === 'today') return this.$t('guide.event_dates.starts_today');
                if (s.key === 'live') return this.$t('guide.event_dates.happening_now');
                return this.$t('guide.event_dates.finished');
            },
            range() { return range_label(this.g, this.locale); },
            time() { return has_time(this.g) ? fmt(this.start, this.locale, { hour: '2-digit', minute: '2-digit', hour12: false }) : ''; },
            excerpt() {
                const html = (this.event.locale_event && this.event.locale_event.short_description) || '';
                const text = html.replace(/<[^>]+>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
                if (text.replace(/[\s.·-]/g, '').length < 3) return '';           // placeholder text like "."
                return text.length > 140 ? text.slice(0, 137).trim() + '…' : text;
            },
            count() { return this.interested.delta && interested_count(this.g); },
        },
    };
</script>

<style scoped>
    .cg-ev { display: flex; flex-direction: column; height: 100%; overflow: hidden; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); transition: transform .2s var(--cg-ease, ease), box-shadow .2s; }
    .cg-ev:hover { transform: translateY(-3px); box-shadow: var(--cg-shadow, 0 8px 22px rgba(0,0,0,.12)); }
    .cg-ev.is-finished { opacity: .82; }
    .cg-ev.is-finished .cg-ev__img :deep(img) { filter: grayscale(.6); }

    .cg-ev__media { position: relative; }
    .cg-ev__img { display: block; aspect-ratio: 16 / 9; overflow: hidden; background: var(--cg-chalk-2); }
    .cg-ev__img :deep(img) { width: 100%; height: 100%; object-fit: cover; transition: transform .35s var(--cg-ease, ease); }
    .cg-ev:hover .cg-ev__img :deep(img) { transform: scale(1.04); }
    .cg-ev__cat { position: absolute; top: .65rem; left: .65rem; z-index: 2; display: inline-flex; align-items: center; gap: .35rem; padding: .25rem .65rem; border-radius: 2rem; background: rgba(21, 33, 43, .82); color: #fff; font-size: .75rem; font-weight: 800; letter-spacing: .03em; }
    .cg-ev__cat.is-competition .fa { color: var(--cg-hold); }
    .cg-ev__cat.is-event .fa { color: #7fd6c3; }
    /* tear-off calendar tag hanging over the photo's bottom edge */
    .cg-ev__tag { position: absolute; left: 1rem; bottom: -1.4rem; z-index: 2; display: flex; flex-direction: column; align-items: center; min-width: 3.6rem; padding: .35rem .5rem .3rem; border-radius: .6rem; background: var(--cg-hold); color: var(--cg-ink); box-shadow: 0 4px 10px rgba(0, 0, 0, .18); line-height: 1; }
    .cg-ev__tag strong { font-family: var(--cg-font-display); font-size: 1.9rem; }
    .cg-ev__tag small { margin-top: .1rem; font-weight: 800; font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; }
    .is-finished .cg-ev__tag { background: var(--cg-chalk-2); }

    .cg-ev__body { display: flex; flex-direction: column; gap: .35rem; flex: 1; padding: 1rem 1.1rem 1rem; }
    .cg-ev__status { align-self: flex-end; display: inline-flex; align-items: center; gap: .35rem; padding: .2rem .65rem; border-radius: 2rem; background: var(--cg-accent-soft); color: var(--cg-accent-dark); font-size: .75rem; font-weight: 800; }
    .cg-ev__status.is-today { background: #fff4d6; color: #8a5a00; }
    .cg-ev__status.is-live { background: #e3f6ec; color: #13865d; }
    .cg-ev__status.is-live .fa { font-size: .55rem; animation: cg-ev-live 1.4s ease-in-out infinite; }
    .cg-ev__status.is-finished { background: var(--cg-chalk-2); color: var(--cg-stone); }
    @keyframes cg-ev-live { 50% { opacity: .3; } }
    .cg-ev__title { margin: .35rem 0 0 !important; font-size: 1.3rem !important; line-height: 1.2; }
    .cg-ev__title a { color: var(--cg-ink); text-decoration: none; }
    .cg-ev__title a:hover { color: var(--cg-accent-dark); }
    .cg-ev__when { margin: 0; color: var(--cg-stone); font-weight: 700; font-size: .88rem; }
    .cg-ev__when .fa { color: var(--cg-accent); }
    .cg-ev__text { margin: 0; color: var(--cg-ink-2); font-size: .92rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .cg-ev__foot { display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-top: auto; padding-top: .55rem; border-top: 1px dashed var(--cg-line); }
    .cg-ev__count { color: var(--cg-rope, #e0632a); font-weight: 800; font-size: .85rem; }
    .cg-ev__more { margin-left: auto; font-weight: 800; font-size: .88rem; }
    @media (prefers-reduced-motion: reduce) { .cg-ev, .cg-ev__img :deep(img) { transition: none; } .cg-ev:hover { transform: none; } .cg-ev__status.is-live .fa { animation: none; } }
</style>
