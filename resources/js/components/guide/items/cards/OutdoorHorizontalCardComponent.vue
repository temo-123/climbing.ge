<template>
    <div class="outdoor-horizontal-card">
        <div class="row">
            <div class="col-md-4 col-sm-5">
                <div class="horizontal-card-image-wrapper">
                    <div class="product-image" v-if="article.area.global_data.new_flag">
                        <div class="discount-percent-badge discount-badge-fourty">NEW</div>
                    </div>
                    <site-img 
                        v-if="article.area.global_data.image != NULL" 
                        :src=" '/public/' + image_dir + article.area.global_data.image" 
                        :img_class="'img-responsive horizontal-card-image'" 
                        :alt='article.area.locale_data.title'
                    />
                    <site-img 
                        v-else 
                        :src="'/public/images/site_img/image.png'" 
                        :img_class="'img-responsive horizontal-card-image'" 
                        :alt='article.area.locale_data.title'
                    />
                    <div class="horizontal-card-overlay">
                        <router-link :to="'outdoor/'+article.area.global_data.url_title" class="horizontal-card-link">
                            <i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-7">
                <div class="horizontal-card-content">
                    <h3 class="horizontal-card-title">
                        <router-link :to="'outdoor/'+article.area.global_data.url_title">
                            {{ article.area.locale_data.title }}
                        </router-link>
                    </h3>
                    <div class="horizontal-card-meta">
                        <span class="meta-item">
                            <i class="fa fa-map-marker"></i> {{ article.region_name || 'Outdoor' }}
                        </span>
                    </div>
                    <p class="horizontal-card-description" v-html="article.area.locale_data.short_description"></p>
                    <div class="horizontal-card-stats">
                        <span class="stat-badge">
                            <i class="fa fa-th-large"></i> {{ article.route_quantyty.sectors }} {{ $t('guide.stats.sectors') || 'Sectors' }}
                        </span>
                        <span class="stat-badge">
                            <i class="fa fa-compass"></i> {{ article.route_quantyty.routes }} {{ $t('guide.stats.routes') || 'Routes' }}
                        </span>
                        <span v-if="article.route_quantyty.mtps > 0" class="stat-badge">
                            <i class="fa fa-arrow-up"></i> {{ article.route_quantyty.mtps }} {{ $t('guide.stats.multipitch') || 'Multi-pitch' }}
                        </span>
                    </div>
                    <router-link :to="'outdoor/'+article.area.global_data.url_title" class="horizontal-card-btn">
                        {{ $t('guide.view_more') || 'View More' }} <i class="fa fa-arrow-right"></i>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'article',
            'image_dir'
        ],
        data: function () {
            return {
                //
            };
        },
        components: {
        },
        mounted() {
            //
        },
        methods: {
        }
    }
</script>
