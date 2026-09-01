<template>
    <div class="row" v-if="images && images.length > 0">
        <div class="col-xs-4 col-sm-4 col-md-3 gallery_thumb" v-for="(image, index) in images" :key="image.id">
            <openImg
                :img="image_path + image.image"
                :img_alt="image.title || $t('global.gallery_image_alt')"
                img_class="gallery_img"
                :gallery="gallery"
                :gallery_index="index"
            />
        </div>
    </div>
</template>

<script>
import openImg from "../ImageOpenComponent.vue";

// Admin category slugs don't all match their public-site URL segment
// (see SiteRoutes.js) — e.g. "mount_route" is served at /mountaineering,
// "partners" at /partner, "special" at /special_article.
const DETAIL_PATHS = {
    mount_route: 'mountaineering',
    partners: 'partner',
    special: 'special_article',
    spot_projects: 'spot_project',
};

export default {
    components: {
        openImg,
    },
    props: {
        images: {
            type: Array,
            default: () => [],
        },
        image_path: {
            type: String,
            default: '/public/images/article_gallery_img/',
        },
    },
    computed: {
        // Every image as one list, so the lightbox can page through them —
        // same pattern as SectorComponent's sector_gallery. `link` is set
        // when the image carries its source article (e.g. the index
        // gallery, which mixes photos from many articles), so the lightbox
        // can offer a "go to article" button.
        gallery() {
            return this.images.map(image => ({
                src: this.image_path + image.image,
                alt: image.title || this.$t('global.gallery_image_alt'),
                link: this.article_link(image.article),
            }));
        },
    },
    methods: {
        article_link(article) {
            if (!article || !article.category || !article.url_title) return null;
            const path = DETAIL_PATHS[article.category] || article.category;
            return path + '/' + article.url_title;
        },
    },
};
</script>

<style>
.gallery_thumb {
    cursor: pointer;
    padding-top: 15px;
}
.gallery_img {
    width: 100%;
    height: auto;
    max-width: 100%;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
