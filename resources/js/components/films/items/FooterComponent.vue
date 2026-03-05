<template>
    <div class="footer"> 
        <div class="container"> 
            <div class="row">

                <div class="col-md-4 display-none-720px">
                    <div class="f-about" style='color: #fff;'>
                        <h4 class="footer_title">{{ $t('about us')}}</h4>
                        
                        <div class="footer_description_block">
                            <span v-html="this.$siteData.data.shop_short_description"></span>
                            <router-link :to="{name: 'about_films'}">More</router-link>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="f-links">
                        <h4 class="footer_title">{{ $t("links") }}</h4>
                        <ul class="footer_links">
                            <!-- <li><router-link :to="{name: 'services'}" exact> {{ $t('forum menu services') }} </router-link></li> -->
                            <li><router-link :to="{name: 'about_films'}" exact> {{ $t('forum menu about us')}} </router-link></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="row f-links container">
                        <div class="flick">
                            <h4 class="footer_title">{{ $t("share") }}</h4>
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
    import share from "../../global_components/ShareComponent.vue";
    import followComponent from '../../global_components/FollowComponent.vue'
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