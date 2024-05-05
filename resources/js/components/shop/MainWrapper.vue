<template>
    <div>
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
    </div>
</template>

<script>
import navbar from "./items/navbars/NavbarComponent.vue";
import footter from "./items/FooterComponent.vue";
import loginModal from "../global_components/LoginModalComponent.vue";
import go_to_top from "../global_components/GoOnTopComponent.vue";

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
        go_to_top
    },
    mounted() {

    },
    methods: {
        
    },
};
</script>
