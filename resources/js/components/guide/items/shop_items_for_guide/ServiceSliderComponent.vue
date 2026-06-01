<template>
    <div class="h-recent-work services" id="services" v-if="services.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ $t('shop.title.services') }}</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>

            <h3 class="article_list_short_description">
                <span v-html="this.$siteData.data.services_description"></span>
            </h3>

            <div class="services-slider-container">
                <div class="previes_services_bottom" v-if="services.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index <= 0 }" @click="previous">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>

                <div ref="sliderWrapper" class="services-slider-wrapper" @mouseenter="pauseAutoSlide" @mouseleave="resumeAutoSlide">
                    <div class="services-slider" :style="{ display: 'flex', width: (services.length * (100 / visibleCount)) + '%', transform: 'translateX(' + (-slider_index * 100 / services.length) + '%)', transition: 'transform 0.5s ease' }">
                        <div class="service-slider-item" v-for="service in services" :key='service.id' :style="{ flex: '0 0 ' + (100 / services.length) + '%' }">
                            <ServiceItem :service_data="service">
                            </ServiceItem>
                        </div>
                    </div>
                </div>

                <div class="next_services_bottom" v-if="services.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index >= services.length - visibleCount }" @click="next">
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
                visibleCount: window.innerWidth < 768 ? 1 : 3,
                autoSlideInterval: null,
                isPaused: false,
                touchStartX: 0,
                touchEndX: 0
            }
        },
        mounted() {
            this.get_services()
            window.addEventListener('resize', this.onResize)
        },
        beforeUnmount() {
            this.stopAutoSlide()
            window.removeEventListener('resize', this.onResize)
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
                .get('/get_service/get_local_services/'+localStorage.getItem('lang'))
                .then(response => {
                    this.services = response.data;
                    this.$nextTick(() => {
                        this.startAutoSlide()
                        const wrapper = this.$el.querySelector('.services-slider-wrapper');
                        if (wrapper) {
                            wrapper.addEventListener('touchstart', this.handleTouchStart, { passive: false });
                            wrapper.addEventListener('touchmove', this.handleTouchMove, { passive: false });
                            wrapper.addEventListener('touchend', this.handleTouchEnd, { passive: false });
                        }
                    })
                })
                .catch(error =>{
                })
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

            onResize() {
                const count = window.innerWidth < 768 ? 1 : 3
                if (count !== this.visibleCount) {
                    this.visibleCount = count
                    this.slider_index = 0
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
        box-sizing: border-box;
        padding: 0 8px;
    }

    .service-slider-item :deep(.col-md-4),
    .service-slider-item :deep(.col-sm-6) {
        width: 100%;
        max-width: 100%;
        padding-left: 0;
        padding-right: 0;
        float: none;
    }

    .slider-btn-disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
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

    @media (max-width: 768px) {
        .services-slider-container {
            position: relative;
        }
        .previes_services_bottom, .next_services_bottom {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            font-size: 100%;
            padding: 6px 10px;
            margin: 0;
        }
        .previes_services_bottom {
            left: 4px;
        }
        .next_services_bottom {
            right: 4px;
        }
    }
</style>
