<template>
    <main role="main" class="container">
        <div class="container text-center py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="display-4 mb-5 summit-title">{{ $t('summit.scan_qr.title') || 'Scan QR Code' }}</h1>
                    <div class="bar mb-4"><i class="fa fa-qrcode" style="font-size: 3rem; color: #005f43;"></i></div>
                    <button @click="scanQR" class="btn btn-success btn-lg btn-scan-qr">
                        <i class="fa fa-qrcode"></i> {{ $t('summit.scan_qr.button') || 'Scan QR code' }}
                    </button>
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
    methods: {
      scanQR() {
        // Placeholder for QR scan logic - integrate jsQR or html5-qrcode lib
        alert('QR Scanner would open here. Integrate camera access and QR detection.')
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

