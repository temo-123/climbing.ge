<template>
  <div>
    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8 blog-main">
          <section class="hero">
            <div class="container">
              <div class="row">
                <h1>{{ $t('blog.about_us.title') }}</h1>
                <p>{{ $t('blog.about_us.welcome') }}</p>
                <p>{{ $t('blog.about_us.mission') }}</p>
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
