export default {
    methods: {
        admin_all_menu(){
            return [
                {
                    id: 1,
                    title: "Home",
                    route: "/",
                },
                {
                    title: "Favorites",
                    name: "favorites",
                    routes: [
                        {
                            name: "Favorite products",
                            route: "/favorite_products"
                        },
                        {
                            name: "Favorite outdoor area",
                            route: "/my_favorite_outdoor_area"
                        },
                        {
                            name: "Interested events",
                            route: "/interested_events"
                        },
                    ],
                },
                {
                    title: "Guide",
                    name: "guide",
                    routes: [
                        {
                            name: "Indoor climbing",
                            route: "/article/indoor",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Outdoor climbing",
                            route: "/article/outdoor",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Sectors & Routes",
                            route: "/routes_and_sectors",
                            permissions: [
                                ['show', 'route'],
                            ]
                        },
                        {
                            name: "Ice & Mix",
                            route: "/article/ice",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Sectors local images",
                            route: "/sector_local_images",
                            permissions: [
                                ['show', 'sector_local_image'],
                            ]
                        },
                        {
                            name: "Spot projects",
                            route: "/article/spot_projects",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Mountaineering",
                            route: "/article/mount_route",
                            permissions: [
                                ['show', 'article'],
                                ['show', 'mount_massive'],
                            ]
                        },
                        {
                            name: "Other activity",
                            route: "/article/other",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "News",
                            route: "/article/news",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Security (tech tip)",
                            route: "/article/tech_tip",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Partners",
                            route: "/article/partners",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Special article",
                            route: "/article/special",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: "Live cameras",
                            route: "/live_cameras",
                            permissions: [
                                ['show', 'live_camera'],
                            ]
                        },
                        {
                            name: "Events & Competitions",
                            route: "/event",
                            permissions: [
                                ['show', 'event'],
                            ]
                        },
                        {
                            name: "Local bisnes",
                            route: "/local_bisnes",
                            permissions: [
                                ['show', 'local_bisnes'],
                            ]
                        },
                        {
                            name: "Export tips",
                            route: "/export_tips",
                            permissions: [
                                ['show', 'export']
                            ]
                        },
                    ]
                },
                {
                    title: "Guide Dashboard",
                    name: "guide_tasks",
                    routes: [
                        {
                            name: "Guide Dashboard",
                            route: "/guide_dashboard_page",
                            permissions: [
                                ['show', 'task'],
                            ]
                        },
                        {
                            name: "Guide task panel",
                            route: "/guide_task_panel",
                            permissions: [
                                ['show', 'task'],
                            ]
                        },
                        {
                            name: "My tasks for guide",
                            route: "/team_member_tasks_page",
                            permissions: [
                                ['show', 'team'],
                            ]
                        },
                    ]
                },
                {
                    title: "Shop",
                    name: "shop",
                    routes: [
                        {
                            name: "My purchases",
                            route: "/purchases"
                        },
                        {
                            name: "All products & categories",
                            route: "/products_and_categories",
                            permissions: [
                                ['show', 'product'],
                                ['show', 'product_category'],
                                ['show', 'product_option'],
                                ['show', 'sale_code'],
                            ]
                        },
                        {
                            name: "Warehouses",
                            route: "/warehouses",
                            permissions: [
                                ['show', 'warehouse'],
                            ]
                        },
                        {
                            name: "All orders",
                            route: "/order/all_orders",
                            permissions: [
                                ['show', 'order'],
                            ]
                        },
                        {
                            name: "All tours",
                            route: "/all_tours_list",
                            permissions: [
                                ['show', 'tour'],
                                ['show', 'tour_category'],
                            ]
                        },
                        {
                            name: "All tours reservations",
                            route: "/all_tours_reservations_list",
                            permissions: [
                                ['show', 'tour_reservation'],
                            ]
                        },
                        {
                            name: "Services",
                            route: "/services_list",
                            permissions: [
                                ['show', 'services'],
                            ]
                        },
                    ],
                },
                {
                    title: "Shop Dashboard",
                    name: "shop_tasks",
                    routes: [
                        {
                            name: "Shop Dashboard",
                            route: "/shop_dashboard_page",
                            permissions: [
                                ['show', 'task'],
                            ]
                        },
                        {
                            name: "Shop task panel",
                            route: "/shop_task_panel",
                            permissions: [
                                ['show', 'task'],
                            ]
                        },
                        {
                            name: "My Tasks (Shop)",
                            route: "/shop_worker_tasks_page",
                            permissions: [
                                ['show', 'task'],
                            ]
                        },
                    ],
                },
                {
                    title: "Blog",
                    name: "blog",
                    routes: [
                        {
                            name: "Posts",
                            route: "/posts",
                            permissions: [
                                ['show', 'post'],
                            ]
                        },
                    ]
                },
                {
                    title: "Summit",
                    name: "summit",
                    routes: [
                        {
                            name: "Summits",
                            route: "/summits",
                            permissions: [
                                ['show', 'summit'],
                            ]
                        },
                    ]
                },
                {
                    title: "Site options",
                    name: "site_options",
                    routes: [
                        {
                            name: "About us",
                            route: "/site_info",
                            permissions: [
                                ['show', 'site_data'],
                            ]
                        },
                        {
                            name: "Head Sliders",
                            route: "/head_sliders_images",
                            permissions: [
                                ['show', 'head_slider'],
                            ]
                        },
                        {
                            name: "Site followers",
                            route: "/site_folloers",
                            permissions: [
                                ['show', 'site_folloers'],
                            ]
                        },
                        {
                            name: "Users & Permissions",
                            route: "/users_list",
                            permissions: [
                                ['show', 'user'],
                            ]
                        },
                        {
                            name: "Other commenters",
                            route: "/non_registered_commenter_list",
                            permissions: [
                                ['show', 'commenter'],
                            ]
                        },
                        {
                            name: "Multimedia",
                            route: "/multimedia",
                            permissions: [
                                ['show', 'export'],
                            ]
                        },
                        {
                            name: "Database",
                            route: "/database",
                            permissions: [
                                ['show', 'database'],
                            ]
                        },
                        {
                            name: "All comments",
                            route: "/comments_and_reviews",
                            permissions: [
                                ['show', 'comment'],
                            ]
                        },
                    ],
                },
            ]
        }
    }
}
