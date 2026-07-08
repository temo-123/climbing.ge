<template>
  <div>
      <preloader />
      <routeloader />
      <navbar />
      <div class="container top_menu_margin h-recent-work">
          <router-view /> 
      </div>

      <div class="footer__graphic" :style="'background-image: url(../images/svg/mountains.svg)'"></div>
      <footter />
      <loginModal />
      <go_to_top />
      <toast-notification />
  </div>
</template>

<script>
  import navbar from './items/NavbarComponent'
  import footter from './items/FooterComponent'
  import loginModal from "../global_components/modals/LoginModalComponent.vue";
    import go_to_top from "../global_components/GoOnTopComponent.vue";
  
  import preloader from "../global_components/loaders/PreloaderComponent.vue";
  import routeloader from "../global_components/loaders/RouteloaderComponent.vue";

  export default {
      data: function () {
          return {
            // cart_products: [],
          };
      },
      components: {
          navbar,
          footter,
          loginModal,
          preloader,
            go_to_top,
            routeloader
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


</style>