<template>
    <div>
        <!-- Show More Button on Shadow Block -->
        <div v-if="global_info_prop.is_show" class="show-shadow-container" :class="{ 'shadow-hidden': is_show_more_data }">
            <!-- Full content that is always rendered but covered by shadow -->
            <div class="content-wrapper">
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
            
            <!-- Centered Show Button (visible when content is hidden) -->
            <div v-if="!is_show_more_data" class="center-overlay">
                <button class="show-more-btn" @click="show_more_data">
                    <i class="fa fa-eye"></i>
                    {{ locale_data_prop && locale_data_prop.show_more ? locale_data_prop.show_more : 'Show more' }}
                </button>
            </div>
        </div>

        <!-- Default rendering when is_show is false -->
        <span v-if="!global_info_prop.is_show">
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

                // Track the action if showing more content
                if (this.is_show_more_data && this.global_info_prop.id) {
                    this.trackAction(this.global_info_prop.id, 'show_more_click')
                }
            },

            async trackAction(blockId, actionType) {
                try {
                    const response = await axios.post('get_general_info/track_action', {
                        block_id: blockId,
                        action_type: actionType
                    })

                    if (response.data.status === 'success') {
                        console.log('Action tracked successfully')
                    }
                } catch (error) {
                    console.error('Failed to track action:', error)
                }
            }
        }
    }
</script>

<style scoped>
    /* Shadow container with left highlight */
    .show-shadow-container {
        position: relative;
        /* background: #f0f0f0;
        border-left: 4px solid #f0f0f0;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); */
        overflow: hidden;
    }
    
    /* Content wrapper - always takes full space */
    .content-wrapper {
        opacity: 0.3;
        filter: blur(2px);
        pointer-events: none;
    }
    
    /* When revealed - remove blur and shadow */
    .shadow-hidden .content-wrapper {
        opacity: 1;
        filter: none;
        pointer-events: auto;
    }
    
    /* Center overlay with button */
    .center-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        /* background: rgba(255, 255, 255, 0.4); */
        background: rgb(114 157 170 / 51%);
        z-index: 10;
    }
    
    /* Friendly show more button */
    .show-more-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 30px;
        background: #fff;
        color: #333;
        border: 2px solid #333;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .show-more-btn:hover {
        background: #333;
        color: #fff;
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }
    
</style>
