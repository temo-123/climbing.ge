<template>
    <div>
        <navbar />

        <div class="container top_menu_margin">
            <div class="container bootstrap snippet mt-5">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import navbar from "./items/NavbarComponent.vue";

export default {
    data: function () {
        return {
            // search_query: '',
        };
    },
    components: {
        navbar,
    },
    mounted() {
        // console.log( this.authing_user());
    },

    watch: {
        $route(to, from) {
            window.scrollTo(0, 0);
        },
    },
};
</script>