<template>
    <div class="team-members-slider" v-if="users.length > 0">
        <card-slider :items="users" item-key="id" :desktop="3" :mobile="1" :autoplay="5000" :gap="24">
            <template #default="{ item: user }">
                <div class="team-card" @click="show_user_modal(user.id)">
                    <div class="user_img_wrapper">
                        <div :style="'background-image: url(/public/images/site_img/demo_imgs/user_demo_img.gif);'" class='user_img' v-if='user.image == null'> </div>
                        <div :style="'background-image: url(/public/images/user_profil_img/' + user.image + ');'" class='user_img' v-else> </div>
                    </div>
                    <span class="user_name">{{ user.name }} {{ user.surname }}</span>
                    <span class="user_status" v-if="showStatus && user.member_status">{{ user.member_status }}</span>
                </div>
            </template>
        </card-slider>

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
        components: {
            user_modal
        },
        methods: {
            show_user_modal(user_id){
                this.$refs.user_modal.show_modal(user_id, this.modalType)
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

    @media (hover: hover) {
        .team-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.14);
        }

        .team-card:hover .user_img_wrapper {
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(124, 124, 253, 0.4);
        }
    }

    @media (max-width: 767px) {
        .user_img_wrapper {
            width: 7em;
            height: 7em;
        }

        .team-card {
            padding: 20px 14px 18px;
        }
    }
</style>
