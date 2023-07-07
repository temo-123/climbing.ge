export const navbar = {
    admin_all_menu(){
        return [
            {
                title: "Home",
                route: "/",
            },
            {
                title: "Dashboard",
                route: "/admin_page",
                permissions: [
                    ['show', 'dashboard']
                ]
            },
            {
                title: "Cart",
                route: "/cart",
            },
            {
                title: "My Comments",
                route: "/myComments",
            },
            {
                title: "My Orders",
                route: "/order/my_orders",
            },
            {
                title: "Favorites",
                name: "favorites",
                routes: [
                    {
                        name: "Favorite products",
                        route: "/favorite_products",
                        permissions: []
                    },
                    {
                        name: "Favorite outdoor area",
                        route: "/my_favorite_outdoor_area",
                        permissions: []
                    },
                    {
                        name: "Interested events",
                        route: "/interested_events",
                        permissions: []
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
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "Outdoor climbing",
                        route: "/article/outdoor",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "Sectors & Routes",
                        route: "/routes_and_sectors",
                        permissions: [
                            ['add', 'route'],
                            ['edit', 'route'],
                            ['del', 'route'],

                            ['add', 'mtp'],
                            ['edit', 'mtp'],
                            ['del', 'mtp'],

                            ['add', 'mtp_pitch'],
                            ['edit', 'mtp_pitch'],
                            ['del', 'mtp_pitch'],

                            ['add', 'sector'],
                            ['edit', 'sector'],
                            ['del', 'sector'],
                        ]
                    },
                    {
                        name: "Sectors local images",
                        route: "/sector_local_images",
                        permissions: [
                            ['add', 'sector_local_images'],
                            ['edit', 'sector_local_ima`ges'],
                            ['del', 'sector_local_images'],
                        ]
                    },
                    {
                        name: "Ice & Mix",
                        route: "/article/ice",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "Ice routes",
                        route: "/ice_routes",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "Mountaineering",
                        route: "/mount_routes/mount_routes_list",
                        permissions: [
                            ['add', 'mount_route'],
                            ['edit', 'mount_route'],
                            ['del', 'mount_route'],
                            ['add', 'mount_massive'],
                            ['edit', 'mount_massive'],
                            ['del', 'mount_massive'],
                        ]
                    },
                    {
                        name: "Other activity",
                        route: "/article/other",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "News",
                        route: "/article/news",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "Security (tech tip)",
                        route: "/article/tech_tip",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "Partners",
                        route: "/article/partners",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                ]
            },
            {
                title: "Shop",
                name: "shop",
                routes: [
                    {
                        name: "Products & categoryes",
                        route: "/products_and_categories",
                        permissions: [
                            ['add', 'product'],
                            ['edit', 'product'],
                            ['del', 'product'],
                            ['add', 'product_category'],
                            ['edit', 'product_category'],
                            ['del', 'product_category'],
                            ['add', 'product_option'],
                            ['edit', 'product_option'],
                            ['del', 'sale_code'],
                            ['add', 'sale_code'],
                            ['edit', 'sale_code'],
                            ['del', 'sale_code']
                        ]
                    },
                    {
                        name: "Services",
                        route: "/services_list",
                        permissions: [
                            ['add', 'services'],
                            ['edit', 'services'],
                            ['del', 'services']
                        ]
                    },
                ],
            },
            {
                title: "Films",
                route: "/films",
                permissions: [
                    ['add', 'film'],
                    ['edit', 'film'],
                    ['del', 'film'],
                    ['add', 'film_teg'],
                    ['edit', 'film_teg'],
                    ['del', 'film_teg'],
                    ['add', 'film_category'],
                    ['edit', 'film_category'],
                    ['del', 'film_category'],
                ]
            },
            {
                title: "Local bisnes",
                route: "/local_bisnes",
                permissions: [
                    ['add', 'local_bisnes'],
                    ['edit', 'local_bisnes'],
                    ['del', 'local_bisnes'],
                ]
            },
            {
                title: "Global data",
                name: "global_data",
                routes: [
                    {
                        name: "About us",
                        route: "/site_info",
                        permissions: [
                            ['edit', 'site_data']
                        ]
                    },
                    {
                        name: "Gallery",
                        route: "gallery",
                        permissions: [
                            ['add', 'gallery'],
                            ['edit', 'gallery'],
                            ['del', 'gallery'],
                        ]
                    },
                ],
            },
            {
                title: "Events & Competitions",
                route: "/event",
                permissions: [
                    ['add', 'event'],
                    ['edit', 'event'],
                    ['del', 'event'],
                ]
            },
            {
                title: "Site options",
                name: "site_options",
                routes: [
                    {
                        name: "Users & Permissions",
                        route: "/users_list",
                        permissions: [
                            ['add', 'user'],
                            ['del', 'user'],
                            ['edit_permissions', 'user'],
                            ['create_ban', 'user'],
                        ]
                    },
                    {
                        name: "Media (Beta)",
                        route: "/multimedia",
                        permissions: [
                            ['add', 'user'],
                        ]
                    },
                    {
                        name: "All comments",
                        route: "/comments",
                        permissions: [
                            ['show', 'comments'],
                            ['del_comment', 'comments'],
                        ]
                    },
                    {
                        name: "All orders",
                        route: "/order/all_orders",
                        permissions: [
                            ['edit_order_status', 'order'],
                        ]
                    },
                ],
            },
            {
                title: "Options",
                route: "/options",
                permissions: [
                    ['add', 'local_bisnes'],
                    ['edit', 'local_bisnes'],
                    ['del', 'local_bisnes'],
                ]
            },
        ]
    },
}