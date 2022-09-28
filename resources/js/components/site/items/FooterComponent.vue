<template>
    <div class="footer"> 
        <div class="container"> 
            <footerAd />
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about container">
                        
                        <h4 class="footer_title">{{ $t('about us')}}</h4>
                        
                        <!-- {!!$site -> short_description!!} -->
                        {{this.$siteData.guid_short_description}}

                        <li><router-link :to="{name: 'about_us', params: {lang: this.$i18n.locale}}"> {{ $t('more') }} </router-link></li>
                        
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="f-links">
                        <h4 class="footer_title">{{ $t('links')}}</h4>
                        <ul>
                            <li><router-link :to="{name: 'indoors', params: {lang: this.$i18n.locale}}"> {{ $t('menu indoor climbing') }} </router-link></li>
                            <li><router-link :to="{name: 'outdoors', params: {lang: this.$i18n.locale}}"> {{ $t('menu outdoor climbing') }} </router-link></li>
                            <li><router-link :to="{name: 'mountaineerings', params: {lang: this.$i18n.locale}}"> {{ $t('menu mountaineering') }} </router-link></li>
                            <li><router-link :to="{name: 'ices', params: {lang: this.$i18n.locale}}"> {{ $t('menu ice climbing') }} </router-link></li>
                            <li><router-link :to="{name: 'others', params: {lang: this.$i18n.locale}}"> {{ $t('menu other') }} </router-link></li>
                            <li><router-link :to="{name: 'about_us', params: {lang: this.$i18n.locale}}"> {{ $t('menu about us') }} </router-link></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links container">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t('share')}}</h4>
                            
                            <share />
                        </div>
                    </div>
                    <followComponent />
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
    import followComponent from '../../global_components/FollowComponent.vue'
    import share from '../../global_components/ShareComponent.vue'
    export default {
        data: function () {
            return {
                num: 0
            };
        },
        components: {
            followComponent,
            share
        },
        mounted() {
            // this.num = Math.floor(Math.random() * 11);
        },
        methods: {
            // follow()
        }
    }
</script>
