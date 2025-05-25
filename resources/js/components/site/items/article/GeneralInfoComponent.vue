<template>
    <div>
        <span v-if="global_info_prop.length == 0">
            <span v-html="locale_data_prop"></span>
        </span>
        <span v-else>
            <span v-if="global_info_prop.block_action == 'befor'">
                <span v-html="global_info_prop.text"></span>
                <span v-html="locale_data_prop"></span>
            </span>
            <span v-if="global_info_prop.block_action == 'after'">
                <span v-html="locale_data_prop"></span>
                <span v-html="global_info_prop.text"></span>
            </span>
            <span v-if="global_info_prop.block_action == 'instead'">
                <span v-html="global_info_prop.text"></span>
            </span>
        </span>

    </div> 
</template>

<script>
    export default {
        props: [
            'global_info_prop',
            'locale_data_prop',
        ],
        components: {
            // 
        },
        data: function () {
            return {
                // 
            }
        },
        mounted() {
            // 
        },
        watch: {
            '$route' (to, from) {
                // 
            }
        },
        methods: {
            // 
        }
    }
</script>