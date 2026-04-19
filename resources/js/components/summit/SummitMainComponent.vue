<template>
    <div>
        <preloader />
        <routeloader />
         <navbar />
        <navbar />
        <div class="container top_menu_margin h-recent-work">
            <router-view /> 
        </div>

        <div class="footer__graphic" :style="'background-image: url(../images/svg/mountains.svg)'"></div>
        <footter />
        <loginModal />
        <go_to_top />        
    </div>
</template>

<script>
    import navbar from './items/NavbarComponent.vue'
    import footter from './items/FooterComponent.vue'
    import loginModal from "../global_components/modals/LoginModalComponent.vue";
    import go_to_top from "../global_components/GoOnTopComponent.vue";

    import preloader from "../global_components/loaders/PreloaderComponent.vue";
    import routeloader from "../global_components/loaders/RouteloaderComponent.vue";

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
        }
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
