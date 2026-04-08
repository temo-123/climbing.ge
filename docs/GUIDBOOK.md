<h1 align="center">Sport Climbing Spot Structure</h1>

<p><strong>climbing.ge</strong> is a Georgian climbing guidebook.</p>

<p>Below you can see the basic structure of an outdoor sport climbing area. It shows the database table structure for easier use.</p>

<h1>Articles</h1>

<p>Articles have a global table and locale tables. In the global table (<code>articles</code>), global article information is saved: published status, article image, and other data. In the locale table (<code>locale_articles</code>), you find only the article's locale version (English, Georgian, Russian).</p>

<img src="docs/DEMO_IMAGES/Guidbook/Article_structur_diogram.drawio.png" alt="Article structure diagram">

<h2>General info in article</h2>

<p>Sometimes you need to add the same information to many articles (contact information or other). For this, create general values in the <code>general_infos</code> table and insert them into your article blocks.</p>

<img src="docs/DEMO_IMAGES/Guidbook/General_info_structur_diogram.drawio.png" alt="General info structure">

<h1>Outdoor climbing region description, structure</h1>

<h2>Outdoor spot</h2>

<p>An outdoor climbing region is also an article with category "outdoor". For describing sectors/rocks, add all or many sector images for easier finding. These images are added in the <code>spot_rocks_images</code> table.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Climbing_spot_diogram.drawio.png" alt="Climbing spot diagram">

<h2>Sector local images (spot local images)</h2>

<p>Sometimes you need a small part of the area rocks. Add them in the <code>sector_local_images</code> table. In the image add form, you can insert multiple sectors.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Sector_local_images_(area_local_images).drawio.png" alt="Sector local images">

<h2>Sector</h2>

<p>In sector, add sector images in the individual table <code>sector_images</code>.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Sector_images_diogram.drawio.png" alt="Sector images">

<h2>Sector and routes</h2>

<p>The sector can include many types of climbing routes: sport climbing routes, top rope routes, trad climbing routes, bouldering routes, and multi-pitch routes.</p>

<p>For sport climbing routes, it's one table with different categories (sport route or bouldering). For multi-pitch, there are 2 tables: general multi-pitch table (<code>mtps</code>) and multi-pitch pitches (<code>mtp_pitchs</code>).</p>

<img src="docs/DEMO_IMAGES/Guidbook/Multy-pitch_structur_diogram.drawio.png" alt="Multi-pitch structure">
<img src="docs/DEMO_IMAGES/Guidbook/Route_diogram.drawio.png" alt="Route diagram">

<h1>Mountain climbing spot structure</h1>

<p>Below you can see the basic structure of mountaineering climbing area.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Mount_route_diogram.drawio.png" alt="Mount route diagram">

<h1>Gallery</h1>

<p>Column <code>image_type</code> in <code>galleries</code> table has 3 values (<code>"header_image"</code>, <code>"index_gallery_image"</code>, <code>"article_image"</code>). Depending on the category, the image is displayed in a specific block.</p>

<p>If category is <code>"article_image"</code>, add dependence in <code>gallery_image_article</code> table.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Guidbook_gallery_structure.drawio.png" alt="Guidebook gallery structure">

<h1>Comments</h1>

<p>Site guest or auth user can add article comments. If guest, private info (name, surname, email) is required. For registered/auth users, it's automatic.</p>

<p>All visitors can see commentator name on comments.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Guidbook_comments.drawio.png" alt="Guidebook comments">

<h2>Finding comment user</h2>

<p>If user is registered but not logged in, service requires private info. After creating comment, service compares email with user email (unique) and creates request. User gets notification in dashboard to confirm (<code>"Is it your comment?"</code>). If yes, comment added; if no, request deleted.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Is_it_your_comment.drawio.png" alt="Is it your comment">

<h2>Comment violation</h2>

<p>If user finds violation in comment, create complaint. Admin checks and decides. User gets email notification.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Comment_complaints.drawio.png" alt="Comment complaints">

<h1>Local business</h1>

<p>Also local business (guesthouse, tour company, shop) in climbing area. Structure same as article but with <code>article_id</code> and <code>published_date</code>. Select article to show on page, limit by last published date.</p>

<p>For localization: global (<code>suport_local_bisneses</code>) and locale (<code>locale_bisneses</code>) tables.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Locale_bisnes_structur_diogram.drawio.png" alt="Local business structure">

<p>Business also has gallery (<code>suport_local_bisnes_images</code>): <code>image</code> and <code>bisnes_id</code>.</p>

<h1>Events</h1>

<p>Event articles structure similar to articles: global (<code>events</code>) and locale (<code>locale_events</code>) tables.</p>

<h1>Favorite outdoor articles and interested events</h1>

<p>Add priority for most interesting events/outdoor areas using <code>favorite_outdoor_areas</code> and <code>interested_events</code> tables.</p>

<img src="docs/DEMO_IMAGES/Guidbook/Favorite_outdoor_area_and_interesting_event.drawio.png" alt="Favorites diagram">

<hr>

[Go back](../README.md)

