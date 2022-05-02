<template>
    <div>
        <h3>Filter Products By Category</h3> 
        <select v-model="value_product_category_id">
            <option v-bind:value="'all'">All</option>
            <option v-for="category in product_categorys" :key="category" v-bind:value="category.id">{{ category.us_name }}</option>
        </select>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                product_categorys:[],
            }
        },

        mounted() {
            this.get_categories_data();
        },
         
        methods: {
            get_categories_data: function(){
                axios
                .get("../api/categories/")
                .then(response => {
                    this.product_categorys = response.data
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