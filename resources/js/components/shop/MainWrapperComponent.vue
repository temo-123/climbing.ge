<template>
    <div>
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
    </div>
</template>

<script>
import navbar from "./items/navbars/NavbarComponent.vue";
import footter from "./items/FooterComponent";
import loginModal from "../global_components/LoginModalComponent.vue";

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
        loginModal
    },
    mounted() {},
    methods: {},
};
</script>
