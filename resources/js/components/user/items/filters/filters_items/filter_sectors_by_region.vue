<template>
   <div class="row cms_filters">
        <div class="col-md-7">
            <h3>Filter sectors By outdoor regions</h3>
        </div>
        <div class="col-md-5">
            <select v-model="value_outdoor_id" @click="return_data(value_outdoor_id)">
                <option :value="0">All</option>
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
                value_outdoor_id: 0
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
                this.$emit('filtred_id', {'filtr_id': filtr_id, 'filtr_category': 'sector_filtr'})
            }
        }
    }
</script>

<style>

</style>