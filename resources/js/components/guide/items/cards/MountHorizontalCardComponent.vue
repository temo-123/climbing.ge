<template>
    <div class="mount-horizontal-card">
        <div class="row">
            <div class="col-md-4 col-sm-5">
                <div class="horizontal-card-image-wrapper">
                    <div class="product-image" v-if="mount.global_data.new_flag">
                        <div class="discount-percent-badge discount-badge-fourty">NEW</div>
                    </div>
                    <site-img 
                        v-if="mount.global_data.image != null" 
                        :src="'/public/images/mount_route_img/'+mount.global_data.image" 
                        :img_class="'img-responsive horizontal-card-image'" 
                        :alt='mount.locale_data.title'
                    />
                    <site-img 
                        v-else 
                        :src="'/public/images/site_img/image.png'" 
                        :img_class="'img-responsive horizontal-card-image'" 
                        :alt='mount.locale_data.title'
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
                            {{ mount.locale_data.title }}
                        </router-link>
                    </h3>
                    <div class="horizontal-card-meta" v-if="mount.mount_masive">
                        <span class="meta-item">
                            <i class="fa fa-mountain"></i> {{ mount.mount_masive }}
                        </span>
                        <span class="meta-item" v-if="mount.locale_data.location">
                            <i class="fa fa-map-marker"></i> {{ mount.locale_data.location }}
                        </span>
                    </div>
                    <p class="horizontal-card-description" v-html="mount.locale_data.short_description"></p>
                    <div class="horizontal-card-stats" v-if="mount.route_data">
                        <span v-if="mount.route_data.difficulty" class="stat-badge">
                            <i class="fa fa-signal"></i> {{ mount.route_data.difficulty }}
                        </span>
                        <span v-if="mount.route_data.length" class="stat-badge">
                            <i class="fa fa-arrows-v"></i> {{ mount.route_data.length }}m
                        </span>
                        <span v-if="mount.route_data.duration" class="stat-badge">
                            <i class="fa fa-clock-o"></i> {{ mount.route_data.duration }}
                        </span>
                    </div>
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
            'mount',
            'route',
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

