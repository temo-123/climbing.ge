// Single source of truth for every fixed construction/geometry parameter used
// across the wall price calculator AND its 3D viewer/geometry builder — sheet
// sizes, frame dimensions, mat/rope spacing ratios, and validation limits.
// Previously each of these lived as its own locally-declared constant in
// whichever file first needed it, so the same real-world value (e.g. a
// standard 1.5m plywood sheet, or the mat's own sizing ratio) had to be kept
// in sync by hand across files that don't otherwise import from each other —
// this is a plain module (not a Vue mixin) so both Vue components and the
// framework-agnostic Three.js builder modules can import it directly.
//
// Values below are grounded in real climbing-wall construction practice
// (framing guides, T-nut layout guides, and published European/North
// American climbing-wall safety-matting standards), not arbitrary picks —
// see the comment on each group for the specific source finding.
export const WALL_CONSTRUCTION = {
    MIN_WALL_SIZE: 1.5, // meters — minimum width/height for any wall

    // Plywood / bolt-hole grid. Real T-nut layouts space holes ~8" (0.2m)
    // apart; sheets are standard 3/4" (19mm) stock, sometimes doubled for
    // durability — SHEET_SIZE stays a simplified 1.5m square (real sheets are
    // a non-square 1.22m x 2.44m 4x8ft panel; keeping it square is a known,
    // deliberate simplification since the seam/UV logic assumes square tiles).
    SHEET_SIZE: 1.5, // meters — standard plywood sheet edge length (simplified square)
    HOLE_SPACING: 0.21, // meters — real T-nut bolt-hole grid spacing (~8")

    // Frame / panel construction. Real stud-wall climbing panels run studs
    // 16" (0.4m) on-center — the previous 0.75m spacing (derived from half a
    // plywood sheet) was nearly double the real spacing and read as
    // under-framed. Panel thickness models doubled 3/4" plywood (a common
    // durability practice for climbing surfaces); frame member size splits
    // the difference between a real stud's two faces (38mm x 89mm) since the
    // geometry here uses one square cross-section for simplicity.
    STUD_SPACING: 0.4, // meters — 16" on-center, real stud-wall framing spacing
    PANEL_THICKNESS: 0.04, // meters — doubled 3/4" plywood
    FRAME_MEMBER_SIZE: 0.05, // meters — simplified single-value stud/rail cross-section

    // Safety mat sizing — modeled on published matting standards rather than
    // scaling with wall height (a real crash pad stack doesn't get thicker
    // just because the wall is taller): baseline ~15cm (the low end of a
    // "regular" 4"-6" pad stack), with additional thickness for deeper
    // overhangs (matting standards call for thicker pads under
    // overhangs/caves), capped at a realistic maximum. Depth (how far the mat
    // reaches out from the wall) defaults to ~2m for a non-overhanging wall,
    // matching the European ACS standard's 2.0m front fall-zone for walls up
    // to 3m, growing with overhang depth beyond that. Side margin is new —
    // real standards also require the mat to extend to the side by roughly
    // half the wall's height, which nothing here modeled before.
    MAT_MIN_THICKNESS: 0.15, // meters — baseline pad-stack thickness
    MAT_THICKNESS_PER_OVERHANG: 0.03, // additional meters of thickness per meter of overhang depth
    MAT_MAX_THICKNESS: 0.4, // meters — cap even for extreme overhangs
    DEFAULT_MAT_DEPTH: 2.0, // meters — front reach for a non-overhanging wall
    MAT_DEPTH_MARGIN: 0.3, // extra fraction added to overhang depth for mat depth
    MAT_SIDE_MARGIN_RATIO: 0.5, // fraction of wall height added to each side of the mat
    MAT_SIDE_MARGIN_MAX: 2.0, // meters — cap on the side margin for very tall walls

    // Sport-climbing belay rope line spacing
    ROPE_EDGE_MARGIN: 0.75, // meters from each side before the first rope line
    ROPE_TARGET_SPACING: 1.5, // meters between rope lines

    // Size validation — keeps geometry within the range the 3D viewer's own
    // corner/roof math was verified against (see ClimbingWallPriceColculatePage.vue's
    // maxOverhangDepth computed for the full derivation)
    MAX_OVERHANG_ANGLE_DEG: 76, // degrees from vertical — depth validation cap (real extreme cave/roof-style walls do get this steep)
    TOWER_RADIUS_OVERHANG_RATIO: 2.0, // max (top reach)/(base radius) for multi-side towers
};

// A real crash-pad stack's thickness depends on how far/hard a fall can be
// (deeper overhang = harder, further-out fall), not on the wall's overall
// height — shared here so the pricing page and the 3D viewer's own mat never
// drift apart onto two different formulas.
export function computeMatThickness(overhangDepth) {
    const d = Math.max(parseFloat(overhangDepth) || 0, 0);
    return Math.min(
        WALL_CONSTRUCTION.MAT_MIN_THICKNESS + d * WALL_CONSTRUCTION.MAT_THICKNESS_PER_OVERHANG,
        WALL_CONSTRUCTION.MAT_MAX_THICKNESS
    );
}

// Real matting standards extend to the side by roughly half the wall's
// height, not just exactly the wall's own width — capped so very tall walls
// don't get an absurdly wide mat.
export function computeMatSideMargin(wallHeight) {
    const h = Math.max(parseFloat(wallHeight) || 0, 0);
    return Math.min(h * WALL_CONSTRUCTION.MAT_SIDE_MARGIN_RATIO, WALL_CONSTRUCTION.MAT_SIDE_MARGIN_MAX);
}
