<template>
    <div class="food col-md-4">
        <div class="portfolio-img view view-first">
            
            <div class="product-image" v-if="article.global_data.new_flag">
                <div class="discount-percent-badge discount-badge-fourty">{{ $t('guide.new_flag_badge') }}</div>
            </div>

            <!-- photo + hover mask in one box, so the mask covers exactly the photo -->
            <div class="cg-card-media">
                <favoriteHeart :article_id="article.global_data.id" :category="article.global_data.category" />
                <site-img v-if="article.global_data.image != null" :src=" '/public/' + this.image_dir+article.global_data.image" :img_class="'img-responsive'" :alt='article.locale_data.title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='article.locale_data.title'/>

                <div class="mask">
                    <router-link :to="this.route" class="info">
                        <i class="fa fa-arrow-right"></i>
                        <p style="display: none">{{article.locale_data.title}}</p>
                    </router-link>
                </div>
            </div>

            <div class="isotope-item-dtls">
                <h3>
                    <router-link :to="this.route">{{ article.locale_data.title }}</router-link>
                </h3>
                <div class="article_size">
                    <span v-html="article.locale_data.short_description"></span>
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
            'image_dir',
            'route',
        ],
        data: function () {
            return {
                // 
            };
        },
        components: {
            favoriteHeart,
        },
        mounted() {
            // 
        },
        methods: {
        }
    }
</script>