<template>
    <div class="partners" v-if="partners.length > 0">
        <div class="container">

            <h2 class='index_h2'>{{ $t('guide.title.partners') }}</h2>

            <div class="bar"><i class="fa fa-handshake-o"></i></div>

            <h3 class="article_list_short_description"> <span v-html="$siteData.data.partners_description"></span> </h3>

            <div class="partners-slider-container">
                <div class="previes_partners_bottom" v-if="partners.length > visibleCount && slider_index > 0" @click="previous">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>

                <div class="partners-slider-wrapper" @wheel="onWheel">
                    <div class="partners-slider" :style="{ display: 'flex', gap: '2%', width: (partners.length * 25) + '%', transform: 'translateX(' + (-slider_index * 25) + '%)', transition: 'transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)' }">
                        <div class="partner-item" v-for="partner in partners" :key="partner.global_data.id">
                            <div class="thumbnail partner_thumbnail">
                                <site-img v-if="partner.global_data.image != null" :src="'/public/images/partner_img/'+partner.global_data.image" :img_class="'img-responsive'" :alt='partner.locale_data.title'/>
                                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='partner.locale_data.title'/>
                                <div class="caption">
                                    <h3 class="text-center text-uppercase partner_title">{{ partner.locale_data.title }}</h3>
                                    <span class="text-center" v-html="partner.locale_data.short_description"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="next_partners_bottom" v-if="partners.length > visibleCount && slider_index < partners.length - visibleCount" @click="next">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios_mixin from '../../../mixins/axios_mixin'

    export default {
        mixins: [
            axios_mixin
        ],
        props: [
            // 'partners',
        ],
        data: function () {
            return {
                slider_index: 0,
                visibleCount: 4,
                autoSlide: null,

                partners: []
            };
        },
        mounted() {
            this.get_partners()
            this.startAutoSlide();
        },
        beforeDestroy() {
            if (this.autoSlide) {
                clearInterval(this.autoSlide);
            }
        },
        methods: {
            next(){
                if (this.slider_index < this.partners.length - this.visibleCount) {
                    this.slider_index += 1;
                } else {
                    this.slider_index = 0;
                }
                this.pauseAutoSlide();
            },
            previous(){
                if (this.slider_index > 0) {
                    this.slider_index -= 1;
                } else {
                    this.slider_index = this.partners.length - this.visibleCount;
                }
                this.pauseAutoSlide();
            },
            get_partners(){
                this.get_articles('partners', localStorage.getItem('lang'),
                    (data) => {
                        this.partners = data;
                    },
                    (error) => {
                        console.error('Error fetching articles:', error);
                    },
                    () => {
                        this.indoor_article_loading = false;
                    }
                );
            },
            startAutoSlide() {
                this.autoSlide = setInterval(() => {
                    this.next();
                }, 5000);
            },
            pauseAutoSlide() {
                if (this.autoSlide) {
                    clearInterval(this.autoSlide);
                    this.autoSlide = null;
                }
                setTimeout(() => {
                    if (!this.autoSlide) {
                        this.startAutoSlide();
                    }
                }, 3000);
            },
            onWheel(e) {
                e.preventDefault();
                if (e.deltaY > 0) {
                    this.next();
                } else {
                    this.previous();
                }
                this.pauseAutoSlide();
            },
        }
    };
</script>

<style scoped>
    .partners {
        margin-left: 5%;
        margin-right: 5%;
    }

    .partners-slider-container {
        display: flex;
        align-items: center;
    }

    .partners-slider-wrapper {
        flex: 1;
        overflow: hidden;
    }

    .partner-item {
        flex: 0 0 25%;
        text-align: center;
    }

    .previes_partners_bottom, .next_partners_bottom {
        font-size: 150%;
        cursor: pointer;
        padding: 10px 15px;
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid #dee2e6;
        border-radius: 50%;
        transition: all 0.3s ease;
        color: #6c757d;
        margin: 0 10px;
    }

    .previes_partners_bottom:hover, .next_partners_bottom:hover {
        background-color: #7c7cfd;
        color: white;
        border-color: #7c7cfd;
    }

    .partner-item {
        opacity: 1;
        transition: opacity 0.5s ease-in-out, transform 0.2s;
    }

    .partner-item:hover {
        transform: scale(1.05);
    }
</style>

<!-- <style>
.partners{
    margin-left: 5%;
    margin-Right: 5%;
}
.previes_partner_bottom{
    float: left;
}
.next_partner_bottom{
    float: right;
}
.next_partner_bottom, .previes_partner_bottom{
    margin-top: -10%;
    font-size: 150%;
    cursor: pointer;
}
</style> -->