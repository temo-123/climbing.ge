<template>
    <!-- Gear rental prices on a shop service page (services.link_type = 'gear_rental').
         Data: `linked` of get_service/get_local_service_in_page (GearRentalService::public_price_list). -->
    <section v-if="list && list.gear && list.gear.length" class="rental-prices">
        <h2 class="section-title">{{ $t('shop.rental.prices_title') }}</h2>
        <p class="rental-prices__lead">{{ $t('shop.rental.prices_lead') }}</p>

        <div class="rental-prices__wrap">
            <table class="table rental-prices__table">
                <thead>
                    <tr>
                        <th scope="col">{{ $t('shop.rental.gear_col') }}</th>
                        <th v-for="d in list.periods" :key="d" scope="col" class="text-end">{{ period_label(d) }}</th>
                        <th v-if="has_deposit" scope="col" class="text-end">{{ $t('shop.rental.deposit_col') }}</th>
                        <th scope="col" class="text-end">{{ $t('shop.rental.available_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="g in list.gear" :key="g.id">
                        <th scope="row">
                            {{ g.name }}
                            <small v-if="g.category" class="rental-prices__cat">{{ g.category }}</small>
                        </th>
                        <td v-for="d in list.periods" :key="d" class="text-end" :data-label="period_label(d)">
                            <span v-if="g.prices[d] !== undefined">{{ money(g.prices[d]) }}</span>
                            <span v-else class="text-muted">—</span>
                        </td>
                        <td v-if="has_deposit" class="text-end" :data-label="$t('shop.rental.deposit_col')">{{ g.deposit ? money(g.deposit) : '—' }}</td>
                        <td class="text-end" :data-label="$t('shop.rental.available_col')">
                            <span v-if="g.available > 0" class="rental-prices__ok">{{ g.available }}</span>
                            <span v-else class="rental-prices__out">{{ $t('shop.rental.all_rented') }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="rental-prices__note">{{ $t('shop.rental.prices_note') }}</p>
    </section>
</template>

<script>
export default {
    name: 'RentalPriceList',
    props: {
        list: { type: Object, default: null },
    },
    computed: {
        has_deposit() {
            return (this.list.gear || []).some(g => g.deposit)
        },
    },
    methods: {
        period_label(days) {
            return this.$t('shop.rental.days', { n: days }, days)
        },
        money(v) {
            const n = Number(v)
            return (Number.isInteger(n) ? n : n.toFixed(2)) + ' ₾'
        },
    },
}
</script>

<style scoped>
.rental-prices { margin: 2rem 0; }
.rental-prices__lead { color: var(--cg-stone); margin-bottom: 1rem; }
.rental-prices__wrap {
    overflow-x: auto;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r-lg);
    background: #fff;
}
.rental-prices__table { margin: 0; min-width: 480px; }
.rental-prices__table thead th {
    background: var(--cg-chalk);
    font-size: .85rem;
    white-space: nowrap;
}
.rental-prices__table th[scope="row"] { font-weight: 700; }
.rental-prices__cat { display: block; color: var(--cg-muted); font-weight: 500; font-size: .8rem; }
.rental-prices__table td { white-space: nowrap; font-variant-numeric: tabular-nums; }
.rental-prices__ok { font-weight: 700; color: #2fa36b; }
.rental-prices__out { font-size: .82rem; font-weight: 700; color: #b5860a; }
.rental-prices__note { margin-top: .6rem; font-size: .85rem; color: var(--cg-muted); }
</style>
