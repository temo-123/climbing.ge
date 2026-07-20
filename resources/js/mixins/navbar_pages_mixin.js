export default {
    methods: {
        admin_all_menu(){
            const menu = [
                {
                    id: 1,
                    title: this.$t('user.nav.home'),
                    route: "/",
                },
                {
                    title: this.$t('user.nav.favorites'),
                    name: "favorites",
                    routes: [
                        {
                            name: this.$t('user.nav.favorites_favorite_products'),
                            route: "/favorite_products"
                        },
                        {
                            name: this.$t('user.nav.favorites_favorite_outdoor_area'),
                            route: "/my_favorite_outdoor_area"
                        },
                        {
                            name: this.$t('user.nav.favorites_interested_events'),
                            route: "/interested_events"
                        },
                    ],
                },
                {
                    title: this.$t('user.nav.guide'),
                    name: "guide",
                    routes: [
                        {
                            name: this.$t('user.nav.guide_indoor_climbing'),
                            route: "/article/indoor",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_outdoor_climbing'),
                            route: "/article/outdoor",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_sectors_and_routes'),
                            route: "/routes_and_sectors",
                            permissions: [
                                ['show', 'route'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_ice_and_mix'),
                            route: "/article/ice",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_sectors_local_images'),
                            route: "/sector_local_images",
                            permissions: [
                                ['show', 'sector_local_image'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_spot_projects'),
                            route: "/article/spot_projects",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_mountaineering'),
                            route: "/article/mount_route",
                            permissions: [
                                ['show', 'article'],
                                ['show', 'mount_massive'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_other_activity'),
                            route: "/article/other",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_news'),
                            route: "/article/news",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_security_tech_tip'),
                            route: "/article/tech_tip",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_partners'),
                            route: "/article/partners",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_special_article'),
                            route: "/article/special",
                            permissions: [
                                ['show', 'article'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_live_cameras'),
                            route: "/live_cameras",
                            permissions: [
                                ['show', 'live_camera'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_events_and_competitions'),
                            route: "/event",
                            permissions: [
                                ['show', 'event'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_local_business'),
                            route: "/local_bisnes",
                            permissions: [
                                ['show', 'local_bisnes'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_export_tips'),
                            route: "/export_tips",
                            permissions: [
                                ['show', 'export']
                            ]
                        },
                    ]
                },
                {
                    title: this.$t('user.nav.guide_dashboard'),
                    name: "guide_tasks",
                    routes: [
                        {
                            name: this.$t('user.nav.guide_dashboard_page'),
                            route: "/guide_dashboard_page",
                            permissions: [
                                ['show', 'guide_task'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_task_panel'),
                            route: "/guide_task_panel",
                            permissions: [
                                ['show', 'guide_task'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.guide_my_tasks'),
                            route: "/team_member_tasks_page",
                            permissions: [
                                ['show', 'team'],
                            ]
                        },
                    ]
                },
                {
                    title: this.$t('user.nav.shop'),
                    name: "shop",
                    routes: [
                        // {
                        //     name: "My purchases",
                        //     route: "/purchases"
                        // },
                        {
                            name: this.$t('user.nav.shop_products_and_categories'),
                            route: "/products_and_categories",
                            permissions: [
                                ['show', 'product'],
                                ['show', 'product_category'],
                                ['show', 'product_option'],
                                ['show', 'sale_code'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.shop_warehouses'),
                            route: "/warehouses",
                            permissions: [
                                ['show', 'warehouse'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.shop_all_orders'),
                            route: "/order/all_orders",
                            permissions: [
                                ['show', 'order'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.shop_all_tours'),
                            route: "/all_tours_list",
                            permissions: [
                                ['show', 'tour'],
                                ['show', 'tour_category'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.shop_all_tours_reservations'),
                            route: "/all_tours_reservations_list",
                            permissions: [
                                ['show', 'tour_reservation'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.shop_services'),
                            route: "/services_list",
                            permissions: [
                                ['show', 'services'],
                            ]
                        },
                    ],
                },
                {
                    title: this.$t('user.nav.shop_dashboard'),
                    name: "shop_tasks",
                    routes: [
                        {
                            name: this.$t('user.nav.shop_dashboard_page'),
                            route: "/shop_dashboard_page",
                            permissions: [
                                ['show', 'shop_task'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.shop_task_panel'),
                            route: "/shop_task_panel",
                            permissions: [
                                ['show', 'shop_task'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.shop_my_tasks'),
                            route: "/shop_worker_tasks_page",
                            permissions: [
                                ['show', 'shop_task'],
                            ]
                        },
                    ],
                },
                {
                    title: this.$t('user.nav.blog'),
                    name: "blog",
                    routes: [
                        {
                            name: this.$t('user.nav.blog_posts'),
                            route: "/posts",
                            permissions: [
                                ['show', 'post'],
                            ]
                        },
                    ]
                },
                {
                    title: this.$t('common.summit'),
                    name: "summit",
                    routes: [
                        {
                            name: this.$t('user.nav.summit_summits'),
                            route: "/summits",
                            permissions: [
                                ['show', 'summit'],
                            ]
                        },
                    ]
                },
                {
                    title: this.$t('user.nav.site_options'),
                    name: "site_options",
                    routes: [
                        {
                            name: this.$t('user.nav.site_options_about_us'),
                            route: "/site_info",
                            permissions: [
                                ['show', 'site_data'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_head_sliders'),
                            route: "/head_sliders_images",
                            permissions: [
                                ['show', 'head_slider'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_users_and_permissions'),
                            route: "/users_list",
                            permissions: [
                                ['show', 'user'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_other_commenters'),
                            route: "/non_registered_commenter_list",
                            permissions: [
                                ['show', 'commenter'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_multimedia_and_database'),
                            route: "/multimedia",
                            permissions: [
                                ['show', 'database'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_notification_analytics'),
                            route: "/notification_analytics",
                            permissions: [
                                ['show', 'user_notification'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_mail'),
                            route: "/mail",
                            permissions: [
                                ['show', 'mail'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_all_comments'),
                            route: "/comments_and_reviews",
                            permissions: [
                                ['show', 'comment'],
                            ]
                        },
                        {
                            name: this.$t('user.nav.site_options_all_routes_reviews'),
                            route: "/all_routes_reviews",
                            permissions: [
                                ['show', 'comment'],
                            ]
                        },
                    ],
                },
            ]

            // All routes above are hardcoded absolute paths (e.g. "/favorite_products")
            // with no locale prefix. Without this, following any of these links while
            // browsing in Georgian silently drops back to English, since the app's
            // locale is derived from the URL prefix on every navigation.
            const prefix = this.$i18n && this.$i18n.locale === 'ka' ? '/ka' : ''
            const applyPrefix = (items) => {
                items.forEach(item => {
                    if (item.route) item.route = prefix + item.route
                    if (item.routes) applyPrefix(item.routes)
                })
            }
            applyPrefix(menu)

            return menu
        }
    }
}
