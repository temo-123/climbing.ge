<template>
    <div>
        <preloader />
        <navbar />

        <div class="container top_menu_margin">
            <div class="bootstrap snippet mt-5">
                <router-view />
            </div>
        </div>
        <go_to_top />
    </div>
</template>

<script>
import navbar from "./items/navbars/NavbarComponent.vue";
import Preloader from "../global_components/PreloaderComponent.vue";
import go_to_top from "../global_components/GoOnTopComponent.vue";

import { mapState } from 'vuex'
export default {
    computed: mapState({
        arrayIndex: state => state.arrayIndex
    }),

    data: function () {
        return {
            // search_query: '',
        };
    },
    components: {
        navbar,
        Preloader,
        go_to_top
    },

    mounted() {
        this.get_user_data()

        document.querySelector('body').style.marginLeft = '0';
    },

    methods: {
        get_user_data: function(){
        axios
            .get("/get_user/get_auth_user_permissions/")
            .then(response => {
                this.$ability.update(
                    response.data
                )
            })
            .catch(
                // error => console.log(error)
            ); 
        },
    },

    watch: {
        $route(to, from) {
            window.scrollTo(0, 0);
            // this.get_user_data()
        },
    },
};
</script>

<style scoped>
    .top_menu {
        background-color: #7427bb !important;
    }

    .preloader_background {
        background: radial-gradient(circle, #8c31e0 0% 0%, #4c1c7a 100%) !important;
    }

    .navbar {
        background-color: #7427bb !important;
        border-color: #6a8991;
    }

    .navbar-inverse .navbar-nav>.open>a{
        background-color: #7427bb !important;
    }

    .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover{
        background-color: #7427bb !important;
    }

    .navbar-inverse .navbar-collapse{
        border-color: #838383 !important;
    }

    .navbar-collapse {
        max-height: none !important;
    }
</style>