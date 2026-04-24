<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.about.title') }}</h1>

                <div class="bar"><i class="fa fa-info-circle"></i></div>

                <h3 class="article_list_short_description">
                    {{ $t('summit.about.welcome') }}
                </h3>

                <div class="row" style="margin-top: 40px">
                    <div class="col-md-4 text-center">
                        <div class="bar" style="width:60px; margin: 0 auto 16px;">
                            <i class="fa fa-flag-checkered"></i>
                        </div>
                        <h4>Explore Summits</h4>
                        <p class="h-recent-work" style="font-size:14px">Browse our list of challenging peaks across Georgia.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bar" style="width:60px; margin: 0 auto 16px;">
                            <i class="fa fa-qrcode"></i>
                        </div>
                        <h4>QR Registration</h4>
                        <p class="h-recent-work" style="font-size:14px">Scan QR codes at summits to log your climbs instantly.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bar" style="width:60px; margin: 0 auto 16px;">
                            <i class="fa fa-users"></i>
                        </div>
                        <h4>Climber Community</h4>
                        <p class="h-recent-work" style="font-size:14px">Share experiences and connect with other climbers.</p>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px">
                    <div class="col-md-12">
                        <p class="article_list_short_description" style="font-size:15px; line-height:1.8">
                            {{ $t('summit.about.mission') }}
                        </p>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 30px; margin-bottom: 60px">
                    <router-link to="/summits/list" class="btn btn-default btn-send main-btn btn-lg">
                        <i class="fa fa-flag-checkered"></i> {{ $t('summit.about.view_list') }}
                    </router-link>
                </div>

            </div>
        </div>

        <metaData
            :title="$t('summit.about.title') + ' – Summit Climbing Georgia'"
            :description="$t('summit.about.meta')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../items/MetaDataComponent.vue'

export default {
    name: 'AboutUsPage',
    components: { metaData },
}
</script>
