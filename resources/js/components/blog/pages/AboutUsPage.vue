<template>
  <div>
    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8 blog-main">
          <section class="hero">
            <div class="container">
              <div class="row">
                <h1>About Us</h1>
                <p>Welcome to our blog! We are passionate about sharing knowledge and stories.</p>
                <p>Our mission is to provide valuable content on various topics.</p>
              </div>
            </div>
          </section>
        </div>
        <rightMenu />
      </div>
    </main>

    <metaData 
        :title = " $t('blog.meta.index') "
        :description = "this.$siteData.data.guid_short_description"
        :image = "'/public/images/meta_img/outdoor.jpg'"
    />
  </div>
</template>

<script>
  import metaData from '../items/MetaDataComponent'

  export default {
    name: 'AboutUsPage',
    components: {
      metaData
    },
  }
</script>
