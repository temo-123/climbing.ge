<template>
    <div class="container climber-list">
        <metaData
            :title="$t('global.follow.all_climbers_title') + ' - climbing.ge'"
            :description="$t('global.follow.climbers_meta_description')"
        />

        <h1>{{ $t('global.follow.all_climbers_title') }}</h1>

        <div class="climber-list__grid">
            <climber-card v-for="u in users" :key="u.id" :user="u" @open="open_profile" />
        </div>

        <div class="row justify-content-center" v-if="loading">
            <div class="col-md-3 text-center">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="text-center mt-3 mb-4" v-if="!loading && page < lastPage">
            <button class="btn btn-outline-primary" @click="load_more()">{{ $t('global.follow.load_more_btn') }}</button>
        </div>

        <climber-profile-modal ref="profile_modal" @follow-changed="on_follow_changed" />
    </div>
</template>

<script>
    import ClimberProfileModal from '../items/climber/ClimberProfileModalComponent.vue';
    import ClimberCard from '../../global_components/ClimberCardComponent.vue';
    import metaData from '../items/MetaDataComponent';

    export default {
        components: {
            'climber-profile-modal': ClimberProfileModal,
            'climber-card': ClimberCard,
            metaData,
        },
        data() {
            return {
                users: [],
                page: 1,
                lastPage: 1,
                loading: false,
            };
        },
        mounted() {
            this.fetch_page(1);
        },
        methods: {
            fetch_page(page) {
                this.loading = true;
                axios.get('get_climber_profile/list', { params: { page } })
                    .then(response => {
                        this.users = page === 1 ? response.data.data : this.users.concat(response.data.data);
                        this.page = response.data.current_page;
                        this.lastPage = response.data.last_page;
                    })
                    .finally(() => this.loading = false);
            },
            load_more() {
                this.fetch_page(this.page + 1);
            },
            open_profile(userId) {
                this.$refs.profile_modal.show_modal(userId);
            },
            on_follow_changed({ userId, followers_count }) {
                const card = this.users.find(u => u.id == userId);
                if (card) card.followers_count = followers_count;
            },
        },
    };
</script>

<style scoped>
.climber-list__grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 14px;
    margin: 16px 0;
}
</style>
