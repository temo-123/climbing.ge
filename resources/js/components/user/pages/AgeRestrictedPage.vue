<template>
    <div class="cg-age-page">
        <div class="cg-age-page__card">
            <i class="fa fa-user-times cg-age-page__icon" aria-hidden="true"></i>

            <h1 class="cg-age-page__title">{{ $t('global.age_restriction.title', { age: min_age }) }}</h1>
            <div class="alert alert-warning text-start mb-3" role="alert">
                {{ $t('global.age_restriction.text', { age: min_age }) }}
            </div>
            <p class="small text-muted mb-3">{{ $t('global.age_restriction.wrong_birthday') }}</p>

            <div class="cg-age-page__actions">
                <button type="button" class="btn btn-primary" @click="logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i> {{ $t('global.age_restriction.logout') }}
                </button>
                <router-link :to="{ name: 'delete_account', params: { locale: $locale_param() } }" class="btn btn-outline-danger">
                    <i class="fa fa-trash-o" aria-hidden="true"></i> {{ $t('global.age_restriction.delete_account') }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { get_auth_user, forget_auth_user } from '../../../services/authUser.js'

// user.climbing.ge for a logged-in user younger than 13: the route guard in
// app.js sends every panel route here (only Delete account, Forgot password
// and the legal pages stay reachable). HomeComponent hides the menus here.
// docs/FUNCTIONS_DOCS/global/age_restriction.md
export default {
    data() {
        return { min_age: 13 }
    },
    mounted() {
        get_auth_user()
            .then(response => {
                if (response.data.min_age) this.min_age = response.data.min_age
                if (!response.data.is_underage) {
                    this.$router.push({ name: 'home', params: { locale: this.$locale_param() } })
                }
            })
            .catch(() => {})
    },
    methods: {
        logout() {
            axios.post('logout')
                .finally(() => {
                    forget_auth_user()
                    localStorage.removeItem('x_xsrf_token')
                    localStorage.removeItem('user_permissions')
                    localStorage.removeItem('auth_token')
                    if (this.$ability) this.$ability.update([])
                    this.$bus.$emit('logged-out')
                    this.$router.push({ name: 'login' })
                })
        },
    },
}
</script>

<style scoped>
.cg-age-page {
    display: flex;
    justify-content: center;
    padding: 48px 16px;
}
.cg-age-page__card {
    width: 100%;
    max-width: 560px;
    padding: 32px 28px;
    text-align: center;
    background: var(--cg-surface, #fff);
    border: 1px solid var(--cg-line, #e5e5e5);
    border-radius: var(--cg-r-lg, 12px);
    box-shadow: var(--cg-shadow, 0 4px 24px rgba(0, 0, 0, .08));
}
.cg-age-page__icon {
    font-size: 3.2rem;
    color: var(--cg-accent);
}
.cg-age-page__title {
    margin: 12px 0 16px;
    font-family: var(--cg-font-display, inherit);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--cg-ink);
}
.cg-age-page__actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
}
@media (max-width: 575.98px) {
    .cg-age-page__card { padding: 24px 16px; }
    .cg-age-page__actions .btn { width: 100%; }
}
</style>
