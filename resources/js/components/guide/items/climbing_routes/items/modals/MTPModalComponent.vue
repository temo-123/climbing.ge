<template>
<StackModal
        v-model="is_show_mtp_modal"
        :title="$t('guide.route.mtp_title')"
        :modal-class="{ [modalClass]: true }"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
        @close="close_mtp_modal"
    >

        <div class="model-body">
            <div class="container">
                <!-- Loading -->
                <div v-if="loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">{{ $t('guide.route.loading_mtp_details') }}</p>
                </div>

                <div v-else>
                    <!-- MTP overview -->
                    <div class="modal-section overview">
                        <h2 class="section-title">{{ $t('guide.route.mtp_details') }}</h2>
                        <p class="route-detail">{{ $t("guide.route.name") }} — {{ mtpName }}</p>
                        <p class="route-detail" v-if="mtp_detals.mtp && mtp_detals.mtp.height">{{ $t("guide.route.height") }} — {{ mtp_detals.mtp.height }}</p>
                        <div v-if="mtpText" class="mtp-description mt-2" v-html="mtpText"></div>
                        <div class="mt-3" v-if="mtp_detals.reviews_count > 0">
                            <starsReiting
                                :reviews_count_prop="mtp_detals.reviews_count"
                                :reviews_stars_prop="mtp_detals.reviews_stars"
                            />
                        </div>
                    </div>

                    <!-- Pitches section -->
                    <div class="modal-section pitches">
                        <h3 class="section-title">{{ $t('guide.route.pitches') }}</h3>

                        <div v-if="!hasPitches" class="text-center py-3 text-muted">
                            <p>{{ $t('guide.route.no_pitch_info') }}</p>
                        </div>

                        <div v-else>
                            <!-- ── Single canvas: sector image + ALL pitch lines ── -->
                            <div v-if="pitchImageSrc" class="pitch-canvas-block mb-2">
                                <canvas-json-show
                                    :json_items="allPitchJsonItems"
                                    :image_src="pitchImageSrc"
                                    :composite_src="pitchCompositeSrc"
                                    :selected_id="selected_pitch_id"
                                    :show_all="true"
                                    :interactive="true"
                                    @item-click="e => selectPitch(e.id)"
                                />
                            </div>

                            <!-- Selection hint -->
                            <div class="select-hint mb-2" v-if="pitchImageSrc">
                                <template v-if="selected_pitch_id && selectedPitch">
                                    <span class="green-tag">&#9679; Pitch {{ selectedPitch.num }}: {{ selectedPitch.name }} highlighted</span>
                                    <span class="text-muted small ms-2">— click again to deselect</span>
                                </template>
                                <template v-else>
                                    <span class="text-muted small">Click a row to highlight the pitch on the image</span>
                                </template>
                            </div>

                            <!-- Pitch table (clickable) -->
                            <div class="table-responsive mb-3">
                                <table class="table table-hover pitch-table">
                                    <thead>
                                        <tr>
                                            <th>N</th>
                                            <th>{{ $t("guide.route.name") }}</th>
                                            <th>{{ $t("guide.route.height") }}</th>
                                            <th>{{ $t("guide.route.bolts") }}</th>
                                            <th>{{ $t("guide.route.grade fr") }}</th>
                                            <th class="display-none-720px">{{ $t("guide.route.grade yds") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="pitch in mtp_detals.mtp_pitchs"
                                            :key="pitch.id"
                                            :ref="'pitch-row-' + pitch.id"
                                            class="pitch-row"
                                            :class="{ 'pitch-active': selected_pitch_id == pitch.id }"
                                            @click="selectPitch(pitch.id)"
                                        >
                                            <td>{{ pitch.num }}</td>
                                            <td>{{ pitch.name || '—' }}</td>
                                            <td>{{ pitch.height || '—' }}</td>

                                            <td v-if="pitch.category === 'tred'">Trad</td>
                                            <td v-else-if="pitch.category === 'sport climbing'">{{ pitch.bolts || '?' }}</td>
                                            <td v-else>—</td>

                                            <td v-if="pitch.or_grade">{{ pitch.grade || '—' }} / {{ pitch.or_grade }}</td>
                                            <td v-else>{{ pitch.grade || '—' }}</td>

                                            <td class="display-none-720px" v-if="pitch.or_grade">{{ lead_grade_chart(pitch.grade || '') }} / {{ lead_grade_chart(pitch.or_grade) }}</td>
                                            <td class="display-none-720px" v-else>{{ lead_grade_chart(pitch.grade || '') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pitch text descriptions (no per-pitch canvas — all on single canvas above) -->
                            <div
                                v-for="pitch in pitchesWithText"
                                :key="'text-' + pitch.id"
                                class="pitch-text-block p-2 mb-2"
                                :class="{ 'pitch-text-selected': selected_pitch_id == pitch.id }"
                            >
                                <strong>{{ $t('guide.route.pitches') }} {{ pitch.num }}: {{ pitch.name }}</strong>
                                <div v-html="pitchText(pitch)" class="mt-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-row mt-3 w-100">
                        <button class="btn btn-success" @click="open_review_modal">
                            {{ $t('guide.route.make_review') }}
                        </button>
                        <button
                            class="btn btn-primary float-right"
                            v-if="mtp_detals.reviews_count > 0"
                            @click="open_all_reviews_modal"
                        >
                            {{ $t('guide.route.show_feedbacks') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
import grade_chart from '../../../../../../mixins/grade_chart_mixin.js'
import starsReiting from '../../../../../global_components/StarReitingShowComponent.vue'

export default {
    mixins: [grade_chart],
    components: { starsReiting },
    props: [],
    data() {
        return {
            is_show_mtp_modal: false,
            mtp_detals: {},
            modalClass: '',
            loading: false,
            selected_pitch_id: null,
        };
    },
    computed: {
        currentLocale() {
            return localStorage.getItem('locale') || 'us';
        },
        mtpName() {
            return this.mtp_detals.mtp ? this.mtp_detals.mtp.name : '';
        },
        mtpText() {
            if (!this.mtp_detals || !this.mtp_detals.mtp) return '';
            const m = this.mtp_detals.mtp;
            if (this.currentLocale === 'ka') return m.text_ka || m.text_us || '';
            return m.text_us || m.text_ka || '';
        },
        hasPitches() {
            return this.mtp_detals.mtp_pitchs && this.mtp_detals.mtp_pitchs.length > 0;
        },
        // Build json_items array for canvas-json-show — all pitches with drawings
        allPitchJsonItems() {
            if (!this.mtp_detals.mtp_pitchs) return [];
            return this.mtp_detals.mtp_pitchs
                .map(p => ({ id: p.id, json: this.parsePitchJson(p) }))
                .filter(item => item.json !== null);
        },
        // Sector image src — from the first pitch that has a drawing
        pitchImageSrc() {
            if (!this.mtp_detals.mtp_pitchs) return null;
            const p = this.mtp_detals.mtp_pitchs.find(p => p.json && p.json.sector_image_filename);
            if (!p) return null;
            return p.json.has_original
                ? '/public/images/sector_img/origin_img/' + p.json.sector_image_filename
                : '/public/images/sector_img/' + p.json.sector_image_filename;
        },
        pitchCompositeSrc() {
            if (!this.mtp_detals.mtp_pitchs) return null;
            const p = this.mtp_detals.mtp_pitchs.find(p => p.json && p.json.sector_image_filename && p.json.has_original);
            return p ? '/public/images/sector_img/' + p.json.sector_image_filename : null;
        },
        selectedPitch() {
            if (!this.selected_pitch_id || !this.mtp_detals.mtp_pitchs) return null;
            return this.mtp_detals.mtp_pitchs.find(p => p.id == this.selected_pitch_id) || null;
        },
        pitchesWithText() {
            if (!this.mtp_detals.mtp_pitchs) return [];
            return this.mtp_detals.mtp_pitchs.filter(p => p.text_us || p.text_ka);
        },
    },
    methods: {
        pitchText(pitch) {
            if (this.currentLocale === 'ka') return pitch.text_ka || pitch.text_us || '';
            return pitch.text_us || pitch.text_ka || '';
        },
        // Double-parse: DB stores JSON.stringify(paper.exportJSON()) → double-encoded
        parsePitchJson(pitch) {
            if (!pitch.json || !pitch.json.json) return null;
            let j = pitch.json.json;
            try {
                if (typeof j === 'string') j = JSON.parse(j);
                if (typeof j === 'string') j = JSON.parse(j);
            } catch(_) { return null; }
            return j;
        },
        selectPitch(pitchId) {
            this.selected_pitch_id = this.selected_pitch_id == pitchId ? null : pitchId;
            if (this.selected_pitch_id) {
                this.$nextTick(() => {
                    const rows = this.$refs['pitch-row-' + this.selected_pitch_id];
                    const el   = Array.isArray(rows) ? rows[0] : rows;
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            }
        },
        lead_grade_chart(grade_fr)    { return this.lead(grade_fr); },
        boulder_grade_chart(grade_fr) { return this.boulder(grade_fr); },

        show_mtp_modal(id) {
            this.loading = true;
            this.mtp_detals = {};
            this.selected_pitch_id = null;
            this.is_show_mtp_modal = false;
            axios.get("/get_mtp/get_mtp_for_modal/" + id)
                .then(response => {
                    this.mtp_detals = response.data;
                    this.loading = false;
                    this.is_show_mtp_modal = true;
                })
                .catch(() => {
                    this.mtp_detals = {};
                    this.loading = false;
                });
        },
        close_mtp_modal() {
            this.is_show_mtp_modal = false;
            this.loading = false;
            this.mtp_detals = {};
            this.selected_pitch_id = null;
        },
        open_review_modal() {
            this.is_show_mtp_modal = false;
            this.$emit('show_mtp_review_modal', this.mtp_detals.mtp.id);
        },
        open_all_reviews_modal() {
            this.is_show_mtp_modal = false;
            this.$emit('show_mtp_all_review_modal', this.mtp_detals.mtp.id);
        },
    },
};
</script>

<style scoped>
.modal-section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.section-title {
    font-weight: 600;
    font-size: 1.4rem;
    color: #495057;
    margin-bottom: 0.75rem;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.4rem;
}
.route-detail {
    margin-bottom: 0.4rem;
    font-size: 1rem;
    color: #343a40;
}
/* Canvas block */
.pitch-canvas-block {
    border-radius: 6px;
    overflow: hidden;
    background: #111;
}
/* Selection hint */
.select-hint { min-height: 22px; font-size: 13px; }
.green-tag {
    background: #00e64d;
    color: #000;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
/* Pitch table */
.pitch-table { margin-bottom: 0; }
.pitch-table th, .pitch-table td {
    vertical-align: middle;
    text-align: center;
    font-size: 14px;
    padding: 6px 8px;
}
.pitch-table th:nth-child(2), .pitch-table td:nth-child(2) { text-align: left; }
.pitch-row { cursor: pointer; }
.pitch-active { outline: 2px solid #00e64d; outline-offset: -2px; }
.pitch-active td { background-color: rgba(0,230,77,0.18) !important; font-weight: 600; }
/* Text blocks */
.pitch-text-block {
    border-left: 3px solid #dee2e6;
    border-radius: 4px;
    background: #fff;
    transition: border-color 0.2s;
}
.pitch-text-selected { border-left-color: #00e64d; }
</style>
