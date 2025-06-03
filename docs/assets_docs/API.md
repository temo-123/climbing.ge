  POST            _ignition/execute-solution .............................................. ignition.executeSolution › Spatie\LaravelIgnition › ExecuteSolutionController
  GET|HEAD        _ignition/health-check .......................................................... ignition.healthCheck › Spatie\LaravelIgnition › HealthCheckController
  POST            _ignition/update-config ....................................................... ignition.updateConfig › Spatie\LaravelIgnition › UpdateConfigController
  POST            api/FollowingNotification .................................................................. Api\Meil\FollowingNotificationController@send_notification
  POST            api/add_to_favorite/{product_id} .............................................................................. Api\Shop\CartController@add_to_favorite
  POST            api/add_user_adreses .................................................................................. Api\User\UserAdresesController@add_user_adreses
  POST            api/article/add_article/{category} ............................................................................ Api\Guide\ArticleController@add_article
  DELETE          api/article/del_article/{article_id} .......................................................................... Api\Guide\ArticleController@del_article
  POST            api/article/edit_article/{article_id} ........................................................................ Api\Guide\ArticleController@edit_article
  GET|HEAD        api/article/get_article_for_bisnes_page/{lang}/{bisnes_url_title} ............................. Api\Guide\ArticleController@get_article_for_bisnes_page
  GET|HEAD        api/article/get_articles_for_bisnes_suport ................................................. Api\Guide\ArticleController@get_articles_for_bisnes_suport
  GET|HEAD        api/article/get_category_articles/{category} ........................................................ Api\Guide\ArticleController@get_category_articles
  GET|HEAD        api/article/{category}/{lang}/{url_title} ...................................................... Api\Guide\ArticleController@get_locale_article_on_page
  POST            api/articleSearch/{query_request} .................................................................................. Api\SearchController@articleSearch
  GET|HEAD        api/articles/get_editing_data/{id} ....................................................................... Api\Guide\ArticleController@get_editing_data
  POST            api/articles/upload_image .................................................................................... Api\Guide\ArticleController@image_upload
  POST            api/articles/upload_spot_rock_images .............................................................. Api\Guide\ArticleController@upload_spot_rock_images
  GET|HEAD        api/articles/{category}/{lang} ........................................................................ Api\Guide\ArticleController@get_locale_articles
  GET|HEAD        api/auth_user ...................................................................................... auth_user › Api\User\AuthenticationController@user
  POST            api/bisnes/add_local_bisnes .......................................................................... Api\Guide\LocalBisnesController@add_local_bisnes
  DELETE          api/bisnes/del_bisnes_article_relation/{article_id}/{bisnes_id} ........................... Api\Guide\LocalBisnesController@del_bisnes_article_relation
  DELETE          api/bisnes/del_local_bisnes/{bisnes_id} .............................................................. Api\Guide\LocalBisnesController@del_local_bisnes
  DELETE          api/bisnes/del_local_bisnes_image/{image_id} ................................................... Api\Guide\LocalBisnesController@del_local_bisnes_image
  POST            api/bisnes/edit_local_bisnes/{bisnes_id} ............................................................ Api\Guide\LocalBisnesController@edit_local_bisnes
  GET|HEAD        api/bisnes/get_bisnes_article_relation/{bisnes_id} ........................................ Api\Guide\LocalBisnesController@get_bisnes_article_relation
  GET|HEAD        api/bisnes/get_bisnes_images/{bisnes_id} ............................................................ Api\Guide\LocalBisnesController@get_bisnes_images
  GET|HEAD        api/bisnes/get_editing_local_bisnes_info/{bisnes_id} .................................... Api\Guide\LocalBisnesController@get_editing_local_bisnes_info
  GET|HEAD        api/bisnes/get_local_bisnes_for_article/{article_url_title}/{locale} ..................... Api\Guide\LocalBisnesController@get_local_bisnes_for_article
  GET|HEAD        api/bisnes/get_local_bisnes_in_page/{url_title}/{locale} ..................................... Api\Guide\LocalBisnesController@get_local_bisnes_in_page
  GET|HEAD        api/bisnes/get_local_bisneses ...................................................................... Api\Guide\LocalBisnesController@get_local_bisneses
  GET|HEAD        api/bisnes/get_local_bisneses_images/{bisnes_id} ............................................ Api\Guide\LocalBisnesController@get_local_bisneses_images
  POST            api/brand/create_brand ................................................................................... Api\Shop\ProductBrandController@create_brand
  DELETE          api/brand/del_brand/{id} .................................................................................... Api\Shop\ProductBrandController@del_brand
  POST            api/brand/edit_brand/{id} .................................................................................. Api\Shop\ProductBrandController@edit_brand
  GET|HEAD        api/brand/get_all_brands ............................................................................... Api\Shop\ProductBrandController@get_all_brands
  GET|HEAD        api/brand/get_brand/{id} .................................................................................... Api\Shop\ProductBrandController@get_brand
  GET|HEAD        api/cart ................................................................................................... cart.index › Api\Shop\CartController@index
  POST            api/cart ................................................................................................... cart.store › Api\Shop\CartController@store
  POST            api/cart/update_quantity/{item_id} ............................................................................ Api\Shop\CartController@update_quantity
  GET|HEAD        api/cart/{cart} .............................................................................................. cart.show › Api\Shop\CartController@show
  PUT|PATCH       api/cart/{cart} .......................................................................................... cart.update › Api\Shop\CartController@update
  DELETE          api/cart/{cart} ........................................................................................ cart.destroy › Api\Shop\CartController@destroy
  POST            api/ckeditor/upload ..................................................................................................... Api\CKEditorController@upload
  POST            api/competition/add_competition ....................................................................... Api\Guide\CompetitionController@add_competition
  DELETE          api/competition/del_competition/{competition_id} ...................................................... Api\Guide\CompetitionController@del_competition
  POST            api/competition/edit_competition/{competition_id} .................................................... Api\Guide\CompetitionController@edit_competition
  GET|HEAD        api/competition/get_all_competitions ............................................................. Api\Guide\CompetitionController@get_all_competitions
  GET|HEAD        api/competition/get_editing_competition/{competition_id} ...................................... Api\Guide\CompetitionController@get_editing_competition
  POST            api/del_from_favorite/{product_id} .......................................................................... Api\Shop\CartController@del_from_favorite
  DELETE          api/del_image_sector_from_db/{image_id}/{sector_id} .................................... Api\Guide\SectorLocalImagesController@del_image_sector_from_db
  DELETE          api/del_user_adreses/{adres_id} ....................................................................... Api\User\UserAdresesController@del_user_adreses
  POST            api/edit_adres/{adres_id} ................................................................................... Api\User\UserAdresesController@edit_adres
  GET|HEAD        api/email/resend ......................................................................... verification.resend › Api\User\VerificationController@resend
  GET|HEAD        api/email/verify/{user_id}/{hash} .................................................................................. Auth\VerificationController@verify
  POST            api/event/add_competition ................................................................................... Api\Guide\EventController@add_competition
  POST            api/event/add_event ............................................................................................... Api\Guide\EventController@add_event
  POST            api/event/add_to_interested_events ............................................................. Api\Guide\FaworitesController@add_to_interested_events
  DELETE          api/event/del_competition/{competition_id} .................................................................. Api\Guide\EventController@del_competition
  DELETE          api/event/del_event/{event_id} .................................................................................... Api\Guide\EventController@del_event
  DELETE          api/event/del_interested_event/{article_id} ........................................................ Api\Guide\FaworitesController@del_interested_event
  POST            api/event/edit_competition/{competition_id} ................................................................ Api\Guide\EventController@edit_competition
  POST            api/event/edit_event/{event_id} .................................................................................. Api\Guide\EventController@edit_event
  GET|HEAD        api/event/get_all_competitions ......................................................................... Api\Guide\EventController@get_all_competitions
  GET|HEAD        api/event/get_all_events ..................................................................................... Api\Guide\EventController@get_all_events
  GET|HEAD        api/event/get_editing_competition/{competition_id} .................................................. Api\Guide\EventController@get_editing_competition
  GET|HEAD        api/event/get_editing_event/{event_id} .................................................................... Api\Guide\EventController@get_editing_event
  GET|HEAD        api/event/get_event_on_index_page/{lang} ............................................................ Api\Guide\EventController@get_event_on_index_page
  GET|HEAD        api/event/get_event_on_site_list/{lang} .............................................................. Api\Guide\EventController@get_event_on_site_list
  GET|HEAD        api/event/get_event_on_site_page/{lang}/{url_title} .................................................. Api\Guide\EventController@get_event_on_site_page
  GET|HEAD        api/event/get_events_for_site ........................................................................... Api\Guide\EventController@get_events_for_site
  GET|HEAD        api/event/get_interested_events ................................................................... Api\Guide\FaworitesController@get_interested_events
  POST            api/film/add_to_faworite .................................................................................... Api\Films\FilmsController@add_to_faworite
  DELETE          api/film/del_from_faworite/{film_id} ...................................................................... Api\Films\FilmsController@del_from_faworite
  GET|HEAD        api/film/films_search/{locale} ................................................................................. Api\Films\FilmsController@films_search
  GET|HEAD        api/film/get_faworite_film_list ...................................................................... Api\Films\FilmsController@get_faworite_film_list
  GET|HEAD        api/film/get_film/{locale}/{url_title} ............................................................................. Api\Films\FilmsController@get_film
  GET|HEAD        api/film/get_films/{locale} ....................................................................................... Api\Films\FilmsController@get_films
  GET|HEAD        api/film/get_films_categories/{locale} ................................................................. Api\Films\FilmsController@get_films_categories
  GET|HEAD        api/film/get_same_films/{category_id}/{film_id}/{locale} ..................................................... Api\Films\FilmsController@get_same_films
  GET|HEAD        api/film/top_films/{top_film_type}/{locale} ................................................................... Api\Films\FilmsController@get_films_top
  POST            api/filmSearch/{query_request} ........................................................................................ Api\SearchController@filmSearch
  GET|HEAD        api/film_tags .................................................................................... film_tags.index › Api\Films\FilmTagsController@index
  POST            api/film_tags .................................................................................... film_tags.store › Api\Films\FilmTagsController@store
  GET|HEAD        api/film_tags/{film_tag} ........................................................................... film_tags.show › Api\Films\FilmTagsController@show
  PUT|PATCH       api/film_tags/{film_tag} ....................................................................... film_tags.update › Api\Films\FilmTagsController@update
  DELETE          api/film_tags/{film_tag} ..................................................................... film_tags.destroy › Api\Films\FilmTagsController@destroy
  GET|HEAD        api/films ............................................................................................... films.index › Api\Films\FilmsController@index
  POST            api/films ............................................................................................... films.store › Api\Films\FilmsController@store
  GET|HEAD        api/films/{film} .......................................................................................... films.show › Api\Films\FilmsController@show
  PUT|PATCH       api/films/{film} ...................................................................................... films.update › Api\Films\FilmsController@update
  DELETE          api/films/{film} .................................................................................... films.destroy › Api\Films\FilmsController@destroy
  DELETE          api/follow/del_follower/{id} ................................................................................... Api\User\ServiceFollowing@del_follower
  GET|HEAD        api/follow/following_users_list .................................................................... Api\User\ServiceFollowing@get_following_users_list
  POST            api/follow/{service_id} .............................................................................................. Api\User\ServiceFollowing@follow
  DELETE          api/gallery_image/del_image/{image_id} ................................................................... Api\Guide\ArticleGalleryController@del_image
  GET|HEAD        api/gallery_image/get_editing_images/{article_id} ............................................... Api\Guide\ArticleGalleryController@get_editing_images
  GET|HEAD        api/gallery_image/get_index_gallery .............................................................. Api\Guide\ArticleGalleryController@get_index_gallery
  GET|HEAD        api/general_info ........................................................................... general_info.index › Api\Guide\GeneralInfoController@index
  POST            api/general_info ........................................................................... general_info.store › Api\Guide\GeneralInfoController@store
  GET|HEAD        api/general_info/{general_info} .............................................................. general_info.show › Api\Guide\GeneralInfoController@show
  PUT|PATCH       api/general_info/{general_info} .......................................................... general_info.update › Api\Guide\GeneralInfoController@update
  DELETE          api/general_info/{general_info} ........................................................ general_info.destroy › Api\Guide\GeneralInfoController@destroy
  GET|HEAD        api/get_activ_adres/{adres_id} ......................................................................... Api\User\UserAdresesController@get_activ_adres
  POST            api/get_article_global_data/{leng}/{article_id} ................................................... Api\Guide\ArticleController@get_article_global_data
  GET|HEAD        api/get_articles_for_forum/{category}/{lang} ....................................................... Api\Guide\ArticleController@get_articles_for_forum
  POST            api/get_editing_adres/{adres_id} ..................................................................... Api\User\UserAdresesController@get_editing_adres
  GET|HEAD        api/get_editing_sectors/{image_id} .......................................................... Api\Guide\SectorLocalImagesController@get_editing_sectors
  GET|HEAD        api/get_quick_product/{lang}/{product_id} ................................................................ Api\Shop\ProductController@get_quick_product
  GET|HEAD        api/get_routes_for_forum/{sector_id} ................................................................... Api\Guide\RouteController@get_routes_for_forum
  GET|HEAD        api/get_routes_quantity/{article_id} .................................................................... Api\Guide\RouteController@get_routes_quantity
  GET|HEAD        api/get_sectors_for_forum/{article_id} ............................................................... Api\Guide\SectorController@get_sectors_for_forum
  GET|HEAD        api/get_spot_rocks_images/{article_id} ............................................................... Api\Guide\SectorController@get_spot_rocks_images
  GET|HEAD        api/get_user_adreses .................................................................................. Api\User\UserAdresesController@get_user_adreses
  GET|HEAD        api/get_user_favorite_products .................................................................. Api\Shop\ProductController@get_user_favorite_products
  POST            api/guide_comment/add_comment_complaint ............................................................. Api\Guide\CommentController@add_comment_complaint
  POST            api/guide_comment/confirm_email/{email} ..................................................................... Api\Guide\CommentController@confirm_email
  POST            api/guide_comment/create_comment/{article_id} .............................................................. Api\Guide\CommentController@create_comment
  DELETE          api/guide_comment/del_comment/{comment_id} .................................................................... Api\Guide\CommentController@del_comment
  GET|HEAD        api/guide_comment/get_actyve_comment/{comment_id} ...................................................... Api\Guide\CommentController@get_actyve_comment
  GET|HEAD        api/guide_comment/get_all_comments ....................................................................... Api\Guide\CommentController@get_all_comments
  GET|HEAD        api/guide_comment/get_article_comments/{article_id} .................................................. Api\Guide\CommentController@get_article_comments
  GET|HEAD        api/guide_comment/get_comments_complaints ......................................................... Api\Guide\CommentController@get_comments_complaints
  GET|HEAD        api/guide_comment/get_user_comments ..................................................................... Api\Guide\CommentController@get_user_comments
  POST            api/guide_comment/hide_comment/{comment_id} .................................................................. Api\Guide\CommentController@hide_comment
  POST            api/guide_comment/make_decision ............................................................................. Api\Guide\CommentController@make_decision
  POST            api/head_slider/add_slide .................................................................................... Api\Guide\HeadSliderController@add_slide
  DELETE          api/head_slider/del_slide/{slide_id} ......................................................................... Api\Guide\HeadSliderController@del_slide
  POST            api/head_slider/edit_slide/{slide_id} ....................................................................... Api\Guide\HeadSliderController@edit_slide
  GET|HEAD        api/head_slider/get_actyve_slide/{slide_id} ........................................................... Api\Guide\HeadSliderController@get_actyve_slide
  GET|HEAD        api/head_slider/get_all_slides .......................................................................... Api\Guide\HeadSliderController@get_all_slides
  GET|HEAD        api/head_slider/get_slides/{slide_category} ................................................................. Api\Guide\HeadSliderController@get_slides
  POST            api/ice_routes/add_route ....................................................................................... Api\Guide\IceRouteController@add_route
  DELETE          api/ice_routes/del_route/{route_id} ............................................................................ Api\Guide\IceRouteController@del_route
  POST            api/ice_routes/edit_route/{route_id} .......................................................................... Api\Guide\IceRouteController@edit_route
  GET|HEAD        api/ice_routes/get_all_routes ............................................................................. Api\Guide\IceRouteController@get_all_routes
  GET|HEAD        api/ice_routes/get_route_data_for_modal/{route_id} .............................................. Api\Guide\IceRouteController@get_route_data_for_modal
  GET|HEAD        api/ice_routes/get_route_editing_data/{sector_id} ................................................. Api\Guide\IceRouteController@get_route_editing_data
  GET|HEAD        api/ice_routes/get_sector_routes/{sector_id} ........................................................... Api\Guide\IceRouteController@get_sector_routes
  POST            api/ice_sector_images/add_sector_images ......................................................... Api\Guide\IceSectorImagesController@add_sector_images
  DELETE          api/ice_sector_images/del_sector_images/{image_id} .............................................. Api\Guide\IceSectorImagesController@del_sector_images
  GET|HEAD        api/ice_sector_images/get_sector_images/{sector_id} ............................................. Api\Guide\IceSectorImagesController@get_sector_images
  POST            api/ice_sectors/add_sector ................................................................................... Api\Guide\IceSectorController@add_sector
  DELETE          api/ice_sectors/del_sector/{sector_id} ....................................................................... Api\Guide\IceSectorController@del_sector
  POST            api/ice_sectors/edit_sector/{sector_id} ..................................................................... Api\Guide\IceSectorController@edit_sector
  GET|HEAD        api/ice_sectors/get_all_sectors ......................................................................... Api\Guide\IceSectorController@get_all_sectors
  GET|HEAD        api/ice_sectors/get_article_sectors/{article_id} .................................................... Api\Guide\IceSectorController@get_article_sectors
  GET|HEAD        api/ice_sectors/get_sector_data_for_model/{sector_id} ......................................... Api\Guide\IceSectorController@get_sector_data_for_model
  GET|HEAD        api/ice_sectors/get_sector_editing_data/{sector_id} ............................................. Api\Guide\IceSectorController@get_sector_editing_data
  POST            api/ice_sectors/routes_sequence ......................................................................... Api\Guide\IceSectorController@routes_sequence
  GET|HEAD        api/last_news/{lang} ........................................................................................ Api\Guide\ArticleController@get_last_news
  POST            api/login/social/create_password/{email} ........................................................................ Auth\SocialController@create_password
  GET|HEAD        api/login/{provider} ................................................................................................... Auth\SocialController@redirect
  GET|HEAD        api/login/{provider}/callback .......................................................................................... Auth\SocialController@Callback
  GET|HEAD        api/message .......................................................................................... message.index › Api\Meil\MessageController@index
  POST            api/message .......................................................................................... message.store › Api\Meil\MessageController@store
  GET|HEAD        api/message/{message} .................................................................................. message.show › Api\Meil\MessageController@show
  PUT|PATCH       api/message/{message} .............................................................................. message.update › Api\Meil\MessageController@update
  DELETE          api/message/{message} ............................................................................ message.destroy › Api\Meil\MessageController@destroy
  POST            api/mount/create_mount_massive ......................................................................... Api\Guide\MountController@create_mount_massive
  POST            api/mount/edit_mount_massive/{mount_id} .................................................................. Api\Guide\MountController@edit_mount_massive
  GET|HEAD        api/mount/get_editing_mount_data/{mount_id} .......................................................... Api\Guide\MountController@get_editing_mount_data
  GET|HEAD        api/mount/mount ......................................................................................... mount.index › Api\Guide\MountController@index
  POST            api/mount/mount ......................................................................................... mount.store › Api\Guide\MountController@store
  GET|HEAD        api/mount/mount/{mount} ................................................................................... mount.show › Api\Guide\MountController@show
  PUT|PATCH       api/mount/mount/{mount} ............................................................................... mount.update › Api\Guide\MountController@update
  DELETE          api/mount/mount/{mount} ............................................................................. mount.destroy › Api\Guide\MountController@destroy
  GET|HEAD        api/mount/on_page/{lang}/{mount_route_id} .................................................... Api\Guide\MountController@get_locale_mount_on_route_page
  GET|HEAD        api/mount/{lang}/{mount_id} ................................................................................ Api\Guide\MountController@get_locale_mount
  DELETE          api/mount_route/del_mount_route_image/{image_id} ................................................. Api\Guide\MountRouteController@del_mount_route_image
  GET|HEAD        api/mount_route/get_filtred_mount_route_for_admin/{filter_id} ........................ Api\Guide\MountRouteController@get_filtred_mount_route_for_admin
  GET|HEAD        api/mount_route/get_filtred_mount_routes/{lang}/{filter_id}/{published} ....................... Api\Guide\MountRouteController@get_filtred_mount_routes
  GET|HEAD        api/mount_route/get_mount_routes_by_maunt/{lang} ............................................. Api\Guide\MountRouteController@get_mount_routes_by_maunt
  GET|HEAD        api/mount_route/get_mount_routes_images/{article_id} ........................................... Api\Guide\MountRouteController@get_mount_routes_images
  GET|HEAD        api/mounts/{lang} ......................................................................................... Api\Guide\MountController@get_locale_mounts
  GET|HEAD        api/mtp ................................................................................................................. Api\Guide\MTPController@index
  DELETE          api/mtp/del_mtp/{mtp_id} .............................................................................................. Api\Guide\MTPController@del_mtp
  GET|HEAD        api/mtp/get_editing_mtp/{mtp_id} .............................................................................. Api\Guide\MTPController@get_editing_mtp
  GET|HEAD        api/mtp/get_mtp_for_modal/{mtp_id} .......................................................................... Api\Guide\MTPController@get_mtp_for_modal
  GET|HEAD        api/mtp/get_mtps_for_forum/{sector_id} ..................................................................... Api\Guide\MTPController@get_mtps_for_forum
  POST            api/mtp/mtp_add ....................................................................................................... Api\Guide\MTPController@mtp_add
  POST            api/mtp/mtp_edit/{mtp_id} ............................................................................................ Api\Guide\MTPController@mtp_edit
  GET|HEAD        api/mtp/mtp_pitch .................................................................................................. Api\Guide\MTPPitchController@index
  DELETE          api/mtp/mtp_pitch/del_pitch/{pitch_id} ......................................................................... Api\Guide\MTPPitchController@del_pitch
  GET|HEAD        api/mtp/mtp_pitch/get_editin_pitch/{pitch_id} ........................................................... Api\Guide\MTPPitchController@get_editin_pitch
  GET|HEAD        api/mtp/mtp_pitch/get_mtp_pitchs_for_model/{mtp_id} ............................................. Api\Guide\MTPPitchController@get_mtp_pitchs_for_model
  POST            api/mtp/mtp_pitch/mtp_pitch_add ............................................................................ Api\Guide\MTPPitchController@mtp_pitch_add
  POST            api/mtp/mtp_pitch/mtp_pitch_edit/{pitch_id} ............................................................... Api\Guide\MTPPitchController@mtp_pitch_edit
  POST            api/mtp/mtp_pitch/pitchs_sequence ........................................................................ Api\Guide\MTPPitchController@pitchs_sequence
  DELETE          api/non_registered_commenter/del_non_registered_commenter/{id} ................. Api\User\NonRegisteredCommenterController@del_non_registered_commenter
  GET|HEAD        api/non_registered_commenter/get_non_registered_commenter ...................... Api\User\NonRegisteredCommenterController@get_non_registered_commenter
  GET|HEAD        api/options/get_selected_user_data/{user_id} ..................................................... Api\User\UserOptionController@get_selected_user_data
  GET|HEAD        api/options/get_user_notification_data ....................................................... Api\User\UserOptionController@get_user_notification_data
  POST            api/options/update_user_notification_data ................................................. Api\User\UserOptionController@update_user_notification_data
  POST            api/options/user_info_update/{user_id} ................................................................. Api\User\UserOptionController@user_info_update
  POST            api/order/castam_prodaction_message/{product_id} ................................................... Api\Shop\OrderController@castam_prodaction_message
  POST            api/order/check_sale_code .................................................................................... Api\Shop\OrderController@check_sale_code
  POST            api/order/create_order .......................................................................................... Api\Shop\OrderController@create_order
  POST            api/order/edit_order_status/{order_id} ..................................................................... Api\Shop\OrderController@edit_order_status
  GET|HEAD        api/order/get_activ_order/{order_id} ......................................................................... Api\Shop\OrderController@get_activ_order
  GET|HEAD        api/order/get_all_orders ...................................................................................... Api\Shop\OrderController@get_all_orders
  GET|HEAD        api/order/get_order_detals/{order_id} ....................................................................... Api\Shop\OrderController@get_order_detals
  GET|HEAD        api/order/get_order_products/{order_id} ................................................................... Api\Shop\OrderController@get_order_products
  GET|HEAD        api/order/get_user_orders .................................................................................... Api\Shop\OrderController@get_user_orders
  GET|HEAD        api/order/get_user_purchases .............................................................................. Api\Shop\OrderController@get_user_purchases
  GET|HEAD        api/order/get_user_purchules .............................................................................. Api\Shop\OrderController@get_user_purchules
  GET|HEAD        api/order/is_order_confirm/{order_id} ....................................................................... Api\Shop\OrderController@is_order_confirm
  GET|HEAD        api/order/order/get_order_status/{order_id} ................................................................. Api\Shop\OrderController@get_order_status
  POST            api/order/order_is_confirm/{order_id} ....................................................................... Api\Shop\OrderController@order_is_confirm
  POST            api/outdoor/add_spot ............................................................................................. Api\Guide\OutdoorController@add_spot
  POST            api/outdoor/add_to_favorite_outdoor_area/{article_id} ...................................... Api\Guide\FaworitesController@add_to_favorite_outdoor_area
  DELETE          api/outdoor/del_faworite_outdoor_region/{article_id} ........................................ Api\Guide\FaworitesController@del_faworite_outdoor_region
  DELETE          api/outdoor/del_spot/{id} ........................................................................................ Api\Guide\OutdoorController@del_spot
  POST            api/outdoor/edit_spot/{id} ...................................................................................... Api\Guide\OutdoorController@edit_spot
  GET|HEAD        api/outdoor/get_editing_spot_data/{id} .............................................................. Api\Guide\OutdoorController@get_editing_spot_data
  GET|HEAD        api/outdoor/get_faworite_outdoor_region ..................................................... Api\Guide\FaworitesController@get_faworite_outdoor_region
  GET|HEAD        api/outdoor/get_filtred_outdoor_spots/{lang}/{filter_id}/{published} ............................ Api\Guide\OutdoorController@get_filtred_outdoor_spots
  GET|HEAD        api/outdoor/get_filtred_outdoor_spots_for_admin/{filter_id} ........................... Api\Guide\OutdoorController@get_filtred_outdoor_spots_for_admin
  GET|HEAD        api/outdoor/get_spots_by_regions/{lang} .............................................................. Api\Guide\OutdoorController@get_spots_by_regions
  GET|HEAD        api/outdoor/region ................................................................................................ Api\Guide\OutdoorController@get_all
  GET|HEAD        api/outdoor/region/{lang}/{region_id} ....................................................................... Api\Guide\OutdoorController@locale_region
  GET|HEAD        api/outdoor/regions/{lang} ................................................................................. Api\Guide\OutdoorController@locale_regions
  GET|HEAD        api/page_product/{lang}/{url_title} .............................................................. Api\Shop\ProductController@get_local_product_in_page
  GET|HEAD        api/parmisions_list ...................................................................................... Api\User\RolesController@get_parmisions_list
  POST            api/password/reset_password ............................................................................... Auth\ResetPasswordController@reset_password
  POST            api/password/send_forget_mail .......................................................................... Auth\ForgotPasswordController@send_forget_mail
  GET|HEAD        api/permission/get_parmisions_for_role/{role_id} ............................................... Api\User\PermissionsController@get_parmisions_for_role
  GET|HEAD        api/post .................................................................................................. post.index › Api\Forum\PostController@index
  POST            api/post .................................................................................................. post.store › Api\Forum\PostController@store
  GET|HEAD        api/post/{post} ............................................................................................. post.show › Api\Forum\PostController@show
  PUT|PATCH       api/post/{post} ......................................................................................... post.update › Api\Forum\PostController@update
  DELETE          api/post/{post} ....................................................................................... post.destroy › Api\Forum\PostController@destroy
  POST            api/post_comment/add_post_comment/{post_id} ......................................................... Api\Forum\PostCommentsController@add_post_comment
  POST            api/post_comment/edit_post_comment/{comment_id} .................................................... Api\Forum\PostCommentsController@edit_post_comment
  DELETE          api/post_comment/edit_post_comment/{comment_id} .................................................... Api\Forum\PostCommentsController@edit_post_comment
  GET|HEAD        api/post_comment/get_post_comment/{post_id} ......................................................... Api\Forum\PostCommentsController@get_post_comment
  POST            api/posts/add_post .................................................................................................. Api\Forum\PostController@add_post
  GET|HEAD        api/posts/get_likes/{post_id} ...................................................................................... Api\Forum\PostController@get_likes
  GET|HEAD        api/posts/get_mtp_posts/{mtp_id} ............................................................................... Api\Forum\PostController@get_mtp_posts
  POST            api/posts/get_posts_for_outdoor_region/{article_id} ............................................. Api\Forum\PostController@get_posts_for_outdoor_region
  GET|HEAD        api/posts/get_route_posts/{route_id} ......................................................................... Api\Forum\PostController@get_route_posts
  GET|HEAD        api/posts_topic .............................................................................. posts_topic.index › Api\Forum\PostsTopicController@index
  POST            api/posts_topic .............................................................................. posts_topic.store › Api\Forum\PostsTopicController@store
  GET|HEAD        api/posts_topic/list/{lang} ........................................................................... Api\Forum\PostsTopicController@get_local_topics
  GET|HEAD        api/posts_topic/{posts_topic} .................................................................. posts_topic.show › Api\Forum\PostsTopicController@show
  PUT|PATCH       api/posts_topic/{posts_topic} .............................................................. posts_topic.update › Api\Forum\PostsTopicController@update
  DELETE          api/posts_topic/{posts_topic} ............................................................ posts_topic.destroy › Api\Forum\PostsTopicController@destroy
  GET|HEAD        api/product .................................................................................................. index › Api\Shop\ProductController@index
  POST            api/product .................................................................................................. store › Api\Shop\ProductController@store
  GET|HEAD        api/product/add_product ........................................................................................ Api\Shop\ProductController@add_product
  POST            api/product/add_product ........................................................................................ Api\Shop\ProductController@add_product
  POST            api/product/change_user_relation ...................................................................... Api\Shop\ProductController@change_user_relation
  DELETE          api/product/del_product/{product_id} ........................................................................... Api\Shop\ProductController@del_product
  POST            api/product/edit_product/{product_id} ......................................................................... Api\Shop\ProductController@edit_product
  GET|HEAD        api/product/get_all_products .............................................................................. Api\Shop\ProductController@get_all_products
  GET|HEAD        api/product/get_product_editing_data/{product_id} ................................................. Api\Shop\ProductController@get_product_editing_data
  GET|HEAD        api/product/get_user_products ............................................................................ Api\Shop\ProductController@get_user_products
  GET|HEAD        api/product/{} ................................................................................................. show › Api\Shop\ProductController@show
  PUT|PATCH       api/product/{} ............................................................................................. update › Api\Shop\ProductController@update
  DELETE          api/product/{} ........................................................................................... destroy › Api\Shop\ProductController@destroy
  POST            api/productSearch/{query_request} .................................................................................. Api\SearchController@productSearch
  GET|HEAD        api/product_category ................................................................ product_category.index › Api\Shop\ProductCategoryController@index
  POST            api/product_category ................................................................ product_category.store › Api\Shop\ProductCategoryController@store
  GET|HEAD        api/product_category/{product_category} ............................................... product_category.show › Api\Shop\ProductCategoryController@show
  PUT|PATCH       api/product_category/{product_category} ........................................... product_category.update › Api\Shop\ProductCategoryController@update
  DELETE          api/product_category/{product_category} ......................................... product_category.destroy › Api\Shop\ProductCategoryController@destroy
  POST            api/product_feedback/add_feedback_complaint ................................................. Api\Shop\ProductFeedbackController@add_feedback_complaint
  POST            api/product_feedback/confirm_email/{email} ........................................................... Api\Shop\ProductFeedbackController@confirm_email
  POST            api/product_feedback/create_feedback/{product_id} .................................................. Api\Shop\ProductFeedbackController@create_feedback
  DELETE          api/product_feedback/del_feedback/{feedback_id} ....................................................... Api\Shop\ProductFeedbackController@del_feedback
  GET|HEAD        api/product_feedback/get_actyve_feedback/{feedback_id} ......................................... Api\Shop\ProductFeedbackController@get_actyve_feedback
  GET|HEAD        api/product_feedback/get_all_feedbacks ........................................................... Api\Shop\ProductFeedbackController@get_all_feedbacks
  GET|HEAD        api/product_feedback/get_feedbacks_complaints ............................................. Api\Shop\ProductFeedbackController@get_feedbacks_complaints
  GET|HEAD        api/product_feedback/get_product_feedbacks/{product_id} ...................................... Api\Shop\ProductFeedbackController@get_product_feedbacks
  GET|HEAD        api/product_feedback/get_user_feedbacks ......................................................... Api\Shop\ProductFeedbackController@get_user_feedbacks
  POST            api/product_feedback/hide_feedback/{feedback_id} ..................................................... Api\Shop\ProductFeedbackController@hide_feedback
  POST            api/product_feedback/make_decision ................................................................... Api\Shop\ProductFeedbackController@make_decision
  POST            api/product_option/add_option ............................................................................. Api\Shop\ProductOptionController@add_option
  DELETE          api/product_option/del_option/{option_id} ................................................................. Api\Shop\ProductOptionController@del_option
  DELETE          api/product_option/del_option_image/{image_id} ...................................................... Api\Shop\ProductOptionController@del_option_image
  POST            api/product_option/edit_option/{option_id} ............................................................... Api\Shop\ProductOptionController@edit_option
  GET|HEAD        api/product_option/get_activ_product_options/{product_id} .................................. Api\Shop\ProductOptionController@get_activ_product_options
  GET|HEAD        api/product_option/get_editing_option/{option_id} ................................................. Api\Shop\ProductOptionController@get_editing_option
  GET|HEAD        api/product_price_interval ...................................................................... Api\Shop\ProductController@get_product_price_interval
  GET|HEAD        api/products/{lang} ..................................................................................... Api\Shop\ProductController@get_local_products
  GET|HEAD        api/role ....................................................................................................... index › Api\User\RolesController@index
  POST            api/role ....................................................................................................... store › Api\User\RolesController@store
  POST            api/role/create_role ............................................................................................. Api\User\RolesController@create_role
  DELETE          api/role/del_role/{role_id} ......................................................................................... Api\User\RolesController@del_role
  DELETE          api/role/del_role_permission/{role_id}/{permission_id} ................................................... Api\User\RolesController@del_role_permission
  DELETE          api/role/del_user_pemisino/{permission_id}/{user_id} ....................................................... Api\User\RolesController@del_user_pemisino
  POST            api/role/edit_permissions_and_role/{user_id} ....................................................... Api\User\RolesController@edit_permissions_and_role
  POST            api/role/edit_role/{role_id} ....................................................................................... Api\User\RolesController@edit_role
  GET|HEAD        api/role/get_editing_role/{role_id} ......................................................................... Api\User\RolesController@get_editing_role
  GET|HEAD        api/role/get_editing_role_permissions/{role_id} ................................................. Api\User\RolesController@get_editing_role_permissions
  GET|HEAD        api/role/get_user_permissions/{user_id} ................................................................. Api\User\RolesController@get_user_permissions
  GET|HEAD        api/role/{} ...................................................................................................... show › Api\User\RolesController@show
  PUT|PATCH       api/role/{} .................................................................................................. update › Api\User\RolesController@update
  DELETE          api/role/{} ................................................................................................ destroy › Api\User\RolesController@destroy
  POST            api/route/add_route ............................................................................................... Api\Guide\RouteController@add_route
  DELETE          api/route/del_route/{route_id} .................................................................................... Api\Guide\RouteController@del_route
  POST            api/route/edit_route/{route_id} .................................................................................. Api\Guide\RouteController@edit_route
  GET|HEAD        api/route/get_all_routes ..................................................................................... Api\Guide\RouteController@get_all_routes
  GET|HEAD        api/route/get_route_editing_data/{route_id} .......................................................... Api\Guide\RouteController@get_route_editing_data
  GET|HEAD        api/route/get_route_for_modal/{route_id} ................................................................ Api\Guide\RouteController@get_route_for_modal
  GET|HEAD        api/route/routes_authers ..................................................................................... Api\Guide\RouteController@routes_authers
  POST            api/route_review/create_route_review/{route_id} ................................................. Api\Guide\RoutesReitingController@create_route_review
  DELETE          api/route_review/del_route_review/{review_id} ...................................................... Api\Guide\RoutesReitingController@del_route_review
  POST            api/route_review/edit_route_review/{review_id} .................................................... Api\Guide\RoutesReitingController@edit_route_review
  GET|HEAD        api/route_review/get_actyve_review/{review_id} .................................................... Api\Guide\RoutesReitingController@get_actyve_review
  GET|HEAD        api/route_review/get_all_review ...................................................................... Api\Guide\RoutesReitingController@get_all_review
  GET|HEAD        api/route_review/get_all_route_reviews/{route_id} ............................................. Api\Guide\RoutesReitingController@get_all_route_reviews
  GET|HEAD        api/route_review/get_user_review .................................................................... Api\Guide\RoutesReitingController@get_user_review
  GET|HEAD        api/sale_code ..................................................................................... sale_code.index › Api\Shop\SaleCodeController@index
  POST            api/sale_code ..................................................................................... sale_code.store › Api\Shop\SaleCodeController@store
  GET|HEAD        api/sale_code/{sale_code} ........................................................................... sale_code.show › Api\Shop\SaleCodeController@show
  PUT|PATCH       api/sale_code/{sale_code} ....................................................................... sale_code.update › Api\Shop\SaleCodeController@update
  DELETE          api/sale_code/{sale_code} ..................................................................... sale_code.destroy › Api\Shop\SaleCodeController@destroy
  GET|HEAD        api/sale_products/{lang} .......................................................................... Api\Shop\ProductController@get_local_saled_products
  GET|HEAD        api/sector ................................................................................................... index › Api\Guide\SectorController@index
  POST            api/sector ................................................................................................... store › Api\Guide\SectorController@store
  POST            api/sector/add_sector ........................................................................................... Api\Guide\SectorController@add_sector
  DELETE          api/sector/del_sector_image_from_db/{image_id} .................................................... Api\Guide\SectorController@del_sector_image_from_db
  DELETE          api/sector/del_sector_sector/{sector_id} ................................................................. Api\Guide\SectorController@del_sector_sector
  POST            api/sector/edit_sector/{sector_id} ............................................................................. Api\Guide\SectorController@edit_sector
  GET|HEAD        api/sector/get_sector_and_routes/{article_id} ........................................................ Api\Guide\SectorController@get_sector_and_routes
  GET|HEAD        api/sector/get_sector_data_for_model/{sector_id} ................................................. Api\Guide\SectorController@get_sector_data_for_model
  GET|HEAD        api/sector/get_sector_editing_data/{sector_id} ..................................................... Api\Guide\SectorController@get_sector_editing_data
  GET|HEAD        api/sector/get_sector_images/{sector_id} ................................................................. Api\Guide\SectorController@get_sector_images
  GET|HEAD        api/sector/get_spot_sectors_data_for_model/{article_id} .................................... Api\Guide\SectorController@get_spot_sectors_data_for_model
  POST            api/sector/routes_sequence ................................................................................. Api\Guide\SectorController@routes_sequence
  POST            api/sector/save_sector_sequence ....................................................................... Api\Guide\SectorController@save_sector_sequence
  GET|HEAD        api/sector/{} .................................................................................................. show › Api\Guide\SectorController@show
  PUT|PATCH       api/sector/{} .............................................................................................. update › Api\Guide\SectorController@update
  DELETE          api/sector/{} ............................................................................................ destroy › Api\Guide\SectorController@destroy
  GET|HEAD        api/sector_local_images ....................................................... sector_local_images.index › Api\Guide\SectorLocalImagesController@index
  POST            api/sector_local_images ....................................................... sector_local_images.store › Api\Guide\SectorLocalImagesController@store
  POST            api/sector_local_images/update_image/{image_id} .................................................... Api\Guide\SectorLocalImagesController@update_image
  GET|HEAD        api/sector_local_images/{sector_local_image} .................................... sector_local_images.show › Api\Guide\SectorLocalImagesController@show
  PUT|PATCH       api/sector_local_images/{sector_local_image} ................................ sector_local_images.update › Api\Guide\SectorLocalImagesController@update
  DELETE          api/sector_local_images/{sector_local_image} .............................. sector_local_images.destroy › Api\Guide\SectorLocalImagesController@destroy
  GET|HEAD        api/sector_local_img/get_sector_local_img_for_modal/{image_id} ................... Api\Guide\SectorLocalImagesController@get_sector_local_img_for_modal
  GET|HEAD        api/sectors_and_routes_quantity ............................................................ Api\Guide\SectorController@get_sectors_and_routes_quantity
  GET|HEAD        api/service ................................................................................................. index › Api\Shop\ServicesController@index
  POST            api/service ................................................................................................. store › Api\Shop\ServicesController@store
  POST            api/service/add_service ....................................................................................... Api\Shop\ServicesController@add_service
  DELETE          api/service/del_service/{service_id} .......................................................................... Api\Shop\ServicesController@del_service
  DELETE          api/service/del_service_image/{image_id} ................................................................ Api\Shop\ServicesController@del_service_image
  POST            api/service/edit_service/{service_id} ........................................................................ Api\Shop\ServicesController@edit_service
  GET|HEAD        api/service/get_editing_service/{service_id} .......................................................... Api\Shop\ServicesController@get_editing_service
  GET|HEAD        api/service/get_service/{service_id} .......................................................................... Api\Shop\ServicesController@get_service
  GET|HEAD        api/service/get_service_images/{service_id} ............................................................ Api\Shop\ServicesController@get_service_images
  GET|HEAD        api/service/{lang}/{url_title} .................................................................. Api\Shop\ServicesController@get_local_service_in_page
  GET|HEAD        api/service/{} ................................................................................................ show › Api\Shop\ServicesController@show
  PUT|PATCH       api/service/{} ............................................................................................ update › Api\Shop\ServicesController@update
  DELETE          api/service/{} .......................................................................................... destroy › Api\Shop\ServicesController@destroy
  GET|HEAD        api/services/{lang} .................................................................................... Api\Shop\ServicesController@get_local_services
  POST            api/shiped_region/add_region ............................................................................... Api\Shop\ShipedRegionController@add_region
  DELETE          api/shiped_region/del_region/{region_id} ................................................................... Api\Shop\ShipedRegionController@del_region
  POST            api/shiped_region/edit_region/{region_id} ................................................................. Api\Shop\ShipedRegionController@edit_region
  GET|HEAD        api/shiped_region/get_activ_region/{region_id} ....................................................... Api\Shop\ShipedRegionController@get_activ_region
  GET|HEAD        api/shiped_region/get_all_shiped_regions ....................................................... Api\Shop\ShipedRegionController@get_all_shiped_regions
  POST            api/similar_article/{lang} ..................................................................... Api\Guide\ArticleController@get_similar_locale_article
  GET|HEAD        api/similar_product/{lang}/{product_id} ................................................................ Api\Shop\ProductController@get_similar_product
  GET|HEAD        api/similar_services/{lang}/{id} ...................................................................... Api\Shop\ServicesController@get_similar_service
  GET|HEAD        api/siteData ............................................................................................... index › Api\Guide\SiteDataController@index
  POST            api/siteData ............................................................................................... store › Api\Guide\SiteDataController@store
  POST            api/siteData/edit_site_data ............................................................................... Api\Guide\SiteDataController@edit_site_data
  POST            api/siteData/edit_site_global_data ................................................................. Api\Guide\SiteDataController@edit_site_global_data
  POST            api/siteData/edit_site_ka_data ......................................................................... Api\Guide\SiteDataController@edit_site_ka_data
  POST            api/siteData/edit_site_ru_data ......................................................................... Api\Guide\SiteDataController@edit_site_ru_data
  POST            api/siteData/edit_site_us_data ......................................................................... Api\Guide\SiteDataController@edit_site_us_data
  GET|HEAD        api/siteData/fix_article_bugs ........................................................................... Api\Guide\SiteDataController@fix_article_bugs
  GET|HEAD        api/siteData/get_site_global_data ................................................................... Api\Guide\SiteDataController@get_site_global_data
  GET|HEAD        api/siteData/get_site_ka_data ........................................................................... Api\Guide\SiteDataController@get_site_ka_data
  GET|HEAD        api/siteData/get_site_locale_data/{locale} .......................................................... Api\Guide\SiteDataController@get_site_locale_data
  GET|HEAD        api/siteData/get_site_ru_data ........................................................................... Api\Guide\SiteDataController@get_site_ru_data
  GET|HEAD        api/siteData/get_site_us_data ........................................................................... Api\Guide\SiteDataController@get_site_us_data
  GET|HEAD        api/siteData/{} .............................................................................................. show › Api\Guide\SiteDataController@show
  PUT|PATCH       api/siteData/{} .......................................................................................... update › Api\Guide\SiteDataController@update
  DELETE          api/siteData/{} ........................................................................................ destroy › Api\Guide\SiteDataController@destroy
  GET|HEAD        api/site_data_counts .................................................................................... Api\Guide\SiteDataController@site_data_counts
  POST            api/site_social_links/add_site_social_links ....................................................... Api\User\SocialLinkController@add_site_social_links
  DELETE          api/site_social_links/del_site_social_links/{link_id} ............................................. Api\User\SocialLinkController@del_site_social_links
  GET|HEAD        api/site_social_links/get_site_social_links ....................................................... Api\User\SocialLinkController@get_site_social_links
  DELETE          api/spot_rock_images/del_spot_rock_image/{image_id} .................................................. Api\Guide\SpotRockController@del_spot_rock_image
  GET|HEAD        api/spot_rock_images/get_spot_rock_images/{article_id} .............................................. Api\Guide\SpotRockController@get_spot_rock_images
  POST            api/subcategory/create_subcategory/{category_id} ............................................. Api\Shop\ProductSubcategoryController@create_subcategory
  DELETE          api/subcategory/del_subcategory/{id} ............................................................ Api\Shop\ProductSubcategoryController@del_subcategory
  POST            api/subcategory/edit_subcategory/{id} .......................................................... Api\Shop\ProductSubcategoryController@edit_subcategory
  GET|HEAD        api/subcategory/get_all_subcategories ..................................................... Api\Shop\ProductSubcategoryController@get_all_subcategories
  GET|HEAD        api/subcategory/get_subcategories_for_category/{category_id} ..................... Api\Shop\ProductSubcategoryController@get_subcategories_for_category
  GET|HEAD        api/subcategory/get_subcategory/{id} ............................................................ Api\Shop\ProductSubcategoryController@get_subcategory
  POST            api/task/create_task .............................................................................................. Api\User\TaskController@create_task
  DELETE          api/task/del_task/{task_id} .......................................................................................... Api\User\TaskController@del_task
  GET|HEAD        api/task/get_all_tasks .......................................................................................... Api\User\TaskController@get_all_tasks
  GET|HEAD        api/task/get_task_data/{task_id} ................................................................................ Api\User\TaskController@get_task_data
  GET|HEAD        api/task/get_user_tasks ........................................................................................ Api\User\TaskController@get_user_tasks
  POST            api/task/task_category/create_task_category ...................................................... Api\User\TaskCategoryController@create_task_category
  DELETE          api/task/task_category/del_task_category/{task_category_id} ......................................... Api\User\TaskCategoryController@del_task_category
  GET|HEAD        api/task/task_category/get_all_task_categories ................................................ Api\User\TaskCategoryController@get_all_task_categories
  GET|HEAD        api/task/task_category/get_task_category_data/{task_category_id} ............................... Api\User\TaskCategoryController@get_task_category_data
  POST            api/task/task_category/update_task_category/{task_category_id} ................................... Api\User\TaskCategoryController@update_task_category
  POST            api/task/update_task/{task_id} .................................................................................... Api\User\TaskController@update_task
  POST            api/task/update_task_status/{task_id} ...................................................................... Api\User\TaskController@update_task_status
  GET|HEAD        api/token ............................................................................................................................................. 
  POST            api/tour/add_tour .................................................................................................... Api\Shop\TourController@add_tour
  POST            api/tour/category/add_category ........................................................................... Api\Shop\TourCategoryController@add_category
  DELETE          api/tour/category/del_category/{category_id} ............................................................. Api\Shop\TourCategoryController@del_category
  POST            api/tour/category/edit_category/{category_id} ........................................................... Api\Shop\TourCategoryController@edit_category
  GET|HEAD        api/tour/category/get_all_categories ............................................................... Api\Shop\TourCategoryController@get_all_categories
  GET|HEAD        api/tour/category/get_editing_category/{category_id} ............................................. Api\Shop\TourCategoryController@get_editing_category
  POST            api/tour/change_user_relation ............................................................................ Api\Shop\TourController@change_user_relation
  DELETE          api/tour/del_tour/{tour_id} .......................................................................................... Api\Shop\TourController@del_tour
  DELETE          api/tour/del_tour_image/{image_id} ............................................................................. Api\Shop\TourController@del_tour_image
  POST            api/tour/edit_tour/{tour_id} ........................................................................................ Api\Shop\TourController@edit_tour
  GET|HEAD        api/tour/get_all_tours .......................................................................................... Api\Shop\TourController@get_all_tours
  GET|HEAD        api/tour/get_editing_tour/{tour_id} .......................................................................... Api\Shop\TourController@get_editing_tour
  GET|HEAD        api/tour/get_similar_tours/{lang}/{tour_id} ................................................................. Api\Shop\TourController@get_similar_tours
  GET|HEAD        api/tour/get_tour/{lang}/{url_title} ................................................................................. Api\Shop\TourController@get_tour
  GET|HEAD        api/tour/get_tour_images/{tour_id} ............................................................................ Api\Shop\TourController@get_tour_images
  GET|HEAD        api/tour/get_tours/{lang} ........................................................................................... Api\Shop\TourController@get_tours
  GET|HEAD        api/tour/get_user_tours ........................................................................................ Api\Shop\TourController@get_user_tours
  POST            api/tour/reservation/create_reservation/{tour_id} ............................................... Api\Shop\TourReservationController@create_reservation
  DELETE          api/tour/reservation/del_reservation/{reservation_id} .............................................. Api\Shop\TourReservationController@del_reservation
  GET|HEAD        api/tour/reservation/get_reservations ............................................................. Api\Shop\TourReservationController@get_reservations
  GET|HEAD        api/tour/reservation/get_user_reservations ................................................... Api\Shop\TourReservationController@get_user_reservations
  POST            api/tour/reservation/verifiation_reservation/{reservation_id} .............................. Api\Shop\TourReservationController@verifiation_reservation
  POST            api/user/create_user_by_admin ........................................................................... Api\User\UsersController@create_user_by_admin
  DELETE          api/user/del_user/{user_id} ......................................................................................... Api\User\UsersController@del_user
  GET|HEAD        api/user/get_all_users ......................................................................................... Api\User\UsersController@get_all_users
  GET|HEAD        api/user/get_auth_user_data ............................................................................... Api\User\UsersController@get_auth_user_data
  GET|HEAD        api/user/get_auth_user_permissions ................................................................. Api\User\UsersController@get_auth_user_permissions
  GET|HEAD        api/user/get_user_data/{user_id} ............................................................................... Api\User\UsersController@get_user_data
  GET|HEAD        api/user/get_worker_users ................................................................................... Api\User\UsersController@get_worker_users
  POST            api/user/notifications/send_user_favorites_notification/{action} ................ Api\User\UserNotificationsController@send_user_favorites_notification
  GET|HEAD        api/user/post_user/{user_id} ................................................................................... Api\User\UsersController@get_post_user
  POST            api/user/update_password ..................................................................................... Api\User\UsersController@update_password
  POST            api/user/user_image_update/{user_id} ....................................................................... Api\User\UsersController@user_image_update
  GET|HEAD        api/user_site ..................................................................................... user_site.index › Api\User\UserSiteController@index
  POST            api/user_site ..................................................................................... user_site.store › Api\User\UserSiteController@store
  GET|HEAD        api/user_site/{user_site} ........................................................................... user_site.show › Api\User\UserSiteController@show
  PUT|PATCH       api/user_site/{user_site} ....................................................................... user_site.update › Api\User\UserSiteController@update
  DELETE          api/user_site/{user_site} ..................................................................... user_site.destroy › Api\User\UserSiteController@destroy
  POST            api/warehouse/add_warehouses .............................................................................. Api\User\WarehouseController@add_warehouses
  DELETE          api/warehouse/del_warehouse/{warehouse_id} ................................................................. Api\User\WarehouseController@del_warehouse
  POST            api/warehouse/edit_warehouse/{warehouse_id} ............................................................... Api\User\WarehouseController@edit_warehouse
  GET|HEAD        api/warehouse/get_activ_warehouse/{warehouse_id} ..................................................... Api\User\WarehouseController@get_activ_warehouse
  POST            api/warehouse/get_editing_warehouse/{warehouse_id} ................................................. Api\User\WarehouseController@get_editing_warehouse
  GET|HEAD        api/warehouse/get_warehouses .............................................................................. Api\User\WarehouseController@get_warehouses
  POST            email/resend ................................................................................. verification.resend › Auth\VerificationController@resend
  GET|HEAD        email/verify ................................................................................... verification.notice › Auth\VerificationController@show
  GET|HEAD        email/verify/{id}/{hash} ..................................................................... verification.verify › Auth\VerificationController@verify
  GET|HEAD        horizon/api/batches ............................................................ horizon.jobs-batches.index › Laravel\Horizon › BatchesController@index
  POST            horizon/api/batches/retry/{id} ................................................. horizon.jobs-batches.retry › Laravel\Horizon › BatchesController@retry
  GET|HEAD        horizon/api/batches/{id} ......................................................... horizon.jobs-batches.show › Laravel\Horizon › BatchesController@show
  GET|HEAD        horizon/api/jobs/completed ............................................. horizon.completed-jobs.index › Laravel\Horizon › CompletedJobsController@index
  GET|HEAD        horizon/api/jobs/failed ...................................................... horizon.failed-jobs.index › Laravel\Horizon › FailedJobsController@index
  GET|HEAD        horizon/api/jobs/failed/{id} ................................................... horizon.failed-jobs.show › Laravel\Horizon › FailedJobsController@show
  GET|HEAD        horizon/api/jobs/pending ................................................... horizon.pending-jobs.index › Laravel\Horizon › PendingJobsController@index
  POST            horizon/api/jobs/retry/{id} ......................................................... horizon.retry-jobs.show › Laravel\Horizon › RetryController@store
  GET|HEAD        horizon/api/jobs/silenced ................................................ horizon.silenced-jobs.index › Laravel\Horizon › SilencedJobsController@index
  GET|HEAD        horizon/api/jobs/{id} ....................................................................... horizon.jobs.show › Laravel\Horizon › JobsController@show
  GET|HEAD        horizon/api/masters ........................................................ horizon.masters.index › Laravel\Horizon › MasterSupervisorController@index
  GET|HEAD        horizon/api/metrics/jobs .................................................... horizon.jobs-metrics.index › Laravel\Horizon › JobMetricsController@index
  GET|HEAD        horizon/api/metrics/jobs/{id} ................................................. horizon.jobs-metrics.show › Laravel\Horizon › JobMetricsController@show
  GET|HEAD        horizon/api/metrics/queues .............................................. horizon.queues-metrics.index › Laravel\Horizon › QueueMetricsController@index
  GET|HEAD        horizon/api/metrics/queues/{id} ........................................... horizon.queues-metrics.show › Laravel\Horizon › QueueMetricsController@show
  GET|HEAD        horizon/api/monitoring ........................................................ horizon.monitoring.index › Laravel\Horizon › MonitoringController@index
  POST            horizon/api/monitoring ........................................................ horizon.monitoring.store › Laravel\Horizon › MonitoringController@store
  GET|HEAD        horizon/api/monitoring/{tag} ........................................ horizon.monitoring-tag.paginate › Laravel\Horizon › MonitoringController@paginate
  DELETE          horizon/api/monitoring/{tag} .......................................... horizon.monitoring-tag.destroy › Laravel\Horizon › MonitoringController@destroy
  GET|HEAD        horizon/api/stats .............................................................. horizon.stats.index › Laravel\Horizon › DashboardStatsController@index
  GET|HEAD        horizon/api/workload .............................................................. horizon.workload.index › Laravel\Horizon › WorkloadController@index
  GET|HEAD        horizon/{view?} ................................................................................ horizon.index › Laravel\Horizon › HomeController@index
  GET|HEAD        login ...................................................................................................... login › Auth\LoginController@showLoginForm
  POST            login ...................................................................................................................... Auth\LoginController@login
  POST            logout ........................................................................................................... logout › Auth\LoginController@logout
  GET|HEAD        password/confirm .................................................................... password.confirm › Auth\ConfirmPasswordController@showConfirmForm
  POST            password/confirm ............................................................................................... Auth\ConfirmPasswordController@confirm
  POST            password/email ...................................................................... password.email › Auth\ForgotPasswordController@sendResetLinkEmail
  GET|HEAD        password/reset ................................................................... password.request › Auth\ForgotPasswordController@showLinkRequestForm
  POST            password/reset ................................................................................... password.update › Auth\ResetPasswordController@reset
  GET|HEAD        password/reset/{token} .................................................................... password.reset › Auth\ResetPasswordController@showResetForm
  GET|HEAD        register ...................................................................................... register › Auth\RegisterController@showRegistrationForm
  POST            register ............................................................................................................. Auth\RegisterController@register
  GET|HEAD        sanctum/csrf-cookie ................................................................. sanctum.csrf-cookie › Laravel\Sanctum › CsrfCookieController@show
  GET|HEAD        climbing.loc/{any} ................................................................................................. index › Site\IndexController@index
  GET|HEAD        films.climbing.loc/{any} .................................................................................... films_index › Films\IndexController@index
  GET|HEAD        shop.climbing.loc/{any} ....................................................................................... shop_index › Shop\IndexController@index
  GET|HEAD        user.climbing.loc/{any} ....................................................................................... user_index › User\IndexController@index
