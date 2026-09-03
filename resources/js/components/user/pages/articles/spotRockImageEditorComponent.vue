<template>
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 d-flex align-items-center gap-3">
                    <button class="btn btn-secondary btn-sm" @click="goBack">← {{ $t('common.back') }}</button>
                    <h1 class="mb-0 h4">{{ $t('admin.articles.spot_rock_image_editor.title') }}</h1>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Left: layouts list -->
                <div class="col-md-4">
                    <h5>{{ $t('admin.articles.sector_local_image_editor.layouts_title') }} <small class="text-muted">{{ $t('admin.articles.sector_local_image_editor.per_sector_hint') }}</small></h5>
                    <div class="layout-list mb-2">
                        <div
                            v-for="layout in layouts"
                            :key="layout.id"
                            class="layout-item d-flex align-items-center justify-content-between mb-1 p-2"
                            :class="{ 'layout-active': activeLayoutId === layout.id }"
                            style="border:1px solid #dee2e6; border-radius:4px; background:#f8f9fa; cursor:pointer;"
                            @click="selectLayout(layout.id)"
                        >
                            <span style="font-size:0.95rem; flex:1;">
                                {{ layout.sector ? layout.sector.name : $t('admin.articles.sector_local_image_editor.layout_number_prefix', { id: layout.id }) }}
                            </span>
                            <button class="btn btn-danger btn-sm p-0" style="width:22px;height:22px;line-height:1;" @click.stop="deleteLayout(layout.id)">✕</button>
                        </div>
                        <div v-if="layouts.length === 0" class="text-muted small p-2">{{ $t('admin.articles.sector_local_image_editor.no_layouts_yet') }}</div>
                    </div>
                </div>

                <!-- Right: sector selector (radio — one drawing per sector) -->
                <div class="col-md-8">
                    <h5>{{ $t('admin.articles.sector_local_image_editor.select_sector_title') }}</h5>
                    <p class="text-muted small mb-2">{{ $t('admin.articles.sector_local_image_editor.select_sector_hint') }}</p>
                    <div style="max-height:220px; overflow-y:auto; border:1px solid #dee2e6; border-radius:4px; padding:8px;">
                        <div v-for="sector in sectors" :key="sector.id" class="form-check mb-1">
                            <input
                                class="form-check-input"
                                type="radio"
                                :id="'sec-' + sector.id"
                                :value="sector.id"
                                v-model="selectedSectorId"
                            >
                            <label class="form-check-label" :for="'sec-' + sector.id">
                                {{ sector.name }} <small class="text-muted">(#{{ sector.id }})</small>
                                <span v-if="layoutBySector(sector.id)" class="badge bg-success ms-1" style="font-size:10px;">{{ $t('admin.articles.sector_local_image_editor.has_drawing_badge') }}</span>
                            </label>
                        </div>
                        <div v-if="sectors.length === 0" class="text-muted small">{{ $t('admin.articles.spot_rock_image_editor.no_sectors_for_article') }}</div>
                    </div>
                </div>
            </div>

            <!-- Save button + status -->
            <div class="row mb-2">
                <div class="col-12 d-flex align-items-center gap-2">
                    <button class="btn btn-success" :disabled="saving" @click="saveChanges">
                        <i class="fa fa-save"></i> {{ saving ? $t('admin.articles.spot_rock_image_editor.saving_ellipsis') : $t('admin.articles.spot_rock_image_editor.save_drawing_btn') }}
                    </button>
                    <span v-if="saveStatus" :class="saveStatus === 'ok' ? 'text-success' : 'text-danger'">
                        {{ saveStatus === 'ok' ? $t('admin.articles.spot_rock_image_editor.saved_badge') : $t('admin.articles.spot_rock_image_editor.error_badge') }}
                    </span>
                    <span v-if="imageInfo && imageInfo.has_original" class="badge bg-success ms-2" style="font-size:11px;">{{ $t('admin.articles.spot_rock_image_editor.original_saved_badge') }}</span>
                </div>
            </div>

            <!-- Canvas editor -->
            <div class="row">
                <div class="col-12">
                    <Editor
                        v-if="imageUrl"
                        ref="editorComponent"
                        :image_prop="imageUrl"
                        :json_prop="canvasData"
                        :json_meta="canvasJsonMeta"
                        :related_jsons="relatedJsons"
                        :related_jsons_meta="relatedJsonsMeta"
                        canvas_col_class="col-lg-8 col-md-8"
                        layers_col_class="col-lg-4 col-md-4"
                        @canvas_data="handleCanvasData"
                    />
                    <div v-else class="text-muted p-4 text-center border rounded">{{ $t('admin.articles.spot_rock_image_editor.loading_image_ellipsis') }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '../../items/canvas/EditorComponent.vue'
import { drawItemScaled } from '../../../../services/canvas/paperJsonRenderer.js'

export default {
    components: { Editor },
    data() {
        return {
            imageInfo:        null,
            imageUrl:         '',
            canvasData:       null,
            canvasJsonMeta:   null,
            layouts:          [],
            sectors:          [],
            selectedSectorId: null,
            activeLayoutId:   null,
            saving:           false,
            saveStatus:       null,
        }
    },
    watch: {
        // Radio buttons only update selectedSectorId (no handler on the input itself),
        // so keep canvasData/activeLayoutId in sync whenever the sector changes —
        // otherwise the previous sector's strokes stay loaded in the canvas and get
        // saved (duplicated) into the newly selected sector's layout.
        selectedSectorId(sectorId) {
            const layout = this.layoutBySector(sectorId);
            this.canvasData     = layout ? layout.json : null;
            this.canvasJsonMeta = this._layoutMeta(layout);
            this.activeLayoutId = layout ? layout.id   : null;
        },
    },
    computed: {
        // Reference-only overlay: every OTHER sector's own layout shows as a
        // dimmed reference while editing the active one.
        relatedJsons() {
            return this.layouts
                .filter(l => l.id !== this.activeLayoutId && l.json)
                .map(l => l.json);
        },
        relatedJsonsMeta() {
            return this.layouts
                .filter(l => l.id !== this.activeLayoutId && l.json)
                .map(l => this._layoutMeta(l));
        },
        bgImageUrl() {
            if (!this.imageInfo || !this.imageInfo.image) return null;
            return this.imageInfo.has_original
                ? '/public/images/spot_rocks_img/origin_img/' + this.imageInfo.image
                : '/public/images/spot_rocks_img/' + this.imageInfo.image;
        },
    },
    mounted() {
        document.querySelector('body').style.marginLeft = '0';
        const nav = document.querySelector('.admin_page_header_navbar');
        if (nav) nav.style.marginLeft = '0';
        // This page needs the full viewport width for the canvas — HomeComponent.vue
        // wraps every user-subdomain page in a Bootstrap `.container`, which caps
        // max-width per breakpoint. Drop just that class (page-local DOM tweak, not
        // an edit to HomeComponent.vue itself) and restore it on leaving so no other
        // page under this same layout is affected.
        const container = document.querySelector('.container.top_menu_margin');
        if (container) container.classList.remove('container');
        this.loadImageData();
    },
    beforeUnmount() {
        const container = document.querySelector('.top_menu_margin');
        if (container) container.classList.add('container');
    },
    methods: {
        loadImageData() {
            axios.get('/set_sector/set_spot_rock_images/get_for_editor/' + this.$route.params.id)
                .then(response => {
                    const d = response.data || {};
                    if (d.image) {
                        this.imageInfo = d.image;
                        this.imageUrl = d.image.has_original
                            ? '/public/images/spot_rocks_img/origin_img/' + d.image.image
                            : '/public/images/spot_rocks_img/' + d.image.image;
                    }
                    this.sectors = d.sectors || [];
                    this.layouts = d.layouts || [];
                })
                .catch(error => console.log(error));
        },

        reloadLayouts() {
            axios.get('/set_sector/set_spot_rock_images/get_for_editor/' + this.$route.params.id)
                .then(response => {
                    this.layouts = (response.data && response.data.layouts) ? response.data.layouts : [];
                })
                .catch(error => console.log(error));
        },

        layoutBySector(sectorId) {
            return this.layouts.find(l => l.sector && l.sector.id === sectorId);
        },

        // The background photo's own position/size within a layout's OWN save-time
        // view — needed to rescale that layout's strokes onto the current fit.
        _layoutMeta(layout) {
            if (!layout) return null;
            return {
                canvas_width: layout.canvas_width, canvas_height: layout.canvas_height,
                bg_left: layout.bg_left, bg_top: layout.bg_top,
                bg_width: layout.bg_width, bg_height: layout.bg_height,
            };
        },

        selectLayout(layoutId) {
            const layout = this.layouts.find(l => l.id === layoutId);
            if (!layout) return;
            this.canvasData        = layout.json;
            this.canvasJsonMeta    = this._layoutMeta(layout);
            this.selectedSectorId  = layout.sector_id;
            this.activeLayoutId    = layout.id;
        },

        handleCanvasData(data) {
            this.canvasData = data;
        },

        // The background photo's own actual position + size within the Paper.js
        // view — the editor fits it with a uniform cover-scale, centered, so it
        // doesn't necessarily start at (0,0) or fill the view exactly. Without
        // this, every viewer had to assume zero offset, which is exactly what
        // let saved strokes land in the wrong place once redrawn elsewhere.
        bgBoundsPayload(canvasContainer) {
            const bounds = canvasContainer && typeof canvasContainer.getBackgroundBounds === 'function'
                ? canvasContainer.getBackgroundBounds()
                : null;
            return {
                bg_left:   bounds ? bounds.left   : null,
                bg_top:    bounds ? bounds.top    : null,
                bg_width:  bounds ? bounds.width  : null,
                bg_height: bounds ? bounds.height : null,
            };
        },

        async saveChanges() {
            if (!this.canvasData)       { alert(this.$t('admin.articles.sector_local_image_editor.draw_something_alert')); return; }
            if (!this.selectedSectorId) { alert(this.$t('admin.articles.sector_local_image_editor.select_sector_first_alert')); return; }
            if (!this.$refs.editorComponent) { alert(this.$t('admin.articles.spot_rock_image_editor.editor_not_ready')); return; }

            this.saving    = true;
            this.saveStatus = null;

            try {
                const canvasContainer = this.$refs.editorComponent.$refs.canvasContainer;

                let json = this.canvasData;
                if (canvasContainer && typeof canvasContainer.getCleanJson === 'function') {
                    const cleanJson = canvasContainer.getCleanJson();
                    if (cleanJson) json = cleanJson;
                }

                let editedImageData = null;
                let canvasWidth = 0;
                let canvasHeight = 0;
                if (canvasContainer) {
                    const scope = canvasContainer.getCanvasScope();
                    if (scope && scope.view) {
                        canvasWidth  = Math.round(scope.view.viewSize.width);
                        canvasHeight = Math.round(scope.view.viewSize.height);
                    }
                    const bgBounds = canvasContainer.getBackgroundBounds ? canvasContainer.getBackgroundBounds() : null;
                    const ownMeta = {
                        json,
                        canvas_width: canvasWidth, canvas_height: canvasHeight,
                        bg_left: bgBounds && bgBounds.left, bg_top: bgBounds && bgBounds.top,
                        bg_width: bgBounds && bgBounds.width, bg_height: bgBounds && bgBounds.height,
                    };
                    // relatedJsons/relatedJsonsMeta are parallel arrays (aligned by
                    // index), not pre-merged objects — renderCompositeAtFullResolution
                    // needs each related item's own json inline alongside its bg_*.
                    const relatedFull = this.relatedJsons.map((j, i) => ({ json: j, ...(this.relatedJsonsMeta[i] || {}) }));
                    editedImageData = await this.renderCompositeAtFullResolution(this.bgImageUrl, ownMeta, relatedFull);
                }

                const response = await axios.post(
                    '/set_sector/set_spot_rock_images/save_drawing/' + this.$route.params.id,
                    {
                        json,
                        sector_id:     this.selectedSectorId,
                        edited_image:  editedImageData,
                        canvas_width:  canvasWidth,
                        canvas_height: canvasHeight,
                        ...this.bgBoundsPayload(canvasContainer),
                    }
                );

                this.saveStatus     = 'ok';
                this.activeLayoutId = response.data.layout_id;

                if (response.data.has_original && this.imageInfo) {
                    this.imageInfo.has_original = true;
                    this.imageUrl = '/public/images/spot_rocks_img/origin_img/' + this.imageInfo.image;
                }

                this.reloadLayouts();
                setTimeout(() => { this.saveStatus = null; }, 3000);
            } catch (e) {
                console.error(e);
                this.saveStatus = 'error';
            } finally {
                this.saving = false;
            }
        },

        deleteLayout(layoutId) {
            if (!confirm(this.$t('admin.articles.sector_local_image_editor.confirm_delete_layout'))) return;
            axios.delete('/set_sector/set_spot_rock_images/del_layout/' + layoutId)
                .then(() => {
                    if (this.activeLayoutId === layoutId) {
                        this.canvasData = null;
                        this.canvasJsonMeta = null;
                        this.selectedSectorId = null;
                        this.activeLayoutId = null;
                    }
                    this.reloadLayouts();
                })
                .catch(error => console.log(error));
        },

        // Bakes the saved composite image by drawing the background photo at its own
        // full native resolution, then drawing this layout's own strokes plus every
        // sibling layout's strokes directly from their Paper.js JSON via the shared
        // paperJsonRenderer — the SAME algorithm the public guidebook viewer uses to
        // redraw a route from its JSON. Replaces an earlier raster-capture-then-
        // stretch approach that blurred every edge and could shift colors slightly.
        renderCompositeAtFullResolution(bgPath, ownMeta, relatedMetas) {
            return new Promise((resolve) => {
                if (!bgPath) { resolve(null); return; }
                const bg = new Image();
                bg.onload = () => {
                    const w = bg.naturalWidth, h = bg.naturalHeight;
                    const canvas = document.createElement('canvas');
                    canvas.width = w; canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(bg, 0, 0, w, h);

                    (relatedMetas || []).forEach(meta => {
                        if (!meta || !meta.json) return;
                        try { drawItemScaled(ctx, meta, w, h, null, null, null, 1, 1); } catch (_) {}
                    });
                    if (ownMeta && ownMeta.json) {
                        try { drawItemScaled(ctx, ownMeta, w, h, null, null, null, 1, 1); } catch (_) {}
                    }

                    resolve(canvas.toDataURL('image/jpeg', 0.92));
                };
                bg.onerror = () => resolve(null);
                bg.src = bgPath;
            });
        },

        goBack() { this.$router.go(-1); },
    }
}
</script>

<style scoped>
.layout-active {
    border-color: #0d6efd !important;
    background: #e7f1ff !important;
}
</style>
