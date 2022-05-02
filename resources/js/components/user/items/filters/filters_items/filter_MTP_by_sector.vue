<template>
    <div>
        <h3>Filter MTP By Sector</h3> 
        <select v-model="value_sector_id">
            <option v-bind:value="'all'">All</option>
            <option v-for="sector in sectors" :key="sector" v-bind:value="sector.id">{{ sector.name }}</option>
        </select>
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
        }
    }
</script>

<style>

</style>