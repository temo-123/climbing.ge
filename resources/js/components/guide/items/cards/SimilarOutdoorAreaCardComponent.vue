<template>
    <!-- "Similar areas" card for outdoor crags: same frame as
         SimilarArticleCardComponent, plus the sector / route counts. -->
    <router-link :to="this.route" class="cg-sim-card">
        <div class="cg-sim-card__media similar_articles_img">
            <site-img v-if="article.area.global_data.image != null" :src="'/public/' + this.image_dir + '/' + article.area.global_data.image" :img_class="'img-responsive'" :alt='article.area.locale_data.title'/>
            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.area.locale_data.title'/>
        </div>
        <h3 class="cg-sim-card__title">{{ article.area.locale_data.title }}</h3>
        <div v-if="article.route_quantyty" class="cg-sim-card__meta">
            <span>{{ article.route_quantyty.sectors }} {{ $t('common.sectors') }}</span>
            <span>{{ article.route_quantyty.routes }} {{ $t('common.routes') }}</span>
            <span v-if="article.route_quantyty.mtps > 0">{{ article.route_quantyty.mtps }} {{ $t('guide.route.mtp') }}</span>
        </div>
    </router-link>
</template>

<script>
    export default {
        props: [
            'article',
            'image_dir',
            'route',
            'category'
        ],
    }
</script>
