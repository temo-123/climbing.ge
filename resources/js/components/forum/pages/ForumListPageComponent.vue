<template>
<div>
    <main role="main" class="container">

      <div class="row">
        <div class="col-md-8 blog-main">
            <section class="hero">
                <div class="container">
                    <div class="row">	

                      <addBlock />
                    
                      <post />
                      
                      <!-- <instaPost /> -->
                    </div>
                </div>
            </section>
        </div>

        <rightMenu />

      </div>
    </main>
</div>
</template>

<script>
  import addBlock from '../items/AddBlockComponent.vue'
  import post from '../items/PostComponent.vue'
  // import instaPost from '../items/InstaPostsComponent.vue'
  import rightMenu from '../items/RightMenuComponent.vue'
  export default {
    components: {
      addBlock,
      post,
      rightMenu,
      // instaPost
    }
  }
</script>

