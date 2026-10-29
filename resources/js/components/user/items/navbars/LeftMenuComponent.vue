<template>
    <aside class="cg-sidebar" :class="{ 'open': menu_position, 'animate': animate_enabled }" :aria-hidden="!menu_position">
        <div class="cg-sidebar__head">
            <cg-brand service="user" :to="{ name: 'home' }" />
            <button type="button" class="cg-sidebar__close" @click="set_menu_position(false)" :aria-label="$t('user.nav.menu_header')">
                <i class="fa fa-angle-double-left" aria-hidden="true"></i>
            </button>
        </div>

        <router-link v-if="user" :to="{ name: 'options' }" class="cg-sidebar__user">
            <span class="cg-avatar">
                <img loading="lazy" decoding="async" v-if="user.image" :src="'/images/user_profil_img/' + user.image" :alt="user.name">
                <span v-else>{{ initials }}</span>
            </span>
            <span class="cg-sidebar__user-text">
                <strong>{{ user.name }} {{ user.surname }}</strong>
                <small>{{ user.email }}</small>
            </span>
        </router-link>

        <div class="cg-sidebar__body">
            <admin-menu-tree />
        </div>
    </aside>
</template>

<script>
    import { useAuthStore } from '../../../../store/auth.js'
    import AdminMenuTree from './AdminMenuTreeComponent.vue'

    // Desktop sidebar of the user/admin panel. Pages opt in by rendering
    // <left-menu /> (global component); the top bar shows its toggle only while
    // one is mounted (left-menu-mounted / left-menu-unmounted bus events).
    export default {
        name: 'leftMenu',
        components: {
            'admin-menu-tree': AdminMenuTree,
        },
        data(){
            return {
                menu_position: false,
                animate_enabled: false,
            }
        },
        computed: {
            user() {
                return useAuthStore().user
            },
            initials() {
                const u = this.user || {}
                return ((u.name || '').charAt(0) + (u.surname || '').charAt(0)).toUpperCase() || '?'
            },
        },
        mounted() {
            this.$bus.$emit('left-menu-mounted');
            this.syncMenuPosition();

            const store = useAuthStore()
            if (!store.user && localStorage.getItem('auth_token')) store.fetchUser()

            this._onToggle = () => this.set_menu_position(!this.menu_position)
            this.$bus.$on('sidebar-toggle', this._onToggle)

            this._onResize = () => {
                if (window.innerWidth <= 993 && this.menu_position) this.set_menu_position(false, false)
            }
            window.addEventListener('resize', this._onResize)
        },

        beforeUnmount() {
            this.$bus.$emit('left-menu-unmounted');
            this.$bus.$off('sidebar-toggle', this._onToggle);
            window.removeEventListener('resize', this._onResize)
            document.body.classList.remove('sidebar-open');
        },

        watch: {
            $route() {
                this.$nextTick(() => this.apply_sidebar_margin(this.menu_position));
            }
        },

        methods: {
            syncMenuPosition() {
                if (window.innerWidth > 993) {
                    if (!localStorage.getItem('left_menu_position')) {
                        localStorage.setItem('left_menu_position', 'true');
                        this.menu_position = true;
                    } else {
                        this.menu_position = (localStorage.getItem('left_menu_position') === 'true');
                    }
                } else {
                    this.menu_position = false;
                }
                this.apply_sidebar_margin(this.menu_position);
                this.$bus.$emit('sidebar-state', this.menu_position);
            },

            apply_sidebar_margin(open) {
                // Clear inline styles that full-screen editor pages set directly on body/navbar,
                // otherwise they override the sidebar-open CSS class when navigating back.
                document.body.style.marginLeft = '';
                const navbar = document.querySelector('.admin_page_header_navbar');
                if (navbar) navbar.style.marginLeft = '';

                if (open && window.innerWidth > 993) {
                    document.body.classList.add('sidebar-open');
                } else {
                    document.body.classList.remove('sidebar-open');
                }
            },

            set_menu_position(open, remember = true) {
                this.animate_enabled = true;
                this.menu_position = open;
                if (remember) localStorage.setItem('left_menu_position', open);
                this.apply_sidebar_margin(open);
                this.$bus.$emit('sidebar-state', open);
                setTimeout(() => {
                    this.animate_enabled = false;
                }, 450);
            },
        },
    }
</script>
