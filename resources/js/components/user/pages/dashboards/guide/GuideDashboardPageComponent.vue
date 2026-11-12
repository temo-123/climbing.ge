<template>
    <!-- Guidebook dashboard (/guide_dashboard_page, guide_task › show).
         Tabs: Overview (what needs attention + the guidebook in numbers) · Donations ·
         Community (condition reports, partner posts; comment › show). Open tab in ?tab=.
         docs/FUNCTIONS_DOCS/guide/guide_dashboard.md -->
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <breadcrumb />

            <section class="cg-page-head cg-dashboard__hero cg-art-mountains">
                <span class="cg-eyebrow">{{ $t('admin.guide_dash.eyebrow') }}</span>
                <h1>{{ $t('admin.guide_dash.title') }}</h1>
                <p>{{ $t('admin.guide_dash.lead') }}</p>
            </section>

            <div class="gd-tabs">
                <div class="cg-segmented" role="tablist">
                    <button
                        v-for="t in tabs"
                        :key="t.key"
                        type="button"
                        role="tab"
                        :class="{ active: tab === t.key }"
                        :aria-selected="tab === t.key"
                        :title="t.label"
                        @click="open_tab(t.key)"
                    >
                        <i class="fa" :class="t.icon" aria-hidden="true"></i>
                        <span class="gd-tabs__label">{{ t.label }}</span>
                    </button>
                </div>
                <p class="gd-tabs__hint">{{ current.hint }}</p>
            </div>

            <div v-if="tab === 'overview'">
                <admin_attention :blocks="['guide', 'moderation']" :title="$t('admin.guide_dash.attention_title')" />
                <data_counts_list />
            </div>

            <section v-else-if="tab === 'donations'" class="cg-panel">
                <donation_list />
            </section>

            <section v-else-if="tab === 'community'" class="cg-panel">
                <community_moderation />
            </section>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    import data_counts_list from '../../../items/dashboards/SiteDataCountListComponent.vue'
    import donation_list from './assets/DonationListComponent.vue'
    import community_moderation from './assets/CommunityModerationComponent.vue'
    import admin_attention from '../../../items/dashboards/AdminAttentionComponent.vue'

    export default {
        components: {
            breadcrumb,
            data_counts_list,
            donation_list,
            community_moderation,
            admin_attention,
        },
        computed: {
            tabs() {
                const list = [
                    { key: 'overview', icon: 'fa-tachometer', label: this.$t('admin.guide_dash.tab_overview'), hint: this.$t('admin.guide_dash.hint_overview') },
                    { key: 'donations', icon: 'fa-heart', label: this.$t('admin.dashboards.donations_tab'), hint: this.$t('admin.guide_dash.hint_donations') },
                ]
                if (this.$can('show', 'comment')) {
                    list.push({ key: 'community', icon: 'fa-users', label: this.$t('admin.community.tab'), hint: this.$t('admin.guide_dash.hint_community') })
                }
                return list
            },
            tab() {
                const t = this.$route.query.tab
                return this.tabs.some(x => x.key === t) ? t : 'overview'
            },
            current() {
                return this.tabs.find(t => t.key === this.tab) || this.tabs[0]
            },
        },
        methods: {
            open_tab(key) {
                if (key === this.tab) return
                const query = { ...this.$route.query }
                if (key === 'overview') delete query.tab
                else query.tab = key
                this.$router.replace({ query })
            },
        },
    }
</script>

<style scoped>
    .gd-tabs {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .5rem 1rem;
        margin-bottom: 1.25rem;
    }
    .gd-tabs__hint {
        margin: 0;
        font-size: .88rem;
        color: var(--cg-stone);
    }
    @media (max-width: 575.98px) {
        .gd-tabs__label { display: none; }
        .gd-tabs .cg-segmented i { font-size: 1.1rem; }
    }
</style>
