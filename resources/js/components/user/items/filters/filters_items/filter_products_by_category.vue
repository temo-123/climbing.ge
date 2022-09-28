<template>
   <div class="row cms_filters">
        <div class="col-md-8">
            <h3>Filter Products By Category</h3> 
        </div>
        <div class="col-md-4 ">
            <select v-model="value_category_id" @click="return_data(value_category_id)">
                <option :value="'all'">All</option>
                <option v-for="category in product_categories" :key="category" :value="category.id">{{ category.us_name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                product_categories:[],
                value_category_id: 'all'
            }
        },

        mounted() {
            this.get_categories_data();
        },
         
        methods: {
            get_categories_data: function(){
                axios
                .get("../api/product_category/")
                .then(response => {
                    this.product_categories = response.data
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