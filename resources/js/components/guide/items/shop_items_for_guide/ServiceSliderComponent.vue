<template>
    <div class="h-recent-work services" id="services" v-if="services.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ $t('shop.title.services') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.data.services_description"></span>
            </h3>

            <div class="services-slider-container">
                <div class="previes_services_bottom" v-if="services.length > visibleCount && slider_index > 0" @click="previous">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>

                <div class="services-slider-wrapper" @mouseenter="pauseAutoSlide" @mouseleave="resumeAutoSlide">
                    <div class="services-slider" :style="{ display: 'flex', width: (services.length * 49) + '%', transform: 'translateX(' + (-slider_index * 49) + '%)', transition: 'transform 0.5s ease' }">
                        <div class="service-slider-item" v-for="service in services" :key='service.id'>
                            <ServiceItem :service_data="service">
                            </ServiceItem>
                        </div>
                    </div>
                </div>

                <div class="next_services_bottom" v-if="services.length > visibleCount && slider_index < services.length - visibleCount" @click="next">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import ServiceItem from './cards/ServiceItemComponent'

    export default {
        components: {
            ServiceItem
        },
        data() {
            return {
                services: [],
                slider_index: 0,
                visibleCount: 2,
                autoSlideInterval: null,
                isPaused: false,
                touchStartX: 0,
                touchEndX: 0
            }
        },
        mounted() {
            this.get_services()
            this.$nextTick(() => {
                this.startAutoSlide()
                const wrapper = this.$el.querySelector('.services-slider-wrapper');
                wrapper.addEventListener('touchstart', this.handleTouchStart, { passive: false });
                wrapper.addEventListener('touchmove', this.handleTouchMove, { passive: false });
                wrapper.addEventListener('touchend', this.handleTouchEnd, { passive: false });
            })
        },
        beforeDestroy() {
            this.stopAutoSlide()
            const wrapper = this.$el.querySelector('.services-slider-wrapper');
            if (wrapper) {
                wrapper.removeEventListener('touchstart', this.handleTouchStart);
                wrapper.removeEventListener('touchmove', this.handleTouchMove);
                wrapper.removeEventListener('touchend', this.handleTouchEnd);
            }
        },
        methods: {
            get_services(){
                axios
                .get('/service/get_local_services/'+localStorage.getItem('lang'))
                .then(response => {
                    this.services = response.data;
                })
                .catch(error =>{
                })
                // .finally(() => this.services_loading = false);
            },

            startAutoSlide() {
                if (this.autoSlideInterval) return;
                this.autoSlideInterval = setInterval(() => {
                    if (this.slider_index < this.services.length - this.visibleCount) {
                        this.slider_index += 1;
                    } else {
                        this.slider_index = 0; // loop back
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

            next(){
                this.pauseAutoSlide();
                if (this.slider_index < this.services.length - this.visibleCount) {
                    this.slider_index += 1;
                }
                this.resumeAutoSlide();
            },

            previous(){
                this.pauseAutoSlide();
                if (this.slider_index > 0) {
                    this.slider_index -= 1;
                }
                this.resumeAutoSlide();
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
                        this.next();
                    } else {
                        this.previous();
                    }
                }
            },
        }
    }
</script>

<style scoped>
    .services-slider-container {
        display: flex;
        align-items: center;
    }

    .services-slider-wrapper {
        flex: 1;
        overflow: hidden;
    }

    .service-slider-item {
        flex: 0 0 48%;
        /* Override Bootstrap col classes */
        width: 48% !important;
        max-width: 48% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .previes_services_bottom, .next_services_bottom {
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

    .previes_services_bottom:hover, .next_services_bottom:hover {
        background-color: #7c7cfd;
        color: white;
        border-color: #7c7cfd;
    }
</style>
