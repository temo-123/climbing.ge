<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.scan_qr.title') }}</h1>

                <div class="bar"><i class="fa fa-qrcode"></i></div>

                <h3 class="article_list_short_description" v-html="$siteData.data.summit_index_intro || $t('summit.scan_qr.instruction')"></h3>

                <!-- Scan card -->
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 text-center">

                        <!-- Mobile -->
                        <div v-if="isMobile && !isScanning">
                            <button @click="scanQR" class="btn btn-success btn-lg btn-send main-btn">
                                <i class="fa fa-qrcode"></i> {{ $t('summit.scan_qr.button') }}
                            </button>
                        </div>

                        <!-- Desktop -->
                        <div v-else-if="!isMobile">
                            <div class="alert alert-info">
                                <i class="fa fa-desktop fa-2x mb-2"></i>
                                <h4>{{ $t('summit.scan_qr.pc_message') }}</h4>
                                <p class="mb-0">{{ $t('summit.scan_qr.instruction') }}</p>
                            </div>
                            <router-link to="/summits/list" class="btn btn-default btn-send main-btn">
                                <i class="fa fa-list"></i> {{ $t('summit.menu.list') }}
                            </router-link>
                        </div>

                    </div>
                </div>

                <!-- How it works -->
                <div style="margin-top: 60px">
                    <h3 class="text-center" style="margin-bottom: 30px; text-transform: uppercase; letter-spacing: .08em; font-size: 14px; color: #888">
                        {{ $t('summit.how.title') }}
                    </h3>
                    <div class="row text-center">
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 20px">
                            <div class="bar" style="width:60px; margin: 0 auto 12px;">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <h4>{{ $t('summit.how.reach') }}</h4>
                            <p style="font-size:13px; color:#888" v-html="$siteData.data.summit_how_reach_desc"></p>
                        </div>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 20px">
                            <div class="bar" style="width:60px; margin: 0 auto 12px;">
                                <i class="fa fa-qrcode"></i>
                            </div>
                            <h4>{{ $t('summit.how.scan') }}</h4>
                            <p style="font-size:13px; color:#888" v-html="$siteData.data.summit_how_scan_desc"></p>
                        </div>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 20px">
                            <div class="bar" style="width:60px; margin: 0 auto 12px;">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <h4>{{ $t('summit.how.validate') }}</h4>
                            <p style="font-size:13px; color:#888" v-html="$siteData.data.summit_how_validate_desc"></p>
                        </div>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 20px">
                            <div class="bar" style="width:60px; margin: 0 auto 12px;">
                                <i class="fa fa-share-alt"></i>
                            </div>
                            <h4>{{ $t('summit.how.share') }}</h4>
                            <p style="font-size:13px; color:#888" v-html="$siteData.data.summit_how_share_desc"></p>
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 40px">
                    <router-link to="/summits/list" class="btn btn-default btn-send main-btn">
                        <i class="fa fa-arrow-right"></i> {{ $t('summit.menu.list') }}
                    </router-link>
                </div>

            </div>
        </div>

        <!-- QR Scanner Overlay -->
        <div v-if="isScanning" class="qr-scanner-overlay" @click="closeScanner">
            <div class="scanner-container" @click.stop>
                <div class="scanner-header">
                    <h3>{{ $t('summit.scan_qr.scanning') }}</h3>
                    <button class="btn btn-default close-btn" @click="closeScanner">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div ref="scannerEl" class="scanner-viewport"></div>
                <div class="scanner-instructions">
                    <p>{{ $t('summit.scan_qr.instruction_scan') }}</p>
                    <div v-if="scanError" class="alert alert-danger">{{ scanError }}</div>
                    <div v-if="scannedData" class="alert alert-success">
                        <strong>Scanned:</strong> {{ scannedData }}
                    </div>
                </div>
            </div>
        </div>

        <metaData
            :title="$t('summit.scan_qr.title') + ' – Summit Climbing Georgia'"
            :description="$t('summit.scan_qr.meta')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
import { Html5QrcodeScanner } from 'html5-qrcode';
import metaData from '../items/MetaDataComponent.vue';

export default {
    components: { metaData },
    data() {
        return {
            isScanning: false,
            scanner: null,
            scanError: '',
            scannedData: '',
        };
    },
    computed: {
        isMobile() {
            const ua = navigator.userAgent;
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua)
                || window.innerWidth < 768
                || 'ontouchstart' in window
                || navigator.maxTouchPoints > 0;
        }
    },
    methods: {
        scanQR() {
            this.isScanning = true;
            this.scanError = '';
            this.scannedData = '';
            this.$nextTick(() => { this.initScanner(); });
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
                this.scanner = new Html5QrcodeScanner(this.$refs.scannerEl, config, false);
                this.scanner.render(this.onScanSuccess, this.onScanError, this.onScanFailure);
            } catch (err) {
                this.scanError = 'Scanner initialization failed: ' + err;
            }
        },
        onScanSuccess(decodedText) {
            this.scannedData = decodedText;
            this.scanError = '';
            this.closeScanner();
            this.$router.push(decodedText.replace(window.location.origin, '') || '/summits/list');
        },
        onScanError() {},
        onScanFailure(err) { this.scanError = 'Scan failed: ' + err; },
        closeScanner() {
            if (this.scanner) { this.scanner.clear(); this.scanner = null; }
            this.isScanning = false;
            this.scanError = '';
            this.scannedData = '';
        }
    },
    mounted() {
        if (!this.$siteData.loaded) {
            this.get_site_data();
        }
    },
    beforeUnmount() { this.closeScanner(); }
};
</script>

<style scoped>
.qr-scanner-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.92);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.scanner-container {
    background: white;
    border-radius: 10px;
    width: 90vw;
    max-width: 400px;
    height: 80vh;
    max-height: 600px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.scanner-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.scanner-header h3 { margin: 0; font-size: 1.1rem; }
.scanner-viewport { flex: 1; position: relative; overflow: hidden; }
.scanner-instructions { padding: 14px 20px; text-align: center; font-size: 14px; color: #666; }
</style>
