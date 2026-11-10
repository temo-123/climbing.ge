<template>
    <!-- Sector view "Tiles": a grid of sector tiles; a tile opens that sector
         (the normal SectorComponent: icons, text, photos, tables) in a big panel
         with ‹ › to step to the neighbouring sectors. -->
    <div class="cg-tiles">
        <button
            v-for="item in items"
            :key="item.entry.sector.id"
            type="button"
            class="cg-tile"
            :class="{ 'is-dim': item.sum.matches === 0 }"
            @click="open(item)"
        >
            <!-- site-img brings its own watermark box (.cg-wm) -->
            <span class="cg-tile__photo">
                <site-img v-if="item.thumb" :src="item.thumb" :alt="item.name" img_class="cg-tile__img" />
                <span v-else class="cg-tile__nophoto" aria-hidden="true"><i class="fa fa-picture-o"></i></span>
                <span class="cg-tile__num">{{ item.n }}</span>
            </span>
            <span class="cg-tile__body">
                <span class="cg-tile__name">{{ item.name }}</span>
                <span class="cg-tile__facts">
                    <span><strong>{{ item.sum.routes }}</strong> {{ $t('guide.sector_view.routes') }}</span>
                    <span v-if="item.sum.mtps"><strong>{{ item.sum.mtps }}</strong> {{ $t('guide.sector_view.mtps') }}</span>
                    <span v-if="item.sum.range" class="cg-tile__range">{{ item.sum.range }}</span>
                </span>
                <span v-if="item.sum.bands.length" class="cg-grade-bar" aria-hidden="true">
                    <i v-for="band in item.sum.bands" :key="band.key" :class="'cg-gb-' + band.key" :style="{ flexGrow: band.count }"></i>
                </span>
                <span class="cg-tile__foot">
                    <span v-if="item.entry.sector.wolking_time" class="cg-tile__walk">
                        <img src="/images/svg/walking.svg" alt="" loading="lazy" decoding="async" /> ~ {{ item.entry.sector.wolking_time }}
                    </span>
                    <span v-if="item.sum.matches !== null" class="cg-tile__match">
                        <i class="fa fa-filter" aria-hidden="true"></i> {{ item.sum.matches }}
                    </span>
                </span>
            </span>
        </button>

        <StackModal
            v-model="panel_open"
            :title="current ? current.name : ''"
            :size="'xxxl'"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
            @close="panel_open = false"
        >
            <div v-if="current" class="cg-tiles__panel">
                <div class="cg-tiles__nav">
                    <button type="button" class="cg-tiles__step" :disabled="index === 0" @click="step(-1)">
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                        <span v-if="index > 0">{{ items[index - 1].name }}</span>
                    </button>
                    <span class="cg-tiles__count">{{ index + 1 }} / {{ items.length }}</span>
                    <button type="button" class="cg-tiles__step" :disabled="index === items.length - 1" @click="step(1)">
                        <span v-if="index < items.length - 1">{{ items[index + 1].name }}</span>
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
                <sector :key="current.entry.sector.id" :sector="current.entry" />
            </div>
        </StackModal>
    </div>
</template>

<script>
import sector from "../items/SectorComponent.vue";
import { sector_list, sector_name, sector_summary, sector_thumb } from '../../../../../services/guide/sectorSummary.js'

export default {
    name: 'SectorTilesComponent',
    components: { sector },
    props: ['climbing_area'],
    inject: { route_tools: { default: null } },
    data() {
        return { panel_open: false, index: 0 };
    },
    computed: {
        items() {
            return sector_list(this.climbing_area).map(x => ({
                ...x,
                name: sector_name(x.entry, x.n, this.$t),
                sum: sector_summary(x.entry, this.route_tools),
                thumb: sector_thumb(x.entry, x.group),
            }));
        },
        current() { return this.panel_open ? this.items[this.index] : null; },
    },
    mounted() {
        this.onOpenRequest = (e) => {
            const i = this.items.findIndex(x => String(x.entry.sector.id) === String(e.detail && e.detail.id));
            if (i !== -1) { this.index = i; this.panel_open = true; }
        };
        window.addEventListener('cg-sector-open', this.onOpenRequest);
    },
    beforeUnmount() {
        window.removeEventListener('cg-sector-open', this.onOpenRequest);
    },
    methods: {
        open(item) {
            this.index = this.items.indexOf(item);
            this.panel_open = true;
        },
        step(d) {
            const i = this.index + d;
            if (i >= 0 && i < this.items.length) this.index = i;
        },
    },
};
</script>

<style scoped>
.cg-tiles {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.cg-tile {
    display: flex;
    flex-direction: column;
    padding: 0;
    overflow: hidden;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r-lg);
    background: var(--cg-surface, #fff);
    box-shadow: var(--cg-shadow-sm);
    text-align: left;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
}
.cg-tile:hover { transform: translateY(-2px); border-color: var(--cg-accent); box-shadow: var(--cg-shadow); }
.cg-tile:focus-visible { outline: 2px solid var(--cg-accent); outline-offset: 2px; }
.cg-tile.is-dim { opacity: .45; }

.cg-tile__photo {
    position: relative;
    display: block;
    aspect-ratio: 4 / 3;
    background: var(--cg-chalk);
}
.cg-tile__photo :deep(#image) { height: 100%; }
.cg-tile__photo :deep(img) {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0;
}
.cg-tile__nophoto {
    display: grid;
    place-items: center;
    height: 100%;
    font-size: 2rem;
    color: var(--cg-muted);
}
.cg-tile__num {
    position: absolute;
    top: .5rem;
    left: .5rem;
    min-width: 1.8rem;
    padding: .1rem .45rem;
    border-radius: 999px;
    background: rgba(15, 20, 25, .7);
    color: #fff;
    font-size: .78rem;
    font-weight: 800;
    text-align: center;
}
.cg-tile__body {
    display: flex;
    flex-direction: column;
    gap: .45rem;
    padding: .75rem .85rem .85rem;
}
.cg-tile__name {
    font-family: var(--cg-font-display);
    font-size: 1.2rem;
    line-height: 1.15;
    color: var(--cg-ink);
}
.cg-tile__facts {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .35rem .7rem;
    font-size: .85rem;
    color: var(--cg-stone);
}
.cg-tile__facts strong { color: var(--cg-ink); }
.cg-tile__range {
    padding: .05rem .5rem;
    border-radius: 999px;
    background: var(--cg-chalk);
    font-weight: 800;
    color: var(--cg-ink-2);
}
.cg-tile__foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 1.2rem;
    font-size: .8rem;
    font-weight: 700;
    color: var(--cg-stone);
}
.cg-tile__walk { display: inline-flex; align-items: center; gap: .25rem; }
.cg-tile__walk img { width: 16px; height: 16px; }
.cg-tile__match { color: var(--cg-accent-dark); }

.cg-tiles__nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
    margin-bottom: .5rem;
}
.cg-tiles__step {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    max-width: 40%;
    padding: .35rem .8rem;
    border: 1px solid var(--cg-line);
    border-radius: 999px;
    background: var(--cg-surface, #fff);
    font-size: .85rem;
    font-weight: 700;
    color: var(--cg-accent-dark);
    cursor: pointer;
}
.cg-tiles__step span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cg-tiles__step:disabled { visibility: hidden; }
.cg-tiles__step:hover { border-color: var(--cg-accent); }
.cg-tiles__count { font-size: .82rem; font-weight: 800; color: var(--cg-muted); }

@media (max-width: 575.98px) {
    .cg-tiles { grid-template-columns: repeat(2, 1fr); gap: .6rem; }
    .cg-tile__name { font-size: 1.02rem; }
    .cg-tile__body { padding: .55rem .6rem .65rem; }
}
</style>
