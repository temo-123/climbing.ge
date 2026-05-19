<template>
    <div class="h-recent-work services" id="services" v-if="team_members.length > 0">
        <div class="container">

            <h2 class='index_h2'>Team Members</h2>

            <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
            <h3 class='article_list_short_description'> <span v-html="this.$siteData.data.team_member_description"></span> </h3>

            <div class="team-members-slider-container">
                <div class="previes_team_bottom" v-if="team_members.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index <= 0 }" @click="previous">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </div>

                <div ref="teamSliderWrapper" class="team-slider-wrapper" @mouseenter="pauseAutoSlide" @mouseleave="resumeAutoSlide">
                    <div class="team-slider" :style="{ display: 'flex', width: (team_members.length * (100 / visibleCount)) + '%', transform: 'translateX(' + (-slider_index * 100 / team_members.length) + '%)', transition: 'transform 0.5s ease' }">
                        <div class="team-item" v-for="user in team_members" :key="user.id" :style="{ flex: '0 0 ' + (100 / team_members.length) + '%' }" @click="show_user_modal(user.id)">
                            <div :style="'background-image: url(/public/images/site_img/demo_imgs/user_demo_img.gif);'" class='user_img' v-if='user.image == null'> </div>
                            <div :style="'background-image: url(/public/images/user_profil_img/' + user.image + ');'" class='user_img' v-else> </div>
                            <span class="user_name">{{ user.name }} {{ user.surname }}</span>
                        </div>
                    </div>
                </div>

                <div class="next_team_bottom" v-if="team_members.length > visibleCount" :class="{ 'slider-btn-disabled': slider_index >= team_members.length - visibleCount }" @click="next">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </div>
            </div>


        </div>

        <user_modal
            ref="user_modal"
            @show_modal="show_user_modal"
        />
    </div>
</template>

<script>
    import user_modal from "../../global_components/modals/UserModalComponent.vue";

    export default {
        props: [
            // 'team_members',
        ],
        data: function () {
            return {
                slider_index: 0,
                visibleCount: window.innerWidth < 768 ? 1 : 3,
                team_members: [],
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
            this.get_team_members()
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
                this.$refs.user_modal.show_modal(user_id)
            },
            
            get_team_members(){
                axios
                .get('/get_team/get_team_members/')
                .then(response => {
                    this.team_members = response.data
                    this.$nextTick(() => {
                        this.startAutoSlide()
                        const wrapper = this.$refs.teamSliderWrapper;
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
                    if (this.slider_index < this.team_members.length - this.visibleCount) {
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
                if (this.slider_index < this.team_members.length - this.visibleCount) {
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
    .user_name{
        font-size: 1.5em;
        text-align: center;
        display: block;
        margin-top: 10px;
    }
    .user_img{
        width: 15em;
        height: 15em;
        border-radius: 50%;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .team-members-slider-container {
        display: flex;
        align-items: center;
    }

    .team-slider-wrapper {
        flex: 1;
        overflow: hidden;
    }

    .team-item {
        box-sizing: border-box;
        padding: 0 8px;
        text-align: center;
        cursor: pointer;
    }

    .slider-btn-disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    .previes_team_bottom, .next_team_bottom {
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

    .previes_team_bottom:hover, .next_team_bottom:hover {
        background-color: #7c7cfd;
        color: white;
        border-color: #7c7cfd;
    }
</style>
