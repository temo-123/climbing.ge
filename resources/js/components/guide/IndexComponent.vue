<template>
    <div>
        <preloader />
        <routeloader v-if="isRouteLoading"/>
        <navbar @grade="grade_action"/>
        <div class="container top_menu_margin h-recent-work">
            <router-view />
            <go_to_top />
        </div>

        <div
            class="footer__graphic"
            :style="'background-image: url(' + publicPath + '/public/images/svg/mountains.svg)'"
        ></div>
        <footter />
        <loginModal />
        <go_to_top />
        <toast-notification />
    </div>
</template>

<script>
    import navbar from "./items/navbars/NavbarComponent";
    import footter from "./items/FooterComponent";
    import loginModal from "../global_components/modals/LoginModalComponent.vue";
    import go_to_top from "../global_components/GoOnTopComponent.vue";

    import preloader from "../global_components/loaders/PreloaderComponent.vue";
    import routeloader from "../global_components/loaders/RouteloaderComponent.vue";

    import { isRouteLoading } from '../../store/routeLoader'
    import { useAuthStore } from "../../store/auth.js";
    export default {
        data: function () {
            return {
                publicPath: window.location.protocol + '//' + window.location.hostname
            };
        },
        computed: {
            isRouteLoading() {
                return isRouteLoading.value
            }
        },
        components: {
            navbar,
            footter,
            loginModal,
            preloader,
            go_to_top,
            routeloader
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

        methods: {
            grade_action(event){
                this.$emit('grade', event)
            }
        },
    };

</script>

<style>         
    .navbar {
        background-color: #279fbb !important;
        border-color: #6a8991;
    }

    .navbar-inverse .navbar-nav>.open>a{
        background-color: #2f869b !important;
    }

    .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover{
        background-color: #287386 !important;
    }

    .navbar-inverse .navbar-collapse{
        border-color: #838383;
    }
    /* .navbar-inverse .navbar-nav>.open>a{
        background-color: #23788c !important;
    } */
</style>