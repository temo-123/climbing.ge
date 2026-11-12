<template>
    <!-- Account settings (/options): profile, addresses, notifications, preferences.
         The open tab lives in ?tab= so it survives reloads and can be linked to. -->
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <breadcrumb />

            <section class="cg-page-head cg-dashboard__hero cg-art-user">
                <span class="cg-eyebrow">{{ $t('user.profile.page_eyebrow') }}</span>
                <h1>{{ $t('user.profile.page_title') }}</h1>
                <p>{{ $t('user.profile.page_lead') }}</p>
            </section>

            <div class="cg-segmented opt-tabs" role="tablist">
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
                    <i :class="'fa ' + t.icon" aria-hidden="true"></i>
                    <span>{{ t.label }}</span>
                </button>
            </div>

            <div class="opt-body">
                <template v-if="tab === 'profile'">
                    <userOptions />

                    <section class="cg-panel opt-danger mt-4">
                        <div>
                            <h2 class="opt-danger__title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('user.profile.danger_title') }}</h2>
                            <p class="mb-0 text-muted small">{{ $t('user.profile.danger_text') }}</p>
                        </div>
                        <router-link :to="{ name: 'delete_account' }" class="btn btn-outline-danger btn-sm">
                            {{ $t('user.delete_account.options_link') }}
                        </router-link>
                    </section>
                </template>

                <div v-else-if="tab === 'addresses'" class="row">
                    <adresList />
                </div>

                <div v-else-if="tab === 'notifications'" class="row">
                    <notificationsControlList />
                </div>

                <div v-else-if="tab === 'preferences'" class="row">
                    <userPreferences />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'

    import notificationsControlList from '../items/user_options/UserNotificationsListComponent.vue'
    import adresList from '../items/user_options/UserAdresesComponent.vue'
    import userOptions from '../items/user_options/UserOptionsComponent.vue'
    import userPreferences from '../items/preferences/UserPreferencesComponent.vue'

    const TAB_KEYS = ['profile', 'addresses', 'notifications', 'preferences']

    export default {
        components: {
            adresList,
            breadcrumb,
            userOptions,
            userPreferences,
            notificationsControlList,
        },
        props: [
            'status',
        ],
        computed: {
            tab() {
                const t = this.$route.query.tab
                return TAB_KEYS.includes(t) ? t : 'profile'
            },
            tabs() {
                return [
                    { key: 'profile',       icon: 'fa-user',      label: this.$t('user.profile.tab_private_info') },
                    { key: 'addresses',     icon: 'fa-map-marker', label: this.$t('user.profile.tab_addresses') },
                    { key: 'notifications', icon: 'fa-bell',      label: this.$t('user.profile.tab_notifications') },
                    { key: 'preferences',   icon: 'fa-sliders',   label: this.$t('user.preferences.tab') },
                ]
            },
        },
        methods: {
            open_tab(key) {
                if (key === this.tab) return
                const query = { ...this.$route.query }
                if (key === 'profile') delete query.tab
                else query.tab = key
                this.$router.replace({ query })
            },
        },
    }
</script>

<style scoped>
    .opt-tabs {
        margin: 0 0 1.5rem;
        flex-wrap: wrap;
    }
    .opt-body {
        min-height: 12rem;
    }
    .opt-danger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        border-color: rgba(220, 53, 69, .35);
    }
    .opt-danger__title {
        font-size: 1rem;
        font-weight: 700;
        color: #b02a37;
        margin: 0 0 .25rem;
    }
    @media (max-width: 575.98px) {
        .opt-tabs > button span { display: none; }
        .opt-tabs > button i { font-size: 1.1rem; }
    }
</style>
