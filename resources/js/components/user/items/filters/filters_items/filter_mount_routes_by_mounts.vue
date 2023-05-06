<template>
   <div class="row cms_filters">
        <div class="col-md-8">
            <h3>Filter Mountaineering routes areas By mount</h3>
            <!-- {{ mounts[0].global_mount.id }} -->
        </div>
        <div class="col-md-4 ">
            <select v-model="value_mount_id" @click="return_data(value_mount_id)">
                <option :value="'all'">All</option>
                <option v-for="mount in mounts" :key="mount" :value="mount.global_mount.id">{{ mount.global_mount.name }}</option>
            </select>
        </div>
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
                value_mount_id: 'all'
            }
        },
        methods: {
            get_mounts(){
                axios
                .get("../api/mount/mount")
                .then(response => {
                    this.mounts = response.data
                })
                .catch(
                    error => console.log(error)
                );
            },
            return_data(filtr_id){
                this.$emit('filtred_id', filtr_id)
            }
        }
    }
</script>

<style>

</style>