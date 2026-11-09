<template>
    <div class="buyer-search">
        <!-- Linked -->
        <div v-if="linked" class="buyer-search__linked">
            <span class="buyer-search__avatar">{{ initials(linked) }}</span>
            <div class="flex-grow-1 min-w-0">
                <div class="fw-semibold">
                    <i class="fa fa-link text-success"></i>
                    {{ $t('admin.orders.buyer_linked_to', { name: linked.name + ' ' + linked.surname }) }}
                    <span class="badge text-bg-light">#{{ linked.id }}</span>
                </div>
                <div class="small text-muted text-break">
                    {{ [linked.email, linked.phone_number].filter(Boolean).join(' · ') }}
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary" @click="$emit('unlink')">
                <i class="fa fa-chain-broken"></i> {{ $t('admin.orders.buyer_unlink') }}
            </button>
        </div>

        <!-- Suggestions -->
        <div v-else-if="results.length" class="buyer-search__box">
            <div class="buyer-search__head">
                <i class="fa fa-users"></i> {{ $t('admin.orders.buyer_matches_title', { count: results.length }) }}
                <span v-if="loading" class="spinner-border spinner-border-sm ms-1"></span>
            </div>
            <div v-for="u in results" :key="u.id" class="buyer-search__row" :class="{ 'buyer-search__row--exact': u.exact }">
                <span class="buyer-search__avatar">{{ initials(u) }}</span>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold">
                        {{ u.name }} {{ u.surname }}
                        <span class="badge text-bg-light">#{{ u.id }}</span>
                        <span v-for="m in u.matched_by.filter(m => m !== 'partial')" :key="m" class="badge text-bg-info ms-1">
                            {{ $t('admin.orders.buyer_match_' + m) }}
                        </span>
                        <span v-if="u.organization" class="badge text-bg-warning ms-1">
                            <i class="fa fa-handshake-o"></i> {{ u.organization.name }} −{{ u.organization.discount }}%
                        </span>
                    </div>
                    <div class="small text-muted text-break">
                        {{ [u.email, u.phone_number, u.city].filter(Boolean).join(' · ') }}
                        <span v-if="u.orders_count"> · {{ $t('admin.orders.buyer_orders_count', { count: u.orders_count }) }}</span>
                    </div>
                </div>
                <button type="button" class="btn btn-sm" :class="u.exact ? 'btn-success' : 'btn-outline-primary'" @click="$emit('link', u)">
                    <i class="fa fa-link"></i> {{ $t('admin.orders.buyer_link_btn') }}
                </button>
            </div>
            <div class="buyer-search__foot">{{ $t('admin.orders.buyer_auto_link_hint') }}</div>
        </div>
    </div>
</template>

<script>
// Existing accounts that match the buyer fields while staff type them
// (GET custom_order/search_buyers). Picking one links it to the order
// (linked_user_id → orders_user); exact e-mail / phone matches are linked
// by the server anyway.
export default {
    emits: ['link', 'unlink'],
    props: {
        name: { type: String, default: '' },
        surname: { type: String, default: '' },
        email: { type: String, default: '' },
        phone: { type: String, default: '' },
        linked: { type: Object, default: null },
    },
    data() {
        return { results: [], loading: false, timer: null, seq: 0 }
    },
    computed: {
        query() {
            return { name: this.name || '', surname: this.surname || '', email: this.email || '', phone: this.phone || '' }
        },
    },
    watch: {
        query: {
            deep: true,
            handler() {
                clearTimeout(this.timer)
                this.timer = setTimeout(() => this.search(), 350)
            },
        },
    },
    beforeUnmount() {
        clearTimeout(this.timer)
    },
    methods: {
        search() {
            if (this.linked) return
            const q = this.query
            const enough = q.name.trim().length >= 2 || q.surname.trim().length >= 2 || q.email.trim().length >= 3 || q.phone.replace(/\D/g, '').length >= 4
            if (!enough) { this.results = []; return }

            const seq = ++this.seq
            this.loading = true
            axios.get('/custom_order/search_buyers', { params: q })
                .then(r => { if (seq === this.seq) this.results = r.data || [] })
                .catch(() => { if (seq === this.seq) this.results = [] })
                .finally(() => { if (seq === this.seq) this.loading = false })
        },
        initials(u) {
            return ((u.name || '').charAt(0) + (u.surname || '').charAt(0)).toUpperCase() || '?'
        },
    },
}
</script>

<style scoped>
.buyer-search__box,
.buyer-search__linked {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    margin-bottom: 1rem;
}
.buyer-search__linked {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .6rem .85rem;
    border-left: 4px solid var(--bs-success);
}
.buyer-search__head {
    padding: .45rem .85rem;
    font-size: .85rem;
    font-weight: 600;
    background: var(--bs-tertiary-bg);
    border-bottom: 1px solid var(--bs-border-color);
}
.buyer-search__row {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .5rem .85rem;
    border-bottom: 1px solid var(--bs-border-color);
}
.buyer-search__row--exact {
    background: var(--bs-success-bg-subtle);
}
.buyer-search__avatar {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--cg-accent);
    color: #fff;
    font-size: .8rem;
    font-weight: 600;
}
.buyer-search__foot {
    padding: .4rem .85rem;
    font-size: .78rem;
    color: var(--bs-secondary-color);
}
.min-w-0 { min-width: 0; }
</style>
