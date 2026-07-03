export const coepicients_mixin = {
    wall() {
        return {
            // Base wall price per m²
            wall_squarenes_price: {
                'coepicient': 120, //$
            },

            // Discipline multiplier (step 1 of the wall-type wizard) — the
            // display name is looked up via $t('shop.wall.discipline_name_' +
            // key) in the template, not stored here, since a raw English
            // string can't follow the page's own locale.
            disciplines: {
                'bouldering': {
                    'coepicient': 0.85,
                    'icon': 'fa fa-hand-rock-o',
                },
                'sport_climbing': {
                    'coepicient': 1.0,
                    'icon': 'fa fa-building',
                },
            },

            // Construction-style multiplier (step 2 of the wall-type wizard)
            // — same deal, display name comes from $t('shop.wall.structure_name_' + key).
            structures: {
                'indoor': {
                    'coepicient': 1.0,
                    'icon': 'fa fa-home',
                    'stand_free': false,
                },
                'outdoor': {
                    'coepicient': 1.15,
                    'icon': 'fa fa-tree',
                    'stand_free': false,
                },
                'standfree_indoor': {
                    'coepicient': 1.1,
                    'icon': 'fa fa-cube',
                    'stand_free': true,
                },
                'standfree_outdoor': {
                    'coepicient': 1.3,
                    'icon': 'fa fa-cloud',
                    'stand_free': true,
                },
            },

            // Material types - price per m² for different materials
            material_types: {
                'plywood': {
                    'coepicient': 45,
                    'name': 'Plywood Surface'
                },
                'concrete': {
                    'coepicient': 80,
                    'name': 'Concrete Surface'
                },
                'metal_frame': {
                    'coepicient': 120,
                    'name': 'Metal Frame Structure'
                },
                'wooden_frame': {
                    'coepicient': 65,
                    'name': 'Wooden Frame Structure'
                },
            },

            // Surface finish - additional cost per m²
            surface_finish: {
                'basic_paint': {
                    'coepicient': 10,
                    'name': 'Basic Paint Finish'
                },
                'textured': {
                    'coepicient': 25,
                    'name': 'Textured Coating'
                },
                'vinyl': {
                    'coepicient': 35,
                    'name': 'Vinyl Covering'
                },
                'raw': {
                    'coepicient': 0,
                    'name': 'No Finish (Raw Materials)'
                },
            },

            // Installation type - multiplier for installation costs
            installation_type: {
                'diy': {
                    'coepicient': 0,
                    'name': 'DIY (Self-Installation)',
                    'description': 'You install the wall yourself'
                },
                'professional': {
                    'coepicient': 0.3,
                    'name': 'Professional Installation',
                    'description': 'Our team installs the wall'
                },
                'professional_training': {
                    'coepicient': 0.5,
                    'name': 'Professional with Training',
                    'description': 'Installation + climbing training session'
                },
            },

            // Additional features - fixed costs
            additional_features: {
                'safety_padding': {
                    'coepicient': 150,
                    'name': 'Safety Padding Around Wall',
                    'unit': 'linear meter',
                },
                'anchor_points': {
                    'coepicient': 25,
                    'name': 'Anchor Points for Ropes',
                    'unit': 'per point',
                },
                'wall_lighting': {
                    'coepicient': 200,
                    'name': 'Wall Lighting System',
                    'unit': 'per installation',
                },
                'belay_stations': {
                    'coepicient': 300,
                    'name': 'Belay Stations',
                    'unit': 'per station',
                },
                'wall_padding': {
                    'coepicient': 80,
                    'name': 'Protection Wall Padding',
                    'unit': 'per m²',
                },
            },

            // Mat price per m²
            mat_squarenes_price: {
                'coepicient': 80, //$
            },

            // Lead/sport-climbing protection points — one bolted anchor per
            // belay-rope line (see computeRopeAnchorXs), priced per anchor,
            // plus the rope itself priced per meter of climbing length.
            protection_anchor_price: {
                'coepicient': 50, //$ per anchor
            },
            protection_rope_price: {
                'coepicient': 10, //$ per meter of rope
            },

            // Hold middle price per unit
            hold_midle_price: {
                'coepicient': 10, //$
            },

            // Foundation strip footing — stand-free structures only (a wall
            // attached to an existing building uses that building's own
            // foundation, not priced here). Priced per linear meter of wall
            // width, one footing run under each side, not per square meter —
            // a strip footing is a long, narrow run, not an area.
            foundation_price: {
                'coepicient': 300, //$ per linear meter of wall width
            },
            // The footing's own depth into the ground, shown in the PDF's
            // construction description/drawing — a sizing ratio, not a price.
            foundation_depth_ratio: {
                'coepicient': 0.12, // fraction of wall height
            },

            // Roof — any outdoor wall (attached or stand-free) needs weather
            // cover overhead; priced per m² of the wall's own footprint area
            // (width x height, same footprint the wall itself occupies).
            roof_price: {
                'coepicient': 15, //$ per m²
            },

            // VAT percentage
            vat: {
                'coepicient': 18, //%
            },
        };
    },
};

