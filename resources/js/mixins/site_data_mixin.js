export const site_data = {
    get_data() {
        axios
        .get("/siteData/get_site_locale_data/"+localStorage.getItem('lang'))
        .then((response) => (
            Vue.prototype.$siteData = response.data.locale_data,
            Vue.prototype.$globalSiteData = response.data.global_data
        ));
    }
}