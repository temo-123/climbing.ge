import { coef } from '../../../../../services/coefficients.js';

// Every price/ratio here is admin-editable (Site Options -> Coefficients,
// slugs `wall_*`); the second coef() argument is only a last-resort fallback.
export const coepicients_mixin = {
    wall() {
        return {
            // Base wall price per m²
            wall_squarenes_price: {
                'coepicient': coef('wall_square_price', 120), //$
            },

            // Discipline multiplier (step 1 of the wall-type wizard) — the
            // display name is looked up via $t('shop.wall.discipline_name_' +
            // key) in the template, not stored here, since a raw English
            // string can't follow the page's own locale.
            disciplines: {
                'bouldering': {
                    'coepicient': coef('wall_discipline_bouldering', 0.85),
                    'icon': 'fa fa-hand-rock-o',
                },
                'sport_climbing': {
                    'coepicient': coef('wall_discipline_sport_climbing', 1.0),
                    'icon': 'fa fa-building',
                },
            },

            // Construction-style multiplier (step 2 of the wall-type wizard)
            // — same deal, display name comes from $t('shop.wall.structure_name_' + key).
            structures: {
                'indoor': {
                    'coepicient': coef('wall_structure_indoor', 1.0),
                    'icon': 'fa fa-home',
                    'stand_free': false,
                },
                'outdoor': {
                    'coepicient': coef('wall_structure_outdoor', 1.15),
                    'icon': 'fa fa-tree',
                    'stand_free': false,
                },
                'standfree_indoor': {
                    'coepicient': coef('wall_structure_standfree_indoor', 1.1),
                    'icon': 'fa fa-cube',
                    'stand_free': true,
                },
                'standfree_outdoor': {
                    'coepicient': coef('wall_structure_standfree_outdoor', 1.3),
                    'icon': 'fa fa-cloud',
                    'stand_free': true,
                },
            },

            // Mat price per m²
            mat_squarenes_price: {
                'coepicient': coef('wall_mat_price', 80), //$
            },

            // Lead/sport-climbing protection points — one bolted anchor per
            // belay-rope line (see computeRopeAnchorXs), priced per anchor,
            // plus the rope itself priced per meter of climbing length.
            protection_anchor_price: {
                'coepicient': coef('wall_protection_anchor_price', 50), //$ per anchor
            },
            protection_rope_price: {
                'coepicient': coef('wall_protection_rope_price', 10), //$ per meter of rope
            },

            // Hold middle price per unit
            hold_midle_price: {
                'coepicient': coef('wall_hold_price', 10), //$
            },

            // Foundation strip footing — stand-free structures only (a wall
            // attached to an existing building uses that building's own
            // foundation, not priced here). Priced per linear meter of wall
            // width, one footing run under each side, not per square meter —
            // a strip footing is a long, narrow run, not an area.
            foundation_price: {
                'coepicient': coef('wall_foundation_price', 300), //$ per linear meter of wall width
            },
            // The footing's own depth into the ground, shown in the PDF's
            // construction description/drawing — a sizing ratio, not a price.
            foundation_depth_ratio: {
                'coepicient': coef('wall_foundation_depth_ratio', 0.12), // fraction of wall height
            },

            // Roof — any outdoor wall (attached or stand-free) needs weather
            // cover overhead; priced per m² of the wall's own footprint area
            // (width x height, same footprint the wall itself occupies).
            roof_price: {
                'coepicient': coef('wall_roof_price', 15), //$ per m²
            },

            // VAT percentage
            vat: {
                'coepicient': coef('wall_vat_percent', 18), //%
            },
        };
    },
};
