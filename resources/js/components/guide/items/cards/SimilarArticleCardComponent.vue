<template>
    <!-- "Similar articles" card: a 4:3 photo frame (cropped, every card the
         same size), the title clamped to two lines. -->
    <router-link :to="this.route" class="cg-sim-card">
        <div class="cg-sim-card__media similar_articles_img">
            <site-img v-if="article.global_data.image != null" :src="'/public/' + this.image_dir + '/' + article.global_data.image" :img_class="'img-responsive'" :alt='article.locale_data.title'/>
            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.locale_data.title'/>
        </div>
        <h3 class="cg-sim-card__title">{{ article.locale_data.title }}</h3>
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
