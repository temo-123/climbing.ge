<template>
    <div>
        <!-- Show More Button on Shadow Block -->
        <div v-if="global_info_prop.is_show && !is_show_more_data" class="show-shadow-container">
            <button class="show-more-btn" @click="show_more_data">
                <i class="fa fa-eye"></i>
                {{ locale_data_prop && locale_data_prop.show_more ? locale_data_prop.show_more : 'Show more' }}
            </button>
        </div>

        <span v-if="global_info_prop.length == 0">
            <span v-html="locale_data_prop"></span>
        </span>
        <span v-else>
            <span v-if="global_info_prop.block_action == 'befor'">
                <span v-if="global_info_prop.is_show">
                    <span class="is_show_block" :class="{ 'show-active': is_show_more_data }" v-if="is_show_more_data">
                        <span v-html="global_info_prop.text"></span>
                        <span v-html="locale_data_prop"></span>
                    </span>
                </span><span v-else>
                    <span v-html="global_info_prop.text"></span>
                    <span v-html="locale_data_prop"></span>
                </span>
            </span>
            <span v-if="global_info_prop.block_action == 'after'">
                <span v-if="global_info_prop.is_show">
                    <span class="is_show_block" :class="{ 'show-active': is_show_more_data }" v-if="is_show_more_data">
                        <span v-html="locale_data_prop"></span>
                        <span v-html="global_info_prop.text"></span>
                    </span>
                </span><span v-else>
                    <span v-html="locale_data_prop"></span>
                    <span v-html="global_info_prop.text"></span>
                </span>
            </span>
            <span v-if="global_info_prop.block_action == 'instead'">
                <span v-if="global_info_prop.is_show">
                    <span class="is_show_block" :class="{ 'show-active': is_show_more_data }" v-if="is_show_more_data">
                        <span v-html="global_info_prop.text"></span>
                    </span>
                </span><span v-else>
                    <span v-html="global_info_prop.text"></span>
                </span>
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
                is_show_more_data: false
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
            show_more_data(){
                this.is_show_more_data = !this.is_show_more_data
            }
        }
    }
</script>

<style scoped>
    /* Shadow container with left highlight */
    .show-shadow-container {
        background: #f0f0f0;
        border-left: 4px solid #f0f0f0;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Friendly show more button */
    .show-more-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .show-more-btn:hover {
        background: #e0e0e0;
        transform: translateY(-2px);
    }
    
</style>