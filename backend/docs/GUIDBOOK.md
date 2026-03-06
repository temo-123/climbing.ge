<h1 align="center">Sport climbing spor structur</h1>

<p>climbing.ge - is a georgian climbing guidbook.</p>

<p>Down you can see the basic structure of outdoor sport climbing area. It`s a show databases tab structure for more comfortable to using.</p>


<h1>Articles</h1>

<p>Article have global tab and locale tab. In globale tab (article), seved article global information published or not, article image and other data. in local article tab (locale_article), you fined only article locale version, locales is English, Georgian and russion</p>

![Article structur diogram](/docs/Demo_images/Guidbook/Article_structur_diogram.drawio.png)

<h2>General info in article</h2>

<p>Some time you need add same information for many article (contact information or other). For this you can create general value in "general_infos" tab and insert this in blocks of your article</p>

![Article structur diogram](/docs/Demo_images/Guidbook/General_info_structur_diogram.drawio.png)


<h1>Outdoor climbing region description, structure</h1>

<h2>Outdoor spot</h2>

<p>Outdoor climbing region it also article with category "outdoor"</p> <p>For description sectors rocks you need all or many sector image for easier finding it. this image added in "sector_images" tab.</p>

![Climbing spot diogram](/docs/Demo_images/Guidbook/Spot_rocks_images.drawio.png)

<h2>Sector local images (spot local images)</h2>

<p>Some times you need small part of this area rocks. It possible to adding in "sector_local_images" tab</p>

<p>In image add form you can insert sectors, how much you won't.</p>

![Sector local images diogram](/docs/Demo_images/Guidbook/Sector_local_images_(area_local_images).drawio.png)

<h2>Sector</h2>

<p>In sector you need sector images. Images added in individual table "sector_images"</p>

![Sector images diogram](/docs/Demo_images/Guidbook/Sector_images_diogram.drawio.png)

<h2>Sector and routes </h2>

<p>The sector can includs many of tipe of climbing routes. Sport climbing routes, top rope routes, tred climbing routes, bouldering routes and pulti-pitchs routes</p>

<p>For sport climbing routes it`s only 1 tab in database and it have diferent caregory (sport route or bouldering), but for multi pitch is 2 tab: general multi-pitcs tag (mtps) and multi-pitchs pitcs (mtp_pitchs). multi pitchs includ pitch</p>

<p>down you can see routes structur diograms</p>

![Multy-pitch structur diogram](/docs/Demo_images/Guidbook/Multy-pitch_structur_diogram.drawio.png)    ![Route diogram](/docs/Demo_images/Guidbook/Route_diogram.drawio.png)


<h2>Outdoor climbing area big diogram</h2>

![Climbing spot total diogram](/docs/Demo_images/Guidbook/Climbing_spot_diogram.drawio.png)


<h1>Mountain climbing spor structur</h1>

<p>Down you can see the basic structur of mountaineering climbing area.</p>

![Mount route diogram](/docs/Demo_images/Guidbook/Mount_route_diogram.drawio.png)


<h1>Gallery</h1>

<p>Column "image_type" in "galleries" table have 3 values. ("header_image", "index_gallery_image", "article_image"). Depending on the category, the image is displayed in a specific block.</p>

<p>If category is "article_image" you need add dependence in "gallery_image_article" tab</p>

![Guidbook gallery structure diogram](/docs/Demo_images/Guidbook/Guidbook_gallery_structure.drawio.png)


<h1>Comments</h1>

<p>Site gest or auth user can add article comment. If the site visit gest, necessary add privot information (name, surname and email). If user is registrated and authed privat information insert automaticly.</p>

<p>All visitors can show commentator name on all comments</p>

![Guidbook comments diogram](/docs/Demo_images/Guidbook/Guidbook_comments.drawio.png)

<h2>Finding comment user</h2>

<p>If user is registrated but not logined, servise will require privat information. After create comment by user, service compare this comment mail and user meil (user meil are unicul) and create recuest for registrated user if it found.. </p>

<p>User with take notification in dashboard and answer on question ("is it your comment?"). If answer is "yes" this comment added on this user, if "not" request will be deleted.</p>

![Is it your comment](/docs/Demo_images/Guidbook/Is_it_your_comment.drawio.png)

<h2>Comment violation</h2>

<p>If user find some violation in comment, possible to create complaint. Admin check this complaint and make decision. Also user take email notification about decision.</p>

![Comment complaints](/docs/Demo_images/Guidbook/Comment_complaints.drawio.png)


<h1>Local bisnes</h1>

<p>We have alse local bisnes. This is some interesting bisnes (gesthous, tour company or shop) in climbing area. Structur of bisnes is same of article but it hawe "article_id" and "published_data" tub. Need select article for show them on article page and last published data for limited showing bisnes. For localization global bisnes tab ('suport_local_bisneses') and local bisnes tab ('locale_bisneses') </p>

![Locale bisnes structur diogram](/docs/Demo_images/Guidbook/Locale_bisnes_structur.drawio.png)

<p>Alse bisnes have gallery (tab -> 'suport_local_bisnes_images'). Row have "image" and "bisnes_id" values.</p>


<h1>Events</h1>

<p>We have alse event articles. Structur of event is similar of article. Global event tab ('events') and local event tab ('locale_events') </p>


<h1>Favorite outdoor article and interested events</h1>

<p>You can add priotity for most interestid events and outdoor areas. For this service us "favorite_outdoor_areas" tab, for favorite outdoor area and "	interested_events" tab for priority ivents</p>

![Favorite outdoor area and interesting event](/docs/Demo_images/Guidbook/Favorite_outdoor_area_and_interesting_event.drawio.png)

<hr>

[Go back](../README.md) - to README.md