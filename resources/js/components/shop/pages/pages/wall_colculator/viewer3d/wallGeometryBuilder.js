// Framework-agnostic climbing-wall model.
//
// Coordinate convention (this matters — earlier versions had this backwards):
//   - The climber/viewer stands on the +Z side, looking toward -Z.
//   - The climbing surface's visible face points +Z (toward the climber).
//   - `depth` is the overhang lean distance: how far the TOP of the wall sticks out
//     toward +Z (toward the climber) past vertical. depth=0 is a flat vertical wall.
//   - The support frame lives on the -Z side of the panel (behind it, hidden from
//     the climber, same as real plywood-on-studs construction).
//   - The safety mat, belay rope, and human reference figure stand/hang on the +Z
//     side, right at the wall's base — same side as the climbing face.
//
// Two assembly kinds:
//   - "single": one continuous slant from the floor (sport climbing / stand-free
//     walls, matches a generic room-built systems board).
//   - "bouldering": a straight vertical section at the bottom (matches the mat's
//     footprint, like real bouldering-wall framing) with the overhang only
//     starting above that — real bouldering walls don't lean from the floor.
//
// Each assembly is built in "local wall space" (or per-segment local space for
// bouldering) as if vertical, then tilted once around its own base — every child
// (frame, holds) stays correctly attached without per-part trig.
import { buildHoldsInstancedMesh, HOLD_COLORS as ROUTE_COLORS, synthesizeDefaultRouteXs } from './holdsInstancer.js';
import { buildHumanFigure } from './humanFigure.js';
import { buildPanelTexture } from './panelTexture.js';
import { WALL_CONSTRUCTION } from '../wallConstructionConfig.js';

const MIN_SIZE = WALL_CONSTRUCTION.MIN_WALL_SIZE;
export const PANEL_THICKNESS = WALL_CONSTRUCTION.PANEL_THICKNESS;
export const FRAME_MEMBER_SIZE = WALL_CONSTRUCTION.FRAME_MEMBER_SIZE; // 8x8cm real sawn-lumber dimension for the wall's own studs/rails

export function buildWallModel({ width = 0, height = 0, depth = 0 }) {
    const w = Math.max(parseFloat(width) || 0, MIN_SIZE);
    const h = Math.max(parseFloat(height) || 0, MIN_SIZE);
    const d = Math.max(parseFloat(depth) || 0, 0);

    const slantLength = Math.sqrt(h * h + d * d);
    const tiltAngle = Math.atan2(d, h); // 0 = vertical; grows as the top leans further toward the climber

    return { kind: 'single', width: w, height: h, depth: d, slantLength, tiltAngle };
}

// Bouldering walls: a straight vertical base then the overhang starts above it,
// instead of leaning from the floor. The straight section's height matches the
// real safety mat height where we have one (`matHeightHint`) rather than an
// arbitrary fraction of wall height — a mat is a few tens of cm, not ~30% of the
// wall, and real bouldering-wall framing keeps that base section short too.
export function buildBoulderingModel({ width = 0, height = 0, depth = 0, matHeightHint = 0 }) {
    const w = Math.max(parseFloat(width) || 0, MIN_SIZE);
    const h = Math.max(parseFloat(height) || 0, MIN_SIZE);
    const d = Math.max(parseFloat(depth) || 0, 0);

    const hint = parseFloat(matHeightHint) || 0;
    const vertHeight = hint > 0
        ? Math.min(Math.max(hint, 0.15), h - 0.3)
        : Math.min(h * 0.3, 1.0, h - 0.3);
    const angledHeight = h - vertHeight;
    const angledSlantLength = Math.sqrt(angledHeight * angledHeight + d * d);
    const angledTiltAngle = Math.atan2(d, angledHeight);

    return {
        kind: 'bouldering',
        width: w,
        height: h,
        depth: d, // total horizontal overhang offset at the very top — kept at this
                  // shape so camera framing math can treat both model kinds the same
        vertHeight,
        angled: { height: angledHeight, depth: d, slantLength: angledSlantLength, tiltAngle: angledTiltAngle },
    };
}

// THREE is injected (not imported) so this module stays loadable without pulling
// three.js in statically. `extras` carries the optional mat/roof/holds/human/rope
// /stand-free-frame add-ons.
export function buildWallMeshGroup(THREE, model, extras = {}) {
    const group = new THREE.Group();
    const frameMaterial = new THREE.MeshStandardMaterial({ color: 0xb89770, roughness: 0.85, metalness: 0.02 });

    // Resolved ONCE here (not left to each hold-placement call to synthesize
    // independently) so a bouldering wall's two segments (vertical base +
    // overhang, built separately below) share the exact same route X
    // positions instead of each segment inventing its own unrelated set and
    // the "lines" not lining up across the seam between them. Sport climbing:
    // each rope line becomes its own hold route, so holds cluster near the
    // actual protection line rather than being placed independently of where
    // the ropes actually are. Merged into `extras` itself (not a separate
    // variable) so every downstream consumer in this function sees the same,
    // consistent extras object.
    if (!extras.routeLines) {
        const anchorXs = extras.showRope ? computeRopeAnchorXs(model.width) : synthesizeDefaultRouteXs(model.width);
        extras = {
            ...extras,
            routeLines: anchorXs.map((x, i) => ({ x, color: ROUTE_COLORS[i % ROUTE_COLORS.length] })),
        };
    }

    let topWorld;

    if (model.kind === 'bouldering') {
        topWorld = buildBoulderingAssembly(THREE, group, model, { frameMaterial, extras });
    } else {
        topWorld = buildSingleSlantAssembly(THREE, group, model, { frameMaterial, extras });
    }

    // A 3+ sided tower's own radiating stud frames (one per face, meeting at
    // the shared center) are already the "extra construction" a stand-free wall
    // needs — calling this separate box+truss once per side there piled up N
    // overlapping copies in the small central area. A 2-sided back-to-back
    // tower has no such shared hollow core (each side's own frame extends into
    // open space in the OPPOSITE direction from the other side, so they never
    // overlap) — a real freestanding A-frame is exactly two of these braced
    // structures built back-to-back, so it gets one per side too instead of
    // the old bare mast+rails.
    if (extras.isStandFree && (!extras.isTowerSide || extras.isTwoSideTower)) {
        addStandFreePrimaryFrame(THREE, group, { w: model.width, topWorld, frameMaterial });
    }

    addSiteFeatures(THREE, group, {
        width: model.width,
        topWorld,
        extras,
    });

    return group;
}

function buildSingleSlantAssembly(THREE, group, model, { frameMaterial, extras }) {
    const { width: w, height: h, depth: d, slantLength: L, tiltAngle } = model;

    // Tilt about X by +tiltAngle so the local top (y=L, z=0) lands at world (h, +d) —
    // i.e. leaning toward the climber.
    const wallPivot = new THREE.Group();
    wallPivot.rotation.x = tiltAngle;
    group.add(wallPivot);

    addClimbingPanel(THREE, wallPivot, w, L);
    addSupportFrame(THREE, wallPivot, { w, L, frameMaterial, skipRails: extras.isTowerSide });
    addHoldsIfEnabled(THREE, wallPivot, extras, w, L);
    if (!extras.isTowerSide) addSideClosingPanels(THREE, wallPivot, w, L);

    return { y: h, z: d };
}

function buildBoulderingAssembly(THREE, group, model, { frameMaterial, extras }) {
    const { width: w, height: h, vertHeight, angled } = model;

    // Lower section: straight and vertical, matching the mat's footprint —
    // added directly to the (untilted) group since local space == world space here.
    addClimbingPanel(THREE, group, w, vertHeight);
    addSupportFrame(THREE, group, { w, L: vertHeight, frameMaterial, skipRails: extras.isTowerSide });
    if (!extras.isTowerSide) addSideClosingPanels(THREE, group, w, vertHeight);

    // Upper section: the overhang, pivoting from the top of the lower section.
    const upperPivot = new THREE.Group();
    upperPivot.position.set(0, vertHeight, 0);
    upperPivot.rotation.x = angled.tiltAngle;
    group.add(upperPivot);

    addClimbingPanel(THREE, upperPivot, w, angled.slantLength);
    addSupportFrame(THREE, upperPivot, { w, L: angled.slantLength, frameMaterial, skipRails: extras.isTowerSide });
    if (!extras.isTowerSide) addSideClosingPanels(THREE, upperPivot, w, angled.slantLength);

    // A horizontal seam rail at the kink, like the "panel mount" line in real
    // transition-angle builds. Skipped for tower sides — full-width rails from
    // every side converge in the tower's open, unfinished-ceiling core with
    // nothing to hide them behind, showing up as an unexplained crossed lattice
    // (the same reason the frame's own rails are skipped there too).
    if (!extras.isTowerSide) {
        const seam = new THREE.Mesh(
            new THREE.BoxGeometry(w, FRAME_MEMBER_SIZE, FRAME_MEMBER_SIZE),
            frameMaterial
        );
        seam.position.set(0, vertHeight, -(PANEL_THICKNESS / 2 + FRAME_MEMBER_SIZE / 2));
        seam.name = 'frame_seam';
        group.add(seam);
    }

    if (extras.holdsEnabled && extras.holdsCount > 0) {
        const lowerArea = w * vertHeight;
        const upperArea = w * angled.slantLength;
        const totalArea = lowerArea + upperArea || 1;
        const lowerCount = Math.round(extras.holdsCount * (lowerArea / totalArea));
        const upperCount = Math.max(extras.holdsCount - lowerCount, 0);

        addHoldsIfEnabled(THREE, group, { ...extras, holdsCount: lowerCount }, w, vertHeight);
        addHoldsIfEnabled(THREE, upperPivot, { ...extras, holdsCount: upperCount }, w, angled.slantLength);
    }

    return { y: h, z: angled.depth };
}

function addClimbingPanel(THREE, parent, w, L) {
    const panelGeometry = new THREE.BoxGeometry(w, L, PANEL_THICKNESS);
    panelGeometry.translate(0, L / 2, 0);
    // White base so the plywood texture's own tone/holes/seams show through as-drawn.
    const material = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        roughness: 0.92,
        metalness: 0.0,
        map: buildPanelTexture(THREE, w, L),
    });
    const panelMesh = new THREE.Mesh(panelGeometry, material);
    panelMesh.name = 'climbing_surface';
    parent.add(panelMesh);
    parent.add(new THREE.LineSegments(
        new THREE.EdgesGeometry(panelGeometry),
        new THREE.LineBasicMaterial({ color: 0x2c3e50, transparent: true, opacity: 0.3 })
    ));
}

// A non-stand-free wall is fixed to an actual building wall, not floating in
// open air — a plain backing panel behind the stud frame reads much better
// than empty space. A real building wall is always vertical regardless of how
// far the climbing panel itself overhangs — an earlier version tilted this
// panel along with the frame to close the gap between them, but stretched
// across a steep tilt (plus its own top/side margins) that reads as an
// absurdly long near-horizontal slab rather than a recognizable wall. Kept
// fixed/vertical here; addBackingGapCloser (below) closes the actual gap that
// opens up between the leaning frame and this static wall instead.
function addBackingWall(THREE, group, { w, topWorld, isOutdoor }) {
    const backWallMargin = 2.0;
    const backWallThickness = 0.4;
    const backWallHeight = topWorld.y + backWallMargin;
    const backWallZFront = isOutdoor ? -0.12 : -0.15;

    const backWall = new THREE.Mesh(
        new THREE.BoxGeometry(w + backWallMargin * 2, backWallHeight, backWallThickness),
        new THREE.MeshStandardMaterial({ color: 0xe6e3dc, roughness: 0.95 })
    );
    backWall.position.set(0, backWallHeight / 2, backWallZFront - backWallThickness / 2);
    backWall.name = 'building_backing_wall';
    group.add(backWall);

    return backWallZFront;
}

// The wall's own frame leans forward with height while the backing wall above
// stays put — the gap between them (near-zero at the base, growing to
// roughly the full overhang depth at the top) reads as visible daylight
// between the climbing structure and the building wall behind it. Closed with
// a flat sloped wedge at each side edge (only as tall as the wall itself, not
// the backing wall's own +2m margin — this is a "return" panel closing the
// actual gap, not a second wall), like a real soffit/gusset closing the
// underside of a leaning structure. Skipped when there's no meaningful gap
// (near-vertical walls) — matches the corner-infill's own "skip tiny gaps"
// convention.
function addBackingGapCloser(THREE, group, { w, topWorld, backWallZFront }) {
    const frameBackZ = -(PANEL_THICKNESS / 2 + FRAME_MEMBER_SIZE); // wall's own frame back face at its (untitled) base
    const topBackZ = topWorld.z + frameBackZ; // approx: the tilt shrinks this term toward 0, negligible next to real overhang depths
    if (topBackZ - backWallZFront < 0.05) return;

    const halfW = w / 2;
    const material = new THREE.MeshStandardMaterial({ color: 0xb89770, roughness: 0.85, metalness: 0.02, side: THREE.DoubleSide });

    [-halfW, halfW].forEach((x) => {
        const geometry = new THREE.BufferGeometry();
        const positions = new Float32Array([
            x, 0, frameBackZ,
            x, topWorld.y, topBackZ,
            x, topWorld.y, backWallZFront,
            x, 0, frameBackZ,
            x, topWorld.y, backWallZFront,
            x, 0, backWallZFront,
        ]);
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        geometry.computeVertexNormals();
        const mesh = new THREE.Mesh(geometry, material);
        mesh.name = 'backing_gap_closer';
        group.add(mesh);
    });
}

// A single standalone wall (not part of a multi-side tower) reads as an open
// frame from the side without these — real modular wall systems close off
// their sides into a finished box, matching the reference photo this was
// checked against. Spans the wall's full construction depth, front face to
// behind the stud frame.
function addSideClosingPanels(THREE, parent, w, L) {
    const halfW = w / 2;
    const frontZ = PANEL_THICKNESS / 2;
    const backZ = -(PANEL_THICKNESS / 2 + FRAME_MEMBER_SIZE);
    const depth = frontZ - backZ;
    const material = new THREE.MeshStandardMaterial({ color: 0xb89770, roughness: 0.85, metalness: 0.02 });

    [-halfW, halfW].forEach((x) => {
        const panel = new THREE.Mesh(new THREE.BoxGeometry(0.03, L, depth), material);
        panel.position.set(x, L / 2, (frontZ + backZ) / 2);
        panel.name = 'side_closing_panel';
        parent.add(panel);
    });
}

function addHoldsIfEnabled(THREE, parent, extras, panelWidth, panelLength) {
    if (!extras.holdsEnabled || !extras.holdsCount || extras.holdsCount <= 0) return;
    const holds = buildHoldsInstancedMesh(THREE, {
        panelWidth,
        panelLength,
        count: extras.holdsCount,
        // Hold geometry is already baked to protrude outward from its own z=0 mounting
        // face (see holdsInstancer.js) — this only needs to land that face flush on the
        // panel's actual front surface (+ a hair to dodge z-fighting), not add a second
        // offset on top of it. The old `+ 0.045` here double-counted the hold's own
        // protrusion and left a visible floating gap between every hold and the wall.
        surfaceOffset: PANEL_THICKNESS / 2 + 0.002,
        routeLines: extras.routeLines || null,
    });
    if (holds) parent.add(holds);
}

function addSiteFeatures(THREE, group, { width: w, topWorld, extras }) {
    let matTopY = 0;
    let matFrontZ = 0.05;

    if (!extras.isStandFree) {
        const backWallZFront = addBackingWall(THREE, group, { w, topWorld, isOutdoor: extras.isOutdoor });
        addBackingGapCloser(THREE, group, { w, topWorld, backWallZFront });
    }

    // Safety mat sits flat on the ground at the wall's base, on the same (+z) side
    // as the climbing face — flush against the wall, not behind it. Skipped
    // entirely for sport climbing (rope instead, see below).
    if (!extras.showRope && extras.mat && extras.mat.width > 0 && extras.mat.height > 0) {
        const matDepthVal = Math.max(extras.mat.depth || 1.2, 0.3);
        const matThickness = Math.max(extras.mat.height, 0.06);
        const matMesh = new THREE.Mesh(
            new THREE.BoxGeometry(extras.mat.width, matThickness, matDepthVal),
            new THREE.MeshStandardMaterial({ color: 0xd64545, roughness: 0.95 })
        );
        matMesh.position.set(0, matThickness / 2, matDepthVal / 2 + 0.02);
        matMesh.name = 'safety_mat';
        group.add(matMesh);
        matTopY = matThickness;
        matFrontZ = matDepthVal * 0.55;
    }

    // Sport climbing: a two-strand belay rope through a carabiner at the top
    // anchor, instead of a mat. Ropes hang under gravity — the strands drop
    // straight down regardless of how far the wall itself overhangs, and a real
    // top-rope setup runs UP to the anchor and back DOWN, not a single line.
    // Real routes get one line roughly every 1.5m across the wall (with a
    // 0.75m margin from each edge), not just a single central line.
    if (extras.showRope) {
        addBelayAnchorSystem(THREE, group, computeRopeAnchorXs(w), w, topWorld);
        matFrontZ = 0.6;
    }

    // Outdoor wall types get a canopy roof + support posts, anchored above the
    // leaning top edge — untilted (a roof stays level, it doesn't lean with the
    // wall). A real canopy roof (see reference porch-canopy photos this was
    // checked against) reads as a genuinely sized structure projecting well
    // past the opening it covers, not a cap barely wider than the wall itself.
    if (extras.isOutdoor) {
        // A stand-free structure has no backing building wall for the roof to
        // read as an extension of — a canopy sized the same as an
        // attached-wall roof (generous eave margins, rise scaling freely with
        // however deep the overhang goes) had nothing to visually anchor it
        // and just looked like an oversized slab floating over the frame.
        // Kept noticeably smaller and capped regardless of overhang depth.
        const sideEaveMargin = extras.isStandFree ? 0.4 : 0.7;
        const frontEaveMargin = extras.isStandFree ? 0.35 : 0.6;
        const roofWidth = w + sideEaveMargin * 2;
        const roofFrontY = topWorld.y + 0.14; // low edge — just clears the wall's own top
        const roofZBack = -0.15;
        const roofZFront = topWorld.z + frontEaveMargin;
        const roofSpan = roofZFront - roofZBack;

        // A real lean-to canopy — one continuous slope, high at the building
        // wall and low at the front eave — not a ridged two-sided gable (which
        // has no ridge beam to actually support it and doesn't match how a
        // real awning/canopy attached to a building looks; checked against
        // reference photos of exactly this kind of structure).
        //
        // The rise is a fixed FRACTION of the span, not a span-independent
        // capped absolute value — a fixed ~1.2m cap looked fine for a modest
        // canopy but, once the span itself grew large (a deep overhang pushes
        // the front eave far out), produced an almost paper-flat sliver roof
        // that from some camera angles read edge-on as a thin crossing blade.
        // A constant pitch keeps the same, sane roof proportions at any span
        // — capped outright for stand-free roofs so a very deep overhang
        // still gets a contained canopy, not a roof ballooning right along
        // with it.
        const backRise = extras.isStandFree
            ? Math.min(Math.max(roofSpan * 0.15, 0.2), 1.1)
            : Math.max(roofSpan * 0.22, 0.2);
        const roofBackY = roofFrontY + backRise;
        const roofGeometry = buildMonopitchRoofGeometry(THREE, {
            width: roofWidth, backY: roofBackY, frontY: roofFrontY, zBack: roofZBack, zFront: roofZFront,
        });
        const roofMaterial = new THREE.MeshStandardMaterial({ color: 0x9c3b30, roughness: 0.75, metalness: 0.05, side: THREE.DoubleSide });
        const roofMesh = new THREE.Mesh(roofGeometry, roofMaterial);
        roofMesh.name = 'roof';
        group.add(roofMesh);

        // A monopitch roof's own left/right profile is an open diagonal edge
        // with nothing closing the space underneath it — real lean-to canopies
        // (see reference photos) have a closed triangular side fascia there,
        // not a bare open edge. One flat right-triangle panel per side: a
        // vertical back edge (the height difference between the two eaves), a
        // horizontal bottom edge (level with the low front eave), and the
        // sloped hypotenuse following the roof's own underside.
        const halfRoofW = roofWidth / 2;
        [-halfRoofW, halfRoofW].forEach((x) => {
            const geometry = new THREE.BufferGeometry();
            const positions = new Float32Array([
                x, roofBackY, roofZBack,
                x, roofFrontY, roofZFront,
                x, roofFrontY, roofZBack,
            ]);
            geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            geometry.computeVertexNormals();
            const mesh = new THREE.Mesh(geometry, roofMaterial);
            mesh.name = 'roof_side_fascia';
            group.add(mesh);
        });

        // No separate front support posts — those used to stick out disconnected
        // from the rest of the structure. The roof instead reads as carried by
        // the wall's own construction: the backing building-wall behind it
        // (non-stand-free) or the primary frame's own legs (stand-free, added
        // separately in addStandFreePrimaryFrame), both already tall/close enough
        // to visually support it.

        // A header/fascia panel closing the gap between the wall's own top edge
        // and the roof underside — without it that gap reads as an open hole
        // rather than a finished, enclosed structure.
        const gapHeight = roofFrontY - topWorld.y;
        if (gapHeight > 0.02) {
            const fascia = new THREE.Mesh(
                new THREE.BoxGeometry(w, gapHeight, PANEL_THICKNESS),
                new THREE.MeshStandardMaterial({ color: 0xb89770, roughness: 0.85, metalness: 0.02 })
            );
            fascia.position.set(0, topWorld.y + gapHeight / 2, topWorld.z);
            fascia.name = 'roof_fascia';
            group.add(fascia);
        }

        // Stand-free walls lean (overhang) while the roof stays flat, opening a
        // triangular gap on each side between the wall's own angled top corner
        // and the level roof above it — the fascia above only closes the front
        // gap (spanning the width), not this side profile. One small gusset
        // bracket per side closes it.
        if (extras.isStandFree) {
            const bracketMaterial = new THREE.MeshStandardMaterial({
                color: 0xb89770, roughness: 0.85, metalness: 0.02, side: THREE.DoubleSide,
            });
            [-w / 2, w / 2].forEach((x) => {
                const geometry = new THREE.BufferGeometry();
                const positions = new Float32Array([
                    x, topWorld.y, topWorld.z,
                    x, roofFrontY, roofZFront,
                    x, roofBackY, roofZBack,
                ]);
                geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
                geometry.computeVertexNormals();
                const mesh = new THREE.Mesh(geometry, bracketMaterial);
                mesh.name = 'roof_bracket';
                group.add(mesh);
            });
        }
    }

    // A human figure for scale reference: standing on the mat if there is one
    // (bouldering), otherwise directly on the ground (sport climbing, matTopY
    // stays 0 since no mat is built above) — always on the climbing-face (+z) side.
    // `matFrontZ` alone (a fixed fraction of the mat's own depth / a small rope
    // default) doesn't scale with how far the wall itself overhangs — for a
    // steep overhang the wall's swept volume reaches well past that fixed point,
    // leaving the figure standing underneath the panel instead of clear of it.
    // Clearing the wall's own top-corner reach (topWorld.z) fixes that for any
    // overhang depth.
    if (extras.showHuman !== false) {
        const human = buildHumanFigure(THREE);
        const sideX = Math.min(w / 2 + 0.55, w * 0.5 + 0.8);
        const clearZ = Math.max(matFrontZ, topWorld.z + 0.5);
        human.position.set(sideX, matTopY, clearZ);
        human.name = 'reference_human';
        group.add(human);
    }
}

const ROPE_EDGE_MARGIN = WALL_CONSTRUCTION.ROPE_EDGE_MARGIN; // meters from each side before the first rope line
const ROPE_TARGET_SPACING = WALL_CONSTRUCTION.ROPE_TARGET_SPACING; // meters between rope lines, real sport-route bolt-line spacing

// Evenly spaced anchor X positions (panel-centered, so 0 = wall center) roughly
// 1.5m apart with a 0.75m margin from each edge — e.g. a 2.5m-wide wall only
// fits one line (centered), a 3m wall fits exactly two (one 0.75m from each side).
function computeRopeAnchorXs(width) {
    const usable = Math.max(width - ROPE_EDGE_MARGIN * 2, 0);
    const count = usable > 0 ? Math.floor(usable / ROPE_TARGET_SPACING) + 1 : 1;
    if (count <= 1) return [0];
    const xs = [];
    for (let i = 0; i < count; i++) {
        xs.push(-usable / 2 + (i / (count - 1)) * usable);
    }
    return xs;
}

const ANCHOR_FLANGE_HEIGHT = 0.22; // vertical mounting plate, bolted flush to the wall
const ANCHOR_ARM_PROJECTION = 0.18; // horizontal arm reaching out from the top of the flange

// One self-contained anchor bracket per rope line: an L-shaped steel bracket
// — a vertical flange bolted flush to the wall, with a horizontal arm welded
// to its top projecting straight out — with the rope hanging directly from
// the arm's tip. The hook-ring + fabric-sling + carabiner hardware between
// the arm and the rope (present on the real anchor this was checked against)
// rendered as messy, near-invisible slivers at most camera angles — thin
// torus rings side-on look like flat discs, thin boxes edge-on look like
// stray lines — reading as clutter rather than detail. Keeping just the green
// metal bracket and the rope itself reads far cleaner at any angle.
// `topWorld.z` alone is the panel's own CENTERLINE, not its front face —
// mounting the flange directly there put the whole rig flush with (and for a
// near-vertical wall, actually inside) the panel; mounting it at the panel's
// real front face instead keeps it flush against the wall, with only the arm
// projecting outward.
function addBelayAnchorSystem(THREE, group, anchorXs, w, topWorld) {
    anchorXs.forEach((anchorX) => addBelayAnchor(THREE, group, anchorX, topWorld));
}

function addBelayAnchor(THREE, group, anchorX, topWorld) {
    const hardwareMaterial = new THREE.MeshStandardMaterial({ color: 0x2f6b3a, roughness: 0.5, metalness: 0.4 });
    const boltMaterial = new THREE.MeshStandardMaterial({ color: 0x888888, metalness: 0.8, roughness: 0.3 });
    const wallFaceZ = topWorld.z + PANEL_THICKNESS / 2;
    const mountY = topWorld.y;

    // The vertical flange, bolted flush to the wall, hanging down from the
    // wall's own top edge.
    const flange = new THREE.Mesh(new THREE.BoxGeometry(0.14, ANCHOR_FLANGE_HEIGHT, 0.02), hardwareMaterial);
    flange.position.set(anchorX, mountY - ANCHOR_FLANGE_HEIGHT / 2, wallFaceZ + 0.01);
    flange.name = 'belay_bracket_flange';
    group.add(flange);

    [-0.06, 0.06].forEach((by) => {
        const bolt = new THREE.Mesh(new THREE.CylinderGeometry(0.012, 0.012, 0.03, 8), boltMaterial);
        bolt.rotation.x = Math.PI / 2;
        bolt.position.set(anchorX, mountY - ANCHOR_FLANGE_HEIGHT / 2 + by, wallFaceZ - 0.005);
        bolt.name = 'belay_bracket_bolt';
        group.add(bolt);
    });

    // The horizontal arm, welded to the flange's top edge, reaching straight
    // out from the wall — this is what actually carries the rope clear of the
    // panel below it, not the flange itself.
    const armY = mountY - 0.015;
    const arm = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.03, ANCHOR_ARM_PROJECTION), hardwareMaterial);
    arm.position.set(anchorX, armY, wallFaceZ + ANCHOR_ARM_PROJECTION / 2);
    arm.name = 'belay_bracket_arm';
    group.add(arm);

    const tipZ = wallFaceZ + ANCHOR_ARM_PROJECTION;
    const dropY = armY - 0.02;
    addBelayRope(THREE, group, anchorX, dropY, tipZ);
}

// Two-strand rope hanging from the anchor carabiner down to the ground —
// separate from addBelayAnchor's own hardware so it stays reusable if the
// anchor point ever needs to come from somewhere else.
function addBelayRope(THREE, group, anchorX, anchorY, anchorZ) {
    const spread = 0.16; // how far apart the climber-side and belayer-side strands land
    const ropeMaterial = new THREE.MeshStandardMaterial({ color: 0x8a2e2e, roughness: 0.75 });

    [-1, 1].forEach((side) => {
        const groundX = anchorX + side * spread;
        const dx = groundX - anchorX;
        const dy = -anchorY;
        const strandLength = Math.sqrt(dx * dx + dy * dy);
        const strand = new THREE.Mesh(new THREE.CylinderGeometry(0.013, 0.013, strandLength, 6), ropeMaterial);
        strand.position.set((anchorX + groundX) / 2, anchorY / 2, anchorZ);
        strand.rotation.z = Math.atan2(-dx, dy);
        strand.name = 'belay_rope';
        group.add(strand);
    });
}

// Stand-free walls (matching the "primary frame + subframe" reference this was
// checked against): a free-standing box frame on its own legs, carrying a thick
// diagonal truss that props the panel up from underneath — real freestanding
// structures don't just hang the panel off thin studs, they need this kind of
// independent load path to the ground.
function addStandFreePrimaryFrame(THREE, group, { w, topWorld, frameMaterial }) {
    const legHeight = Math.min(topWorld.y * 0.4, 1.3);
    const boxDepth = 1.1;
    const boxBackZ = -0.5;
    const boxFrontZ = boxBackZ - boxDepth;
    const halfW = w / 2 - 0.1;
    const trussMaterial = new THREE.MeshStandardMaterial({ color: 0x9c8259, roughness: 0.8, metalness: 0.05 });

    // Four corner legs of the primary frame box.
    const corners = [
        [-halfW, boxBackZ], [halfW, boxBackZ], [-halfW, boxFrontZ], [halfW, boxFrontZ],
    ];
    corners.forEach(([x, z]) => {
        const leg = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.05, legHeight, 8), trussMaterial);
        leg.position.set(x, legHeight / 2, z);
        leg.name = 'primary_frame_leg';
        group.add(leg);
    });

    // Top rails tying the legs into a box.
    const topRailPairs = [
        [[-halfW, boxBackZ], [halfW, boxBackZ]],
        [[-halfW, boxFrontZ], [halfW, boxFrontZ]],
        [[-halfW, boxBackZ], [-halfW, boxFrontZ]],
        [[halfW, boxBackZ], [halfW, boxFrontZ]],
    ];
    topRailPairs.forEach(([[x1, z1], [x2, z2]]) => {
        const dx = x2 - x1;
        const dz = z2 - z1;
        const length = Math.sqrt(dx * dx + dz * dz);
        const rail = new THREE.Mesh(new THREE.BoxGeometry(length, 0.08, 0.08), trussMaterial);
        rail.position.set((x1 + x2) / 2, legHeight, (z1 + z2) / 2);
        rail.rotation.y = Math.atan2(-dz, dx);
        rail.name = 'primary_frame_rail';
        group.add(rail);
    });

    // The diagonal subframe: a proper triangulated (Warren) truss running from
    // the base of the primary frame up to the top-back of the panel — two
    // parallel chords with a zigzag of diagonal web members between them,
    // matching the reference structural diagram. A single thick beam with
    // isolated, unconnected angled blocks scattered along it (the earlier
    // version) doesn't actually triangulate anything and reads as random
    // clutter rather than a real truss.
    [-halfW * 0.6, halfW * 0.6].forEach((x) => {
        const startY = 0; // the truss carries load all the way to the ground, like the reference
        const startZ = boxFrontZ + 0.15;
        const endY = topWorld.y;
        const endZ = topWorld.z;
        const dy = endY - startY;
        const dz = endZ - startZ;
        const length = Math.sqrt(dy * dy + dz * dz);
        const dirY = dy / length;
        const dirZ = dz / length;
        const perpY = -dirZ; // 90° rotation of the truss direction within the Y-Z plane
        const perpZ = dirY;
        const trussDepth = 0.22;

        const chordLine = (side) => ({
            y1: startY + perpY * trussDepth * side, z1: startZ + perpZ * trussDepth * side,
            y2: endY + perpY * trussDepth * side, z2: endZ + perpZ * trussDepth * side,
        });
        const front = chordLine(0.5);
        const back = chordLine(-0.5);
        const pointAt = (c, t) => ({ y: c.y1 + (c.y2 - c.y1) * t, z: c.z1 + (c.z2 - c.z1) * t });

        const addTrussMember = (p1, p2, size, name) => {
            const mdy = p2.y - p1.y;
            const mdz = p2.z - p1.z;
            const mlen = Math.sqrt(mdy * mdy + mdz * mdz);
            if (mlen < 0.02) return;
            const beam = new THREE.Mesh(new THREE.BoxGeometry(size, mlen, size), trussMaterial);
            beam.position.set(x, (p1.y + p2.y) / 2, (p1.z + p2.z) / 2);
            beam.rotation.x = Math.atan2(mdz, mdy);
            beam.name = name;
            group.add(beam);
        };

        addTrussMember({ y: front.y1, z: front.z1 }, { y: front.y2, z: front.z2 }, 0.08, 'subframe_chord');
        addTrussMember({ y: back.y1, z: back.z1 }, { y: back.y2, z: back.z2 }, 0.08, 'subframe_chord');

        const segments = 5;
        for (let i = 0; i <= segments; i++) {
            const t = i / segments;
            const fp = pointAt(front, t);
            const bp = pointAt(back, t);
            addTrussMember(fp, bp, 0.05, 'subframe_web'); // rung at every node

            if (i < segments) {
                const tNext = (i + 1) / segments;
                const fpNext = pointAt(front, tNext);
                const bpNext = pointAt(back, tNext);
                // Alternate which chord each diagonal starts from — the zigzag
                // that actually triangulates the truss.
                if (i % 2 === 0) addTrussMember(bp, fpNext, 0.05, 'subframe_web');
                else addTrussMember(fp, bpNext, 0.05, 'subframe_web');
            }
        }
    });
}

const STUD_SPACING = WALL_CONSTRUCTION.STUD_SPACING; // 16" (0.4m) on-center — real stud-wall
                                                      // framing spacing, not derived from sheet
                                                      // size (a prior version used half the 1.5m
                                                      // sheet, ~0.75m, nearly double a real stud's
                                                      // actual spacing and visibly under-framed)

// Every position from 0 up to `total`, `step` apart, always including `total`
// itself as the last entry even if it doesn't land exactly on a multiple of
// `step` — guarantees a member right at both ends (edge/top plate) as well as
// at every sheet seam and sheet midpoint in between.
function sheetAlignedPositions(total, step) {
    const positions = [0];
    for (let v = step; v < total - 1e-6; v += step) positions.push(v);
    positions.push(total);
    return positions;
}

// A member centered exactly on a boundary position (the panel's own edge)
// would stick out half its own thickness past that edge — clamp it to sit
// just inside instead, without disturbing any interior member's position.
function clampToInterior(value, min, max) {
    const half = FRAME_MEMBER_SIZE / 2;
    return Math.min(Math.max(value, min + half), max - half);
}

function addSupportFrame(THREE, wallPivot, { w, L, frameMaterial, skipRails = false }) {
    const halfW = w / 2;
    const backZ = -(PANEL_THICKNESS / 2 + FRAME_MEMBER_SIZE / 2); // behind the panel, hidden from the climber

    // Vertical studs aligned to the plywood sheet seams and midpoints (every
    // 0.75m — half of the 1.5m sheet), not an arbitrary spacing: a stud always
    // backs a sheet's edge, its center, and the seam with the next sheet, right
    // where the seam lines on the panel texture actually are.
    sheetAlignedPositions(w, STUD_SPACING).forEach((offset) => {
        const x = clampToInterior(-halfW + offset, -halfW, halfW);
        const postGeometry = new THREE.BoxGeometry(FRAME_MEMBER_SIZE, L, FRAME_MEMBER_SIZE);
        postGeometry.translate(0, L / 2, 0);
        const post = new THREE.Mesh(postGeometry, frameMaterial);
        post.position.set(x, 0, backZ);
        post.name = 'frame_post';
        wallPivot.add(post);
    });

    // Horizontal rails on the same 0.75m rhythm (bottom = sole plate, top = top
    // plate, in between = blocking at every sheet seam/midpoint) — no diagonal
    // bracing: real plywood-on-studs construction gets its shear strength from
    // the sheathing itself, not visible X-braces across the back.
    //
    // Skipped for tower sides: each side's rail spans its own full width `w`,
    // and every side's frame sits at roughly the same small radius from the
    // shared central axis — so on a tower, rails from every side sweep through
    // that same open core and cross each other there, visible as an unexplained
    // lattice through the hollow center with nothing to hide them behind. Studs
    // alone stay within each side's own angular slot and don't have this problem.
    if (!skipRails) {
        sheetAlignedPositions(L, STUD_SPACING).forEach((y) => {
            const rail = new THREE.Mesh(
                new THREE.BoxGeometry(w, FRAME_MEMBER_SIZE, FRAME_MEMBER_SIZE),
                frameMaterial
            );
            rail.position.set(0, clampToInterior(y, 0, L), backZ);
            rail.name = 'frame_rail';
            wallPivot.add(rail);
        });
    }
}

// A real pitched roof: a ridge line along the width, sloping down to front and
// back eaves, with triangular gable ends closing the sides — not a flat slab.
// Shared between the single-wall roof (above) and the 2-side tower roof
// (WallViewer3D.vue's addTowerRoof), which is the same shape at a different scale.
export function buildGableRoofGeometry(THREE, { width, eaveY, ridgeY, zBack, zFront, ridgeRatio = 0.5 }) {
    const halfW = width / 2;
    const zCenter = zBack + (zFront - zBack) * ridgeRatio;
    const bl = [-halfW, eaveY, zBack];
    const br = [halfW, eaveY, zBack];
    const fl = [-halfW, eaveY, zFront];
    const fr = [halfW, eaveY, zFront];
    const rl = [-halfW, ridgeY, zCenter];
    const rr = [halfW, ridgeY, zCenter];

    const positions = new Float32Array([
        ...bl, ...br, ...rr, ...bl, ...rr, ...rl, // back slope
        ...fl, ...rl, ...rr, ...fl, ...rr, ...fr, // front slope
        ...bl, ...rl, ...fl, // left gable end
        ...br, ...fr, ...rr, // right gable end
    ]);
    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.computeVertexNormals();
    return geometry;
}

// A lean-to canopy roof: one continuous slope, high at the back (the
// building-wall side) and low at the front eave — the shape a real awning or
// canopy roof bolted to a building actually has, as opposed to a two-sided
// gable with a ridge (which needs a real ridge beam to hold up the middle,
// and doesn't match a wall attached to an existing building on one side).
// Used for single (non-tower) outdoor walls; buildGableRoofGeometry (above)
// is kept for the 2-side tower spine case, which genuinely has two open sides
// needing a real ridge.
export function buildMonopitchRoofGeometry(THREE, { width, backY, frontY, zBack, zFront }) {
    const halfW = width / 2;
    const bl = [-halfW, backY, zBack];
    const br = [halfW, backY, zBack];
    const fl = [-halfW, frontY, zFront];
    const fr = [halfW, frontY, zFront];

    const positions = new Float32Array([
        ...bl, ...br, ...fr, ...bl, ...fr, ...fl,
    ]);
    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.computeVertexNormals();
    return geometry;
}

// A pyramidal (hip) roof meeting at a central peak — the natural shape for a
// 3+ sided tower, matching real multi-sided tower/gazebo roofs. `segments`
// should match the tower's own side count so each roof face sits over one wall.
export function buildPyramidRoofGeometry(THREE, { baseRadius, baseY, apexY, segments, rotationOffset = 0 }) {
    const positions = [];
    for (let i = 0; i < segments; i++) {
        const theta1 = rotationOffset + i * ((2 * Math.PI) / segments);
        const theta2 = rotationOffset + (i + 1) * ((2 * Math.PI) / segments);
        const p1 = [baseRadius * Math.sin(theta1), baseY, baseRadius * Math.cos(theta1)];
        const p2 = [baseRadius * Math.sin(theta2), baseY, baseRadius * Math.cos(theta2)];
        const apex = [0, apexY, 0];
        positions.push(...p1, ...p2, ...apex);
    }
    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(new Float32Array(positions), 3));
    geometry.computeVertexNormals();
    return geometry;
}

// A non-regular hip roof whose base vertices can each sit at their own
// distance from center — used for multi-side towers where one wall overhangs
// much more than its neighbors, so the roof reaches further specifically
// over that side instead of every direction being oversized to match
// whichever single wall overhangs the most (WallViewer3D.vue's addTowerRoof
// computes each vertex's own required reach before calling this).
export function buildAsymmetricHipRoofGeometry(THREE, { vertices, apexY }) {
    const n = vertices.length;
    const positions = [];
    for (let i = 0; i < n; i++) {
        const next = (i + 1) % n;
        const p1 = [vertices[i].x, 0, vertices[i].z];
        const p2 = [vertices[next].x, 0, vertices[next].z];
        const apex = [0, apexY, 0];
        positions.push(...p1, ...p2, ...apex);
    }
    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(new Float32Array(positions), 3));
    geometry.computeVertexNormals();
    return geometry;
}

export function disposeWallMeshGroup(group) {
    if (!group) return;
    const disposed = new Set();
    group.traverse((obj) => {
        if (obj.geometry) obj.geometry.dispose();
        const materials = Array.isArray(obj.material) ? obj.material : obj.material ? [obj.material] : [];
        materials.forEach((m) => {
            if (disposed.has(m)) return;
            disposed.add(m);
            if (m.map) m.map.dispose();
            m.dispose();
        });
    });
}
