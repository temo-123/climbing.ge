export default {
    methods: {
        go_to_service(service){
            if(service == "shop"){
                if(window.location.href == this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '/about_us'){
                    this.$router.push({name: 'catalog'});
                }
                else{
                    window.open(this.MIX_APP_SSH + 'shop.' + this.MIX_SITE_URL + '')
                }
            }
            else if(service == "guid"){
                if(window.location.href == this.MIX_APP_SSH + this.MIX_SITE_URL + '/about_us'){
                    this.$router.push({name: 'index'});
                }
                else{
                    window.open(this.MIX_APP_SSH  + this.MIX_SITE_URL + '/')
                }
            }
            else if(service == "blog"){
                if(window.location.href == this.MIX_APP_SSH + 'blog.' + this.MIX_SITE_URL + '/about_us'){
                    this.$router.push({name: 'studia'});
                }
                else{
                    window.open(this.MIX_APP_SSH  + 'blog.' + this.MIX_SITE_URL + '/')
                }
            }
            else if(service == "films"){
                if(window.location.href == this.MIX_APP_SSH + 'films.' + this.MIX_SITE_URL + '/about_us'){
                    this.$router.push({name: 'studia'});
                }
                else{
                    window.open(this.MIX_APP_SSH  + 'films.' + this.MIX_SITE_URL + '/')
                }
            }
        },
    }
};