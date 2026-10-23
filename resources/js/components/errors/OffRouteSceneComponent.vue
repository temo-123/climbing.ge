<template>
    <!-- Lead climber on a rock face (simulation in services/fun/leadClimbScene.js):
         climbs calmly and clips the quickdraws, can't hold the crux in the middle
         of the wall, takes a quiet fall onto the rope (belayer lifted a little),
         hangs, is lowered off and the loop starts again. Drawn in a simple flat style: big rock
         facets, a few chunky holds, clear silhouettes. -->
    <svg class="cg-scene" :viewBox="`0 0 ${scene.width} ${scene.height}`" role="img" :aria-label="$t('error.404.off_route')">
        <defs>
            <linearGradient id="cgSky" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#cfe6ef" />
                <stop offset="1" stop-color="#eef3f1" />
            </linearGradient>
            <linearGradient id="cgFaceShade" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0" stop-color="#15212b" stop-opacity=".12" />
                <stop offset=".5" stop-color="#15212b" stop-opacity="0" />
                <stop offset="1" stop-color="#15212b" stop-opacity=".18" />
            </linearGradient>
            <clipPath id="cgCard">
                <rect :width="scene.width" :height="scene.height" rx="22" />
            </clipPath>
        </defs>

        <g clip-path="url(#cgCard)">
            <!-- sky and a distant ridge -->
            <rect :width="scene.width" :height="scene.height" fill="url(#cgSky)" />
            <path d="M0 120l40-30 30 18 40-40 34 30 30-20 50 36 40-26 46 32 50-34V200H0z" fill="#b9cdd4" />

            <!-- the rock face: a few big flat facets -->
            <path d="M34 40l70-26 90 6 80-14 60 30 10 500H22z" fill="#c8bba6" />
            <path d="M34 40l70-26 30 120-60 70-40-20z" fill="#d6cbb8" />
            <path d="M194 20l80-14 60 30-40 110-90-20z" fill="#bcae96" />
            <path d="M104 234l90-30 60 80-40 110-120-40z" fill="#d1c5b1" />
            <path d="M254 284l90-40-10 240-80-60z" fill="#b3a58c" />
            <path d="M40 330l70 10 30 120-110 40z" fill="#bfb19b" />
            <rect :width="scene.width" :height="scene.height" fill="url(#cgFaceShade)" />
            <!-- two cracks -->
            <path d="M84 30c10 50-14 90 0 150s-10 100 4 170" fill="none" stroke="#6e6252" stroke-width="2.4" stroke-linecap="round" stroke-opacity=".55" />
            <path d="M286 60c-12 60 14 110 0 170" fill="none" stroke="#6e6252" stroke-width="2" stroke-linecap="round" stroke-opacity=".5" />

            <!-- the route: chunky holds -->
            <ellipse v-for="(h, i) in holds" :key="'h' + i" :cx="h.x" :cy="h.y" rx="6.5" ry="4.5" :fill="holdColors[i % holdColors.length]" />

            <!-- ground -->
            <path :d="`M0 ${scene.ground - 4}c60-8 120-2 180-6s120 2 180-2V${scene.height}H0z`" fill="#7d8f63" />
            <path :d="`M0 ${scene.ground + 8}c80-6 160 2 240-4s80 2 120 0V${scene.height}H0z`" fill="#6a7c52" />
        </g>

        <!-- lowering anchor at the top of the route: two bolts, chains, a ring -->
        <g :transform="`translate(${anchor.x} ${anchor.y})`">
            <circle cx="-9" cy="0" r="3.2" fill="#6c757d" />
            <circle cx="9" cy="-3" r="3.2" fill="#6c757d" />
            <path d="M-9 2l7 14M9 -1l-7 17" fill="none" stroke="#9aa3ab" stroke-width="2" stroke-dasharray="2.4 1.6" />
            <circle cx="0" cy="20" r="4.4" fill="none" stroke="#7b858e" stroke-width="2.4" />
        </g>

                <!-- quickdraws: bolt, sling, carabiner the rope runs through -->
        <g v-for="(b, i) in bolts" :key="'b' + i" :transform="`translate(${b.x} ${b.y})`">
            <!-- bolt hanger + a long (18cm) quickdraw sling -->
            <circle cx="0" cy="-27" r="3" fill="#6c757d" />
            <ellipse cx="0" cy="-22" rx="3" ry="4" fill="none" stroke="#9aa3ab" stroke-width="1.6" />
            <!-- sling + carabiner swing from the hanger -->
            <g ref="swing">
                <rect x="-2.2" y="-19" width="4.4" height="14" rx="2" fill="#2f9e8f" />
                <!-- carabiner: pops (scale + white ring) the moment the rope is clipped in -->
                <g ref="draws">
                    <circle class="cg-scene__clip-ring" cx="0" cy="-1" r="7" fill="none" stroke="#fff" stroke-width="2" opacity="0" />
                    <ellipse cx="0" cy="-1" rx="4.2" ry="5.2" fill="none" stroke="#7b858e" stroke-width="2" />
                </g>
            </g>
        </g>

        <!-- belayer (side view, facing the wall) -->
        <!-- the belayer's spare rope coiled on the ground behind them; the brake
             strand runs from the belay device down into it -->
        <path :transform="`translate(${belay.x + 22} ${belay.y - 1})`" d="M-11 0c-6-1-9-4-4-5.5s15-1 17 1-3 4.5-11 4.5-12-2-9-4.5 14-3 17-1" fill="none" stroke="#f07a3e" stroke-width="2.6" stroke-linecap="round" />
        <path ref="brake" fill="none" stroke="#f07a3e" stroke-width="2.4" stroke-linecap="round" />

        <g ref="belayer">
            <!-- mirrored: stands right of the route, facing it -->
            <g :transform="`translate(${belay.x} ${belay.y}) scale(-1 1)`" stroke-linecap="round" stroke-linejoin="round">
                <path d="M-3 -32l-4 16 2 16M2 -32l5 15-1 17" fill="none" stroke="#2b3440" stroke-width="7" />
                <path d="M-8 0h7M3 0h8" stroke="#2b3440" stroke-width="5" />
                <path d="M-9 -32c-1-11 0-20 3-27h12c3 7 3 16 1 27z" fill="#c4532d" />
                <path d="M3 -54l9 9 5-5M-2 -54l5 14 8 2" fill="none" stroke="#c4532d" stroke-width="5" />
                <rect x="-9" y="-36" width="18" height="5" rx="2.4" fill="#ffc233" />
                <circle cx="1" cy="-66" r="7.5" fill="#e8b58f" />
                <path d="M-7.8 -67a8.8 9 0 0 1 17.6 0z" fill="#7a5bd6" />
            </g>
        </g>


        <!-- rope behind the climber (seen from behind, the tie-in is on the
             front of the harness): up through the quickdraws to the knot -->
        <path ref="ropeShadow" fill="none" stroke="#7a2e1d" stroke-width="4.4" stroke-linecap="round" stroke-linejoin="round" />
        <path ref="rope" fill="none" stroke="#f07a3e" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" />
        <circle ref="knot" r="3.2" fill="#f07a3e" stroke="#7a2e1d" stroke-width="1.4" />

        <!-- the climber (from behind): simple silhouette, yellow helmet -->
        <g ref="climber" stroke-linecap="round" stroke-linejoin="round">
            <path ref="legL" fill="none" stroke="#2b3440" stroke-width="7.5" />
            <path ref="legR" fill="none" stroke="#2b3440" stroke-width="7.5" />
            <path ref="shoeL" stroke="#d1495b" stroke-width="6" />
            <path ref="shoeR" stroke="#d1495b" stroke-width="6" />
            <path ref="armL" fill="none" stroke="#1f93b0" stroke-width="6" />
            <path ref="armR" fill="none" stroke="#1f93b0" stroke-width="6" />
            <path ref="torso" fill="#1f93b0" />
            <path ref="harness" stroke="#ffc233" stroke-width="5" fill="none" />
            <circle ref="handL" r="3.4" fill="#e8b58f" />
            <circle ref="handR" r="3.4" fill="#e8b58f" />
            <circle ref="hair" r="7.4" fill="#5a3d2b" />
            <path ref="helmet" fill="#ffc233" />
        </g>
    </svg>
</template>

<script>
    import { SCENE, HOLDS, BOLTS, ANCHOR, BELAY, buildFrames, bodyPoints, ropePath } from '../../services/fun/leadClimbScene.js'

    const f1 = n => n.toFixed(1)
    const line = (...pts) => 'M' + pts.map(p => `${f1(p.x)} ${f1(p.y)}`).join(' L')

    export default {
        name: 'OffRouteScene',
        data() {
            return {
                scene: SCENE,
                holds: HOLDS,
                bolts: BOLTS,
                anchor: ANCHOR,
                belay: BELAY,
                holdColors: ['#ffc233', '#ff6a3d', '#3fb59a', '#8a63e0'],
            }
        },
        mounted() {
            this.frames = buildFrames()
            const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches
            if (reduced) {
                // calm frame: hanging on the rope, a few seconds after the catch
                const fall = this.frames.findIndex(f => f.stance === 'hang')
                this.draw(this.frames[Math.min(this.frames.length - 1, fall + 4 * 60)])
                return
            }
            this.start = null
            const n = this.frames.length
            const tick = (now) => {
                // start the clock on the first frame: rAF timestamps can be a
                // little earlier than performance.now() at mount (negative index)
                if (this.start === null) this.start = now
                const i = Math.max(0, Math.floor(((now - this.start) / 1000) * 60)) % n
                this.draw(this.frames[i])
                this.raf = requestAnimationFrame(tick)
            }
            this.raf = requestAnimationFrame(tick)
        },
        beforeUnmount() {
            cancelAnimationFrame(this.raf)
        },
        methods: {
            // test hook: render a given time (seconds) of the loop
            drawAt(sec) {
                cancelAnimationFrame(this.raf)
                this.draw(this.frames[Math.floor(sec * 60) % this.frames.length])
            },
            draw(f) {
                const r = this.$refs
                if (!r.climber) return
                const b = bodyPoints(f)
                const set = (el, attr, v) => el.setAttribute(attr, v)

                // rope + belayer (lifted toward the first bolt when the rope comes tight)
                const rope = ropePath(f)
                set(r.rope, 'd', rope); set(r.ropeShadow, 'd', rope)
                // belayer pulled along the rope toward the first bolt, leaning into the wall
                // brake strand: belay device (moves with the belayer) → spare-rope coil
                {
                    const dx = BELAY.x - 11 + (f.bx || 0), dy = BELAY.y - 36 + (f.by || 0)
                    const px = BELAY.x + 24, py = BELAY.y - 4
                    set(r.brake, 'd', `M${f1(dx)} ${f1(dy)} Q${f1((dx + px) / 2 + 6)} ${f1(Math.max(dy, py) + 6)} ${f1(px)} ${f1(py)}`)
                }
                set(r.belayer, 'transform', `translate(${f1(f.bx || 0)} ${f1(f.by || 0)}) rotate(${f1(-(f.brot || 0))} ${BELAY.x} ${BELAY.y})`)

                set(r.climber, 'opacity', f1(f.opacity))
                // the quickdraw that was just clipped pops, so the clip reads clearly
                // draws move only around a clip: the one being clipped swings on
                // its hanger, the ones already clipped below it move a little
                const tt = performance.now() / 1000
                ;(r.draws || []).forEach((g, i) => {
                    const pop = i === f.clipped - 1 ? (f.flash || 0) : 0
                    const k = f.flash || 0
                    // the clipped draw swings hard; the ones below it move a bit
                    const sway = !k ? 0 : (pop ? Math.sin(tt * 14) * 22 * pop
                        : (i < f.clipped - 1 ? Math.sin(tt * 11 + i * 1.9) * 5 * k : 0))
                    if (r.swing && r.swing[i]) set(r.swing[i], 'transform', sway ? `rotate(${f1(sway)} 0 -22)` : '')
                    set(g, 'transform', pop ? `translate(0 -1) scale(${f1(1 + 0.6 * pop)}) translate(0 1)` : '')
                    set(g.firstElementChild, 'opacity', f1(pop * 0.9))
                })
                // tie-in knot at the harness, where the rope ends
                set(r.knot, 'cx', f1(f.hip.x)); set(r.knot, 'cy', f1(f.hip.y + 2))
                set(r.knot, 'opacity', f1(f.opacity))
                // legs: hip → knee → foot, shoes point outward
                set(r.legL, 'd', line(b.lhip, b.ll.joint, b.ll.end))
                set(r.legR, 'd', line(b.rhip, b.rl.joint, b.rl.end))
                set(r.shoeL, 'd', line(b.ll.end, { x: b.ll.end.x - 4, y: b.ll.end.y + 1 }))
                set(r.shoeR, 'd', line(b.rl.end, { x: b.rl.end.x + 4, y: b.rl.end.y + 1 }))
                // torso as a rounded tapered shape from hips to shoulders
                const c = Math.cos(b.rad), s = Math.sin(b.rad)
                const off = (p, dx) => ({ x: p.x + c * dx, y: p.y + s * dx })
                const hl = off(f.hip, -7.5), hr = off(f.hip, 7.5), sl = off(b.sh, -10), sr = off(b.sh, 10)
                set(r.torso, 'd', `M${f1(hl.x)} ${f1(hl.y)} L${f1(sl.x)} ${f1(sl.y)} Q${f1(b.sh.x - s * 4)} ${f1(b.sh.y - c * 4)} ${f1(sr.x)} ${f1(sr.y)} L${f1(hr.x)} ${f1(hr.y)}z`)
                set(r.harness, 'd', line(off(f.hip, -8.5), off(f.hip, 8.5)))
                // arms: shoulder → elbow → hand
                set(r.armL, 'd', line(b.ls, b.la.joint, b.la.end))
                set(r.armR, 'd', line(b.rs, b.ra.joint, b.ra.end))
                set(r.handL, 'cx', f1(b.la.end.x)); set(r.handL, 'cy', f1(b.la.end.y))
                set(r.handR, 'cx', f1(b.ra.end.x)); set(r.handR, 'cy', f1(b.ra.end.y))
                // head (back of the head + helmet)
                set(r.hair, 'cx', f1(b.head.x)); set(r.hair, 'cy', f1(b.head.y))
                set(r.helmet, 'd', `M${f1(b.head.x - 8.4)} ${f1(b.head.y + 1)}a8.4 8.8 0 0 1 16.8 0z`)
                set(r.helmet, 'transform', `rotate(${f1(f.lean)} ${f1(b.head.x)} ${f1(b.head.y)})`)
            },
        },
    }
</script>
