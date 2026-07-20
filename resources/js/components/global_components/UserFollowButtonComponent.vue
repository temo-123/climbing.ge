<template>
    <span class="user-follow-button" v-if="!is_self">
        <button
            v-if="!loading"
            class="btn btn-sm"
            :class="following ? 'btn-outline-secondary' : 'btn-primary'"
            @click="handle_click()"
        >
            {{ following ? $t('global.follow.unfollow_btn') : $t('global.follow.follow_btn') }}
        </button>
    </span>
</template>

<script>
    export default {
        props: {
            userId: { type: [Number, String], required: true },
        },
        emits: ['changed', 'login-required', 'login-succeeded'],
        data() {
            return {
                following: false,
                loading: false,
                is_self: false,
            };
        },
        mounted() {
            if (localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token')) {
                this.get_status();
            }
        },
        methods: {
            get_status() {
                axios.get('set_user_follow/follow_status/' + this.userId)
                    .then(response => {
                        this.is_self = response.data.is_self;
                        this.following = response.data.following;
                    })
                    .catch(() => {});
            },
            handle_click() {
                const token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token');
                if (!token) {
                    this.$emit('login-required');
                    this.$bus.$emit('open-login-modal', () => {
                        this.toggle();
                        this.$emit('login-succeeded');
                    });
                    return;
                }
                this.toggle();
            },
            toggle() {
                this.loading = true;
                const request = this.following
                    ? axios.delete('set_user_follow/unfollow/' + this.userId)
                    : axios.post('set_user_follow/follow/' + this.userId);

                request
                    .then(response => {
                        this.following = response.data.following;
                        this.$emit('changed', this.following);
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 422) {
                            this.is_self = true; // just logged in as the profile's own owner
                        }
                    })
                    .finally(() => this.loading = false);
            },
        },
    };
</script>
