<template>
    <div class="current-user-card" v-if="user && user.id">
        <div class="current-user-card__avatar-wrap" @click="open_my_profile_modal">
            <img
                v-if="user.image"
                :src="'/public/images/user_profil_img/' + user.image"
                class="current-user-card__avatar"
                alt="avatar"
            >
            <div v-else class="current-user-card__avatar current-user-card__avatar--placeholder">
                <i class="fa fa-user" aria-hidden="true"></i>
            </div>
        </div>

        <div class="current-user-card__body">
            <div class="current-user-card__name" @click="open_my_profile_modal">{{ user.name }} {{ user.surname }}</div>

            <div class="current-user-card__stats">
                <span class="current-user-card__badge">
                    <i class="fa fa-users" aria-hidden="true"></i> {{ counts.followers_count }} {{ $t('global.follow.followers_label') }}
                </span>
                <span class="current-user-card__badge">
                    <i class="fa fa-user-plus" aria-hidden="true"></i> {{ counts.following_count }} {{ $t('global.follow.following_label') }}
                </span>
                <span class="current-user-card__badge current-user-card__badge--points">
                    <i class="fa fa-star" aria-hidden="true"></i> {{ counts.points_total }} {{ $t('global.follow.points_label') }}
                </span>
            </div>
        </div>

        <div class="current-user-card__extra">
            <slot name="extra"></slot>
        </div>

        <climber-profile-modal ref="profile_modal" />
    </div>

    <div class="current-user-card current-user-card--guest" v-else-if="checked">
        <div class="current-user-card__guest-icon">
            <i class="fa fa-user-circle" aria-hidden="true"></i>
        </div>
        <div class="current-user-card__guest-body">
            <p class="current-user-card__guest-text">{{ $t('global.follow.guest_prompt_text') }}</p>
            <div class="current-user-card__guest-actions">
                <button type="button" class="btn btn-primary btn-sm" @click="open_login_modal">
                    <i class="fa fa-sign-in" aria-hidden="true"></i> {{ $t('common.login') }}
                </button>
                <a href="#" class="btn btn-outline-primary btn-sm" @click.prevent="go_to_register">
                    {{ $t('global.follow.register_btn') }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import ClimberProfileModal from '../guide/items/climber/ClimberProfileModalComponent.vue'

    export default {
        components: {
            'climber-profile-modal': ClimberProfileModal,
        },
        emits: ['loaded'],
        data() {
            return {
                user: {},
                checked: false,
                counts: {
                    followers_count: 0,
                    following_count: 0,
                    points_total: 0,
                },
            };
        },
        mounted() {
            this.get_user_data();
        },
        methods: {
            get_user_data() {
                axios.get('/auth_user')
                    .then(response => {
                        this.user = response.data || {};
                        if (this.user.id) {
                            this.get_profile_counts();
                            this.$emit('loaded', this.user.id);
                        }
                    })
                    .catch(() => { this.user = {}; })
                    .finally(() => { this.checked = true; });
            },
            get_profile_counts() {
                axios.get('get_climber_profile/' + this.user.id)
                    .then(response => {
                        this.counts.followers_count = response.data.followers_count;
                        this.counts.following_count = response.data.following_count;
                        this.counts.points_total = response.data.points_total;
                    })
                    .catch(() => {});
            },
            open_my_profile_modal() {
                this.$refs.profile_modal.show_modal(this.user.id);
            },
            open_login_modal() {
                this.$bus.$emit('open-login-modal', () => this.get_user_data());
            },
            go_to_register() {
                const ssh = process.env.MIX_APP_SSH || 'http://';
                const userHost = process.env.MIX_USER_PAGE_URL || '';
                window.open(ssh + userHost + '/register');
            },
        },
    };
</script>

<style scoped>
.current-user-card {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 16px 20px;
    margin: 16px 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #f4f9fb 0%, #eef3f5 100%);
    border: 1px solid #e2e9ec;
}
.current-user-card__avatar-wrap {
    cursor: pointer;
    flex: 0 0 auto;
}
.current-user-card__avatar {
    width: 66px;
    height: 66px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #279fbb;
}
.current-user-card__avatar--placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #dfe7ea;
    color: #8a9aa1;
    font-size: 2rem;
}
.current-user-card__body {
    flex: 1 1 auto;
    min-width: 0;
}
.current-user-card__name {
    cursor: pointer;
    font-size: 1.9rem;
    font-weight: 700;
    margin-bottom: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.current-user-card__stats {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.current-user-card__badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 1.3rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    background: #fff;
    color: #555;
    border: 1px solid #e2e9ec;
}
.current-user-card__badge--points {
    background: #fff7e0;
    color: #b5860a;
    border-color: #f2e2ab;
}
.current-user-card__extra {
    flex: 0 0 auto;
}

.current-user-card--guest {
    align-items: center;
}
.current-user-card__guest-icon {
    flex: 0 0 auto;
    font-size: 3.4rem;
    color: #b6c3c9;
}
.current-user-card__guest-body {
    flex: 1 1 auto;
}
.current-user-card__guest-text {
    margin: 0 0 8px;
    font-size: 1.5rem;
    color: #555;
}
.current-user-card__guest-actions {
    display: flex;
    gap: 10px;
}
</style>
