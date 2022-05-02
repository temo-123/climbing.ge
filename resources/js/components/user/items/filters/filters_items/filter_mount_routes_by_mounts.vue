<template>
   <div>
        <h3>Filter Mount routes By Mounts</h3>
        <select v-model="value_mount_id">
            <option v-bind:value="'all'">All</option>
            <option v-for="mount in mounts" :key="mount" v-bind:value="mount.id">{{ mount.name }}</option>
        </select> 
    </div>
</template>

<script>
    export default {
        mounted() {
            this.get_mounts();
        },
        data(){
            return {
                mounts: [],
            }
        },
        methods: {
            get_mounts(){
                axios
                .get("../api/mount/")
                .then(response => {
                    this.mounts = response.data
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