<template>
    <div class="footer"> 
        <div class="container"> 
            <footerAd />
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about" style='color: #fff;'>
                        <h4 class="footer_title">{{ $t('about us')}}</h4>
                        
                        <div class="footer_description_block">
                            <span v-html="this.$siteData.shop_short_description"></span>
                        </div>
                            
                        <router-link :to="{name: 'about_store'}" exact>More</router-link>
                    </div>
                </div>

                <div class="col-md-4 display-none-720px">
                    <div class="f-links">
                        <h4 class="footer_title">{{ $t('links')}}</h4>
                        <ul>
                            <!-- <li> <a href="">{{ $t('shop menu products')}}</a> </li>
                            <li> <a href="">{{ $t('shop menu about us')}}</a> </li>
                            <li> <a href="">{{ $t('shop login')}}</a> </li> -->

                            
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t('share')}}</h4>
                            <share />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import share from '../../global_components/ShareComponent.vue'
    export default {
        data: function () {
            return {
                num: 0
            };
        },
        components: {
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