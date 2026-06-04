const SSH = process.env.MIX_APP_SSH || 'http://';
const URLS = {
    shop:   SSH + (process.env.MIX_SHOP_URL   || ''),
    guid:   SSH + (process.env.MIX_GUIDBOOK_URL || process.env.MIX_SITE_URL || ''),
    summit: SSH + (process.env.MIX_SUMMIT_URL  || ''),
    blog:   SSH + (process.env.MIX_BLOG_URL    || ''),
    films:  SSH + (process.env.MIX_FILMS_URL   || ''),
};

export default {
    methods: {
        go_to_service(service){
            const url = URLS[service];
            if (!url) return;

            if(service == "shop"){
                if(window.location.origin === url){ this.$router.push({name: 'catalog'}); }
                else{ window.open(url); }
            }
            else if(service == "guid"){
                if(window.location.origin === url){ this.$router.push({name: 'index'}); }
                else{ window.open(url + '/'); }
            }
            else if(service == "summit"){
                if(window.location.origin === url){ this.$router.push({name: 'index'}); }
                else{ window.open(url + '/'); }
            }
            else if(service == "blog"){
                if(window.location.origin === url){ this.$router.push({name: 'index'}); }
                else{ window.open(url + '/'); }
            }
            else if(service == "films"){
                if(window.location.origin === url){ this.$router.push({name: 'index'}); }
                else{ window.open(url + '/'); }
            }
        },
    }
};

