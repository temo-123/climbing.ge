<template>
    <div class="food row">
        
        <div class="col-md-12">
            <span v-for="user in users_prop" :key="user.id">
                <div class="col-xs-6 col-md-4 col-lg-4 cursor_pointer" @click="show_user_modal(user.id)" >
                    <div class="row">
                        <div :style="'background-image: url(/public/images/site_img/demo_imgs/user_demo_img.gif);'" class='user_img' v-if='user.image == null'> </div>
                        <div :style="'background-image: url(/public/images/user_profil_img/' + user.image + ');'" class='user_img' v-else> </div>
                    </div>
                    <div class="row">
                        <span class="user_name">{{ user.name }} {{ user.surname }}</span>
                    </div>
                </div>
            </span>
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
                // 
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
        }
    }
</script>

<style>
    .user_name{
        font-size: 1.5em;
    }
    .user_img{
        width: 15em;
        height: 15em;
        border-radius: 50%;
        background-repeat: no-repeat;
        background-position: center center;
    }
</style>