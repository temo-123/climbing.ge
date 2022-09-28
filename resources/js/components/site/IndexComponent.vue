<template>
    <div>
        <loader v-if="loader" object="#ff9633" color1="#ffffff" color2="#17fd3d" size="7" speed="2.5" bg="#3d3d3d" objectbg="#737373" opacity="99" disableScrolling="true" name="circular"></loader>

        <!-- <div class="vld-parent">
            <loading v-model="isLoading"
                    :can-cancel="true"
                    :on-cancel="onCancel"
                    :is-full-page="fullPage"/>

            <label><input type="checkbox" v-model="fullPage">Full page?</label>
            <button @click.prevent="doAjax">fetch Data</button>
        </div> -->

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


    // import Loading from 'vue-loading-overlay';
    // import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        data: function () {
            return {
                search_query: '',
                // MIX_APP_URL: process.env.MIX_APP_URL,
                // MIX_APP_SSH: process.env.MIX_APP_SSH,

                // isLoading: false,
                // fullPage: true

                loader: false
            };
        },
        components: {
            navbar,
            footter,

            // Loading
        },
        mounted(){
            // axios
            // .get('/api/user')
            // .then((response)=>{
            //     this.user = response.data
            //     console.log(this.user);
            // })
        },

        methods: {
            // doAjax() {
            //     this.isLoading = true;
            //     // simulate AJAX
            //     setTimeout(() => {
            //         this.isLoading = false
            //     }, 5000)
            // },
            // onCancel() {
            //     console.log('User cancelled the loader.')
            // }
        }
    }
</script>