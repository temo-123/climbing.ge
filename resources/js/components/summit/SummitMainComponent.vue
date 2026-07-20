<template>
    <div>
        <preloader />
        <routeloader />
        <navbar />
        <div class="top_menu_margin">
            <router-view />
        </div>

        <div class="footer__graphic" :style="'background-image: url(../images/svg/mountains.svg)'"></div>
        <footter />
        <loginModal />
        <go_to_top />
        <toast-notification />
    </div>
</template>

<script>
    import navbar from './items/NavbarComponent.vue'
    import footter from './items/FooterComponent.vue'
    import loginModal from "../global_components/modals/LoginModalComponent.vue";
    import go_to_top from "../global_components/GoOnTopComponent.vue";

    import preloader from "../global_components/loaders/PreloaderComponent.vue";
    import routeloader from "../global_components/loaders/RouteloaderComponent.vue";
    import { useAuthStore } from "../../store/auth.js";

    export default {
        data () {
            return {
            };
        },
        components: {
            navbar,
            footter,
            loginModal,
            preloader,
            routeloader,
            go_to_top
        },
        async mounted() {
            // localStorage is per-subdomain — logging in on user.climbing.ge
            // (or any other subdomain) leaves this subdomain's own localStorage
            // empty even though the same Sanctum session cookie is shared
            // across every subdomain. Recover a Bearer token off that shared
            // cookie before concluding no one's logged in (same fix as
            // shop/MainWrapper.vue and the user subdomain's router guard in app.js).
            let token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token');

            if (!token) {
                try {
                    const raw = await axios.get('token', { _tokenRecovery: true });
                    token = String(raw.data).trim();
                    if (token) localStorage.setItem('auth_token', token);
                } catch {
                    // No valid session cookie either — a genuinely anonymous visitor.
                }
            }

            if (token) {
                useAuthStore().fetchUser();
            }
        },
    }
</script>

<style scoped>
    .top_menu {
        background-color: #522f92 !important;
    }

    .preloader_background {
        background: radial-gradient(circle, #522f92 0% 0%, #7B55B3 100%) !important;
    }

    .loader {
        border-top-color: #522f92 !important;
    }

    .navbar {
        background-color: #522f92 !important;
        border-color: #6A3FA0;
    }

    .navbar-inverse .navbar-nav>.open>a{
        background-color: #522f92 !important;
    }

    .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover{
        background-color: #522f92 !important;
    }
</style>
