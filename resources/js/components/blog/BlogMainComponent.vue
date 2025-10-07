<template>
  <div>
      <preloader />
      <navbar />
      <div class="container top_menu_margin h-recent-work">
          <router-view /> 
      </div>

      <div class="footer__graphic" :style="'background-image: url(../images/svg/mountains.svg)'"></div>
      <footter />
      <loginModal />
  </div>
</template>

<script>
  import navbar from './items/NavbarComponent'
  import footter from './items/FooterComponent'
  // import loginModal from "../global_components/LoginModalComponent.vue";
  import Preloader from "../global_components/PreloaderComponent.vue";

  export default {
      data: function () {
          return {
            // cart_products: [],
          };
      },
      components: {
          navbar,
          footter,
          // loginModal,
          Preloader
      },
      mounted(){
        //
      },
      methods: {
        //
      }
  }
</script>

<style scoped>
    .top_menu {
        background-color: #005f43 !important;
    }

    .preloader_background {
        background: radial-gradient(circle, #005f43 0% 0%, #018b62 100%) !important;
    }

    .loader {
        border-top-color: #005f43 !important;
    }

    .navbar {
        background-color: #005f43 !important;
        border-color: #6a8991;
    }

    .navbar-inverse .navbar-nav>.open>a{
        background-color: #005f43 !important;
    }

    .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover{
        background-color: #005f43 !important;
    }

    .navbar-inverse .navbar-collapse{
        border-color: #838383 !important;
    }

    .navbar-collapse {
        max-height: none !important;
    }
</style>