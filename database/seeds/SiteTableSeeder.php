<?php

use Illuminate\Database\Seeder;

use App\Models\Site;

class SiteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site = new Site();

        $site->guid_title = 'Rock climbing In Georgia';
	    $site->guid_title_ru = 'Скалолазание в Грузии';
        $site->guid_title_ka = 'მეკლდეურობა საქართველოში';
        
	    $site->guid_description	 = 'Rock climbing in Georgia is a developing sport, but the country has a great prospect in rock climbing and in mountaineering. We constantly collect information about promising rocks, we am happy to accept your messages about new and unknown places. 
                                    There are many ready-made regions for all kinds of climbing. Also there are many places to implement and establish new climbing routes and regions, so it is very attractive for those who are searching for new unique places and opportunities.
                                    If you need any additional information feel free to contact us. We will provide you with all essential details, suggest a concrete way how to get to the site of climbing or offer his services for equipment and take you to the rock climbing areas.
                                    Alse if you need a climbing guide you can contact us. We can accompany you to any mountaineering and rock climbing regions throughout Georgia. Tour prices depend on the complexity of the route and the size of the group. Write to us for more details. Celk here for write message.
                                    We are constantly updating information about all regions so that you get full information. 
                                    Information partners. climbingingeorgia.wordpress.com
                                    See information about climbing areas in neighboring countries.
                                    Climbing in Armenia - uptherock.com
                                    Climbing in Turkey - climbing-europe.com/RockClimbingTurkey';

	    $site->guid_description_ru	 = 'Сервис Climbing.ge – это информационный сайт цель которого детальное описание альпинистких и скалолазных маршрутов в Грузии, и эту информацию сделать более доступной как для населения Грузии так и для спортсменов приезжающих из других стран.
                                        Наша команда постоянно работает над обновлением информации и добавляет детальное описание во все скалолазные регионы и альпинсткие маршруты. Старается описать всё как можно подробнее.
                                        В Грузии спортивное лазание и скалолазание в настоящее время не развито на должном уровне и недостаточно развито по регионам. Мы постоянно стараемся найти и создать для вас интересные маршруты для лазания и разрабатываем новые регионы, стараемся обнаружить новые, пригодные для лазания, скалы и в этом месте создать как можно больше маршрутов для скалолазания.
                                        К сожалению, пока что, это всё происходит на наши личные средства и поэтому процесс разработки новых трасс и регионов продвигается в медленном темпе .Мы готовы к партнёрству и сотрудничеству для развития данного вида спорта. Мы будем рады помощи любого типа, будь то финансовая, техническая помощь или если вы поделитесь с нами вашим опытом.
                                        Также на нашем сайте вы можете найти описание альпинистких маршрутов. Большая часть Грузии покрыта горами .Здесь есть красивейшие и технически сложные вершины , такие как: Ушба, Шхара, Казбег, Чаухский массив и многое другое. На склонах этих гор множество маршрутов. К сожалению эти маршруты не описаны. Мы старались собрать и описать пройденные нами или другими альпинистами маршруты. Если у вас есть информация и описание какого-либо маршрута, вы можете прислать эту информацию нам. Нам поможет эта информация в работе.
                                        Если вам понадобится дополнительная информация или вам необходимо взять гида для любого региона Грузии, напишите нам ,внизу дана форма и мы ответим вам очень быстро,
                                        CLIMBING.GE- желает вам хороших восхождений и тренировок.';

        $site->guid_description_ka	 = 'სერვისი climbing.ge, არის საინფორმაციო საიტი, რომელიც მიზნად ისახავს საქართველოს საცოცი და ალპინისტური მარშრუტების დეტალურ აღწერას და ამ იმფორმაციის მეტად პოპულარიზაციას, რათა ეს იმფორმაცია იყოს მეტად ხელმისაწვდომი, როგორც საქართველოს მოსახლეობისთვის, ასევე სხვა ქვეყნიდან ჩამოსული სპორცმენებისთვის. ჩვენი გუნდი მუდმივად მუსაობს ოიმისთვის, რომ იმფორმაცია ხშირად განახლდეს, დაემატოს მეტი სიახლე და ყველა საცოცი რეგიონი თუ ალპინისტური მარშრუტი იყოს რაც შეიძლება დეტალურად აღწერილი.
                                        საქართველოში მეკლდეურობა და სპორტული ცოცვა ჯერჯერობით არ არის განვითარებული სათანადო დონეზე, მაგრამ ჩვენ მუდამ ვცდილობთ ვიპოვოთ და შევქმნათ თქვენთვის საინტერესო საცოცი რეგიონები. აღმოვაჩინოთ ახალი, საცოცად გამოსადეგი კლდეები და ამ ადგილებში რაც შეიძლება მეტი საცოცი მარშრუტი გავაკეთოთ . სამწუხაროდ ჯერჯერობით ყვეკაფერი ეს ჩვენი პირადი ხარჯებით ხორციელდება, ამიტომაც ეს ყველაფერი საკმაოდ ნელი ტემპებით მიმდინარეობს. ჩვენ მუდმივად მზად ვართ პარტნიორობისთის, რათა სპორტის ეს სახეობა უფრო მეტად განვითარდეს. ჩვენ მოხარულები ვიქნებით ყველა ტიპის დახმარებისთვის, იქნება ეს ფინანსური, ტექნიკური თუ გამოცდილების გაზიარება.
                                        ასევე ჩვენს საიტზე თქვენ შეგიძლიათ იხილოთ ალპინისტური მარშრუტების არწერაც. საქართველოს უდიდესი ნაწილი მტებითაა დაფარული. აქ არის ბევრი ულამაზესი და ტექნიკურად რთული მწვერვალი. როგორიცაა: უშბა, ტეთნულდი, შხარა, მყინვარი, ჭაუხის მასივი და ბევრი სხვა. ამ მთებზე არის ბევრი მარშრუტი . სამწუხაროდ ეს მარშრუტები არ არის აღწერილი. ჩვენ ვცდილობთ შევაგროვოთ და ავღწეროთ ჩვენი თუ სხვის მიერ გავლილი მარშრუტები. თუ თქვენ გაქვთ იმფორმაცია და რომელიმე მარშრუტის აღწერა, თქვენ შეგიძლიათ გამოგვიგზავნოთ ის. ეს ჩვენ ძალიან დაგვეხმარება.
                                        თუ თქვენ დაგწირდათ დამატებითი იმფორმაცია ან თქვენ გინდათ გიდის აყვანა საქართველოს ნებისმიერ რეგიონში, მოგვწერეთ ქვემოთ მოყვანილი ფორმის მეშვეობით. ჩვენ მალე გიპასუხებთ
                                        Climbing.ge - ის გუნდი ყველას კარ ცოცვას და ვარჯიშს გისურვებთ.';
	    
        $site->guid_short_description = 'Rock climbing and mountaineering in Georgia';
	    $site->guid_short_description_ru = 'Скалолазание и альпинизм в Грузии';
        $site->guid_short_description_ka = 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა';




        $site->films_title = 'Rock climbing films';
	    $site->films_title_ru = 'Фильмы о скалолазании';
        $site->films_title_ka = 'ცოცვის ფილმები';

        $site->films_description = 'Climbing movies';
        $site->films_description_ru = 'Фильмы о скалолазании';
        $site->films_description_ka = 'ცოცვის ფილმები';

        $site->films_short_description = 'Climbing movies';
        $site->films_short_description_ru = 'Фильмы о скалолазании';
        $site->films_short_description_ka = 'ცოცვის ფილმები';




        $site->forum_title = 'Georgian Climbing forum';
	    $site->forum_title_ru = 'Форум скалолазание Грузии';
        $site->forum_title_ka = 'ქართული ცოცვის ფორუმი';

        $site->forum_description = 'Georgian climbing forum';
        $site->forum_description_ru = 'Форум скалолазание Грузии';
        $site->forum_description_ka = 'ქართული ცოცვის ფორუმი';

        $site->forum_short_description = 'Georgian climbing forum';
        $site->forum_short_description_ru = 'Форум скалолазание Грузии';
        $site->forum_short_description_ka = 'ქართული ცოცვის ფორუმი';





        $site->shop_title = 'Climbing.ge products and services';
	    $site->shop_title_ru = 'Товары и услуги Climbing.ge';
        $site->shop_title_ka = 'Climbing.ge-ს საქონელი და მომსახურება';

		$site->shop_description	 = 'Climbing.ge products';
		$site->shop_description_ru	 = 'Climbing.ge продукты';
        $site->shop_description_ka	 = 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ';

        $site->shop_short_description = 'Climbing.ge products';
        $site->shop_short_description_ru = 'Climbing.ge продукты';
        $site->shop_short_description_ka = 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ';



        
		$site->other_activity_description	 = 'Other activity for active rest in Georgia';
		$site->other_activity_description_ru	 = 'Другая деятельность для активного отдыха в Грузии';
        $site->other_activity_description_ka = 'სხვა აკტიობები';
        	
		$site->mount_description	 = 'Mountaineering in Georgia';
		$site->mount_description_ru	 = 'Альпинизм в Грузии';
        $site->mount_description_ka	 = 'ალპინისტური მარშრუტები საქართველოში(კავკასიონის ქედი)';
        
		$site->outdoor_description	 = 'Outdoor rock climbing area in Georgia';
		$site->outdoor_description_ru	 = 'Скалолазание на открытом воздухе в Грузии';
        $site->outdoor_description_ka	 = 'ცოცვა ბუნებრივ კლდეზე';
        
		$site->event_description	 = 'event_description';
		$site->event_description_ru	 = 'Скалолазание на открытом воздухе в Грузии';
        $site->event_description_ka	 = 'ცოცვა ბუნებრივ კლდეზე';
            
        $site->tech_tips_description = 'Climbing tech tips';
        $site->tech_tips_description_ru = 'Советы по альпинизму';
        $site->tech_tips_description_ka = 'ცოცვის ტექნიკური რჩევები';

        $site->news_description = 'Georgian climbing news';
        $site->news_description_ru = 'Новости скалолазания в Грузинского ';
        $site->news_description_ka = 'ცოცვის სიახლეები საქართველოშე';
            
        $site->index_gallery_description = 'Gallery';
        $site->index_gallery_description_ru = 'Галерея';
        $site->index_gallery_description_ka = 'გალერეა';

		$site->indoor_description	 = 'Indoor climbing gyms in Georgia';
		$site->indoor_description_ru	 = 'Скалолазные залы в грузии';
        $site->indoor_description_ka = 'საცოცი დარბაზები';
        
		$site->ice_description	 = 'Ice climbing in Georgia';
		$site->ice_description_ru	 = 'Ледолазание в Грузии';
        $site->ice_description_ka = 'ყინულზე ცოცვა';
        	
		$site->topo_description	 = 'Climbing topo';
		$site->topo_description_ru	 = 'Восхождение на вершину';
        $site->topo_description_ka	 = 'საქართველოს საცოცი მარშრუტები რუკაზე';
        
		$site->what_we_do_description	 = 'Georgia rock climbing and mountaineering description';
		$site->what_we_do_description_ru	 = 'Описание скалолазания и альпинизма в Грузии';
        $site->what_we_do_description_ka	 = 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა';

        $site->products_description = 'Climbing.ge products';
        $site->products_description_ru = 'Продукция Climbing.ge';
        $site->products_description_ka = 'Climbing.ge-ს პროდუქცია';

        $site->services_description = 'Climbing.ge services';
        $site->services_description_ru = 'Услуги Climbing.ge';
        $site->services_description_ka = 'Climbing.ge-ს მომსახურება';

        $site->fb_link = 'https://www.facebook.com/shop.climbing.ge';
        $site->inst_link = 'https://www.instagram.com/climbing.ge/';
        $site->google_link = 'samsonadze.temo9@gmail.com';
        $site->twit_link = '';

        $site->ad_published = 0;

        $site->email = 'info@'.env('BASE_URL');
        $site->number = '(+995) 598 45 70 48';

        $site->map = '<iframe src="https://www.google.com/maps/d/embed?mid=1vp_Rgh8UmlN7nZGFGHChh_rRy1t5GdOv" width="100%" height="480"></iframe>';

        $site->save();
    }
}
