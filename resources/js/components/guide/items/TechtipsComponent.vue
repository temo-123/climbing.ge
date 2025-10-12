<template>
    <div class="h-recent-work services" id="services">
        <div class="container" v-if="techtips.length > 0">

            <h2 class='index_h2'>{{ $t('guide.title.tech tips') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h3 class='article_list_short_description'> {{this.$siteData.data.tech_tips_description}} </h3>
                    
            <div>
                <div class="tips-slider-container">
                    <div class="previes_tip_bottom" v-if="techtips.length > 3 && slider_index > 0" @click="previousTips">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </div>

                    <div class="tips-slider-wrapper" @mouseenter="pauseAutoSlide" @mouseleave="resumeAutoSlide">
                        <div class="tips-slider" :style="{ display: 'flex', gap: '2%', width: (techtips.length * 31) + '%', transform: 'translateX(' + (-slider_index * 31) + '%)', transition: 'transform 0.4s ease-in-out' }">
                            <div class="tip-item" v-for="tip in techtips" :key="tip.global_data.id">
                                <div class="product-image" v-if="tip.global_data.new_flag">
                                    <div class="discount-percent-badge discount_percent_badge_for_techtip discount-badge-fourty">NEW</div>
                                </div>
                                <div class="thumbnail">
                                    <router-link :to="'tech_tip/'+tip.global_data.url_title" class="info">
                                        <site-img v-if="tip.global_data.image != null" :src="'/public/images/tech_tip_img/'+tip.global_data.image" :img_class="'img-responsive'" :alt='tip.locale_data.title'/>
                                        <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'img-responsive'" :alt='tip.locale_data.title'/>
                                    </router-link>
                                    <div class="caption">
                                        <router-link :to="'tech_tip/'+tip.global_data.url_title" class="info">
                                            <h3 class="text-center text-uppercase tip_title">{{ tip.locale_data.title }}</h3>
                                        </router-link>
                                        <!-- <hr> -->
                                        <span class="text-center" v-html="tip.locale_data.short_description"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="next_tip_bottom" v-if="techtips.length > visibleCount && slider_index < techtips.length - visibleCount - 1" @click="nextTips">
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                techtips: [],
                slider_index: 0,
                visibleCount: 3,
                autoSlideInterval: null,
                isPaused: false,
                touchStartX: 0,
                touchEndX: 0,
                // tip_num: 0
            }
        },
        mounted() {
            this.get_techtips()
            this.startAutoSlide()
            this.$nextTick(() => {
                const wrapper = this.$el.querySelector('.tips-slider-wrapper');
                wrapper.addEventListener('touchstart', this.handleTouchStart, { passive: false });
                wrapper.addEventListener('touchmove', this.handleTouchMove, { passive: false });
                wrapper.addEventListener('touchend', this.handleTouchEnd, { passive: false });
            })
        },
        beforeDestroy() {
            this.stopAutoSlide()
            const wrapper = this.$el.querySelector('.tips-slider-wrapper');
            if (wrapper) {
                wrapper.removeEventListener('touchstart', this.handleTouchStart);
                wrapper.removeEventListener('touchmove', this.handleTouchMove);
                wrapper.removeEventListener('touchend', this.handleTouchEnd);
            }
        },
        methods: {
            get_techtips(){
                axios
                .get('../api/articles/tech_tip/'+localStorage.getItem('lang'))
                .then(response => {
                    // this.techtips = response.data
                    this.techtips = response.data
                })
                .catch(error =>{
                })
            },

            nextTips(){
                this.pauseAutoSlide();
                if (this.slider_index < this.techtips.length - this.visibleCount - 1) {
                    this.slider_index += 1;
                }
                this.resumeAutoSlide();
            },

            previousTips(){
                this.pauseAutoSlide();
                if (this.slider_index > 0) {
                    this.slider_index -= 1;
                }
                this.resumeAutoSlide();
            },

            startAutoSlide() {
                if (this.autoSlideInterval) return;
                this.autoSlideInterval = setInterval(() => {
                    if (this.slider_index < this.techtips.length - this.visibleCount - 1) {
                        this.slider_index += 1;
                    } else {
                        this.stopAutoSlide();
                    }
                }, 5000);
            },

            stopAutoSlide() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                    this.autoSlideInterval = null;
                }
            },

            pauseAutoSlide() {
                this.stopAutoSlide();
            },

            resumeAutoSlide() {
                this.startAutoSlide();
            },

            handleTouchStart(e) {
                this.touchStartX = e.changedTouches[0].screenX;
            },

            handleTouchMove(e) {
                e.preventDefault();
            },

            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
            },

            handleSwipe() {
                const diff = this.touchStartX - this.touchEndX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        this.nextTips();
                    } else {
                        this.previousTips();
                    }
                }
            },
        }
    };
</script>

<style scoped>
.tip_title{
    margin: 10px;
    color: #2d2c2c;
    transition: 0.5s;
}
.tip_title:hover{
    color: #7c7cfd;
    transition: 0.5s;
}

@media (max-width: 990px){
    .thumbnail{
        /* height: 18em;
        max-height: 22em; */
        margin-top: 20px;
    }
    .thumbnail p{
        font-size: 80%;
    }
}

@media (max-width: 375px){
    .thumbnail{
        /* height: 9em;
        max-height: 22em; */
        margin-top: 16px;
    }
    .thumbnail p{
        font-size: 80%;
    }
}
.tip{
    width: 25%;
    height: auto;
    margin: 2%;
}
/* .caption h3{
    margin: 0;
} */
.caption p{
    text-align: center;
}
/* .tips_list{
    max-height: 100%; 
    overflow: hidden;
}
.tips{
    display: flex;
} */
.tips-slider-container {
    display: flex;
    align-items: center;
}

.tips-slider-wrapper {
    flex: 1;
    overflow: hidden;
}

.tip-item {
    /* flex: 0 0 32%;
    max-width: 32%; */

    flex: 0 0 23em;
    max-width: 31%;
}

.previes_tip_bottom, .next_tip_bottom {
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

.previes_tip_bottom:hover, .next_tip_bottom:hover {
    background-color: #7c7cfd;
    color: white;
    border-color: #7c7cfd;
}

.thumbnail {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.thumbnail:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.discount_percent_badge_for_techtip {
    margin: 5px 20px 0 0;
}
</style>