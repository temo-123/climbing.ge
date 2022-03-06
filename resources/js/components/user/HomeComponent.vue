<template>
    <div>
        <navbar />

        <div class="container top_menu_margin">
            <div class="container bootstrap snippet mt-5 ">
                <div class="row">
                    <div class="col-sm-3">

                        <leftMenu />

                    </div>
                    <div class="col-sm-9">
                        <!-- <div class="container top_menu_margin h-recent-work"> -->
                            <router-view />
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="footer__graphic" :style="'background-image: url(../images/svg/mountains.svg)'"></div>
        <footter /> -->
    </div>  
</template>

<script>
    import navbar from './items/NavbarComponent'
    import leftMenu from './items/LeftMenuComponent'
    // import footter from './items/FooterComponent'

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
            leftMenu,
            // footter,
        },
        mounted() {
        }
    }
</script>