import { defineStore } from 'pinia';

export const useDataTableTabsStore = defineStore('dataTableTabs', {
    state: () => ({
        tabPages: {},
    }),

    getters: {
        getTabPage: (state) => (tabId) => state.tabPages[tabId] || 1,
    },

    actions: {
        setTabPage(tabId, page) {
            this.tabPages[tabId] = page;
        },
        resetTabPage(tabId) {
            delete this.tabPages[tabId];
        },
    },
});
