<template>
    <!-- services.link_type / link_id: what the service page shows from another system.
         Now: gear rental prices of a rental warehouse, or the climbing wall calculator.
         Edits the given global_service object. -->
    <div class="service-link border rounded p-3 my-3">
        <label class="form-label fw-semibold" for="service_link_type">{{ $t('admin.services.link_label') }}</label>
        <select id="service_link_type" class="form-select" v-model="model.link_type">
            <option :value="null">{{ $t('admin.services.link_none') }}</option>
            <option v-for="t in types" :key="t.key" :value="t.key">{{ $t('admin.services.link_type_' + t.key) }}</option>
        </select>
        <small class="form-text text-muted">{{ $t('admin.services.link_hint') }}</small>

        <div v-if="model.link_type === 'gear_rental'" class="mt-3">
            <label class="form-label" for="service_link_id">{{ $t('admin.services.link_warehouse_label') }}</label>
            <select id="service_link_id" class="form-select" v-model="model.link_id">
                <option :value="null">{{ $t('admin.services.link_general_warehouse') }}</option>
                <option v-for="w in targets('gear_rental')" :key="w.id" :value="w.id">
                    {{ w.name }}<template v-if="w.general"> ({{ $t('admin.gear_rental.general') }})</template>
                </option>
            </select>
            <small class="form-text text-muted">{{ $t('admin.services.link_gear_rental_hint') }}</small>
            <div v-if="loaded && !targets('gear_rental').length" class="alert alert-warning py-2 mt-2 mb-0 small">
                {{ $t('admin.services.link_no_warehouses') }}
            </div>
        </div>

        <div v-if="model.link_type === 'wall_calculator'" class="alert alert-info py-2 mt-3 mb-0 small">
            <i class="fa fa-calculator" aria-hidden="true"></i> {{ $t('admin.services.link_wall_calculator_hint') }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'ServiceLinkFields',
    props: {
        // the form's global_service object — edited in place (like the other global fields)
        model: { type: Object, required: true },
    },
    data() {
        return { types: [], loaded: false }
    },
    mounted() {
        if (this.model.link_type === undefined) this.model.link_type = null
        if (this.model.link_id === undefined) this.model.link_id = null
        axios.get('/set_service/get_link_targets')
            .then(r => { this.types = r.data.types || [] })
            .catch(() => {})
            .finally(() => { this.loaded = true })
    },
    watch: {
        'model.link_type'(type) {
            if (type !== 'gear_rental') this.model.link_id = null   // only gear rental has a target
        },
    },
    methods: {
        targets(key) {
            return (this.types.find(t => t.key === key) || {}).targets || []
        },
    },
}
</script>
