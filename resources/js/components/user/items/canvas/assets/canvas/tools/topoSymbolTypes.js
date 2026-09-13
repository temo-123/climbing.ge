// Registry of the "Topo symbols" toolbar group (Rappel/Bolt/Pin/Pendulum/Crux —
// see ToolbarComponent.vue's "Topo symbols" tool-group) used to build the
// auto-generated on-canvas Legend (see DrawingTools.vue's rebuildLegend).
// Deliberately excludes the generic Arrow tool, which lives in the separate
// "Draw" tool-group and isn't a fixed topo symbol with a single meaning.
// `key` is a unique id used both as the legend row's identity and as its
// PointText name suffix; `match(data)` tests a Paper.js item's `.data` object
// against the `item.data.isXxx` flags each add_* function sets (DrawingTools.vue);
// `labelKey` is the i18n key under admin.articles.canvas_editor.* for its
// legend row text. Pendulum-left/right both set `isPendulum` plus a
// `direction` of -1/1 (see _buildPendulum) — matched separately here so they
// get their own distinct legend rows instead of collapsing into one, since
// they're visually mirrored and placed via separate toolbar buttons.
//
// `category` groups rows under a small section heading when the combined
// legend is drawn (see legendRenderer.js's drawLegendCard) — a flat list
// mixing e.g. 9 near-identical anchor-quality variants with landmark/POI
// pins read as one undifferentiated wall of rows; grouping by family lets a
// reader jump straight to "Anchors" or "Points of interest" instead of
// scanning the whole list. See LEGEND_CATEGORIES below for the fixed
// section order + heading i18n keys.
export const TOPO_SYMBOL_TYPES = [
    { key: 'rappel',         labelKey: 'legend_label_rappel',        category: 'anchor', match: (d) => !!d.isRappel },
    { key: 'bolt',           labelKey: 'legend_label_bolt',          category: 'topo', match: (d) => !!d.isBolt },
    { key: 'pin',            labelKey: 'legend_label_pin',           category: 'topo', match: (d) => !!d.isPin },
    { key: 'pendulum_left',  labelKey: 'legend_label_pendulum_left', category: 'topo', match: (d) => !!d.isPendulum && d.direction === -1 },
    { key: 'pendulum_right', labelKey: 'legend_label_pendulum_right',category: 'topo', match: (d) => !!d.isPendulum && d.direction === 1 },
    { key: 'crux',           labelKey: 'legend_label_crux',          category: 'topo', match: (d) => !!d.isCrux },

    // Anchor family (hollow-ring quality markers — see DrawingTools.vue's
    // add_anchor/_buildAnchorParts). `anchorKind` is 'anchor' (plain ring, no
    // accent) | 'portaledge' (chevron above) | 'rappel' (shaft+chevron below)
    // | 'rescue' (concentric double-ring, no quality tier).
    { key: 'anchor_good',            labelKey: 'legend_label_anchor_good',            category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'anchor' && d.anchorQuality === 'good' },
    { key: 'anchor_mid',             labelKey: 'legend_label_anchor_mid',             category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'anchor' && d.anchorQuality === 'mid' },
    { key: 'anchor_bad',             labelKey: 'legend_label_anchor_bad',             category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'anchor' && d.anchorQuality === 'bad' },
    { key: 'portaledge_anchor_good', labelKey: 'legend_label_portaledge_anchor_good', category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'portaledge' && d.anchorQuality === 'good' },
    { key: 'portaledge_anchor_mid',  labelKey: 'legend_label_portaledge_anchor_mid',  category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'portaledge' && d.anchorQuality === 'mid' },
    { key: 'portaledge_anchor_bad',  labelKey: 'legend_label_portaledge_anchor_bad',  category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'portaledge' && d.anchorQuality === 'bad' },
    { key: 'rappel_anchor_good',     labelKey: 'legend_label_rappel_anchor_good',     category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'rappel' && d.anchorQuality === 'good' },
    { key: 'rappel_anchor_mid',      labelKey: 'legend_label_rappel_anchor_mid',      category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'rappel' && d.anchorQuality === 'mid' },
    { key: 'rappel_anchor_bad',      labelKey: 'legend_label_rappel_anchor_bad',      category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'rappel' && d.anchorQuality === 'bad' },
    { key: 'rescue_anchor',          labelKey: 'legend_label_rescue_anchor',          category: 'anchor', match: (d) => !!d.isAnchorSymbol && d.anchorKind === 'rescue' },

    // Landmark markers (see DrawingTools.vue's add_summit/add_tent/
    // add_parking) — general map-style glyphs unrelated to the anchor/
    // topo-symbol families above.
    { key: 'summit',  labelKey: 'legend_label_summit',  category: 'landmark', match: (d) => !!d.isSummitMarker },
    { key: 'tent',    labelKey: 'legend_label_tent',    category: 'landmark', match: (d) => !!d.isTentMarker },
    { key: 'parking', labelKey: 'legend_label_parking', category: 'landmark', match: (d) => !!d.isParkingMarker },

    // Points-of-interest pins (see DrawingTools.vue's add_poi/_buildPoiParts)
    // — red "map pin + white circle + black icon" markers for common amenity
    // types along a route/approach.
    { key: 'poi_hiking',  labelKey: 'legend_label_poi_hiking',  category: 'poi', match: (d) => !!d.isPoiMarker && d.poiKind === 'hiking' },
    { key: 'poi_bed',     labelKey: 'legend_label_poi_bed',     category: 'poi', match: (d) => !!d.isPoiMarker && d.poiKind === 'bed' },
    { key: 'poi_bike',    labelKey: 'legend_label_poi_bike',    category: 'poi', match: (d) => !!d.isPoiMarker && d.poiKind === 'bike' },
    { key: 'poi_water',   labelKey: 'legend_label_poi_water',   category: 'poi', match: (d) => !!d.isPoiMarker && d.poiKind === 'water' },
    { key: 'poi_food',    labelKey: 'legend_label_poi_food',    category: 'poi', match: (d) => !!d.isPoiMarker && d.poiKind === 'food' },
    { key: 'poi_tent',    labelKey: 'legend_label_poi_tent',    category: 'poi', match: (d) => !!d.isPoiMarker && d.poiKind === 'tent' },
    { key: 'poi_medical', labelKey: 'legend_label_poi_medical', category: 'poi', match: (d) => !!d.isPoiMarker && d.poiKind === 'medical' },
];

// Fixed section order + heading i18n key for the grouped combined legend
// (see legendRenderer.js's drawLegendCard and SectorLocalImageCanvasComponent
// .vue's drawLegends, which mirrors the same grouping for the public page).
export const LEGEND_CATEGORIES = [
    { key: 'topo',     labelKey: 'legend_category_topo' },
    { key: 'anchor',   labelKey: 'legend_category_anchor' },
    { key: 'landmark', labelKey: 'legend_category_landmark' },
    { key: 'poi',      labelKey: 'legend_category_poi' },
];

// Toolbar position-picker options for the legend, plus "hidden" (don't show).
export const LEGEND_POSITIONS = [
    'top-left', 'top-mid', 'top-right',
    'left-mid', 'right-mid',
    'bot-left', 'bot-mid', 'bot-right',
    'hidden'
];
