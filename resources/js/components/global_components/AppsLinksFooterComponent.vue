<template>
    <div class="footer_apps_links_row" v-if="guideAppStore || guidePlayStore || trainingAppStore || trainingPlayStore">
        <h4 class="footer_title">{{ $t('global.footer.get_app') }}</h4>

        <div class="footer_apps_links_groups">
            <div class="footer_apps_links_group" v-if="guideAppStore || guidePlayStore">
                <div class="footer_apps_links_group_label">{{ $t('global.footer.guide_app_label') }}</div>
                <div class="footer_apps_links_block">
                    <a v-if="guideAppStore" :href="guideAppStore" target="_blank" rel="noopener">
                        <img loading="lazy" decoding="async" src="/images/training_promo/app-store-badge.webp" :alt="$t('global.footer.app_store_alt')">
                    </a>
                    <a v-if="guidePlayStore" :href="guidePlayStore" target="_blank" rel="noopener">
                        <img loading="lazy" decoding="async" src="/images/training_promo/google-play-badge.webp" :alt="$t('global.footer.google_play_alt')">
                    </a>
                </div>
            </div>

            <div class="footer_apps_links_group" v-if="trainingAppStore || trainingPlayStore">
                <div class="footer_apps_links_group_label">{{ $t('global.footer.training_app_label') }}</div>
                <div class="footer_apps_links_block">
                    <a v-if="trainingAppStore" :href="trainingAppStore" target="_blank" rel="noopener">
                        <img loading="lazy" decoding="async" src="/images/training_promo/app-store-badge.webp" :alt="$t('global.footer.app_store_alt')">
                    </a>
                    <a v-if="trainingPlayStore" :href="trainingPlayStore" target="_blank" rel="noopener">
                        <img loading="lazy" decoding="async" src="/images/training_promo/google-play-badge.webp" :alt="$t('global.footer.google_play_alt')">
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                guideAppStore: '',
                guidePlayStore: '',
                trainingAppStore: '',
                trainingPlayStore: '',
            }
        },
        mounted() {
            this.get_apps_links()
        },
        methods: {
            get_apps_links() {
                axios
                    .get('get_apps_links/get_all')
                    .then(response => {
                        const links = response.data || []
                        const find = keyword => {
                            const row = links.find(l => l.keyword === keyword)
                            return row ? row.link : ''
                        }
                        this.guideAppStore = find('guide_app_store')
                        this.guidePlayStore = find('guide_play_store')
                        this.trainingAppStore = find('training_app_store')
                        this.trainingPlayStore = find('training_play_store')
                    })
                    .catch(error => {
                        console.log('Error fetching app links:', error)
                    })
            }
        }
    }
</script>

<style scoped>
    .footer_apps_links_row {
        padding: 1.5rem 0;
        border-top: 1px solid rgba(255, 255, 255, .1);
    }
    .footer_apps_links_groups {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem 3rem;
    }
    .footer_apps_links_group_label {
        margin-bottom: .5rem;
        color: #bdbdbd;
        font-size: .85rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .footer_apps_links_block {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .75rem;
    }
    .footer_apps_links_block a {
        transition: transform .2s ease;
    }
    .footer_apps_links_block a:hover {
        transform: translateY(-2px);
    }
    .footer_apps_links_block img {
        height: 48px;
        width: auto;
    }
</style>
