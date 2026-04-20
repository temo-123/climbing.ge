<template>
    <main class="summit_map_page">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center py-5">
                    <h1 class="display-4 mb-3 summit-title">{{ $t('summit.menu.map') }}</h1>
                    <div class="bar mb-4"><i class="fa fa-map-marker" style="font-size: 3rem; color: #005f43;"></i></div>
                    <p class="lead text-muted">{{ $t('summit.summit_map_description') }}</p>    
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
import { Html5QrcodeScanner } from 'html5-qrcode';
import metaData from '../items/MetaDataComponent.vue';

export default {
  components: {
    metaData
  },
  data() {
    return {
      isScanning: false,
      scanner: null,
      scanError: '',
      scannedData: '',
      handleClick: this.handleClick // declare if needed elsewhere
    };
  },
  computed: {
    //
  },
  methods: {
    //
  }
};
</script>
