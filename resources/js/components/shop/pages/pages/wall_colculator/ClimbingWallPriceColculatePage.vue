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
            <div class="col-md-7">

                <!-- Section 1: Wall Type -->
                <div class="calc-section">
                    <div class="cs-head">
                        <span class="cs-num">1</span>
                        <h3 class="cs-title">{{ $t('shop.wall.wall_type') }}</h3>
                    </div>
                    <p class="cs-hint">{{ $t('shop.wall.wall_type_help') }}</p>

                    <div class="wall-type-grid">
                        <div v-for="(type, key) in coepicients.wall_types"
                             :key="key"
                             class="wt-card"
                             :class="{selected: wall_type === key}"
                             @click="selectWallType(key)">
                            <div class="wt-icon"><i :class="wallTypeIcon(key)"></i></div>
                            <div class="wt-name">{{ type.name }}</div>
                            <div class="wt-badge"
                                 :class="type.coepicient < 1 ? 'badge-green' : (type.coepicient > 1.2 ? 'badge-orange' : 'badge-blue')">
                                <span v-if="type.coepicient >= 1">×{{ type.coepicient }}</span>
                                <span v-else>-{{ Math.round((1 - type.coepicient) * 100) }}%</span>
                            </div>
                            <div class="wt-check"><i class="fa fa-check-circle"></i></div>
                        </div>
                    </div>

                    <transition name="expand">
                        <div v-if="wall_type && translatedDescription" class="type-description">
                            <i class="fa fa-info-circle"></i> {{ translatedDescription }}
                        </div>
                    </transition>
                </div>

                <!-- Section 2: Wall Dimensions -->
                <div class="calc-section">
                    <div class="cs-head">
                        <span class="cs-num">2</span>
                        <h3 class="cs-title">{{ $t('shop.wall.wall_dimensions') }}</h3>
                    </div>

                    <div class="dim-row">
                        <div class="dim-field" :class="{highlighted: focused_dim === 'height'}">
                            <label><i class="fa fa-sort"></i> {{ $t('shop.wall.height') }}</label>
                            <div class="stepper">
                                <button type="button" @click="adjustWall('height', -0.5)">−</button>
                                <input type="number" :value="height" min="0" step="0.5"
                                       :placeholder="$t('shop.wall.height_placeholder')"
                                       @focus="focused_dim = 'height'"
                                       @blur="focused_dim = null"
                                       @input="e => setWallDim('height', e.target.value)">
                                <button type="button" @click="adjustWall('height', 0.5)">+</button>
                            </div>
                            <span class="dim-unit">m</span>
                        </div>

                        <div class="dim-field" :class="{highlighted: focused_dim === 'width'}">
                            <label><i class="fa fa-arrows-h"></i> {{ $t('shop.wall.width') }}</label>
                            <div class="stepper">
                                <button type="button" @click="adjustWall('width', -0.5)">−</button>
                                <input type="number" :value="width" min="0" step="0.5"
                                       :placeholder="$t('shop.wall.width_placeholder')"
                                       @focus="focused_dim = 'width'"
                                       @blur="focused_dim = null"
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
                                <button type="button" @click="adjustWall('depth', 0.5)">+</button>
                            </div>
                            <span class="dim-unit">m</span>
                        </div>
                    </div>

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

                <!-- Safety Mat (auto-shown for walls ≤ 5m, auto-calculated) -->
                <transition name="slide-down">
                    <div v-if="!wall_height_for_mat && wall_square > 0" class="calc-section mat-section">
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
            <div class="col-md-5">
                <div class="price-panel">

                    <!-- Wall SVG Diagram -->
                    <transition name="fade">
                        <div v-if="wallViz" class="wall-viz">
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
                        </div>
                    </transition>
                </div>

                <!-- Help Box -->
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

    export default {
        components: { metaData },
        mixins: [coepicients_mixin],
        data() {
            return {
                coepicients: coepicients_mixin.wall(),
                focused_dim: null,
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
                wall_type: '',
            };
        },
        computed: {
            stepIndex() {
                if (!this.wall_type) return 0;
                if (this.wall_square === 0) return 1;
                return 2;
            },
            translatedDescription() {
                if (!this.wall_type || !this.coepicients.wall_types[this.wall_type]) return '';
                const desc = this.coepicients.wall_types[this.wall_type].description;
                if (!desc) return '';
                if (desc.startsWith('$siteData.')) {
                    const path = desc.replace('$siteData.', '').replace(/^data\./, '');
                    return (this.$siteData && this.$siteData.data && this.$siteData.data[path]) || '';
                }
                if (desc.includes('.')) return this.$t(desc);
                return desc;
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
        methods: {
            wallTypeIcon(key) {
                const map = {
                    indoor_bouldering: 'fa fa-home',
                    outdoor_bouldering: 'fa fa-tree',
                    indoor_sport_climbing: 'fa fa-building',
                    outdoor_sport_climbing: 'fa fa-globe',
                    indoor_stand_free_wall: 'fa fa-cube',
                    outdoor_stand_free_wall: 'fa fa-cloud',
                };
                return map[key] || 'fa fa-th-large';
            },

            selectWallType(key) {
                this.wall_type = key;
                this.colculate_wall_price();
            },

            setWallDim(key, val) {
                this[key] = parseFloat(val) || 0;
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
                const next = Math.max(0, parseFloat(((parseFloat(this[key]) || 0) + delta).toFixed(2)));
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
                this.holds_total_quantity = Math.round(this.wall_square * this.holds_quantyty_for_meter);
                this.holds_total_price = this.holds_total_quantity * this.coepicients.hold_midle_price.coepicient;
                this.colculate_total_price();
            },

            colculate_wall_price() {
                if (this.depth > 0 && this.width > 0 && this.height > 0) {
                    this.wall_square = this.depth * this.width * this.height;
                    this.wall_price_sum = this.wall_square * this.coepicients.wall_squarenes_price.coepicient;
                } else if (this.width > 0 && this.height > 0) {
                    this.wall_square = this.height * this.width;
                    this.wall_price_sum = this.wall_square * this.coepicients.wall_squarenes_price.coepicient;
                } else {
                    this.wall_square = 0;
                    this.wall_price_sum = 0;
                }

                if (this.wall_type && this.coepicients.wall_types[this.wall_type]) {
                    this.wall_price_sum = this.wall_price_sum * this.coepicients.wall_types[this.wall_type].coepicient;
                }

                if (!this.wall_height_for_mat) {
                    this.colculate_mat_size();
                } else {
                    this.colculate_total_price();
                }
            },

            colculate_mat_size() {
                this.mat_width = this.width;
                this.mat_square = this.mat_depth * this.mat_width;
                this.mat_square = Math.ceil(this.mat_square);

                if (this.depth > 0 && this.width > 0 && this.height > 0) {
                    this.depth = Number(this.depth);
                    let procent = (this.depth * 30) / 100;
                    this.mat_depth = this.depth + procent;
                    this.mat_height = this.height * 0.08;
                } else if (this.width > 0 && this.height > 0) {
                    this.mat_height = this.height * 0.08;
                    this.mat_depth = 1.5;
                }

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
                    this.holds_total_quantity = Math.round(this.wall_square * this.holds_quantyty_for_meter);
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
    max-width: 1100px;
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

.calc-section {
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius);
    padding: 22px 24px;
    margin-bottom: 16px;
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

/* ── Wall Type Cards ────────────────────────────────────── */
.wall-type-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
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

.type-description {
    margin-top: 14px;
    padding: 12px 14px;
    background: #f0f7ff;
    border-left: 3px solid var(--primary);
    border-radius: 6px;
    font-size: 13.5px;
    color: #444;
    line-height: 1.55;
}

/* ── Dimension Inputs ───────────────────────────────────── */
.dim-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}
.dim-row-sm { gap: 10px; }

.dim-field {
    position: relative;
    padding-bottom: 4px;
    border-radius: 6px;
    transition: background 0.15s;
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

/* ── Price Panel ────────────────────────────────────────── */
.price-panel {
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: var(--shadow);
    margin-bottom: 16px;
    position: sticky;
    top: 20px;
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

/* ── Help Box ───────────────────────────────────────────── */
.help-box {
    background: #f8f9fa;
    border: 1px solid #e8ecf0;
    border-radius: var(--radius);
    padding: 16px 20px;
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
    .dim-row { grid-template-columns: 1fr 1fr; }
    .price-panel { position: static; }
    .calc-title { font-size: 22px; }
}
@media (max-width: 480px) {
    .wall-type-grid { grid-template-columns: 1fr 1fr; }
    .dim-row { grid-template-columns: 1fr; }
    .calc-section { padding: 16px; }
}
</style>
