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

<style>
    /* Firefox scrollbar color*/
    html {
        scrollbar-color: #005f43 white;
        scrollbar-width: thin;
        border-radius: 0%
    }
    /* Other brouser scrollbar color*/
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
        background-color: white;
        border-radius: 0%
    }
    ::-webkit-scrollbar-thumb {
        background: #005f43;
        border-radius: 5px;
        border-radius: 0%
    }
    /* Navbar collor */
    .top_menu {
        background-color: #005f43;
    }
    .preloader {
        background: #005f43;
    }
</style>