<template>
    <li class="nav-badge-li">
        <a class="nav-badge-btn" @click="goToCart" title="Cart">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            <span class="nav-badge" v-if="isLoggedIn && cartCount > 0">{{ cartCount > 99 ? '99+' : cartCount }}</span>
        </a>
    </li>
    <li class="nav-badge-li" ref="userLi">
        <a class="nav-badge-btn" @click="handleUserClick" :title="isLoggedIn ? 'My account' : 'Login'">
            <i class="fa fa-user-circle" aria-hidden="true"></i>
            <span class="nav-badge nav-badge-alert" v-if="isLoggedIn && notifCount > 0">{{ notifCount }}</span>
        </a>
        <div class="nb-dropdown" v-if="isUserPage && isLoggedIn && userMenuOpen" @click.stop>
            <router-link :to="'/options'" class="nb-dropdown-item">{{ $t('user.menu.options') }}</router-link>
            <router-link :to="'/my_comments_and_reviews'" class="nb-dropdown-item">{{ $t('user.menu.my comments') }}</router-link>
            <router-link :to="'/purchases'" class="nb-dropdown-item">{{ $t('user.menu.my purchases') }}</router-link>
            <router-link :to="'/my_ascents'" class="nb-dropdown-item">{{ $t('user.menu.my ascents') }}</router-link>
            <router-link :to="'/my_donations'" class="nb-dropdown-item">{{ $t('user.menu.my donations') }}</router-link>
            <div class="nb-dropdown-divider"></div>
            <a class="nb-dropdown-item" href="#" @click.prevent="logout">{{ $t('user.menu.logout') }}</a>
        </div>
    </li>
</template>

<script>
export default {
    data() {
        return {
            cartCount: 0,
            notifCount: 0,
            _isLoggedIn: false,
            userMenuOpen: false,
        }
    },
    computed: {
        isLoggedIn() {
            return this._isLoggedIn
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
            axios.get('auth_user')
                .then(response => {
                    if (response.data && response.data.id) {
                        const wasLoggedOut = !this._isLoggedIn
                        this._isLoggedIn = true
                        if (wasLoggedOut || forceCountRefresh) this.fetchCounts()
                    } else {
                        this._isLoggedIn = false
                        this.cartCount = 0
                        this.notifCount = 0
                        this.clearLocalTokens()
                    }
                })
                .catch(() => {
                    this._isLoggedIn = false
                    this.cartCount = 0
                    this.notifCount = 0
                    this.clearLocalTokens()
                })
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
            axios.get('auth_user')
                .then(response => {
                    const user = response.data
                    if (!user || !user.id) { this.notifCount = 0; return }
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
                const response = await axios.get('auth_user')
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
                    this.$bus.$emit('logged-out')
                    this.goToLogin()
                })
        },
    }
}
</script>

<style scoped>
.nav-badge-li {
    display: inline-block;
    vertical-align: middle;
    list-style: none;
    position: relative;
}
.nav-badge-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 15px 10px;
    cursor: pointer;
    color: #fff !important;
    font-size: 1.1em;
    text-decoration: none !important;
    line-height: 20px;
    background: transparent !important;
}
@media (max-width: 992px) {
    .nav-badge-li {
        display: block;
    }
}
.nav-badge-btn:hover {
    opacity: 0.75;
}
.nav-badge {
    position: absolute;
    top: 6px;
    right: 2px;
    background: #007bff;
    color: #fff;
    border-radius: 50%;
    font-size: 0.55em;
    min-width: 17px;
    height: 17px;
    line-height: 17px;
    text-align: center;
    padding: 0 3px;
    font-weight: bold;
    pointer-events: none;
}
.nav-badge-alert {
    background: #e74c3c;
}
.nb-dropdown {
    position: absolute;
    right: 0;
    top: 100%;
    z-index: 1000;
    background: #fff;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 4px;
    min-width: 180px;
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
    padding: 5px 0;
}
.nb-dropdown-item {
    display: block;
    padding: 6px 20px;
    color: #333 !important;
    font-size: 0.95em;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}
.nb-dropdown-item:hover {
    background: #f5f5f5;
    color: #111 !important;
    opacity: 1;
}
.nb-dropdown-divider {
    height: 1px;
    margin: 5px 0;
    background: #e5e5e5;
}
</style>
