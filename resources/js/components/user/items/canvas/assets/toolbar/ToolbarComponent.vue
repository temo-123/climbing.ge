<template>
    <div class="canvas-toolbar">

        <!-- Tool buttons row -->
        <div class="d-flex flex-wrap align-items-end gap-2 px-2 pt-2 pb-1 bg-light border rounded-top">

            <!-- History -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-danger" @click.prevent="$emit('reset')" :title="$t('admin.articles.canvas_editor.clear_all_drawings_tooltip')">
                        <i class="fa fa-times"></i>
                    </button>
                    <button type="button" class="btn btn-warning" :disabled="historyLength === 0" @click.prevent="$emit('undo')" :title="$t('admin.articles.canvas_editor.undo_tooltip', { count: historyLength })">
                        <i class="fa fa-undo"></i>
                        <span v-if="historyLength > 0" class="badge bg-dark ms-1">{{ historyLength }}</span>
                    </button>
                    <button type="button" class="btn btn-warning" :disabled="redoLength === 0" @click.prevent="$emit('redo')" :title="$t('admin.articles.canvas_editor.redo_tooltip', { count: redoLength })">
                        <i class="fa fa-repeat"></i>
                        <span v-if="redoLength > 0" class="badge bg-dark ms-1">{{ redoLength }}</span>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.history_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1 mx-2"></div>

            <!-- Draw tools -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 1 }]" @click.prevent="$emit('line')" :title="$t('admin.articles.canvas_editor.freehand_line_tooltip')">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 20 }]" @click.prevent="$emit('continue-line')" :disabled="!hasDrawing" :title="$t('admin.articles.canvas_editor.continue_from_endpoint_tooltip')">
                        <i class="fa fa-pencil"></i><i class="fa fa-long-arrow-right" style="font-size:9px; margin-left:1px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 2 }]" @click.prevent="$emit('point')" :title="$t('admin.articles.canvas_editor.point_dot_tooltip')">
                        <i class="fa fa-circle" style="font-size:8px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 3 }]" @click.prevent="$emit('number')" :title="$t('admin.articles.canvas_editor.numbered_route_tooltip')">
                        <i class="fa fa-pencil"></i>&thinsp;<i class="fa fa-dot-circle-o" style="font-size:9px;"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 7 }]" @click.prevent="$emit('combined')" :title="$t('admin.articles.canvas_editor.combined_tooltip')">
                        <span style="display:inline-flex; align-items:center; gap:2px; vertical-align:middle;">
                            <b style="font-size:10px; font-weight:bold; line-height:1;">1</b>
                            <span style="width:8px; height:2px; background:currentColor; display:inline-block;"></span>
                            <i class="fa fa-circle" style="font-size:5px;"></i>
                        </span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 4 }]" @click.prevent="$emit('rectangle')" :title="$t('admin.articles.canvas_editor.rectangle_tooltip')">
                        <i class="fa fa-square-o"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 10 }]" @click.prevent="$emit('circle')" :title="$t('admin.articles.canvas_editor.circle_tooltip')">
                        <i class="fa fa-circle-o"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 11 }]" @click.prevent="$emit('ellipse')" :title="$t('admin.articles.canvas_editor.ellipse_tooltip')">
                        <i class="fa fa-circle-o"></i><span style="font-size:9px; margin-left:1px; line-height:1; font-style:italic;">e</span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 21 }]" @click.prevent="$emit('arrow')" :title="$t('admin.articles.canvas_editor.arrow_tooltip')">
                        <i class="fa fa-long-arrow-right"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 13 }]" @click.prevent="$emit('text')" :title="$t('admin.articles.canvas_editor.text_label_tooltip')">
                        <i class="fa fa-font"></i>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.draw_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1 mx-2"></div>

            <!-- Topo symbols -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 23 }]" @click.prevent="$emit('bolt')" :title="$t('admin.articles.canvas_editor.bolt_tooltip')">
                        <i class="fa fa-times"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 24 }]" @click.prevent="$emit('pin')" :title="$t('admin.articles.canvas_editor.pin_tooltip')">
                        <i class="fa fa-thumb-tack"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 25 }]" @click.prevent="$emit('pendulum-left')" :title="$t('admin.articles.canvas_editor.pendulum_left_tooltip')">
                        <i class="fa fa-reply"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 26 }]" @click.prevent="$emit('pendulum-right')" :title="$t('admin.articles.canvas_editor.pendulum_right_tooltip')">
                        <i class="fa fa-share"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 27 }]" @click.prevent="$emit('crux')" :title="$t('admin.articles.canvas_editor.crux_tooltip')">
                        <i class="fa fa-key"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 48 }]" @click.prevent="$emit('trail')" :title="$t('admin.articles.canvas_editor.trail_tooltip')">
                        <span class="trail-icon-glyph">┄┄</span>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.topo_symbols_group_label') }}</div>
            </div>

            <template v-if="hasLegendSymbols">
                <div class="vr align-self-stretch my-1 mx-2"></div>

                <!-- Legend position/size — its own group (not nested under Topo
                     Symbols) so it doesn't make that group's column taller than
                     every other tool-group and throw off the row's alignment.
                     The whole group only renders once there's actually a symbol
                     SOMEWHERE on this photo for a legend to represent (see
                     `hasLegendSymbols` prop doc) — fixed September 2026, round
                     13: previously shown unconditionally, so an admin drawing a
                     brand-new sector/route/pitch with nothing on it yet saw two
                     controls that visibly did nothing. -->
                <div class="tool-group">
                    <div class="d-flex flex-column gap-1">
                        <div class="d-flex align-items-start gap-1">
                            <span class="small text-muted legend-select-label pt-1">{{ $t('admin.articles.canvas_editor.legend_position_label') }}</span>
                            <!-- Visual 3x3 compass-arrow picker — replaced the plain text
                                 dropdown (fixed September 2026, round 12/14, "make
                                 selections match more friendly"): the 8 real corner/edge
                                 positions sit at their own matching spot in the grid, each
                                 pointing the same direction its slot sits in (top-left cell
                                 = ↖, etc.), so picking one is a single recognizable click
                                 instead of reading through 9 text option strings. The
                                 center cell (no real direction of its own) doubles as
                                 "hidden" (✕) — semantically the odd one out anyway, since
                                 it means "don't show it" rather than pinning to an edge. -->
                            <div class="legend-position-grid" :title="$t('admin.articles.canvas_editor.legend_position_tooltip')">
                                <button v-for="cell in legendPositionGrid" :key="cell.value"
                                        type="button"
                                        class="legend-position-cell"
                                        :class="{ active: legendPosition === cell.value, 'is-hidden-cell': cell.value === 'hidden' }"
                                        :title="$t(`admin.articles.canvas_editor.${cell.labelKey}`)"
                                        :aria-label="$t(`admin.articles.canvas_editor.${cell.labelKey}`)"
                                        @click="$emit('legend-position-change', cell.value)">
                                    {{ cell.arrow }}
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="small text-muted legend-select-label">{{ $t('admin.articles.canvas_editor.legend_scale_label') }}</span>
                            <!-- "− 100% +" stepper — replaced the plain `<select>` (round
                                 13, reported as "blinking" / refusing to keep the picked
                                 size) and then the 7-button row it became (round 14, "make
                                 selections match more friendly": 7 buttons read as busier/
                                 wider than this whole toolbar column needs for a value
                                 that's really just "smaller/bigger"). See
                                 legendScaleOptions'/legendScaleIndex's own comments. -->
                            <div class="legend-scale-stepper" :title="$t('admin.articles.canvas_editor.legend_scale_tooltip')">
                                <button type="button" class="legend-scale-step-btn"
                                        :disabled="legendScaleIndex <= 0"
                                        :aria-label="$t('admin.articles.canvas_editor.legend_scale_decrease')"
                                        @click="stepLegendScale(-1)">−</button>
                                <span class="legend-scale-value">{{ Math.round(legendScale * 100) }}%</span>
                                <button type="button" class="legend-scale-step-btn"
                                        :disabled="legendScaleIndex >= legendScaleOptions.length - 1"
                                        :aria-label="$t('admin.articles.canvas_editor.legend_scale_increase')"
                                        @click="stepLegendScale(1)">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.legend_group_label') }}</div>
                </div>

                <div class="vr align-self-stretch my-1 mx-2"></div>
            </template>

            <!-- Anchors — four small sub-clusters (anchor / portaledge / rappel
                 / rescue) instead of one undifferentiated 10-button row, so
                 the near-identical circle icons read as distinct families at
                 a glance instead of a single blob. -->
            <div class="tool-group">
                <div class="d-flex align-items-center gap-1">
                    <div class="btn-group btn-group-sm" :title="$t('admin.articles.canvas_editor.anchor_family_hint')">
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 22 }]" @click.prevent="$emit('rappel')" :title="$t('admin.articles.canvas_editor.rappel_tooltip')">
                            <i class="fa fa-anchor"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 28 }]" @click.prevent="$emit('anchor-good')" :title="$t('admin.articles.canvas_editor.anchor_good_tooltip')">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 29 }]" @click.prevent="$emit('anchor-mid')" :title="$t('admin.articles.canvas_editor.anchor_mid_tooltip')">
                            <i class="fa fa-minus-circle anchor-icon-rotated"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 30 }]" @click.prevent="$emit('anchor-bad')" :title="$t('admin.articles.canvas_editor.anchor_bad_tooltip')">
                            <i class="fa fa-ban"></i>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm" :title="$t('admin.articles.canvas_editor.portaledge_family_hint')">
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 31 }]" @click.prevent="$emit('portaledge-anchor-good')" :title="$t('admin.articles.canvas_editor.portaledge_anchor_good_tooltip')">
                            <i class="fa fa-long-arrow-up anchor-icon-accent"></i><i class="fa fa-minus-circle"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 32 }]" @click.prevent="$emit('portaledge-anchor-mid')" :title="$t('admin.articles.canvas_editor.portaledge_anchor_mid_tooltip')">
                            <i class="fa fa-long-arrow-up anchor-icon-accent"></i><i class="fa fa-minus-circle anchor-icon-rotated"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 33 }]" @click.prevent="$emit('portaledge-anchor-bad')" :title="$t('admin.articles.canvas_editor.portaledge_anchor_bad_tooltip')">
                            <i class="fa fa-long-arrow-up anchor-icon-accent"></i><i class="fa fa-ban"></i>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm" :title="$t('admin.articles.canvas_editor.rappel_anchor_family_hint')">
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 34 }]" @click.prevent="$emit('rappel-anchor-good')" :title="$t('admin.articles.canvas_editor.rappel_anchor_good_tooltip')">
                            <i class="fa fa-minus-circle"></i><i class="fa fa-long-arrow-down anchor-icon-accent"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 35 }]" @click.prevent="$emit('rappel-anchor-mid')" :title="$t('admin.articles.canvas_editor.rappel_anchor_mid_tooltip')">
                            <i class="fa fa-minus-circle anchor-icon-rotated"></i><i class="fa fa-long-arrow-down anchor-icon-accent"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 36 }]" @click.prevent="$emit('rappel-anchor-bad')" :title="$t('admin.articles.canvas_editor.rappel_anchor_bad_tooltip')">
                            <i class="fa fa-ban"></i><i class="fa fa-long-arrow-down anchor-icon-accent"></i>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 37 }]" @click.prevent="$emit('rescue-anchor')" :title="$t('admin.articles.canvas_editor.rescue_anchor_tooltip')">
                            <i class="fa fa-dot-circle-o"></i>
                        </button>
                    </div>
                    <!-- Tent (see DrawingTools.vue's add_tent) moved here from the
                         old "Landmarks" group, which otherwise only ever had 3
                         buttons and didn't earn its own toolbar section. -->
                    <div class="btn-group btn-group-sm">
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 39 }]" @click.prevent="$emit('tent')" :title="$t('admin.articles.canvas_editor.tent_tooltip')">
                            <span class="tent-icon-glyph">⛺</span>
                        </button>
                    </div>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.anchors_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1 mx-2"></div>

            <!-- Points of interest — also carries Summit and Parking (see
                 DrawingTools.vue's add_summit/add_parking), moved here from the
                 old "Landmarks" group along with Tent above, since none of the
                 three landmark markers needed a dedicated toolbar section of
                 their own. -->
            <div class="tool-group">
                <div class="d-flex align-items-center gap-1">
                    <div class="btn-group btn-group-sm" :title="$t('admin.articles.canvas_editor.landmarks_family_hint')">
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 38 }]" @click.prevent="$emit('summit')" :title="$t('admin.articles.canvas_editor.summit_tooltip')">
                            <i class="fa fa-map-marker"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 40 }]" @click.prevent="$emit('parking')" :title="$t('admin.articles.canvas_editor.parking_tooltip')">
                            <b class="parking-icon-glyph">P</b>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 41 }]" @click.prevent="$emit('poi-hiking')" :title="$t('admin.articles.canvas_editor.poi_hiking_tooltip')">
                            <i class="fa fa-male"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 42 }]" @click.prevent="$emit('poi-bed')" :title="$t('admin.articles.canvas_editor.poi_bed_tooltip')">
                            <i class="fa fa-bed"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 43 }]" @click.prevent="$emit('poi-bike')" :title="$t('admin.articles.canvas_editor.poi_bike_tooltip')">
                            <i class="fa fa-bicycle"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 44 }]" @click.prevent="$emit('poi-water')" :title="$t('admin.articles.canvas_editor.poi_water_tooltip')">
                            <i class="fa fa-tint"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 45 }]" @click.prevent="$emit('poi-food')" :title="$t('admin.articles.canvas_editor.poi_food_tooltip')">
                            <i class="fa fa-cutlery"></i>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 46 }]" @click.prevent="$emit('poi-tent')" :title="$t('admin.articles.canvas_editor.poi_tent_tooltip')">
                            <span class="tent-icon-glyph">⛺</span>
                        </button>
                        <button type="button" :class="['btn', 'btn-primary', { active: action === 47 }]" @click.prevent="$emit('poi-medical')" :title="$t('admin.articles.canvas_editor.poi_medical_tooltip')">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.poi_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1 mx-2"></div>

            <!-- Climbing-zone signs — yellow diamond warning-sign markers
                 (see DrawingTools.vue's add_zoneSign), a separate family
                 from the Points of Interest pins above: they mark an
                 area's climbing discipline, not an amenity. -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 50 }]" @click.prevent="$emit('zone-dry-tooling')" :title="$t('admin.articles.canvas_editor.zone_dry_tooling_tooltip')">
                        <span class="dry-tooling-icon-glyph">⛏</span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 51 }]" @click.prevent="$emit('zone-sport-climbing')" :title="$t('admin.articles.canvas_editor.zone_sport_climbing_tooltip')">
                        <span class="sport-climbing-icon-glyph">🧗</span>
                    </button>
                    <button type="button" :class="['btn', 'btn-primary', { active: action === 52 }]" @click.prevent="$emit('zone-bouldering')" :title="$t('admin.articles.canvas_editor.zone_bouldering_tooltip')">
                        <span class="bouldering-icon-glyph">🪨</span>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.zone_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1 mx-2"></div>

            <!-- Edit tools -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 5 }]" :disabled="!hasDrawing" @click.prevent="$emit('eraser')" :title="$t('admin.articles.canvas_editor.erase_item_tooltip')">
                        <i class="fa fa-eraser"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 8 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('move')" :title="$t('admin.articles.canvas_editor.move_single_item_tooltip')">
                        <i class="fa fa-arrows"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 17 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('move-all')" :title="$t('admin.articles.canvas_editor.move_all_objects_tooltip')">
                        <i class="fa fa-arrows-alt"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 14 }]" :disabled="!hasUnlockedDrawing" @click.prevent="$emit('selection')" :title="$t('admin.articles.canvas_editor.select_area_tooltip')">
                        <i class="fa fa-mouse-pointer"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 19 }]" :disabled="!hasResizableShapes" @click.prevent="$emit('resize')" :title="$t('admin.articles.canvas_editor.resize_shape_tooltip')">
                        <i class="fa fa-expand"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 15 }]" @click.prevent="$emit('crop')" :title="$t('admin.articles.canvas_editor.crop_canvas_tooltip')">
                        <i class="fa fa-crop"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 16 }]" :disabled="!hasDrawing" @click.prevent="$emit('edit-points')" :title="$t('admin.articles.canvas_editor.edit_path_points_tooltip')">
                        <i class="fa fa-share-alt"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-warning', { active: action === 49 }]" :disabled="!hasEditableLines" @click.prevent="$emit('edit-line-points')" :title="$t('admin.articles.canvas_editor.edit_line_points_tooltip')">
                        <span class="line-points-icon-glyph">●–●</span>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.edit_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1 mx-2"></div>

            <!-- View / Navigate -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-in')" :title="$t('admin.articles.canvas_editor.zoom_in_tooltip')">
                        <i class="fa fa-search-plus"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-reset')" :title="$t('admin.articles.canvas_editor.reset_zoom_fit_tooltip')">
                        <i class="fa fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('zoom-out')" :title="$t('admin.articles.canvas_editor.zoom_out_tooltip')">
                        <i class="fa fa-search-minus"></i>
                    </button>
                    <button type="button" :class="['btn', 'btn-secondary', { active: action === 9 }]" @click.prevent="$emit('pan')" :title="$t('admin.articles.canvas_editor.pan_scroll_tooltip')">
                        <i class="fa fa-hand-paper-o"></i>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.navigate_group_label') }}</div>
            </div>

            <div class="vr align-self-stretch my-1 mx-2"></div>

            <!-- Export -->
            <div class="tool-group">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-png')" :title="$t('admin.articles.canvas_editor.export_png_tooltip')">
                        <i class="fa fa-download"></i> PNG
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('export-svg')" :title="$t('admin.articles.canvas_editor.export_svg_tooltip')">
                        <i class="fa fa-download"></i> SVG
                    </button>
                    <button type="button" class="btn btn-secondary" @click.prevent="$emit('save-image')" :title="$t('admin.articles.canvas_editor.download_canvas_tooltip')">
                        <i class="fa fa-save"></i>
                    </button>
                </div>
                <div class="tool-group-label">{{ $t('admin.articles.canvas_editor.export_group_label') }}</div>
            </div>

        </div>

        <!-- Active tool indicator — its own full-width row (not squeezed into
             the tool-buttons row above via ms-auto), so it lands in the same
             predictable spot every time no matter how many tool-groups wrap
             above it, instead of getting stranded mid-layout next to
             whichever group happened to be last on its line. -->
        <div class="d-flex align-items-center justify-content-start gap-2 px-2 py-1 bg-light border border-top-0 active-tool-row">
            <span class="small text-muted">{{ $t('admin.articles.canvas_editor.active_tool_label') }}</span>
            <span class="badge rounded-pill" :class="activeBadgeClass" style="font-size:11px; padding:4px 10px;">
                <i :class="activeActionIcon" class="me-1"></i>{{ actionLabel }}
            </span>
        </div>

    </div>
</template>

<script>
export default {
    name: "ToolbarComponent",
    props: {
        action: {
            type: Number,
            default: 1
        },
        historyLength: {
            type: Number,
            default: 0
        },
        redoLength: {
            type: Number,
            default: 0
        },
        hasDrawing: {
            type: Boolean,
            default: false
        },
        hasUnlockedDrawing: {
            type: Boolean,
            default: true
        },
        // Gates the Resize button specifically — true only when at least one
        // unlocked rectangle/circle/ellipse/arrow exists (see
        // EditorComponent's hasResizableShapes and CanvasHandlers.vue's
        // _isResizableShape, the tool's own real type check at click time).
        // Defaults true so a host that never wires this up keeps the button
        // enabled, same fallback convention as hasLegendSymbols below.
        hasResizableShapes: {
            type: Boolean,
            default: true
        },
        // Gates the Edit Line Points button — true only when at least one
        // unlocked freehand line/trail exists (see EditorComponent's
        // hasEditableLines and CanvasHandlers.vue's _isEditableLineShape).
        hasEditableLines: {
            type: Boolean,
            default: true
        },
        legendPosition: {
            type: String,
            default: 'top-right'
        },
        legendScale: {
            type: Number,
            default: 1
        },
        // Whether ANY sector/route/pitch sharing this photo has a topo-
        // symbol/POI marker to actually show a legend for (see
        // canvasOverlaysMixin.js's computeEditorLegend, which computes this
        // the same way the combined legend itself decides what to show —
        // aggregated across every sibling, not just whichever item is
        // currently selected). Hides the whole Legend Position/Size group
        // when false instead of leaving two controls visible that affect
        // nothing yet (fixed September 2026, round 13 — "if i dont have a
        // sign item dont show position and size selection"). Defaults to
        // true so a host page that hasn't wired this prop up at all (i.e.
        // isn't one of the 5 combined-legend editors) keeps its old
        // always-visible behavior.
        hasLegendSymbols: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        // Same fixed 7 sizes the old `<select>` offered — now stepped
        // through via a "− 100% +" stepper (fixed September 2026, round 13
        // replaced the `<select>` with 7 separate pill buttons because a
        // native `<select>`'s open dropdown was reported as "blinking" and
        // refusing to keep the chosen size — the same live-preview
        // recomputing every animation frame, see canvasOverlaysMixin.js's
        // computeEditorLegend, was interrupting it; round 14 then swapped
        // those 7 buttons for this narrower stepper, "make selections match
        // more friendly").
        legendScaleOptions() {
            return [0.5, 0.75, 1, 1.25, 1.5, 2, 2.5];
        },
        // Current position of `legendScale` within legendScaleOptions, for
        // the stepper's own +/- bounds and display. A value that isn't one
        // of the 7 fixed stops (legacy data saved before this stepper
        // existed, e.g. a raw 1.1) resolves to its NEAREST stop rather than
        // a fixed 100% fallback — snapping to 100% regardless of the real
        // value meant the displayed "___%" text was still correct (that
        // reads `legendScale` directly) but a single +/- click stepped from
        // the WRONG position, jumping far past the adjacent stop instead of
        // moving one step from where the value actually was (fixed
        // September 2026).
        legendScaleIndex() {
            const idx = this.legendScaleOptions.indexOf(this.legendScale);
            if (idx !== -1) return idx;
            let nearest = 0, nearestDist = Infinity;
            this.legendScaleOptions.forEach((opt, i) => {
                const dist = Math.abs(opt - this.legendScale);
                if (dist < nearestDist) { nearestDist = dist; nearest = i; }
            });
            return nearest;
        },
        // Row-major 3x3 layout for the visual legend-position picker (see
        // the template) — each cell's grid slot IS its own real on-photo
        // corner/edge, so the array order below directly controls layout;
        // 'hidden' sits in the unused center slot. Matches LEGEND_POSITIONS
        // (topoSymbolTypes.js) minus the ordering, which there is a flat
        // list (used by the old `<select>`) rather than a grid. Each cell's
        // `arrow` glyph points the same direction its slot sits in the
        // grid (compass-style, fixed September 2026, round 14) — 'hidden'
        // (the center, no direction of its own) gets a plain ✕ instead.
        legendPositionGrid() {
            return [
                { value: 'top-left',  labelKey: 'legend_pos_top_left',  arrow: '↖' },
                { value: 'top-mid',   labelKey: 'legend_pos_top_mid',   arrow: '↑' },
                { value: 'top-right', labelKey: 'legend_pos_top_right', arrow: '↗' },
                { value: 'left-mid',  labelKey: 'legend_pos_left_mid',  arrow: '←' },
                { value: 'hidden',    labelKey: 'legend_pos_hidden',    arrow: '✕' },
                { value: 'right-mid', labelKey: 'legend_pos_right_mid', arrow: '→' },
                { value: 'bot-left',  labelKey: 'legend_pos_bot_left',  arrow: '↙' },
                { value: 'bot-mid',   labelKey: 'legend_pos_bot_mid',   arrow: '↓' },
                { value: 'bot-right', labelKey: 'legend_pos_bot_right', arrow: '↘' },
            ];
        },
        actionLabel() {
            const labels = {
                1:  this.$t('admin.articles.canvas_editor.action_label_line'),
                2:  this.$t('admin.articles.canvas_editor.action_label_point'),
                3:  this.$t('admin.articles.canvas_editor.action_label_number'),
                4:  this.$t('admin.articles.canvas_editor.action_label_rectangle'),
                5:  this.$t('admin.articles.canvas_editor.action_label_eraser'),
                7:  this.$t('admin.articles.canvas_editor.action_label_combined'),
                8:  this.$t('admin.articles.canvas_editor.action_label_move_item'),
                9:  this.$t('admin.articles.canvas_editor.action_label_pan'),
                10: this.$t('admin.articles.canvas_editor.action_label_circle'),
                11: this.$t('admin.articles.canvas_editor.action_label_ellipse'),
                13: this.$t('admin.articles.canvas_editor.action_label_text'),
                14: this.$t('admin.articles.canvas_editor.action_label_select'),
                15: this.$t('admin.articles.canvas_editor.action_label_crop'),
                16: this.$t('admin.articles.canvas_editor.action_label_edit_points'),
                17: this.$t('admin.articles.canvas_editor.action_label_move_all'),
                19: this.$t('admin.articles.canvas_editor.action_label_resize'),
                20: this.$t('admin.articles.canvas_editor.action_label_continue_line'),
                21: this.$t('admin.articles.canvas_editor.action_label_arrow'),
                22: this.$t('admin.articles.canvas_editor.action_label_rappel'),
                23: this.$t('admin.articles.canvas_editor.action_label_bolt'),
                24: this.$t('admin.articles.canvas_editor.action_label_pin'),
                25: this.$t('admin.articles.canvas_editor.action_label_pendulum_left'),
                26: this.$t('admin.articles.canvas_editor.action_label_pendulum_right'),
                27: this.$t('admin.articles.canvas_editor.action_label_crux'),
                28: this.$t('admin.articles.canvas_editor.action_label_anchor_good'),
                29: this.$t('admin.articles.canvas_editor.action_label_anchor_mid'),
                30: this.$t('admin.articles.canvas_editor.action_label_anchor_bad'),
                31: this.$t('admin.articles.canvas_editor.action_label_portaledge_anchor_good'),
                32: this.$t('admin.articles.canvas_editor.action_label_portaledge_anchor_mid'),
                33: this.$t('admin.articles.canvas_editor.action_label_portaledge_anchor_bad'),
                34: this.$t('admin.articles.canvas_editor.action_label_rappel_anchor_good'),
                35: this.$t('admin.articles.canvas_editor.action_label_rappel_anchor_mid'),
                36: this.$t('admin.articles.canvas_editor.action_label_rappel_anchor_bad'),
                37: this.$t('admin.articles.canvas_editor.action_label_rescue_anchor'),
                38: this.$t('admin.articles.canvas_editor.action_label_summit'),
                39: this.$t('admin.articles.canvas_editor.action_label_tent'),
                40: this.$t('admin.articles.canvas_editor.action_label_parking'),
                41: this.$t('admin.articles.canvas_editor.action_label_poi_hiking'),
                42: this.$t('admin.articles.canvas_editor.action_label_poi_bed'),
                43: this.$t('admin.articles.canvas_editor.action_label_poi_bike'),
                44: this.$t('admin.articles.canvas_editor.action_label_poi_water'),
                45: this.$t('admin.articles.canvas_editor.action_label_poi_food'),
                46: this.$t('admin.articles.canvas_editor.action_label_poi_tent'),
                47: this.$t('admin.articles.canvas_editor.action_label_poi_medical'),
                50: this.$t('admin.articles.canvas_editor.action_label_zone_dry_tooling'),
                51: this.$t('admin.articles.canvas_editor.action_label_zone_sport_climbing'),
                52: this.$t('admin.articles.canvas_editor.action_label_zone_bouldering'),
            };
            return labels[this.action] || this.$t('admin.articles.canvas_editor.action_label_line');
        },
        activeActionIcon() {
            const icons = {
                1:  'fa fa-pencil',
                2:  'fa fa-circle',
                3:  'fa fa-pencil',
                4:  'fa fa-square-o',
                5:  'fa fa-eraser',
                7:  'fa fa-pencil',
                8:  'fa fa-arrows',
                9:  'fa fa-hand-paper-o',
                10: 'fa fa-circle-o',
                11: 'fa fa-circle-o',
                13: 'fa fa-font',
                14: 'fa fa-mouse-pointer',
                15: 'fa fa-crop',
                16: 'fa fa-share-alt',
                17: 'fa fa-arrows-alt',
                19: 'fa fa-expand',
                20: 'fa fa-pencil',
                21: 'fa fa-long-arrow-right',
                22: 'fa fa-anchor',
                23: 'fa fa-times',
                24: 'fa fa-thumb-tack',
                25: 'fa fa-reply',
                26: 'fa fa-share',
                27: 'fa fa-key',
                28: 'fa fa-minus-circle',
                29: 'fa fa-minus-circle',
                30: 'fa fa-ban',
                31: 'fa fa-minus-circle',
                32: 'fa fa-minus-circle',
                33: 'fa fa-ban',
                34: 'fa fa-minus-circle',
                35: 'fa fa-minus-circle',
                36: 'fa fa-ban',
                37: 'fa fa-dot-circle-o',
                38: 'fa fa-map-marker',
                39: 'fa fa-map-marker',
                40: 'fa fa-square',
                41: 'fa fa-male',
                42: 'fa fa-bed',
                43: 'fa fa-bicycle',
                44: 'fa fa-tint',
                45: 'fa fa-cutlery',
                46: 'fa fa-square',
                47: 'fa fa-plus',
                50: 'fa fa-square',
                51: 'fa fa-link',
                52: 'fa fa-circle',
            };
            return icons[this.action] || 'fa fa-pencil';
        },
        activeBadgeClass() {
            // Draw tools = primary, edit tools = warning, view = secondary
            const primaryActions  = [1, 2, 3, 4, 7, 10, 11, 13, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47];
            const warningActions  = [5, 8, 14, 15, 16, 17, 19];
            if (primaryActions.includes(this.action))  return 'bg-primary';
            if (warningActions.includes(this.action))  return 'bg-warning text-dark';
            return 'bg-secondary';
        }
    },
    methods: {
        // Steps the legend-scale stepper one stop (±1 through
        // legendScaleOptions) — clamps at either end instead of wrapping,
        // matching the +/- buttons' own `:disabled` state at those ends.
        stepLegendScale(delta) {
            const idx = this.legendScaleIndex + delta;
            if (idx < 0 || idx >= this.legendScaleOptions.length) return;
            this.$emit('legend-scale-change', this.legendScaleOptions[idx]);
        }
    }
}
</script>

<style scoped>
.canvas-toolbar {
    user-select: none;
}
.tool-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}
.tool-group-label {
    font-size: 9px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    line-height: 1;
    text-align: center;
}
/* "− 100% +" stepper replacing the old row of 7 size-percentage buttons
   (fixed September 2026, round 14, "make selections match more friendly") —
   a value that's really just "smaller/bigger" reads more like a real widget
   as a compact stepper than as 7 separate pills. */
.legend-scale-stepper {
    display: flex;
    align-items: center;
    gap: 4px;
}
.legend-scale-step-btn {
    width: 20px;
    height: 20px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ced4da;
    border-radius: 3px;
    background: #fff;
    color: #495057;
    font-size: 13px;
    line-height: 1;
    cursor: pointer;
}
.legend-scale-step-btn:hover:not(:disabled) {
    border-color: #86b7fe;
    background: #f0f6ff;
}
.legend-scale-step-btn:disabled {
    color: #ced4da;
    cursor: default;
}
.legend-scale-value {
    font-size: 11px;
    font-weight: 600;
    color: #333;
    width: 34px;
    text-align: center;
}
.legend-select-label {
    font-size: 9px;
    width: 26px;
    flex-shrink: 0;
    text-align: right;
}
/* Visual 3x3 compass-arrow picker replacing the old plain-text position
   dropdown (fixed September 2026, round 12; arrows + bigger cells round 14,
   "make selections match more friendly") — each cell shows a directional
   glyph matching its own spot in the grid. */
.legend-position-grid {
    display: grid;
    grid-template-columns: repeat(3, 22px);
    grid-template-rows: repeat(3, 22px);
    gap: 3px;
}
.legend-position-cell {
    width: 22px;
    height: 22px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ced4da;
    border-radius: 4px;
    background: #fff;
    color: #495057;
    font-size: 13px;
    line-height: 1;
    cursor: pointer;
}
.legend-position-cell:hover {
    border-color: #86b7fe;
    background: #f0f6ff;
}
.legend-position-cell.active {
    border-color: #0d6efd;
    background: #0d6efd;
    color: #fff;
}
/* The center "hidden" cell reads as a toggle (✕), not a direction, so it
   gets its own muted resting look distinct from the 8 real positions —
   still shares the same `.active` treatment when actually selected. */
.legend-position-cell.is-hidden-cell {
    color: #adb5bd;
}
.legend-position-cell.is-hidden-cell.active {
    color: #fff;
}
/* Approximates the "vertical bar in a circle" glyph (precarious/medium
   quality anchor) by rotating the closest real FontAwesome 4 icon
   (fa-minus-circle) 90° — the actual on-canvas symbol is drawn precisely via
   Paper.js, this is just a toolbar button hint. */
.anchor-icon-rotated {
    display: inline-block;
    transform: rotate(90deg);
}
.anchor-icon-accent {
    font-size: 8px;
    margin-left: 1px;
    vertical-align: middle;
}
/* No plain tent icon exists in the FontAwesome 4 set this app uses elsewhere
   (fa-*) — the tent emoji glyph is a widely-supported, instantly-recognizable
   stand-in for the toolbar button hint; the actual on-canvas symbol is drawn
   precisely via Paper.js (see DrawingTools.vue's add_tent). */
.tent-icon-glyph {
    font-size: 13px;
    line-height: 1;
}
.parking-icon-glyph {
    font-size: 13px;
    line-height: 1;
}
/* Same reasoning as .tent-icon-glyph above — no FontAwesome 4 "dashed line"
   icon exists, so a literal dash-leader glyph stands in for the button hint;
   the real dashed line is drawn via Paper.js's own dashArray (see
   DrawingTools.vue's add_trail), not this glyph. */
.trail-icon-glyph {
    font-size: 13px;
    line-height: 1;
    letter-spacing: -1px;
}
/* Same reasoning as .trail-icon-glyph above — a "point—point" glyph stands in
   for "edit a line's vector points" (no matching FontAwesome 4 icon); the
   real draggable point handles are drawn by CanvasHandlers.vue's own
   _drawLinePointHandles, not this glyph. */
.line-points-icon-glyph {
    font-size: 12px;
    line-height: 1;
    letter-spacing: -1px;
}
/* Same reasoning as .tent-icon-glyph above — no matching FontAwesome 4
   icons for these three zone types, so emoji glyphs stand in for the
   button hints; the real on-canvas symbols are drawn precisely via
   Paper.js (see DrawingTools.vue's _drawPoiDryToolingIcon/
   _drawPoiSportClimbingIcon/_drawPoiBoulderingIcon). */
.dry-tooling-icon-glyph,
.sport-climbing-icon-glyph,
.bouldering-icon-glyph {
    font-size: 13px;
    line-height: 1;
}
</style>
