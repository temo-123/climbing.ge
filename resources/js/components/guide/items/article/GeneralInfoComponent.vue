<template>
    <div>
        <!-- Show More Button on Shadow Block -->
        <div v-if="is_show" class="show-shadow-container" :class="{ 'shadow-hidden': is_show_more_data }">
            <!-- Full content that is always rendered but covered by shadow -->
            <div class="content-wrapper">
                <span v-for="(part, i) in parts" :key="i" v-html="part"></span>
            </div>

            <!-- Centered Show Button (visible when content is hidden) -->
            <div v-if="!is_show_more_data" class="center-overlay">
                <button class="show-more-btn" @click="show_more_data">
                    <i class="fa fa-eye"></i>
                    {{ $t('guide.show_more') }}
                </button>
            </div>
        </div>

        <!-- Default rendering when is_show is false -->
        <span v-else>
            <span v-for="(part, i) in parts" :key="i" v-html="part"></span>
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
        computed: {
            // general_info block from the API: object { block_action, text, is_show } or [] / null when none
            block() {
                const b = this.global_info_prop
                return b && !Array.isArray(b) && b.block_action ? b : null
            },
            is_show() {
                return !!(this.block && this.block.is_show)
            },
            // befor = general text then own text, after = own then general,
            // instead = general only; no block (or new_info) = own text only
            parts() {
                const own = this.locale_data_prop
                const b = this.block
                let parts = [own]
                if (b && b.block_action == 'befor') parts = [b.text, own]
                else if (b && b.block_action == 'after') parts = [own, b.text]
                else if (b && b.block_action == 'instead') parts = [b.text]
                return parts.filter(p => p != null && p !== '')
            },
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
                if (this.is_show_more_data && this.block && this.block.id) {
                    this.trackAction(this.block.id, 'show_more_click')
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
        /* background: var(--cg-chalk);
        border-left: 4px solid var(--cg-chalk);
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
        color: var(--cg-ink-2);
        border: 2px solid var(--cg-ink-2);
        border-radius: 30px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .show-more-btn:hover {
        background: var(--cg-ink-2);
        color: #fff;
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }
    
</style>
