<template>
    <div>
        <h3>Filter Pitchs By MTP</h3> 
        <select v-model="value_MTP_id">
            <option v-bind:value="'all'">All</option>
            <option v-for="MTP in MTPs" :key="MTP" v-bind:value="MTP.id">{{ MTP.name }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.get_MTPs();
        },
        data(){
            return {
                MTPs: [],
            }
        },
        methods: {
            get_MTPs(){
                axios
                .get("../api/MTP/")
                .then(response => {
                    this.MTPs = response.data
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