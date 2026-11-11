<template>
    <!-- ID card photo from the private disk: loaded with the panel's Bearer token
         (set_gear_rental/id_card/{id}) into an object URL, never a public link. -->
    <div class="idc-view">
        <div v-if="loading" class="idc-view__box text-muted"><span class="spinner-border spinner-border-sm" aria-hidden="true"></span></div>
        <div v-else-if="failed" class="idc-view__box text-muted small">{{ $t('admin.gear_rental.id_card_load_failed') }}</div>
        <a v-else-if="src" :href="src" target="_blank" rel="noopener" class="idc-view__link" :title="$t('admin.gear_rental.id_card_open')">
            <img :src="src" :alt="$t('admin.gear_rental.id_card')" class="idc-view__img">
        </a>
    </div>
</template>

<script>
export default {
    name: 'IdCardImage',
    props: {
        rental_id: { type: [Number, String], required: true },
        // bump to reload after a new photo was uploaded
        version: { type: Number, default: 0 },
    },
    data() {
        return { src: null, loading: false, failed: false }
    },
    watch: {
        rental_id() { this.load() },
        version() { this.load() },
    },
    mounted() { this.load() },
    beforeUnmount() { this.revoke() },
    methods: {
        revoke() {
            if (this.src) URL.revokeObjectURL(this.src)
            this.src = null
        },
        load() {
            this.revoke()
            this.loading = true
            this.failed = false
            axios.get('/set_gear_rental/id_card/' + this.rental_id, { responseType: 'blob', _silent: true })
                .then(r => { this.src = URL.createObjectURL(r.data) })
                .catch(() => { this.failed = true })
                .finally(() => { this.loading = false })
        },
    },
}
</script>

<style scoped>
.idc-view__box {
    display: grid;
    place-items: center;
    min-height: 120px;
    border: 1px dashed var(--cg-line);
    border-radius: var(--cg-r);
}
.idc-view__link { display: block; }
.idc-view__img {
    display: block;
    width: 100%;
    max-height: 320px;
    object-fit: contain;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
    background: var(--cg-chalk);
}
</style>
