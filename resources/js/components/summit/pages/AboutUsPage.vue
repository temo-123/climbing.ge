<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.about.title') }}</h1>

                <div class="bar"><i class="fa fa-info-circle"></i></div>

                <h3 class="article_list_short_description" v-html="$siteData.data.summit_about_welcome"></h3>

                <!-- Feature cards -->
                <div class="row" style="margin-top: 40px">
                    <div class="col-md-4 text-center">
                        <div class="bar" style="width:60px; margin: 0 auto 16px;">
                            <i class="fa fa-flag-checkered"></i>
                        </div>
                        <h4>{{ $t('summit.about.explore_title') }}</h4>
                        <p class="h-recent-work" style="font-size:14px" v-html="$siteData.data.summit_about_explore_desc"></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bar" style="width:60px; margin: 0 auto 16px;">
                            <i class="fa fa-qrcode"></i>
                        </div>
                        <h4>{{ $t('summit.about.qr_title') }}</h4>
                        <p class="h-recent-work" style="font-size:14px" v-html="$siteData.data.summit_about_qr_desc"></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bar" style="width:60px; margin: 0 auto 16px;">
                            <i class="fa fa-users"></i>
                        </div>
                        <h4>{{ $t('summit.about.community_title') }}</h4>
                        <p class="h-recent-work" style="font-size:14px" v-html="$siteData.data.summit_about_community_desc"></p>
                    </div>
                </div>

                <!-- Mission -->
                <div class="row" style="margin-top: 20px">
                    <div class="col-md-12">
                        <p class="article_list_short_description" style="font-size:15px; line-height:1.8" v-html="$siteData.data.summit_about_mission"></p>
                    </div>
                </div>

                <!-- How It Works -->
                <div style="margin-top: 50px">
                    <h2 class="index_h2" style="font-size: 200%">{{ $t('summit.how.title') }}</h2>
                    <div class="bar"><i class="fa fa-lightbulb-o"></i></div>

                    <div class="row text-center" style="margin-top: 30px">
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                            <div class="how-step-number">1</div>
                            <div class="bar" style="width:60px; margin: 12px auto;">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <h4>{{ $t('summit.how.reach') }}</h4>
                            <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_reach_desc"></p>
                        </div>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                            <div class="how-step-number">2</div>
                            <div class="bar" style="width:60px; margin: 12px auto;">
                                <i class="fa fa-qrcode"></i>
                            </div>
                            <h4>{{ $t('summit.how.scan') }}</h4>
                            <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_scan_desc"></p>
                        </div>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                            <div class="how-step-number">3</div>
                            <div class="bar" style="width:60px; margin: 12px auto;">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <h4>{{ $t('summit.how.validate') }}</h4>
                            <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_validate_desc"></p>
                        </div>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                            <div class="how-step-number">4</div>
                            <div class="bar" style="width:60px; margin: 12px auto;">
                                <i class="fa fa-share-alt"></i>
                            </div>
                            <h4>{{ $t('summit.how.share') }}</h4>
                            <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_share_desc"></p>
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 30px; margin-bottom: 60px">
                    <router-link to="/summits/list" class="btn btn-default btn-send main-btn btn-lg">
                        <i class="fa fa-flag-checkered"></i> {{ $t('summit.about.view_list') }}
                    </router-link>
                </div>

            </div>
        </div>

        <messageComponent />

        <metaData
            :title="$t('summit.about.title') + ' – Summit Climbing Georgia'"
            :description="$t('summit.about.meta')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../items/MetaDataComponent.vue'
import messageComponent from '../../global_components/MessageComponent.vue'

export default {
    name: 'AboutUsPage',
    components: { metaData, messageComponent },
    mounted() {
        if (!this.$siteData.loaded) {
            this.get_site_data();
        }
    },
}
</script>

<style scoped>
.how-step-number {
    width: 40px;
    height: 40px;
    line-height: 40px;
    border-radius: 50%;
    background: #1d7a48;
    color: #fff;
    font-size: 18px;
    font-weight: bold;
    margin: 0 auto 8px;
    text-align: center;
}
</style>
