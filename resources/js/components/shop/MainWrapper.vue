<template>
    <div>
        <preloader />
        <navbar />
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
    </div>
</template>

<script>
import navbar from "./items/navbars/NavbarComponent.vue";
import footter from "./items/FooterComponent.vue";
import loginModal from "../global_components/LoginModalComponent.vue";
import go_to_top from "../global_components/GoOnTopComponent.vue";
<<<<<<< HEAD
=======
import Preloader from "../global_components/PreloaderComponent.vue";
>>>>>>> master

export default {
    data: function () {
        return {
            publicPath: window.location.protocol + '//' + window.location.hostname
        };
    },
    components: {
        navbar,
        footter,
        loginModal,
<<<<<<< HEAD
        go_to_top
    },
    mounted() {

    },
    methods: {
        
=======
        Preloader,
        go_to_top
>>>>>>> master
    },
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