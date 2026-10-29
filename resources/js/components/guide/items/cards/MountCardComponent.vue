<template>
  <div class="food col-md-4">
        <div class="portfolio-img view view-first">
            <div class="product-image" v-if="mount.global_data.new_flag">
                <div class="discount-percent-badge discount-badge-fourty">{{ $t('guide.new_flag_badge') }}</div>
            </div>
                                
            <!-- photo + hover mask in one box, so the mask covers exactly the photo -->
            <div class="cg-card-media">
                <favoriteHeart :article_id="mount.global_data.id" :category="mount.global_data.category" />
                <site-img v-if="mount.global_data.image != null" :src="'/images/mount_route_img/'+mount.global_data.image" :img_class="'img-responsive'" :alt='mount.locale_data.title'/>
                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='mount.locale_data.title'/>

                <div class="mask">
                    <router-link :to="this.route" class="info"><i class="fa fa-arrow-right"></i></router-link>
                </div>
            </div>

            <div class="isotope-item-dtls">
                <h3>
                    <router-link :to="this.route">{{ mount.locale_data.title }}</router-link>
                </h3>
                <div class="mount_size article_size">
                    <span v-html="mount.locale_data.short_description"></span>
                </div>
                <div class="cg-card-stats" v-if="mount.mount_masive">
                    <span class="cg-chip"><i class="fa fa-area-chart" aria-hidden="true"></i> {{ mount.mount_masive }}</span>
                </div>
                <div class="mt-2" v-if="mount.global_data.mount_grade">
                    <mount-grade-bar-component :grade="mount.global_data.mount_grade" />
                </div>
            </div>
        </div>
        
    </div> 
</template>

<script>
    import favoriteHeart from '../FavoriteHeartComponent.vue'
    import MountGradeBarComponent from '../MountGradeBarComponent.vue';

    export default {
        props: [
            'mount',
            'route',
        ],
        data: function () {
            return {
            };
        },
        components: {
            favoriteHeart,
            MountGradeBarComponent,
        },
        mounted() {
        },
        methods: {
        }
    }
</script>

<style scoped>
.mount_size {
    color: var(--cg-stone);
    line-height: 1.5;
}
</style>