<template>
    <div>
        <span v-if="global_info.length == 0">
            <span v-html="locale_data"></span>
        </span>
        <span v-else>
            <span v-if="global_info.block_action == 'befor'">
                <span v-html="global_info.text"></span>
                <span v-html="locale_data"></span>
            </span>
            <span v-if="global_info.block_action == 'after'">
                <span v-html="locale_data"></span>
                <span v-html="global_info.text"></span>
            </span>
            <span v-if="global_info.block_action == 'instead'">
                <span v-html="global_info.text"></span>
            </span>
        </span>

    </div> 
</template>

<script>
    export default {
        props: [
            'global_info',
            'locale_data',
        ],
        components: {
            // commentForm,
        },
        data: function () {
            return {
                // article_global_data: [],
            }
        },
        mounted() {
            // 
        },
        watch: {
            '$route' (to, from) {
                // console.log('lllllllllllll');
                // this.update_similar_articles_component()
                // console.log('lllllllllllll');
            }
        },
        methods: {
            // 
        }
    }
</script>