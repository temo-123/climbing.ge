<template>
    <div class="wall-calculator">

        <!-- Hero Header -->
        <div class="calc-hero">
            <div class="calc-hero-icon"><i class="fa fa-calculator"></i></div>
            <h1 class="calc-title">{{ $t('shop.wall.title') }}</h1>
            <p class="calc-subtitle">Configure your climbing wall and get an instant price estimate</p>
        </div>

        <!-- Step Progress Bar -->
        <div class="calc-progress">
            <div class="cp-step" :class="{done: stepIndex > 0, active: stepIndex === 0}">
                <div class="cp-dot">
                    <i v-if="stepIndex > 0" class="fa fa-check"></i>
                    <span v-else>1</span>
                </div>
                <div class="cp-label">{{ $t('shop.wall.wall_type') }}</div>
            </div>
            <div class="cp-line" :class="{done: stepIndex > 0}"></div>
            <div class="cp-step" :class="{done: stepIndex > 1, active: stepIndex === 1}">
                <div class="cp-dot">
                    <i v-if="stepIndex > 1" class="fa fa-check"></i>
                    <span v-else>2</span>
                </div>
                <div class="cp-label">{{ $t('shop.wall.wall_dimensions') }}</div>
            </div>
            <div class="cp-line" :class="{done: stepIndex > 1}"></div>
            <div class="cp-step" :class="{done: total_price_sum > 0, active: stepIndex === 2}">
                <div class="cp-dot">
                    <i v-if="total_price_sum > 0" class="fa fa-check"></i>
                    <span v-else>3</span>
                </div>
                <div class="cp-label">{{ $t('shop.wall.add_options') }}</div>
            </div>
        </div>

        <div class="row calc-body">

            <!-- LEFT: Form -->
            <div class="col-md-4 calc-form-col" ref="formCol">

                <!-- Section 1: Wall Type Wizard (discipline -> structure -> sides) -->
                <div class="calc-section">
                    <div class="cs-head">
                        <span class="cs-num">1</span>
                        <h3 class="cs-title">{{ $t('shop.wall.wall_type') }}</h3>
                    </div>

                    <!-- Step 1a: discipline -->
                    <p class="cs-hint">{{ $t('shop.wall.discipline_help') }}</p>
                    <div class="wall-type-grid wall-type-grid-2">
                        <div v-for="(d, key) in coepicients.disciplines"
                             :key="key"
                             class="wt-card"
                             :class="{selected: discipline === key}"
                             @click="selectDiscipline(key)">
                            <div class="wt-icon"><i :class="d.icon"></i></div>
                            <div class="wt-name">{{ d.name }}</div>
                            <div class="wt-check"><i class="fa fa-check-circle"></i></div>
                        </div>
                    </div>

                    <!-- Step 1b: construction style -->
                    <transition name="expand">
                        <div v-if="discipline" class="wizard-substep">
                            <p class="cs-hint wizard-substep-hint">{{ $t('shop.wall.structure_help') }}</p>
                            <div class="wall-type-grid">
                                <div v-for="(s, key) in coepicients.structures"
                                     :key="key"
                                     class="wt-card"
                                     :class="{selected: structure === key}"
                                     @click="selectStructure(key)">
                                    <div class="wt-icon"><i :class="s.icon"></i></div>
                                    <div class="wt-name">{{ s.name }}</div>
                                    <div class="wt-badge"
                                         :class="s.coepicient < 1 ? 'badge-green' : (s.coepicient > 1.2 ? 'badge-orange' : 'badge-blue')">
                                        ×{{ s.coepicient }}
                                    </div>
                                    <div class="wt-check"><i class="fa fa-check-circle"></i></div>
                                </div>
                            </div>
                        </div>
                    </transition>

                    <!-- Step 1c: number of climbing sides (stand-free only) -->
                    <transition name="expand">
                        <div v-if="isStandFreeStructure" class="wizard-substep">
                            <p class="cs-hint wizard-substep-hint">{{ $t('shop.wall.sides_help') }}</p>
                            <div class="sides-picker">
                                <button v-for="n in 4" :key="n" type="button"
                                        class="side-count-btn"
                                        :class="{selected: sides_count === n}"
                                        @click="selectSidesCount(n)">
                                    {{ n }}
                                </button>
                            </div>
                        </div>
                    </transition>

                    <transition name="fade">
                        <div v-if="disciplineAutoSwitched" class="info-chip wizard-reset-hint">
                            <i class="fa fa-info-circle"></i> {{ $t('shop.wall.discipline_auto_switch_hint') }}
                        </div>
                    </transition>
                </div>

                <!-- Section 2: Wall Dimensions -->
                <transition name="expand">
                <div class="calc-section" v-if="wizardComplete">
                    <div class="cs-head">
                        <span class="cs-num">2</span>
                        <h3 class="cs-title">{{ $t('shop.wall.wall_dimensions') }}</h3>
                        <button type="button" class="cs-reset-btn" @click="resetWallDimensions">
                            <i class="fa fa-undo"></i> {{ $t('shop.wall.reset_dimensions') }}
                        </button>
                    </div>

                    <div class="dim-row dim-row-height">
                        <div class="dim-field" :class="{highlighted: focused_dim === 'height'}">
                            <label><i class="fa fa-sort"></i> {{ $t('shop.wall.height') }}</label>
                            <div class="stepper">
                                <button type="button" :disabled="height <= minWallSize" @click="adjustWall('height', -0.5)">−</button>
                                <input type="number" :value="height" min="1.5" step="0.5"
                                       :placeholder="$t('shop.wall.height_placeholder')"
                                       @focus="focused_dim = 'height'"
                                       @blur="onDimBlur('height')"
                                       @input="e => setWallDim('height', e.target.value)">
                                <button type="button" @click="adjustWall('height', 0.5)">+</button>
                            </div>
                            <span class="dim-unit">m</span>
                        </div>
                    </div>

                    <!-- Side 1 -->
                    <div class="side-block">
                        <div class="side-block-label" v-if="sides_count > 1">{{ $t('shop.wall.side_label', { n: 1 }) }}</div>
                        <div class="dim-row dim-row-2">
                            <div class="dim-field" :class="{highlighted: focused_dim === 'width'}">
                                <label><i class="fa fa-arrows-h"></i> {{ $t('shop.wall.width') }}</label>
                                <div class="stepper">
                                    <button type="button" :disabled="width <= minWallSize" @click="adjustWall('width', -0.5)">−</button>
                                    <input type="number" :value="width" min="1.5" step="0.5"
                                           :placeholder="$t('shop.wall.width_placeholder')"
                                           @focus="focused_dim = 'width'"
                                           @blur="onDimBlur('width')"
                                           @input="e => setWallDim('width', e.target.value)">
                                    <button type="button" @click="adjustWall('width', 0.5)">+</button>
                                </div>
                                <span class="dim-unit">m</span>
                            </div>

                            <div class="dim-field" :class="{highlighted: focused_dim === 'depth'}">
                                <label><i class="fa fa-expand"></i> {{ $t('shop.wall.depth') }}</label>
                                <div class="stepper">
                                    <button type="button" @click="adjustWall('depth', -0.5)">−</button>
                                    <input type="number" :value="depth" min="0" step="0.5"
                                           :placeholder="$t('shop.wall.depth_placeholder')"
                                           @focus="focused_dim = 'depth'"
                                           @blur="focused_dim = null"
                                           @input="e => setWallDim('depth', e.target.value)">
                                    <button type="button" :disabled="maxOverhangDepth > 0 && depth >= maxOverhangDepth" @click="adjustWall('depth', 0.5)">+</button>
                                </div>
                                <span class="dim-unit">m</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sides 2..N (stand-free, multi-side towers only) — width
                         always matches side 1 automatically (a symmetric tower
                         needs equal-width faces to close up cleanly; only each
                         face's own overhang depth varies), so only depth is
                         editable here. -->
                    <div class="side-block" v-for="i in (sides_count - 1)" :key="'side-' + i">
                        <div class="side-block-label">{{ $t('shop.wall.side_label', { n: i + 1 }) }}</div>
                        <div class="dim-row dim-row-sm">
                            <div class="dim-field">
                                <label><i class="fa fa-expand"></i> {{ $t('shop.wall.depth') }}</label>
                                <div class="stepper">
                                    <button type="button" @click="adjustExtraSide(i - 1, 'depth', -0.5)">−</button>
                                    <input type="number" :value="extra_sides[i - 1].depth" min="0" step="0.5"
                                           @input="e => setExtraSideDim(i - 1, 'depth', e.target.value)">
                                    <button type="button" @click="adjustExtraSide(i - 1, 'depth', 0.5)">+</button>
                                </div>
                                <span class="dim-unit">m</span>
                            </div>
                        </div>
                    </div>

                    <p class="depth-hint">{{ $t('shop.wall.depth_hint') }}</p>

                    <transition name="fade">
                        <div v-if="wall_square > 0" class="area-badge">
                            <i class="fa fa-th-large"></i>
                            {{ $t('shop.wall.wall_area') }}: <strong>{{ wall_square }} m²</strong>
                        </div>
                    </transition>

                    <transition name="fade">
                        <div v-if="wall_height_for_mat" class="info-chip">
                            <i class="fa fa-info-circle"></i> {{ $t('shop.wall.no_mat_hint') }}
                        </div>
                    </transition>
                </div>
                </transition>

                <!-- Belay rope note for sport climbing — mats don't apply there -->
                <transition name="fade">
                    <div v-if="isSportClimbing && wall_square > 0" class="info-chip">
                        <i class="fa fa-link"></i> {{ $t('shop.wall.rope_instead_of_mat_hint') }}
                    </div>
                </transition>

                <!-- Safety Mat (auto-shown for walls ≤ 5m, auto-calculated) -->
                <transition name="slide-down">
                    <div v-if="!isSportClimbing && !wall_height_for_mat && wall_square > 0" class="calc-section mat-section">
                        <div class="cs-head">
                            <span class="cs-num cs-sub"><i class="fa fa-shield"></i></span>
                            <h3 class="cs-title">{{ $t('shop.wall.mat_section_title') }}</h3>
                            <span class="auto-badge">Auto-calculated</span>
                        </div>

                        <div class="dim-row dim-row-sm">
                            <div class="dim-field">
                                <label>{{ $t('shop.wall.mat_height') }}</label>
                                <div class="stepper">
                                    <button type="button" @click="adjustMat('mat_height', -0.05)">−</button>
                                    <input type="number" :value="mat_height" min="0.05" step="0.05"
                                           :placeholder="$t('shop.wall.mat_height_placeholder')"
                                           @input="e => setMatDim('mat_height', e.target.value)">
                                    <button type="button" @click="adjustMat('mat_height', 0.05)">+</button>
                                </div>
                                <span class="dim-unit">m</span>
                            </div>

                            <div class="dim-field">
                                <label>{{ $t('shop.wall.mat_width') }}</label>
                                <div class="stepper">
                                    <button type="button" @click="adjustMat('mat_width', -0.5)">−</button>
                                    <input type="number" :value="mat_width" min="0" step="0.5"
                                           :placeholder="$t('shop.wall.mat_width_placeholder')"
                                           @input="e => setMatDim('mat_width', e.target.value)">
                                    <button type="button" @click="adjustMat('mat_width', 0.5)">+</button>
                                </div>
                                <span class="dim-unit">m</span>
                            </div>

                            <div class="dim-field">
                                <label>{{ $t('shop.wall.mat_depth') }}</label>
                                <div class="stepper">
                                    <button type="button" @click="adjustMat('mat_depth', -0.1)">−</button>
                                    <input type="number" :value="mat_depth" min="0" step="0.1"
                                           :placeholder="$t('shop.wall.mat_depth_placeholder')"
                                           @input="e => setMatDim('mat_depth', e.target.value)">
                                    <button type="button" @click="adjustMat('mat_depth', 0.1)">+</button>
                                </div>
                                <span class="dim-unit">m</span>
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Section 3: Options -->
                <div class="calc-section">
                    <div class="cs-head">
                        <span class="cs-num">3</span>
                        <h3 class="cs-title">{{ $t('shop.wall.add_options') }}</h3>
                    </div>

                    <!-- Climbing Holds toggle -->
                    <div class="option-item">
                        <div class="option-left">
                            <div class="option-icon"><i class="fa fa-circle-o"></i></div>
                            <div>
                                <div class="option-label">{{ $t('shop.wall.add_holds') }}</div>
                                <div class="option-sub">${{ coepicients.hold_midle_price.coepicient }} per hold</div>
                            </div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" v-model="hold_include" @change="colculate_wall_price()">
                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        </label>
                    </div>

                    <transition name="slide-down">
                        <div v-if="hold_include" class="holds-config">
                            <template v-if="wall_square > 0">
                                <div class="range-header">
                                    <span>{{ $t('shop.wall.holds_per_sqm') }}</span>
                                    <strong>{{ holds_quantyty_for_meter }} {{ $t('shop.wall.holds_per_unit') }}</strong>
                                </div>
                                <input type="range" class="styled-range" min="1" max="20"
                                       v-model.number="holds_quantyty_for_meter"
                                       @input="colculate_holds_price()">
                                <div class="holds-dots">
                                    <span v-for="n in 20" :key="n"
                                          class="hold-dot"
                                          :class="{active: n <= holds_quantyty_for_meter}"></span>
                                </div>
                                <div class="holds-stat">
                                    ~{{ holds_total_quantity }} holds total &middot; <strong>${{ holds_total_price }}</strong>
                                </div>
                                <div class="holds-cap-hint" v-if="maxHolesAvailable > 0">
                                    {{ $t('shop.wall.holds_capacity_hint', { available: maxHolesAvailable }) }}
                                    <span v-if="holds_total_quantity >= maxHolesAvailable" class="holds-cap-full">
                                        {{ $t('shop.wall.holds_capacity_full') }}
                                    </span>
                                </div>
                            </template>
                            <div v-else class="warn-chip">
                                <i class="fa fa-exclamation-triangle"></i>
                                {{ $t('shop.wall.enter_dimensions_first') }}
                            </div>
                        </div>
                    </transition>

                    <!-- VAT toggle -->
                    <div class="option-item">
                        <div class="option-left">
                            <div class="option-icon"><i class="fa fa-tag"></i></div>
                            <div>
                                <div class="option-label">{{ $t('shop.wall.include_vat') }}</div>
                                <div class="option-sub">{{ coepicients.vat.coepicient }}% added to subtotal</div>
                            </div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" v-model="is_vat_include" @change="colculate_wall_price()">
                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Live Price Panel -->
            <div class="col-md-8 calc-price-col" ref="priceCol">
                <div class="price-panel" :style="pricePanelStyle" ref="pricePanel">

                    <!-- Wall Diagram: interactive 3D preview, falls back to SVG if WebGL is unavailable -->
                    <transition name="fade">
                        <div v-if="wallViz" class="wall-viz">
                            <WallViewer3D
                                v-if="!viewer3dFailed"
                                ref="wallViewer"
                                :width="width"
                                :height="height"
                                :depth="depth"
                                :discipline="discipline"
                                :structure="structure"
                                :sides-count="sides_count"
                                :extra-sides="extra_sides"
                                :mat-enabled="!isSportClimbing && !wall_height_for_mat && mat_price_sum > 0"
                                :mat-width="mat_width"
                                :mat-height="mat_height"
                                :mat-depth="mat_depth"
                                :holds-enabled="hold_include"
                                :holds-count="holds_total_quantity"
                                @failed="handleViewer3dFailed"
                            />
                            <template v-else>
                                <svg :viewBox="wallViz.viewBox" class="wall-svg" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Top face -->
                                    <polygon v-if="wallViz.dOff > 0"
                                        :points="wallViz.topPoints"
                                        fill="rgba(74,144,217,0.18)" stroke="#4a90d9" stroke-width="1.2"/>
                                    <!-- Side face -->
                                    <polygon v-if="wallViz.dOff > 0"
                                        :points="wallViz.rightPoints"
                                        fill="rgba(74,144,217,0.12)" stroke="#4a90d9" stroke-width="1.2"/>
                                    <!-- Front face -->
                                    <rect :x="wallViz.ox" :y="wallViz.oy"
                                          :width="wallViz.fw" :height="wallViz.fh"
                                          fill="rgba(74,144,217,0.22)" stroke="#4a90d9" stroke-width="1.5" rx="1"/>
                                    <!-- Mat strip -->
                                    <rect v-if="!wall_height_for_mat && wallViz.fh > 12"
                                          :x="wallViz.ox" :y="wallViz.oy + wallViz.fh"
                                          :width="wallViz.fw" :height="Math.min(wallViz.fh * 0.07, 10)"
                                          fill="rgba(92,184,92,0.55)" stroke="#5cb85c" stroke-width="1"/>
                                    <!-- Width label -->
                                    <text :x="wallViz.ox + wallViz.fw / 2"
                                          :y="wallViz.oy + wallViz.fh + 20"
                                          text-anchor="middle" font-size="9" fill="#666">
                                        {{ wallViz.w }}m
                                    </text>
                                    <!-- Height label -->
                                    <text :x="wallViz.ox - 5"
                                          :y="wallViz.oy + wallViz.fh / 2"
                                          text-anchor="end" dominant-baseline="middle" font-size="9" fill="#666">
                                        {{ wallViz.h }}m
                                    </text>
                                    <!-- Depth label -->
                                    <text v-if="wallViz.d > 0 && wallViz.dOff > 8"
                                          :x="wallViz.ox + wallViz.fw + wallViz.dOff * 0.6"
                                          :y="wallViz.oy - wallViz.dOff * 0.5"
                                          font-size="9" fill="#666" dominant-baseline="middle">
                                        {{ wallViz.d }}m
                                    </text>
                                </svg>
                            </template>
                            <div class="viz-legend" v-if="!wall_height_for_mat && mat_price_sum > 0">
                                <span class="legend-dot wall-dot"></span> Wall
                                <span class="legend-dot mat-dot"></span> Safety mat
                            </div>
                        </div>
                    </transition>

                    <!-- Empty state -->
                    <div v-if="total_price_sum === 0 && !wallViz" class="price-empty">
                        <div class="pe-icon"><i class="fa fa-calculator"></i></div>
                        <p>Fill in the form to see your price estimate</p>
                    </div>

                    <!-- Price Summary -->
                    <transition name="fade">
                        <div v-if="total_price_sum > 0" class="price-summary">
                            <h4 class="ps-title">{{ $t('shop.wall.price_summary') }}</h4>

                            <div class="price-rows">
                                <template v-if="wall_price_sum > 0">
                                    <div class="pr-row">
                                        <div class="pr-info">
                                            <span class="pr-dot" style="background:#4a90d9"></span>
                                            {{ $t('shop.wall.base_price') }}
                                        </div>
                                        <span class="pr-val">${{ wall_price_sum }}</span>
                                    </div>
                                    <div class="pr-bar-wrap">
                                        <div class="pr-bar-fill" style="background:#4a90d9"
                                             :style="{width: pct(wall_price_sum)}"></div>
                                    </div>
                                </template>

                                <template v-if="!wall_height_for_mat && mat_price_sum > 0">
                                    <div class="pr-row">
                                        <div class="pr-info">
                                            <span class="pr-dot" style="background:#5cb85c"></span>
                                            {{ $t('shop.wall.mat_price') }}
                                        </div>
                                        <span class="pr-val">${{ mat_price_sum }}</span>
                                    </div>
                                    <div class="pr-bar-wrap">
                                        <div class="pr-bar-fill" style="background:#5cb85c"
                                             :style="{width: pct(mat_price_sum)}"></div>
                                    </div>
                                </template>

                                <template v-if="hold_include && holds_total_price > 0">
                                    <div class="pr-row">
                                        <div class="pr-info">
                                            <span class="pr-dot" style="background:#f0ad4e"></span>
                                            {{ $t('shop.wall.holds_price') }}
                                        </div>
                                        <span class="pr-val">${{ holds_total_price }}</span>
                                    </div>
                                    <div class="pr-bar-wrap">
                                        <div class="pr-bar-fill" style="background:#f0ad4e"
                                             :style="{width: pct(holds_total_price)}"></div>
                                    </div>
                                </template>

                                <template v-if="is_vat_include && vat_price > 0">
                                    <div class="pr-row">
                                        <div class="pr-info">
                                            <span class="pr-dot" style="background:#d9534f"></span>
                                            {{ $t('shop.wall.vat') }} ({{ coepicients.vat.coepicient }}%)
                                        </div>
                                        <span class="pr-val">${{ vat_price }}</span>
                                    </div>
                                    <div class="pr-bar-wrap">
                                        <div class="pr-bar-fill" style="background:#d9534f"
                                             :style="{width: pct(vat_price)}"></div>
                                    </div>
                                </template>
                            </div>

                            <div class="total-row">
                                <span class="total-label">{{ $t('shop.wall.total_price') }}</span>
                                <transition name="price-flash" mode="out-in">
                                    <span class="total-val" :key="total_price_sum">${{ total_price_sum }}</span>
                                </transition>
                            </div>

                            <!-- Admin-only: full construction PDF (3D renders +
                                 2D dimensioned drawings + pricing + materials) -->
                            <button v-if="$can('export', 'wall_calculator')" type="button"
                                    class="export-pdf-btn" :disabled="exportingPdf" @click="exportPdf">
                                <i class="fa" :class="exportingPdf ? 'fa-spinner fa-spin' : 'fa-file-pdf-o'"></i>
                                {{ exportingPdf ? $t('shop.wall.export_pdf_generating') : $t('shop.wall.export_pdf') }}
                            </button>
                        </div>
                    </transition>

                    <!-- Help Box: kept inside the pinned 3D/price panel itself
                         (not a sibling below it) so it stays reachable — a
                         truly fixed panel has no page scroll of its own to
                         carry a separate block down to. -->
                    <div class="help-box">
                        <div class="hb-head">
                            <i class="fa fa-question-circle"></i>
                            {{ $t('shop.wall.need_help') }}
                        </div>
                        <ul class="hb-list">
                            <li><i class="fa fa-check-circle"></i> {{ $t('shop.wall.help_dimensions') }}</li>
                            <li><i class="fa fa-check-circle"></i> {{ $t('shop.wall.help_mat') }}</li>
                            <li><i class="fa fa-check-circle"></i> {{ $t('shop.wall.help_holds') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <metaData
            :title="$t('shop.meta.climbing_wall_title')"
            :description="$t('shop.meta.climbing_wall_description')"
            :image="'/public/images/site_img/climbing_wall_img/wall.png'"
        />
    </div>
</template>

<script>
    import metaData from '../../../items/MetaDataComponent'
    import { coepicients_mixin } from './coepicients_mixin.js';
    import WallViewer3D from './viewer3d/WallViewer3D.vue';
    import { computeBoltHoleGrid } from './viewer3d/panelTexture.js';
    import { WALL_CONSTRUCTION, computeMatThickness, computeMatSideMargin } from './wallConstructionConfig.js';

    export default {
        components: { metaData, WallViewer3D },
        mixins: [coepicients_mixin],
        data() {
            return {
                coepicients: coepicients_mixin.wall(),
                focused_dim: null,
                viewer3dFailed: false,
                wall_height_for_mat: false,
                hold_include: false,
                is_vat_include: false,
                vat_price: 0,
                width: 0,
                height: 0,
                depth: 0,
                wall_square: 0,
                mat_square: 0,
                mat_width: 0,
                mat_height: 0,
                mat_depth: 0,
                holds_quantyty_for_meter: 10,
                holds_total_quantity: 0,
                holds_total_price: 0,
                wall_price_sum: 0,
                mat_price_sum: 0,
                total_price_sum: 0,
                discipline: '',
                structure: '',
                sides_count: 1,
                // Side 1 lives in width/depth above (unchanged, so the rest of the
                // pricing/3D plumbing didn't need touching); sides 2-4 for stand-free
                // multi-sided towers live here. `width` is never independently
                // entered — it's kept in sync with side 1's own width automatically
                // (see syncExtraSideWidths) so a symmetric tower's faces always
                // close up cleanly; only each side's own overhang `depth` varies.
                extra_sides: [
                    { width: 0, depth: 0 },
                    { width: 0, depth: 0 },
                    { width: 0, depth: 0 },
                ],
                disciplineAutoSwitched: false,
                pricePanelStyle: {},
                pricePanelRaf: null,
                exportingPdf: false,
            };
        },
        computed: {
            stepIndex() {
                if (!this.wizardComplete) return 0;
                if (this.wall_square === 0) return 1;
                return 2;
            },
            isBouldering() {
                return this.discipline === 'bouldering';
            },
            isSportClimbing() {
                return this.discipline === 'sport_climbing';
            },
            isStandFreeStructure() {
                return this.structure === 'standfree_indoor' || this.structure === 'standfree_outdoor';
            },
            wizardComplete() {
                if (!this.discipline || !this.structure) return false;
                return !this.isStandFreeStructure || this.sides_count > 0;
            },
            heightTooTallForBoulder() {
                return (parseFloat(this.height) || 0) >= 5;
            },
            minWallSize() {
                return WALL_CONSTRUCTION.MIN_WALL_SIZE;
            },
            // Real overhang structures don't make sense as a "wall" much past
            // roughly a 70° lean from vertical — beyond that a wall reads as a
            // near-flat roof/ceiling (and the 3D viewer's own corner/roof
            // geometry assumes a moderate tilt range), so depth is capped to
            // this ratio of height wherever it can be set (side 1 or any extra
            // tower side), instead of letting it grow independently of height.
            //
            // Multi-side towers get a SECOND, tighter cap on top of that: an
            // overhang reaching many multiples of the tower's own footprint
            // radius is exactly the "tiny tower, huge overhang" shape whose
            // corners twist adjacent faces into a self-crossing mess, however
            // the corner is triangulated — bounding depth to a multiple of the
            // tower's own radius keeps every corner's gap proportional to how
            // big the tower actually is. An initial 2.5x-radius bound still let
            // a top reach 3.5x the base radius through — every corner (not
            // just one asymmetric pair, since even a perfectly SYMMETRIC
            // overhang leaves a real per-corner gap, verified numerically)
            // opening that wide at once reads as a converging, needle-like
            // tower rather than a real structure. Tightened to keep the top
            // reach at most 2x the base radius.
            maxOverhangDepth() {
                if (!(this.height > 0)) return 0;
                const heightCap = this.height * Math.tan(WALL_CONSTRUCTION.MAX_OVERHANG_ANGLE_DEG * Math.PI / 180);
                if (this.sides_count <= 1) return heightCap;

                const avgWidth = this.all_sides.reduce((sum, s) => sum + (parseFloat(s.width) || 0), 0)
                    / this.all_sides.length;
                const radius = this.sides_count === 2
                    ? Math.max(avgWidth * 0.04, 0.14)
                    : avgWidth / (2 * Math.tan(Math.PI / this.sides_count));
                const radiusCap = radius * WALL_CONSTRUCTION.TOWER_RADIUS_OVERHANG_RATIO;
                return Math.min(heightCap, radiusCap);
            },
            // Side 1 always lives in width/depth; sides 2-4 (stand-free multi-side
            // towers only) come from extra_sides. This is the single place both the
            // pricing methods and the 3D viewer read the full side list from.
            all_sides() {
                const sides = [{ width: this.width, depth: this.depth }];
                for (let i = 0; i < this.sides_count - 1; i++) {
                    sides.push({ width: this.extra_sides[i].width, depth: this.extra_sides[i].depth });
                }
                return sides;
            },
            // A wall only has so many actual bolt holes (real 21cm T-nut grid, same
            // formula the 3D view uses) — holds priced/rendered can never exceed that,
            // no matter what the density slider says. Summed across every side.
            maxHolesAvailable() {
                if (!(this.height > 0)) return 0;
                return this.all_sides.reduce((total, side) => {
                    const w = parseFloat(side.width) || 0;
                    if (w <= 0) return total;
                    const d = parseFloat(side.depth) || 0;
                    const slantLength = d > 0 ? Math.sqrt(this.height * this.height + d * d) : this.height;
                    return total + computeBoltHoleGrid(w, slantLength).length;
                }, 0);
            },
            wallViz() {
                const w = parseFloat(this.width) || 0;
                const h = parseFloat(this.height) || 0;
                const d = parseFloat(this.depth) || 0;
                if (w <= 0 && h <= 0) return null;

                const maxW = 120, maxH = 100;
                const scale = Math.max(Math.min(maxW / (w || 1), maxH / (h || 1)), 4);
                const fw = Math.max(w * scale, w > 0 ? 30 : 0);
                const fh = Math.max(h * scale, h > 0 ? 30 : 0);
                const dOff = d > 0 ? Math.min(d * scale * 0.38, 32) : 0;

                const padL = 32, padT = 18, padR = 14, padB = 28;
                const ox = padL;
                const oy = padT + dOff;
                const svgW = fw + dOff + padL + padR;
                const svgH = fh + dOff + padT + padB;

                return {
                    w, h, d, fw, fh, dOff, ox, oy,
                    viewBox: `0 0 ${svgW} ${svgH}`,
                    topPoints: `${ox},${oy} ${ox + fw},${oy} ${ox + fw + dOff},${padT} ${ox + dOff},${padT}`,
                    rightPoints: `${ox + fw},${oy} ${ox + fw + dOff},${padT} ${ox + fw + dOff},${padT + fh} ${ox + fw},${oy + fh}`,
                };
            },
        },
        mounted() {
            this.updatePricePanelPin();
            window.addEventListener('resize', this.updatePricePanelPin);
            window.addEventListener('scroll', this.onScrollForPricePanel, { passive: true });
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.updatePricePanelPin);
            window.removeEventListener('scroll', this.onScrollForPricePanel);
            if (this.pricePanelRaf) cancelAnimationFrame(this.pricePanelRaf);
        },
        methods: {
            handleViewer3dFailed() {
                this.viewer3dFailed = true;
            },

            // Pins the price/3D panel to a fixed screen position instead of
            // relying on `position: sticky`, which depends on its own column
            // staying at least as tall as the panel — true whenever the form
            // column happens to be shorter (or CSS meant to force that, like a
            // flex `min-height: 0` fix, silently doesn't apply), the panel
            // runs out of room to "stick" and detaches, leaving a blank gap
            // where it used to be. A JS-measured `position: fixed` sidesteps
            // that container-height dependency entirely — the panel just
            // stays glued to the same screen coordinates regardless of how
            // tall either column's content is, while the form column scrolls
            // the page normally beside it.
            //
            // `position: fixed` is relative to the viewport, not to
            // `.calc-price-col`, so it has no built-in concept of "the column
            // has ended, stop floating here" — left unchecked it keeps
            // hovering over whatever comes after the calculator (the footer)
            // for the rest of the page. The natural fix is to detach into
            // `position: absolute` once the *form* column (the one with real
            // content) has scrolled past — but `.calc-price-col` itself can't
            // be used as that boundary: its only child is always taken out of
            // flow (fixed or absolute), so its own height/bottom edge
            // collapses to ~0 and is meaningless as a "container bottom" —
            // using it caused the panel to anchor to a point already above
            // the viewport the moment you scrolled, i.e. to vanish instantly.
            // Instead everything below is computed in scroll-independent
            // document coordinates (element offset + window.scrollY) anchored
            // to the form column's real bottom edge and the price column's
            // stable top edge, so the "container" being measured always has
            // genuine height regardless of the panel's own position scheme.
            updatePricePanelPin() {
                const col = this.$refs.priceCol;
                const formCol = this.$refs.formCol;
                const panel = this.$refs.pricePanel;
                if (!col || !formCol || !panel) return;
                if (window.innerWidth < 769) {
                    // Below the layout's own single-column breakpoint, the
                    // panel should just flow normally like everything else.
                    this.pricePanelStyle = {};
                    return;
                }

                const topOffset = 20;
                const scrollY = window.scrollY || window.pageYOffset || 0;
                const colRect = col.getBoundingClientRect();
                const formRect = formCol.getBoundingClientRect();
                const panelHeight = panel.offsetHeight;

                // Document-relative (scroll-independent) coordinates, so the
                // thresholds below stay correct no matter how far the page
                // has already been scrolled when this runs.
                const colDocTop = colRect.top + scrollY;
                const formDocBottom = formRect.bottom + scrollY;

                if (scrollY + topOffset < colDocTop) {
                    // Haven't scrolled up to the pin point yet — let the
                    // panel sit at its natural spot at the top of the column.
                    this.pricePanelStyle = {};
                    return;
                }

                if (scrollY + topOffset + panelHeight > formDocBottom) {
                    // A viewport-pinned panel would now reach past the end of
                    // the (taller) form column — anchor it to the price
                    // column's own top edge instead (stable regardless of the
                    // column's collapsed height), offset so the panel's
                    // bottom lines up with the form column's end. From here
                    // it scrolls away with the page instead of continuing to
                    // float over whatever follows (the footer).
                    this.pricePanelStyle = {
                        position: 'absolute',
                        top: (formDocBottom - colDocTop - panelHeight) + 'px',
                        left: '0px',
                        width: colRect.width + 'px',
                        maxHeight: 'calc(100vh - 40px)',
                        overflowY: 'auto',
                    };
                    return;
                }

                this.pricePanelStyle = {
                    position: 'fixed',
                    top: topOffset + 'px',
                    left: colRect.left + 'px',
                    width: colRect.width + 'px',
                    maxHeight: 'calc(100vh - 40px)',
                    overflowY: 'auto',
                };
            },

            // Scroll fires far more often than the layout actually needs to be
            // recomputed — rAF-throttle so we recalc at most once per frame.
            onScrollForPricePanel() {
                if (this.pricePanelRaf) return;
                this.pricePanelRaf = requestAnimationFrame(() => {
                    this.pricePanelRaf = null;
                    this.updatePricePanelPin();
                });
            },

            selectDiscipline(key) {
                const changed = this.discipline !== key;
                this.discipline = key;
                this.disciplineAutoSwitched = false;
                // Each discipline has its own sane starting size — switching
                // disciplines needs to land on the NEW one's default, not silently
                // keep whatever the previous discipline's dimensions happened to
                // be (e.g. selecting sport climbing first sets height to 6m; if
                // that carried over after switching to bouldering, which tops out
                // around 3m, the wall would still be priced/sized as if it were
                // an unusually tall bouldering wall).
                if (changed) {
                    const isSport = key === 'sport_climbing';
                    this.height = isSport ? 6 : 3;
                    this.width = 3;
                    this.depth = 0;
                    this.extra_sides.forEach((s) => { s.depth = 0; });
                }
                this.setWallDim('width', this.width);
                this.setWallDim('height', this.height);
            },

            // Resets every size field back to a sane starting point: height to
            // the discipline default, width/depth to the same values used
            // when a side is first activated — lets the user start sizing
            // over without re-picking discipline/structure from scratch.
            resetWallDimensions() {
                // The bare minimum wall size (1.5m) produces an unrealistically
                // tiny wall to start from — resets back to the same sane
                // discipline-based default selectDiscipline uses (sport climbing:
                // 6m tall, everything else: 3m), not the absolute floor.
                this.height = this.isSportClimbing ? 6 : 3;
                this.width = 3;
                this.depth = 0;
                this.extra_sides.forEach((s) => { s.depth = 0; });
                this.setWallDim('width', this.width);
                this.setWallDim('height', this.height);
            },

            selectStructure(key) {
                this.structure = key;
                const stillStandFree = key === 'standfree_indoor' || key === 'standfree_outdoor';
                if (!stillStandFree) this.sides_count = 1;
                this.colculate_wall_price();
            },

            selectSidesCount(n) {
                this.sides_count = n;
                // Every side always matches side 1's own width automatically —
                // a symmetric tower needs equal-width faces to close up
                // cleanly. A freshly-added side just starts flat (depth 0)
                // rather than guessing it should copy side 1's own overhang.
                this.syncExtraSideWidths();
                // Adding/removing sides changes the tower's own radius (and so
                // maxOverhangDepth) — a depth that was fine for a 3-sided tower
                // can be illogical the moment it becomes a 4-sided one.
                this.clampAllDepthsToMax();
                this.colculate_wall_price();
            },

            // A visible toast when a size limit actually stops the user from
            // going further, instead of the value just silently snapping back
            // — makes clear it's a deliberate physical/geometric limit, not
            // the input ignoring them.
            alertSizeLimit(message) {
                this.$bus.$emit('toast', {
                    type: 'warning',
                    title: this.$t('shop.wall.size_limit_title'),
                    message,
                    duration: 4500,
                });
            },

            // maxOverhangDepth depends on height, sides_count, AND every side's
            // own width (through the tower's radius) — so a change to any of
            // those can shrink the allowed depth for every side, not just the
            // one field that was just edited. Called after each of those
            // changes rather than duplicating this clamp per-field.
            clampAllDepthsToMax() {
                const max = this.maxOverhangDepth;
                if (!(max > 0)) return;
                const capped = Math.round(max * 100) / 100;
                let clamped = false;
                if (this.depth > max) { this.depth = capped; clamped = true; }
                this.extra_sides.forEach((s) => {
                    if (s.depth > max) { s.depth = capped; clamped = true; }
                });
                if (clamped) {
                    this.alertSizeLimit(this.$t('shop.wall.depth_limit_alert', { max: capped }));
                }
            },

            // Extra sides only ever have their own `depth` set directly by the
            // user now — `width` is kept in sync with side 1 automatically
            // (see syncExtraSideWidths), never entered independently.
            setExtraSideDim(sideIndex, key, val) {
                const next = parseFloat(val) || 0;
                this.extra_sides[sideIndex][key] = next;
                if (key === 'depth') this.clampAllDepthsToMax();
                this.colculate_wall_price();
            },

            adjustExtraSide(sideIndex, key, delta) {
                const current = parseFloat(this.extra_sides[sideIndex][key]) || 0;
                const next = Math.max(0, parseFloat((current + delta).toFixed(2)));
                this.setExtraSideDim(sideIndex, key, next);
            },

            // A symmetric multi-side tower needs every face the same width to
            // close up cleanly (this is exactly what a custom, independently
            // adjustable per-side width required a whole irregular-polygon
            // corner-gap system to handle correctly) — far simpler to just
            // keep every extra side's width equal to side 1's automatically,
            // so that system is never needed in the first place. Called
            // whenever side 1's own width changes or a side is freshly added.
            syncExtraSideWidths() {
                this.extra_sides.forEach((s) => { s.width = this.width; });
            },

            onDimBlur(key) {
                this.focused_dim = null;
                if (this[key] > 0 && this[key] < WALL_CONSTRUCTION.MIN_WALL_SIZE) {
                    this.setWallDim(key, WALL_CONSTRUCTION.MIN_WALL_SIZE);
                    this.alertSizeLimit(this.$t('shop.wall.min_size_alert', { min: WALL_CONSTRUCTION.MIN_WALL_SIZE }));
                }
            },

            setWallDim(key, val) {
                this[key] = parseFloat(val) || 0;
                if (key === 'width') this.syncExtraSideWidths();
                if (key === 'width' || key === 'height' || key === 'depth') this.clampAllDepthsToMax();
                if (key === 'height') {
                    // Bouldering walls top out around 10m — past that height it's
                    // structurally a sport-climbing (lead) wall instead, and a wall
                    // brought back under that height reads as bouldering again. Auto-
                    // switching discipline to match keeps structure/sides/everything
                    // else the user already configured intact, instead of the old
                    // behaviour of bouncing the whole wizard back to step 1.
                    if (this.isBouldering && this.heightTooTallForBoulder) {
                        this.discipline = 'sport_climbing';
                        this.disciplineAutoSwitched = true;
                    } else if (this.isSportClimbing && !this.heightTooTallForBoulder) {
                        this.discipline = 'bouldering';
                        this.disciplineAutoSwitched = true;
                    }
                }
                if (this.height > 5) {
                    this.wall_height_for_mat = true;
                    this.mat_width = this.mat_height = this.mat_depth = 0;
                    this.mat_price_sum = 0;
                } else {
                    this.wall_height_for_mat = false;
                }
                this.colculate_wall_price();
            },

            setMatDim(key, val) {
                this[key] = parseFloat(val) || 0;
                this.colculate_mat_price();
            },

            adjustWall(key, delta) {
                const floor = key === 'depth' ? 0 : WALL_CONSTRUCTION.MIN_WALL_SIZE;
                const next = Math.max(floor, parseFloat(((parseFloat(this[key]) || 0) + delta).toFixed(2)));
                this.setWallDim(key, next);
            },

            adjustMat(key, delta) {
                const next = Math.max(0, parseFloat(((parseFloat(this[key]) || 0) + delta).toFixed(3)));
                this.setMatDim(key, next);
            },

            pct(val) {
                const total = this.wall_price_sum + this.mat_price_sum + this.holds_total_price + this.vat_price;
                if (!total) return '0%';
                return Math.round((val / total) * 100) + '%';
            },

            colculate_holds_price() {
                this.holds_total_quantity = Math.min(Math.round(this.wall_square * this.holds_quantyty_for_meter), this.maxHolesAvailable);
                this.holds_total_price = this.holds_total_quantity * this.coepicients.hold_midle_price.coepicient;
                this.colculate_total_price();
            },

            colculate_wall_price() {
                // `depth` is the overhang lean distance (how far the top sticks out past
                // vertical), not a structural footprint depth — so climbable surface area
                // is the slant length (Pythagorean) per side, summed across every side of
                // a stand-free multi-side tower (just 1 side for every other structure).
                this.wall_square = 0;
                if (this.height > 0) {
                    this.all_sides.forEach((side) => {
                        const w = parseFloat(side.width) || 0;
                        if (w <= 0) return;
                        const d = parseFloat(side.depth) || 0;
                        const slant_length = d > 0 ? Math.sqrt(this.height * this.height + d * d) : this.height;
                        this.wall_square += w * slant_length;
                    });
                }
                this.wall_price_sum = this.wall_square * this.coepicients.wall_squarenes_price.coepicient;

                if (this.discipline && this.coepicients.disciplines[this.discipline]) {
                    this.wall_price_sum = this.wall_price_sum * this.coepicients.disciplines[this.discipline].coepicient;
                }
                if (this.structure && this.coepicients.structures[this.structure]) {
                    this.wall_price_sum = this.wall_price_sum * this.coepicients.structures[this.structure].coepicient;
                }

                // Sport climbing walls use ropes, not crash pads — never price a mat for them.
                if (this.isSportClimbing) {
                    this.mat_width = this.mat_height = this.mat_depth = 0;
                    this.mat_price_sum = 0;
                    this.colculate_total_price();
                } else if (!this.wall_height_for_mat) {
                    this.colculate_mat_size();
                } else {
                    this.colculate_total_price();
                }
            },

            colculate_mat_size() {
                // One mat per side in the 3D view, but priced here as an aggregate: total
                // mat coverage scales with the combined width of every side (plus a real
                // matting-standard side margin of roughly half the wall's height on each
                // end), sized by whichever side overhangs the most (a bigger overhang
                // needs a deeper landing zone and a thicker pad stack).
                const totalWidth = this.all_sides.reduce((sum, side) => sum + (parseFloat(side.width) || 0), 0);
                const maxDepth = Math.max(0, ...this.all_sides.map((side) => parseFloat(side.depth) || 0));
                this.mat_width = totalWidth + computeMatSideMargin(this.height) * 2;

                if (maxDepth > 0 && this.height > 0) {
                    this.mat_depth = maxDepth + (maxDepth * WALL_CONSTRUCTION.MAT_DEPTH_MARGIN);
                } else if (this.height > 0) {
                    this.mat_depth = WALL_CONSTRUCTION.DEFAULT_MAT_DEPTH;
                }
                if (this.height > 0) {
                    this.mat_height = computeMatThickness(maxDepth);
                }
                this.mat_square = Math.ceil(this.mat_depth * this.mat_width);

                this.colculate_mat_price();
            },

            colculate_mat_price() {
                if (this.mat_depth > 0 && this.mat_width > 0 && this.mat_height > 0) {
                    this.mat_price_sum = (this.mat_depth * this.mat_width * this.mat_height) * this.coepicients.mat_squarenes_price.coepicient;
                } else if (this.mat_width > 0 && this.mat_height > 0) {
                    this.mat_price_sum = (this.mat_width * this.mat_height) * this.coepicients.mat_squarenes_price.coepicient;
                } else {
                    this.mat_price_sum = 0;
                }
                this.colculate_total_price();
            },

            colculate_total_price() {
                this.wall_price_sum = Math.ceil(this.wall_price_sum);
                this.mat_price_sum = Math.ceil(this.mat_price_sum);

                if (this.hold_include) {
                    this.holds_total_quantity = Math.min(Math.round(this.wall_square * this.holds_quantyty_for_meter), this.maxHolesAvailable);
                    this.holds_total_price = Math.ceil(this.holds_total_quantity * this.coepicients.hold_midle_price.coepicient);
                } else {
                    this.holds_total_quantity = 0;
                    this.holds_total_price = 0;
                }

                const subtotal = this.wall_price_sum + this.mat_price_sum + this.holds_total_price;

                if (this.is_vat_include) {
                    this.vat_price = Math.ceil(subtotal * (this.coepicients.vat.coepicient / 100));
                    this.total_price_sum = subtotal + this.vat_price;
                } else {
                    this.vat_price = 0;
                    this.total_price_sum = subtotal;
                }
            },

            // A real materials/quantities estimate for the PDF export — reuses
            // the SAME construction constants (stud spacing, sheet size, frame
            // member size, bolt-hole spacing) the 3D viewer's own geometry
            // builder is built from, so this isn't a rough guess independent
            // of what's actually rendered, it's an aggregate of the real
            // lumber/hardware that configuration would need.
            computeBillOfMaterials() {
                const sides = this.all_sides;
                const isTower = sides.length > 1;
                let totalSheets = 0;
                let studLinearMeters = 0;
                let railLinearMeters = 0;

                sides.forEach((side) => {
                    const w = parseFloat(side.width) || 0;
                    if (w <= 0) return;
                    const d = parseFloat(side.depth) || 0;
                    const slant = d > 0 ? Math.sqrt(this.height * this.height + d * d) : this.height;

                    totalSheets += (w * slant) / (WALL_CONSTRUCTION.SHEET_SIZE * WALL_CONSTRUCTION.SHEET_SIZE);

                    const studCount = Math.ceil(w / WALL_CONSTRUCTION.STUD_SPACING) + 1;
                    studLinearMeters += studCount * slant;

                    // Horizontal rails are skipped on tower sides in the 3D
                    // build too (every side's own full-width rail would
                    // otherwise converge and cross in the tower's shared
                    // hollow core) — matches addSupportFrame's own skipRails.
                    if (!isTower) {
                        const railCount = Math.ceil(slant / WALL_CONSTRUCTION.STUD_SPACING) + 1;
                        railLinearMeters += railCount * w;
                    }
                });

                const rows = [
                    {
                        item: `Plywood sheets (${WALL_CONSTRUCTION.SHEET_SIZE}x${WALL_CONSTRUCTION.SHEET_SIZE}m)`,
                        quantity: Math.ceil(totalSheets),
                        unit: 'sheets',
                    },
                    {
                        item: `Frame studs (${Math.round(WALL_CONSTRUCTION.FRAME_MEMBER_SIZE * 1000)}mm lumber)`,
                        quantity: Math.ceil(studLinearMeters),
                        unit: 'linear meters',
                    },
                ];
                if (railLinearMeters > 0) {
                    rows.push({ item: 'Frame rails (same lumber)', quantity: Math.ceil(railLinearMeters), unit: 'linear meters' });
                }

                const holeGridTotal = sides.reduce((sum, side) => {
                    const w = parseFloat(side.width) || 0;
                    if (w <= 0) return sum;
                    const d = parseFloat(side.depth) || 0;
                    const slant = d > 0 ? Math.sqrt(this.height * this.height + d * d) : this.height;
                    return sum + computeBoltHoleGrid(w, slant).length;
                }, 0);
                rows.push({ item: 'T-nut bolt-hole positions (real grid capacity)', quantity: holeGridTotal, unit: 'holes' });

                if (this.hold_include && this.holds_total_quantity > 0) {
                    rows.push({ item: 'Climbing holds installed', quantity: this.holds_total_quantity, unit: 'holds' });
                }

                // A 1- or 2-sided stand-free structure gets its own separate
                // primary-frame box + diagonal subframe truss per side (see
                // addStandFreePrimaryFrame) — 3-4 sided towers instead rely on
                // each side's own radiating stud frame, so this section is
                // only relevant for the simpler stand-free cases.
                if (this.isStandFreeStructure && sides.length <= 2) {
                    rows.push({ item: 'Primary frame legs', quantity: sides.length === 2 ? 8 : 4, unit: 'posts' });
                    rows.push({ item: 'Subframe truss chords', quantity: sides.length === 2 ? 4 : 2, unit: 'members' });
                }

                if (!this.wall_height_for_mat && this.mat_price_sum > 0 && this.mat_width > 0) {
                    rows.push({
                        item: 'Safety mat',
                        quantity: 1,
                        unit: `unit (${Math.round(this.mat_width)}x${Math.round(this.mat_depth)}m)`,
                    });
                }

                return rows;
            },

            // Admin-only: captures the live 3D view from a few standard
            // angles, gathers the exact same configuration/pricing/materials
            // already computed for the on-screen calculator, and asks the
            // backend to render it all into one construction PDF. `axios`
            // here is the global instance bootstrap.js attaches to `window`
            // (same one every other component in this app already uses).
            async exportPdf() {
                if (this.exportingPdf) return;
                this.exportingPdf = true;
                try {
                    const snapshots = (this.$refs.wallViewer && this.$refs.wallViewer.captureSnapshots)
                        ? this.$refs.wallViewer.captureSnapshots()
                        : [];

                    const payload = {
                        width: this.width,
                        height: this.height,
                        depth: this.depth,
                        discipline: this.discipline,
                        structure: this.structure,
                        sides_count: this.sides_count,
                        extra_sides: this.extra_sides.slice(0, Math.max(this.sides_count - 1, 0)),
                        mat: (!this.isSportClimbing && !this.wall_height_for_mat && this.mat_price_sum > 0)
                            ? { width: this.mat_width, height: this.mat_height, depth: this.mat_depth }
                            : null,
                        holds_enabled: this.hold_include,
                        holds_count: this.holds_total_quantity,
                        rope_enabled: this.isSportClimbing,
                        price: {
                            wall_price_sum: this.wall_price_sum,
                            mat_price_sum: this.mat_price_sum,
                            holds_total_price: this.holds_total_price,
                            vat_price: this.vat_price,
                            vat_percent: this.coepicients.vat.coepicient,
                            total_price_sum: this.total_price_sum,
                        },
                        bill_of_materials: this.computeBillOfMaterials(),
                        snapshots,
                    };

                    const response = await axios.post('/set_wall_calculator/export_pdf', payload, { responseType: 'blob' });
                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'wall-configuration-' + Date.now() + '.pdf';
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(url);
                } catch (e) {
                    // The success path needs `responseType: 'blob'` for the PDF
                    // bytes, which means an ERROR response also arrives as a
                    // Blob (not parsed JSON) — read it back out as text so the
                    // real backend message (e.g. a validation error) shows up
                    // instead of a generic failure.
                    let message = this.$t('shop.wall.export_pdf_error');
                    if (e.response && e.response.data instanceof Blob) {
                        try {
                            const text = await e.response.data.text();
                            const parsed = JSON.parse(text);
                            if (parsed.message) message = parsed.message;
                        } catch (parseError) {
                            // Keep the generic message if the error body isn't JSON.
                        }
                    }
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('shop.wall.export_pdf_error_title'),
                        message,
                        duration: 5500,
                    });
                } finally {
                    this.exportingPdf = false;
                }
            },
        },
    }
</script>

<style scoped>
/* ── Variables ──────────────────────────────────────────── */
.wall-calculator {
    --primary: #4a90d9;
    --primary-light: rgba(74, 144, 217, 0.12);
    --success: #5cb85c;
    --warning: #f0ad4e;
    --danger: #d9534f;
    --radius: 10px;
    --shadow: 0 2px 12px rgba(0,0,0,0.08);
    /* max-width: 1100px; */
    margin: 0 auto;
    padding: 0 16px 60px;
}

/* ── Hero ───────────────────────────────────────────────── */
.calc-hero {
    text-align: center;
    padding: 40px 20px 30px;
}
.calc-hero-icon {
    width: 64px; height: 64px;
    background: var(--primary);
    color: #fff;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin-bottom: 14px;
    box-shadow: 0 4px 16px rgba(74,144,217,0.35);
}
.calc-title {
    font-size: 26px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 8px;
}
.calc-subtitle {
    color: #666;
    font-size: 15px;
    margin: 0;
}

/* ── Progress Bar ───────────────────────────────────────── */
.calc-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 32px;
    max-width: 480px;
    padding: 0 16px;
}
.cp-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}
.cp-dot {
    width: 34px; height: 34px;
    border-radius: 50%;
    border: 2px solid #ddd;
    background: #fff;
    color: #aaa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s;
}
.cp-step.active .cp-dot {
    border-color: var(--primary);
    color: var(--primary);
    box-shadow: 0 0 0 4px rgba(74,144,217,0.15);
}
.cp-step.done .cp-dot {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}
.cp-label {
    font-size: 11px;
    color: #aaa;
    white-space: nowrap;
    transition: color 0.3s;
}
.cp-step.active .cp-label,
.cp-step.done .cp-label {
    color: var(--primary);
    font-weight: 600;
}
.cp-line {
    flex: 1;
    height: 2px;
    background: #e0e0e0;
    margin: 0 6px;
    margin-bottom: 18px;
    transition: background 0.3s;
}
.cp-line.done { background: var(--primary); }

/* ── Sections ───────────────────────────────────────────── */
.calc-body { margin-top: 0; }

/* The 3D/price panel's screen position is pinned via JS (updatePricePanelPin,
   applied through :style on .price-panel) rather than CSS `position: sticky`
   — sticky depends on its own column staying taller than the panel itself,
   which broke repeatedly (as the form grew with more wizard fields, or once
   a CSS flexbox min-height quirk silently defeated the workaround). A
   JS-measured `position: fixed` has no such dependency; .calc-price-col just
   needs to exist so its width/left can be measured, nothing else. */

.calc-section {
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius);
    padding: 18px 20px;
    margin-bottom: 14px;
    box-shadow: var(--shadow);
    transition: box-shadow 0.2s;
}
.calc-section:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,0.11);
}
.mat-section {
    background: #f8fffe;
    border-color: #c3e6cb;
}

.cs-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}
.cs-num {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--primary);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.cs-sub {
    background: var(--success);
    font-size: 12px;
}
.cs-title {
    font-size: 16px;
    font-weight: 600;
    color: #222;
    margin: 0;
}
.cs-hint {
    color: #888;
    font-size: 13px;
    margin: 0 0 16px;
}
.auto-badge {
    background: #d4edda;
    color: #155724;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 12px;
    margin-left: auto;
    font-weight: 500;
}
.cs-reset-btn {
    margin-left: auto;
    background: none;
    border: 1px solid #e8ecf0;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    color: #888;
    cursor: pointer;
    transition: all 0.15s;
    flex-shrink: 0;
}
.cs-reset-btn:hover {
    border-color: var(--danger);
    color: var(--danger);
    background: rgba(217,83,79,0.06);
}
.cs-reset-btn .fa { margin-right: 4px; }

/* ── Wall Type Wizard ───────────────────────────────────── */
.wizard-substep {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px dashed #e8ecf0;
}
.wizard-substep-hint {
    margin-bottom: 8px;
}
.wizard-reset-hint {
    margin-top: 10px;
}
.sides-picker {
    display: flex;
    gap: 10px;
}
.side-count-btn {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    border: 2px solid #e8ecf0;
    background: #fafbfc;
    font-size: 18px;
    font-weight: 700;
    color: #333;
    cursor: pointer;
    transition: all 0.2s;
}
.side-count-btn:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}
.side-count-btn.selected {
    border-color: var(--primary);
    background: var(--primary);
    color: #fff;
}
.side-block {
    margin-bottom: 10px;
    padding: 10px 12px 2px;
    background: #fafbfc;
    border: 1px solid #eef1f4;
    border-radius: 8px;
}
.side-block-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 6px;
}
/* ── Wall Type Cards ────────────────────────────────────── */
.wall-type-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}
.wall-type-grid-2 {
    grid-template-columns: repeat(2, 1fr);
}
.wall-type-grid-2 .wt-icon {
    font-size: 30px;
}
.wall-type-grid-2 .wt-name {
    font-size: 13px;
}
.wt-card {
    border: 2px solid #e8ecf0;
    border-radius: 8px;
    padding: 14px 10px 10px;
    text-align: center;
    cursor: pointer;
    position: relative;
    transition: all 0.2s;
    background: #fafbfc;
    user-select: none;
}
.wt-card:hover {
    border-color: var(--primary);
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(74,144,217,0.18);
}
.wt-card.selected {
    border-color: var(--primary);
    background: var(--primary-light);
    box-shadow: 0 0 0 3px rgba(74,144,217,0.2);
}
.wt-icon {
    font-size: 22px;
    color: var(--primary);
    margin-bottom: 7px;
}
.wt-name {
    font-size: 11px;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}
.wt-badge {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    margin-top: 6px;
}
.badge-blue { background: #dbeafe; color: #1d4ed8; }
.badge-green { background: #dcfce7; color: #166534; }
.badge-orange { background: #fef3c7; color: #92400e; }
.wt-check {
    position: absolute;
    top: 7px;
    right: 8px;
    color: var(--primary);
    font-size: 14px;
    opacity: 0;
    transition: opacity 0.2s;
}
.wt-card.selected .wt-check { opacity: 1; }

/* ── Dimension Inputs ───────────────────────────────────── */
.dim-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}
.dim-row-sm { gap: 10px; }
/* Only ever holds a single .dim-field, so it doesn't need .dim-row's
   3-column split — must come AFTER `.dim-row` in source order, since with
   equal class specificity the later rule wins regardless of the order the
   classes are listed on the element (this used to be defined earlier in the
   file, so `.dim-row`'s 3-column template silently won anyway, squeezing the
   field into ~1/3 of its width — narrow enough that the stepper's number
   input had no room left to show its value at all). No `max-width` either:
   the field should read as a full-size input matching the width/depth
   fields below it, not a small capped box off to one side. */
.dim-row-height {
    grid-template-columns: 1fr;
    margin-bottom: 14px;
}

.dim-field {
    position: relative;
    min-width: 0;
    padding-bottom: 2px;
    border-radius: 6px;
    transition: background 0.15s;
}
.depth-hint {
    margin: 6px 0 0;
    font-size: 11.5px;
    color: #888;
    line-height: 1.4;
}
.dim-field.highlighted {
    background: rgba(74,144,217,0.05);
}
.dim-field label {
    font-size: 12px;
    font-weight: 600;
    color: #555;
    margin-bottom: 6px;
    display: block;
}
.stepper {
    display: flex;
    align-items: center;
    border: 1.5px solid #d0d7de;
    border-radius: 6px;
    overflow: hidden;
    transition: border-color 0.15s;
}
.dim-field.highlighted .stepper,
.stepper:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(74,144,217,0.15);
}
.stepper button {
    width: 32px;
    min-width: 32px;
    height: 36px;
    border: none;
    background: #f6f8fa;
    color: #555;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}
.stepper button:hover {
    background: var(--primary);
    color: #fff;
}
.stepper input {
    flex: 1;
    min-width: 0;
    border: none;
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    padding: 0 4px;
    height: 36px;
    outline: none;
    background: #fff;
    color: #222;
}
.stepper input::-webkit-inner-spin-button,
.stepper input::-webkit-outer-spin-button { -webkit-appearance: none; }
.dim-unit {
    display: block;
    font-size: 11px;
    color: #999;
    text-align: right;
    margin-top: 3px;
}

.area-badge {
    margin-top: 14px;
    display: inline-block;
    background: #e8f4fd;
    color: #1a6da3;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}
.info-chip {
    margin-top: 12px;
    padding: 9px 14px;
    background: #e8f4fd;
    border-radius: 6px;
    font-size: 13px;
    color: #31708f;
}
.warn-chip {
    padding: 9px 14px;
    background: #fef8e7;
    border-radius: 6px;
    font-size: 13px;
    color: #856404;
}

/* ── Options / Toggles ──────────────────────────────────── */
.option-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid #f0f0f0;
}
.option-item:last-child { border-bottom: none; }
.option-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.option-icon {
    width: 36px; height: 36px;
    border-radius: 8px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}
.option-label {
    font-size: 14px;
    font-weight: 600;
    color: #222;
}
.option-sub {
    font-size: 12px;
    color: #888;
    margin-top: 2px;
}

/* Toggle switch */
.toggle {
    position: relative;
    cursor: pointer;
}
.toggle input { position: absolute; opacity: 0; width: 0; }
.toggle-track {
    display: block;
    width: 44px;
    height: 24px;
    background: #d0d7de;
    border-radius: 12px;
    position: relative;
    transition: background 0.25s;
}
.toggle input:checked + .toggle-track { background: var(--primary); }
.toggle-thumb {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    transition: transform 0.25s;
}
.toggle input:checked + .toggle-track .toggle-thumb { transform: translateX(20px); }

/* Holds config */
.holds-config {
    padding: 16px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 6px;
}
.range-header {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #555;
    margin-bottom: 10px;
}
.styled-range {
    width: 100%;
    height: 6px;
    border-radius: 3px;
    appearance: none;
    background: linear-gradient(to right, var(--primary) 0%, var(--primary) calc(var(--val, 50%)), #ddd calc(var(--val, 50%)));
    cursor: pointer;
    outline: none;
    margin-bottom: 12px;
}
.styled-range::-webkit-slider-thumb {
    appearance: none;
    width: 18px; height: 18px;
    border-radius: 50%;
    background: var(--primary);
    border: 2px solid #fff;
    box-shadow: 0 1px 5px rgba(0,0,0,0.2);
    cursor: grab;
}
.holds-dots {
    display: grid;
    grid-template-columns: repeat(20, 1fr);
    gap: 3px;
    margin-bottom: 10px;
}
.hold-dot {
    height: 10px;
    border-radius: 2px;
    background: #ddd;
    transition: background 0.15s;
    display: block;
}
.hold-dot.active { background: var(--primary); }
.holds-stat {
    font-size: 13px;
    color: #555;
    text-align: center;
}
.holds-cap-hint {
    margin-top: 8px;
    font-size: 11.5px;
    color: #999;
    text-align: center;
    line-height: 1.4;
}
.holds-cap-full {
    display: inline-block;
    margin-left: 4px;
    color: #b94a48;
    font-weight: 600;
}

/* ── Price Panel ────────────────────────────────────────── */
/* `position: relative` gives the panel's "detached" state (see
   updatePricePanelPin) a stable anchor — its `top` offset is computed
   against this element's own top edge once the form column has scrolled
   past, regardless of this column's own (collapsed) height. */
.calc-price-col {
    position: relative;
}
/* Position/top/left/width/max-height/overflow are all set via the
   `pricePanelStyle` inline style (see updatePricePanelPin) so the panel stays
   pinned to a fixed screen position while the form scrolls the page normally
   beside it — only the panel's own visual styling lives here. `max-height` +
   `overflow-y` (applied inline) give the panel an internal scrollbar for the
   rare case its own content (3D viewer + price breakdown + help box) is
   taller than the viewport, so nothing inside it (the help box in
   particular) ends up unreachable below the fold. */
.price-panel {
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: var(--shadow);
    margin-bottom: 16px;
}

/* Wall SVG */
.wall-viz {
    text-align: center;
    padding: 10px 0 8px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 16px;
}
.wall-svg {
    max-width: 220px;
    height: auto;
    display: inline-block;
}
.viz-legend {
    font-size: 11px;
    color: #888;
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
    margin-top: 4px;
}
.legend-dot {
    width: 10px; height: 10px;
    border-radius: 2px;
    display: inline-block;
}
.wall-dot { background: rgba(74,144,217,0.55); }
.mat-dot { background: rgba(92,184,92,0.65); }

/* Empty state */
.price-empty {
    text-align: center;
    padding: 40px 20px;
    color: #aaa;
}
.pe-icon {
    font-size: 40px;
    margin-bottom: 12px;
    opacity: 0.4;
}
.pe-icon .fa { color: var(--primary); }
.price-empty p { font-size: 14px; }

/* Price breakdown */
.ps-title {
    font-size: 15px;
    font-weight: 700;
    color: #222;
    margin: 0 0 14px;
}
.price-rows { margin-bottom: 14px; }

.pr-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    color: #444;
    padding: 5px 0 3px;
}
.pr-info {
    display: flex;
    align-items: center;
    gap: 7px;
}
.pr-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.pr-val {
    font-weight: 600;
    color: #222;
    white-space: nowrap;
}
.pr-bar-wrap {
    height: 4px;
    background: #f0f0f0;
    border-radius: 2px;
    margin-bottom: 8px;
    overflow: hidden;
}
.pr-bar-fill {
    height: 100%;
    border-radius: 2px;
    transition: width 0.4s cubic-bezier(0.25,0.46,0.45,0.94);
    min-width: 4px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid #f0f0f0;
    padding-top: 14px;
    margin-top: 6px;
}
.total-label {
    font-size: 15px;
    font-weight: 700;
    color: #222;
}
.total-val {
    font-size: 24px;
    font-weight: 800;
    color: var(--primary);
}

.export-pdf-btn {
    width: 100%;
    margin-top: 14px;
    padding: 11px 16px;
    background: #1a1a2e;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.15s;
}
.export-pdf-btn:hover:not(:disabled) { background: #2c2c4a; }
.export-pdf-btn:disabled { opacity: 0.65; cursor: not-allowed; }

/* ── Help Box ───────────────────────────────────────────── */
.help-box {
    background: #f8f9fa;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius);
    padding: 16px 20px;
    margin-top: 16px;
}
.hb-head {
    font-size: 14px;
    font-weight: 600;
    color: #31708f;
    margin-bottom: 10px;
}
.hb-head .fa { margin-right: 6px; }
.hb-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.hb-list li {
    font-size: 13px;
    color: #555;
    padding: 4px 0;
}
.hb-list .fa {
    color: var(--success);
    margin-right: 6px;
}

/* ── Transitions ────────────────────────────────────────── */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-down-enter-active { transition: all 0.3s ease; }
.slide-down-leave-active { transition: all 0.25s ease; }
.slide-down-enter-from { opacity: 0; transform: translateY(-10px); }
.slide-down-leave-to { opacity: 0; transform: translateY(-8px); }

.expand-enter-active { transition: all 0.3s ease; overflow: hidden; }
.expand-leave-active { transition: all 0.25s ease; overflow: hidden; }
.expand-enter-from { opacity: 0; max-height: 0; }
.expand-enter-to { opacity: 1; max-height: 200px; }
.expand-leave-to { opacity: 0; max-height: 0; }

.price-flash-enter-active {
    animation: price-up 0.22s ease;
}
@keyframes price-up {
    from { transform: translateY(-6px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .wall-type-grid { grid-template-columns: repeat(2, 1fr); }
    .dim-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    /* Still only ever holds one field — keep it full-width rather than
       inheriting the 2-column split meant for the width/depth rows. */
    .dim-row-height { grid-template-columns: 1fr; }
    /* Below this breakpoint updatePricePanelPin() itself clears the inline
       pin style, so the panel just flows normally — nothing to override here. */
    .calc-title { font-size: 22px; }
}
@media (max-width: 480px) {
    .wall-type-grid { grid-template-columns: 1fr 1fr; }
    .dim-row { grid-template-columns: minmax(0, 1fr); }
    .calc-section { padding: 16px; }
}
</style>
