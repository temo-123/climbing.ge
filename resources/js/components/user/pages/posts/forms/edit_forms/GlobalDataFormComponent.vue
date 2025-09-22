<template>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">Edit post global information</h2>
                        <p class="lead">Edit post global information.</p>
                    </div>
                </div>
                <form style="margin-top: 5%;">
                    <published_item 
                        :published_prop="data.published"
                        @item_data="data.published = $event" 
                    />
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import published_item from '../../../../items/form/parts/PublishedValueComponent.vue'

    export default {
        components: {
            published_item
        },
        props: [
            'global_data_prop',
            'category_prop',
            'title_prop',
            'description_prop',
        ],
        data(){
            return {
                data: this.global_data_prop,
                category: this.category_prop,
            }
        },
        watch: {
            global_data_prop(newVal) { this.data = newVal; },
            category_prop(newVal) { this.category = newVal; },
        },
    }
</script>

<style>

</style>