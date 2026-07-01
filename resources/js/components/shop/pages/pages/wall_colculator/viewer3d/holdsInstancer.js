// Places climbing holds on the real bolt-hole grid (one hold per hole, never a
// hole under empty air) using one THREE.InstancedMesh per shape variant — never
// one Mesh per hold, counts can run into the hundreds.
//
// Shapes are modeled after real hold photos: faceted low-poly pyramids/wedges
// (the common angular "geometric" hold style), a faceted dome (the rounded
// "jug" style), and a broad flattened dome (a "sloper" — real slopers have no
// defined edge/lip at all, gripping relies purely on surface contact and a
// low, shallow bulge, unlike a jug's taller, rounder profile) — not generic
// icosahedrons/boxes. Each variant's geometry is pre-oriented (baked via
// geometry.rotateX, not a per-instance rotation) so its flat mounting face
// sits at local z=0 and it bulges outward along +z, matching how a real hold
// sits flush against the wall and juts toward the climber.
//
// Placement follows a "route line" model instead of scattering holds
// uniformly at random: real route-setting lays holds along a deliberate path
// up the wall (a setter's "line"), not randomly across the whole surface, and
// every hold on one route keeps a consistent color so climbers can follow it
// (real gyms color-code routes the same way). Each sport-climbing rope line
// becomes its own route, with holds clustering near a meandering path below
// it; bouldering (no ropes) gets a handful of synthetic route lines spread
// across the wall instead.
import { computeBoltHoleGrid } from './panelTexture.js';

const MAX_RENDERED_HOLDS = 600;
export const HOLD_COLORS = [0xe74c3c, 0xf1c40f, 0x2ecc71, 0xff8c00, 0x9b59b6, 0x1abc9c];

// Deterministic PRNG so the same dimensions/count always produce the same hold
// layout instead of the scatter jumping around on every unrelated rebuild.
function mulberry32(seed) {
    let a = seed;
    return function () {
        a |= 0; a = (a + 0x6D2B79F5) | 0;
        let t = Math.imul(a ^ (a >>> 15), 1 | a);
        t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
        return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };
}

function orientedCone(THREE, radius, height, radialSegments) {
    const geometry = new THREE.ConeGeometry(radius, height, radialSegments);
    geometry.translate(0, height / 2, 0); // base at local y=0, tip at y=height
    geometry.rotateX(Math.PI / 2); // base -> flush at z=0, tip -> pokes out along +z
    return geometry;
}

function orientedDome(THREE, radius, segments) {
    const geometry = new THREE.SphereGeometry(radius, segments, Math.max(3, Math.round(segments / 2)), 0, Math.PI * 2, 0, Math.PI / 2);
    geometry.rotateX(Math.PI / 2); // equator ring -> flush at z=0, pole -> pokes out along +z
    return geometry;
}

// A sloper: the same hemisphere as a jug but flattened (scaled down along its
// own pre-rotation pole axis) into a low, broad bulge with no defined lip —
// real slopers rely on surface contact rather than any positive edge to grip.
function orientedSloper(THREE, radius, segments) {
    const geometry = new THREE.SphereGeometry(radius, segments, Math.max(3, Math.round(segments / 2)), 0, Math.PI * 2, 0, Math.PI / 2);
    geometry.scale(1, 0.4, 1); // flatten the dome's height before it's rotated onto +z
    geometry.rotateX(Math.PI / 2);
    return geometry;
}

// A handful of distinct shape variants stand in for real hold variety — a
// single geometry shared by an InstancedMesh can't vary shape per instance,
// only transform, so visual variety has to come from a few separate
// InstancedMeshes rather than per-instance geometry.
function buildHoldVariants(THREE) {
    return [
        // Big rounded "jug" — faceted dome (low segment count keeps the flat
        // geometric-looking facets real holds have, not a smooth sphere).
        { name: 'jug', geometry: orientedDome(THREE, 0.065, 8), elongateAxis: null, scaleRange: [0.85, 1.25] },
        // Small flat angular chip/wedge — 4-sided faceted pyramid.
        { name: 'crimp', geometry: orientedCone(THREE, 0.04, 0.028, 4), elongateAxis: 'x', scaleRange: [0.8, 1.3] },
        // Pointed triangular hold — 3-sided faceted pyramid.
        { name: 'pinch', geometry: orientedCone(THREE, 0.032, 0.05, 3), elongateAxis: 'y', scaleRange: [0.8, 1.3] },
        // Broad low bulge with no edge — surface-contact-only sloper.
        { name: 'sloper', geometry: orientedSloper(THREE, 0.085, 10), elongateAxis: null, scaleRange: [0.8, 1.15] },
    ];
}

// Real route lines aren't straight — they drift left/right as they rise.
// `panelWidth` bounds how far a line can wander; `rand` seeds a random (but
// reproducible) phase/frequency/amplitude per line so different routes on the
// same wall don't all wobble in lockstep.
function makeRouteDrift(panelWidth, rand) {
    return {
        phase: rand() * Math.PI * 2,
        freq: 1.1 + rand() * 0.9,
        amplitude: Math.min(panelWidth * 0.16, 0.55),
    };
}

// Bouldering (no ropes) gets a handful of synthetic route lines spread across
// the wall instead — evenly spaced with a margin from each edge, standing in
// for a gym's usual several parallel "problems" on one wall. Exported so
// callers that build a wall in multiple segments (a bouldering wall's
// vertical base + overhang, built in two separate calls) can compute this
// ONCE and pass the same X positions to both, instead of each segment
// independently re-synthesizing its own set and the "lines" not lining up
// across the seam between them.
export function synthesizeDefaultRouteXs(panelWidth) {
    const margin = Math.min(panelWidth * 0.18, 0.75);
    const usable = Math.max(panelWidth - margin * 2, 0);
    const count = Math.max(1, Math.min(4, Math.round(panelWidth / 2.2)));
    if (count === 1) return [0];
    const xs = [];
    for (let i = 0; i < count; i++) xs.push(-usable / 2 + (i / (count - 1)) * usable);
    return xs;
}

// Sport climbing: one route per rope line, anchored under that rope's own X.
// Bouldering falls back to synthesizeDefaultRouteXs when the caller hasn't
// already resolved routeLines upstream.
function resolveRouteLines(panelWidth, rand, routeLines) {
    const resolved = routeLines && routeLines.length
        ? routeLines
        : synthesizeDefaultRouteXs(panelWidth).map((x) => ({ x }));
    return resolved.map((r, i) => ({
        baseX: r.x || 0,
        color: r.color != null ? r.color : HOLD_COLORS[i % HOLD_COLORS.length],
        ...makeRouteDrift(panelWidth, rand),
    }));
}

// Scores every candidate point by distance to its nearest route's current
// drift target at that point's own height, mixes in a little randomness (a
// real route isn't a perfectly straight line of holds either), then returns
// indices sorted best-first with each point's assigned route attached.
function scoreByRoutes(points, routes, panelWidth, rand) {
    return points
        .map((point) => {
            let best = null;
            routes.forEach((route) => {
                const targetX = route.baseX + Math.sin(point.y * route.freq + route.phase) * route.amplitude;
                const dist = Math.abs(point.x - targetX);
                if (!best || dist < best.dist) best = { dist, route };
            });
            return { point, route: best.route, score: best.dist - rand() * (panelWidth * 0.1) };
        })
        .sort((a, b) => a.score - b.score);
}

// Slightly varies a route's base color per hold (a small hue/lightness
// jitter) so a route reads as "the red line" while individual holds still
// look like distinct, individually-molded pieces rather than one flat color.
function shadeVariant(color, baseHex, rand) {
    color.setHex(baseHex);
    const hsl = { h: 0, s: 0, l: 0 };
    color.getHSL(hsl);
    color.setHSL(
        hsl.h,
        Math.min(1, Math.max(0.35, hsl.s + (rand() - 0.5) * 0.12)),
        Math.min(0.8, Math.max(0.2, hsl.l + (rand() - 0.5) * 0.2))
    );
    return color;
}

// `panelWidth`/`panelLength` are the panel's LOCAL dimensions (length = slant length,
// not world height) since this mesh is added as a child of the tilted wall pivot.
// `routeLines` (optional): [{x, color}] — sport climbing passes each rope
// anchor's own X so holds cluster under the actual protection line; left
// unset for bouldering, which synthesizes its own spread of lines.
// Returns a Group of InstancedMeshes (one per shape variant), or null if there's
// nothing to place.
export function buildHoldsInstancedMesh(THREE, { panelWidth, panelLength, count, surfaceOffset = 0.05, routeLines = null }) {
    const grid = computeBoltHoleGrid(panelWidth, panelLength);
    if (!grid.length) return null;

    const renderCount = Math.max(0, Math.min(Math.round(count) || 0, MAX_RENDERED_HOLDS, grid.length));
    if (renderCount === 0) return null;

    const seed = Math.round((panelWidth * 97 + panelLength * 131 + renderCount * 17) * 1000) % 2147483647;
    const rand = mulberry32(seed || 1);
    const routes = resolveRouteLines(panelWidth, rand, routeLines);
    const scored = scoreByRoutes(grid, routes, panelWidth, rand);
    const chosen = scored.slice(0, renderCount);

    const variants = buildHoldVariants(THREE);
    const buckets = variants.map(() => []);
    chosen.forEach((entry, i) => buckets[i % variants.length].push(entry));

    const group = new THREE.Group();
    group.name = 'holds';

    const matrix = new THREE.Matrix4();
    const scaleVec = new THREE.Vector3();
    const color = new THREE.Color();

    buckets.forEach((entries, variantIndex) => {
        if (!entries.length) return;
        const variant = variants[variantIndex];
        const material = new THREE.MeshStandardMaterial({ roughness: 0.62, metalness: 0.04, vertexColors: true });
        const mesh = new THREE.InstancedMesh(variant.geometry, material, entries.length);
        const [minScale, maxScale] = variant.scaleRange;

        entries.forEach((entry, i) => {
            const { point: pos, route } = entry;
            const baseScale = minScale + rand() * (maxScale - minScale);
            const elongation = 1 + rand() * 0.35;
            const sx = variant.elongateAxis === 'x' ? baseScale * elongation : baseScale;
            const sy = variant.elongateAxis === 'y' ? baseScale * elongation : baseScale;
            const sz = baseScale * (0.85 + rand() * 0.3); // protrusion depth varies a bit independently
            const rotation = rand() * Math.PI * 2;

            matrix.makeRotationZ(rotation);
            matrix.scale(scaleVec.set(sx, sy, sz));
            matrix.setPosition(pos.x, pos.y, surfaceOffset);
            mesh.setMatrixAt(i, matrix);
            mesh.setColorAt(i, shadeVariant(color, route.color, rand));
        });

        mesh.instanceMatrix.needsUpdate = true;
        if (mesh.instanceColor) mesh.instanceColor.needsUpdate = true;
        mesh.name = `holds_${variant.name}`;
        group.add(mesh);
    });

    return group;
}

// Corner-infill triangles (see WallViewer3D.vue's addCornerInfills) are real
// climbing surface too, textured the same as the main panels — they shouldn't
// be the one visibly bare-of-holds patch on an otherwise holds-enabled wall.
// Unlike the rectangular panels, a triangle has no bolt-hole grid to snap to
// (it's a one-off shape, not a standard sheet), so points are scattered by
// barycentric coordinates across the triangle instead, with each hold's own
// baked protrusion axis (local +Z) aligned to the triangle's actual face
// normal so it juts outward correctly regardless of the triangle's tilt.
// `routeColor` (optional) keeps the triangle's holds consistent with whichever
// route continues nearest to it, instead of an unrelated random color.
export function buildTriangleHoldsMesh(THREE, { p1, p2, p3, count, seed = 1, surfaceOffset = 0.045, routeColor = null }) {
    const renderCount = Math.max(0, Math.min(Math.round(count) || 0, MAX_RENDERED_HOLDS));
    if (renderCount === 0) return null;

    const rand = mulberry32(seed || 1);
    const baseColor = routeColor != null ? routeColor : HOLD_COLORS[Math.floor(rand() * HOLD_COLORS.length)];
    const edge1 = new THREE.Vector3().subVectors(p2, p1);
    const edge2 = new THREE.Vector3().subVectors(p3, p1);
    const normal = new THREE.Vector3().crossVectors(edge1, edge2).normalize();

    const variants = buildHoldVariants(THREE);
    const buckets = variants.map(() => []);
    for (let i = 0; i < renderCount; i++) {
        let r1 = rand();
        let r2 = rand();
        if (r1 + r2 > 1) { r1 = 1 - r1; r2 = 1 - r2; }
        const point = p1.clone().addScaledVector(edge1, r1).addScaledVector(edge2, r2);
        buckets[i % variants.length].push(point);
    }

    const group = new THREE.Group();
    group.name = 'holds';

    const zAxis = new THREE.Vector3(0, 0, 1);
    const alignQuat = new THREE.Quaternion().setFromUnitVectors(zAxis, normal);
    const spinQuat = new THREE.Quaternion();
    const finalQuat = new THREE.Quaternion();
    const matrix = new THREE.Matrix4();
    const scaleVec = new THREE.Vector3();
    const posVec = new THREE.Vector3();
    const color = new THREE.Color();

    buckets.forEach((points, variantIndex) => {
        if (!points.length) return;
        const variant = variants[variantIndex];
        const material = new THREE.MeshStandardMaterial({ roughness: 0.62, metalness: 0.04, vertexColors: true });
        const mesh = new THREE.InstancedMesh(variant.geometry, material, points.length);
        const [minScale, maxScale] = variant.scaleRange;

        points.forEach((point, i) => {
            const baseScale = minScale + rand() * (maxScale - minScale);
            const elongation = 1 + rand() * 0.35;
            const sx = variant.elongateAxis === 'x' ? baseScale * elongation : baseScale;
            const sy = variant.elongateAxis === 'y' ? baseScale * elongation : baseScale;
            const sz = baseScale * (0.85 + rand() * 0.3);

            spinQuat.setFromAxisAngle(zAxis, rand() * Math.PI * 2);
            finalQuat.multiplyQuaternions(alignQuat, spinQuat);
            posVec.copy(point).addScaledVector(normal, surfaceOffset);

            matrix.compose(posVec, finalQuat, scaleVec.set(sx, sy, sz));
            mesh.setMatrixAt(i, matrix);
            mesh.setColorAt(i, shadeVariant(color, baseColor, rand));
        });

        mesh.instanceMatrix.needsUpdate = true;
        if (mesh.instanceColor) mesh.instanceColor.needsUpdate = true;
        mesh.name = `holds_${variant.name}`;
        group.add(mesh);
    });

    return group;
}
