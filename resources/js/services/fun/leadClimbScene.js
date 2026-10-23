// Lead-climbing story for the 404 page.
//
// The whole loop is simulated once at 60 fps into an array of poses:
//   climb (feet up, stand up, reach; weight shifts before each foot move) →
//   clip each quickdraw → run it out above the last clip → pull up rope to
//   clip the next bolt and slip mid-clip → fall at real gravity (twice the
//   distance above the bolt + slack) → soft catch on the dynamic rope (one
//   small bounce, belayer lifted) → hang → lowered to the ground → reset.
// The component then just plays the frames back in a loop. Limbs are drawn
// with two-bone IK, so hands and feet stay on the holds while the body moves.

export const SCENE = {
    width: 360,
    height: 540,
    ground: 500,
}

const FPS = 60
const HIP_TO_SHOULDER = 26
const ARM = [15, 15]
const LEG = [17, 17]

// The line follows the rock a little (ROUTE_X(y) = the bolt line at height y)
// but the bolts are nearly in a straight line, so the rope runs straight up
// through them. The holds are just RIGHT of the bolt line — the climber moves
// beside the bolts, so each quickdraw hangs by the climber's left side (never
// hidden behind the body) and is clipped with the left hand, like on a real
// sport route. The first bolt is ~3m up; above the last draw there's only
// the lowering anchor — the climber falls trying to clip it.
export const ROUTE_X = y => 172 + 12 * Math.sin((SCENE.ground - y) / 90)
const HOLDS_OFFSET = 22

export const HOLDS = (() => {
    const out = []
    for (let k = 0; k < 19; k++) {
        const y = 486 - k * 21
        const side = k % 2 ? 1 : -1
        out.push({ x: ROUTE_X(y) + HOLDS_OFFSET + side * (15 + ((k * 7) % 5)), y, side })
    }
    return out
})()

export const BOLTS = [384, 322, 262, 204].map((y, i) => ({ x: ROUTE_X(y) + (i % 2 ? -2 : 2), y }))

// lowering anchor at the top of the route: two bolts, chains, one ring
export const ANCHOR = { x: ROUTE_X(102), y: 102 }

// the belayer stands right of the route, a step back from the wall
export const BELAY = { x: ROUTE_X(SCENE.ground) + 82, y: SCENE.ground }
const DEVICE = { x: BELAY.x - 11, y: SCENE.ground - 36 }
// direction the belayer is pulled when the rope comes tight (toward bolt 1)
const PULL = (() => { const dx = BOLTS[0].x - DEVICE.x, dy = BOLTS[0].y - DEVICE.y, d = Math.hypot(dx, dy); return { x: dx / d, y: dy / d } })()

const FALL_ABOVE_BOLT = 3          // last draw clipped; the climber then falls trying to clip the anchor

// ---- small helpers ---------------------------------------------------------
const lerp = (a, b, t) => a + (b - a) * t
const ease = t => (t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2)
const P = (x, y) => ({ x, y })
const lp = (a, b, t) => P(lerp(a.x, b.x, t), lerp(a.y, b.y, t))
const clone = o => JSON.parse(JSON.stringify(o))

// Two-bone IK: joint position for a limb from root to target.
export function ik(root, target, a, b, bend) {
    let dx = target.x - root.x
    let dy = target.y - root.y
    let d = Math.hypot(dx, dy) || 0.001
    const max = a + b - 0.5
    const min = Math.abs(a - b) + 0.5
    let end = target
    if (d > max) {                       // out of reach: point straight at it
        end = P(root.x + (dx / d) * max, root.y + (dy / d) * max)
        d = max
    }
    if (d < min) d = min
    const base = Math.atan2(dy, dx)
    const cosA = Math.min(1, Math.max(-1, (a * a + d * d - b * b) / (2 * a * d)))
    const A = Math.acos(cosA) * bend
    return { joint: P(root.x + a * Math.cos(base + A), root.y + a * Math.sin(base + A)), end }
}

// ---- simulation --------------------------------------------------------------
// Scale: the climber is ~70px tall (≈1.7m), so 1m ≈ 41px and real gravity
// (9.8 m/s²) is ≈ 400px/s². Moves are paced like a calm climber: feet first,
// stand up over them, then reach — with short rests, and a quiet fall caught
// by a soft dynamic rope (one small bounce, no flailing, no comic shout).
const GRAVITY = 400

export function buildFrames() {
    const frames = []
    const s = {
        hip: P(ROUTE_X(SCENE.ground - 36) + HOLDS_OFFSET, SCENE.ground - 36),
        lean: 0,
        lh: P(ROUTE_X(SCENE.ground) + HOLDS_OFFSET - 16, SCENE.ground - 82), rh: P(ROUTE_X(SCENE.ground) + HOLDS_OFFSET + 16, SCENE.ground - 88),
        lf: P(ROUTE_X(SCENE.ground) + HOLDS_OFFSET - 10, SCENE.ground - 2), rf: P(ROUTE_X(SCENE.ground) + HOLDS_OFFSET + 10, SCENE.ground - 2),
        clipped: 0,
        flash: 0,               // 1 → 0 just after a clip (the draw pops)
        ropeHold: null,         // 'lh' | 'rh' while that hand holds the rope (pulling it up to clip)
        slack: 10,
        tension: 0,             // rope tension at the belayer (drives the pull)
        bx: 0, by: 0, brot: 0,  // belayer displacement / lean
        opacity: 1,
        stance: 'wall',          // wall | hang | ground
    }
    // Belayer: a mass on the ground pulled along the rope (toward bolt 1) by
    // the rope tension. Nothing moves until the pull beats the belayer's
    // weight; then they're lifted and swung toward the wall, lag behind the
    // rope a little, and drop back to the ground when the tension eases.
    let belayU = 0, belayV = 0
    const push = () => {
        const target = Math.min(52, Math.max(0, (s.tension || 0) * 0.75 - 8))
        belayV += ((target - belayU) * 70 - belayV * 9) / FPS
        belayU = Math.max(0, belayU + belayV / FPS)
        s.bx = PULL.x * belayU * 1.6                       // a step or two in toward the wall
        s.by = PULL.y * belayU
        s.brot = Math.min(22, belayU * 0.6)                // leaning in toward the wall
        frames.push(clone(s))
        if (s.flash > 0) s.flash = Math.max(0, s.flash - 1 / 48)
    }
    const wait = sec => { for (let i = 0; i < Math.round(sec * FPS); i++) push() }

    // tween any set of pose fields over `sec` seconds
    function tween(sec, to, onStep) {
        const from = clone(s)
        const n = Math.max(1, Math.round(sec * FPS))
        for (let i = 1; i <= n; i++) {
            const t = ease(i / n)
            for (const k of Object.keys(to)) {
                if (typeof to[k] === 'number') s[k] = lerp(from[k], to[k], t)
                else s[k] = lp(from[k], to[k], t)
            }
            if (onStep) onStep(i / n)
            push()
        }
    }
    const idealHip = () => {
        const feetY = (s.lf.y + s.rf.y) / 2
        const handsY = (s.lh.y + s.rh.y) / 2
        // stand as tall as the legs allow, but the shoulders can't rise
        // above the hands (arms bend as the climber pulls through)
        return P(
            (s.lh.x + s.rh.x + s.lf.x * 1.4 + s.rf.x * 1.4) / 4.8,
            Math.max(feetY - 32, handsY + 30),
        )
    }
    // move one limb to a hold. Before a foot moves, the weight shifts over the
    // other foot; the limb then leaves the rock in a small arc.
    function move(limb, target, sec, lift = 6) {
        const isFoot = limb.endsWith('f')
        if (isFoot) {
            const other = limb === 'lf' ? s.rf : s.lf
            tween(0.1, { hip: P(lerp(s.hip.x, other.x, 0.35), s.hip.y), lean: (other.x - s.hip.x) * 0.2 })
        }
        const start = clone(s[limb])
        const n = Math.round(sec * FPS)
        for (let i = 1; i <= n; i++) {
            const t = ease(i / n)
            s[limb] = P(lerp(start.x, target.x, t), lerp(start.y, target.y, t) - Math.sin(Math.PI * t) * lift * (isFoot ? 1 : -0.4))
            push()
        }
        s[limb] = target
        wait(isFoot ? 0.03 : 0.07)         // place the foot / grip the hold
    }
    const nearestHold = (y, side) =>
        HOLDS.filter(h => h.side === side).reduce((best, h) => (Math.abs(h.y - y) < Math.abs(best.y - y) ? h : best))

    // ---- 1. at the base: a moment to look up ------------------------------------
    wait(0.4)

    // ---- 2. climb: feet up, stand up, reach — clipping bolts on the way --------
    // Pace: quick on the easy start, getting slower and more deliberate as
    // the climber gets higher toward the crux (k = duration factor).
    // run it out ~1m above the last clip, toward the next (unclipped) bolt
    const targetHipY = BOLTS[FALL_ABOVE_BOLT].y - 40
    const startHipY = s.hip.y
    const pace = () => lerp(0.26, 0.52, Math.min(1, Math.max(0, (startHipY - s.hip.y) / (startHipY - targetHipY))))
    // Clip like a lead climber: as soon as the next bolt comes within reach in
    // front of the chest/face (above the harness, not once it's below it),
    // pull rope up from the tie-in and clip it into the quickdraw.
    function clipIfInReach(k) {
        const next = BOLTS[s.clipped]
        if (!next || s.clipped > FALL_ABOVE_BOLT) return false
        const shoulderY = s.hip.y - HIP_TO_SHOULDER
        if (next.y >= s.hip.y || next.y < shoulderY - 14) return false
        // the hand on the draw's side clips; the other hand stays on its hold
        const hand = next.x >= s.hip.x ? 'rh' : 'lh'
        const side = hand === 'rh' ? 1 : -1
        const back = clone(s[hand])
        tween(0.3 * k, { [hand]: P(s.hip.x + side * 4, s.hip.y - 2) }, t => { s.slack = 10 + 8 * t })   // grab the rope at the knot
        s.ropeHold = hand                                                // the rope now runs through the hand
        tween(0.38 * k, { [hand]: P(next.x + side * 2, next.y + 4) }, t => { s.slack = 18 + 6 * t })    // pull it up to the draw beside the body
        s.ropeHold = null
        s.clipped++
        s.flash = 1
        s.slack = 10
        wait(0.18)
        tween(0.3 * k, { [hand]: back })
        wait(0.2 * k)                                                    // breathe
        return true
    }

    let moves = 0
    while (s.hip.y > targetHipY) {
        const k = pace()
        const clipped = clipIfInReach(k)
        move('rf', nearestHold(s.hip.y + 6, 1), 0.3 * k, 9)
        move('lf', nearestHold(s.hip.y + 10, -1), 0.3 * k, 9)
        tween(0.38 * k, { hip: idealHip(), lean: 0 })                  // stand up over the feet
        move('rh', nearestHold(s.hip.y - 52, 1), 0.3 * k)
        move('lh', nearestHold(s.hip.y - 48, -1), 0.3 * k)
        tween(0.26 * k, { hip: idealHip() })
        moves++
        if (!clipped && moves % 5 === 0) wait(0.2 * k)                 // short rest
    }
    clipIfInReach(pace())

    // ---- 3. the crux: pull up rope to clip the anchor, slip mid-clip ---------
    // (the classic lead fall: the extra rope pulled for the clip is slack, so
    // the climber falls twice the distance above the last bolt, plus slack)
    wait(0.35)
    // the next clip is the anchor's ring (no more draws above the last bolt)
    const nextBolt = BOLTS[s.clipped] || P(ANCHOR.x, ANCHOR.y + 20)
    const cHand = nextBolt.x >= s.hip.x ? 'rh' : 'lh'
    const cSide = cHand === 'rh' ? 1 : -1
    tween(0.3, { hip: P(s.hip.x + cSide * 2, s.hip.y + 4) })
    tween(0.45, { [cHand]: P(s.hip.x + cSide * 4, s.hip.y - 4) }, t => { s.slack = 10 + t * 18 })   // grab rope at the harness
    s.ropeHold = cHand                                                   // pull the rope up toward the anchor ring
    tween(0.55, { [cHand]: P(nextBolt.x + cSide * 2, nextBolt.y + 6), hip: P(s.hip.x + cSide * 3, s.hip.y - 6), lean: 5 * cSide })
    wait(0.25)                                                           // fumbling the clip
    tween(0.1, { rf: P(s.rf.x + 8, s.rf.y + 10), lf: P(s.lf.x - 4, s.lf.y + 7) })   // a foot pops

    // ---- 4. fall + catch: real gravity, slack, then a soft dynamic rope ---------
    const anchor = BOLTS[s.clipped - 1]
    // distance to the last bolt + the slack pulled for the clip
    const ropeLen = Math.hypot(s.hip.x - anchor.x, s.hip.y - anchor.y) + 22
    let v = P(10, 0)
    const dt = 1 / FPS
    s.stance = 'hang'
    s.ropeHold = null                                                    // the rope is let go as the foot pops
    const pose = clone(s)
    const fallFrom = s.hip.y
    let caught = false
    for (let i = 0; i < Math.round(3.2 * FPS); i++) {
        let ax = 0
        let ay = GRAVITY
        const dx = s.hip.x - anchor.x
        const dy = s.hip.y - anchor.y
        const d = Math.hypot(dx, dy)
        const stretch = d - ropeLen
        if (stretch > 0) {
            // dynamic rope: soft spring, strong damping → one small bounce;
            // the belayer is pulled up toward the first bolt (soft catch)
            caught = true
            const k = 38, damp = 4.2
            ax -= (dx / d) * stretch * k + v.x * 1.2
            ay -= (dy / d) * stretch * k + v.y * damp
            s.tension = stretch * 1.6
        } else {
            s.tension = 0
        }
        v = P(v.x + ax * dt, v.y + ay * dt)
        s.hip = P(s.hip.x + v.x * dt, s.hip.y + v.y * dt)
        s.slack = Math.max(0, (pose.slack || 0) * (1 - i / (0.25 * FPS)))
        // free fall: arms up and loose, body tips back a little, legs bent
        // and ready; at the catch the feet come to the wall to absorb it
        const t = Math.min(1, i / (0.25 * FPS))
        const fallen = Math.min(1, (s.hip.y - fallFrom) / 60)
        const swing = (s.hip.x - anchor.x) * 0.3
        s.lean = lerp(pose.lean, swing - (caught ? 0 : 8 * fallen), t)
        s.lh = lp(pose.lh, P(s.hip.x - 12, s.hip.y - 52 - (caught ? 0 : 4)), t)
        s.rh = lp(pose.rh, P(s.hip.x + 10, s.hip.y - 54 - (caught ? 0 : 4)), t)
        s.lf = lp(pose.lf, P(s.hip.x - 4 + (caught ? 0 : 4), s.hip.y + 28), t)
        s.rf = lp(pose.rf, P(s.hip.x + 14 + (caught ? 0 : 2), s.hip.y + 24), t)
        push()
    }
    s.tension = 0

    // ---- 5. hang on the rope: push off to the side of the last draw (so it's
    // not hidden behind the body), hands on the rope, feet back on the wall ----
    const topDraw = BOLTS[s.clipped - 1]
    // both hands hold the rope itself: points on the line harness → top draw
    // hands on the rope just in front of the chest: the points of the rope line
    // (harness → top draw) at chest and face height, elbows bent and relaxed
    const ropeHands = () => {
        const hx = s.hip.x, hy = s.hip.y + 2
        const at = y => {
            const t = Math.max(0, Math.min(1, (hy - y) / (hy - topDraw.y || 1)))
            return P(hx + (topDraw.x - hx) * t, y)
        }
        return { lh: at(s.hip.y - 30), rh: at(s.hip.y - 40) }
    }
    const hangX = topDraw.x + 24                      // off to the right of the draw
    tween(0.8, Object.assign({
        hip: P(hangX, s.hip.y),
        lf: P(hangX - 8, s.hip.y + 30),
        rf: P(hangX + 10, s.hip.y + 27),
        lean: -6,
    }, (() => { const keep = s.hip; s.hip = P(hangX, s.hip.y); const h = ropeHands(); s.hip = keep; return h })()))
    wait(0.9)

    // ---- 6. lowered off: feet walk down the wall beside the bolt line, hands on
    // the rope that runs up to the top draw ---------------------------------------
    const bottom = SCENE.ground - 36
    let step = 0
    while (s.hip.y < bottom - 0.5) {
        const dy = Math.min(14, bottom - s.hip.y)
        const lead = step % 2 ? 'lf' : 'rf'
        const n = Math.round(0.24 * FPS)
        const hip0 = clone(s.hip)
        const f0 = clone(s[lead])
        for (let i = 1; i <= n; i++) {
            const t = ease(i / n)
            const y = hip0.y + dy * t
            s.hip = P(lerp(hip0.x, ROUTE_X(y) + HOLDS_OFFSET, 0.04), y)
            s[lead] = P(f0.x, f0.y + dy * 2 * t - Math.sin(Math.PI * t) * 5)
            Object.assign(s, ropeHands())
            s.lean = lerp(s.lean, -4, 0.05)
            const other = lead === 'lf' ? 'rf' : 'lf'
            s[other] = P(s[other].x, Math.max(s[other].y, s.hip.y + 24))
            push()
        }
        step++
    }
    // ---- 7. back on the ground, then fade and reset -------------------------------
    s.stance = 'ground'
    tween(0.5, {
        lf: P(s.hip.x - 8, SCENE.ground - 2),
        rf: P(s.hip.x + 8, SCENE.ground - 2),
        lh: P(s.hip.x - 14, s.hip.y - 10),
        rh: P(s.hip.x + 14, s.hip.y - 10),
    })
    wait(0.8)
    tween(0.5, { opacity: 0 })
    return frames
}

// ---- rendering helpers ------------------------------------------------------------
export function bodyPoints(f) {
    const rad = (f.lean * Math.PI) / 180
    const sh = P(f.hip.x + Math.sin(rad) * HIP_TO_SHOULDER, f.hip.y - Math.cos(rad) * HIP_TO_SHOULDER)
    const off = (p, dx) => P(p.x + Math.cos(rad) * dx, p.y + Math.sin(rad) * dx)
    const ls = off(sh, -7), rs = off(sh, 7)
    const lhip = off(f.hip, -5), rhip = off(f.hip, 5)
    const head = P(sh.x + Math.sin(rad) * 10, sh.y - Math.cos(rad) * 10)
    return {
        sh, head, rad,
        la: ik(ls, f.lh, ARM[0], ARM[1], -1), ls,
        ra: ik(rs, f.rh, ARM[0], ARM[1], 1), rs,
        ll: ik(lhip, f.lf, LEG[0], LEG[1], 1), lhip,
        rl: ik(rhip, f.rf, LEG[0], LEG[1], -1), rhip,
    }
}

// rope from the belay device through the clipped bolts to the harness, with sag
export function ropePath(f) {
    // the device moves with the belayer when they're pulled off the ground
    const pts = [P(DEVICE.x + (f.bx || 0), DEVICE.y + (f.by || 0)), ...BOLTS.slice(0, Math.round(f.clipped))]
    // while clipping, the rope runs up through the climber's hand, then back to the knot
    if (f.ropeHold) pts.push(P(f[f.ropeHold].x, f[f.ropeHold].y))
    pts.push(P(f.hip.x, f.hip.y + 2))
    let d = `M${pts[0].x.toFixed(1)} ${pts[0].y.toFixed(1)}`
    for (let i = 1; i < pts.length; i++) {
        const a = pts[i - 1], b = pts[i]
        const last = i === pts.length - 1
        const sag = last ? f.slack : (i === 1 ? f.slack * 0.6 : f.slack * 0.25)
        const cx = (a.x + b.x) / 2 + sag * 0.3
        const cy = (a.y + b.y) / 2 + sag
        d += ` Q${cx.toFixed(1)} ${cy.toFixed(1)} ${b.x.toFixed(1)} ${b.y.toFixed(1)}`
    }
    return d
}

export const BELAY_DEVICE = DEVICE
