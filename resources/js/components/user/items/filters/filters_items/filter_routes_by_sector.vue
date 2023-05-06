<template>
   <div class="row cms_filters">
        <div class="col-md-8">
            <h3>Filter routes By sectors</h3>
        </div>
        <div class="col-md-4 ">
            <select v-model="value_sector_id" @click="return_data(value_sector_id)">
                <option :value="'all'">All</option>
                <option v-for="sector in sectors" :key="sector" :value="sector.id">{{ sector.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
export default {
        mounted() {
            this.get_sectors();
        },
        data(){
            return {
                sectors: [],
                value_sector_id: 'all'
            }
        },
        methods: {
            get_sectors(){
                axios
                .get("../api/sector/")
                .then(response => {
                    this.sectors = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            return_data(filtr_id){
                // this.$emit('filtred_id', filtr_id)
                this.$emit('filtred_id', {'filtr_id': filtr_id, 'filtr_category': 'route_filtr'})
            }
        }
    }
</script>

<style>

</style>