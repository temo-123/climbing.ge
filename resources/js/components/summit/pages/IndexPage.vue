<template>
    <div>

        <!-- ── Hero ── -->
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.scan_qr.title') }}</h1>
                <div class="bar"><i class="fa fa-flag-checkered"></i></div>
                <h3 class="article_list_short_description" v-html="$siteData.data.summit_index_intro || $t('summit.scan_qr.instruction')"></h3>

                <!-- CTA row -->
                <div class="text-center" style="margin: 20px 0 30px; display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
                    <!-- Mobile: QR scan -->
                    <button v-if="isMobile && !isScanning" @click="scanQR" class="btn btn-success btn-lg btn-send main-btn">
                        <i class="fa fa-qrcode"></i> {{ $t('summit.scan_qr.button') }}
                    </button>
                    <router-link to="/summits/list" class="btn btn-default btn-lg btn-send main-btn">
                        <i class="fa fa-list"></i> {{ $t('summit.menu.list') }}
                    </router-link>
                    <router-link to="/summits/map" class="btn btn-default btn-lg btn-send main-btn">
                        <i class="fa fa-map-marker"></i> {{ $t('summit.menu.map') }}
                    </router-link>
                </div>

                <!-- Stats strip -->
                <div v-if="!loading" class="row summit-index-stats">
                    <div class="col-xs-4 text-center">
                        <div class="summit-index-stat-num">{{ summits.length }}</div>
                        <div class="summit-index-stat-lbl">Summits</div>
                    </div>
                    <div class="col-xs-4 text-center">
                        <div class="summit-index-stat-num">
                            {{ summits.filter(s => s.height).length > 0
                                ? Math.max(...summits.filter(s => s.height).map(s => s.height)).toLocaleString() + 'm'
                                : '—' }}
                        </div>
                        <div class="summit-index-stat-lbl">Highest peak</div>
                    </div>
                    <div class="col-xs-4 text-center">
                        <div class="summit-index-stat-num">
                            {{ summits.filter(s => s.height).length > 0
                                ? Math.round(summits.filter(s=>s.height).reduce((a,s)=>a+s.height,0)/summits.filter(s=>s.height).length).toLocaleString() + 'm'
                                : '—' }}
                        </div>
                        <div class="summit-index-stat-lbl">Avg elevation</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── Featured summits ── -->
        <div class="h-recent-work">
            <div class="container">

                <h2 class="index_h2" style="font-size:180%">
                    <i class="fa fa-flag-checkered"></i> Summits
                </h2>

                <div v-if="loading" class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                </div>

                <div v-else-if="summits.length === 0" class="text-center py-4 text-muted">
                    <i class="fa fa-exclamation-circle fa-2x mb-2"></i>
                    <p>{{ $t('summit.no_summits') }}</p>
                </div>

                <div v-else>
                    <div class="row smt-grid">
                        <div v-for="summit in featuredSummits" :key="summit.id" class="col-md-4 col-sm-6 smt-grid__col">
                            <summit-card :summit="summit" />
                        </div>
                    </div>

                    <div v-if="summits.length > 6" class="text-center" style="margin-top:20px">
                        <router-link to="/summits/list" class="btn btn-default btn-send main-btn">
                            <i class="fa fa-arrow-right"></i> View all {{ summits.length }} summits
                        </router-link>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── How it works ── -->
        <div class="h-recent-work">
            <div class="container">
                <how_summit_works />
            </div>
        </div>

        <!-- ── My ascents CTA (logged in) ── -->
        <div class="h-recent-work">
            <div class="container text-center">
                <h2 class="index_h2" style="font-size:160%">
                    <i class="fa fa-trophy" style="color:#f0a500"></i> Ready to climb?
                </h2>
                <p class="article_list_short_description" style="margin-bottom:24px">
                    Reach the summit, scan the QR code, and your ascent is recorded forever.
                </p>
                <div style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
                    <router-link to="/summits/list" class="btn btn-success btn-lg btn-send main-btn">
                        <i class="fa fa-flag-checkered"></i> Choose a Summit
                    </router-link>
                    <a v-if="isLoggedIn" :href="userAscentsUrl" class="btn btn-default btn-lg btn-send main-btn">
                        <i class="fa fa-list-ol"></i> My Ascents
                    </a>
                </div>
            </div>
        </div>

        <!-- ── QR Scanner overlay ── -->
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
import how_summit_works from '../items/how_summit_works.vue'
import SummitCard from '../items/cards/SummitCardComponent.vue'

export default {
    components: { metaData, how_summit_works, SummitCard },
    data() {
        return {
            summits: [],
            loading: true,
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
        },
        featuredSummits() {
            return this.summits.slice(0, 6)
        },
        isLoggedIn() {
            return !!(localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token'))
        },
        userAscentsUrl() {
            return (process.env.MIX_APP_SSH || 'http://') + (process.env.MIX_USER_PAGE_URL || '') + '/my_ascents'
        },
    },
    mounted() {
        if (!this.$siteData.loaded) this.get_site_data();
        this.loadSummits();
    },
    beforeUnmount() { this.closeScanner(); },
    methods: {
        loadSummits() {
            this.loading = true
            axios.get('summit/list')
                .then(r => { this.summits = r.data })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },
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
};
</script>

<style scoped>
/* ── Stats strip ── */
.summit-index-stats {
    background: #f8f8f8;
    border-radius: 8px;
    padding: 16px 0;
    margin-bottom: 10px;
}
.summit-index-stat-num {
    font-size: 1.9rem;
    font-weight: bold;
    color: #1d7a48;
    line-height: 1.1;
}
.summit-index-stat-lbl {
    font-size: 11px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-top: 3px;
}

/* ── Summit cards grid ── */
.smt-grid { margin-top: 10px; }
.smt-grid__col { margin-bottom: 24px; display: flex; }
.smt-grid__col > * { width: 100%; }

/* ── QR scanner overlay (unchanged from before) ── */
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
