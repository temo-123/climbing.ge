<template>
  <StackModal
      v-model="is_show_modal"
      :title="sectorData ? ($t('guide.article.title.sector name') + ' — ' + sectorData.sector.name) : ''"
      :size="'90per'"
      :saveButton="{ visible: false }"
      :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
      @close="close_modal"
  >
    <div v-if="sectorData">

      <!-- ── Image tabs (only when sector has multiple images) ── -->
      <ul v-if="sectorData.sector_imgs.length > 1" class="nav nav-tabs img-tabs mb-1">
        <li
            v-for="(img, i) in sectorData.sector_imgs"
            :key="img.id"
            class="nav-item"
        >
          <button
              class="nav-link d-flex align-items-center gap-1"
              :class="{ active: selectedImageIdx === i }"
              @click="selectImage(i)"
          >
            <img
                :src="'/public/images/sector_img/' + img.image"
                class="tab-thumb"
                :alt="'Image ' + (i + 1)"
            />
            {{ i + 1 }}
          </button>
        </li>
      </ul>

      <!-- ── Sector image with canvas route-line overlay ── -->
      <div class="sector-img-block mb-2">
        <canvas-json-show
            v-if="currentImage"
            :fetch_url="'get_route/get_route_jsons_for_sector_image'"
            :fetch_id="currentImage.id"
            :image_src="currentImageSrc"
            :composite_src="compositeSrc"
            :selected_id="selected_route_id"
            :show_all="!!currentImage.has_original"
            :interactive="true"
            :extra_item="extraDrawing"
            refresh_event="route-drawing-updated"
            @item-click="e => selectRoute(e.id)"
        />
        <div v-else class="no-img-placeholder">
          <i class="fa fa-image fa-3x text-muted"></i>
        </div>
      </div>

      <!-- ── Selection hint ── -->
      <div class="select-hint mb-2">
        <template v-if="selected_route_id && selectedRoute">
          <span class="green-tag">&#9679; #{{ selectedRoute.num }} {{ selectedRoute.name }} highlighted in green</span>
          <span class="text-muted small ms-2">— click the row again to deselect</span>
        </template>
        <template v-else>
          <span class="text-muted small">Click a row to highlight the route on the image</span>
        </template>
      </div>

      <!-- ── Sport routes table ── -->
      <div v-if="sectorData.sport_routes && sectorData.sport_routes.length > 0" class="table-responsive mb-2">
        <table class="table table-hover sector-table">
          <thead>
            <tr>
              <th>N</th>
              <th>{{ $t('guide.route.name') }}</th>
              <th>{{ $t('guide.route.height') }}</th>
              <th>{{ $t('guide.route.bolts') }}</th>
              <th>{{ $t('guide.route.grade fr') }}</th>
              <th>{{ $t('guide.route.grade yds') }}</th>
              <th class="col-info">Info</th>
            </tr>
          </thead>
          <tbody>
            <tr
                v-for="route in sectorData.sport_routes"
                :key="'sp' + route.id"
                :ref="'route-row-' + route.id"
                class="route-row"
                :class="{ 'route-active': selected_route_id == route.id }"
                @click="selectRoute(route.id)"
            >
              <td>{{ route.num }}</td>
              <td>{{ route.name }}</td>
              <td>{{ route.height }}</td>
              <td>
                <template v-if="route.category === 'tred'">Trad</template>
                <template v-else-if="route.category === 'top'">Top Rope</template>
                <template v-else>{{ route.bolts || '?' }}</template>
              </td>
              <td>{{ route.grade }}<template v-if="route.or_grade"> / {{ route.or_grade }}</template></td>
              <td>
                <template v-if="route.or_grade">{{ lead_grade_chart(route.grade) }} / {{ lead_grade_chart(route.or_grade) }}</template>
                <template v-else>{{ lead_grade_chart(route.grade) }}</template>
              </td>
              <td class="col-info" @click.stop="openRouteModal(route.id)">
                <i class="fa fa-arrow-right route-arrow"></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ── Boulder routes table ── -->
      <div v-if="sectorData.boulder_route && sectorData.boulder_route.length > 0" class="table-responsive mb-2">
        <div class="section-label">Boulder</div>
        <table class="table table-hover sector-table">
          <thead>
            <tr>
              <th>N</th>
              <th>{{ $t('guide.route.name') }}</th>
              <th>{{ $t('guide.route.height') }}</th>
              <th>{{ $t('guide.route.grade fr') }}</th>
              <th>{{ $t('guide.route.grade yds') }}</th>
              <th class="col-info">Info</th>
            </tr>
          </thead>
          <tbody>
            <tr
                v-for="route in sectorData.boulder_route"
                :key="'bl' + route.id"
                :ref="'route-row-' + route.id"
                class="route-row"
                :class="{ 'route-active': selected_route_id == route.id }"
                @click="selectRoute(route.id)"
            >
              <td>{{ route.num }}</td>
              <td>{{ route.name }}</td>
              <td>{{ route.height }}</td>
              <td>{{ route.grade }}<template v-if="route.or_grade"> / {{ route.or_grade }}</template></td>
              <td>
                <template v-if="route.or_grade">{{ boulder_grade_chart(route.grade) }} / {{ boulder_grade_chart(route.or_grade) }}</template>
                <template v-else>{{ boulder_grade_chart(route.grade) }}</template>
              </td>
              <td class="col-info" @click.stop="openRouteModal(route.id)">
                <i class="fa fa-arrow-right route-arrow"></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ── Multi-pitch section ── -->
      <div v-if="sectorData.mtps && sectorData.mtps.length > 0" class="mb-2">
        <div class="section-label">Multi-pitch</div>
        <table class="table table-hover sector-table">
          <tbody>
            <tr
                v-for="(mtp, i) in sectorData.mtps"
                :key="'mtp' + (mtp.mtp_id || i)"
            >
              <td style="width:40px;">{{ mtp.mtp_num || i + 1 }}</td>
              <td
                  class="text-primary"
                  style="cursor:pointer;"
                  @click="openMtpModal(mtp.mtp_id)"
              >{{ mtp.mtp_name }}</td>
              <td class="col-info" @click="openMtpModal(mtp.mtp_id)">
                <i class="fa fa-arrow-right route-arrow"></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </StackModal>
</template>

<script>
import grade_chart from '../../../../../../mixins/grade_chart_mixin.js';

export default {
    mixins: [grade_chart],
    props: ['sector'],
    data() {
        return {
            is_show_modal:     false,
            sectorData:        null,
            selected_route_id: null,
            selectedImageIdx:  0,
            // The general-purpose "extra info" annotation for the currently
            // shown sector image (approach notes, hazards, landmarks) — not
            // tied to any route, fetched separately from the route drawings.
            extraDrawing:      null,
        };
    },
    watch: {
        currentImage(img) {
            this.fetchExtraDrawing(img);
        },
    },
    computed: {
        currentImage() {
            if (!this.sectorData || !this.sectorData.sector_imgs.length) return null;
            return this.sectorData.sector_imgs[this.selectedImageIdx]
                || this.sectorData.sector_imgs[0];
        },
        // Use the original clean photo when available so the canvas can draw all route
        // lines itself (dim + green selected). Fall back to the composite sector image.
        currentImageSrc() {
            if (!this.currentImage) return null;
            return this.currentImage.has_original
                ? '/public/images/sector_img/origin_img/' + this.currentImage.image
                : '/public/images/sector_img/'            + this.currentImage.image;
        },
        // When has_original, the composite (routes baked in) has the same pixel dimensions
        // as the Paper.js coordinate space used to draw the routes. Pass it to route_lines
        // so it can scale coordinates from composite space → origin image space.
        compositeSrc() {
            if (!this.currentImage || !this.currentImage.has_original) return null;
            return '/public/images/sector_img/' + this.currentImage.image;
        },
        selectedRoute() {
            if (!this.selected_route_id || !this.sectorData) return null;
            const all = [
                ...(this.sectorData.sport_routes  || []),
                ...(this.sectorData.boulder_route || []),
            ];
            return all.find(r => r.id == this.selected_route_id) || null;
        },
    },
    methods: {
        lead_grade_chart(grade_fr)    { return this.lead(grade_fr); },
        boulder_grade_chart(grade_fr) { return this.boulder(grade_fr); },

        show_sector_modal(sector) {
            this.sectorData        = sector;
            this.selected_route_id = null;
            this.selectedImageIdx  = 0;
            this.is_show_modal     = true;
        },
        close_modal() {
            this.is_show_modal     = false;
            this.sectorData        = null;
            this.selected_route_id = null;
            this.selectedImageIdx  = 0;
            this.extraDrawing      = null;
        },
        selectImage(idx) {
            this.selectedImageIdx  = idx;
            this.selected_route_id = null;
        },
        // Fetches the current sector image's general "extra info" annotation
        // (approach notes, hazards, landmarks) — separate from route drawings,
        // never selectable/hoverable, drawn as its own layer on the canvas.
        async fetchExtraDrawing(img) {
            this.extraDrawing = null;
            if (!img) return;
            try {
                const res = await axios.get('/get_sector/get_sector_image_extra_drawing/get/' + img.id);
                const drawing = res.data && res.data.extra_drawing;
                if (!drawing || !drawing.json) return;
                let parsed = typeof drawing.json === 'string' ? JSON.parse(drawing.json) : drawing.json;
                if (typeof parsed === 'string') parsed = JSON.parse(parsed);
                this.extraDrawing = {
                    json: parsed,
                    canvas_width: drawing.canvas_width || null,
                    canvas_height: drawing.canvas_height || null,
                    bg_left: drawing.bg_left ?? null,
                    bg_top: drawing.bg_top ?? null,
                    bg_width: drawing.bg_width || null,
                    bg_height: drawing.bg_height || null,
                };
            } catch (_) {}
        },
        selectRoute(routeId) {
            this.selected_route_id = this.selected_route_id == routeId ? null : routeId;
            if (this.selected_route_id) {
                this.$nextTick(() => {
                    const rows = this.$refs['route-row-' + this.selected_route_id];
                    const el   = Array.isArray(rows) ? rows[0] : rows;
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            }
        },
        openRouteModal(route_id) {
            this.$emit('show_route_modal', route_id);
            this.close_modal();
        },
        openMtpModal(mtp_id) {
            this.$emit('show_mtp_modal', mtp_id);
            this.close_modal();
        },
    },
}
</script>

<style scoped>
/* ── Image tabs ── */
.img-tabs .nav-link {
    padding: 4px 10px;
    font-size: 13px;
}
.tab-thumb {
    width: 44px;
    height: 30px;
    object-fit: cover;
    border-radius: 3px;
}

/* ── Image block ── */
.sector-img-block {
    width: 100%;
    background: transparent;
    border-radius: 6px;
    overflow: hidden;
    /* The canvas preview now shrink-wraps to the image's own rendered size
       (so tall/portrait photos don't stretch to full width and blow up in
       height) — center it instead of letting it hug the left edge, and let
       the leftover space show the modal's own background instead of a dark bar. */
    text-align: center;
}

/* ── Hint ── */
.select-hint {
    min-height: 22px;
    font-size: 13px;
}
.green-tag {
    background: #00e64d;
    color: #000;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

/* ── Route tables (match sector section style) ── */
.sector-table {
    margin-bottom: 0;
}
.sector-table th,
.sector-table td {
    vertical-align: middle;
    text-align: center;
    font-size: 14px;
    padding: 6px 8px;
}
.sector-table th:nth-child(2),
.sector-table td:nth-child(2) {
    text-align: left;
}
.route-row {
    cursor: pointer;
}
.route-active {
    outline: 2px solid #00e64d;
    outline-offset: -2px;
}
.route-active td {
    background-color: rgba(0, 230, 77, 0.18) !important;
    font-weight: 600;
}
.col-info {
    width: 42px;
    cursor: pointer;
}
.route-arrow {
    color: #888;
    font-size: 12px;
}
.route-row:hover .route-arrow {
    color: #2563eb;
}

/* ── Section label ── */
.section-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #888;
    margin-bottom: 4px;
}

/* ── No image placeholder ── */
.no-img-placeholder {
    text-align: center;
    padding: 60px;
    color: #666;
}
</style>
