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
        // Try to load permissions from localStorage first (set during login)
        const storedPermissions = localStorage.getItem('user_permissions')
        if (storedPermissions) {
            try {
                const permissions = JSON.parse(storedPermissions)
                if (permissions && permissions.length > 0 && this.$ability) {
                    this.$ability.update(permissions)
                    // Fetch fresh permissions in background
                    this.get_user_data()
                    return
                }
            } catch (e) {
                console.error('Error parsing stored permissions:', e)
            }
        }
        
        // Fallback: fetch permissions from server
        this.get_user_data()

        document.querySelector('body').style.marginLeft = '0';
        
        // Listen for permissions-loaded event from login (fixes issue where functions don't show after login without refresh)
        this.$root.$on('permissions-loaded', this.onPermissionsLoaded)
    },
    
    beforeUnmount() {
        // Clean up event listener to prevent memory leaks
        this.$root.$off('permissions-loaded', this.onPermissionsLoaded)
    },

    methods: {
        onPermissionsLoaded(permissions) {
            // Called when permissions are loaded after login
            // Refresh user data to update UI components that depend on permissions
            this.get_user_data()
            // Force re-render to update any permission-dependent components
            this.$forceUpdate()
        },
        
        get_user_data: function(){
        axios
            .get(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + "/api/get_user/get_auth_user_permissions/")
            .then(response => {
                // Update ability with fresh permissions
                this.$ability.update(
                    response.data
                )
                // Store in localStorage for future use
                localStorage.setItem('user_permissions', JSON.stringify(response.data))
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