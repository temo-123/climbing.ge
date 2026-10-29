import { defineStore } from 'pinia';
import { updateAbility } from '../services/ability/ability.js';
import { apply_user_preferences } from '../services/preferences.js';

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
                // account defaults (language, grade systems, animations) → this device
                apply_user_preferences(response.data);
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
