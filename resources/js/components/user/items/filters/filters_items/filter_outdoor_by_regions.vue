<template>
   <div class="row cms_filters">
        <div class="col-md-8">
            <h3>Filter Rutdoor areas By Region</h3>
        </div>
        <div class="col-md-4 ">
            <select v-model="value_region_id" @click="return_data(value_region_id)">
                <option :value="'all'">All</option>
                <option v-for="region in regions" :key="region.id" :value="region.id">{{ region.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.get_regions();
            this.$emit('filtred_id', value_region_id)
        },
        data(){
            return {
                value_region_id: 'all',
                regions: [],
            }
        },
        methods: {
            get_regions(){
                axios
                .get('../api/regions/en')
                .then(response => {
                    this.regions = response.data
                })
                .catch(error =>{
                })
                .finally(() => this.region_loading = false)
            },
            return_data(filtr_id){
                this.$emit('filtred_id', filtr_id)
            }
        }
    }
</script>

<style>
    /* .cms_filters{
        background-color: #c1c1c1;
    } */
</style>