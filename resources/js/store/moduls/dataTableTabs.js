const state = {
    tabPages: {} // Stores current page for each tab by tab_id
};

const getters = {
    getCurrentPage: (state) => (tabId) => {
        return state.tabPages[tabId] || 1;
    }
};

const mutations = {
    SET_TAB_PAGE(state, { tabId, page }) {
        state.tabPages[tabId] = page;
    },
    RESET_TAB_PAGE(state, tabId) {
        if (state.tabPages[tabId]) {
            delete state.tabPages[tabId];
        }
    }
};

const actions = {
    saveTabPage({ commit }, { tabId, page }) {
        commit('SET_TAB_PAGE', { tabId, page });
    },
    resetTabPage({ commit }, tabId) {
        commit('RESET_TAB_PAGE', tabId);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};

