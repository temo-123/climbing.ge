<template>
    <div class="team-members-slider-container" v-if="users_prop.length > 0">
        <!-- <div class="previes_team_bottom" v-if="users_prop.length > visibleCount && slider_index > 0" @click="previousTeam">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </div> -->

        <div class="team-slider-wrapper">
            <div class="team-slider" :style="{ display: 'flex', gap: '2%', width: (users_prop.length * 32) + '%', transform: 'translateX(' + (-slider_index * 32) + '%)', transition: 'transform 0.5s ease' }">
                <div class="team-item" v-for="user in users_prop" :key="user.id" @click="show_user_modal(user.id)">
                    <div :style="'background-image: url(/public/images/site_img/demo_imgs/user_demo_img.gif);'" class='user_img' v-if='user.image == null'> </div>
                    <div :style="'background-image: url(/public/images/user_profil_img/' + user.image + ');'" class='user_img' v-else> </div>
                    <span class="user_name">{{ user.name }} {{ user.surname }}</span>
                </div>
            </div>
        </div>

        <div class="next_team_bottom" v-if="users_prop.length > visibleCount && slider_index < users_prop.length - visibleCount" @click="nextTeam">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
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
        props: [
            'users_prop',
        ],
        data: function () {
            return {
                slider_index: 0,
                visibleCount: 3,
            };
        },
        components: {
            user_modal
        },
        mounted() {
            // 
        },
        methods: {
            show_user_modal(user_id){
                this.$refs.user_modal.show_modal(user_id)
            },

            nextTeam(){
                if (this.slider_index < this.users_prop.length - this.visibleCount) {
                    this.slider_index += 1;
                }
            },

            previousTeam(){
                if (this.slider_index > 0) {
                    this.slider_index -= 1;
                }
            },
        }
    }
</script>

<style>
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
        flex: 0 0 32%;
        max-width: 32%;
        text-align: center;
        cursor: pointer;
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
