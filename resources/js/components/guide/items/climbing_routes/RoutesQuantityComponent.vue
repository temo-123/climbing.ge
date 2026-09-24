<template>
    <div class="routes_quantity" v-if="slides.length">
        <div class="routes_quantity_card">
            <div class="routes_quantity_header">
                <span class="routes_quantity_title">{{ activeSlide.title }}</span>
                <span class="routes_quantity_total">{{ activeSlide.total }}</span>
            </div>

            <div class="routes_quantity_track" ref="track" @scroll.passive="onScroll">
                <div class="routes_quantity_slide" v-for="slide in slides" :key="slide.key">
                    <GChart
                        :type="slide.type"
                        :data="slide.data"
                        :options="slide.options"

                        :resizeDebounce = "100"
                    />
                </div>
            </div>

            <div class="routes_quantity_nav" v-if="slides.length > 1">
                <button
                    type="button"
                    class="routes_quantity_nav_btn"
                    :class="{ invisible: activeIndex === 0 }"
                    :aria-label="$t('guide.chart_prev')"
                    @click="goTo(activeIndex - 1)"
                >
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span class="routes_quantity_nav_label">{{ slides[activeIndex - 1] ? slides[activeIndex - 1].short : '' }}</span>
                </button>

                <span class="routes_quantity_counter">{{ activeIndex + 1 }} / {{ slides.length }}</span>

                <button
                    type="button"
                    class="routes_quantity_nav_btn"
                    :class="{ invisible: activeIndex === slides.length - 1 }"
                    :aria-label="$t('guide.chart_next')"
                    @click="goTo(activeIndex + 1)"
                >
                    <span class="routes_quantity_nav_label">{{ slides[activeIndex + 1] ? slides[activeIndex + 1].short : '' }}</span>
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

    // route.category value => [translation key, slice color]
    const ROUTE_TYPES = {
        'sport climbing': ['guide.route_type_sport_climbing', '#F1C40F'],
        'bouldering': ['guide.route_type_bouldering', '#1ABC9C'],
        'top': ['guide.route_type_top_rope', '#3498DB'],
        'tred': ['guide.route_type_trad_climbing', '#E67E22'],
        'multi pitch': ['guide.route_type_multi_pitch', '#9B59B6'],
        'ice climbing': ['guide.route_type_ice_climbing', '#5DADE2'],
        'dry tooling': ['guide.route_type_dry_tooling', '#7F8C8D'],
    }

    export default {
        props: [
            'outdoor_region_article_id'
        ],
        components: {
            GChart
        },
        data: function () {
            return {
                sportChartData: [],
                bolderChartData: [],
                routeTypesData: [],

                activeIndex: 0,

                id: this.outdoor_region_article_id
            };
        },
        computed: {
            slides() {
                const slides = []

                // pie only makes sense when the region mixes several route types
                if (this.routeTypesData.length > 2) {
                    slides.push({
                        key: 'route_types',
                        type: 'PieChart',
                        title: this.$t('guide.route_types_title'),
                        short: this.$t('guide.route_types_title'),
                        total: this.total(this.routeTypesData),
                        data: this.translatedRouteTypes(),
                        options: this.pieOptions(),
                    })
                }
                if (this.sportChartData.length > 1) {
                    slides.push({
                        key: 'sport',
                        type: 'ColumnChart',
                        title: this.$t('guide.sport_route_quantity_title'),
                        short: this.$t('guide.chart_short_sport'),
                        total: this.total(this.sportChartData),
                        data: this.withAnnotations(this.sportChartData),
                        options: this.chartOptions(this.sportChartData),
                    })
                }
                if (this.bolderChartData.length > 1) {
                    slides.push({
                        key: 'bouldering',
                        type: 'ColumnChart',
                        title: this.$t('guide.bouldering_route_quantity_title'),
                        short: this.$t('guide.chart_short_bouldering'),
                        total: this.total(this.bolderChartData),
                        data: this.withAnnotations(this.bolderChartData),
                        options: this.chartOptions(this.bolderChartData),
                    })
                }

                return slides
            },
            activeSlide() {
                return this.slides[this.activeIndex] || this.slides[0]
            },
        },
        mounted() {
            this.get_routes_quantiti()
        },
        watch: {
            '$route' (to, from) {
                this.get_routes_quantiti(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_routes_quantiti(){
                this.bolderChartData = []
                this.sportChartData = []
                this.routeTypesData = []
                this.activeIndex = 0
                if (this.$refs.track) this.$refs.track.scrollLeft = 0

                axios
                .get("/get_route/get_routes_quantity/"+this.id)
                .then(response => {
                    this.bolderChartData = response.data.bouldering
                    this.sportChartData = response.data.sport_routes
                    this.routeTypesData = response.data.route_types || []
                })
                .catch(
                    error => console.log(error)
                );
            },

            update(id){
                this.id = id
                this.get_routes_quantiti()
            },

            goTo(index){
                const track = this.$refs.track
                if (!track) return
                const target = Math.max(0, Math.min(index, this.slides.length - 1))
                track.scrollTo({ left: target * track.clientWidth, behavior: 'smooth' })
                this.activeIndex = target
            },

            onScroll(){
                const track = this.$refs.track
                if (!track || !track.clientWidth) return
                this.activeIndex = Math.round(track.scrollLeft / track.clientWidth)
            },

            total(chartData){
                return chartData.slice(1).reduce((sum, row) => sum + Number(row[1]), 0)
            },

            translatedRouteTypes(){
                const [header, ...rows] = this.routeTypesData
                return [
                    [header[0], this.$t('common.routes')],
                    ...rows.map(([category, count]) => [
                        ROUTE_TYPES[category] ? this.$t(ROUTE_TYPES[category][0]) : category,
                        Number(count),
                    ]),
                ]
            },

            pieOptions(){
                const narrow = window.innerWidth < 576

                return {
                    height: 240,
                    is3D: true,
                    backgroundColor: 'transparent',
                    fontName: 'inherit',
                    pieSliceText: 'value',
                    pieSliceTextStyle: { fontSize: 13, bold: true, color: '#fff' },
                    colors: this.routeTypesData.slice(1).map(([category]) => (ROUTE_TYPES[category] || [])[1] || '#95A5A6'),
                    legend: {
                        position: narrow ? 'bottom' : 'right',
                        alignment: 'center',
                        textStyle: { fontSize: 13, color: '#555' },
                    },
                    chartArea: narrow
                        ? { left: 8, right: 8, top: 10, bottom: 40, width: '100%', height: '100%' }
                        : { left: 8, right: 8, top: 10, bottom: 10, width: '100%', height: '100%' },
                    tooltip: { textStyle: { fontSize: 12 } },
                }
            },

            // Adds a translated series label and a count label on top of every bar
            withAnnotations(chartData){
                const [header, ...rows] = chartData
                return [
                    [header[0], this.$t('common.routes'), ...header.slice(2), { role: 'annotation' }],
                    ...rows.map(row => [...row, row[1]]),
                ]
            },

            chartOptions(chartData){
                const max = Math.max(1, ...chartData.slice(1).map(row => Number(row[1])))
                const step = Math.max(1, Math.ceil(max / 4))
                const ticks = []
                for (let i = 0; i <= Math.ceil(max / step) * step; i += step) ticks.push(i)

                return {
                    height: 240,
                    legend: { position: 'none' },
                    backgroundColor: 'transparent',
                    fontName: 'inherit',
                    bar: { groupWidth: '65%' },
                    chartArea: { left: 36, right: 12, top: 20, bottom: 36, width: '100%', height: '100%' },
                    annotations: {
                        alwaysOutside: true,
                        textStyle: { fontSize: 12, bold: true, color: '#444', auraColor: 'none' },
                        stem: { length: 4, color: 'transparent' },
                    },
                    vAxis: {
                        ticks: ticks,
                        format: '0',
                        gridlines: { color: '#eee' },
                        baselineColor: '#bbb',
                        textStyle: { fontSize: 11, color: '#888' },
                    },
                    hAxis: {
                        textStyle: { fontSize: 12, color: '#555' },
                    },
                    tooltip: { textStyle: { fontSize: 12 } },
                }
            },
        }
    }
</script>

<style>
    .routes_quantity_card {
        border: 1px solid #e6e6e6;
        border-radius: 10px;
        padding: 14px 14px 10px;
        margin-bottom: 20px;
        background: #fff;
    }
    .routes_quantity_header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 4px;
    }
    .routes_quantity_title {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
    }
    .routes_quantity_total {
        min-width: 32px;
        padding: 2px 10px;
        border-radius: 999px;
        background: #f1f3f5;
        color: #555;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
    }
    .routes_quantity_track {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }
    .routes_quantity_track::-webkit-scrollbar {
        display: none;
    }
    .routes_quantity_slide {
        flex: 0 0 100%;
        min-width: 0;
        scroll-snap-align: start;
    }
    /* 1fr | auto | 1fr keeps the counter centred even when one side's button is hidden */
    .routes_quantity_nav {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        padding-top: 10px;
        border-top: 1px solid #f0f0f0;
    }
    .routes_quantity_nav_btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        max-width: 100%;
        min-height: 40px;
        padding: 6px 14px;
        border: 1px solid #d6d9dc;
        border-radius: 999px;
        background: #fff;
        color: #333;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s, color 0.15s, border-color 0.15s;
    }
    .routes_quantity_nav_btn:first-child {
        justify-self: start;
    }
    .routes_quantity_nav_btn:last-child {
        justify-self: end;
    }
    /* hover only on real pointers — on phones :hover sticks after a tap */
    @media (hover: hover) {
        .routes_quantity_nav_btn:hover {
            background: #333;
            border-color: #333;
            color: #fff;
        }
    }
    .routes_quantity_nav_btn {
        -webkit-tap-highlight-color: transparent;
    }
    .routes_quantity_nav_btn .fa {
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .routes_quantity_nav_label {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .routes_quantity_counter {
        flex-shrink: 0;
        color: #888;
        font-size: 0.85rem;
        font-weight: 600;
    }

    @media (max-width: 576px) {
        .routes_quantity_card {
            padding: 12px 10px 10px;
        }
        .routes_quantity_title {
            font-size: 0.95rem;
        }
        .routes_quantity_nav_btn {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
    }
</style>
