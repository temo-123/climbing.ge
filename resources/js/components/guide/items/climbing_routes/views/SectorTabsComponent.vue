<template>
    <!-- Sector view "Tabs": a row of sector tabs; only the chosen sector is
         shown (the normal SectorComponent, with its local-approach photo when it
         has one), with ‹ › to step through the sectors. -->
    <div class="cg-stabs">
        <div class="cg-stabs__strip" role="tablist" ref="strip">
            <button
                v-for="(item, i) in items"
                :key="item.entry.sector.id"
                type="button"
                role="tab"
                class="cg-stabs__tab"
                :class="{ 'is-active': i === index, 'is-dim': item.sum.matches === 0 }"
                :aria-selected="i === index ? 'true' : 'false'"
                @click="select(i)"
            >
                <span class="cg-stabs__name">{{ item.name }}</span>
                <span class="cg-stabs__count">{{ item.sum.matches !== null ? item.sum.matches + '/' : '' }}{{ item.sum.routes + item.sum.mtps }}</span>
                <span v-if="item.sum.bands.length" class="cg-grade-bar cg-stabs__bar" aria-hidden="true">
                    <i v-for="band in item.sum.bands" :key="band.key" :class="'cg-gb-' + band.key" :style="{ flexGrow: band.count }"></i>
                </span>
            </button>
        </div>

        <div v-if="current" class="cg-stabs__panel" role="tabpanel">
            <!-- the sector's local-approach photo, then the sector itself -->
            <sector_and_local_area_images
                v-if="current.group && current.group.local_images && current.group.local_images.length"
                :key="'g' + current.entry.sector.id"
                :sectors_and_images="{ local_images: current.group.local_images, sectors: [current.entry] }"
            />
            <sector v-else :key="current.entry.sector.id" :sector="current.entry" />

            <div class="cg-stabs__nav">
                <button type="button" class="cg-stabs__step" :disabled="index === 0" @click="select(index - 1, true)">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    <span v-if="index > 0">{{ items[index - 1].name }}</span>
                </button>
                <span class="cg-stabs__pos">{{ index + 1 }} / {{ items.length }}</span>
                <button type="button" class="cg-stabs__step" :disabled="index === items.length - 1" @click="select(index + 1, true)">
                    <span v-if="index < items.length - 1">{{ items[index + 1].name }}</span>
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import sector from "../items/SectorComponent.vue";
import sector_and_local_area_images from "../items/SectorsAndAreaLocalImageComponrnt.vue";
import { sector_list, sector_name, sector_summary } from '../../../../../services/guide/sectorSummary.js'

export default {
    name: 'SectorTabsComponent',
    components: { sector, sector_and_local_area_images },
    props: ['climbing_area'],
    inject: { route_tools: { default: null } },
    data() {
        // a ?sector= link starts on that sector
        return { index: 0, start_id: new URLSearchParams(window.location.search).get('sector') };
    },
    computed: {
        items() {
            return sector_list(this.climbing_area).map(x => ({
                ...x,
                name: sector_name(x.entry, x.n, this.$t),
                sum: sector_summary(x.entry, this.route_tools),
            }));
        },
        current() { return this.items[this.index] || this.items[0] || null; },
    },
    watch: {
        items: {
            immediate: true,
            handler(list) {
                if (this.index >= list.length) this.index = 0;
                if (this.start_id) {
                    const i = list.findIndex(x => String(x.entry.sector.id) === String(this.start_id));
                    if (i !== -1) { this.index = i; this.start_id = null; }
                }
            },
        },
    },
    mounted() {
        this.onOpenRequest = (e) => {
            const i = this.items.findIndex(x => String(x.entry.sector.id) === String(e.detail && e.detail.id));
            if (i !== -1) this.select(i);
        };
        window.addEventListener('cg-sector-open', this.onOpenRequest);
    },
    beforeUnmount() {
        window.removeEventListener('cg-sector-open', this.onOpenRequest);
    },
    methods: {
        // from the bottom arrows: also bring the tab strip back into view
        select(i, scroll_up) {
            if (i < 0 || i >= this.items.length) return;
            this.index = i;
            this.$nextTick(() => {
                const strip = this.$refs.strip;
                const tab = strip && strip.children[i];
                if (tab && strip) strip.scrollTo({ left: tab.offsetLeft - strip.clientWidth / 2 + tab.clientWidth / 2, behavior: 'smooth' });
                if (scroll_up && strip) {
                    const y = strip.getBoundingClientRect().top + window.pageYOffset - 90;
                    if (strip.getBoundingClientRect().top < 0) window.scrollTo({ top: y, behavior: 'smooth' });
                }
            });
        },
    },
};
</script>

<style scoped>
.cg-stabs { margin-bottom: 1.5rem; }
.cg-stabs__strip {
    display: flex;
    gap: .5rem;
    padding: .25rem .1rem .6rem;
    overflow-x: auto;
    scroll-snap-type: x proximity;
    scrollbar-width: thin;
}
.cg-stabs__tab {
    flex: 0 0 auto;
    display: grid;
    grid-template-columns: auto auto;
    align-items: center;
    gap: .3rem .6rem;
    min-width: 120px;
    padding: .55rem .8rem .6rem;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r);
    background: var(--cg-surface, #fff);
    text-align: left;
    scroll-snap-align: start;
    cursor: pointer;
    transition: border-color .15s ease, box-shadow .15s ease;
}
.cg-stabs__tab:hover { border-color: var(--cg-accent); }
.cg-stabs__tab:focus-visible { outline: 2px solid var(--cg-accent); outline-offset: 2px; }
.cg-stabs__tab.is-active {
    border-color: var(--cg-accent);
    background: var(--cg-accent-soft);
    box-shadow: inset 0 -3px 0 var(--cg-accent);
}
.cg-stabs__tab.is-dim { opacity: .5; }
.cg-stabs__name {
    font-weight: 800;
    color: var(--cg-ink);
    white-space: nowrap;
}
.cg-stabs__tab.is-active .cg-stabs__name { color: var(--cg-accent-dark); }
.cg-stabs__count {
    justify-self: end;
    padding: 0 .45rem;
    border-radius: 999px;
    background: var(--cg-chalk);
    font-size: .75rem;
    font-weight: 800;
    color: var(--cg-stone);
}
.cg-stabs__bar { grid-column: 1 / -1; height: 5px; }

.cg-stabs__panel { margin-top: .25rem; }
.cg-stabs__nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
    margin-top: 1rem;
}
.cg-stabs__step {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    max-width: 42%;
    padding: .45rem .9rem;
    border: 1px solid var(--cg-line);
    border-radius: 999px;
    background: var(--cg-surface, #fff);
    font-size: .88rem;
    font-weight: 700;
    color: var(--cg-accent-dark);
    cursor: pointer;
}
.cg-stabs__step span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.cg-stabs__step:disabled { visibility: hidden; }
.cg-stabs__step:hover { border-color: var(--cg-accent); }
.cg-stabs__pos { font-size: .85rem; font-weight: 800; color: var(--cg-muted); }
</style>
