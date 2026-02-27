<template>
    <div class="article-horizontal-card">
        <div class="row">
            <div class="col-md-4 col-sm-5">
                <div class="horizontal-card-image-wrapper">
                    <div class="product-image" v-if="item.global_data.new_flag">
                        <div class="discount-percent-badge discount-badge-fourty">NEW</div>
                    </div>
                    <site-img 
                        v-if="item.global_data.image != NULL" 
                        :src="'/public/' + image_dir + item.global_data.image" 
                        :img_class="'img-responsive horizontal-card-image'" 
                        :alt='item.locale_data.title'
                    />
                    <site-img 
                        v-else 
                        :src="'/public/images/site_img/image.png'" 
                        :img_class="'img-responsive horizontal-card-image'" 
                        :alt='item.locale_data.title'
                    />
                    <div class="horizontal-card-overlay">
                        <router-link :to="this.route" class="horizontal-card-link">
                            <i class="fa fa-arrow-right"></i>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-7">
                <div class="horizontal-card-content">
                    <h3 class="horizontal-card-title">
                        <router-link :to="this.route">
                            {{ item.locale_data.title }}
                        </router-link>
                    </h3>
                    <p class="horizontal-card-description" v-html="item.locale_data.short_description"></p>
                    <router-link :to="this.route" class="horizontal-card-btn">
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
            'mount',
            'image_dir',
            'route',
        ],
        data: function () {
            return {
                //
            };
        },
        computed: {
            item() {
                // Support both 'article' and 'mount' props for compatibility
                return this.article || this.mount;
            }
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

