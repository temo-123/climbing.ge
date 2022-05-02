<template>
    <div>
        <h3>Filter Sectors By Region</h3> 
        <select v-model="value_egion_id">
            <option v-bind:value="'all'">All</option>
            <option v-for="egion in regions" :key="egion" v-bind:value="egion.id">{{ egion.url_title }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.get_regions();
        },
        data(){
            return {
                regions: [],
            }
        },
        methods: {
            get_regions(){
                axios
                .post("../api/article/", {
                    category: 'outdoor',
                })
                .then(response => {
                    this.regions = response.data
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