<template>
    <!-- Shop dashboard: tour reservations at a glance + link to the reservations dashboard -->
    <router-link v-if="stats" :to="{ name: 'allToursReservations' }" class="cg-trs">
        <span class="cg-trs__icon" aria-hidden="true"><i class="fa fa-compass"></i></span>
        <span class="cg-trs__title">{{ $t('admin.tour_dashboard.summary_title') }}</span>
        <span class="cg-trs__fig"><strong>{{ stats.totals.upcoming }}</strong> {{ $t('admin.tour_dashboard.upcoming') }}</span>
        <span class="cg-trs__fig is-warn"><strong>{{ stats.totals.waiting }}</strong> {{ $t('admin.tour_dashboard.waiting') }}</span>
        <span class="cg-trs__fig"><strong>{{ stats.totals.new_7_days }}</strong> {{ $t('admin.tour_dashboard.new_7') }}</span>
        <span class="cg-trs__go">{{ $t('admin.tour_dashboard.open') }} <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
    </router-link>
</template>

<script>
    export default {
        name: 'TourReservationsSummaryComponent',
        data() { return { stats: null }; },
        mounted() {
            axios.get('/set_tour/get_reservation/get_reservation_stats')
                .then(r => { this.stats = r.data; })
                .catch(() => {});
        },
    };
</script>

<style scoped>
.cg-trs {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .5rem 1.25rem;
    margin-bottom: 1rem;
    padding: .85rem 1.1rem;
    border: 1px solid var(--cg-line);
    border-left: 4px solid var(--cg-accent);
    border-radius: var(--cg-r);
    background: var(--cg-surface, #fff);
    color: var(--cg-ink-2);
    text-decoration: none;
}
.cg-trs:hover { border-color: var(--cg-accent); }
.cg-trs__icon { display: grid; place-items: center; width: 2.2rem; height: 2.2rem; border-radius: 50%; background: var(--cg-accent-soft); color: var(--cg-accent-dark); }
.cg-trs__title { font-weight: 800; color: var(--cg-ink); }
.cg-trs__fig { font-size: .9rem; }
.cg-trs__fig strong { font-size: 1.15rem; color: var(--cg-ink); }
.cg-trs__fig.is-warn strong { color: #b07a00; }
.cg-trs__go { margin-left: auto; font-weight: 700; color: var(--cg-accent-dark); }
</style>
