<template>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="row">
                <h2 class='index_h2'>{{ $t('global.services') }}</h2>
                <div class="bar"><i class="fa fa-window-restore" aria-hidden="true"></i></div>
                <h3 class="article_list_short_descriptio"> <span v-html="this.$siteData.data.services"></span> </h3>

            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4 col-xs-12 text-center service-item" @click="go_to_service('guid')">
                <div class="row service_icon">
                    <i class="fa fa fa-map" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>{{ $t('guide.name') }}</h2>
                    <span v-html="this.$siteData.data.guid_short_description"></span>
                </div>
            </div>
            <div class="col-md-4 col-xs-12 text-center service-item" @click="go_to_service('shop')">
                <div class="row service_icon">
                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>{{ $t('shop.name') }}</h2>
                    <span v-html="this.$siteData.data.shop_short_description"></span>
                </div>
            </div>
            <div class="col-md-4 col-xs-12 text-center service-item" @click="go_to_service('blog')">
                <div class="row service_icon" >
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>{{ $t('blog.name') }}</h2>
                    <span v-html="this.$siteData.data.blog_short_description"></span>
                </div>
            </div>
            <!-- <div class="col-md-4 text-center" @click="go_to_service('films')">
                <div class="row service_icon">
                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>Climbing Films</h2>
                    <p>{{ film_short_description }}</p>
                </div>
            </div> -->
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
                if(service == "blog"){
                    if(window.location.href == this.MIX_APP_SSH + 'blog.' + this.MIX_SITE_URL + '/about_us'){
                        this.$router.push({name: 'blog_index'});
                    }
                    else{
                        window.open(this.MIX_APP_SSH + 'blog.' + this.MIX_SITE_URL + '/')
                    }
                }
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
                // else if(service == "films"){
                //     if(window.location.href == this.MIX_APP_SSH + 'films.' + this.MIX_SITE_URL + '/about_us'){
                //         this.$router.push({name: 'studia'});
                //     }
                //     else{
                //         window.open(this.MIX_APP_SSH  + 'films.' + this.MIX_SITE_URL + '/')
                //     }
                // }
            },
        }
    }
</script>

<style>
    .service-item {
        padding: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-item:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .service_icon i {
        font-size: 200%;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .service_icon i:hover {
        color: #007bff;
    }

    @media (max-width: 768px) {
        .service_icon i {
            font-size: 150%;
        }
    }
</style>
