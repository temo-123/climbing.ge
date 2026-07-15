<template>
    <div class="team-members-slider-container" v-if="users.length > 0">
        <div class="previes_team_bottom" v-if="users.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index <= 0 }" @click="previous">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </div>

        <div ref="teamSliderWrapper" class="team-slider-wrapper" @mouseenter="pauseAutoSlide" @mouseleave="resumeAutoSlide">
            <div class="team-slider" :style="{ display: 'flex', width: (users.length * (100 / visibleCount)) + '%', transform: 'translateX(' + (-slider_index * 100 / users.length) + '%)', transition: 'transform 0.5s ease' }">
                <div class="team-item" v-for="user in users" :key="user.id" :style="{ flex: '0 0 ' + (100 / users.length) + '%' }">
                    <div class="team-card" @click="show_user_modal(user.id)">
                        <div class="user_img_wrapper">
                            <div :style="'background-image: url(/public/images/site_img/demo_imgs/user_demo_img.gif);'" class='user_img' v-if='user.image == null'> </div>
                            <div :style="'background-image: url(/public/images/user_profil_img/' + user.image + ');'" class='user_img' v-else> </div>
                        </div>
                        <span class="user_name">{{ user.name }} {{ user.surname }}</span>
                        <span class="user_status" v-if="showStatus && user.member_status">{{ user.member_status }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="next_team_bottom" v-if="users.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index >= users.length - visibleCount }" @click="next">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </div>

        <div class="team-slider-dots" v-if="users.length > visibleCount">
            <span
                v-for="dotIndex in (users.length - visibleCount + 1)"
                :key="dotIndex"
                class="team-dot"
                :class="{ active: slider_index === dotIndex - 1 }"
                @click="goToSlide(dotIndex - 1)"
            ></span>
        </div>

        <user_modal
            ref="user_modal"
            @show_modal="show_user_modal"
        />
    </div>
</template>

<script>
    import user_modal from "./modals/UserModalComponent.vue";

    export default {
        name: 'TeamCardsSliderComponent',
        props: {
            users: {
                type: Array,
                default: () => [],
            },
            modalType: {
                type: String,
                default: 'user',
            },
            showStatus: {
                type: Boolean,
                default: true,
            },
        },
        data: function () {
            return {
                slider_index: 0,
                visibleCount: window.innerWidth < 768 ? 1 : 3,
                autoSlideInterval: null,
                isPaused: false,
                touchStartX: 0,
                touchEndX: 0
            };
        },
        components: {
            user_modal
        },
        mounted() {
            this.$nextTick(() => {
                this.startAutoSlide()
                const wrapper = this.$refs.teamSliderWrapper;
                if (wrapper) {
                    wrapper.addEventListener('touchstart', this.handleTouchStart, { passive: false });
                    wrapper.addEventListener('touchmove', this.handleTouchMove, { passive: false });
                    wrapper.addEventListener('touchend', this.handleTouchEnd, { passive: false });
                }
            })
            window.addEventListener('resize', this.onResize)
        },
        beforeUnmount() {
            this.stopAutoSlide()
            window.removeEventListener('resize', this.onResize)
            const wrapper = this.$refs.teamSliderWrapper;
            if (wrapper) {
                wrapper.removeEventListener('touchstart', this.handleTouchStart);
                wrapper.removeEventListener('touchmove', this.handleTouchMove);
                wrapper.removeEventListener('touchend', this.handleTouchEnd);
            }
        },
        methods: {
            show_user_modal(user_id){
                this.$refs.user_modal.show_modal(user_id, this.modalType)
            },

            startAutoSlide() {
                if (this.autoSlideInterval) return;
                this.autoSlideInterval = setInterval(() => {
                    if (this.slider_index < this.users.length - this.visibleCount) {
                        this.slider_index += 1;
                    } else {
                        this.slider_index = 0;
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
                if (this.slider_index < this.users.length - this.visibleCount) {
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

            goToSlide(index){
                this.pauseAutoSlide();
                this.slider_index = index;
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
    .user_name{
        font-size: 1.15em;
        font-weight: 600;
        text-align: center;
        display: block;
        margin-top: 16px;
        color: #2b2b3c;
    }

    .user_status{
        font-size: 0.85em;
        text-align: center;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-top: 4px;
        color: #8a8a99;
    }

    .user_img_wrapper {
        width: 8.5em;
        height: 8.5em;
        margin: 0 auto;
        border-radius: 50%;
        padding: 4px;
        background: linear-gradient(135deg, #7c7cfd, #b2b2ff);
        box-shadow: 0 4px 14px rgba(124, 124, 253, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .user_img{
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        border: 3px solid #fff;
    }

    .team-members-slider-container {
        display: flex;
        align-items: center;
    }

    .team-slider-wrapper {
        flex: 1;
        overflow: hidden;
        padding: 10px 0;
    }

    .team-item {
        box-sizing: border-box;
        padding: 0 12px;
    }

    .team-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        padding: 20px 16px 18px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        box-sizing: border-box;
    }

    .team-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.14);
    }

    .team-card:hover .user_img_wrapper {
        transform: scale(1.05);
        box-shadow: 0 6px 18px rgba(124, 124, 253, 0.4);
    }

    .team-slider-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
    }

    .team-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background-color: #dcdce6;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .team-dot:hover {
        background-color: #b2b2ff;
    }

    .team-dot.active {
        background-color: #7c7cfd;
        width: 22px;
        border-radius: 5px;
    }

    .slider-btn-disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    .previes_team_bottom, .next_team_bottom {
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

    .previes_team_bottom:hover, .next_team_bottom:hover {
        background-color: #7c7cfd;
        color: white;
        box-shadow: 0 4px 16px rgba(124, 124, 253, 0.4);
        transform: scale(1.08);
    }

    @media (max-width: 767px) {
        .user_img_wrapper {
            width: 7em;
            height: 7em;
        }

        .team-card {
            padding: 20px 14px 18px;
        }

        .previes_team_bottom, .next_team_bottom {
            width: 40px;
            height: 40px;
            margin: 0 6px;
        }
    }
</style>
