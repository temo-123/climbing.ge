<template>
    <div class="cg-admin">
        <preloader />
        <routeloader />
        <navbar />

        <main class="cg-admin__main">
            <div class="cg-admin__content">
                <router-view />
            </div>
            <footer class="cg-admin__footer">
                <footerText />
            </footer>
        </main>
        <go_to_top />
        <toast-notification />
        <welcomeQuestions />
    </div>
</template>

<script>
    import navbar from "./items/navbars/NavbarComponent.vue";
    import go_to_top from "../global_components/GoOnTopComponent.vue";
    import footerText from "../global_components/FooterBottomTextComponent.vue";
    import welcomeQuestions from "./items/preferences/WelcomeQuestionsComponent.vue";

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
            go_to_top,
            footerText,
            welcomeQuestions,
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
