<template>
    <div>
        <preloader />
        <routeloader />
        <navbar />

        <div class="container top_menu_margin ">
            <div class="bootstrap snippet mt-5">
                <router-view />
            </div>
        </div>
        <go_to_top />
    </div>
</template>

<script>
    import navbar from "./items/navbars/NavbarComponent.vue";
    import go_to_top from "../global_components/GoOnTopComponent.vue";

    import preloader from "../global_components/loaders/PreloaderComponent.vue";
    import routeloader from "../global_components/loaders/RouteloaderComponent.vue";

    export default {

        data: function () {
            return {
                menuOpen: localStorage.getItem('left_menu_position') === 'true',
            };
        },

        components: {
            navbar,
            preloader,
            routeloader,
            go_to_top
        },

        mounted() {
            if (!this.$ability) {
                this.$nextTick(() => this.get_user_data());
                return;
            }

            const storedPermissions = localStorage.getItem('user_permissions')
            if (storedPermissions) {
                try {
                    const permissions = JSON.parse(storedPermissions)
                    if (permissions && permissions.length > 0) {
                        this.$ability.update(permissions)
                        this.get_user_data()
                        return
                    }
                } catch (e) {
                    // fall through to fresh fetch
                }
            }

            this.get_user_data();

            // Listen for permissions loaded after login so left menu updates without page refresh
            this.$bus.$on('permissions-loaded', this.on_permissions_loaded)
        },

        beforeUnmount() {
            this.$bus.$off('permissions-loaded', this.on_permissions_loaded)
        },

        methods: {
            toggleMenu() {
                this.menuOpen = !this.menuOpen;
                localStorage.setItem('left_menu_position', this.menuOpen);
                this.$bus.$emit('menu-toggle');
            },

            on_permissions_loaded(permissions) {
                if (this.$ability) {
                    this.$ability.update(permissions)
                }
                localStorage.setItem('user_permissions', JSON.stringify(permissions))
            },

            get_user_data() {
                axios
                    .get('get_user/get_auth_user_permissions/')
                    .then(response => {
                        this.$ability.update(response.data)
                        localStorage.setItem('user_permissions', JSON.stringify(response.data))
                        this.$bus.$emit('permissions-loaded', response.data)
                    })
                    .catch(() => {});
            },
        },

        watch: {
            $route() {
                window.scrollTo(0, 0);
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
