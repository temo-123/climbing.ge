<template>
    <div>
        <navbar @grade="grade_action"/>
        <div class="container top_menu_margin h-recent-work">
            <router-view />
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
import navbar from "./items/navbars/NavbarComponent";
import footter from "./items/FooterComponent";
import loginModal from "../global_components/LoginModalComponent.vue";

export default {
    data: function () {
        return {
            publicPath: window.location.protocol + '//' + window.location.hostname
        };
    },
    components: {
        navbar,
        footter,
        loginModal
    },
    mounted() {
        
    },

    methods: {
        grade_action(event){
            this.$emit('grade', event)
        }
    },
};
</script>
