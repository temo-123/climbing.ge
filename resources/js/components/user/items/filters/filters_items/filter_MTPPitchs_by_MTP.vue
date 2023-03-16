<template>
   <div class="row cms_filters">
        <div class="col-md-8">
            <h3>Filter multy-pitch pitch By multy-pitch</h3>
        </div>
        <div class="col-md-4 ">
            <select v-model="value_mtp_id" @click="return_data(value_mtp_id)">
                <option :value="'all'">All</option>
                <option v-for="mtp in MTPs" :key="mtp" :value="mtp.id">{{ mtp.name }}</option>
            </select>
        </div>
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
                value_mtp_id: 'all'
            }
        },
        methods: {
            get_MTPs(){
                axios
                .get("../api/mtp/")
                .then(response => {
                    this.MTPs = response.data
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