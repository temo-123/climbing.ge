<template>
    <!-- Climbing scene on the side of the right menu card: the card's left
         edge is the "wall" (x = 0 in SVG units = CSS px), the card bottom is
         the ground (y = H). The SVG covers the strip left of the card, the
         card itself, the area above it (mountain top) and below it (slackline).
         Everything is clipped away where it would cover the card, so climbers
         can walk out from "behind" the card before they start.
         Variants (picked by RightMenuComponent from the page):
           lead      — outdoor: lead climb clipping the quickdraws, lowered off
                       the anchor, a real changeover, then the partner leads
           indoor    — the same on a gym wall: coloured holds, no helmets, mat
           ice       — lead ice climbing: ice screws, tools, A-frame stance
           safety    — tech tips: practising a lead fall with a soft catch
           summit    — the card top is a mountain: the leader climbs placing
                       cams, belays the second up from an anchor (who removes
                       the cams), flag + climbing.ge ascent verification, then
                       they walk back and rappel on a double rope
           slackline — other articles: a slackliner under the card between
                       two trees
         Ground belayers stand with their spare rope coiled beside them; the
         top belay on the mountain is tethered to the rock-horn anchor.
         The bolter (one-sector outdoor menus, special articles) is a static
         SVG image (public/images/scenes/bolter-solo.svg), not this component. -->
    <svg
        ref="svg"
        class="cg-side-scene"
        :style="{ left: -S + 'px', top: -T + 'px', width: S + W + 'px', height: T + H + B + 'px' }"
        :viewBox="`${-S} ${-T} ${S + W} ${T + H + B}`"
        aria-hidden="true"
    >
        <defs>
            <!-- visible area: left of the card, above it and below it (never over it) -->
            <clipPath :id="clipId">
                <rect :x="-S" :y="-T" :width="S + 0.5" :height="T + H + B" />
                <rect :x="-S" :y="-T" :width="S + W" :height="T + 0.5" />
                <rect :x="-S" :y="H - 0.5" :width="S + W" :height="B + 0.5" />
            </clipPath>
            <linearGradient :id="clipId + 'ice'" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0" stop-color="#bfe3f3" /><stop offset="1" stop-color="#e9f6fb" />
            </linearGradient>
        </defs>

        <g :clip-path="`url(#${clipId})`">
            <!-- mountain top over the card (summit) -->
            <g v-if="variant === 'summit'">
                <path :d="mountain.body" fill="#fff" />
                <path :d="mountain.shade" fill="#e4e8ec" />
                <path :d="mountain.snow" fill="#f4f8fb" stroke="#d6dee5" stroke-width="1" />
                <path :d="mountain.edge" fill="none" stroke="#c9d1d8" stroke-width="1.4" stroke-linejoin="round" />
                <!-- the crack the cams go in -->
                <path :d="`M-1 ${H - 40}c-2-40 2-80-1-120s2-80 0-${Math.max(0, H - 200)}`" fill="none" stroke="#c9d1d8" stroke-width="1.6" />
            </g>

            <!-- ice strip down the card edge -->
            <g v-if="variant === 'ice'">
                <rect x="-9" y="0" width="10" :height="H" :fill="`url(#${clipId}ice)`" />
                <path :d="icicles" fill="#d7eef8" />
            </g>

            <!-- gym wall: coloured holds on the edge -->
            <g v-if="variant === 'indoor'">
                <path v-for="(h, i) in gymHolds" :key="'gh' + i" :d="`M0 ${h.y - 4}a4.5 4.5 0 0 0 0 9z`" :fill="h.c" />
            </g>

            <!-- the ledge the climbers stand on, styled per category -->
            <!-- (short: only under the climbers, so it doesn't cover the page) -->
            <g v-if="ledge">
                <rect :x="ledgeX" :y="H - 1" :width="-ledgeX" height="5" rx="2" :fill="ledge.fill" />
                <rect :x="ledgeX" :y="H - 1" :width="-ledgeX" height="1.6" :fill="ledge.top" />
                <g v-if="ledge.stones" fill="#9c8f77">
                    <ellipse :cx="ledgeX + 6" :cy="H - 1" rx="3.5" ry="2.2" /><ellipse cx="-10" :cy="H - 1" rx="3" ry="2" />
                </g>
            </g>

            <!-- the belayer's spare rope: a coil on the ground behind them, the
                 brake strand runs from the belay device down into it -->
            <g ref="spare" opacity="0">
                <path d="M-11 0c-6-1-9-4-4-5.5s15-1 17 1-3 4.5-11 4.5-12-2-9-4.5 14-3 17-1" fill="none" stroke="#f07a3e" stroke-width="2.4" stroke-linecap="round" />
            </g>
            <path ref="brake" fill="none" stroke="#f07a3e" stroke-width="2.2" stroke-linecap="round" />

            <!-- quickdraws on bolts (rock, gym) or ice screws (ice): hanger,
                 carabiner, dogbone sling, rope carabiner (pops when clipped) -->
            <g v-for="(d, i) in draws" :key="'d' + i" :transform="`translate(0 ${d})`" stroke-linecap="round"><g ref="drawSwing">
                <g v-if="variant === 'ice'">
                    <rect x="-7" y="-24" width="7" height="3.4" rx="1" fill="#9aa3ab" />
                    <path d="M-7 -22.3h-2.4" stroke="#ffc233" stroke-width="2.6" />
                    <circle cx="-8" cy="-22.3" r="1.6" fill="none" stroke="#5b6670" stroke-width="1" />
                </g>
                <g v-else>
                    <path d="M0 -26.5h-3.6a2 2 0 0 0-2 2v1.6a2 2 0 0 0 2 2H0z" fill="#9aa3ab" />
                    <circle cx="-1.6" cy="-23.7" r="1" fill="#5b6670" />
                </g>
                <ellipse cx="-4.2" cy="-17.6" rx="2.1" ry="3.1" fill="none" stroke="#9aa3ab" stroke-width="1.4" />
                <rect x="-5.6" y="-15.2" width="2.8" height="9.4" rx="1.4" :fill="variant === 'indoor' ? '#d1495b' : '#2f9e8f'" />
                <g ref="drawRing">
                    <circle cx="-3.8" cy="-2.6" r="5.6" fill="none" stroke="#fff" stroke-width="1.6" opacity="0" />
                    <ellipse cx="-3.8" cy="-2.6" rx="2.6" ry="3.7" fill="none" stroke="#7b858e" stroke-width="1.7" />
                    <path d="M-1.4 -4.6l-.6 3.6" stroke="#c7cdd2" stroke-width="1.1" />
                </g>
            </g></g>

            <!-- lowering anchor at the top (rope scenes): two bolt hangers, short
                 chains down to one ring with a quick-link -->
            <g v-if="hasAnchor" stroke-linecap="round">
                <path d="M0 2h-6.5a3.2 3.2 0 0 0 0 6.4H0zM0 13h-6.5a3.2 3.2 0 0 0 0 6.4H0z" fill="#9aa3ab" />
                <circle cx="-2.4" cy="5.2" r="1.3" fill="#5b6670" /><circle cx="-2.4" cy="16.2" r="1.3" fill="#5b6670" />
                <path d="M-6.6 6.6L-9 23.6M-6.6 17.6L-9 23.6" fill="none" stroke="#7b858e" stroke-width="2.2" stroke-dasharray="2.4 1.1" />
                <rect x="-11.2" y="22.4" width="4.4" height="3.6" rx="1.4" fill="none" stroke="#5b6670" stroke-width="1.5" />
                <circle cx="-9" cy="29.4" r="3.9" fill="none" stroke="#7b858e" stroke-width="2.2" />
            </g>

            <!-- mountaineering: cams the leader places in the crack and the second
                 removes — lobes in the crack, stem, coloured sling, carabiner -->
            <g v-for="(y, i) in pieces" :key="'pc' + i" ref="piece" :transform="`translate(0 ${y})`" opacity="0" stroke-linecap="round" stroke-linejoin="round">
                <path d="M-1 -3.2l-3 1.6v3.2l3 1.6" fill="#9aa3ab" stroke="#7b858e" stroke-width=".8" />
                <path d="M-4 0h-5" stroke="#5b6670" stroke-width="1.8" />
                <path d="M-8 -2.4v4.8" stroke="#3a4552" stroke-width="1.6" />
                <path d="M-9 0c-3 1-4 5-3 9 1 3 4 3 5 0 1-4 0-8-2-9z" fill="none" :stroke="camColors[i % camColors.length]" stroke-width="2" />
                <ellipse cx="-10" cy="12" rx="2.4" ry="3.3" fill="none" stroke="#7b858e" stroke-width="1.6" />
            </g>
            <!-- rappel / belay anchor near the edge: a sling round a rock horn -->
            <g v-if="variant === 'summit'" :transform="`translate(${rapAnchor.x} ${rapAnchor.y})`">
                <path d="M-7 1c0-6 3-9 7-9s7 3 7 9z" fill="#c9d1d8" />
                <path d="M-6.5 -1.5c3 2 10 2 13 0" fill="none" stroke="#d1495b" stroke-width="2.2" />
                <circle cx="-7" cy="0" r="2.4" fill="none" stroke="#7b858e" stroke-width="1.6" />
            </g>

            <!-- slackline between two trees under the card -->
            <g v-if="variant === 'slackline'">
                <rect :x="-S" :y="H + B - 8" :width="S + W" height="8" fill="#7d8f63" />
                <rect :x="-S" :y="H + B - 8" :width="S + W" height="2" fill="#93a676" />
                <g v-for="x in [slack.ax - 6, slack.bx + 6]" :key="'tr' + x">
                    <rect :x="x - 4" :y="H + 40" width="8" :height="B - 46" rx="3" fill="#7a5a3c" />
                    <circle :cx="x" :cy="H + 34" r="13" fill="#4f6b45" />
                    <circle :cx="x - 7" :cy="H + 42" r="8" fill="#5c7a50" />
                    <rect :x="x - 5" :y="slack.y - 3" width="10" height="5" rx="1.5" fill="#d1495b" />
                </g>
                <path ref="slack" fill="none" stroke="#ffc233" stroke-width="2.6" stroke-linecap="round" />
            </g>

            <!-- rope pile on the ground during the changeover -->
            <path ref="pile" fill="none" stroke="#f07a3e" stroke-width="2.4" stroke-linecap="round" opacity="0"
                  d="M-9 0c-6-1-9-4-4-5s14-1 16 1-3 4-10 4-12-2-9-4 13-3 16-1" />

            <!-- belayer tethers (behind the figures) and ropes -->
            <path ref="tether1" fill="none" stroke="#2f9e8f" stroke-width="2" stroke-linecap="round" />
            <path ref="tether2" fill="none" stroke="#2f9e8f" stroke-width="2" stroke-linecap="round" />
            <path ref="rope1" fill="none" stroke="#f07a3e" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" />
            <path ref="rope2" fill="none" stroke="#f07a3e" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" />
            <path ref="rope3" fill="none" stroke="#2f6fb3" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />

            <!-- summit flag (Georgian flag), waving -->
            <g v-if="variant === 'summit'" ref="flag" opacity="0">
                <path ref="flagPole" stroke="#5b6670" stroke-width="2.2" stroke-linecap="round" />
                <g ref="flagCloth">
                    <rect x="0" y="0" width="26" height="17" fill="#fff" stroke="#c9d1d8" stroke-width=".6" />
                    <path d="M11.2 0h3.6v17h-3.6zM0 6.7h26v3.6H0z" fill="#e8112d" />
                    <path d="M4.4 2.2v4M2.4 4.2h4M21.6 2.2v4M19.6 4.2h4M4.4 10.8v4M2.4 12.8h4M21.6 10.8v4M19.6 12.8h4" stroke="#e8112d" stroke-width="1.3" />
                </g>
            </g>

            <!-- climbers: side profile, facing the wall (right) -->
            <g v-for="(c, i) in figures" :key="'f' + i" ref="fig" stroke-linecap="round" stroke-linejoin="round">
                <path data-part="farLeg" fill="none" stroke="#1d242c" stroke-width="6" />
                <path data-part="farArm" fill="none" :stroke="c.shade" stroke-width="4.6" />
                <path data-part="tool1" fill="none" stroke="#3a4552" stroke-width="2" />
                <path data-part="torso" fill="none" :stroke="c.shirt" stroke-width="12" />
                <path data-part="nearLeg" fill="none" stroke="#2b3440" stroke-width="6.4" />
                <path data-part="shoes" fill="none" :stroke="c.shoe" stroke-width="4.4" />
                <path data-part="harness" fill="none" stroke="#ffc233" stroke-width="3.6" />
                <path data-part="nearArm" fill="none" :stroke="c.shirt" stroke-width="5" />
                <path data-part="tool2" fill="none" stroke="#3a4552" stroke-width="2" />
                <rect data-part="phone" width="5" height="8" rx="1.2" fill="#15212b" opacity="0" />
                <circle data-part="head" r="6.6" fill="#e8b58f" />
                <path data-part="hair" :fill="c.hair" />
                <path data-part="helmet" :fill="c.helmet || 'none'" />
            </g>
        </g>

        <!-- speech bubble (summit: the ascent gets verified on climbing.ge) -->
        <g v-if="variant === 'summit'" ref="bubble" opacity="0">
            <rect ref="bubbleBox" height="17" rx="8.5" fill="#fff" stroke="#15212b" stroke-opacity=".15" />
            <path ref="bubbleTail" fill="#fff" />
            <text ref="bubbleText" font-family="Manrope, 'Noto Sans Georgian', sans-serif" font-size="8.6" font-weight="800" fill="#13865d" dominant-baseline="middle" />
        </g>
    </svg>
</template>

<script>
// ---- geometry -----------------------------------------------------------------
const P = (x, y) => ({ x, y })
const f1 = n => (Math.round(n * 10) / 10).toString()
const lerp = (a, b, t) => a + (b - a) * t
const lp = (a, b, t) => P(lerp(a.x, b.x, t), lerp(a.y, b.y, t))
const clamp = (v, a, b) => Math.max(a, Math.min(b, v))
const smooth = t => t * t * (3 - 2 * t)
const GRAVITY = 400                         // px/s² at this scale (climber ≈ 62px ≈ 1.7m)
const LEG = 19, ARM = 12                    // legs ≈ half the body height

// two-bone IK; `pref` picks the side the joint bends to (knees toward the
// wall, elbows down and back)
function ik(r, t, a, b, pref) {
    let dx = t.x - r.x, dy = t.y - r.y
    let d = Math.hypot(dx, dy) || 0.001
    let end = t
    const max = a + b - 0.4
    if (d > max) { end = P(r.x + dx / d * max, r.y + dy / d * max); d = max }
    const ux = (end.x - r.x) / d, uy = (end.y - r.y) / d
    const along = (a * a - b * b + d * d) / (2 * d)
    const h = Math.sqrt(Math.max(0, a * a - along * along))
    const px = -uy, py = ux
    const s = (px * pref.x + py * pref.y) >= 0 ? 1 : -1
    return { j: P(r.x + ux * along + px * h * s, r.y + uy * along + py * h * s), end }
}
const poly = (...pts) => 'M' + pts.map(p => `${f1(p.x)} ${f1(p.y)}`).join(' L')
// rope path with rounded corners — a rope never makes a hard angle
function roundPath(pts, r = 7) {
    if (pts.length < 3) return pts.length ? poly(...pts) : ''
    let d = `M${f1(pts[0].x)} ${f1(pts[0].y)}`
    for (let i = 1; i < pts.length - 1; i++) {
        const a = pts[i - 1], p = pts[i], b = pts[i + 1]
        const la = Math.hypot(p.x - a.x, p.y - a.y) || 1, lb = Math.hypot(b.x - p.x, b.y - p.y) || 1
        const ra = Math.min(r, la / 2), rb = Math.min(r, lb / 2)
        const s = P(p.x + (a.x - p.x) / la * ra, p.y + (a.y - p.y) / la * ra)
        const e = P(p.x + (b.x - p.x) / lb * rb, p.y + (b.y - p.y) / lb * rb)
        d += ` L${f1(s.x)} ${f1(s.y)} Q${f1(p.x)} ${f1(p.y)} ${f1(e.x)} ${f1(e.y)}`
    }
    const z = pts[pts.length - 1]
    return d + ` L${f1(z.x)} ${f1(z.y)}`
}

// A limb that stays on its hold while the body moves up, then moves quickly
// to the next hold one `step` higher (y decreases going up). `ph` staggers the
// limbs so they move one at a time. Returns { y, moving 0..1 }.
function holdY(v, step, ph) {
    const u = v / step + ph
    const fl = Math.floor(u)
    const f = u - fl
    const s = f < 0.28 ? smooth(1 - f / 0.28) : 0
    return { y: (fl - s - ph) * step, moving: s > 0 && s < 1 ? Math.sin(Math.PI * s) : 0 }
}

const ROCK = [
    { shirt: '#1f93b0', shade: '#17758b', helmet: '#ffc233', shoe: '#d1495b', hair: '#5a3d2b' },
    { shirt: '#c4532d', shade: '#a8462a', helmet: '#7a5bd6', shoe: '#2f6fb3', hair: '#3b2a1f' },
]
const GYM = [
    { shirt: '#18a06f', shade: '#13865d', helmet: null, shoe: '#d1495b', hair: '#3b2a1f' },
    { shirt: '#8a63e0', shade: '#6c45c4', helmet: null, shoe: '#ffc233', hair: '#b07a17' },
]
const ICE = [
    { shirt: '#d9343e', shade: '#b8232c', helmet: '#ffc233', shoe: '#3a4552', hair: '#5a3d2b' },
    { shirt: '#2f6fb3', shade: '#24588f', helmet: '#f4f7f9', shoe: '#3a4552', hair: '#3b2a1f' },
]
const SLACK = [{ shirt: '#ffc233', shade: '#e0a400', helmet: null, shoe: '#2f6fb3', hair: '#5a3d2b' }]

export default {
    name: 'MenuSideScene',
    props: {
        variant: { type: String, required: true },     // lead | indoor | ice | safety | summit | slackline
        width: { type: Number, required: true },       // card width (px)
        height: { type: Number, required: true },      // card height (px)
    },
    data() {
        return { S: 130, camColors: ['#d9343e', '#ffc233', '#2f6fb3', '#8a63e0'] }
    },
    computed: {
        clipId() { return 'cgside' + this.$.uid },
        W() { return Math.round(this.width) },
        H() { return Math.max(160, Math.round(this.height)) },
        // summit: room above the card for the mountain, the flag and the speech bubble
        T() { return this.variant === 'summit' ? 168 : 12 },
        // slackline: room below the card for the trees and the line
        B() { return this.variant === 'slackline' ? 132 : 10 },
        figures() {
            if (this.variant === 'indoor') return GYM
            if (this.variant === 'ice') return ICE
            if (this.variant === 'slackline') return SLACK
            return ROCK
        },
        hasAnchor() { return ['lead', 'indoor', 'ice'].includes(this.variant) },
        ledge() {
            switch (this.variant) {
                case 'lead': return { fill: '#7d8f63', top: '#93a676', stones: true }
                case 'safety': return { fill: '#7d8f63', top: '#93a676', stones: false }
                case 'indoor': return { fill: '#2f6fb3', top: '#4a8bd0', stones: false }
                case 'ice': case 'summit': return { fill: '#e4eef4', top: '#f7fbfd', stones: false }
                default: return null
            }
        },
        ledgeX() { return this.belaySpot - 34 },
        belaySpot() { return this.variant === 'summit' ? -46 : -42 },  // ~1m out from the route
        // ground belayers aren't anchored; their spare rope lies in a coil here
        sparePile() { return P(this.belaySpot - 20, this.H - 1) },
        // quickdraw / ice-screw positions (y of the rope carabiner), bottom → top
        draws() {
            if (this.variant === 'summit' || this.variant === 'slackline') return []
            const out = []
            for (let y = this.H - 104; y > 70; y -= 60) out.push(Math.round(y))
            return out
        },
        // mountaineering protection on the side of the card, bottom → top
        pieces() {
            if (this.variant !== 'summit') return []
            const out = []
            for (let y = this.H - 92; y > 58; y -= 56) out.push(Math.round(y))
            return out
        },
        // the card top as a mountain: peak a little right of centre
        mountain() {
            const W = this.W, M = 80, pk = P(W * 0.5, -M + 10)
            const l1 = P(W * 0.22, -M * 0.42), r1 = P(W * 0.72, -M * 0.55), r2 = P(W * 0.86, -M * 0.3)
            const R = 14                                      // card corner radius
            const pts = `L${f1(l1.x)} ${f1(l1.y)}L${f1(W * 0.34)} ${f1(-M * 0.5)}L${f1(pk.x)} ${f1(pk.y)}L${f1(r1.x)} ${f1(r1.y)}L${f1(r2.x)} ${f1(r2.y)}`
            const body = `M${R} 1${pts}L${W - R} 1Z`
            const edge = `M${R} 0.5${pts}L${W - R} 0.5`
            const shade = `M${f1(pk.x)} ${f1(pk.y)}L${f1(r1.x)} ${f1(r1.y)}L${f1(r2.x)} ${f1(r2.y)}L${W - R} 1L${f1(W * 0.62)} 1Z`
            const snow = `M${f1(pk.x)} ${f1(pk.y)}L${f1(pk.x - 18)} ${f1(pk.y + 20)}l8-3 6 6 7-7 6 4 9-6z`
            const ridge = [P(R, 0.5), l1, P(W * 0.34, -M * 0.5), pk, r1, r2]
            return { body, edge, shade, snow, peak: pk, ridge, base: R }
        },
        // belay / rappel anchor: a rock horn with a sling, just in from the edge
        rapAnchor() {
            const x = this.mountain.base + 8
            return P(x, this.ridgeY(x))
        },
        // the line hangs low enough that the slackliner stands clear of the card
        slack() { return { ax: 14, bx: this.W - 14, y: this.H + 92 } },
        icicles() {
            let d = ''
            for (let y = 6; y < this.H - 10; y += 26) d += `M-9 ${y}l-4 9 4 3z`
            return d
        },
        gymHolds() {
            const cols = ['#ffc233', '#ff6a3d', '#3fb59a', '#8a63e0', '#2f6fb3']
            const out = []
            for (let y = 24, i = 0; y < this.H - 20; y += 14, i++) out.push({ y, c: cols[i % cols.length] })
            return out
        },
        // the lead-climb schedule for the current card height
        leadPlan() {
            const top = 46, bottom = this.H - 37
            const v = this.variant === 'ice' ? 13 : (this.variant === 'indoor' ? 22 : 18)
            const plan = []
            let y = bottom
            this.draws.forEach((d, i) => {
                const at = d + 30                          // hip height when the draw is at the chest
                if (at < y && at > top) {
                    plan.push({ type: 'climb', from: y, to: at, dur: (y - at) / v })
                    plan.push({ type: 'clip', y: at, draw: i, dur: 1.1 })
                    y = at
                }
            })
            plan.push({ type: 'climb', from: y, to: top, dur: (y - top) / v })
            plan.push({ type: 'clip', y: top, draw: -1, dur: 1.1 })      // clip the anchor
            let t = 0
            plan.forEach(s => { s.t0 = t; t += s.dur })
            return { plan, dur: t, top, bottom }
        },
    },
    mounted() {
        this.reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches
        this.visible = true
        if (window.IntersectionObserver) {
            this.io = new IntersectionObserver(([e]) => { this.visible = e.isIntersecting; this.kick() })
            this.io.observe(this.$refs.svg)
        }
        this.onVis = () => this.kick()
        document.addEventListener('visibilitychange', this.onVis)
        this.t0 = performance.now()
        this.last = 0
        if (this.reduced) this.$nextTick(() => this.render(this.stillTime()))
        else this.kick()
    },
    beforeUnmount() {
        cancelAnimationFrame(this.raf)
        if (this.io) this.io.disconnect()
        document.removeEventListener('visibilitychange', this.onVis)
    },
    methods: {
        kick() {
            if (this.reduced || this.raf || !this.visible || document.hidden) return
            const loop = now => {
                this.raf = null
                if (!this.visible || document.hidden) return
                if (now - this.last > 33) {                 // ~30 fps is plenty here
                    this.last = now
                    this.render((now - this.t0) / 1000)
                }
                this.raf = requestAnimationFrame(loop)
            }
            this.raf = requestAnimationFrame(loop)
        },
        stillTime() { return this.variant === 'summit' ? 9999 : 9 },

        // height of the mountain ridge line at x
        ridgeY(x) {
            const ridge = this.mountain.ridge
            if (x <= ridge[0].x) return ridge[0].y
            for (let i = 1; i < ridge.length; i++) {
                const a = ridge[i - 1], b = ridge[i]
                if (x <= b.x) return lerp(a.y, b.y, (x - a.x) / (b.x - a.x))
            }
            return ridge[ridge.length - 1].y
        },
        polyLen(pts) { let l = 0; for (let i = 1; i < pts.length; i++) l += Math.hypot(pts[i].x - pts[i - 1].x, pts[i].y - pts[i - 1].y); return l },
        // the part of a polyline between distances `from` and `to` along it
        subPath(pts, from, to) {
            const out = []
            let acc = 0
            for (let i = 1; i < pts.length; i++) {
                const a = pts[i - 1], b = pts[i], d = Math.hypot(b.x - a.x, b.y - a.y)
                const s = acc, e = acc + d
                if (e >= from && s <= to && d > 0) {
                    if (!out.length) out.push(lp(a, b, clamp((from - s) / d, 0, 1)))
                    out.push(lp(a, b, clamp((to - s) / d, 0, 1)))
                }
                acc = e
            }
            return out
        },

        // ---- figure drawing ----------------------------------------------------
        // pose: hip, sh, lh/rh (far/near hand), lf/rf (far/near foot)
        // opts: { tools, flatShoes, opacity, mirror, phone }
        draw(i, pose, opts = {}) {
            const g = this.$refs.fig && this.$refs.fig[i]
            if (!g) return
            if (!pose) { g.setAttribute('opacity', '0'); return }
            const part = n => g.querySelector(`[data-part="${n}"]`)
            const set = (n, a, v) => part(n).setAttribute(a, v)
            const { hip, sh } = pose
            const ux = (sh.x - hip.x), uy = (sh.y - hip.y), ul = Math.hypot(ux, uy) || 1
            const up = P(ux / ul, uy / ul)
            const head = P(sh.x + up.x * 9, sh.y + up.y * 9)
            const toWall = P(1, 0), downBack = P(-0.4, 1)
            const legF = ik(P(hip.x - 1, hip.y), pose.lf, LEG, LEG, toWall)
            const legN = ik(P(hip.x + 1, hip.y + 1), pose.rf, LEG, LEG, toWall)
            const armF = ik(P(sh.x - 1, sh.y + 2), pose.lh, ARM, ARM, downBack)
            const armN = ik(P(sh.x + 1, sh.y + 2), pose.rh, ARM, ARM, downBack)
            set('farLeg', 'd', poly(P(hip.x - 1, hip.y), legF.j, legF.end))
            set('nearLeg', 'd', poly(P(hip.x + 1, hip.y + 1), legN.j, legN.end))
            const shoe = e => opts.flatShoes ? `M${f1(e.x - 4)} ${f1(e.y)}h7` : `M${f1(e.x)} ${f1(e.y - 4)}v7`
            set('shoes', 'd', shoe(legF.end) + shoe(legN.end))
            set('torso', 'd', poly(hip, sh))
            set('harness', 'd', poly(P(hip.x - 6, hip.y - 2), P(hip.x + 6, hip.y + 1)))
            set('farArm', 'd', poly(P(sh.x - 1, sh.y + 2), armF.j, armF.end))
            set('nearArm', 'd', poly(P(sh.x + 1, sh.y + 2), armN.j, armN.end))
            set('head', 'cx', f1(head.x)); set('head', 'cy', f1(head.y))
            set('hair', 'd', `M${f1(head.x - 6.9)} ${f1(head.y + 1.5)}a6.9 7.2 0 0 1 12.4-4.6c-3.4-.6-6.6.4-8.4 3.2-.8 1.2-1.6 1.6-4 1.4z`)
            set('helmet', 'd', `M${f1(head.x - 7.2)} ${f1(head.y + 0.5)}a7.2 7.6 0 0 1 14.4 0z`)
            const tool = h => `M${f1(h.x)} ${f1(h.y + 3)}L${f1(h.x + 3)} ${f1(h.y - 10)}l6 3`
            set('tool1', 'd', opts.tools ? tool(armF.end) : '')
            set('tool2', 'd', opts.tools ? tool(armN.end) : '')
            const ph = part('phone')
            ph.setAttribute('x', f1(armN.end.x - 1)); ph.setAttribute('y', f1(armN.end.y - 8))
            ph.setAttribute('opacity', opts.phone ? '1' : '0')
            g.setAttribute('opacity', f1(opts.opacity == null ? 1 : opts.opacity))
            // facing left (e.g. belaying from the top, walking back): mirror around the hips
            g.setAttribute('transform', opts.mirror ? `translate(${f1(2 * hip.x)} 0) scale(-1 1)` : '')
        },

        // poses ------------------------------------------------------------------
        // rock / gym: on the wall, moving up; y = hip. Hips out from the wall,
        // one foot high and one low, legs long — not folded up under the body.
        climbPose(y, x = -18) {
            const rh = holdY(y - 36, 26, 0), lh = holdY(y - 40, 26, 0.5)
            // feet step in shorter moves so they stay well below the hips (legs
            // never fold up under the body): near foot y+25..y+37, far y+21..y+33
            const rf = holdY(y + 37, 12, 0.25), lf = holdY(y + 33, 12, 0.75)
            return {
                hip: P(x, y), sh: P(x + 3, y - 25),
                rh: P(-2 - rh.moving * 5, rh.y), lh: P(-3 - lh.moving * 5, lh.y),
                rf: P(-2 - rf.moving * 7, rf.y), lf: P(-3 - lf.moving * 7, lf.y),
            }
        },
        // ice: the A-frame — hips well out, feet low and wide, tools high
        icePose(y) {
            const rh = holdY(y - 44, 22, 0), lh = holdY(y - 48, 22, 0.5)
            const rf = holdY(y + 38, 22, 0.25), lf = holdY(y + 35, 22, 0.75)
            return {
                hip: P(-25, y), sh: P(-22, y - 25),
                rh: P(-10 - rh.moving * 6, rh.y + 8), lh: P(-11 - lh.moving * 6, lh.y + 8),
                rf: P(-8 - rf.moving * 7, rf.y), lf: P(-9 - lf.moving * 7, lf.y),
            }
        },
        wallPose(y) { return this.variant === 'ice' ? this.icePose(y) : this.climbPose(y) },
        lowerPose(y, anchor, walk) {      // sitting in the harness, legs out to the wall
            const hip = P(-27, y)
            const sh = P(-33, y - 24)
            const dx = anchor.x - hip.x, dy = anchor.y - hip.y, d = Math.hypot(dx, dy) || 1
            return {
                hip, sh,
                rh: P(hip.x + dx / d * 16, hip.y + dy / d * 16), lh: P(hip.x + dx / d * 27, hip.y + dy / d * 27),
                rf: P(-2, y + 12 + Math.sin(walk) * 4), lf: P(-3, y + 5 - Math.sin(walk) * 4),
            }
        },
        standPose(x, ground, walk = 0, belay = false) {
            const hip = P(x, ground - 37 + Math.abs(Math.sin(walk)) * -1.5)
            return {
                hip, sh: P(x + 1, hip.y - 25),
                rh: belay ? P(x + 9, hip.y - 4) : P(x + 3 + Math.sin(walk) * 4, hip.y + 4),
                lh: belay ? P(x + 3, hip.y + 8) : P(x - 2 - Math.sin(walk) * 4, hip.y + 4),
                rf: P(x + 6 + Math.sin(walk) * 7, ground), lf: P(x - 6 - Math.sin(walk) * 7, ground),
            }
        },
        // belayer pulled up and toward the wall by `lift` px (soft catch)
        lifted(pose, lift) {
            if (!lift) return pose
            const dx = lift * 0.7, dy = -lift
            const mv = p => P(p.x + dx, p.y + dy)
            return { hip: mv(pose.hip), sh: P(pose.sh.x + dx + lift * 0.25, pose.sh.y + dy), rh: mv(pose.rh), lh: mv(pose.lh), rf: P(pose.rf.x + dx + 3, pose.rf.y + dy + 2), lf: P(pose.lf.x + dx - 2, pose.lf.y + dy + 4) }
        },
        harnessOf(pose) { return P(pose.hip.x, pose.hip.y + 2) },

        // ropes --------------------------------------------------------------------
        rope(ref, ...pts) {
            const el = this.$refs[ref]
            if (el) el.setAttribute('d', roundPath(pts))
        },
        // a slack rope between two points hangs in a curve ("belly")
        belly(ref, a, b, sag = 12) {
            const el = this.$refs[ref]
            if (!el) return
            if (!a || !b) { el.setAttribute('d', ''); return }
            const c = P((a.x + b.x) / 2, Math.max(a.y, b.y) + sag * 2)
            el.setAttribute('d', `M${f1(a.x)} ${f1(a.y)} Q${f1(c.x)} ${f1(c.y)} ${f1(b.x)} ${f1(b.y)}`)
        },
        // the belayer's sling to the anchor (~0.8m): slack unless pulled tight
        tether(ref, harness, anchor) {
            if (!harness || !anchor) return this.belly(ref)
            const d = Math.hypot(harness.x - anchor.x, harness.y - anchor.y)
            this.belly(ref, harness, anchor, Math.max(0, (34 - d) * 0.45))
        },
        drawPts(n) { return this.draws.slice(0, n).map(y => P(-3.8, y - 2.6)) },
        // Quickdraw movement, only when clipping: the draw being clipped (i)
        // pops and swings hard on its hanger as the rope goes in, the draws
        // already clipped below it move a bit (k: 1 → 0); otherwise still.
        popDraws(i, k, t = 0, clippedN = 0) {
            (this.$refs.drawRing || []).forEach((g, j) => {
                const pop = j === i ? k : 0
                g.setAttribute('transform', pop ? `translate(-3.8 -2.6) scale(${f1(1 + 0.6 * pop)}) translate(3.8 2.6)` : '')
                g.firstElementChild.setAttribute('opacity', f1(pop * 0.9))
            })
            ;(this.$refs.drawSwing || []).forEach((g, j) => {
                // moves only around a clip: the draw being clipped swings hard,
                // the draws already clipped below it twitch a little (the rope
                // pulls through them), each with its own phase; all settle (k → 0)
                let a = 0
                if (i >= 0 && k > 0) {
                    if (j === i) a = Math.sin(t * 14) * 22 * k
                    else if (j < i) a = Math.sin(t * 11 + j * 1.9) * 5 * k
                }
                g.setAttribute('transform', a ? `rotate(${f1(a)} -1.6 -23.7)` : '')
            })
        },
        // brake strand from the belay device (at the belayer's harness) down
        // into the spare-rope coil; null hides it
        spareRope(pose) {
            const pile = this.$refs.spare, br = this.$refs.brake
            if (!pile || !br) return
            if (!pose) { pile.setAttribute('opacity', '0'); br.setAttribute('d', ''); return }
            const p = this.sparePile
            pile.setAttribute('transform', `translate(${f1(p.x)} ${f1(p.y)})`)
            pile.setAttribute('opacity', '1')
            const dev = P(pose.hip.x + 8, pose.hip.y - 3)
            const c = P((dev.x + p.x) / 2 - 4, p.y - 2)
            br.setAttribute('d', `M${f1(dev.x)} ${f1(dev.y)} Q${f1(c.x)} ${f1(c.y)} ${f1(p.x + 4)} ${f1(p.y - 3)}`)
        },
        clearRopes() { ['rope1', 'rope2', 'rope3', 'tether1', 'tether2'].forEach(r => this.rope(r)) },

        // ---- scenes ---------------------------------------------------------------
        render(t) {
            if (this.variant === 'summit') this.renderSummit(t)
            else if (this.variant === 'safety') this.renderSafety(t)
            else if (this.variant === 'slackline') this.renderSlackline(t)
            else this.renderLead(t)
        },

        // walking out from behind the card (x > 0 is hidden) to the start spots
        walkIn(u, Tin, targets) {
            const p = smooth(clamp(u / Tin, 0, 1))
            const ground = this.H
            const poses = targets.map((x, i) => this.standPose(lerp(28 + i * 16, x, p), ground, p < 1 ? u * 8 + i * 1.5 : 0))
            poses.forEach((pose, i) => this.draw(i, pose, { mirror: true }))
            // they carry the rope between them
            this.belly('rope1', this.harnessOf(poses[0]), this.harnessOf(poses[1]), 10)
            ;['rope2', 'rope3', 'tether1', 'tether2'].forEach(r => this.rope(r))
        },

        // where the leader is in the lead schedule at time u
        leadAt(u) {
            const { plan } = this.leadPlan
            let seg = plan[plan.length - 1]
            for (const s of plan) if (u < s.t0 + s.dur) { seg = s; break }
            const p = clamp((u - seg.t0) / seg.dur, 0, 1)
            const done = plan.filter(s => s.type === 'clip' && s.draw >= 0 && (s.t0 + s.dur * 0.75) <= u).length
            if (seg.type === 'climb') return { y: lerp(seg.from, seg.to, p), clipped: done }
            return { y: seg.y, clipped: done, clip: seg, p }
        },

        // Lead climbing with a partner (outdoor rock, gym, ice), then a real
        // changeover: C leads, clipping every draw, clips the anchor and is
        // lowered; C unties; the belayer pulls the rope down through the anchor
        // and the draws so it drops into a pile; they swap places; the new
        // leader ties in from the pile and the other loads the belay device.
        renderLead(t) {
            const ice = this.variant === 'ice'
            const ground = this.H
            const spot = this.belaySpot, wallX = -18
            const Tin = 2.8
            if (!this.reduced && t < Tin) { this.spareRope(null); return this.walkIn(t, Tin, [wallX, spot]) }
            t -= Tin

            const { dur: Tc, top, bottom } = this.leadPlan
            const anchor = P(-9, 31)                           // bottom of the anchor ring
            const Tp = 1, Tl = (bottom - top) / 42
            const Tu = 1.1, Tpull = 2.6, Ts = 2.2, Ttie = 1.4
            const half = Tc + Tp + Tl + Tu + Tpull + Ts + Ttie
            const k = Math.floor(t / half) % 2, u = t % half
            const C = k, Bf = 1 - k                            // this half: C climbs, Bf belays
            const device = x => P(x + 9, ground - 40)
            const belayer = x => this.standPose(x, ground, 0, true)
            const tieHands = (pose, q) => {                    // hands working at the harness
                const w = Math.sin(q * 16) * 2
                pose.rh = P(pose.hip.x + 7, pose.hip.y - 4 + w); pose.lh = P(pose.hip.x + 4, pose.hip.y - 1 - w)
                return pose
            }
            const pile = (x, show) => {
                const el = this.$refs.pile
                if (el) { el.setAttribute('transform', `translate(${f1(x)} ${ground - 1})`); el.setAttribute('opacity', show ? '1' : '0') }
            }
            const T1 = Tc, T2 = T1 + Tp + Tl, T3 = T2 + Tu, T4 = T3 + Tpull, T5 = T4 + Ts
            this.rope('rope3'); this.rope('tether2')
            let belayPose = null

            if (u < T1) {
                // leading: rope from the device up through the clipped draws to
                // the knot — or, while clipping, up through the hand
                const s = this.leadAt(u)
                const pose = this.wallPose(s.y)
                let hand = null, pop = -1, popK = 0
                if (s.clip) {
                    const target = s.clip.draw >= 0 ? P(-5, this.draws[s.clip.draw] - 3) : anchor
                    const knot = P(pose.hip.x + 4, pose.hip.y - 2)
                    const hold = pose.lh
                    const p = s.p
                    if (p < 0.3) pose.lh = lp(hold, knot, smooth(p / 0.3))
                    else if (p < 0.75) { pose.lh = lp(knot, target, smooth((p - 0.3) / 0.45)); hand = pose.lh }
                    else pose.lh = lp(target, hold, smooth((p - 0.75) / 0.25))
                }
                // the swing starts when the rope goes in and settles over ~1s,
                // carrying on while the climber moves on
                for (const seg of this.leadPlan.plan) {
                    if (seg.type !== 'clip' || seg.draw < 0) continue
                    const since = u - (seg.t0 + seg.dur * 0.75)
                    if (since >= 0 && since < 1) { pop = seg.draw; popK = 1 - since }
                }
                this.popDraws(pop, popK, u, s.clipped)
                this.draw(C, pose, { tools: ice })
                belayPose = belayer(spot)
                this.draw(Bf, belayPose)
                const pts = [device(spot), ...this.drawPts(s.clipped)]
                if (s.clip && s.clip.draw < 0 && s.p >= 0.75) pts.push(anchor)
                if (hand) pts.push(hand)
                const hp = this.harnessOf(pose)
                if (popK > 0 && pts.length) {
                    // the rope swings out a little as it comes out of the clip
                    const last = pts[pts.length - 1]
                    pts.push(P((last.x + hp.x) / 2 - 6 - Math.sin(u * 12) * 6 * popK, (last.y + hp.y) / 2))
                }
                pts.push(hp)
                this.rope('rope1', ...pts)
                this.rope('rope2')
                pile(0, false)
            } else if (u < T2) {
                // lowered off the anchor
                const p = u < T1 + Tp ? 0 : (u - T1 - Tp) / Tl
                const y = top + (bottom - top) * smooth(p)
                const pose = p < 0.97 ? this.lowerPose(y, anchor, y / 7) : this.standPose(wallX, ground)
                this.popDraws(-1, 0, u, p < 0.97 ? this.draws.length : 0)
                this.draw(C, pose)
                belayPose = belayer(spot)
                this.draw(Bf, belayPose)
                this.rope('rope1', device(spot), ...this.drawPts(this.draws.length), anchor)
                this.rope('rope2', anchor, this.harnessOf(pose))
                pile(0, false)
            } else if (u < T3) {
                // C unties: hands at the knot, then the rope end drops
                const q = u - T2
                this.popDraws(-1, 0)
                this.draw(C, tieHands(this.standPose(wallX, ground), q))
                belayPose = belayer(spot)
                this.draw(Bf, belayPose)
                this.rope('rope1', device(spot), ...this.drawPts(this.draws.length), anchor)
                this.rope('rope2', anchor, q < Tu * 0.7 ? P(wallX, ground - 35) : P(wallX + 4, ground - 2))
                pile(0, false)
            } else if (u < T4) {
                // the belayer pulls the rope down through the anchor and the draws
                const q = (u - T3) / Tpull
                const pull = this.standPose(spot, ground, 0, true)
                const arm = Math.sin(q * 30)
                pull.rh = P(spot + 6, ground - 60 + arm * 8); pull.lh = P(spot + 3, ground - 44 - arm * 8)
                belayPose = pull
                this.draw(C, this.standPose(wallX - 4, ground))
                this.draw(Bf, pull)
                const path = [device(spot), ...this.drawPts(this.draws.length), anchor, P(wallX + 4, ground - 2)]
                const left = this.polyLen(path) * (1 - smooth(q))
                this.popDraws(-1, 0, u, q < 0.85 ? this.draws.length : 0)          // rope running through
                this.rope('rope1', ...(left > 8 ? this.subPath(path, 0, left) : []))
                this.rope('rope2')
                pile(spot + 4, q > 0.55)
            } else if (u < T5) {
                // swap places
                const p = smooth((u - T4) / Ts)
                const walk = (u - T4) * 9
                this.draw(C, this.standPose(lerp(wallX - 4, spot, p), ground, walk), { mirror: true })
                this.draw(Bf, this.standPose(lerp(spot, wallX, p), ground, walk + 1.5))
                this.rope('rope1'); this.rope('rope2')
                pile(spot + 4 + 14 * p, true)
            } else {
                // Bf ties in from the pile; C loads the rope into the device
                const q = u - T5
                this.draw(Bf, tieHands(this.standPose(wallX, ground), q))
                const cp = this.standPose(spot, ground, 0, true)
                cp.rh = P(spot + 9, ground - 36 + Math.sin(q * 12) * 2)
                belayPose = cp
                this.draw(C, cp)
                const pileX = spot + 18
                this.rope('rope1', device(spot), P(pileX, ground - 3), P(wallX, ground - 35))
                this.rope('rope2')
                pile(pileX, true)
            }
            this.rope('tether1')
            this.spareRope(belayPose)
        },

        // Tech tips — practising a lead fall: climb ~1m above a clipped draw, let
        // go on purpose, soft catch (the belayer, tethered to a ground anchor, is
        // pulled up until the sling comes tight), hang, climb back up, again.
        renderSafety(t) {
            const ground = this.H
            const spot = this.belaySpot
            const Tin = 2.8
            if (!this.reduced && t < Tin) { this.spareRope(null); return this.walkIn(t, Tin, [-18, spot]) }
            t -= Tin
            const n = Math.max(2, Math.min(this.draws.length, 3))
            const di = n - 1                                 // the practice draw
            const D = this.draws[di]
            const device = P(spot + 9, ground - 40)
            const yHang = D + 64
            const yTop = D - 42                              // ~1m above the draw
            const v = 16
            const Tclimb = (yHang - yTop) / v, Tpause = 0.8, Tfall = 2.2, Thang = 1.2
            const cycle = Tclimb + Tpause + Tfall + Thang
            const u = t % cycle
            let pose, lift = 0
            if (u < Tclimb) {
                pose = this.climbPose(yHang - (yHang - yTop) * (u / Tclimb))
            } else if (u < Tclimb + Tpause) {
                pose = this.climbPose(yTop)
                pose.lh = P(-8, yTop - 30)                     // ready to let go
            } else if (u < Tclimb + Tpause + Tfall) {
                const tt = u - Tclimb - Tpause
                const freeT = Math.sqrt(2 * (yHang + 10 - yTop) / GRAVITY)
                let y
                if (tt < freeT) y = yTop + 0.5 * GRAVITY * tt * tt
                else {
                    const s = tt - freeT
                    y = yHang + 10 * Math.exp(-3.4 * s) * Math.cos(7 * s) - 10 * (1 - Math.exp(-3.4 * s))
                    // soft catch, limited by the belayer's tether to the ground anchor
                    lift = Math.min(14, Math.max(0, 22 * Math.exp(-2.2 * s) * Math.sin(Math.PI * Math.min(1, s / 0.7))))
                }
                const air = clamp(tt / 0.25, 0, 1)
                pose = {
                    hip: P(-19 - air * 6, y), sh: P(-21 - air * 8, y - 25),
                    lh: P(-27 - air * 4, y - 46), rh: P(-17 - air * 4, y - 48),
                    lf: P(-5, y + 30), rf: P(-3, y + 24),
                }
            } else {
                pose = this.lowerPose(yHang, P(-5, D - 3), 0)
            }
            this.popDraws(-1, 0, t, di + 1)
            this.draw(0, pose)
            const bp = this.lifted(this.standPose(spot, ground, 0, true), lift)
            this.draw(1, bp)
            this.rope('rope1', P(device.x + lift * 0.7, device.y - lift), ...this.drawPts(di + 1), this.harnessOf(pose))
            this.rope('rope2'); this.rope('rope3'); this.rope('tether2')
            this.rope('tether1')
            this.spareRope(bp)
        },

        // Other articles — a slackliner walks the line between two trees under
        // the card, arms out for balance, turns round at the end and walks back.
        renderSlackline(t) {
            const { ax, bx, y: ly } = this.slack
            const span = bx - ax - 40
            const v = 16, Tturn = 1.2
            const leg = span / v
            const cycle = 2 * (leg + Tturn)
            const u = t % cycle
            let x, dir, walking
            if (u < leg) { x = ax + 20 + v * u; dir = 1; walking = true }
            else if (u < leg + Tturn) { x = bx - 20; dir = u - leg < Tturn / 2 ? 1 : -1; walking = false }
            else if (u < 2 * leg + Tturn) { x = bx - 20 - v * (u - leg - Tturn); dir = -1; walking = true }
            else { x = ax + 20; dir = u - 2 * leg - Tturn < Tturn / 2 ? -1 : 1; walking = false }
            const sag = 7 + 1.5 * Math.sin(t * 6)
            const lineAt = px => {                              // the line sags under the walker
                const s = clamp(Math.abs(px - x) / 60, 0, 1)
                return ly + sag * (1 - s * s)
            }
            const pts = [P(ax, ly)]
            for (let i = 1; i < 24; i++) { const px = lerp(ax, bx, i / 24); pts.push(P(px, lineAt(px))) }
            pts.push(P(bx, ly))
            this.$refs.slack && this.$refs.slack.setAttribute('d', poly(...pts))
            const wob = Math.sin(t * 2.6) + 0.4 * Math.sin(t * 7.1)
            const walk = walking ? t * 5 : 0
            const fy = lineAt(x)
            const hip = P(x + wob * 1.5, fy - 37)
            const pose = {
                hip, sh: P(hip.x + wob * 3, hip.y - 25),
                lh: P(hip.x - 16, hip.y - 28 + wob * 7), rh: P(hip.x + 16, hip.y - 28 - wob * 7),
                lf: P(x - 3 - Math.sin(walk) * 5, lineAt(x - 3 - Math.sin(walk) * 5)),
                rf: P(x + 3 + Math.sin(walk) * 5, lineAt(x + 3 + Math.sin(walk) * 5)),
            }
            this.draw(0, pose, { flatShoes: true, mirror: dir < 0 })
        },

        // Mountaineering, as a real two-person team does it:
        //  0. both walk out from behind the card, carrying the rope
        //  1. the leader climbs placing cams in the crack, belayed from the bottom
        //     (the belayer tethered to a ground anchor)
        //  2. the leader tops out, builds a belay on the rock horn (tethered to it)
        //     and belays the second up — the rope runs through the anchor
        //  3. the second climbs and removes the cams; 4. tops out
        //  5. both walk to the summit, rope hanging in a curve between them
        //  6. flag, then the ascent is verified on climbing.ge (phone + speech)
        //  7. walk back to the horn, thread the double rope, rappel one after the
        //     other, and pull the rope down — the two strands keep their colours
        renderSummit(t) {
            const H = this.H, pk = this.mountain.peak
            const slopeY = x => this.ridgeY(x)
            const x0 = this.mountain.base + 6
            const xb = x0 + 16                                  // leader's belay stance
            const spot = this.belaySpot
            const ground = H, bottom = H - 37, top = 30
            const pieces = this.pieces
            const rap = this.rapAnchor
            const rapPt = P(rap.x - 7, rap.y)                   // carabiner on the horn sling
            const edge = P(-2, 1)
            const Tin = 2.6, Tc = (bottom - top) / 20, Tm = 1.2, Tset = 1.6, Tw = 3.4
            const Tf = 7, Tback = 3, Trs = 1, Tr1 = (H - 40) / 36, Tr2 = Tr1, Tpull = 1.8, Tend = 1
            const leaderEnd = P(pk.x + 40, slopeY(pk.x + 40))
            const secondEnd = P(pk.x - 26, slopeY(pk.x - 26))
            const T0 = Tin, T1 = T0 + Tc, T2 = T1 + Tm + Tset, T3 = T2 + Tc, T4 = T3 + Tm, T5 = T4 + Tw
            const T6 = T5 + Tf, T7 = T6 + Tback, T8 = T7 + Trs, T9 = T8 + Tr1, T10 = T9 + Tr2, T11 = T10 + Tpull
            const cycle = T11 + Tend
            const u = this.reduced ? T5 + 4 : t % cycle

            const mantle = p => {
                const x = lerp(-18, x0, p), y = lerp(top, slopeY(x0) - 37, p)
                const pose = this.standPose(x, slopeY(x) + (1 - p) * 30, 0)
                pose.hip = P(x, y); pose.sh = P(x + 4 - p * 3, y - 25)
                pose.rh = P(lerp(-2, x + 8, p), lerp(4, y - 6, p)); pose.lh = P(lerp(-3, x + 2, p), lerp(-2, y + 4, p))
                return pose
            }
            const walker = (x, walk, moving) => {
                const pose = this.standPose(x, slopeY(x), walk)
                pose.sh = P(x + (moving ? 5 : 1), pose.hip.y - 24)
                const step = d => P(x + d, slopeY(x + d))
                pose.rf = step(4 + Math.sin(walk) * 6); pose.lf = step(-4 - Math.sin(walk) * 6)
                return pose
            }
            const topBelay = () => {                            // facing the edge (mirrored), brake hand low
                const pose = this.standPose(xb, slopeY(xb), 0, true)
                pose.rh = P(xb + 8, pose.hip.y + 4)
                return pose
            }
            const H_ = p => this.harnessOf(p)
            const pc = y => P(-10, y + 15)
            const visible = new Array(pieces.length).fill(false)
            const bubble = this.$refs.bubble

            let L, S2, Lopts = { flatShoes: true }, Sopts = { flatShoes: true }
            let r1 = [], r3 = [], belly = null, t1 = null, t2 = null, speech = 0, groundBelayer = null
            if (u < T0) {
                // 0. walk out from behind the card
                const p = smooth(u / Tin)
                L = this.standPose(lerp(30, -18, p), ground, u * 8); Lopts = { mirror: true }
                S2 = this.standPose(lerp(46, spot, p), ground, u * 8 + 1.5); Sopts = { mirror: true }
                belly = [H_(L), H_(S2)]
            } else if (u < T1) {
                const y = bottom - (bottom - top) * ((u - T0) / Tc)
                L = this.climbPose(y); Lopts = {}
                pieces.forEach((py, i) => {
                    if (y < py + 26) visible[i] = true
                    if (y < py + 26 && y > py + 14) L.lh = P(-6, py + 1)        // placing the cam
                })
                S2 = this.standPose(spot, ground, 0, true); Sopts = {}
                r1 = [P(spot + 9, ground - 40), ...pieces.filter(py => y < py + 26).map(pc), H_(L)]
                groundBelayer = S2
            } else if (u < T2) {
                const q = u - T1
                if (q < Tm) L = mantle(smooth(q / Tm))
                else {
                    const p = smooth((q - Tm) / Tset)
                    L = walker(lerp(x0, xb, p), (q - Tm) * 7, p < 1)
                    if (p > 0.6) { L = topBelay(); Lopts = { flatShoes: true, mirror: true }; t1 = [H_(L), rapPt] }
                }
                visible.fill(true)
                S2 = this.standPose(spot, ground, 0, true); Sopts = {}
                r1 = [P(spot + 9, ground - 40), ...pieces.map(pc), edge, H_(L)]
                groundBelayer = S2
            } else if (u < T3) {
                // 3. second climbs, belayed through the anchor; removes the cams
                L = topBelay(); Lopts = { flatShoes: true, mirror: true }
                const y = bottom - (bottom - top) * ((u - T2) / Tc)
                S2 = this.climbPose(y); Sopts = {}
                const left = []
                pieces.forEach((py, i) => {
                    if (y > py + 26) { visible[i] = true; left.push(py) }
                    if (y <= py + 36 && y > py + 26) S2.rh = P(-6, py + 2)          // taking it out
                })
                // brake strand: leader's hand → anchor carabiner → edge → cams → second
                r1 = [P(2 * xb - (xb + 8), L.hip.y + 4), rapPt, edge, ...left.slice().reverse().map(pc), H_(S2)]
                t1 = [H_(L), rapPt]
            } else if (u < T4) {
                L = topBelay(); Lopts = { flatShoes: true, mirror: true }
                S2 = mantle(smooth((u - T3) / Tm))
                r1 = [P(2 * xb - (xb + 8), L.hip.y + 4), rapPt, H_(S2)]
                t1 = [H_(L), rapPt]
            } else if (u < T6) {
                const p = clamp((u - T4) / Tw, 0, 1), walk = (u - T4) * 7
                L = walker(lerp(xb, leaderEnd.x, smooth(p)), walk, p < 1)
                S2 = walker(lerp(x0, secondEnd.x, smooth(p)), walk + 1.5, p < 1)
                if (u > T5) {
                    const q = u - T5
                    S2.rh = P(S2.hip.x + 12, S2.hip.y - 44)            // arm up to the flag
                    if (q > 1 && q < 6) {
                        // the leader holds up the phone: climbing.ge verifies the ascent
                        L.rh = P(L.hip.x + 10, L.hip.y - 30); Lopts = { flatShoes: true, phone: true }
                        speech = clamp((q - 1.5) / 2.5, 0, 1)
                    } else L.lh = P(L.hip.x - 10, L.hip.y - 44)
                }
                belly = [H_(L), H_(S2)]
            } else if (u < T8) {
                const p = clamp((u - T6) / Tback, 0, 1), walk = (u - T6) * 7
                L = walker(lerp(leaderEnd.x, rap.x + 10, smooth(p)), walk, p < 1); Lopts = { flatShoes: true, mirror: true }
                S2 = walker(lerp(secondEnd.x, rap.x + 26, smooth(p)), walk + 1.5, p < 1); Sopts = { flatShoes: true, mirror: true }
                if (u > T7) {
                    const q = (u - T7) / Trs
                    r1 = [rapPt, edge, P(edge.x - 2, edge.y + 44 * q)]
                    r3 = [P(rapPt.x, rapPt.y + 1), P(edge.x - 1, edge.y + 2), P(edge.x + 1, edge.y + 44 * q)]
                } else belly = [H_(L), H_(S2)]                    // still roped together
            } else if (u < T10) {
                const first = u < T9
                const q = first ? (u - T8) / Tr1 : (u - T9) / Tr2
                const y = lerp(28, bottom, smooth(q))
                const rapper = q < 0.97 ? this.lowerPose(y, edge, y / 6) : this.standPose(-18, ground)
                const dev = P(rapper.hip.x + 4, rapper.hip.y - 2)
                r1 = [rapPt, edge, dev, P(dev.x - 2, ground - 2)]
                r3 = [P(rapPt.x, rapPt.y + 1), P(edge.x + 1, edge.y + 2), P(dev.x + 1, dev.y), P(dev.x + 1, ground - 2)]
                if (first) { L = rapper; Lopts = {}; S2 = walker(rap.x + 26, 0, false); Sopts = { flatShoes: true, mirror: true } }
                else { L = this.standPose(-52, ground); Lopts = {}; S2 = rapper; Sopts = {} }
            } else {
                // pull the orange strand: it runs up through the anchor, the blue
                // strand follows over the top and down — colours stay separate
                const q = smooth(clamp((u - T10) / Tpull, 0, 1))
                L = this.standPose(-52, ground); Lopts = {}
                S2 = this.standPose(-24, ground, 0, true); Sopts = {}
                S2.rh = P(-20, ground - 62 + Math.sin(q * 30) * 6)
                // the knot joining the two ropes starts at the anchor and travels
                // down toward the puller: orange below the knot, blue above it
                // and over the anchor, the blue end shortening as it's pulled up
                const path = [P(-22, ground - 60), edge, rapPt, P(edge.x + 1, edge.y + 2), P(-14, ground - 2)]
                const total = this.polyLen(path), dA = this.polyLen(path.slice(0, 3))
                const pulled = total * q
                const knot = Math.max(0, dA - pulled), left = total - pulled
                r1 = knot > 0 ? this.subPath(path, 0, Math.min(knot, left)) : []
                r3 = left > knot ? this.subPath(path, knot, left) : []
            }

            ;(this.$refs.piece || []).forEach((g, i) => g.setAttribute('opacity', visible[i] && u < T4 && u >= T0 ? '1' : '0'))

            const fade = u > T11 ? clamp(1 - (u - T11) / Tend, 0, 1) : 1
            this.draw(0, L, Object.assign({ opacity: fade }, Lopts))
            this.draw(1, S2, Object.assign({ opacity: fade }, Sopts))
            if (belly) this.belly('rope1', belly[0], belly[1], 12)
            else this.rope('rope1', ...r1)
            this.rope('rope2'); this.rope('rope3', ...r3)
            this.tether('tether1', t1 && t1[0], t1 && t1[1])
            this.tether('tether2', t2 && t2[0], t2 && t2[1])
            this.spareRope(groundBelayer)
            ;['rope1', 'rope3'].forEach(r => this.$refs[r] && this.$refs[r].setAttribute('opacity', f1(fade)))

            // flag on the summit — taller than the climbers, stays up
            const flag = this.$refs.flag
            if (flag) {
                const fp = u > T5 ? clamp((u - T5) / 0.6, 0, 1) : 0
                const tip = P(pk.x, pk.y - 74 * fp)
                this.$refs.flagPole.setAttribute('d', poly(P(pk.x, pk.y + 2), tip))
                const wave = Math.sin(t * 4)
                this.$refs.flagCloth.setAttribute('transform',
                    `translate(${f1(tip.x + 1)} ${f1(tip.y)}) skewY(${f1(wave * 6)}) scale(${f1(1.75 * fp * (0.92 + 0.08 * Math.cos(t * 4)))} ${f1(1.75 * fp)})`)
                flag.setAttribute('opacity', f1(fp * fade))
            }

            // speech bubble: the verification text types out like speech
            if (bubble) {
                const show = speech > 0
                bubble.setAttribute('opacity', show ? '1' : '0')
                if (show) {
                    const msg = this.$t('guide.side_scene.ascent_verified')
                    const shown = msg.slice(0, Math.ceil(msg.length * speech))
                    const w = Math.max(30, msg.length * 4.9 + 14)
                    const head = P(L.hip.x + 3, L.hip.y - 40)
                    const bx = Math.min(head.x - w * 0.35, this.W - w - 2), by = head.y - 30
                    this.$refs.bubbleBox.setAttribute('x', f1(bx)); this.$refs.bubbleBox.setAttribute('y', f1(by))
                    this.$refs.bubbleBox.setAttribute('width', f1(w))
                    this.$refs.bubbleTail.setAttribute('d', `M${f1(head.x - 3)} ${f1(by + 16)}l2 8 5-8z`)
                    this.$refs.bubbleText.setAttribute('x', f1(bx + 7)); this.$refs.bubbleText.setAttribute('y', f1(by + 9))
                    this.$refs.bubbleText.textContent = shown
                }
            }
        },
    },
}
</script>
