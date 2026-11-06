<template>
    <div class="cg-dt-quick-filters" v-if="filters.length">
        <div
            class="cg-dt-quick-filter"
            v-for="filter in filters"
            :key="filter.key"
            :class="{ 'is-active': modelValue[filter.key] }"
        >
            <span class="cg-dt-quick-filter__title">{{ filter.title }}</span>
            <div class="btn-group btn-group-sm" role="group" :aria-label="filter.title">
                <button
                    type="button"
                    class="btn"
                    :class="!modelValue[filter.key] ? 'btn-dark' : 'btn-outline-secondary'"
                    @click="set_value(filter.key, null)"
                >
                    {{ $t('admin.table.filter_all') }}
                </button>
                <button
                    type="button"
                    class="btn"
                    :class="modelValue[filter.key] === 'yes' ? 'btn-success' : 'btn-outline-secondary'"
                    @click="set_value(filter.key, 'yes')"
                >
                    <i class="fa fa-check" aria-hidden="true"></i>
                    {{ filter.yes }}
                </button>
                <button
                    type="button"
                    class="btn"
                    :class="modelValue[filter.key] === 'no' ? 'btn-danger' : 'btn-outline-secondary'"
                    @click="set_value(filter.key, 'no')"
                >
                    <i class="fa fa-times" aria-hidden="true"></i>
                    {{ filter.no }}
                </button>
            </div>
        </div>

        <button
            v-if="has_active"
            type="button"
            class="btn btn-link btn-sm cg-dt-quick-filters__reset"
            @click="$emit('update:modelValue', {})"
        >
            {{ $t('admin.table.filter_reset') }}
        </button>
    </div>
</template>

<script>
// Simple Yes / No / All filters. `filters` is built by TabsComponent from the
// tab's `bool` columns: [{ key, title, yes, no }] where yes/no are row counts.
export default {
    props: {
        filters: {
            type: Array,
            default: () => []
        },
        modelValue: {
            type: Object,
            default: () => ({})
        }
    },
    emits: ['update:modelValue'],
    computed: {
        has_active() {
            return Object.values(this.modelValue).some(Boolean);
        }
    },
    methods: {
        set_value(key, value) {
            const next = { ...this.modelValue };
            if (value) next[key] = value;
            else delete next[key];
            this.$emit('update:modelValue', next);
        }
    }
}
</script>

<style>
.cg-dt-quick-filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .5rem .75rem;
    margin-bottom: 1rem;
}
.cg-dt-quick-filter {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .3rem .4rem .3rem .85rem;
    background: #fff;
    border: 1px solid var(--cg-line);
    border-radius: 2rem;
}
.cg-dt-quick-filter.is-active {
    border-color: var(--cg-accent);
    box-shadow: 0 0 0 1px var(--cg-accent);
}
.cg-dt-quick-filter__title {
    font-size: .82rem;
    font-weight: 700;
    color: var(--cg-stone);
    white-space: nowrap;
}
.cg-dt-quick-filter .btn-group .btn {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .2rem .65rem;
    font-size: .8rem;
}
.cg-dt-quick-filter .btn-group .btn:first-child {
    border-top-left-radius: 2rem;
    border-bottom-left-radius: 2rem;
}
.cg-dt-quick-filter .btn-group .btn:last-child {
    border-top-right-radius: 2rem;
    border-bottom-right-radius: 2rem;
}
.cg-dt-quick-filters__reset {
    font-size: .82rem;
    text-decoration: none;
}
</style>
