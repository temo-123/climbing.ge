<template>
    <div>
        <preloader />
        <routeloader />
        <navbar />
        <div class="container top_menu_margin h-recent-work">
            <router-view />
        </div>
        <div
            class="footer__graphic"
            :style="'background-image: url(' + this.publicPath + '/public/images/svg/mountains.svg)'"
        ></div>
        <footter />
        <loginModal />
        <go_to_top />
        <toast-notification />
    </div>
</template>

<script>
    import navbar from "./items/navbars/NavbarComponent.vue";
    import footter from "./items/FooterComponent";
    import loginModal from "../global_components/modals/LoginModalComponent.vue";
    import go_to_top from "../global_components/GoOnTopComponent.vue";

    import preloader from "../global_components/loaders/PreloaderComponent.vue";
    import routeloader from "../global_components/loaders/RouteloaderComponent.vue";
    import { useAuthStore } from "../../store/auth.js";
    // toast-notification is already globally registered in app.js — no local
    // import/registration needed, the tag below resolves to it directly.

    export default {
        data: function () {
            return {
                // is_add_social_link_model: false,

                publicPath: window.location.protocol + '//' + window.location.hostname
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
            // Unlike user.climbing.ge, the shop subdomain never fetches CASL
            // permissions on its own — app.js only does that inside its
            // user-subdomain-specific router guard. That left $can() stuck at
            // its empty default here, so any admin-only shop UI (like the wall
            // calculator's PDF export button) could never show for anyone.
            let token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token');

            // localStorage is per-subdomain — logging in on user.climbing.ge
            // (or climbing.ge) leaves shop.climbing.ge's own localStorage
            // empty even though the same Sanctum session cookie is shared
            // across every subdomain. Same recovery app.js's user-subdomain
            // guard already does: ask for a fresh Bearer token off that
            // shared cookie before concluding no one's logged in.
            if (!token) {
                try {
                    const raw = await axios.get('token', { _tokenRecovery: true });
                    token = String(raw.data).trim();
                    if (token) localStorage.setItem('auth_token', token);
                } catch {
                    // No valid session cookie either — a genuinely anonymous
                    // shopper, which is fine, this is a public storefront.
                }
            }

            if (token) {
                useAuthStore().fetchUser();
            }
        },
        methods: {},
    };
</script>

<style scoped>
    .top_menu {
        background-color: #27bb7d !important;
    }

    .preloader_background {
        background: radial-gradient(circle, #27bb7d 0% 0%, #1d8a5d 100%) !important;
    }

    .loader {
        border-top-color: #27bb7d;
    }

    .navbar {
        background-color: #09bb80 !important;
        border-color: #6a8991;
    }

    .navbar-inverse .navbar-nav>.open>a{
        background-color: #0e9f6f !important;
    }

    .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover{
        background-color: #0c8a60 !important;
    }

    .navbar-inverse .navbar-nav>.open>a{
        background-color: #23788c;
    }
    

</style>