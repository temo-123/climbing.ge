<template>
    <div class="footer"> 
        <div class="container"> 
            <footerLogo />
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about" :style='"color: #fff;"'>
                    
                        <h4 class="footer_title">{{ $t('global.footer.about us')}}</h4>

                        <div class="footer_description_block">
                            <span v-html="this.$siteData.shop_short_description"></span>

                            <router-link :to="{name: 'about_store'}" exact>{{ $t("global.footer.more") }}</router-link>
                        </div>
                    
                    </div>

                    <followComponent />
                </div>

                <div class="col-md-4 display-none-720px">
                    <div class="f-links">
                        <h4 class="footer_title">{{ $t('global.footer.links')}}</h4>
                        <ul class="footer_links">
                            <li><router-link :to="{name: 'catalog'}" exact> {{ $t('shop.menu.products')}} </router-link></li>
                            <li><router-link :to="{name: 'services'}" exact> {{ $t('shop.menu.services') }} </router-link></li>
                            <li><router-link :to="{name: 'about_store'}" exact> {{ $t('shop.menu.about us')}} </router-link></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t('global.footer.share')}}</h4>
                            
                            <!-- <share /> -->
                            
                        </div>
                    </div>
                </div>
            </div>
            <footerText />
        </div>
    </div>
</template>


<script>
    import followComponent from '../../global_components/FollowComponent.vue'
    // import share from '../../global_components/ShareComponent.vue'
    import footerLogo from "../../global_components/FooterLogoBlockComponent.vue";
    import footerText from "../../global_components/FooterBottomTextComponent.vue";

    export default {
        data: function () {
            return {
                num: 0,
                active_year: new Date().getFullYear(),
            };
        },
        components: {
            followComponent,
            // share,
            footerLogo,
            footerText
        },
        mounted() {
            // this.num = Math.floor(Math.random() * 11);
        },
        methods: {
            // follow()
        }
    }
</script>