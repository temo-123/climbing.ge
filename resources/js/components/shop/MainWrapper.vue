<template>
    <div>
        <preloader />
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
    </div>
</template>

<script>
import navbar from "./items/navbars/NavbarComponent.vue";
import footter from "./items/FooterComponent";
import loginModal from "../global_components/LoginModalComponent.vue";
import go_to_top from "../global_components/GoOnTopComponent.vue";
import Preloader from "../global_components/PreloaderComponent.vue";

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
        Preloader,
        go_to_top
    },
    mounted() {},
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
    
    .navbar-inverse .navbar-collapse{
        border-color: #838383 !important;
    }

    .navbar-collapse {
        max-height: none !important;
    }
</style>