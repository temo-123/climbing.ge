<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.about.title') }}</h1>

                <div class="bar"><i class="fa fa-info-circle"></i></div>

                <h3 class="article_list_short_description" v-html="$siteData.data.summit_about_welcome"></h3>

                <!-- Mission -->
                <div class="row" style="margin-top: 20px">
                    <div class="col-md-12">
                        <p class="article_list_short_description" style="font-size:15px; line-height:1.8" v-html="$siteData.data.summit_about_mission"></p>
                    </div>
                </div>

                <how_summit_works />

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

    import how_summit_works from '../items/how_summit_works.vue'

    export default {
        name: 'AboutUsPage',
        components: { 
            metaData, 
            messageComponent,
            how_summit_works
        },
        mounted() {
            if (!this.$siteData.loaded) {
                this.get_site_data();
            }
        },
    }
</script>
