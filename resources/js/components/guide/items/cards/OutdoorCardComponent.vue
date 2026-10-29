<template>
    <div class="article_card col-md-4">
        <div class="portfolio-img view view-first">
            <div class="product-image" v-if="article.area.global_data.new_flag">
                <div class="discount-percent-badge discount-badge-fourty">{{ $t('guide.new_flag_badge') }}</div>
            </div>

            <!-- photo + hover mask in one box, so the mask covers exactly the photo -->
            <div class="cg-card-media">
                <favoriteHeart :article_id="article.area.global_data.id" :category="article.area.global_data.category" />
                <site-img v-if="article.area.global_data.image" :src=" '/public/' + image_dir+article.area.global_data.image" :img_class="'img-responsive'" :alt='article.area.locale_data.title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.area.locale_data.title'/>

                <div class="mask">
                    <router-link :to="'outdoor/'+article.area.global_data.url_title" class="info">
                        <i class="fa fa-arrow-right"></i>
                    </router-link>
                </div>
            </div>

            <div class="isotope-item-dtls">
                <h3>
                    <router-link :to="'outdoor/'+article.area.global_data.url_title">{{ article.area.locale_data.title }}</router-link>
                </h3>
                <div class="article_size">
                    <span v-html="article.area.locale_data.short_description"></span>
                </div>
                <div class="cg-card-stats">
                    <span class="cg-chip"><i class="fa fa-th-large" aria-hidden="true"></i> {{ article.route_quantyty.sectors }} {{ $t('guide.article_right_nabar.quick_stats_sectors') }}</span>
                    <span class="cg-chip"><i class="fa fa-map-signs" aria-hidden="true"></i> {{ article.route_quantyty.routes }} {{ $t('guide.article_right_nabar.quick_stats_routes') }}</span>
                    <span class="cg-chip" v-if="article.route_quantyty.mtps > 0"><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> {{ article.route_quantyty.mtps }} {{ $t('guide.article_right_nabar.quick_stats_mtps') }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import favoriteHeart from '../FavoriteHeartComponent.vue'
    export default {
        props: [
            'article',
            'image_dir'
        ],
        data: function () {
            return {
            };
        },
        components: {
            favoriteHeart,
        },
        mounted() {
        },
        methods: {
        }
    }
</script>
