<template>
    <!-- How It Works -->
    <div style="margin-top: 50px">
        <h2 class="index_h2" style="font-size: 200%">{{ $t('summit.how.title') }}</h2>
        <!-- <div class="bar"><i class="fa fa-lightbulb-o"></i></div> -->

        <div class="row text-center" style="margin-top: 30px">
            <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                <!-- <div class="how-step-number">1</div> -->
                <div class="bar">
                    <i class="fa fa-map-marker"></i>
                </div>
                <h4>{{ $t('summit.how.reach') }}</h4>
                <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_reach_desc"></p>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                <!-- <div class="how-step-number">2</div> -->
                <div class="bar">
                    <i class="fa fa-qrcode"></i>
                </div>
                <h4>{{ $t('summit.how.scan') }}</h4>
                <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_scan_desc"></p>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                <!-- <div class="how-step-number">3</div> -->
                <div class="bar">
                    <i class="fa fa-check-circle"></i>
                </div>
                <h4>{{ $t('summit.how.validate') }}</h4>
                <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_validate_desc"></p>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 30px">
                <!-- <div class="how-step-number">4</div> -->
                <div class="bar">
                    <i class="fa fa-share-alt"></i>
                </div>
                <h4>{{ $t('summit.how.share') }}</h4>
                <p style="font-size:14px; color:#666" v-html="$siteData.data.summit_how_share_desc"></p>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "HowItWorks",
    }
</script>

<style scoped>
/* .how-step-number {
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
} */
</style>