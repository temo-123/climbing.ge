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
  // import catalog from './pages/CatalogComponent'
  import navbar from './items/NavbarComponent'
  import footter from './items/FooterComponent'

  export default {
      data: function () {
          return {
              // cart_products: [],
              search_query: '',
              // metaInfo: {
              //     // if no subcomponents specify a metaInfo.title, this title will be used
              //     title: 'Default Title',
              //     // all titles will be injected into this template
              //     titleTemplate: '%s | My Awesome Webapp'
              // }
          };
      },
      components: {
          // catalog,
          navbar,
          footter,
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
          
      }
  }
</script>

<style>

</style>