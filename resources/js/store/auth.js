import { defineStore } from 'pinia';
import { updateAbility } from '../services/ability/ability.js';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        isLoggedIn: false,
    }),

    getters: {
        getUser: (state) => state.user,
    },

    actions: {
        async fetchUser() {
            try {
                const response = await axios.get('auth_user');
                this.user = response.data;
                this.isLoggedIn = true;
                updateAbility(response.data.casl_permissions || []);
            } catch {
                this.user = null;
                this.isLoggedIn = false;
                updateAbility([]);
            }
        },

        clearUser() {
            this.user = null;
            this.isLoggedIn = false;
            updateAbility([]);
        },
    },
});
