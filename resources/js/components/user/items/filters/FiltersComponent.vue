<template>
    <div class="col-md-12">
        <filterSectorsByRegion      v-if="this.table_name == 'Sectors'"                 @filtred_id="filtred"/>
        <filterRoutesBySector       v-if="this.table_name == 'Routes'"                  @filtred_id="filtred"/>
        <filterMTPBySector          v-if="this.table_name == 'Multi-pitchs'"            @filtred_id="filtred"/>
        <filterMTPPitchsByMTP       v-if="this.table_name == 'Pitches'"                 @filtred_id="filtred"/>
        <filterProductsByCategory   v-if="this.table_name == 'Products'"                @filtred_id="filtred"/>
        <filterMountsRoutesByMount  v-if="this.table_name == 'Mountaineering routes'"   @filtred_id="filtred"/>
        <filterOutdoorByRegions     v-if="this.table_name == 'outdoor'"                 @filtred_id="filtred"/>
    </div>
</template>

<script>
    import filterSectorsByRegion from './filters_items/filter_sectors_by_region.vue'
    import filterRoutesBySector from './filters_items/filter_routes_by_sector.vue'
    import filterMTPBySector from './filters_items/filter_MTP_by_sector.vue'
    import filterMTPPitchsByMTP from './filters_items/filter_MTPPitchs_by_MTP.vue'
    import filterProductsByCategory from './filters_items/filter_products_by_category.vue'
    import filterMountsRoutesByMount from './filters_items/filter_mount_routes_by_mounts.vue'
    import filterOutdoorByRegions from './filters_items/filter_outdoor_by_regions.vue'
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
            filterOutdoorByRegions
        },

        methods: {
            filtred(event){
                this.$emit('filtred', event)
            }
        }
    }
</script>

<style>

</style>