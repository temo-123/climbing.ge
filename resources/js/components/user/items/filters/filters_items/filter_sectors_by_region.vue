<template>
   <div class="row cms_filters">
        <div class="col-md-8">
            <h3>Filter sectors By outdoor regions</h3>
        </div>
        <div class="col-md-4 ">
            <select v-model="value_outdoor_id" @click="return_data(value_outdoor_id)">
                <option :value="'all'">All</option>
                <option v-for="outdoor in outdoor_areas" :key="outdoor" :value="outdoor.id">{{ outdoor.url_title }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.get_outdoor_areas();
        },
        data(){
            return {
                outdoor_areas: [],
                value_outdoor_id: 'all'
            }
        },
        methods: {
            get_outdoor_areas(){
                axios
                .post("../api/article/", {
                    category: 'outdoor',
                })
                .then(response => {
                    this.outdoor_areas = response.data
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