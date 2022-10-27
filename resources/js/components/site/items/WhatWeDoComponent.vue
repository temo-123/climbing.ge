<template>
    <div class="services" v-if="this.$siteData.map">
        <div class="container">

            <h2 class='index_h2'>{{ $t('title what we do') }}</h2>

            <div class="bar"><i class="fa fa-book"></i></div>
        
            <h3> {{this.$siteData.what_we_do_description}} </h3>
                
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <ul class="right-text">
                        <li>
                            <h4>
                                <router-link style="font-size: 1.5em;" :to="{name: 'outdoors'}" exact>
                                    <strong>{{ $t('title outdoor climbing')}}</strong>
                                    <img alt='Outdoor climbing in Georgia' class="index_category_right" src="/images/site_img/outdoor.png" height="auto">
                                </router-link>
                            </h4>

                            <div class="content-text">
                                <span v-html="this.$siteData.indoor_description"></span>
                            </div>
                        </li>
                        <div class="clearfix"></div>
                        <li>
                            <h4>
                                <router-link style="font-size: 1.5em;" :to="{name: 'indoors'}" exact> 
                                    <strong>{{ $t('title indoor climbing')}}</strong>
                                    <img alt='Indoor climbing' class="index_category_right" src="/images/site_img/indoor.png">
                                </router-link>
                            </h4>

                            <div class="content-text">
                                <span v-html="this.$siteData.indoor_description"></span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <ul class="left-text">
                        <li>
                            <h4>
                                <router-link :to="{name: 'mountaineerings'}" exact>
                                    <strong>{{ $t('title ice climbing')}}</strong>
                                    <img alt='Mountaineering in Georgia' class="index_category_left" src="/images/site_img/ice.png">
                                </router-link>
                            </h4>

                            <div class="content-text">
                                <span v-html="this.$siteData.ice_description"></span>
                            </div>
                        </li>
                        <div class="clearfix"></div>
                        <li>
                            <h4>
                                <router-link :to="{name: 'ices'}" exact>
                                    <strong>{{ $t('title mountaineering')}}</strong>
                                    <img alt='Ice climbing in Georgia' class="index_category_left" src="/images/site_img/mount.png">
                                </router-link>
                            </h4>

                            <div class="content-text">
                                <span v-html="this.$siteData.mount_description"></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>