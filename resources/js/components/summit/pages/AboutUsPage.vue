<template>
  <div>
    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8 blog-main">
          <section class="hero">
            <div class="container">
              <div class="row">
                <h1>{{ $t('summit.about.title') || 'About Summit Climbing' }}</h1>
                <div class="about-content">
                  <p>{{ $t('summit.about.welcome') || 'Welcome to Summit Climbing – your ultimate platform for mountain adventures.' }}</p>
                  <p>{{ $t('summit.about.mission') || 'Our mission is to connect climbers with epic summits worldwide through QR code registration and community sharing.' }}</p>
                  <div class="row mt-4">
                    <div class="col-md-4">
                      <i class="fa fa-mountain fa-3x text-success mb-3"></i>
                      <h5>Explore Summits</h5>
                      <p>Browse our list of challenging peaks.</p>
                    </div>
                    <div class="col-md-4">
                      <i class="fa fa-qrcode fa-3x text-success mb-3"></i>
                      <h5>QR Registration</h5>
                      <p>Scan QR codes at summits to log your climbs.</p>
                    </div>
                    <div class="col-md-4">
                      <i class="fa fa-users fa-3x text-success mb-3"></i>
                      <h5>Climber Community</h5>
                      <p>Share experiences and connect with others.</p>
                    </div>
                  </div>
                  <router-link to="/list" class="btn btn-success mt-4">{{ $t('summit.about.view_list') || 'View Summit List' }}</router-link>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </main>

    <metaData 
        :title="$t('summit.about.title') || 'About Summit Climbing'"
        :description="$t('summit.about.meta') || 'Learn about our summit climbing platform'"
        :image="'/public/images/meta_img/summit.jpg'"
    />
  </div>
</template>

<script>
  import metaData from '../items/MetaDataComponent.vue'

  export default {
    name: 'AboutUsPage',
    components: {
      metaData
    },
  }
</script>
