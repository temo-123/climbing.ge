<template>
    <main role="main" class="container" @click="handleClick">
        <div class="container text-center py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h1 class="display-4 mb-5 summit-title">{{ $t('summit.scan_qr.title') || 'Scan QR Code' }}</h1>
                    <div class="bar mb-4"><i class="fa fa-qrcode" style="font-size: 3rem; color: #005f43;"></i></div>
                        <button v-if="isMobile && !isScanning" @click="scanQR" class="btn btn-success btn-lg btn-scan-qr">
                        <i class="fa fa-qrcode"></i> {{ $t('summit.scan_qr.button') || 'Scan QR code' }}
                    </button>
                    <div v-else-if="!isMobile" class="alert alert-warning text-center py-4">
                        <i class="fa fa-desktop fa-3x mb-3 text-warning"></i>
                        <h4>{{ $t('summit.scan_qr.pc_message') || 'This function is not active for personal computer' }}</h4>
                        <p class="mb-0">Please use a mobile device to scan QR codes.</p>
                    </div>
                    <p v-if="!isScanning" class="mt-4 text-muted">{{ $t('summit.scan_qr.instruction') || 'Point your camera at the QR code to start climbing summit registration' }}</p>
                </div>
            </div>

            <!-- QR Scanner Overlay -->
            <div v-if="isScanning" class="qr-scanner-overlay" @click="closeScanner">
                <div class="scanner-container" @click.stop>
                    <div class="scanner-header">
                        <h3>{{ $t('summit.scan_qr.scanning') || 'Scanning QR Code...' }}</h3>
                        <button class="btn btn-secondary close-btn" @click="closeScanner">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div ref="scannerEl" class="scanner-viewport"></div>
                    <div class="scanner-frame">
                        <div class="scan-area"></div>
                    </div>
                    <div class="scanner-instructions">
                        <p>{{ $t('summit.scan_qr.instruction_scan') || 'Point camera at QR code inside frame' }}</p>
                        <div v-if="scanError" class="alert alert-danger">
                            {{ scanError }}
                        </div>
                        <div v-if="scannedData" class="alert alert-success">
                            <strong>Scanned:</strong> {{ scannedData }}
                        </div>
                    </div>
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
    isMobile() {
      const ua = navigator.userAgent;
      const isMobileUA = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);
      const isMobileWidth = window.innerWidth < 768;
      const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
      return isMobileUA || isMobileWidth || isTouch;
    }
  },
  methods: {
    handleClick() {}, // existing empty handler
    scanQR() {
      // if (!this.isMobile) {
      //   alert('This feature is only available on mobile devices.');
      //   return;
      // }
      this.isScanning = true;
      this.scanError = '';
      this.scannedData = '';

      this.$nextTick(() => {
        this.initScanner();
      });
    },
    async initScanner() {
      const config = { 
        fps: 10, 
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
      };
      
      try {
        this.scanner = new Html5QrcodeScanner(
          this.$refs.scannerEl,
          config,
          false // verbose=false
        );

        this.scanner.render(
          this.onScanSuccess,
          this.onScanError,
          this.onScanFailure
        );
      } catch (err) {
        this.scanError = 'Scanner initialization failed: ' + err;
      }
    },
    onScanSuccess(decodedText) {
      this.scannedData = decodedText;
      this.scanError = '';
      
      // Handle scanned data - assume contains summit ID or route
      // TODO: Parse if needed, e.g. const summitId = decodedText.split('/')[1];
      
      this.closeScanner();
      
      // Navigate to summit list or process data
      alert(`QR Scanned: ${decodedText}`); // temp
      this.$router.push('/list'); // or { path: `/summit/${decodedText}` }
    },
    onScanError(err) {
      // Expected errors like "No QR found", ignore for smooth UX
      console.log('Scan error (expected):', err);
    },
    onScanFailure(err) {
      this.scanError = 'Scan failed: ' + err;
    },
    closeScanner() {
      if (this.scanner) {
        this.scanner.clear();
        this.scanner = null;
      }
      this.isScanning = false;
      this.scanError = '';
      this.scannedData = '';
    }
  },
  beforeUnmount() {
    this.closeScanner();
  }
};
</script>

<style scoped>
/* Existing styles preserved */
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

/* New QR Scanner Styles */
.qr-scanner-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.9);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.scanner-container {
  background: white;
  border-radius: 12px;
  width: 90vw;
  max-width: 400px;
  height: 80vh;
  max-height: 600px;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.scanner-header {
  padding: 20px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.scanner-header h3 {
  margin: 0;
  color: #333;
}

.close-btn {
  padding: 8px 12px;
  font-size: 18px;
}

.scanner-viewport {
  flex: 1;
  position: relative;
  overflow: hidden;
  border-radius: 0 0 12px 12px;
}

.scanner-frame {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 250px;
  height: 250px;
}

.scan-area {
  width: 200px;
  height: 200px;
  border: 3px solid #28a745;
  border-radius: 8px;
  margin: 25px auto 0;
  position: relative;
  background: transparent;
}

.scan-area::before,
.scan-area::after {
  content: '';
  position: absolute;
  width: 30px;
  height: 3px;
  background: #28a745;
}

.scan-area::before {
  top: 0;
  left: 0;
  right: 0;
}

.scan-area::after {
  bottom: 0;
  left: 0;
  right: 0;
}

.scanner-instructions {
  padding: 20px;
  text-align: center;
  font-size: 14px;
  color: #666;
}

@media (max-width: 768px) {
  .scanner-container {
    width: 95vw;
    height: 90vh;
    border-radius: 0;
  }
}
</style>

