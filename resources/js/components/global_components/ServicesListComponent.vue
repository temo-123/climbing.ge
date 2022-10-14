<template>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="row">
                <h2 class='index_h2'>{{ $t('title service list') }}</h2>
                <div class="bar"><i class="fa fa-window-restore" aria-hidden="true"></i></div>
                <h3> <span v-html="this.$siteData.services"></span> </h3>

            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4 text-center" @click="go_to_service('guid')">
                <div class="row service_icon">
                    <i class="fa fa fa-map" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>Climbing Guidbook</h2>
                    <p>{{ guid_short_description }}</p>
                </div>
            </div>
            <div class="col-md-4 text-center" @click="go_to_service('shop')">
                <div class="row service_icon">
                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>Climbing shop</h2>
                    <p>{{ shop_short_description }}</p>
                </div>
            </div>
            <!-- <div class="col-md-3 text-center" @click="go_to_service('forum')">
                <div class="row service_icon" >
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>Forum</h2>
                    <p>{{ forum_short_description }}</p>
                </div>
            </div> -->
            <div class="col-md-4 text-center">
                <div class="row service_icon">
                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>Climbing Films</h2>
                    <p>{{ film_short_description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        methods: {
            go_to_service(service){
                // if(service == "forum"){
                //     if(window.location.href == this.MIX_APP_SSH + 'forum.' + this.MIX_SITE_URL + '/about_us'){
                //         this.$router.push({name: 'forum_index'});
                //     }
                //     else{
                //         window.open(this.MIX_APP_SSH + 'forum.' + this.MIX_SITE_URL + '/')
                //     }
                // }
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
                else if(service == "film"){
                    if(window.location.href == this.MIX_APP_SSH + 'film.' + this.MIX_SITE_URL + '/about_us'){
                        this.$router.push({name: 'index'});
                    }
                    else{
                        window.open(this.MIX_APP_SSH  + this.MIX_SITE_URL + '/')
                    }
                }
            },
        }
    }
</script>

<style>
    .service_icon i {
        font-size: 200%;
    }
</style>