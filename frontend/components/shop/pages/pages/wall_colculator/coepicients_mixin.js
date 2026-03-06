export const coepicients_mixin = {
    wall() {
        return {
            // Base wall price per m²
            wall_squarenes_price: {
                'coepicient': 120, //$
            },

            // Wall types - multipliers for different wall types
            wall_types: {
                'indoor_bouldering': {
                    'coepicient': 0.85,
                    'name': 'Indoor Bouldering Wall',
                    'image': 'https://fastly.picsum.photos/id/237/200/300.jpg?hmac=TmmQSbShHz9CdQm0NkEjx1Dyh_Y984R9LpNrpvH2D_U',
                    // 'description': 'Indoor bouldering wall designed for low-height climbing (typically 3-4 meters) without rope protection. Perfect for beginners and training facilities. Features softer landing zones and easier route setting options.'
                    'description': '$siteData.data.indoor_bouldering_description'
                },
                'outdoor_bouldering': {
                    'coepicient': 1.1,
                    'name': 'Outdoor Bouldering Wall',
                    'image': 'https://fastly.picsum.photos/id/237/200/300.jpg?hmac=TmmQSbShHz9CdQm0NkEjx1Dyh_Y984R9LpNrpvH2D_U',
                    // 'description': 'Outdoor bouldering wall built to withstand weather conditions. Features enhanced durability, UV-resistant coatings, and robust construction. Includes crash pads and impact-absorbing surfaces for safe outdoor climbing.'
                    'description': '$siteData.data.outdoor_bouldering_description'
                },
                'indoor_sport_climbing': {
                    'coepicient': 1.0,
                    'name': 'Indoor Sport Climbing Wall',
                    'image': 'https://fastly.picsum.photos/id/237/200/300.jpg?hmac=TmmQSbShHz9CdQm0NkEjx1Dyh_Y984R9LpNrpvH2D_U',
                    // 'description': 'Indoor sport climbing wall with full rope protection systems. Features top-rope and lead climbing areas with anchor points, belay stations, and safety harnesses. Ideal for intermediate to advanced climbers.'
                    'description': '$siteData.data.indoor_sport_climbing_description'
                },
                'outdoor_sport_climbing': {
                    'coepicient': 1.35,
                    'name': 'Outdoor Sport Climbing Wall',
                    'image': 'https://fastly.picsum.photos/id/237/200/300.jpg?hmac=TmmQSbShHz9CdQm0NkEjx1Dyh_Y984R9LpNrpvH2D_U',
                    // 'description': 'Outdoor sport climbing wall with permanent anchor systems and rope protection. Built with weather-resistant materials, includes lightning protection, drainage systems, and professional-grade safety features for year-round climbing.'
                    'description': '$siteData.data.outdoor_sport_climbing_description'
                },
                'indoor_stand_free_wall': {
                    'coepicient': 0.9,
                    'name': 'Indoor Stand-Free Wall',
                    'image': 'https://fastly.picsum.photos/id/237/200/300.jpg?hmac=TmmQSbShHz9CdQm0NkEjx1Dyh_Y984R9LpNrpvH2D_U',
                    // 'description': 'Indoor stand-alone climbing wall that doesn\'t require attachment to existing structures. Features self-supporting frame, modular design for easy reconfiguration, and versatile climbing surfaces for all skill levels.'
                    'description': '$siteData.data.indoor_stand_free_wall_description'
                },
                'outdoor_stand_free_wall': {
                    'coepicient': 1.2,
                    'name': 'Outdoor Stand-Free Wall',
                    'image': 'https://fastly.picsum.photos/id/237/200/300.jpg?hmac=TmmQSbShHz9CdQm0NkEjx1Dyh_Y984R9LpNrpvH2D_U',
                    // 'description': 'Outdoor stand-alone climbing wall with independent foundation and weatherproof construction. Features heavy-duty steel frame, corrosion-resistant coating, and adjustable angle panels for varied climbing experiences.'
                    'description': '$siteData.data.outdoor_stand_free_wall_description'
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

            // Hold middle price per unit
            hold_midle_price: {
                'coepicient': 10, //$
            },

            // VAT percentage
            vat: {
                'coepicient': 20, //%
            },
        };
    },
};

