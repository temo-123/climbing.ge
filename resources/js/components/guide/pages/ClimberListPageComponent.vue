<template>
    <div class="container climber-list">
        <metaData
            :title="$t('global.follow.all_climbers_title') + ' - climbing.ge'"
            :description="$t('global.follow.climbers_meta_description')"
        />

        <h1>{{ $t('global.follow.all_climbers_title') }}</h1>

        <current-user-climber-card />

        <div class="climber-list__toolbar">
            <div class="climber-list__search-wrap">
                <i class="fa fa-search climber-list__search-icon" aria-hidden="true"></i>
                <input
                    type="text"
                    class="form-control climber-list__search"
                    v-model="search"
                    :placeholder="$t('global.follow.search_climbers_placeholder')"
                    @input="on_search_input"
                >
            </div>

            <div class="climber-list__sort" role="group">
                <button
                    type="button"
                    class="climber-list__sort-btn"
                    :class="{ 'climber-list__sort-btn--active': sort === 'name' }"
                    @click="set_sort('name')"
                >{{ $t('global.follow.sort_flat_list_btn') }}</button>
                <button
                    type="button"
                    class="climber-list__sort-btn"
                    :class="{ 'climber-list__sort-btn--active': sort === 'top_active' }"
                    @click="set_sort('top_active')"
                ><i class="fa fa-trophy" aria-hidden="true"></i> {{ $t('global.follow.sort_top_active_btn') }}</button>
            </div>
        </div>

        <div class="climber-list__grid" v-if="users.length">
            <climber-card v-for="u in users" :key="u.id" :user="u" @open="open_profile" />
        </div>

        <div class="climber-list__empty" v-if="!loading && !users.length">
            {{ $t('global.follow.no_climbers_found') }}
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
    import CurrentUserClimberCard from '../../global_components/CurrentUserClimberCardComponent.vue';
    import metaData from '../items/MetaDataComponent';

    export default {
        components: {
            'climber-profile-modal': ClimberProfileModal,
            'climber-card': ClimberCard,
            'current-user-climber-card': CurrentUserClimberCard,
            metaData,
        },
        data() {
            return {
                users: [],
                page: 1,
                lastPage: 1,
                loading: false,
                search: '',
                search_timeout: null,
                sort: 'name',
            };
        },
        mounted() {
            this.fetch_page(1);
        },
        methods: {
            fetch_page(page) {
                this.loading = true;
                axios.get('get_climber_profile/list', { params: { page, search: this.search, sort: this.sort } })
                    .then(response => {
                        this.users = page === 1 ? response.data.data : this.users.concat(response.data.data);
                        this.page = response.data.current_page;
                        this.lastPage = response.data.last_page;
                    })
                    .finally(() => this.loading = false);
            },
            on_search_input() {
                clearTimeout(this.search_timeout);
                this.search_timeout = setTimeout(() => this.fetch_page(1), 350);
            },
            set_sort(sort) {
                if (this.sort === sort) return;
                this.sort = sort;
                this.fetch_page(1);
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
.climber-list__toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin: 16px 0;
}
.climber-list__search-wrap {
    position: relative;
    max-width: 360px;
    flex: 1 1 240px;
}
.climber-list__search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}
.climber-list__search {
    padding-left: 34px;
    border-radius: 999px;
}
.climber-list__sort {
    display: inline-flex;
    gap: 4px;
    flex: 0 0 auto;
    background: #f2f2f2;
    padding: 4px;
    border-radius: 999px;
}
.climber-list__sort-btn {
    border: none;
    background: transparent;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 1.3rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: background .15s, color .15s;
}
.climber-list__sort-btn:hover {
    color: #279fbb;
}
.climber-list__sort-btn--active {
    background: #279fbb;
    color: #fff;
}
.climber-list__grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
    margin: 16px 0;
}
.climber-list__empty {
    text-align: center;
    color: #888;
    padding: 40px 0;
    font-size: 1.6rem;
}
</style>
