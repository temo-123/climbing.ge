<template>
    <div>
        <navbar />
        <div class="container top_menu_margin h-recent-work">
            <router-view />
        </div>

        <div class="footer__graphic" :style="'background-image: url(../images/svg/mountains.svg)'"></div>
        <footter />
    </div>  
</template>

<script>
    import navbar from './items/NavbarComponent'
    import footter from './items/FooterComponent'

    export default {
        data: function () {
            return {
                search_query: '',
                // MIX_APP_URL: process.env.MIX_APP_URL,
                // MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        components: {
            navbar,
            footter,
        },
        mounted(){
            axios
            .get('/api/user')
            .then((response)=>{
                this.user = response.data
                console.log(this.user);
            })
        },
    }
</script>