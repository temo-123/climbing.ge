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
                title: "Task panel",
                route: "/task_panel",
                permissions: [
                    ['show', 'worker']
                ]
            },
            {
                title: "My Comments & Reviews",
                route: "/my_comments_and_reviews"
            },
            {
                title: "Cart",
                route: "/cart"
            },
            {
                title: "My purchases",
                route: "/purchases"
            },
            {
                title: "Favorites",
                name: "favorites",
                routes: [
                    {
                        name: "Favorite products",
                        route: "/favorite_products",
                        permissions: [
                        //     ['show', 'worker']
                        ]
                    },
                    {
                        name: "Favorite outdoor area",
                        route: "/my_favorite_outdoor_area",
                        permissions: [
                        //     ['show', 'worker']
                        ]
                    },
                    {
                        name: "Interested events",
                        route: "/interested_events",
                        permissions: [
                        //     ['show', 'worker']
                        ]
                    },
                ],
            },
            // {
            //     title: "My products",
            //     name: "my_products",
            //     routes: [
            //         {
            //             name: "My products",
            //             route: "/user_product_list",
            //             permissions: [
            //                 ['add', 'product'],
            //                 ['edit', 'product'],
            //                 ['del', 'product'],
            //             ]
            //         },
            //         {
            //             name: "My Orders",
            //             route: "/order/user_orders",
            //             permissions: [
            //                 ['edit_order_status', 'order'],
            //             ]
                        
            //         },
            //     ],
            // },
            // {
            //     title: "My tours",
            //     name: "my_tours",
            //     routes: [
            //         {
            //             name: "My tours",
            //             route: "/user_tours_list",
            //             permissions: [
            //                 ['add', 'tour'],
            //                 ['edit', 'tour'],
            //                 ['del', 'tour'],
            //                 ['add', 'tour_category'],
            //                 ['edit', 'tour_category'],
            //                 ['del', 'tour_category']
            //             ]
            //         },
            //         {
            //             name: "My tour reservations",
            //             route: "/user_tours_reservations_list",
            //             permissions: [
            //                 ['show', 'tour_reservation'],
            //                 ['del', 'tour_reservation'],
            //             ]
            //         }
            //     ],
            // },
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

                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    {
                        name: "Spot projects",
                        route: "/article/spot_projects",
                        permissions: [
                            ['add', 'article'],
                            ['edit', 'article'],
                            ['del', 'article'],
                        ]
                    },
                    // {
                    //     name: "Mountaineering",
                    //     route: "/mount_routes/mount_routes_list",
                    //     permissions: [
                    //         ['add', 'mount_route'],
                    //         ['edit', 'mount_route'],
                    //         ['del', 'mount_route'],
                    //         ['add', 'mount_massive'],
                    //         ['edit', 'mount_massive'],
                    //         ['del', 'mount_massive'],
                    //     ]
                    // },
                    {
                        name: "Mountaineering",
                        route: "/article/mount_route",
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
                    {
                        name: "Special article",
                        route: "/article/special",
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
                        name: "All products & categoryes",
                        route: "/products_and_categories",
                        permissions: [
                            ['show_all', 'product'],
                            // ['add', 'product'],
                            // ['edit', 'product'],
                            // ['del', 'product'],
                            ['show_all', 'product_category'],
                            // ['add', 'product_category'],
                            // ['edit', 'product_category'],
                            // ['del', 'product_category'],
                            ['add', 'product_option'],
                            ['edit', 'product_option'],
                            ['del', 'product_option'],
                            ['add', 'sale_code'],
                            ['edit', 'sale_code'],
                            ['del', 'sale_code']
                        ]
                    },
                    {
                        name: "Warehouses",
                        route: "/warehouses",
                        permissions: [
                            ['show_all', 'tour'],
                            ['show_all', 'tour_category']
                        ]
                        // permissions: [
                        //     ['add', 'warehouses'],
                        //     ['edit', 'warehouses'],
                        //     ['del', 'warehouses']
                        // ]
                    },
                    {
                        name: "All orders",
                        route: "/order/all_orders",
                        permissions: [
                            ['edit_order_status', 'order'],
                        ]
                    },
                    {
                        name: "All tours",
                        route: "/all_tours_list",
                        permissions: [
                            ['show_all', 'tour'],
                            ['show_all', 'tour_category']
                        ]
                    },
                    {
                        name: "All tours reservations",
                        route: "/all_tours_reservations_list",
                        permissions: [
                            ['show_all', 'tour_reservation'],
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
                    {
                        name: "Stor slider",
                        route: "/stor_slides_list",
                        permissions: [
                            ['add', 'store_slides'],
                            ['edit', 'store_slides'],
                            ['del', 'store_slides']
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
                        route: "/posts_lost",
                        permissions: [
                            ['add', 'post'],
                            ['edit', 'post'],
                            ['del', 'post']
                        ]
                    },
                ]
            },
            // {
            //     title: "Films",
            //     route: "/films",
            //     permissions: [
            //         ['add', 'film'],
            //         ['edit', 'film'],
            //         ['del', 'film'],
            //         ['add', 'film_teg'],
            //         ['edit', 'film_teg'],
            //         ['del', 'film_teg'],
            //         ['add', 'film_category'],
            //         ['edit', 'film_category'],
            //         ['del', 'film_category'],
            //     ]
            // },
            {
                title: "Local bisnes",
                route: "/local_bisnes",
                permissions: [
                    ['add', 'local_bisnes'],
                    ['edit', 'local_bisnes'],
                    ['del', 'local_bisnes'],
                ]
            },
            // {
            //     title: "Site Global Data",
            //     name: "global_data",
            //     routes: [
            //         {
            //             name: "About us",
            //             route: "/site_info",
            //             permissions: [
            //                 ['edit', 'site_data']
            //             ]
            //         },
            //         {
            //             name: "Site followers",
            //             route: "/site_folloers",
            //             permissions: [
            //                 ['show', 'site_folloers'],
            //                 ['del', 'site_folloers']
            //             ]
            //         },
            //         {
            //             name: "Head Sliders",
            //             route: "/head_sliders_images",
            //             permissions: [
            //                 ['add', 'gallery'],
            //                 ['edit', 'gallery'],
            //                 ['del', 'gallery'],
            //             ]
            //         },
            //     ],
            // },
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
                        name: "About us",
                        route: "/site_info",
                        permissions: [
                            ['edit', 'site_data']
                        ]
                    },
                    {
                        name: "Head Sliders",
                        route: "/head_sliders_images",
                        permissions: [
                            ['add', 'gallery'],
                            ['edit', 'gallery'],
                            ['del', 'gallery'],
                        ]
                    },
                    {
                        name: "Site followers",
                        route: "/site_folloers",
                        permissions: [
                            ['show', 'site_folloers'],
                            ['del', 'site_folloers']
                        ]
                    },
                    // {
                    //     name: "Climbing routes rewiew",
                    //     route: "/comments_and_reviews",
                    //     permissions: [
                    //         ['show', 'routes_rewiew'],
                    //         ['del_comment', 'routes_rewiew'],
                    //     ]
                    // },

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
                        name: "Other commenters",
                        route: "/non_registered_commenter_list",
                        permissions: [
                            ['add', 'user'],
                            ['del', 'user'],
                            ['edit_permissions', 'user'],
                            ['create_ban', 'user'],
                        ]
                    },
                    {
                        name: "Article all images (beta)",
                        route: "/article_images",
                        permissions: [
                            ['add', 'gallery'],
                            ['edit', 'gallery'],
                            ['del', 'gallery'],
                        ]
                    },
                    {
                        name: "All comments",
                        route: "/comments_and_reviews",
                        permissions: [
                            ['show', 'comments'],
                            ['del_comment', 'comments'],
                        ]
                    },
                ],
            },
            {
                title: "Options",
                route: "/options"
            },
        ]
    },
}