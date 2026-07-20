<template>
    <div class="climber-profile-content">
        <div class="row justify-content-center" v-if="loading">
            <div class="col-md-3 text-center">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>
        </div>

        <div class="alert alert-warning" v-else-if="!profile">
            {{ $t('global.follow.profile_not_found') }}
        </div>

        <div v-else>
            <div class="climber-profile__header">
                <img
                    v-if="profile.user.image"
                    :src="'/public/images/user_profil_img/' + profile.user.image"
                    class="climber-profile__avatar"
                    alt="avatar"
                >
                <div v-else class="climber-profile__avatar climber-profile__avatar--placeholder">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </div>

                <div class="climber-profile__info">
                    <h1>{{ profile.user.name }} {{ profile.user.surname }}</h1>
                    <p v-if="profile.user.my_bio" class="climber-profile__bio">{{ profile.user.my_bio }}</p>

                    <div class="climber-profile__social-links" v-if="hasSocialLinks">
                        <a v-if="profile.user.social_links.facebook" :href="profile.user.social_links.facebook" target="_blank" rel="noopener noreferrer" :title="'Facebook'">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a v-if="profile.user.social_links.instagram" :href="profile.user.social_links.instagram" target="_blank" rel="noopener noreferrer" :title="'Instagram'">
                            <i class="fa fa-instagram" aria-hidden="true"></i>
                        </a>
                        <a v-if="profile.user.social_links.youtube" :href="profile.user.social_links.youtube" target="_blank" rel="noopener noreferrer" :title="'YouTube'">
                            <i class="fa fa-youtube-play" aria-hidden="true"></i>
                        </a>
                        <a v-if="profile.user.social_links.website" :href="profile.user.social_links.website" target="_blank" rel="noopener noreferrer" :title="'Website'">
                            <i class="fa fa-globe" aria-hidden="true"></i>
                        </a>
                    </div>

                    <ul class="climber-profile__sites" v-if="profile.user_sites.length">
                        <li v-for="site in profile.user_sites" :key="site.id">
                            <a :href="site.url" target="_blank" rel="noopener noreferrer">
                                <i class="fa fa-link" aria-hidden="true"></i> {{ siteHost(site.url) }}
                            </a>
                        </li>
                    </ul>

                    <div class="climber-profile__counts">
                        <a href="#" @click.prevent="toggle_list('followers')">
                            <strong>{{ profile.followers_count }}</strong> {{ $t('global.follow.followers_label') }}
                        </a>
                        <a href="#" @click.prevent="toggle_list('following')">
                            <strong>{{ profile.following_count }}</strong> {{ $t('global.follow.following_label') }}
                        </a>
                    </div>

                    <user-follow-button
                        :user-id="userId"
                        @login-required="$emit('login-required')"
                        @login-succeeded="$emit('login-succeeded')"
                        @changed="on_follow_changed"
                    />
                </div>
            </div>

            <div class="climber-profile__section" v-if="open_list">
                <ul class="climber-profile__user-links">
                    <li v-for="u in profile[open_list]" :key="u.id">
                        <a :href="climberProfileUrl(u.id)" @click.prevent="go_to_climber(u.id)">
                            <img
                                v-if="u.image"
                                :src="'/public/images/user_profil_img/' + u.image"
                                class="climber-profile__user-link-avatar"
                                alt="avatar"
                            >
                            <i v-else class="fa fa-user-circle climber-profile__user-link-avatar-placeholder" aria-hidden="true"></i>
                            {{ u.name }} {{ u.surname }}
                        </a>
                    </li>
                    <li v-if="!profile[open_list].length" class="text-muted">{{ $t('global.follow.' + (open_list === 'followers' ? 'no_followers' : 'no_following')) }}</li>
                </ul>
            </div>

            <hr>

            <div class="climber-profile__section" v-if="profile.recent_route_reviews.length || profile.recent_mtp_reviews.length">
                <h3>{{ $t('global.follow.recent_reviews_title') }}</h3>
                <ul class="climber-profile__list">
                    <li v-for="r in profile.recent_route_reviews" :key="'route-' + r.id">
                        <i class="fa fa-star" aria-hidden="true"></i> {{ r.route_name }} — {{ r.stars }}/5
                    </li>
                    <li v-for="r in profile.recent_mtp_reviews" :key="'mtp-' + r.id">
                        <i class="fa fa-star" aria-hidden="true"></i> {{ r.mtp_name }} — {{ r.stars }}/5
                    </li>
                </ul>
            </div>

            <div class="climber-profile__section" v-if="profile.recent_ascents.length">
                <h3>{{ $t('global.follow.recent_ascents_title') }}</h3>
                <ul class="climber-profile__list">
                    <li v-for="a in profile.recent_ascents" :key="'ascent-' + a.id">
                        <i class="fa fa-flag-checkered" aria-hidden="true"></i> {{ a.summit_title }}
                    </li>
                </ul>
            </div>

            <div class="climber-profile__section" v-if="profile.recent_comments.length">
                <h3>{{ $t('global.follow.recent_comments_title') }}</h3>
                <ul class="climber-profile__list">
                    <li v-for="c in profile.recent_comments" :key="'comment-' + c.id">
                        <i class="fa fa-comment" aria-hidden="true"></i> {{ c.text }}
                    </li>
                </ul>
            </div>

            <div class="climber-profile__section" v-if="!profile.recent_comments.length && !profile.recent_ascents.length && !profile.recent_route_reviews.length && !profile.recent_mtp_reviews.length">
                <p class="text-muted">{{ $t('global.follow.no_activity') }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import UserFollowButton from '../../../global_components/UserFollowButtonComponent.vue';

    export default {
        components: {
            'user-follow-button': UserFollowButton,
        },
        props: {
            userId: { type: [Number, String], required: true },
        },
        emits: ['login-required', 'login-succeeded', 'profile-loaded', 'follow-changed'],
        computed: {
            hasSocialLinks() {
                const links = this.profile?.user?.social_links;
                return !!links && Object.values(links).some(v => !!v);
            },
        },
        data() {
            return {
                profile: null,
                loading: true,
                open_list: null,
            };
        },
        mounted() {
            this.get_profile();
        },
        watch: {
            userId() {
                this.open_list = null;
                this.get_profile();
            },
        },
        methods: {
            get_profile() {
                this.loading = true;
                this.profile = null;
                axios.get('get_climber_profile/' + this.userId)
                    .then(response => {
                        this.profile = response.data;
                        this.$emit('profile-loaded', this.profile);
                    })
                    .catch(() => {
                        this.profile = null;
                    })
                    .finally(() => this.loading = false);
            },
            toggle_list(list) {
                this.open_list = this.open_list === list ? null : list;
            },
            on_follow_changed(following) {
                if (!this.profile) return;
                this.profile.followers_count += following ? 1 : -1;
                this.$emit('follow-changed', {
                    userId: this.userId,
                    following,
                    followers_count: this.profile.followers_count,
                });
            },
            climberProfileUrl(userId) {
                const ssh = process.env.MIX_APP_SSH || 'http://';
                const siteHost = process.env.MIX_GUIDBOOK_URL || process.env.MIX_SITE_URL || '';
                return `${ssh}${siteHost}/climber/${userId}`;
            },
            go_to_climber(userId) {
                // Same-subdomain (guide) callers get a smooth SPA navigation;
                // this component is also embedded elsewhere (e.g. the user
                // dashboard's own-profile modal), where that route doesn't
                // exist — fall back to a plain cross-subdomain navigation.
                if (this.$router && this.$router.hasRoute && this.$router.hasRoute('climberProfile')) {
                    this.$router.push({ name: 'climberProfile', params: { id: userId } });
                    return;
                }
                window.open(this.climberProfileUrl(userId));
            },
            siteHost(url) {
                try {
                    return new URL(url).hostname.replace(/^www\./, '');
                } catch (e) {
                    return url;
                }
            },
        },
    };
</script>

<style scoped>
.climber-profile__header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 0;
}
.climber-profile__avatar {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
}
.climber-profile__avatar--placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee;
    font-size: 3.2rem;
    color: #aaa;
}
.climber-profile__info h1 {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 4px;
}
.climber-profile__bio {
    color: #666;
    max-width: 600px;
    font-size: 1.8rem;
}
.climber-profile__social-links {
    display: flex;
    gap: 16px;
    margin: 10px 0;
    font-size: 2.2rem;
}
.climber-profile__social-links a {
    color: #666;
}
.climber-profile__social-links a:hover {
    color: #279fbb;
}
.climber-profile__sites {
    list-style: none;
    padding: 0;
    margin: 10px 0 0;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 1.7rem;
}
.climber-profile__sites a {
    color: #279fbb;
    text-decoration: none;
}
.climber-profile__sites a:hover {
    text-decoration: underline;
}
.climber-profile__counts {
    display: flex;
    gap: 22px;
    margin: 12px 0;
    font-size: 2rem;
}
.climber-profile__counts a {
    color: inherit;
    text-decoration: none;
}
.climber-profile__counts a:hover {
    text-decoration: underline;
}
.climber-profile__section {
    margin-bottom: 24px;
}
.climber-profile__section h3 {
    font-size: 2.2rem;
}
.climber-profile__list {
    list-style: none;
    padding: 0;
    font-size: 1.8rem;
}
.climber-profile__list li {
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}
.climber-profile__user-links {
    list-style: none;
    padding: 14px 0 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 1.7rem;
}
.climber-profile__user-links li {
    background: #f5f5f5;
    border-radius: 20px;
    padding: 6px 14px 6px 6px;
}
.climber-profile__user-links a {
    display: flex;
    align-items: center;
    gap: 8px;
    color: inherit;
    text-decoration: none;
}
.climber-profile__user-link-avatar,
.climber-profile__user-link-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
.climber-profile__user-link-avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ddd;
    color: #999;
    font-size: 1.8rem;
}
</style>
