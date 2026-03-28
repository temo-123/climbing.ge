<template>
    <main role="main" class="container" @click="handleClick">
        <div class="container text-center py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="display-4 mb-5 summit-title">{{ $t('summit.scan_qr.title') || 'Scan QR Code' }}</h1>
                    <div class="bar mb-4"><i class="fa fa-qrcode" style="font-size: 3rem; color: #005f43;"></i></div>
                        <button v-if="isMobile" @click="scanQR" class="btn btn-success btn-lg btn-scan-qr">
                        <i class="fa fa-qrcode"></i> {{ $t('summit.scan_qr.button') || 'Scan QR code' }}
                    </button>
                    <div v-else class="alert alert-warning text-center py-4">
                        <i class="fa fa-desktop fa-3x mb-3 text-warning"></i>
                        <h4>{{ $t('summit.scan_qr.pc_message') || 'This function is not active for personal computer' }}</h4>
                        <p class="mb-0">Please use a mobile device to scan QR codes.</p>
                    </div>
                    <p class="mt-4 text-muted">{{ $t('summit.scan_qr.instruction') || 'Point your camera at the QR code to start climbing summit registration' }}</p>
                </div>
            </div>

            <metaData
                :title="$t('summit.scan_qr.title') || 'Scan QR Code - Summit Climbing'"
                :description="$t('summit.scan_qr.meta') || 'Scan QR code to register your summit climb'"
                :image="'/public/images/meta_img/qr-scan.jpg'"
            />
        </div>
    </main>
</template>

<script>
  import metaData from '../items/MetaDataComponent.vue'

  export default {
    components: {
      metaData
    },
    computed: {
      isMobile() {
        const ua = navigator.userAgent;
        const isMobileUA = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);
        const isMobileWidth = window.innerWidth < 768;
        const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        return isMobileUA || isMobileWidth || isTouch;
      }
    },
    methods: {
      scanQR() {
        if (!this.isMobile) {
          alert('This feature is only available on mobile devices.');
          return;
        }
        // Placeholder for QR scan logic - integrate jsQR or html5-qrcode lib
        alert('QR Scanner would open here. Integrate camera access and QR detection.')
        // Simulate navigation for demo (replace with actual QR logic)
        // this.$router.push('/list')
        // Example future implementation:
        // this.$refs.qrReader.startScan()
        // this.$router.push('/list') // Redirect to summit list after scan
      }
    }
  }
</script>

<style scoped>
.loading-more {
  text-align: center;
  padding: 20px;
  font-size: 16px;
  color: #666;
}

.show-older-button {
  display: block;
  margin: 20px auto;
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.show-older-button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.spinner-border {
  width: 1rem;
  height: 1rem;
  border-width: 0.2em;
  margin-right: 10px;
}
</style>

