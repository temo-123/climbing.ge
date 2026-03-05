<template>
    <div class="col-md-12" >
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">Blog</span> global information</h2>
                        <!-- <p class="lead">Blog global information.</p> -->
                    </div>
                </div>
                <form method="POST" @submit.prevent="add_post">
                    <published_item 
                        :published_prop = data.published
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
            // 'back_url',
            // 'category'
        ],
        data(){
            return {
                data: {
                    published: 0,
                },
            }
        },
        mounted() {
            this.$emit('global_form_data', this.data)
        },
        methods: {
            //
        }
    }
</script>

<style>

</style>