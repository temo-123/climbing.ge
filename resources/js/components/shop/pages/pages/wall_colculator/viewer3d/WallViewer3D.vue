<template>
    <div class="wall-viewer-3d" ref="container">
        <canvas ref="canvas"></canvas>
        <div v-if="loading" class="viewer-loading">
            <i class="fa fa-spinner fa-spin"></i> Loading 3D preview...
        </div>
        <div v-if="!loading && (width <= 0 || height <= 0)" class="viewer-empty">
            Enter wall dimensions to see the 3D preview
        </div>
    </div>
</template>

<script>
import { buildWallModel, buildBoulderingModel, buildWallMeshGroup, disposeWallMeshGroup, FRAME_MEMBER_SIZE, PANEL_THICKNESS, buildGableRoofGeometry, buildAsymmetricHipRoofGeometry } from './wallGeometryBuilder.js';
import { buildPanelTexture } from './panelTexture.js';
import { buildTriangleHoldsMesh, HOLD_COLORS } from './holdsInstancer.js';
import { WALL_CONSTRUCTION, computeMatThickness, computeMatSideMargin } from '../wallConstructionConfig.js';

const MAT_DEPTH_MARGIN = WALL_CONSTRUCTION.MAT_DEPTH_MARGIN;
const DEFAULT_MAT_DEPTH = WALL_CONSTRUCTION.DEFAULT_MAT_DEPTH;

export default {
    name: 'WallViewer3D',
    props: {
        width: { type: Number, default: 0 }, // side 1's width
        height: { type: Number, default: 0 }, // shared across every side
        depth: { type: Number, default: 0 }, // side 1's overhang depth
        discipline: { type: String, default: '' }, // 'bouldering' | 'sport_climbing'
        structure: { type: String, default: '' }, // 'indoor' | 'outdoor' | 'standfree_indoor' | 'standfree_outdoor'
        sidesCount: { type: Number, default: 1 },
        extraSides: { type: Array, default: () => [] }, // [{width, depth}, ...] for sides 2-4
        matEnabled: { type: Boolean, default: false },
        // The page's own mat form fields (colculate_mat_size) — user-editable,
        // so the 3D view must actually use them instead of silently
        // recomputing its own guess and ignoring any manual override.
        matWidth: { type: Number, default: 0 },
        matHeight: { type: Number, default: 0 },
        matDepth: { type: Number, default: 0 },
        holdsEnabled: { type: Boolean, default: false },
        holdsCount: { type: Number, default: 0 },
        // Off by default — the CAD-style dimension lines/labels are genuinely
        // useful but also visual clutter on top of the model, so they're
        // opt-in via the page's own "Show Sizes" toggle rather than always on.
        showDimensions: { type: Boolean, default: false },
    },
    emits: ['failed'],
    data() {
        return {
            loading: true,
        };
    },
    computed: {
        isOutdoor() {
            return this.structure === 'outdoor' || this.structure === 'standfree_outdoor';
        },
        isBouldering() {
            return this.discipline === 'bouldering';
        },
        isSportClimbing() {
            return this.discipline === 'sport_climbing';
        },
        isStandFree() {
            return this.structure === 'standfree_indoor' || this.structure === 'standfree_outdoor';
        },
        // Side 1 always comes from width/depth; sides 2-4 (stand-free multi-side
        // towers only) come from extraSides. Always length === sidesCount.
        allSides() {
            const sides = [{ width: this.width, depth: this.depth }];
            for (let i = 0; i < this.sidesCount - 1; i++) {
                const extra = this.extraSides[i] || {};
                sides.push({ width: extra.width || 0, depth: extra.depth || 0 });
            }
            return sides;
        },
    },
    watch: {
        width() { this.rebuildModel(); },
        height() { this.rebuildModel(); },
        depth() { this.rebuildModel(); },
        discipline() { this.rebuildModel(); },
        structure() { this.rebuildModel(); },
        sidesCount() { this.rebuildModel(); },
        extraSides: { deep: true, handler() { this.rebuildModel(); } },
        matEnabled() { this.rebuildModel(); },
        matWidth() { this.rebuildModel(); },
        matHeight() { this.rebuildModel(); },
        matDepth() { this.rebuildModel(); },
        holdsEnabled() { this.rebuildModel(); },
        holdsCount() { this.rebuildModel(); },
        showDimensions() { this.rebuildModel(); },
    },
    mounted() {
        this.initViewer();
        this.stripParentContainerClass();
    },
    beforeUnmount() {
        this.teardownViewer();
        this.restoreParentContainerClass();
    },
    methods: {
        // The page layout wraps routed content in a Bootstrap `.container` (max-width
        // capped), which leaves little room for this scene now that it includes a
        // frame, mat, roof and a human reference figure. Drop that class from the
        // nearest ancestor while the viewer is mounted, restore it on unmount so
        // other pages reusing the same wrapper aren't affected.
        stripParentContainerClass() {
            let el = this.$el ? this.$el.parentElement : null;
            let hops = 0;
            while (el && hops < 6) {
                if (el.classList.contains('container')) {
                    el.classList.remove('container');
                    this._strippedContainerEl = el;
                    break;
                }
                el = el.parentElement;
                hops++;
            }
        },

        restoreParentContainerClass() {
            if (this._strippedContainerEl) {
                this._strippedContainerEl.classList.add('container');
                this._strippedContainerEl = null;
            }
        },
        async initViewer() {
            try {
                const THREE = await import('three');
                const { OrbitControls } = await import('three/examples/jsm/controls/OrbitControls.js');
                const { createViewerScene } = await import('./sceneSetup.js');

                if (!this.$refs.canvas) return; // unmounted while loading

                this._THREE = THREE;
                this._ctx = createViewerScene(THREE, OrbitControls, this.$refs.canvas, this.$refs.container);

                this._resizeObserver = new ResizeObserver(() => this._ctx && this._ctx.resize());
                this._resizeObserver.observe(this.$refs.container);
                this._ctx.resize();

                this.rebuildModel();
                this.loading = false;
                this.startRenderLoop();
            } catch (e) {
                this.loading = false;
                this.$emit('failed', e);
            }
        },

        // A freestanding multi-side tower is built by calling the exact same
        // per-side pipeline used for a single wall, once per side, then fanning
        // each full assembly out around a shared vertical axis at 360/N degree
        // increments — like spokes meeting at a central mast. sidesCount=1 takes
        // angle 0 and radius 0, so this is a strict superset of the original
        // single-wall path, not a fork of it.
        //
        // Critically, each side must also be pushed OUTWARD from that shared axis
        // before rotating — rotating in place left every side's climbing face
        // sitting almost exactly on the axis itself, so all four faces clustered
        // on top of each other with their (offset) support frames ending up
        // outside the (un-offset) panels: back-to-front. The outward push is a
        // regular-polygon radius, so adjacent faces' corners actually meet instead
        // of floating past each other.
        rebuildModel() {
            if (!this._ctx || !this._THREE) return;
            this.clearGroup();
            if (this.width <= 0 || this.height <= 0) return;

            const sides = this.allSides;
            const holdsPerSide = this.splitHoldsAcrossSides(sides);
            const isTower = sides.length > 1;
            const radius = this.computeTowerRadius(sides);
            const thetas = this.computeTowerThetas(sides);
            const towerRoofY = this.height + 0.14; // kept tight to the tower's own height, not a big fixed offset
            const master = new this._THREE.Group();

            sides.forEach((side, index) => {
                const w = parseFloat(side.width) || 0;
                if (w <= 0) return;
                const d = parseFloat(side.depth) || 0;

                // matHeight/matDepth come straight from the page's own mat form
                // fields when the user has actually set them (colculate_mat_size)
                // — falling back to a same-formula guess only while those are
                // still unset, so a manual override always wins instead of
                // being silently ignored by the 3D view. A taller wall means a
                // longer, harder fall even on a wall that isn't very overhung,
                // so the pad's thickness tracks the wall's own height, not
                // this side's overhang depth.
                const matHeightValue = this.matHeight > 0 ? this.matHeight : computeMatThickness(this.height);
                const matDepthValue = this.matDepth > 0
                    ? this.matDepth
                    : (d > 0 ? d + (d * MAT_DEPTH_MARGIN) : DEFAULT_MAT_DEPTH);

                const matHeightHint = this.matEnabled ? matHeightValue : 0;
                const model = this.isBouldering
                    ? buildBoulderingModel({ width: w, height: this.height, depth: d, matHeightHint })
                    : buildWallModel({ width: w, height: this.height, depth: d });

                // Towers get one continuous mat ring around the whole footprint
                // (built below) instead of one rectangle per face — separate
                // per-face mats left gaps at every corner, right where a climber
                // falling off near the corner would miss the mat entirely. Real
                // matting standards extend the pad to the side by roughly half
                // the wall's own height, not just exactly the wall's width.
                // A single wall's mat has a genuinely straight-edged shape a
                // manual width can describe (unlike a 3+ sided tower's ring), so
                // a user-set matWidth wins here the same way matHeight/matDepth
                // already do above, instead of always being silently overridden.
                const mat = !isTower && this.matEnabled && !this.isSportClimbing
                    ? { width: this.matWidth > 0 ? this.matWidth : w + computeMatSideMargin(this.height) * 2, height: matHeightValue, depth: matDepthValue }
                    : null;

                const group = buildWallMeshGroup(this._THREE, model, {
                    // A tower gets one shared cap roof over the whole footprint
                    // (built below) instead of one per-face roof. Note isStandFree
                    // itself must stay truthful here (never force it false for
                    // towers) — a non-stand-free structure can never reach
                    // sidesCount > 1 in the wizard, so any tower IS stand-free by
                    // construction, and this flag also controls whether the
                    // backing building-wall gets skipped.
                    isOutdoor: !isTower && this.isOutdoor,
                    isStandFree: this.isStandFree,
                    isTowerSide: isTower,
                    showRope: this.isSportClimbing,
                    mat,
                    holdsEnabled: this.holdsEnabled,
                    holdsCount: holdsPerSide[index],
                    showHuman: index === 0, // one reference figure total, not one per face
                });

                // Each face gets its own header panel closing the gap up to the
                // shared tower roof — added as a child of this side's own group so
                // it inherits the exact same position/rotation as the panel behind
                // it, without recomputing any per-side trig separately.
                if (isTower && this.isOutdoor) {
                    const gapHeight = towerRoofY - this.height;
                    if (gapHeight > 0.02) {
                        const fascia = new this._THREE.Mesh(
                            new this._THREE.BoxGeometry(w, gapHeight, 0.08),
                            new this._THREE.MeshStandardMaterial({ color: 0xb89770, roughness: 0.85, metalness: 0.02 })
                        );
                        fascia.position.set(0, this.height + gapHeight / 2, d);
                        fascia.name = 'tower_roof_fascia';
                        group.add(fascia);
                    }
                }

                // CAD-style sizing overlay (width/height/overhang dimension lines
                // + labels) — added as children of this side's own LOCAL (pre-
                // offset, pre-rotation) group, so they inherit the exact same
                // radius offset + tower rotation as the wall panel itself
                // without any separate trig. One shared height dimension only
                // (index 0) — every side shares the same height, so a tower
                // showing it N times would just be N overlapping copies.
                if (this.showDimensions) this.addSideDimensions(group, w, d, index === 0);

                group.position.z = radius;

                const pivot = new this._THREE.Group();
                pivot.rotation.y = isTower ? thetas[index] : 0;
                pivot.add(group);
                master.add(pivot);
            });

            if (isTower) {
                if (this.isOutdoor) this.addTowerRoof(master, sides, radius, towerRoofY, thetas);
                if (this.matEnabled && !this.isSportClimbing) this.addTowerMat(master, sides, radius);
                if (sides.length >= 2) this.addCornerInfills(master, sides, radius, thetas);
            }

            this._ctx.scene.add(master);
            this._group = master;

            this.frameCamera(sides, radius);
        },

        // Regular-polygon apothem: for N faces of (average) width W meeting edge
        // to edge, the center-to-face distance is W / (2*tan(pi/N)). A 2-sided
        // wall isn't a real polygon (tan(pi/2) is undefined) — it's two faces
        // mounted back-to-back on one narrow shared spine (like a real
        // double-sided systems board), not radiating faces meeting at a hollow
        // core. Sized as a genuine — if slim — structural gap: 0.3-0.6m
        // front-to-back total (radius*2), scaling gently with the tower's own
        // width rather than the old near-zero hairline that left no real room
        // for a shared support frame between the two faces.
        computeTowerRadius(sides) {
            const n = sides.length;
            if (n <= 1) return 0;
            const avgWidth = sides.reduce((sum, s) => sum + (parseFloat(s.width) || 0), 0) / n;
            if (n === 2) return Math.min(Math.max(avgWidth * 0.08, 0.15), 0.3);
            return avgWidth / (2 * Math.tan(Math.PI / n));
        },

        // Each side's own rotation around the shared vertical axis — the
        // regular-polygon step (360/n), same as every side has always used.
        // Kept as an array (rather than computing `index * step` inline
        // wherever it's needed) so towerCorner/addCornerInfills/the roof's own
        // reach interpolation all read from one shared source of truth.
        computeTowerThetas(sides) {
            const n = sides.length;
            const step = (2 * Math.PI) / n;
            const thetas = [0];
            for (let i = 1; i < n; i++) thetas.push(thetas[i - 1] + step);
            return thetas;
        },

        // The exact same closed-form corner math used to verify adjacent faces'
        // BOTTOM corners always coincide when every side uses the default
        // (regular-polygon) angle also gives each side's TOP corners — which
        // only coincide with its neighbor's when neither side overhangs.
        // `radius` and `thetas` are threaded through unchanged from
        // rebuildModel so this never drifts out of sync with where the panels
        // actually are (or, for a custom per-corner angle, with the actual
        // rotation used there instead of assuming a uniform 360/n step).
        towerCorner(sides, radius, thetas, sideIndex, isRight, isTop) {
            const side = sides[sideIndex];
            const halfW = (parseFloat(side.width) || 0) / 2;
            const d = parseFloat(side.depth) || 0;
            const localX = isRight ? halfW : -halfW;
            const localY = isTop ? this.height : 0;
            const localZ = (isTop ? d : 0) + radius;
            const theta = thetas[sideIndex];
            const cos = Math.cos(theta);
            const sin = Math.sin(theta);
            return new this._THREE.Vector3(localX * cos + localZ * sin, localY, -localX * sin + localZ * cos);
        },

        // Adjacent faces share a bottom corner by construction WHEN both use
        // the default (regular-polygon) angle — but a custom corner angle
        // (see computeTowerThetas) can open a real gap at the base too, not
        // just at the top from differing overhangs. Two cases:
        //
        // - Bases coincide (the common case): a single flat triangle from
        //   that shared point to each side's own top corner closes the
        //   overhang-driven gap while it's modest — but once the top corners
        //   have pulled apart far enough that the direct diagonal between
        //   them would be LONGER than the wall itself is tall, that one
        //   diagonal reads as an oversized, twisted blade rather than a
        //   corner closure. Splitting it with a vertical corner post instead
        //   — a plumb line from the shared bottom corner straight up to the
        //   wall's own top height, the same detail a real building uses at an
        //   outside corner where two differently-pitched roof planes meet —
        //   replaces that one long diagonal with two shorter, better-behaved
        //   triangles. Only switching to the split when it actually shortens
        //   the longest member keeps the plain single triangle for the
        //   common, modest-overhang case where it's already the tighter shape.
        // - Bases DON'T coincide (a custom angle): a proper quadrilateral
        //   between all four distinct corners, split along whichever
        //   diagonal is shorter — the standard way to triangulate a general
        //   quad without folding it into a bad, twisted shape.
        addCornerInfills(master, sides, radius, thetas) {
            const n = sides.length;
            const density = this.holdDensityPerSqm(sides);

            for (let i = 0; i < n; i++) {
                const next = (i + 1) % n;
                const bottomA = this.towerCorner(sides, radius, thetas, i, true, false);
                const bottomB = this.towerCorner(sides, radius, thetas, next, false, false);
                const topA = this.towerCorner(sides, radius, thetas, i, true, true);
                const topB = this.towerCorner(sides, radius, thetas, next, false, true);

                const baseGap = bottomA.distanceTo(bottomB);
                const topGap = topA.distanceTo(topB);
                if (baseGap < 0.02 && topGap < 0.02) continue; // nothing to fill

                let triangles;
                if (baseGap < 0.02) {
                    const bottomShared = bottomA;
                    const topShared = new this._THREE.Vector3(bottomShared.x, this.height, bottomShared.z);
                    const worstSplitEdge = Math.max(
                        topShared.distanceTo(topA), topShared.distanceTo(topB), bottomShared.distanceTo(topShared)
                    );
                    triangles = worstSplitEdge < topGap
                        ? [[bottomShared, topA, topShared], [bottomShared, topShared, topB]]
                        : [[bottomShared, topA, topB]];
                } else {
                    const diagA = bottomA.distanceTo(topB);
                    const diagB = topA.distanceTo(bottomB);
                    triangles = diagA <= diagB
                        ? [[bottomA, topA, topB], [bottomA, topB, bottomB]]
                        : [[bottomA, topA, bottomB], [topA, topB, bottomB]];
                }

                triangles.forEach(([p1, p2, p3], triIndex) => {
                    this.buildCornerInfillTriangle(master, p1, p2, p3, { i, triIndex, density });
                });
            }
        },

        // Builds one corner-infill triangle's textured surface, frame beams,
        // and holds — shared between the plain single-triangle closure and
        // each half of the split (vertical-post) closure, so both cases get
        // identical treatment instead of duplicated logic.
        buildCornerInfillTriangle(master, p1, p2, p3, { i, triIndex, density }) {
            const span = Math.max(p1.distanceTo(p2), p1.distanceTo(p3), p2.distanceTo(p3));

            // It's real climbing surface, not a cosmetic filler — textured with
            // the same plywood/bolt-hole look as the main panels (mapped onto
            // the triangle via UVs: p1 -> texture bottom-center, p2/p3 -> the
            // texture's top-left/top-right), not a flat color.
            const material = new this._THREE.MeshStandardMaterial({
                color: 0xffffff, roughness: 0.92, metalness: 0, side: this._THREE.DoubleSide,
                map: buildPanelTexture(this._THREE, Math.max(span, 0.3), this.height),
            });

            const geometry = new this._THREE.BufferGeometry();
            const positions = new Float32Array([
                p1.x, p1.y, p1.z,
                p2.x, p2.y, p2.z,
                p3.x, p3.y, p3.z,
            ]);
            geometry.setAttribute('uv', new this._THREE.BufferAttribute(new Float32Array([
                0.5, 0,
                0, 1,
                1, 1,
            ]), 2));
            geometry.setAttribute('position', new this._THREE.BufferAttribute(positions, 3));
            geometry.computeVertexNormals();
            const mesh = new this._THREE.Mesh(geometry, material);
            mesh.name = 'corner_infill';
            master.add(mesh);

            this.addCornerInfillFrame(master, p1, p2, p3);

            // Holds on the triangle too — it's real climbing surface, not
            // just a cosmetic gap-filler, so it shouldn't be the one bare
            // patch on an otherwise holds-covered wall. buildTriangleHoldsMesh
            // orients each hold to the triangle's own face normal, which
            // points outward only for one particular vertex winding — swap
            // the last two points if the natural winding here points inward.
            if (density > 0) {
                const edgeAB = new this._THREE.Vector3().subVectors(p2, p1);
                const edgeAC = new this._THREE.Vector3().subVectors(p3, p1);
                const normal = new this._THREE.Vector3().crossVectors(edgeAB, edgeAC).normalize();
                const outwardRef = new this._THREE.Vector3(p1.x, 0, p1.z).normalize();
                const pointsOutward = normal.dot(outwardRef) >= 0;
                const q2 = pointsOutward ? p2 : p3;
                const q3 = pointsOutward ? p3 : p2;

                const triangleArea = 0.5 * edgeAB.clone().cross(edgeAC).length();
                const holdCount = Math.round(density * triangleArea);
                if (holdCount > 0) {
                    const seed = Math.round((span * 137 + this.height * 71 + i * 997 + triIndex * 353) * 1000) % 2147483647;
                    const holds = buildTriangleHoldsMesh(this._THREE, {
                        p1, p2: q2, p3: q3, count: holdCount, seed: seed || 1,
                        routeColor: HOLD_COLORS[i % HOLD_COLORS.length],
                    });
                    if (holds) master.add(holds);
                }
            }
        },

        // Framing behind the corner triangle, matching the main walls' 8x8cm
        // lumber — one beam per edge, offset toward the tower's interior along
        // the triangle's own face normal so it stays hidden behind the plywood
        // exactly like the stud frame does, instead of the adjacent side's frame
        // showing through an unframed gap.
        addCornerInfillFrame(master, bottomShared, topA, topB) {
            const frameMaterial = new this._THREE.MeshStandardMaterial({ color: 0xb89770, roughness: 0.85, metalness: 0.02 });

            const edgeAB = new this._THREE.Vector3().subVectors(topA, bottomShared);
            const edgeAC = new this._THREE.Vector3().subVectors(topB, bottomShared);
            const normal = new this._THREE.Vector3().crossVectors(edgeAB, edgeAC).normalize();
            // The triangle's outward side is away from the shared vertical axis —
            // bottomShared's own horizontal direction from that axis is a reliable
            // outward reference for any N and any rotation.
            const outwardRef = new this._THREE.Vector3(bottomShared.x, 0, bottomShared.z).normalize();
            if (normal.dot(outwardRef) < 0) normal.negate();
            const inset = normal.clone().multiplyScalar(-(FRAME_MEMBER_SIZE / 2 + PANEL_THICKNESS / 2));

            const addEdgeBeam = (p1, p2) => {
                const length = p1.distanceTo(p2);
                if (length < 0.05) return;
                const mid = new this._THREE.Vector3().addVectors(p1, p2).multiplyScalar(0.5).add(inset);
                const beam = new this._THREE.Mesh(
                    new this._THREE.BoxGeometry(FRAME_MEMBER_SIZE, FRAME_MEMBER_SIZE, length),
                    frameMaterial
                );
                beam.position.copy(mid);
                beam.lookAt(new this._THREE.Vector3().addVectors(p2, inset));
                beam.name = 'corner_infill_frame';
                master.add(beam);
            };

            addEdgeBeam(bottomShared, topA);
            addEdgeBeam(bottomShared, topB);
            addEdgeBeam(topA, topB);
        },

        // One continuous mat covering the whole tower's footprint — separate
        // mats per face left the corners (exactly where a climber is likely to
        // fall from an outside edge) uncovered.
        addTowerMat(master, sides, radius) {
            const maxWidth = Math.max(...sides.map((s) => parseFloat(s.width) || 0));
            const maxDepth = Math.max(0, ...sides.map((s) => parseFloat(s.depth) || 0));
            const matThickness = this.matHeight > 0 ? this.matHeight : computeMatThickness(this.height);
            const matReach = this.matDepth > 0
                ? this.matDepth
                : (maxDepth > 0 ? maxDepth + maxDepth * MAT_DEPTH_MARGIN : DEFAULT_MAT_DEPTH);
            // Only the 2-sided (rectangular, back-to-back) case below actually
            // uses matWidth as a single straight edge — the 3+ sided ring further
            // down is sized off maxWidth/radius directly, so a user-set matWidth
            // only wins here, matching matWidthUsable on the price page.
            const matWidth = (this.matWidth > 0 && sides.length === 2)
                ? this.matWidth
                : maxWidth + computeMatSideMargin(this.height) * 2;
            const matMaterial = new this._THREE.MeshStandardMaterial({ color: 0xd64545, roughness: 0.95 });

            let geometry;
            if (sides.length === 2) {
                // Two faces back-to-back on a narrow shared spine (see
                // computeTowerRadius) have a genuinely RECTANGULAR footprint,
                // not a circular one — spans both faces' own fall zones plus
                // the spine's own width between them. A polygon approximating
                // a circle (used below for 3+ sides) doesn't fit a shape this
                // elongated along one axis, however it's rotated.
                const matDepthTotal = matReach * 2 + radius * 2;
                geometry = new this._THREE.BoxGeometry(matWidth, matThickness, matDepthTotal);
            } else {
                const faceReach = radius + maxWidth / 2 + matReach;
                const matSegments = sides.length * 2;

                // CylinderGeometry's own vertex 0 sits exactly on the +Z axis —
                // the SAME world direction side 0's wall faces (thetas[0] ===
                // 0) — so without a rotation offset every wall's own outward
                // direction lands on a polygon VERTEX (a sharp point jutting
                // straight out in front of the wall) instead of a flat edge,
                // while the empty gaps between walls get a flat edge instead
                // of the corner they actually need. This read as a mat rotated
                // diagonally out of alignment with the walls it's supposed to
                // sit under. Half a segment's rotation (thetaStart) puts a
                // flat edge in front of each wall instead.
                const thetaStart = Math.PI / matSegments;
                // A flat edge's own center reach is only cos(pi/segments) of
                // the circumradius used for its corner vertices — inflate the
                // radius so the flat edge directly in front of each wall still
                // reaches the intended faceReach (the same apothem correction
                // already used for the tower roof, see
                // computeTowerRoofReach/addTowerRoof).
                const apothemCorrection = 1 / Math.cos(Math.PI / matSegments);
                const outerRadius = faceReach * apothemCorrection;
                geometry = new this._THREE.CylinderGeometry(outerRadius, outerRadius, matThickness, matSegments, 1, false, thetaStart);
            }

            const mesh = new this._THREE.Mesh(geometry, matMaterial);
            mesh.position.set(0, matThickness / 2, 0);
            mesh.name = 'tower_mat';
            master.add(mesh);
        },

        // One flat cap roof sized to the tower's actual footprint (radius + the
        // widest face's half-width + a small eave), not a single face's — the
        // old per-face roof was sized for one wall and looked wildly oversized
        // once it was the only roof rendered for an entire multi-side tower.
        // Kept tight to the tower's own footprint (a small fixed eave, not a big
        // fixed offset that balloons on small towers) — `roofY` is threaded in
        // from rebuildModel so every per-face fascia panel lines up with this
        // roof exactly, instead of two separate formulas drifting apart.
        // A proper N-sided cap matching the tower's own shape (a rectangle for
        // the 2-side spine case), not a fixed square unrelated to how many walls
        // are actually configured. Sized by each side's own TOP reach — radius
        // plus that side's overhang depth plus its half-width — so an
        // overhanging tower gets a correspondingly bigger roof instead of one
        // sized only from the (smaller) base footprint. No separate corner
        // support posts: the per-side fascia panel (added in rebuildModel)
        // already rises from each wall's own top edge to meet this roof, so the
        // roof reads as resting directly on the tower instead of floating on
        // disconnected legs out past the actual structure.
        // A real pitched roof, not a flat cap: a pyramid meeting at a central
        // peak for 3+ sided towers (matching real multi-sided tower/gazebo
        // roofs — one face per wall), or a gabled ridge roof for the 2-sided
        // spine case (the same shape the single-wall roof uses, just sized to
        // the whole tower footprint instead of one face).
        // Each wall gets its OWN required roof reach (radius + that wall's own
        // overhang depth + half its width), not one uniform value sized to
        // whichever single wall overhangs the most — a tower with one deeply
        // overhung side and otherwise modest sides now gets a correspondingly
        // bigger roof specifically over that side, matching how a real hip
        // roof follows the building's own footprint rather than being forced
        // into a perfect regular polygon.
        computeTowerRoofReach(sides, radius) {
            const eaveMargin = 0.45;
            const wallReach = sides.map((s) => {
                const w = parseFloat(s.width) || 0;
                const d = parseFloat(s.depth) || 0;
                return radius + d + w / 2 + eaveMargin;
            });

            // Bouldering towers (3-4 sides): the roof should cover at least as
            // much footprint as the safety mat below it everywhere — a
            // landing area left outside the roof's own cover defeats the
            // point of a roof at all. Uses the exact same reach formula as
            // addTowerMat so the two always agree instead of drifting apart.
            if (this.isBouldering && this.matEnabled && sides.length >= 3) {
                const maxWidth = Math.max(...sides.map((s) => parseFloat(s.width) || 0));
                const maxDepth = Math.max(0, ...sides.map((s) => parseFloat(s.depth) || 0));
                const matReach = this.matDepth > 0
                    ? this.matDepth
                    : (maxDepth > 0 ? maxDepth + maxDepth * MAT_DEPTH_MARGIN : DEFAULT_MAT_DEPTH);
                const matFloor = radius + maxWidth / 2 + matReach;
                for (let i = 0; i < wallReach.length; i++) wallReach[i] = Math.max(wallReach[i], matFloor);
            }
            return wallReach;
        },

        addTowerRoof(master, sides, radius, roofY, thetas) {
            const n = sides.length;
            const wallReach = this.computeTowerRoofReach(sides, radius);
            const maxWallReach = Math.max(...wallReach);
            const roofMaterial = new this._THREE.MeshStandardMaterial({
                color: 0x9c3b30, roughness: 0.75, metalness: 0.05, side: this._THREE.DoubleSide,
            });

            let roofMesh;
            if (n >= 3) {
                // More roof facets than wall sides softens the peak — a real
                // gazebo/tented roof commonly uses 6-8 facets regardless of
                // how many walls the building below actually has, since a
                // 3-sided tower's roof meeting at a full 120°-per-face apex
                // reads as absurdly sharp; more, narrower facets read as a
                // much gentler dome (checked against real hip/tented-roof
                // reference photos).
                const roofSegments = Math.max(n, 6);
                // Same apothem correction as before (a face's own reach at its
                // center is less than its two vertices' reach — cos(pi/segments)
                // short of it), now applied per-vertex since each one can have
                // its own required reach instead of one shared circumradius.
                const apothemCorrection = 1 / Math.cos(Math.PI / roofSegments);
                // thetas only covers side 0..n-1 (cumulative rotation from side
                // 0) — append the closing point (a full turn back to side 0)
                // so interpolation has an upper bound for the last wall's own
                // span, whether every side used the default angle (closing
                // exactly at 2π) or a custom angle left a real gap there.
                const wallThetas = [...thetas, thetas[0] + 2 * Math.PI];

                const vertices = [];
                for (let i = 0; i < roofSegments; i++) {
                    const theta = i * ((2 * Math.PI) / roofSegments);
                    // Which two adjacent walls does this roof vertex's angle fall
                    // between, and how far — linear interpolation between their
                    // own required reaches gives every roof vertex a sensible
                    // reach even though it doesn't sit on any one wall's own
                    // center direction. Bounded loop (not a assuming-sorted
                    // binary search) so an unusual combination of custom
                    // angles can never hang this in an infinite loop, only
                    // produce an odd-looking (but still valid) roof.
                    let idx0 = 0;
                    for (let j = 0; j < n - 1; j++) {
                        if (wallThetas[j + 1] > theta) { idx0 = j; break; }
                        idx0 = j + 1;
                    }
                    const idx1 = (idx0 + 1) % n;
                    const span = wallThetas[idx0 + 1] - wallThetas[idx0];
                    const frac = span > 1e-6 ? (theta - wallThetas[idx0]) / span : 0;
                    const reach = (wallReach[idx0] * (1 - frac) + wallReach[idx1] * frac) * apothemCorrection;
                    vertices.push({ x: reach * Math.sin(theta), z: reach * Math.cos(theta) });
                }

                const apexRise = Math.max(maxWallReach * 0.4, 0.6);
                const geometry = buildAsymmetricHipRoofGeometry(this._THREE, { vertices, apexY: apexRise });
                roofMesh = new this._THREE.Mesh(geometry, roofMaterial);
                roofMesh.position.set(0, roofY, 0);
            } else {
                const eaveMargin = 0.45;
                const maxWidth = Math.max(...sides.map((s) => parseFloat(s.width) || 0));
                const roofWidth = maxWidth + eaveMargin * 2;
                const ridgeRise = Math.max(maxWallReach * 0.25, 0.5);
                const geometry = buildGableRoofGeometry(this._THREE, {
                    width: roofWidth, eaveY: 0, ridgeY: ridgeRise, zBack: -maxWallReach, zFront: maxWallReach,
                });
                roofMesh = new this._THREE.Mesh(geometry, roofMaterial);
                roofMesh.position.set(0, roofY, 0);
            }
            roofMesh.name = 'tower_roof';
            master.add(roofMesh);
        },

        // Distribute the priced hold count across sides proportional to each
        // side's own climbing surface area, the same idea already used to split
        // holds between a bouldering wall's vertical and overhang segments.
        splitHoldsAcrossSides(sides) {
            const areas = sides.map((side) => {
                const w = parseFloat(side.width) || 0;
                if (w <= 0) return 0;
                const d = parseFloat(side.depth) || 0;
                const slant = d > 0 ? Math.sqrt(this.height * this.height + d * d) : this.height;
                return w * slant;
            });
            const totalArea = areas.reduce((a, b) => a + b, 0) || 1;
            const counts = areas.map((a) => Math.round(this.holdsCount * (a / totalArea)));
            return counts;
        },

        // Same holds-per-square-meter density as the main panels, so a corner
        // triangle's own hold count (see addCornerInfills) matches how densely
        // covered the rest of the wall is instead of being an arbitrary guess.
        holdDensityPerSqm(sides) {
            if (!this.holdsEnabled || !this.holdsCount) return 0;
            const totalArea = sides.reduce((sum, side) => {
                const w = parseFloat(side.width) || 0;
                if (w <= 0) return sum;
                const d = parseFloat(side.depth) || 0;
                const slant = d > 0 ? Math.sqrt(this.height * this.height + d * d) : this.height;
                return sum + w * slant;
            }, 0);
            return totalArea > 0 ? this.holdsCount / totalArea : 0;
        },

        frameCamera(sides, radius) {
            let maxReach = this.height;
            sides.forEach((side) => {
                const w = parseFloat(side.width) || 0;
                const d = parseFloat(side.depth) || 0;
                const slant = Math.sqrt(this.height * this.height + d * d);
                // A tower face's true reach from the shared center is the polygon
                // radius plus its own depth/slant, not just its own dimensions.
                maxReach = Math.max(maxReach, radius + w / 2 + 1.2, radius + slant);
            });
            if (this.matEnabled) maxReach += 0.8;
            if (sides.length <= 1 && this.isStandFree) maxReach += 1.4;

            if (sides.length <= 1) {
                // Single wall: keep the camera on the +z (climbing) side, looking
                // straight at the overhang the way a climber would see it.
                const d = parseFloat(sides[0].depth) || 0;
                this._ctx.controls.target.set(0, this.height * 0.5, d * 0.3);
                const distance = Math.max(maxReach * 1.6, 4);
                this._ctx.camera.position.set(distance * 0.5, this.height * 0.55 + maxReach * 0.3, distance * 0.9 + d * 0.5);
            } else {
                // Multi-side tower: faces radiate in every direction, so frame it
                // from an elevated 3/4 angle that doesn't stare straight into any
                // single face's back. Math.PI/sides.length aims the camera at the
                // "corner" between two adjacent faces — correct for a real 3+
                // sided polygon, but a 2-sided tower's two faces are 180° apart
                // (back-to-back, not adjacent polygon corners), so that formula
                // works out to exactly 90°: a camera sitting dead-on the axis
                // the panels are widest along, staring straight down their edge
                // instead of at their face. Matches the single-wall camera's own
                // oblique angle instead (~30°, i.e. atan(0.5/0.9) from its x/z
                // position ratio above) since a 2-sided tower is really just two
                // single walls back-to-back, not a genuine multi-face polygon.
                const distance = maxReach * 2.2;
                const azimuth = sides.length === 2 ? Math.PI / 6 : Math.PI / sides.length;
                this._ctx.controls.target.set(0, this.height * 0.5, 0);
                this._ctx.camera.position.set(
                    distance * Math.sin(azimuth),
                    this.height * 0.7 + maxReach * 0.4,
                    distance * Math.cos(azimuth)
                );
            }

            this._ctx.controls.update();
        },

        // Real construction-drawing sizing on the live 3D model itself, not
        // just the 2D/PDF exports: a width dimension along the base (pulled
        // behind the wall, clear of the support frame), one shared height
        // dimension down the left edge, and an overhang dimension along the
        // top when the wall actually leans. Local wall-space coordinates
        // (same space addClimbingPanel etc. use, before the per-side radius
        // offset/tower rotation applied in rebuildModel) so these automatically
        // land in the right place for every side without separate trig.
        addSideDimensions(parent, w, d, showHeight) {
            const halfW = w / 2;
            const h = this.height;

            this.addDimensionLine(parent,
                { x: -halfW, y: -0.1, z: -0.35 },
                { x: halfW, y: -0.1, z: -0.35 },
                this.fmtDimension(w));

            if (showHeight) {
                this.addDimensionLine(parent,
                    { x: -halfW - 0.3, y: 0, z: 0 },
                    { x: -halfW - 0.3, y: h, z: 0 },
                    this.fmtDimension(h));
            }

            if (d > 0.001) {
                this.addDimensionLine(parent,
                    { x: halfW + 0.3, y: h, z: 0 },
                    { x: halfW + 0.3, y: h, z: d },
                    this.fmtDimension(d));
            }
        },

        // Standard CAD dimension: a line between two points, small perpendicular
        // tick marks at each end, and a text label at the midpoint. Every
        // dimension line built here is axis-aligned (along X or Z), so a fixed
        // vertical (+/-Y) tick direction is enough — no need for a general
        // perpendicular-to-an-arbitrary-3D-line calculation.
        addDimensionLine(parent, from, to, label) {
            const THREE = this._THREE;
            const material = new THREE.LineBasicMaterial({ color: 0x2a6fb0 });

            const main = new THREE.BufferGeometry().setFromPoints([
                new THREE.Vector3(from.x, from.y, from.z),
                new THREE.Vector3(to.x, to.y, to.z),
            ]);
            const line = new THREE.Line(main, material);
            line.name = 'dimension_line';
            parent.add(line);

            const tick = 0.05;
            [from, to].forEach((p) => {
                const tickGeom = new THREE.BufferGeometry().setFromPoints([
                    new THREE.Vector3(p.x, p.y - tick, p.z),
                    new THREE.Vector3(p.x, p.y + tick, p.z),
                ]);
                const tickLine = new THREE.Line(tickGeom, material);
                tickLine.name = 'dimension_tick';
                parent.add(tickLine);
            });

            const label3d = this.buildDimensionLabel(label);
            label3d.position.set((from.x + to.x) / 2, (from.y + to.y) / 2, (from.z + to.z) / 2);
            parent.add(label3d);
        },

        // Canvas-texture text sprite (same technique buildPanelTexture already
        // uses for the panel surface) — depthTest disabled so the label always
        // reads on top of the panel/mat/frame geometry behind it, like a real
        // CAD annotation overlay rather than a physical object that can be
        // occluded.
        buildDimensionLabel(text) {
            const THREE = this._THREE;
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const fontSize = 48;
            ctx.font = `600 ${fontSize}px Helvetica, Arial, sans-serif`;
            const textWidth = ctx.measureText(text).width;
            canvas.width = Math.ceil(textWidth) + 24;
            canvas.height = fontSize + 20;

            ctx.font = `600 ${fontSize}px Helvetica, Arial, sans-serif`;
            ctx.fillStyle = 'rgba(255,255,255,0.92)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.strokeStyle = '#2a6fb0';
            ctx.lineWidth = 3;
            ctx.strokeRect(1.5, 1.5, canvas.width - 3, canvas.height - 3);
            ctx.fillStyle = '#1a1a2e';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(text, canvas.width / 2, canvas.height / 2 + 2);

            const texture = new THREE.CanvasTexture(canvas);
            texture.needsUpdate = true;
            const spriteMaterial = new THREE.SpriteMaterial({ map: texture, depthTest: false, depthWrite: false });
            const sprite = new THREE.Sprite(spriteMaterial);
            sprite.name = 'dimension_label';
            sprite.renderOrder = 999;
            const worldHeight = 0.22;
            sprite.scale.set(worldHeight * (canvas.width / canvas.height), worldHeight, 1);
            return sprite;
        },

        fmtDimension(meters) {
            const m = parseFloat(meters) || 0;
            const s = m.toFixed(2).replace(/0+$/, '').replace(/\.$/, '');
            return s + 'm';
        },

        clearGroup() {
            if (this._group && this._ctx) {
                this._ctx.scene.remove(this._group);
                disposeWallMeshGroup(this._group);
            }
            this._group = null;
        },

        startRenderLoop() {
            const renderFrame = () => {
                this._rafId = requestAnimationFrame(renderFrame);
                this._ctx.controls.update();
                this._ctx.renderer.render(this._ctx.scene, this._ctx.camera);
            };
            renderFrame();
        },

        // For the PDF export: temporarily swings the camera to a few standard
        // framing angles (not just whatever the user happens to be looking
        // at interactively), renders each, and captures it as a PNG data URL
        // — then restores the exact camera/target the user had before this
        // was called, so calling this never visibly disturbs the live,
        // interactive view. Calling renderer.render() then immediately
        // toDataURL() in the same synchronous call (not waiting for the next
        // animation frame) is what makes this work reliably even though the
        // renderer isn't configured with preserveDrawingBuffer.
        captureSnapshots() {
            if (!this._ctx || !this._group || !this._THREE) return [];
            const THREE = this._THREE;
            const { camera, controls, renderer, scene } = this._ctx;

            const originalPos = camera.position.clone();
            const originalTarget = controls.target.clone();
            const originalUp = camera.up.clone();

            const box = new THREE.Box3().setFromObject(this._group);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());
            const maxDim = Math.max(size.x, size.y, size.z, 1);
            const distance = maxDim * 1.7;

            const views = [
                { label: 'Front Elevation', azimuth: 0.001, elevation: 0.06 },
                { label: 'Front 3/4 View', azimuth: Math.PI * 0.22, elevation: 0.35 },
                { label: 'Side View', azimuth: Math.PI / 2, elevation: 0.06 },
                { label: 'Rear 3/4 View', azimuth: Math.PI * 1.22, elevation: 0.35 },
                { label: 'Overhead Plan View', azimuth: 0.001, elevation: Math.PI / 2 - 0.3 },
            ];

            const snapshots = views.map((v) => {
                const x = center.x + distance * Math.cos(v.elevation) * Math.sin(v.azimuth);
                const y = center.y + distance * Math.sin(v.elevation);
                const z = center.z + distance * Math.cos(v.elevation) * Math.cos(v.azimuth);
                camera.position.set(x, y, z);
                camera.up.set(0, 1, 0);
                camera.lookAt(center);
                camera.updateProjectionMatrix();
                renderer.render(scene, camera);
                return { label: v.label, data_url: renderer.domElement.toDataURL('image/png') };
            });

            camera.position.copy(originalPos);
            camera.up.copy(originalUp);
            controls.target.copy(originalTarget);
            camera.lookAt(originalTarget);
            camera.updateProjectionMatrix();
            controls.update();
            renderer.render(scene, camera);

            return snapshots;
        },

        teardownViewer() {
            if (this._rafId) cancelAnimationFrame(this._rafId);
            if (this._resizeObserver) this._resizeObserver.disconnect();
            this.clearGroup();
            if (this._ctx) this._ctx.dispose();
            this._ctx = null;
            this._THREE = null;
        },
    },
};
</script>

<style scoped>
.wall-viewer-3d {
    position: relative;
    width: 100%;
    height: 560px;
    border-radius: 8px;
    overflow: hidden;
    background: linear-gradient(180deg, #f4f7fa 0%, #e9eef3 100%);
}
@media (max-width: 768px) {
    .wall-viewer-3d {
        height: 400px;
    }
}
.wall-viewer-3d canvas {
    width: 100%;
    height: 100%;
    display: block;
    touch-action: none;
}
.viewer-loading,
.viewer-empty {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #888;
    text-align: center;
    padding: 0 20px;
}
</style>
