<template>
    <div class="h-recent-work local-bisnes-index" v-if="businesses.length > 0">
        <div class="container">
            <h2 class="index_h2">{{ $t('guide.title.local_businesses') }}</h2>

            <div class="bar"><i class="fa fa-map-marker"></i></div>

            <h3 class="article_list_short_description"> <span v-html="this.$siteData.data.local_bisnes_index_description"></span> </h3>

            <div class="bisnes-index-slider-container">
                <div class="previes_bisnes_bottom" v-if="businesses.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index <= 0 }" @click="previous">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>

                <div class="bisnes-index-slider-wrapper">
                    <div class="bisnes-index-slider" :style="{ display: 'flex', width: (businesses.length * (100 / visibleCount)) + '%', transform: 'translateX(' + (-slider_index * 100 / businesses.length) + '%)', transition: 'transform 0.5s ease' }">
                        <div class="bisnes-index-slide-item" v-for="bisnes in businesses" :key="bisnes.global_data.id" :style="{ flex: '0 0 ' + (100 / businesses.length) + '%', boxSizing: 'border-box', padding: '0 8px' }">
                            <suportLocalBisnesIndexCard
                                :bisnes="bisnes"
                                @open="openModal"
                            />
                        </div>
                    </div>
                </div>

                <div class="next_bisnes_bottom" v-if="businesses.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index >= businesses.length - visibleCount }" @click="next">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <LocalBisnesModal
            v-if="activeUrlTitle"
            :url-title="activeUrlTitle"
            v-model="showModal"
        />
    </div>
</template>

<script>
    import suportLocalBisnesIndexCard from './cards/SuportLocalBisnesIndexCardComponent.vue'
    import LocalBisnesModal from './modals/LocalBisnesModalComponent.vue'

    export default {
        name: 'SuportLocalBisnesIndexComponent',
        components: {
            suportLocalBisnesIndexCard,
            LocalBisnesModal,
        },
        data() {
            return {
                businesses: [],
                slider_index: 0,
                visibleCount: window.innerWidth < 768 ? 1 : 3,
                autoScrollInterval: null,
                showModal: false,
                activeUrlTitle: null,
            }
        },
        mounted() {
            this.get_index_local_bisneses().then(() => {
                this.autoScrollInterval = setInterval(() => {
                    this.next();
                }, 5000);
            });
            window.addEventListener('resize', this.onResize)
        },
        beforeUnmount() {
            if (this.autoScrollInterval) {
                clearInterval(this.autoScrollInterval);
            }
            window.removeEventListener('resize', this.onResize)
        },
        methods: {
            get_index_local_bisneses() {
                const lang = localStorage.getItem('lang') || 'en'
                return axios
                    .get('/get_bisnes/get_index_local_bisneses/' + lang)
                    .then(response => {
                        this.businesses = Array.isArray(response.data) ? response.data : []
                    })
                    .catch(() => {
                        this.businesses = []
                    })
            },

            next() {
                clearInterval(this.autoScrollInterval);
                if (this.slider_index < this.businesses.length - this.visibleCount) {
                    this.slider_index += 1;
                } else {
                    this.slider_index = 0;
                }
                this.autoScrollInterval = setInterval(() => {
                    this.next();
                }, 5000);
            },

            previous() {
                clearInterval(this.autoScrollInterval);
                if (this.slider_index > 0) {
                    this.slider_index -= 1;
                }
                this.autoScrollInterval = setInterval(() => {
                    this.next();
                }, 5000);
            },

            onResize() {
                const count = window.innerWidth < 768 ? 1 : 3
                if (count !== this.visibleCount) {
                    this.visibleCount = count
                    this.slider_index = 0
                }
            },

            openModal(urlTitle) {
                this.showModal = false
                this.activeUrlTitle = urlTitle
                this.$nextTick(() => {
                    this.showModal = true
                })
            },
        }
    }
</script>

<style scoped>
    .bisnes-index-slider-container {
        display: flex;
        align-items: center;
    }

    .bisnes-index-slider-wrapper {
        flex: 1;
        overflow: hidden;
        padding: 10px 0;
    }

    .slider-btn-disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    .previes_bisnes_bottom, .next_bisnes_bottom {
        flex-shrink: 0;
        font-size: 130%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background-color: #fff;
        border: none;
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
        color: #7c7cfd;
        margin: 0 14px;
    }

    .previes_bisnes_bottom:hover, .next_bisnes_bottom:hover {
        background-color: #7c7cfd;
        color: white;
        box-shadow: 0 4px 16px rgba(124, 124, 253, 0.4);
        transform: scale(1.08);
    }

    @media (max-width: 767px) {
        .previes_bisnes_bottom, .next_bisnes_bottom {
            width: 40px;
            height: 40px;
            margin: 0 6px;
        }
    }
</style>
