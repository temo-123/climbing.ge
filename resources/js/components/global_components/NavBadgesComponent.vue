<template>
    <li class="nav-badge-li">
        <a class="cg-icon-btn nav-badge-btn" @click="goToCart" :title="$t('user.nav.cart')">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            <span class="nav-badge" v-if="isLoggedIn && cartCount > 0">{{ cartCount > 99 ? '99+' : cartCount }}</span>
        </a>
    </li>
    <li class="nav-badge-li" ref="userLi">
        <a class="cg-icon-btn nav-badge-btn nav-badge-user" :class="{ 'is-logged': isLoggedIn }" @click="handleUserClick" :title="isLoggedIn ? $t('user.nav.my_account') : $t('common.login')">
            <!-- logged in with a profile photo: show it; otherwise the user icon -->
            <img
                v-if="isLoggedIn && avatarUrl"
                class="nav-avatar"
                :src="avatarUrl"
                :alt="userName"
                data-no-fallback
                @error="avatarFailed = true"
            />
            <i v-else class="fa fa-user-circle" aria-hidden="true"></i>
            <span class="nav-badge nav-badge-alert" v-if="isLoggedIn && notifCount > 0">{{ notifCount }}</span>
        </a>
        <div class="nb-dropdown" v-if="isUserPage && isLoggedIn && userMenuOpen" @click.stop>
            <div class="cg-dropdown-label">{{ $t('user.nav.my_account') }}</div>
            <router-link :to="'/options'" class="nb-dropdown-item">{{ $t('user.menu.options') }}</router-link>
            <router-link :to="'/my_comments_and_reviews'" class="nb-dropdown-item">{{ $t('user.menu.my comments') }}</router-link>
            <router-link :to="'/purchases'" class="nb-dropdown-item">{{ $t('user.menu.my purchases') }}</router-link>
            <router-link :to="'/my_ascents'" class="nb-dropdown-item">{{ $t('user.menu.my ascents') }}</router-link>
            <router-link :to="'/my_trainings'" class="nb-dropdown-item">{{ $t('user.menu.my trainings') }}</router-link>
            <router-link :to="'/my_donations'" class="nb-dropdown-item">{{ $t('user.menu.my donations') }}</router-link>
            <router-link :to="'/my_routes_review'" class="nb-dropdown-item">{{ $t('user.menu.my routes reviews') }}</router-link>
            <div class="nb-dropdown-divider"></div>
            <a class="nb-dropdown-item nb-dropdown-item--danger" href="#" @click.prevent="logout"><i class="fa fa-sign-out" aria-hidden="true"></i>{{ $t('user.menu.logout') }}</a>
        </div>
    </li>
</template>

<script>
import { get_auth_user } from '../../services/authUser.js'

export default {
    data() {
        return {
            cartCount: 0,
            notifCount: 0,
            _isLoggedIn: false,
            userMenuOpen: false,
            userImage: '',
            userName: '',
            avatarFailed: false,
        }
    },
    computed: {
        isLoggedIn() {
            return this._isLoggedIn
        },
        // profile photo: stored file name (uploads) or a full URL (social login)
        avatarUrl() {
            const img = this.userImage
            if (!img || this.avatarFailed) return ''
            if (/^(https?:)?\/\//.test(img)) return img
            return '/images/user_profil_img/' + img
        },
        isUserPage() {
            return window.location.hostname === process.env.MIX_USER_PAGE_URL
        },
    },
    mounted() {
        this.verifyAuth(true)

        this._onLoggedIn = () => {
            this._isLoggedIn = true
            this.fetchCounts()
        }
        this._onCartUpdated = () => {
            if (this._isLoggedIn) this.fetchCartCount()
        }
        this._onLoggedOut = () => {
            this._isLoggedIn = false
            this.cartCount = 0
            this.notifCount = 0
            this.userMenuOpen = false
            this.clearUser()
        }
        this._onDocClick = (e) => {
            if (this.$refs.userLi && !this.$refs.userLi.contains(e.target)) {
                this.userMenuOpen = false
            }
        }
        this._onVisibilityChange = () => {
            if (!document.hidden) this.verifyAuth()
        }

        this.$bus.$on('logged-in', this._onLoggedIn)
        this.$bus.$on('cart-updated', this._onCartUpdated)
        this.$bus.$on('logged-out', this._onLoggedOut)
        document.addEventListener('click', this._onDocClick)
        document.addEventListener('visibilitychange', this._onVisibilityChange)

        // Poll every 5 minutes so other-subdomain logouts are reflected
        // even when the user never switches away from this tab.
        this._authPollInterval = setInterval(() => this.verifyAuth(), 5 * 60 * 1000)
    },
    beforeUnmount() {
        this.$bus.$off('logged-in', this._onLoggedIn)
        this.$bus.$off('cart-updated', this._onCartUpdated)
        this.$bus.$off('logged-out', this._onLoggedOut)
        document.removeEventListener('click', this._onDocClick)
        document.removeEventListener('visibilitychange', this._onVisibilityChange)
        clearInterval(this._authPollInterval)
    },
    methods: {
        verifyAuth(forceCountRefresh = false) {
            get_auth_user()
                .then(response => {
                    if (response.data && response.data.id) {
                        const wasLoggedOut = !this._isLoggedIn
                        this._isLoggedIn = true
                        this.setUser(response.data)
                        if (wasLoggedOut || forceCountRefresh) this.fetchCounts()
                    } else {
                        this._isLoggedIn = false
                        this.cartCount = 0
                        this.notifCount = 0
                        this.clearUser()
                        this.clearLocalTokens()
                    }
                })
                .catch(() => {
                    this._isLoggedIn = false
                    this.cartCount = 0
                    this.notifCount = 0
                    this.clearUser()
                    this.clearLocalTokens()
                })
        },
        setUser(user) {
            if ((user.image || '') !== this.userImage) this.avatarFailed = false
            this.userImage = user.image || ''
            this.userName = [user.name, user.surname].filter(Boolean).join(' ')
        },
        fetchCounts() {
            this.fetchCartCount()
            this.fetchNotifCount()
        },
        fetchCartCount() {
            axios.get('cart')
                .then(response => {
                    if (Array.isArray(response.data)) {
                        this.cartCount = response.data.reduce((sum, item) => sum + (item.quantity || 1), 0)
                    }
                })
                .catch(() => {})
        },
        fetchNotifCount() {
            get_auth_user()
                .then(response => {
                    const user = response.data
                    if (!user || !user.id) { this.notifCount = 0; return }
                    this.setUser(user)
                    let count = 0
                    if (!user.email_verified_at) count++
                    if (!user.name || !user.surname) count++
                    if (!user.image) count++
                    this.notifCount = count
                })
                .catch(() => {})
        },
        handleUserClick() {
            if (this.isUserPage && this.isLoggedIn) {
                this.userMenuOpen = !this.userMenuOpen
            } else {
                this.goToUser()
            }
        },
        async goToCart() {
            // Always re-verify with the server before opening the cart.
            // The local _isLoggedIn flag can be stale after a logout on another
            // subdomain or tab — the server is the only reliable source of truth.
            try {
                const response = await get_auth_user({ force: true })
                if (!response.data || !response.data.id) throw new Error('unauthenticated')
                this._isLoggedIn = true
            } catch {
                this._isLoggedIn = false
                this.clearLocalTokens()
                this.goToLogin()
                return
            }
            if (this.isUserPage) {
                this.$router.push({ name: 'cart' })
            } else {
                window.open(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/cart')
            }
        },
        goToUser() {
            if (this.isUserPage) {
                this.$router.push({ name: 'home' })
            } else {
                window.open(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/')
            }
        },
        goToLogin() {
            if (this.isUserPage) {
                this.$router.push({ name: 'login' })
            } else {
                window.location.href = process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/login'
            }
        },
        clearUser() {
            this.userImage = ''
            this.userName = ''
            this.avatarFailed = false
        },
        clearLocalTokens() {
            localStorage.removeItem('auth_token')
            localStorage.removeItem('x_xsrf_token')
            localStorage.removeItem('user_permissions')
        },
        logout() {
            this.userMenuOpen = false
            axios.post('logout')
                .finally(() => {
                    this.clearLocalTokens()
                    this._isLoggedIn = false
                    this.cartCount = 0
                    this.notifCount = 0
                    this.clearUser()
                    this.$bus.$emit('logged-out')
                    this.goToLogin()
                })
        },
    }
}
</script>

<style scoped>
/* Header tool buttons get their shape from .cg-icon-btn (app.scss);
   only the counters and the account menu live here. */
.nav-badge-li {
    position: relative;
    list-style: none;
}
.nav-badge-user.is-logged {
    background: rgba(255, 255, 255, .1);
}
/* profile photo inside the round user button */
.nav-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 0 0 2px var(--cg-accent), 0 0 0 3px rgba(255, 255, 255, .25);
    background: var(--cg-chalk-2);
}
.nav-badge {
    position: absolute;
    top: 2px;
    right: 0;
    min-width: 18px;
    height: 18px;
    padding: 0 4px;
    border-radius: 9px;
    border: 2px solid #15212b;
    background: var(--cg-accent);
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    line-height: 14px;
    text-align: center;
    pointer-events: none;
}
.nav-badge-alert {
    background: var(--cg-rope);
}
.nb-dropdown {
    position: absolute;
    right: 0;
    top: calc(100% + .55rem);
    z-index: 1000;
    min-width: 230px;
    padding: .4rem;
    background: #fff;
    border: 1px solid var(--cg-line);
    border-radius: .9rem;
    box-shadow: var(--cg-shadow-lg);
}
.nb-dropdown-item {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .55rem .8rem;
    border-radius: .6rem;
    color: var(--cg-ink-2) !important;
    font-size: .92rem;
    font-weight: 600;
    white-space: nowrap;
    cursor: pointer;
    text-decoration: none !important;
}
.nb-dropdown-item:hover,
.nb-dropdown-item.router-link-exact-active {
    background: var(--cg-accent-soft);
    color: var(--cg-accent-dark) !important;
}
.nb-dropdown-item--danger:hover {
    background: #fdecea;
    color: #c0392b !important;
}
.nb-dropdown-divider {
    height: 1px;
    margin: .35rem .2rem;
    background: var(--cg-line);
}
@media (max-width: 1199.98px) {
    .nb-dropdown {
        left: 0;
        right: 0;
    }
}
</style>
