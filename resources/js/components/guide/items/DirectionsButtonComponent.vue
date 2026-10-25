<template>
    <!-- "Directions" to an area's parking: articles.parking holds "lat,lng"
         or a Google Maps link (set in the admin global article form). -->
    <a v-if="url" :href="url" target="_blank" rel="noopener" class="btn btn-sm btn-hold cg-directions d-print-none">
        <i class="fa fa-car" aria-hidden="true"></i> {{ $t('guide.directions.button') }}
    </a>
</template>

<script>
    export default {
        name: 'DirectionsButton',
        props: {
            parking: { type: String, default: null },
        },
        computed: {
            url() {
                const v = String(this.parking || '').trim();
                if (!v) return null;
                if (/^https?:\/\//i.test(v)) return v;
                const m = v.match(/^\s*(-?\d{1,2}(?:\.\d+)?)\s*[,; ]\s*(-?\d{1,3}(?:\.\d+)?)\s*$/);
                return m ? 'https://www.google.com/maps/dir/?api=1&destination=' + m[1] + ',' + m[2] : null;
            },
        },
    };
</script>

<style scoped>
    .cg-directions { display: inline-flex; align-items: center; gap: .4rem; margin: .25rem 0 .75rem; }
</style>
