<template>
    <div>
        <navbar />
        <div class="container top_menu_margin h-recent-work">
            <router-view />
        </div>
        <div
            class="footer__graphic"
            :style="'background-image: url(../images/svg/mountains.svg)'"
        ></div>
        <footter />
    </div>
</template>

<script>
import navbar from "./items/NavbarComponent";
import footter from "./items/FooterComponent";

export default {
    data: function () {
        return {
            // cart_products: [],
        };
    },
    components: {
        navbar,
        footter,
    },
    mounted() {},
    methods: {},
};
</script>
