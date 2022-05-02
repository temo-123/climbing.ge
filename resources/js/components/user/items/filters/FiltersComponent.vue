<template>
    <div class="col-md-12">
        <filterSectorsByRegion v-if="this.table_name == 'Sectors'" />
        <filterRoutesBySector v-if="this.table_name == 'Routes'" />
        <filterMTPBySector v-if="this.table_name == 'Multi-pitchs'" />
        <filterMTPPitchsByMTP v-if="this.table_name == 'Pitches'" />
        <filterProductsByCategory v-if="this.table_name == 'Products'" />
        <filterMountsRoutesByMount v-if="this.table_name == 'Mountaineering routes'" />
    </div>
</template>

<script>
    import filterSectorsByRegion from './filters_items/filter_sectors_by_region.vue'
    import filterRoutesBySector from './filters_items/filter_routes_by_sector.vue'
    import filterMTPBySector from './filters_items/filter_MTP_by_sector.vue'
    import filterMTPPitchsByMTP from './filters_items/filter_MTPPitchs_by_MTP.vue'
    import filterProductsByCategory from './filters_items/filter_products_by_category.vue'
    import filterMountsRoutesByMount from './filters_items/filter_mount_routes_by_mounts.vue'
    export default {
        props:[
            'table_name',
        ],
        components:{
            filterSectorsByRegion,
            filterRoutesBySector,
            filterMTPBySector,
            filterMTPPitchsByMTP,
            filterProductsByCategory,
            filterMountsRoutesByMount,
        }
    }
</script>

<style>

</style>