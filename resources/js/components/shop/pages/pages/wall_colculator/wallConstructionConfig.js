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

    // Safety mat sizing — thickness scales directly with the wall's OWN
    // height (a taller wall means a longer, harder fall even on a wall that
    // isn't very overhung, so the pad needs to be thicker regardless of how
    // much it leans), not overhang depth. 10% of height lands exactly on the
    // 0.5m max at bouldering's own max height (5m — see BOULDER_MAX_HEIGHT),
    // and on the ~15cm baseline (the low end of a "regular" 4"-6" pad stack)
    // at the wall's minimum size, so the whole realistic range gets used.
    // Depth (how far the mat reaches out from the wall) defaults to ~2m for
    // a non-overhanging wall, matching the European ACS standard's 2.0m
    // front fall-zone for walls up to 3m, growing with overhang depth beyond
    // that. Side margin is new — real standards also require the mat to
    // extend to the side by roughly half the wall's height, which nothing
    // here modeled before.
    MAT_HEIGHT_RATIO: 0.10, // mat thickness as a fraction of the wall's own height
    MAT_MIN_THICKNESS: 0.15, // meters — floor, for very short walls
    MAT_MAX_THICKNESS: 0.5, // meters — cap, reached at BOULDER_MAX_HEIGHT
    DEFAULT_MAT_DEPTH: 2.0, // meters — front reach for a non-overhanging wall
    MAT_DEPTH_MARGIN: 0.3, // extra fraction added to overhang depth for mat depth
    // A margin of half the wall's own height, capped at 2m, made even a modest
    // 2-2.5m wide wall's mat balloon to 2.5-3x its own width (e.g. a 5m-tall
    // wall: 2m margin per side on a 2.5m-wide wall = 6.5m total) — reading as
    // a long, disproportionate plank rather than a mat sized to the wall it
    // belongs to. Tightened so the side margin still grows with height (a
    // taller wall does warrant a bit more lateral coverage) but stays a
    // modest, proportionate addition instead of dominating the wall's own
    // width.
    MAT_SIDE_MARGIN_RATIO: 0.25, // fraction of wall height added to each side of the mat
    MAT_SIDE_MARGIN_MAX: 1.0, // meters — cap on the side margin for very tall walls

    // Sport-climbing belay rope line spacing
    ROPE_EDGE_MARGIN: 0.75, // meters from each side before the first rope line
    ROPE_TARGET_SPACING: 1.5, // meters between rope lines

    // A real top/lead-rope run goes UP the wall's own climbing length to the
    // anchor and back DOWN to the belayer — double the wall height, not a
    // single strand — plus extra length for the tie-in knot and handling
    // slack at the belayer's end.
    ROPE_LENGTH_MULTIPLIER: 2, // up to the anchor and back down
    ROPE_LENGTH_MARGIN: 0.10, // extra fraction for tie-in/handling slack

    // Stand-free wall bracing — a freestanding wall has no building wall behind
    // it to lean on, so it needs its own visible support reaching the ground.
    // Real single-side bracing switches style at roughly this point: a flat or
    // barely-overhanging wall only tips gently and is held with simple angled
    // push-pull props (like single-side formwork bracing); once the overhang
    // is deep enough that the top is pulling forward and down in earnest, that
    // same simple prop would need to be absurdly long/shallow to reach the
    // ground, so real builds switch to a proper braced truss instead.
    STAND_FREE_BRACE_OVERHANG_THRESHOLD: 0.5, // meters — depth above which bracing switches from simple props to a truss
    // A single wide stand-free wall needs more than just two corner braces —
    // real modular freestanding systems run a support leg roughly every 1.5m
    // across the width (the same real spacing already used for rope/bolt
    // lines above), not two braces however far apart the wall's edges are.
    STAND_FREE_BRACE_SPACING: 1.5, // meters between stand-free support legs

    // Discipline height/overhang caps — a bouldering wall reads as a lead/
    // sport-climbing wall past this height (see heightTooTallForBoulder in
    // ClimbingWallPriceColculatePage.vue, which auto-switches the discipline
    // label to match instead of hard-blocking the height itself), and each
    // discipline's overhang depth is capped to a fixed, real-world value —
    // same for every structure option — rather than an angle-derived one
    // that let a short wall reach an absurd, deeper-than-its-own-height
    // overhang (see maxOverhangDepth for the full derivation).
    BOULDER_MAX_HEIGHT: 5, // meters — above this, the wall reads as lead/sport-climbing instead
    BOULDER_MAX_OVERHANG_DEPTH: 3, // meters — deepest realistic bouldering cave/overhang
    LEAD_MAX_OVERHANG_DEPTH: 5, // meters — deepest realistic lead/sport-climbing overhang
    // Size validation — keeps geometry within the range the 3D viewer's own
    // corner/roof math was verified against (see ClimbingWallPriceColculatePage.vue's
    // maxOverhangDepth computed for the full derivation). This ratio only
    // governs the SECOND, tower-corner-math cap for multi-side towers, on
    // top of the discipline caps above.
    TOWER_RADIUS_OVERHANG_RATIO: 2.0, // max (top reach)/(base radius) for multi-side towers
};

// A taller wall means a longer, harder fall even on a wall that isn't very
// overhung, so the pad's own thickness scales with the wall's own height
// (10%, e.g. a 5m wall gets a 0.5m mat) — not overhang depth — floored at a
// sane minimum for short walls and capped at a realistic maximum. Shared
// here so the pricing page and the 3D viewer's own mat never drift apart
// onto two different formulas.
export function computeMatThickness(wallHeight) {
    const h = Math.max(parseFloat(wallHeight) || 0, 0);
    return Math.min(
        Math.max(h * WALL_CONSTRUCTION.MAT_HEIGHT_RATIO, WALL_CONSTRUCTION.MAT_MIN_THICKNESS),
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
