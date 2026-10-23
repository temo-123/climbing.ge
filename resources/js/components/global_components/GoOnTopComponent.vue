<template>
    <div class="cg-rappel" :class="{ 'is-visible': showButton }">
        <!-- Rappel: on long pages a climber abseils down the right edge of the
             page as you scroll. Near the bottom they land on the footer
             mountains, untie, and the free rope end flutters in the wind.
             Drawn in screen pixels in one fixed SVG. Not shown on short pages,
             phones, the user/admin panel or the wall calculator (ropeEnabled). -->
        <svg v-if="ropeEnabled" v-show="longPage" ref="svg" class="cg-rappel__svg" aria-hidden="true">
            <!-- anchor: bolt + quickdraw the rope runs through -->
            <g ref="anchor">
                <circle cx="0" cy="-16" r="3.4" fill="#7b858e" />
                <rect x="-2.6" y="-13" width="5.2" height="14" rx="2.4" class="cg-rappel__sling" />
                <ellipse cx="0" cy="6" rx="5" ry="6.5" fill="none" stroke="#9aa3ab" stroke-width="2.4" />
            </g>

            <!-- rope behind the body; it runs through the descender on the harness -->
            <path ref="ropeEdge" fill="none" stroke="#7a2e1d" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            <path ref="rope" fill="none" stroke="#f07a3e" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" />

            <!-- the climber: simple side-view silhouette, yellow helmet, red shoes -->
            <g ref="climber" stroke-linecap="round" stroke-linejoin="round">
                <path ref="legB" fill="none" stroke="#3a4552" stroke-width="6" />
                <path ref="legF" fill="none" stroke="#2b3440" stroke-width="6" />
                <path ref="shoeB" fill="none" stroke="#b83a4b" stroke-width="5" />
                <path ref="shoeF" fill="none" stroke="#d1495b" stroke-width="5" />
                <path ref="armB" fill="none" stroke="#17758b" stroke-width="4.6" />
                <path ref="torso" fill="#1f93b0" />
                <path ref="harness" fill="none" stroke="#ffc233" stroke-width="4" />
                <path ref="armF" fill="none" stroke="#1f93b0" stroke-width="4.6" />
                <circle ref="handB" r="2.8" fill="#d9a07d" />
                <circle ref="handF" r="2.8" fill="#e8b58f" />
                <circle ref="head" r="6" fill="#e8b58f" />
                <path ref="helmet" fill="#ffc233" />
            </g>

            <!-- descender + locking carabiner at the harness -->
            <g ref="device">
                <path d="M-6 0h4" stroke="#9aa3ab" stroke-width="2.2" stroke-linecap="round" />
                <rect x="-2.8" y="-6" width="5.6" height="12" rx="2.6" fill="#3a4552" stroke="#c7cdd2" stroke-width="1.2" />
            </g>
        </svg>

        <!-- back to top: white disc, a ring that fills with the scroll
             progress (service accent), and an up arrow -->
        <button
            ref="topBtn"
            class="back-to-top"
            type="button"
            @click="scrollToTop"
            :title="$t('global.top_out')"
            :aria-label="$t('global.top_out')"
            :tabindex="showButton ? 0 : -1"
        >
            <svg class="back-to-top__ring" viewBox="0 0 48 48" aria-hidden="true">
                <circle cx="24" cy="24" r="21" pathLength="100" class="back-to-top__track" />
                <circle cx="24" cy="24" r="21" pathLength="100" class="back-to-top__progress" />
            </svg>
            <svg class="back-to-top__arrow" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 19V5M6 11l6-6 6 6" />
            </svg>
        </button>
    </div>
</template>

<script>
// ---- small geometry helpers ---------------------------------------------------
const P = (x, y) => ({ x, y })
const f1 = n => n.toFixed(1)
const line = (...pts) => 'M' + pts.map(p => `${f1(p.x)} ${f1(p.y)}`).join(' L')
function ik(root, target, a, b, bend) {
    const dx = target.x - root.x, dy = target.y - root.y
    let d = Math.hypot(dx, dy) || 0.01
    let end = target
    if (d > a + b - 0.5) { d = a + b - 0.5; end = P(root.x + (dx / Math.hypot(dx, dy)) * d, root.y + (dy / Math.hypot(dx, dy)) * d) }
    const base = Math.atan2(dy, dx)
    const A = Math.acos(Math.max(-1, Math.min(1, (a * a + d * d - b * b) / (2 * a * d)))) * bend
    return { joint: P(root.x + a * Math.cos(base + A), root.y + a * Math.sin(base + A)), end }
}

// The climber only abseils on long pages: at least this many screens of
// scrolling (measured in the visitor's own screen height, so it's 1.5 rather
// than more — on tall monitors a normal article scrolls less than 2 screens).
// Short pages just get the back-to-top button.
const LONG_PAGE_SCREENS = 1.5

// sway (px) of the rope tail's free end when the climber isn't moving
const ROPE_IDLE_AMP = 2.5

// Ridge line of the footer mountains (public/images/svg/mountains.svg): the
// image is drawn once onto a small canvas and each column is scanned for its
// first opaque pixel, so the climber can stand exactly on the skyline.
let ridgePromise = null
function loadRidge() {
    if (ridgePromise) return ridgePromise
    ridgePromise = new Promise(resolve => {
        const img = new Image()
        img.onload = () => {
            try {
                const width = Math.round(img.naturalWidth || 863)
                const height = Math.round(img.naturalHeight || 147)
                const canvas = document.createElement('canvas')
                canvas.width = width
                canvas.height = height
                const ctx = canvas.getContext('2d', { willReadFrequently: true })
                ctx.drawImage(img, 0, 0, width, height)
                const data = ctx.getImageData(0, 0, width, height).data
                const ridge = new Array(width).fill(height)
                for (let x = 0; x < width; x++) {
                    for (let y = 0; y < height; y++) {
                        if (data[(y * width + x) * 4 + 3] > 40) { ridge[x] = y; break }
                    }
                }
                resolve({ ridge, width, height })
            } catch (e) {
                resolve(null)
            }
        }
        img.onerror = () => resolve(null)
        img.src = '/images/svg/mountains.svg'
    })
    return ridgePromise
}

export default {
    data() {
        return {
            showButton: false,
            longPage: false,
        };
    },

    computed: {
        // no rope on the user/admin panel or on the climbing wall calculator
        ropeEnabled() {
            if (window.location.hostname === process.env.MIX_USER_PAGE_URL) return false;
            return !(this.$route && this.$route.name === 'wall_price_colculator');
        },
    },

    mounted() {
        window.addEventListener("scroll", this.handleScroll, { passive: true });
        window.addEventListener("resize", this.handleScroll, { passive: true });
        this.reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.lastScroll = window.pageYOffset;
        this.walk = 0;
        this.landedAt = 0;

        // page height changes without a scroll (route change, lazy lists,
        // images loading): re-check whether the page is long enough
        if (window.ResizeObserver) {
            this.resizeObserver = new ResizeObserver(() => this.handleScroll());
            this.resizeObserver.observe(document.body);
        }

        loadRidge().then(r => { this.ridge = r; this.requestRender(); });
        this.handleScroll();
    },

    beforeUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
        window.removeEventListener("resize", this.handleScroll);
        if (this.resizeObserver) this.resizeObserver.disconnect();
        cancelAnimationFrame(this.raf);
    },

    methods: {
        handleScroll() {
            // Show once the user has scrolled down 100px
            this.showButton = window.pageYOffset > 100;
            const scrollable = document.documentElement.scrollHeight - window.innerHeight;
            // progress ring: set straight on the element (no re-render per scroll)
            if (this.$refs.topBtn) {
                const p = scrollable > 0 ? Math.min(1, window.pageYOffset / scrollable) : 0;
                this.$refs.topBtn.style.setProperty('--cg-top-progress', (p * 100).toFixed(1));
            }
            this.longPage = scrollable > window.innerHeight * LONG_PAGE_SCREENS;
            this.requestRender();
        },

        // Draw on demand only (scroll / resize), not 60 times a second: a frame
        // is requested here, and render() keeps requesting more only while
        // something is actually moving (rope fluttering after the landing).
        requestRender() {
            if (this.raf || !this.ropeEnabled) return;
            this.raf = requestAnimationFrame(now => {
                this.raf = null;
                if (this.render(now)) this.requestRender();
            });
        },

        scrollToTop() {
            window.scrollTo({ top: 0, behavior: "smooth" });
        },

        // ground height (screen px) of the footer mountains at screen x, or null
        groundAt(x) {
            const graphic = document.querySelector('.footer__graphic');
            if (!graphic || !this.ridge) return null;
            const rect = graphic.getBoundingClientRect();
            if (rect.top > window.innerHeight || rect.bottom < 0) return null;
            const tileW = 800, tileH = 170;                 // background-size in _footer.scss
            const offsetX = (rect.width - tileW) * 5;       // background-position-x: 500%
            const local = (((x - rect.left - offsetX) % tileW) + tileW) % tileW;
            const sx = this.ridge.width / tileW;
            const i = Math.round(local * sx);
            const at = k => this.ridge.ridge[Math.max(0, Math.min(this.ridge.ridge.length - 1, k))];
            // feet on the highest point under the shoes
            let y = at(i);
            for (let k = -4; k <= 4; k++) y = Math.min(y, at(i + k));
            return rect.top + (rect.height - tileH) + y * (tileH / this.ridge.height);
        },

        // returns true while it needs another frame
        render(now) {
            const r = this.$refs;
            if (!r.svg || !this.showButton || !this.longPage || window.innerWidth < 768) return false;
            // visible viewport (without the scrollbar), so the climber's feet touch the page edge
            const vw = document.documentElement.clientWidth, vh = window.innerHeight;
            const t = now / 1000;
            r.svg.setAttribute('viewBox', `0 0 ${vw} ${vh}`);

            const x = vw - 30;      // rope line, a little in from the "wall" (page edge)
            const navH = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--cg-nav-h')) || 64;
            const top = navH + 34;
            const max = document.documentElement.scrollHeight - vh;
            const progress = max > 0 ? Math.min(1, window.pageYOffset / max) : 0;

            // scrolling makes the climber walk down the wall
            const dy = window.pageYOffset - this.lastScroll;
            this.lastScroll = window.pageYOffset;

            // rope tail swing: the faster the descent, the bigger the swing;
            // it settles back to a slight idle sway within about a second
            const dt = this.lastT ? Math.min(0.05, (now - this.lastT) / 1000) : 1 / 60;
            this.lastT = now;
            const speed = Math.abs(dy) / dt;                                   // px/s of scroll
            this.ropeAmp = Math.max((this.ropeAmp || ROPE_IDLE_AMP) * Math.exp(-dt * 1.4), Math.min(18, ROPE_IDLE_AMP + speed * 0.012));
            this.ropePhase = (this.ropePhase || 0) + dt * (2.4 + this.ropeAmp * 0.06);
            this.walk += Math.min(1, Math.abs(dy) / 40) * 0.35;
            const moving = Math.abs(dy) > 0.5;

            r.anchor.setAttribute('transform', `translate(${x} ${top - 18})`);

            const ground = this.groundAt(x - 14);
            const free = top + 40 + progress * (vh - 150 - top);
            const landed = ground !== null && ground < free + 40;
            if (landed && !this.landedAt) this.landedAt = t;
            if (!landed) this.landedAt = 0;

            // once the ridge has scrolled up under the header, the climber stays
            // up there with it — hide the layer instead of drawing the rope upward
            r.svg.style.opacity = landed && ground < top + 70 ? 0 : 1;

            if (!landed) this.drawRappel(x, Math.max(top + 30, free), vh, t, moving);
            else this.drawLanded(x, ground, top, t - this.landedAt, t);

            // keep animating only while something moves: the landed rope
            // flutters, or the hanging tail is still swinging after a scroll
            if (this.reduced) return false;
            if (landed) return ground >= top + 70;
            if (this.ropeAmp > ROPE_IDLE_AMP + 0.3) return true;
            this.lastT = 0;
            return false;
        },

        // ---- abseiling: sitting in the harness, feet on the wall, legs walking ----
        drawRappel(x, y, vh, t, moving) {
            const r = this.$refs, wall = document.documentElement.clientWidth - 1;
            const hip = P(x - 7, y + 1);
            const sh = P(hip.x - 11, hip.y - 20);
            const head = P(sh.x - 3, sh.y - 8);
            const step = moving ? Math.sin(this.walk * 3) : 0;
            const footF = P(wall - 1, y + 10 + step * 6);
            const footB = P(wall - 1, y + 16 - step * 6);
            this.setBody({
                hip, sh, head, lean: -28,
                legF: ik(hip, footF, 12, 12, -1),
                legB: ik(P(hip.x - 1, hip.y + 1), footB, 12, 12, -1),
                armF: ik(P(sh.x + 2, sh.y + 2), P(x, y - 24), 9, 9, 1),    // guide hand on the rope above
                armB: ik(P(sh.x, sh.y + 2), P(x - 9, y + 16), 9, 9, -1),   // brake hand below the device
                shoesUp: true,
            });
            // rope: tight from the anchor down to the descender at the harness,
            // then the free tail hangs below it. A wave runs down the tail —
            // bigger toward the free end — so it swings like a real rope, and
            // at rest it curves slightly away from the wall.
            const tailEnd = Math.min(vh - 70, y + 260);
            const amp = this.ropeAmp || ROPE_IDLE_AMP;
            const phase = this.ropePhase || 0;
            const pts = [];
            const n = 16;
            for (let i = 0; i <= n; i++) {
                const s = i / n;
                pts.push(P(
                    x - 7 * s * s + amp * Math.pow(s, 1.6) * Math.sin(phase - s * 3.2),
                    y + (tailEnd - y) * s,
                ));
            }
            this.setRope(`M${x} ${f1(this.ropeTop())} L${x} ${f1(y)} L` + pts.map(p => `${f1(p.x)} ${f1(p.y)}`).join(' L'));
            r.device.setAttribute('transform', `translate(${f1(x)} ${f1(y)})`);
            r.device.style.opacity = 1;
            r.climber.style.opacity = 1;
        },

        // ---- landed: standing on the ridge, unties, the rope end flutters in the wind ----
        drawLanded(x, ground, top, since, t) {
            const r = this.$refs;
            const feet = P(x - 22, ground);
            const hip = P(feet.x, ground - 22);
            const sh = P(hip.x, hip.y - 20);
            const head = P(sh.x - 1, sh.y - 8);
            const untieing = since < 0.9;
            const handTarget = untieing ? P(hip.x + 3, hip.y - 1 + Math.sin(since * 30) * 1.5) : P(hip.x - 6, hip.y + 4);
            this.setBody({
                hip, sh, head, lean: 0,
                legF: ik(hip, P(feet.x + 5, ground), 11, 11, 1),
                legB: ik(P(hip.x - 1, hip.y), P(feet.x - 5, ground), 11, 11, 1),
                armF: ik(P(sh.x + 1, sh.y + 2), untieing ? P(hip.x + 4, hip.y - 2) : P(sh.x + 6, sh.y - 16), 9, 9, 1),   // after untying: a wave
                armB: ik(P(sh.x - 1, sh.y + 2), handTarget, 9, 9, -1),
                shoesUp: false,
            });

            // the rope: tight from the anchor while tied in; once untied the
            // lower part swings free and flutters (more toward the free end)
            const ropeTop = this.ropeTop();
            const endY = ground - 8;
            const pts = [];
            const n = 26;
            const release = Math.min(1, Math.max(0, (since - 0.9) / 0.6));
            for (let i = 0; i <= n; i++) {
                const s = i / n;
                const baseX = untieing ? x + (hip.x + 5 - x) * Math.pow(s, 3) : x;
                const yy = ropeTop + (untieing ? hip.y - ropeTop : endY - ropeTop) * s;
                const gust = 0.6 + 0.4 * Math.sin(t * 0.7);
                const flutter = release * Math.pow(s, 2.2) * (Math.sin(t * 5.3 - s * 7) * 7 + Math.sin(t * 2.1 - s * 3) * 5) * gust;
                pts.push(P(baseX - flutter * 0.9 - release * Math.pow(s, 2) * 6, yy - release * Math.pow(s, 4) * 6 * gust));
            }
            this.setRope('M' + pts.map(p => `${f1(p.x)} ${f1(p.y)}`).join(' L'));
            // still on the rope while untying, then the device comes off
            r.device.setAttribute('transform', `translate(${f1(hip.x + 5)} ${f1(hip.y)})`);
            r.device.style.opacity = untieing ? 1 : 0;
            r.climber.style.opacity = 1;
        },

        ropeTop() {
            const navH = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--cg-nav-h')) || 64;
            return navH + 34 - 12;   // the quickdraw ring the rope runs through
        },

        setRope(d) {
            const r = this.$refs;
            r.ropeEdge.setAttribute('d', d);
            r.rope.setAttribute('d', d);
        },

        // draws the side-view body from solved points
        setBody(b) {
            const r = this.$refs;
            const rad = b.lean * Math.PI / 180;
            const off = (p, d) => P(p.x + Math.cos(rad) * d, p.y + Math.sin(rad) * d);
            r.legF.setAttribute('d', line(b.hip, b.legF.joint, b.legF.end));
            r.legB.setAttribute('d', line(P(b.hip.x - 1, b.hip.y + 1), b.legB.joint, b.legB.end));
            const shoe = (end, el) => el.setAttribute('d', b.shoesUp
                ? line(P(end.x - 1, end.y - 3), P(end.x - 1, end.y + 4))
                : line(P(end.x - 4, end.y - 1.5), P(end.x + 1.5, end.y - 1.5)));
            shoe(b.legF.end, r.shoeF);
            shoe(b.legB.end, r.shoeB);
            r.torso.setAttribute('d', line(off(b.hip, -5), off(b.sh, -6.5), off(b.sh, 6.5), off(b.hip, 5.5)) + 'z');
            r.harness.setAttribute('d', line(off(b.hip, -4.5), off(b.hip, 5)));
            r.armF.setAttribute('d', line(P(b.sh.x + 1, b.sh.y + 2), b.armF.joint, b.armF.end));
            r.armB.setAttribute('d', line(P(b.sh.x - 1, b.sh.y + 2), b.armB.joint, b.armB.end));
            r.handF.setAttribute('cx', f1(b.armF.end.x)); r.handF.setAttribute('cy', f1(b.armF.end.y));
            r.handB.setAttribute('cx', f1(b.armB.end.x)); r.handB.setAttribute('cy', f1(b.armB.end.y));
            r.head.setAttribute('cx', f1(b.head.x)); r.head.setAttribute('cy', f1(b.head.y));
            r.helmet.setAttribute('d', `M${f1(b.head.x - 7)} ${f1(b.head.y)}a7 7.4 0 0 1 14 0z`);
            r.helmet.setAttribute('transform', `rotate(${f1(b.lean * 0.6)} ${f1(b.head.x)} ${f1(b.head.y)})`);
        },
    },
};
</script>
