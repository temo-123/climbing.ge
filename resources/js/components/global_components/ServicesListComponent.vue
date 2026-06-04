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
            <div class="col-md-3 col-xs-6 text-center service-item" @click="go_to_service('guid')">
                <div class="row service_icon">
                    <i class="fa fa fa-map" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>{{ $t('guide.name') }}</h2>
                    <span v-html="this.$siteData.data.guid_short_description"></span>
                </div>
            </div>
            <div class="col-md-3 col-xs-6 text-center service-item" @click="go_to_service('shop')">
                <div class="row service_icon">
                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>{{ $t('shop.name') }}</h2>
                    <span v-html="this.$siteData.data.shop_short_description"></span>
                </div>
            </div>
            <div class="col-md-3 col-xs-6 text-center service-item" @click="go_to_service('blog')">
                <div class="row service_icon" >
                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>{{ $t('blog.name') }}</h2>
                    <span v-html="this.$siteData.data.blog_short_description"></span>
                </div>
            </div>
            <div class="col-md-3 col-xs-6 text-center service-item" @click="go_to_service('summit')">
                <div class="row service_icon" >
                    <i class="fa fa-street-view" aria-hidden="true"></i>
                </div>
                <div class="row">
                    <h2>{{ $t('summit.name') }}</h2>
                    <span v-html="this.$siteData.data.summit_short_description"></span>
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
    import go_to_service_mixin from '../../mixins/go_to_service_mixin.js'

    export default {
        mixins: [go_to_service_mixin],
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
