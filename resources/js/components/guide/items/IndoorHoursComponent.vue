<template>
    <!-- Indoor gym page: opening hours, an article section between "How to get
         there" and the price (ArticleTextBlocksComponent). Same hours every day →
         one line; hours per weekday (article_working_hours, Global info → Working
         time "Different hours on different days") → the whole week, Monday
         first, today highlighted, closed days marked. A live badge says
         open / closes in N min / closed. Hidden while a work status (closed,
         coming soon…) replaces the hours — IndoorWorkStatusAlert shows that.
         Rules: services/guide/gymHours.js. -->
    <div v-if="show">
        <h2 id="working_hours">{{ $t('guide.gym_hours.title') }}</h2>
        <section class="cg-hours" :aria-label="$t('guide.gym_hours.title')">
            <span v-if="has_any" class="cg-hours__badge" :class="'is-' + state.state">{{ state.label }}</span>

            <p v-if="!has_any" class="cg-hours__none">{{ $t('guide.article.no_info_working_time') }}</p>

            <p v-else-if="!per_day" class="cg-hours__daily">
                <strong>{{ $t('guide.gym_hours.every_day') }}</strong>
                <span>{{ daily.open }} – {{ daily.close }}</span>
            </p>

            <ol v-else class="cg-hours__week">
                <li v-for="(day, i) in days" :key="day" :class="{ 'is-today': day === today_key, 'is-closed': !rows[i] }">
                    <span class="cg-hours__day">{{ names[i] }}<small v-if="day === today_key"> · {{ $t('guide.gym_hours.today') }}</small></span>
                    <span class="cg-hours__time">
                        <template v-if="rows[i]">{{ rows[i].open }} – {{ rows[i].close }}</template>
                        <template v-else>{{ $t('guide.gym_hours.closed') }}</template>
                    </span>
                </li>
            </ol>
        </section>
    </div>
</template>

<script>
    import { WEEK_DAYS, weekly, hours_for_day, has_hours, day_key, open_state, day_names } from '../../../services/guide/gymHours.js'

    export default {
        name: 'IndoorHours',
        props: {
            gym: { type: Object, required: true },   // the article's global_data
        },
        data() {
            return { now: new Date(), timer: null, days: WEEK_DAYS };
        },
        computed: {
            // a status (temporarily closed, coming soon…) replaces the hours
            show() { return !['temporarily_closed', 'sport_session_closed', 'coming_soon'].includes(this.gym.work_status); },
            has_any() { return this.gym.work_status !== 'no_info' && has_hours(this.gym); },
            per_day() { return !!weekly(this.gym); },
            daily() { return hours_for_day(this.gym, 'mon'); },
            rows() { return WEEK_DAYS.map(d => hours_for_day(this.gym, d)); },
            names() { return day_names(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'); },
            today_key() { return day_key(this.now); },
            state() {
                const st = open_state(this.gym, this.now);
                return { state: st.state, label: this.$t(st.key, { min: st.min }) };
            },
        },
        mounted() {
            // keep the badge current while the page stays open
            this.timer = setInterval(() => { this.now = new Date(); }, 60000);
        },
        beforeUnmount() { clearInterval(this.timer); },
    };
</script>

<style scoped>
    .cg-hours { display: inline-block; min-width: min(100%, 22rem); margin: .25rem 0 1.5rem; padding: 1rem 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-left: 5px solid var(--cg-accent); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .cg-hours__badge { display: inline-block; margin-bottom: .6rem; padding: .2rem .7rem; border-radius: 2rem; color: #fff; font-size: .82rem; font-weight: 800; }
    .cg-hours__badge.is-open { background: #2fa36b; }
    .cg-hours__badge.is-soon { background: #f39c12; }
    .cg-hours__badge.is-closed { background: #7b858e; }
    .cg-hours__none { margin: 0; color: var(--cg-stone); }
    .cg-hours__daily { display: flex; gap: .75rem; margin: 0; font-size: 1.05rem; }
    .cg-hours__daily span { font-weight: 800; color: var(--cg-ink); }
    .cg-hours__week { display: grid; gap: .15rem; margin: 0; padding: 0; list-style: none; max-width: 26rem; }
    .cg-hours__week li { display: flex; justify-content: space-between; gap: 1rem; padding: .35rem .6rem; border-radius: var(--cg-r-sm, 6px); }
    .cg-hours__week li.is-today { background: var(--cg-accent-soft, rgba(var(--cg-accent-rgb), .1)); font-weight: 800; }
    .cg-hours__week li.is-closed .cg-hours__time { color: var(--cg-muted); }
    .cg-hours__day { text-transform: capitalize; }
    .cg-hours__day small { color: var(--cg-accent-dark); font-weight: 800; text-transform: none; }
    .cg-hours__time { font-variant-numeric: tabular-nums; font-weight: 700; color: var(--cg-ink); white-space: nowrap; }
</style>
