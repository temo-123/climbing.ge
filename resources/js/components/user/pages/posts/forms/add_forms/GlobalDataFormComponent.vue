<template>
    <div class="col-md-12" >
        <div class="row">
            <div class="col-md-12">
                <div class="jumbotron width_100">
                    <div class="container">
                        <h2 class="display-4"><span style="text-transform: capitalize">{{this.category}}</span> article global information</h2>
                        <p class="lead">Article global information.</p>
                    </div>
                </div>
                <form method="POST" @submit.prevent="add_article">
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
    // import { SlickList, SlickItem } from 'vue-slicksort';
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

    import published_item from '../../../../items/form/parts/PublishedValueComponent.vue'
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,

            published_item
        },
        props: [
            // 'back_url',
            // 'category'
        ],
        data(){
            return {
                category: this.$route.params.article_category,

                editorConfig: {
                    // toolbar: [ [ 'Bold' ] ]
                },

                error: [],
                
                regions: [],
                mount_masive: [],

                data: {
                    category: this.$route.params.article_category,
                    // us_title_for_url_title: "",
                    published: 0,
                },
                    region_id: 0,
                    mount_id: 0,
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