<template>
    <div class="col-sm-3 col-md-3 offset-1 display-biger-then-768px right_fixed_menu" :class="{ 'cg-has-side-scene': sideScene, 'cg-has-mountain-top': sideScene === 'summit' }">

        <h3 class="navbar_title display-biger-then-768px">{{ $t('guide.article_right_nabar.menu_title') }}</h3>

        <!-- plain text / cards view of the article sections -->
        <articleViewSwitch class="cg-menu-view-switch display-biger-then-768px" />

        <!-- Quick at-a-glance counts, computed from the same sectors payload
             already fetched for the submenu below — no extra request. Was
             requested to fill out an otherwise sparse-looking menu with
             something genuinely useful rather than just decorative spacing. -->
        <div v-if="hasSectors && quickStats.sectorCount > 0" class="quick-stats display-biger-then-768px">
            <div class="quick-stats-item">
                <span class="quick-stats-num">{{ quickStats.sectorCount }}</span>
                <span class="quick-stats-label">{{ $t('guide.article_right_nabar.quick_stats_sectors') }}</span>
            </div>
            <div class="quick-stats-item">
                <span class="quick-stats-num">{{ quickStats.routeCount }}</span>
                <span class="quick-stats-label">{{ $t('guide.article_right_nabar.quick_stats_routes') }}</span>
            </div>
            <div class="quick-stats-item" v-if="quickStats.mtpCount > 0">
                <span class="quick-stats-num">{{ quickStats.mtpCount }}</span>
                <span class="quick-stats-label">{{ $t('guide.article_right_nabar.quick_stats_mtps') }}</span>
            </div>
        </div>

        <!-- the list scrolls by itself when the menu is taller than the window (fit_menu) -->
        <nav ref="nav" class="fading-side-menu display-biger-then-768px ">
            <ul class="list-unstyled">
                <li v-for="item in items" :key="item.key" :class="{ 'is-active': is_active(item) }">

                    <!-- sectors: outdoor areas and ice climbing pages -->
                    <template v-if="item.kind === 'sectors'">
                        <a @click.prevent="scrollToSection(item.id)">
                            <span class="text-primary cursor_pointer">{{ $t('guide.article_right_nabar.sectors') }}</span>
                        </a>

                        <ul v-if="sectors && sectors.length > 0" class="submenu">
                            <template v-for="(sector, sectorIndex) in sectors" :key="sectorIndex">
                                <!-- Handle direct sector objects (no local images) -->
                                <li v-if="sector && sector.sector && sector.sector.id" :class="{ 'is-active': activeId === 'sector-' + sector.sector.id }">
                                    <a @click.prevent="scrollToSection('sector-' + sector.sector.id)">
                                        <span class="text-primary cursor_pointer">{{ sector_label(sector) }}</span>
                                    </a>
                                </li>

                                <!-- Sectors sharing one local-approach image — grouped under that
                                     image's own title, same grouping the "Local approach photo"
                                     page itself uses, instead of flattening them in among every
                                     other sector as if they were unrelated. Reported September
                                     2026: "need make grouped sectors like a sector local image
                                     grouped ... show name of sector local images title." -->
                                <!-- a photo with ONE sector: one row (the photo title, or the
                                     sector's name), not a group header over a single child -->
                                <li v-else-if="sector && sector.sectors && sector.sectors.length === 1 && sector.sectors[0].sector" :class="{ 'is-active': activeId === 'sector-' + sector.sectors[0].sector.id || (groupImageId(sector) && activeId === 'local-image-' + groupImageId(sector)) }">
                                    <a @click.prevent="scrollToSection(groupImageId(sector) ? 'local-image-' + groupImageId(sector) : 'sector-' + sector.sectors[0].sector.id)">
                                        <span class="text-primary cursor_pointer">{{ single_group_label(sector) }}</span>
                                    </a>
                                </li>

                                <li v-else-if="sector && sector.sectors && sector.sectors.length > 1" class="submenu-group">
                                    <a v-if="groupImageId(sector)" @click.prevent="scrollToSection('local-image-' + groupImageId(sector))">
                                        <span class="text-primary cursor_pointer submenu-group-title">{{ groupTitle(sector) }}</span>
                                    </a>
                                    <span v-else class="submenu-group-title">{{ groupTitle(sector) }}</span>
                                    <ul class="submenu submenu-nested">
                                        <li v-for="nestedSector in sector.sectors" :key="nestedSector.sector.id" :class="{ 'is-active': activeId === 'sector-' + nestedSector.sector.id }">
                                            <a v-if="nestedSector && nestedSector.sector && nestedSector.sector.id" @click.prevent="scrollToSection('sector-' + nestedSector.sector.id)">
                                                <span class="text-primary cursor_pointer">{{ sector_label(nestedSector) }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </template>
                        </ul>
                        <div v-else-if="sectors && sectors.length === 0 && sectorsLoaded" class="text-muted" style="padding-left: 20px;">
                            <small>No sectors found</small>
                        </div>
                        <div v-else-if="!sectorsLoaded" class="text-muted" style="padding-left: 20px;">
                            <small>Loading sectors...</small>
                        </div>
                    </template>

                    <!-- any other section: a page subtitle, the live camera, the massif card -->
                    <template v-else>
                        <a @click.prevent="scrollToSection(item.id)">
                            <span class="text-primary cursor_pointer">
                                <i v-if="item.kind === 'camera'" class="fa fa-video-camera" aria-hidden="true"></i>
                                <i v-else-if="item.kind === 'massif'" class="fa fa-area-chart" aria-hidden="true"></i>
                                <i v-else-if="item.kind === 'tours'" class="fa fa-compass" aria-hidden="true"></i>
                                <i v-else-if="item.kind === 'services'" class="fa fa-handshake-o" aria-hidden="true"></i>
                                {{ item.label }}
                            </span>
                        </a>

                        <!-- subtitles inside this section (editor headings, massif tabs) -->
                        <ul v-if="item.children && item.children.length" class="submenu">
                            <li
                                v-for="child in item.children"
                                :key="child.key"
                                :class="{ 'is-active': activeId === child.id, 'submenu-deep': child.deep, 'is-open': child.open }"
                            >
                                <a @click.prevent="open_child(child)" :aria-current="child.open ? 'true' : null">
                                    <span class="text-primary cursor_pointer">{{ child.label }}</span>
                                    <i v-if="child.open" class="fa fa-eye submenu-open-mark" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        </nav>

        <!-- <div class="row local_bisnes" v-if="this.$globalSiteData.data.ad">
            <div class="col-sm-12 col-md-10">
                <span v-html="this.$globalSiteData.data.ad"></span>
            </div>
        </div> -->

        <!-- <h3 class="navbar_title display-biger-then-768px" v-if="local_businesses.length != 0">{{ $t('guide.article_right_nabar.recomended_services') }}</h3>

        <div class="row local_bisnes display-biger-then-768px" v-if="local_businesses.length != 0">
            <div class="col-sm-10 col-md-10" v-for="bisnes in local_businesses" :kay="bisnes.global_data.id">
                <div class="thumbnail">
                    <router-link v-if="bisnes.image.length != 0" style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img loading="lazy" decoding="async" :src="'../../../images/suport_local_bisnes_img/' + bisnes.image" :alt="bisnes.local_data.title">
                    </router-link>
                    <router-link v-else style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img loading="lazy" decoding="async" :src="'/../public/images/site_img/image.png'" :alt="bisnes.local_data.title">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                            <h3>{{ bisnes.local_data.title }}</h3>
                        </router-link>
                    </div>
                    <div class="caption">
                        <span v-html="bisnes.local_data.short_description"></span>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- optional extra block under the menu -->
        <slot name="below" />

        <!-- climbing scene on the side of this card (the card edge is the "wall") -->
        <img
            v-if="sideScene === 'bolter'"
            class="cg-card-bolter"
            src="/images/scenes/bolter-solo.svg"
            alt="" aria-hidden="true" loading="lazy" decoding="async"
        />
        <menu-side-scene
            v-else-if="sideScene && cardSize.h && motion.enabled"
            :variant="sideScene"
            :width="cardSize.w"
            :height="cardSize.h"
        />
    </div>
</template>

<script>
    import { defineAsyncComponent } from 'vue'
    import { motion } from '../../../../services/motion.js'
    import articleViewSwitch from '../ArticleViewSwitchComponent.vue'
    import { open_sector } from '../../../../services/guide/sectorView.js'
    // loaded only on pages that show a side scene
    const MenuSideScene = defineAsyncComponent(() => import('./MenuSideSceneComponent.vue'))

    export default {
        name: "article-right-navigation-menu",
        props: ['article_id'],
        components: { MenuSideScene, articleViewSwitch },
        data(){
            return {
                sectors: [],
                // set once the sector request has finished (ok or failed): the
                // outdoor side scene depends on it, so it waits for the data
                sectorsLoaded: false,
                hasCamera: false,
                cardSize: { w: 0, h: 0 },
                // navbar animation switch: off = no side scene at all
                motion,
                wideScreen: window.innerWidth >= 1200,
                // sections found on the page itself (all pages but outdoor)
                scanned: [],
                // id of the section being read now (highlighted in the menu)
                activeId: null,
                // outdoor (fixed menu): are the optional blocks on the page?
                hasTours: false,
                hasServices: false,
            }
        },
        computed: {

            // pages that list climbing sectors in the menu (both use OutdoorComponent)
            hasSectors() { return ['outdoor', 'ice'].includes(this.$route.name) },
            // Outdoor pages keep the fixed menu (their sector list is already
            // long). Every other page builds the menu from its own subtitles,
            // so mountaineering / ice / indoor show each section they really have.
            autoSections() { return this.$route.name !== 'outdoor' },
            items() {
                if (this.autoSections) return this.scanned;
                const t = (k) => this.$t('guide.article_right_nabar.' + k);
                const list = [
                    { key: 'description', kind: 'heading', id: 'description', label: this.$t('common.description') },
                ];
                if (this.hasServices) list.push({ key: 'local-services', kind: 'services', id: 'local-services', label: t('recomended_services') });
                list.push({ key: 'sectors', kind: 'sectors', id: 'routes' });
                if (this.hasCamera) list.push({ key: 'live-camera', kind: 'camera', id: 'live-camera', label: t('live_camera') });
                list.push(
                    { key: 'gallery', kind: 'heading', id: 'gallery', label: t('gallery') },
                    { key: 'comments', kind: 'heading', id: 'comments', label: t('comments') },
                );
                if (this.hasTours) list.push({ key: 'tours', kind: 'tours', id: 'tours', label: this.$t('guide.tours.article_title') });
                list.push({ key: 'other', kind: 'heading', id: 'other', label: t('similar_articles') });
                return list;
            },
            // Flattens both entry shapes get_sector_and_routes returns
            // ({sector: {...}} standalone, or {local_images, sectors: [...]}
            // grouped) into one list of real sector objects, so counting
            // logic doesn't have to special-case the grouping twice.
            flatSectors() {
                const flat = [];
                (this.sectors || []).forEach(entry => {
                    if (entry && entry.sector) flat.push(entry);
                    else if (entry && entry.sectors) entry.sectors.forEach(s => flat.push(s));
                });
                return flat;
            },
            quickStats() {
                let routeCount = 0, mtpCount = 0;
                this.flatSectors.forEach(s => {
                    routeCount += (s.sport_routes ? s.sport_routes.length : 0)
                                + (s.boulder_routes ? s.boulder_routes.length : 0);
                    mtpCount   += (s.mtps ? s.mtps.length : 0);
                });
                return { sectorCount: this.flatSectors.length, routeCount, mtpCount };
            },
            // which climbing scene hangs on the side of this card (wide screens
            // only — it needs the gap between the article and the card):
            // outdoor with several sectors → lead climbing pair, one sector →
            // bolter; ice → lead ice climbing; indoor → gym lead; tech tips →
            // practising a lead fall; mountaineering → rope team on the summit
            sideScene() {
                if (!this.wideScreen) return null;
                switch (this.$route.name) {
                    case 'special_article': return 'bolter';
                    // wait for the sectors (they decide the menu height and the scene)
                    case 'outdoor':
                        if (!this.sectorsLoaded) return null;
                        return this.quickStats.sectorCount > 1 ? 'lead' : 'bolter';
                    case 'ice': return 'ice';
                    case 'tech_tip': return 'safety';
                    case 'other': return 'slackline';
                    case 'indoor': return 'indoor';
                    case 'mountaineering': return 'summit';
                    default: return null;
                }
            },
        },
        mounted() {
            this.get_sectors_for_article();
            // the side scene follows the card's size (sectors load, window resizes)
            if (window.ResizeObserver) {
                this.sizeObserver = new ResizeObserver(() => {
                    const r = this.$el.getBoundingClientRect();
                    this.cardSize = { w: Math.round(r.width), h: Math.round(r.height) };
                    this.fit_menu();
                });
                this.sizeObserver.observe(this.$el);
            }
            this.onResize = () => { this.wideScreen = window.innerWidth >= 1200; this.fit_menu(); };
            this.fit_menu();
            window.addEventListener('resize', this.onResize, { passive: true });

            // page sections: scan now and again whenever the article changes
            // (route images, the massif card, gallery and comments load later)
            this.targets = {};
            this.headingSeq = 0;
            this.scan_sections();
            const root = this.scan_root();
            if (root && window.MutationObserver) {
                this.pageObserver = new MutationObserver((records) => {
                    // the menu re-rendering itself is not a page change
                    if (records.every(r => this.$el.contains(r.target))) return;
                    clearTimeout(this.scanTimer);
                    this.scanTimer = setTimeout(() => this.scan_sections(), 150);
                });
                this.pageObserver.observe(root, { childList: true, subtree: true });
            }
            this.onScroll = () => {
                if (this.scrollFrame) return;
                this.scrollFrame = requestAnimationFrame(() => {
                    this.scrollFrame = null;
                    this.update_active();
                });
            };
            window.addEventListener('scroll', this.onScroll, { passive: true });
        },
        beforeUnmount() {
            if (this.sizeObserver) this.sizeObserver.disconnect();
            if (this.pageObserver) this.pageObserver.disconnect();
            clearTimeout(this.scanTimer);
            clearTimeout(this.reaimTimer);
            if (this.scrollFrame) cancelAnimationFrame(this.scrollFrame);
            window.removeEventListener('resize', this.onResize);
            window.removeEventListener('scroll', this.onScroll);
        },
        watch: {
            '$route'() {
                this.get_sectors_for_article();
                this.$nextTick(() => this.scan_sections());
            },
            '$i18n.locale'() {
                this.$nextTick(() => this.scan_sections());
            },
            'article_id'(newVal, oldVal) {
                if (newVal !== oldVal) {
                    this.get_sectors_for_article();
                }
            }
        },
        methods: {
            // the page around this menu (header rows, article, comments, similar)
            scan_root() {
                const row = this.$el && this.$el.parentElement;
                return (row && row.parentElement) || row || null;
            },
            text_of(el) {
                const text = (el.textContent || '').replace(/\s+/g, ' ').trim();
                return text.length > 60 ? text.slice(0, 57).replace(/\s+\S*$/, '') + '…' : text;
            },
            // Walks the page in reading order and turns it into menu items:
            //   - section titles the components render (h2 with an id)
            //   - headings typed in the editor texts → sub-items of the section above
            //   - the mountain massif card → item with one sub-item per tab
            //   - the live camera, and (ice) the sector list
            scan_sections() {
                const root = this.scan_root();
                if (!this.autoSections) {
                    this.scanned = [];
                    this.hasTours = !!(root && root.querySelector('#tours'));
                    this.hasServices = !!(root && root.querySelector('#local-services'));
                    this.update_active();
                    return;
                }
                if (!root) return;
                const t = (k) => this.$t('guide.article_right_nabar.' + k);
                const sel = [
                    '#description', 'h2[id]', '#live-camera', '#sectors', '.cg-massif', '#tours',
                    '.article_text_block h1', '.article_text_block h2',
                    '.article_text_block h3', '.article_text_block h4',
                ].join(',');
                const items = [];
                const targets = {};
                let current = null;
                let sectorsAdded = false;
                const push = (item, el) => {
                    targets[item.id] = el;
                    items.push(item);
                    current = item;
                };

                root.querySelectorAll(sel).forEach(el => {
                    if (this.$el.contains(el)) return;
                    // each sector has its own h2 — the sector submenu lists them
                    if (el.closest('#sectors') && el.id !== 'sectors') return;
                    // the massif card's own title is the massif item
                    if (el.closest('.cg-massif') && !el.classList.contains('cg-massif')) return;

                    const editor = el.closest('.article_text_block');
                    if (editor && el !== editor && /^H[1-4]$/.test(el.tagName)) {
                        const label = this.text_of(el);
                        if (!label) return;
                        if (!el.id) el.id = 'cg-subtitle-' + (++this.headingSeq);
                        targets[el.id] = el;
                        const child = { key: el.id, id: el.id, label, deep: /^H[34]$/.test(el.tagName) };
                        // headings before any section title start their own items
                        if (current && current.kind !== 'sectors') current.children.push(child);
                        else push({ key: el.id, kind: 'heading', id: el.id, label, children: [] }, el);
                        return;
                    }

                    if (el.id === 'description') {
                        return push({ key: 'description', kind: 'heading', id: 'description', label: this.$t('common.description'), children: [] }, el);
                    }
                    if (el.classList.contains('cg-massif')) {
                        if (!el.id) el.id = 'massif';
                        const title = el.querySelector('.cg-massif__title');
                        const children = [];
                        el.querySelectorAll('[role="tab"]').forEach(tab => {
                            if (!tab.id) return;
                            targets[tab.id] = el;
                            children.push({ key: tab.id, id: tab.id, label: this.text_of(tab), tab: true, open: tab.classList.contains('is-active') });
                        });
                        return push({ key: 'massif', kind: 'massif', id: el.id, label: title ? this.text_of(title) : '', children }, el);
                    }
                    // "Climb it with a local guide" tours slider (title is its h2)
                    if (el.id === 'tours') {
                        const title = el.querySelector('h2');
                        return push({ key: 'tours', kind: 'tours', id: 'tours', label: title ? this.text_of(title) : this.$t('guide.tours.article_title'), children: [] }, el);
                    }
                    if (el.closest('#tours')) return;
                    // recommended local services (business cards)
                    if (el.id === 'local-services') {
                        return push({ key: 'local-services', kind: 'services', id: 'local-services', label: this.text_of(el), children: [] }, el);
                    }
                    if (el.id === 'live-camera') {
                        return push({ key: 'live-camera', kind: 'camera', id: 'live-camera', label: t('live_camera'), children: [] }, el);
                    }
                    if (this.hasSectors && (el.id === 'routes' || el.id === 'sectors')) {
                        if (sectorsAdded) { targets[el.id] = el; return; }
                        sectorsAdded = true;
                        return push({ key: 'sectors', kind: 'sectors', id: el.id, children: [] }, el);
                    }
                    if (el.id === 'sectors') return;

                    const label = this.text_of(el);
                    if (!label || targets[el.id]) return;
                    push({ key: el.id, kind: 'heading', id: el.id, label, children: [] }, el);
                });

                this.targets = targets;
                // only re-render when something really changed
                if (JSON.stringify(items) !== JSON.stringify(this.scanned)) this.scanned = items;
                this.update_active();
            },
            // the section whose top has passed under the navbar is the one being read
            update_active() {
                const ids = [];
                this.items.forEach(item => {
                    ids.push(item.id);
                    (item.children || []).forEach(c => { if (!c.tab) ids.push(c.id); });
                    if (item.kind === 'sectors') this.flatSectors.forEach(s => s.sector && ids.push('sector-' + s.sector.id));
                });
                let active = null;
                let best = -Infinity;
                ids.forEach(id => {
                    const el = (this.targets && this.targets[id]) || document.getElementById(id);
                    if (!el || !el.isConnected) return;
                    const top = el.getBoundingClientRect().top - 100;
                    if (top <= 0 && top > best) { best = top; active = id; }
                });
                if (active !== this.activeId) {
                    this.activeId = active;
                    this.$nextTick(() => this.reveal_active());
                }
            },
            // Keep the whole card inside the window: the list (nav) gets the
            // height left under the sticky top, the card head (title, view
            // switch, stats) and the card padding. Only the list scrolls, so
            // the card itself never clips its side scene.
            fit_menu() {
                const nav = this.$refs.nav;
                if (!nav || !this.$el || !this.$el.isConnected) return;
                const card = getComputedStyle(this.$el);
                const top = parseFloat(card.top) || 0;
                const head = nav.offsetTop;  // card is sticky → it is nav's offsetParent
                const bottom = (parseFloat(card.paddingBottom) || 0) + 16;
                let below = 0;
                for (let el = nav.nextElementSibling; el; el = el.nextElementSibling) {
                    if (el.offsetParent && !el.matches('.cg-card-bolter, .cg-side-scene')) below += el.offsetHeight;
                }
                const max = Math.max(160, Math.floor(window.innerHeight - top - head - bottom - below));
                const value = max + 'px';
                if (nav.style.maxHeight !== value) nav.style.maxHeight = value;
            },
            // scroll the list (not the page) so the section being read stays visible
            reveal_active() {
                const nav = this.$refs.nav;
                if (!nav || nav.scrollHeight <= nav.clientHeight) return;
                const rows = nav.querySelectorAll('li.is-active > a');
                const row = rows[rows.length - 1];
                if (!row) return;
                const n = nav.getBoundingClientRect();
                const r = row.getBoundingClientRect();
                if (r.top < n.top + 8) nav.scrollTop -= (n.top + 8 - r.top);
                else if (r.bottom > n.bottom - 8) nav.scrollTop += (r.bottom - n.bottom + 8);
            },
            // highlight a top item while it, or anything inside it, is being read
            is_active(item) {
                if (!this.activeId) return false;
                if (item.id === this.activeId) return true;
                if ((item.children || []).some(c => c.id === this.activeId)) return true;
                return item.kind === 'sectors' && this.activeId.indexOf('sector-') === 0;
            },
            // massif tabs: switch to that tab, then scroll to the card
            open_child(child) {
                if (child.tab) {
                    const tab = document.getElementById(child.id);
                    if (tab && !tab.classList.contains('is-active')) tab.click();
                    const card = tab && tab.closest('.cg-massif');
                    if (card) this.scrollToSection(card.id);
                    return;
                }
                this.scrollToSection(child.id);
            },
            // The backend groups sectors sharing one local-approach photo as
            // { local_images: [image], sectors: [...] } — the image's own
            // admin-set title names the group; fall back to a generic label
            // only for the rare case it was never filled in.
            groupTitle(group) {
                const image = group.local_images && group.local_images[0];
                const title = this.clean_name(image && image.title);
                return title || this.$t('guide.article_right_nabar.local_image_group_fallback');
            },
            // Admins sometimes save placeholder names ("-", "Sector -"): drop the
            // trailing dashes / colons, and treat a name with no letter or digit
            // as missing.
            clean_name(name) {
                const text = String(name || '').trim().replace(/[\s\-–—:|.]+$/u, '').trim();
                return /[\p{L}\p{N}]/u.test(text) ? text : '';
            },
            // sector row label; unnamed sectors become "Sector 3" (their place on the page)
            sector_label(entry) {
                const name = this.clean_name(entry && entry.sector && entry.sector.name);
                if (name) return name;
                const n = this.flatSectors.indexOf(entry) + 1;
                return this.$t('guide.article_right_nabar.unnamed_sector', { n: n > 0 ? n : '' }).trim();
            },
            // photo with a single sector: the photo's title, else the sector's own label
            single_group_label(group) {
                const image = group.local_images && group.local_images[0];
                return this.clean_name(image && image.title) || this.sector_label(group.sectors[0]);
            },
            // SectorAndRoutesTabComponent.vue wraps each group in
            // id="local-image-{id}" so this title can jump straight to the
            // photo itself, the same way each sector name jumps to its own
            // section.
            groupImageId(group) {
                const image = group.local_images && group.local_images[0];
                return image ? image.id : null;
            },
            scrollToSection(sectionId) {
                if (/^sector-/.test(sectionId)) {
                    // sector views (services/guide/sectorView.js): Tabs selects the
                    // sector, Tiles opens its panel, Table filters to it. Its own
                    // heading may appear only after that (Tabs) or not exist at
                    // all (Tiles / Table) → then go to the sectors block.
                    open_sector(sectionId.slice(7));
                    this.$nextTick(() => requestAnimationFrame(() => this.scroll_to(sectionId)));
                    return;
                }
                this.scroll_to(sectionId);
            },
            scroll_to(sectionId) {
                const target = document.getElementById(sectionId)
                    || (/^(sector|local-image)-/.test(sectionId) ? document.getElementById('sectors') : null);
                if (!target) return;
                const offset = 80;
                const y_of = () => target.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top: y_of(), behavior: 'smooth' });
                // Lazy photos / charts above the target load while the page
                // scrolls and push it down, so the jump used to stop short.
                // Re-aim once the scroll has settled (twice, for slow images).
                clearTimeout(this.reaimTimer);
                let tries = 0;
                const reaim = () => {
                    const top = target.getBoundingClientRect().top;
                    if (target.isConnected && Math.abs(top - offset) > 12) window.scrollTo({ top: y_of(), behavior: 'auto' });
                    if (++tries < 2) this.reaimTimer = setTimeout(reaim, 700);
                };
                this.reaimTimer = setTimeout(reaim, 900);
            },
            // does this article have a live camera? (shown as a menu item)
            check_camera() {
                this.hasCamera = false;
                if (!this.article_id || !this.hasSectors) return;
                axios.get('/get_live_camera/get_camera_for_article/' + this.article_id)
                    .then(r => { this.hasCamera = !!(r.data && r.data.id); })
                    .catch(() => {});
            },
            get_sectors_for_article() {
                this.check_camera();
                this.sectorsLoaded = false;
                if (!this.article_id) { this.sectors = []; return; }
                axios.get('/get_sector/get_sector_and_routes/' + this.article_id)
                    .then(response => {
                        this.sectors = Array.isArray(response.data) ? response.data : [];
                    })
                    .catch(() => { this.sectors = []; })
                    // the menu has its final height now → the side scene can start
                    .finally(() => { this.$nextTick(() => { this.sectorsLoaded = true; }); });
            },
        },
    };
</script> 

<style scoped>
    /* Article "on this page" card — sticky beside the article */
    .right_fixed_menu {
        position: sticky;
        top: calc(var(--cg-nav-h) + 16px);
        align-self: flex-start;
        max-height: calc(100vh - var(--cg-nav-h) - 32px);
        overflow-y: auto;
        padding: 1.25rem 1.25rem 1rem;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
    }
    .caption h3 {
        margin: 0;
    }
    .navbar_title {
        margin: 0 0 1rem;
        font-size: 1.15rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--cg-muted);
    }
    .cg-menu-view-switch {
        margin-bottom: 1rem;
    }
    /* narrow card (768–1199px): no room for the "View" label next to both buttons */
    @media (max-width: 1199.98px) {
        .cg-menu-view-switch :deep(.cg-view-switch__label) { display: none; }
        .cg-menu-view-switch :deep(.cg-view-switch__btn) { flex: 1; justify-content: center; }
    }
    .quick-stats {
        display: flex;
        gap: 8px;
        margin-bottom: 1rem;
    }
    .quick-stats-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: .6rem .25rem;
        border-radius: var(--cg-r);
        background: var(--cg-accent-soft);
        text-align: center;
    }
    .quick-stats-num {
        font-family: var(--cg-font-display);
        font-size: 1.7rem;
        font-weight: 700;
        line-height: 1;
        color: var(--cg-accent-dark);
    }
    .quick-stats-label {
        margin-top: .2rem;
        font-size: .68rem;
        font-weight: 800;
        color: var(--cg-stone);
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .local_bisnes {
        margin-top: 1.5rem;
    }
    .fading-side-menu {
        margin-bottom: .5rem;
        overflow-y: auto;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        /* room for the active row's highlight next to the scrollbar */
        padding-right: .15rem;
    }
    .fading-side-menu > ul > li > a {
        display: block;
        padding: .45rem .6rem;
        border-radius: .55rem;
        font-weight: 700;
    }
    .fading-side-menu > ul > li > a:hover {
        background: var(--cg-chalk);
    }
    .fading-side-menu .text-primary {
        color: var(--cg-ink-2) !important;
    }
    .fading-side-menu a:hover .text-primary {
        color: var(--cg-accent) !important;
    }
    .submenu {
        list-style: none;
        margin: .1rem 0 .4rem .9rem;
        padding-left: .75rem;
        border-left: 2px dashed var(--cg-line);
    }
    .submenu li {
        margin: 0;
    }
    .submenu li a {
        display: block;
        padding: .25rem .5rem;
        font-size: .9rem;
    }
    .submenu li a .text-primary {
        font-weight: 600;
        color: var(--cg-stone) !important;
    }
    .submenu-group-title {
        display: block;
        margin-top: .5rem;
        font-weight: 800;
        font-size: .8rem;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--cg-muted) !important;
    }
    .submenu-nested {
        margin-left: .4rem;
    }
    /* deeper editor headings (h3 / h4) sit one step further in */
    .submenu li.submenu-deep a {
        padding-left: 1.1rem;
        font-size: .84rem;
    }
    /* the section being read now */
    .fading-side-menu > ul > li.is-active > a {
        background: var(--cg-accent-soft);
    }
    .fading-side-menu > ul > li.is-active > a .text-primary,
    .submenu li.is-active > a .text-primary {
        color: var(--cg-accent-dark) !important;
    }
    .submenu li.is-active > a {
        box-shadow: inset 2px 0 0 var(--cg-accent);
        border-radius: 0 .4rem .4rem 0;
    }
    /* massif tab that is open in the card right now */
    .submenu li.is-open > a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .4rem;
        background: var(--cg-accent-soft);
        border-radius: .4rem;
    }
    .submenu li.is-open > a .text-primary {
        color: var(--cg-accent-dark) !important;
        font-weight: 800;
    }
    .fading-side-menu .submenu-open-mark {
        margin: 0;
        font-size: .8rem;
    }
    .fading-side-menu .fa {
        margin-right: .3rem;
        color: var(--cg-accent);
    }
</style>
