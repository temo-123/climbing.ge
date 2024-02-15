<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\Locale_site;

class Locale_sitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locale_sites')->insert([
            [
                'id' => 1,

                'locale' => 'us',
    
                'guid_title' => 'Rock climbing In Georgia',
                'guid_description' => 'Rock climbing in Georgia is a developing sport, but the country has a great prospect in rock climbing and in mountaineering. We constantly collect information about promising rocks, we am happy to accept your messages about new and unknown places. 
                                            There are many ready-made regions for all kinds of climbing. Also there are many places to implement and establish new climbing routes and regions, so it is very attractive for those who are searching for new unique places and opportunities.
                                            If you need any additional information feel free to contact us. We will provide you with all essential details, suggest a concrete way how to get to the site of climbing or offer his services for equipment and take you to the rock climbing areas.
                                            Alse if you need a climbing guide you can contact us. We can accompany you to any mountaineering and rock climbing regions throughout Georgia. Tour prices depend on the complexity of the route and the size of the group. Write to us for more details. Celk here for write message.
                                            We are constantly updating information about all regions so that you get full information. 
                                            Information partners. climbingingeorgia.wordpress.com
                                            See information about climbing areas in neighboring countries.
                                            Climbing in Armenia - uptherock.com
                                            Climbing in Turkey - climbing-europe.com/RockClimbingTurkey',
                'guid_short_description' => 'Rock climbing and mountaineering in Georgia',
                'films_title' => 'Rock climbing films',
                'films_description' => 'Climbing movies',
                'films_short_description' => 'Climbing movies',
                'forum_title' => 'Georgian Climbing forum',
                'forum_description' => 'Georgian climbing forum',
                'forum_short_description' => 'Georgian climbing forum',
                'shop_title' => 'Climbing.ge products and services',
                'shop_description' => 'Climbing.ge products',
                'shop_short_description' => 'Climbing.ge products',
                'other_activity_description' => 'Other activity for active rest in Georgia',
                'mount_description' => 'Mountaineering in Georgia',
                'outdoor_description' => 'Outdoor rock climbing area in Georgia',
                'event_description' => 'event_description',
                'tech_tips_description' => 'Climbing tech tips',
                'news_description' => 'Georgian climbing news',
                'index_gallery_description' => 'Gallery',
                'indoor_description' => 'Indoor climbing gyms in Georgia',
                'ice_description' => 'Ice climbing in Georgia',
                'topo_description' => 'Climbing topo',
                'what_we_do_description' => 'Georgia rock climbing and mountaineering description',
                'products_description' => 'Climbing.ge products',
                'services_description' => 'Climbing.ge services',
            ],
            [
                'id' => 2,

                'locale' => 'ka',
    
                'guid_title_ka' => 'მეკლდეურობა საქართველოში',
                'guid_description_ka' => 'სერვისი climbing.ge, არის საინფორმაციო საიტი, რომელიც მიზნად ისახავს საქართველოს საცოცი და ალპინისტური მარშრუტების დეტალურ აღწერას და ამ იმფორმაციის მეტად პოპულარიზაციას, რათა ეს იმფორმაცია იყოს მეტად ხელმისაწვდომი, როგორც საქართველოს მოსახლეობისთვის, ასევე სხვა ქვეყნიდან ჩამოსული სპორცმენებისთვის. ჩვენი გუნდი მუდმივად მუსაობს ოიმისთვის, რომ იმფორმაცია ხშირად განახლდეს, დაემატოს მეტი სიახლე და ყველა საცოცი რეგიონი თუ ალპინისტური მარშრუტი იყოს რაც შეიძლება დეტალურად აღწერილი.,
                                                საქართველოში მეკლდეურობა და სპორტული ცოცვა ჯერჯერობით არ არის განვითარებული სათანადო დონეზე, მაგრამ ჩვენ მუდამ ვცდილობთ ვიპოვოთ და შევქმნათ თქვენთვის საინტერესო საცოცი რეგიონები. აღმოვაჩინოთ ახალი, საცოცად გამოსადეგი კლდეები და ამ ადგილებში რაც შეიძლება მეტი საცოცი მარშრუტი გავაკეთოთ . სამწუხაროდ ჯერჯერობით ყვეკაფერი ეს ჩვენი პირადი ხარჯებით ხორციელდება, ამიტომაც ეს ყველაფერი საკმაოდ ნელი ტემპებით მიმდინარეობს. ჩვენ მუდმივად მზად ვართ პარტნიორობისთის, რათა სპორტის ეს სახეობა უფრო მეტად განვითარდეს. ჩვენ მოხარულები ვიქნებით ყველა ტიპის დახმარებისთვის, იქნება ეს ფინანსური, ტექნიკური თუ გამოცდილების გაზიარება.
                                                ასევე ჩვენს საიტზე თქვენ შეგიძლიათ იხილოთ ალპინისტური მარშრუტების არწერაც. საქართველოს უდიდესი ნაწილი მტებითაა დაფარული. აქ არის ბევრი ულამაზესი და ტექნიკურად რთული მწვერვალი. როგორიცაა: უშბა, ტეთნულდი, შხარა, მყინვარი, ჭაუხის მასივი და ბევრი სხვა. ამ მთებზე არის ბევრი მარშრუტი . სამწუხაროდ ეს მარშრუტები არ არის აღწერილი. ჩვენ ვცდილობთ შევაგროვოთ და ავღწეროთ ჩვენი თუ სხვის მიერ გავლილი მარშრუტები. თუ თქვენ გაქვთ იმფორმაცია და რომელიმე მარშრუტის აღწერა, თქვენ შეგიძლიათ გამოგვიგზავნოთ ის. ეს ჩვენ ძალიან დაგვეხმარება.
                                                თუ თქვენ დაგწირდათ დამატებითი იმფორმაცია ან თქვენ გინდათ გიდის აყვანა საქართველოს ნებისმიერ რეგიონში, მოგვწერეთ ქვემოთ მოყვანილი ფორმის მეშვეობით. ჩვენ მალე გიპასუხებთ
                                                Climbing.ge - ის გუნდი ყველას კარ ცოცვას და ვარჯიშს გისურვებთ.',
    
                'guid_short_description_ka' => 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა',
                'films_title_ka' => 'ცოცვის ფილმები',
                'films_description_ka' => 'ცოცვის ფილმები',
                'films_short_description_ka' => 'ცოცვის ფილმები',
                'forum_title_ka' => 'ქართული ცოცვის ფორუმი',
                'forum_description_ka' => 'ქართული ცოცვის ფორუმი',
                'forum_short_description_ka' => 'ქართული ცოცვის ფორუმი',
                'shop_title_ka' => 'Climbing.ge-ს საქონელი და მომსახურება',
                'shop_description_ka' => 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ',
                'shop_short_description_ka' => 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ',
                'other_activity_description_ka' => 'სხვა აკტიობები',
                'mount_description_ka' => 'ალპინისტური მარშრუტები საქართველოში(კავკასიონის ქედი)',
                'outdoor_description_ka' => 'ცოცვა ბუნებრივ კლდეზე',
                'event_description_ka' => 'ცოცვა ბუნებრივ კლდეზე',
                'tech_tips_description_ka' => 'ცოცვის ტექნიკური რჩევები',
                'news_description_ka' => 'ცოცვის სიახლეები საქართველოშე',
                'index_gallery_description_ka' => 'გალერეა',
                'indoor_description_ka' => 'საცოცი დარბაზები',
                'ice_description_ka' => 'ყინულზე ცოცვა',
                'topo_description_ka' => 'საქართველოს საცოცი მარშრუტები რუკაზე',
                'what_we_do_description_ka' => 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა',
                'products_description_ka' => 'Climbing.ge-ს პროდუქცია',
                'services_description_ka' => 'Climbing.ge-ს მომსახურება',
            ],
            [
                'id' => 3,

                'locale' => 'ru',
    
                'guid_title_ru' => 'Скалолазание в Грузии',
                'guid_description_ru' => 'Сервис Climbing.ge – это информационный сайт цель которого детальное описание альпинистких и скалолазных маршрутов в Грузии, и эту информацию сделать более доступной как для населения Грузии так и для спортсменов приезжающих из других стран,
                                                Наша команда постоянно работает над обновлением информации и добавляет детальное описание во все скалолазные регионы и альпинсткие маршруты. Старается описать всё как можно подробнее.
                                                В Грузии спортивное лазание и скалолазание в настоящее время не развито на должном уровне и недостаточно развито по регионам. Мы постоянно стараемся найти и создать для вас интересные маршруты для лазания и разрабатываем новые регионы, стараемся обнаружить новые, пригодные для лазания, скалы и в этом месте создать как можно больше маршрутов для скалолазания.
                                                К сожалению, пока что, это всё происходит на наши личные средства и поэтому процесс разработки новых трасс и регионов продвигается в медленном темпе .Мы готовы к партнёрству и сотрудничеству для развития данного вида спорта. Мы будем рады помощи любого типа, будь то финансовая, техническая помощь или если вы поделитесь с нами вашим опытом.
                                                Также на нашем сайте вы можете найти описание альпинистких маршрутов. Большая часть Грузии покрыта горами .Здесь есть красивейшие и технически сложные вершины , такие как: Ушба, Шхара, Казбег, Чаухский массив и многое другое. На склонах этих гор множество маршрутов. К сожалению эти маршруты не описаны. Мы старались собрать и описать пройденные нами или другими альпинистами маршруты. Если у вас есть информация и описание какого-либо маршрута, вы можете прислать эту информацию нам. Нам поможет эта информация в работе.
                                                Если вам понадобится дополнительная информация или вам необходимо взять гида для любого региона Грузии, напишите нам ,внизу дана форма и мы ответим вам очень быстро,
                                                CLIMBING.GE- желает вам хороших восхождений и тренировок.',
                'guid_short_description_ru' => 'Скалолазание и альпинизм в Грузии',
                'films_title_ru' => 'Фильмы о скалолазании',
                'films_description_ru' => 'Фильмы о скалолазании',
                'films_short_description_ru' => 'Фильмы о скалолазании',
                'forum_title_ru' => 'Форум скалолазание Грузии',
                'forum_description_ru' => 'Форум скалолазание Грузии',
                'forum_short_description_ru' => 'Форум скалолазание Грузии',
                'shop_title_ru' => 'Товары и услуги Climbing.ge',
                'shop_description_ru' => 'Climbing.ge продукты',
                'shop_short_description_ru' => 'Climbing.ge продукты',
                'other_activity_description_ru' => 'Другая деятельность для активного отдыха в Грузии',
                'mount_description_ru' => 'Альпинизм в Грузии',
                'outdoor_description_ru' => 'Скалолазание на открытом воздухе в Грузии',
                'event_description_ru' => 'Скалолазание на открытом воздухе в Грузии',
                'tech_tips_description_ru' => 'Советы по альпинизму',
                'news_description_ru' => 'Новости скалолазания в Грузинского ',
                'index_gallery_description_ru' => 'Галерея',
                'indoor_description_ru' => 'Скалолазные залы в грузии',
                'ice_description_ru' => 'Ледолазание в Грузии',
                'topo_description_ru' => 'Восхождение на вершину',
                'what_we_do_description_ru' => 'Описание скалолазания и альпинизма в Грузии',
                'products_description_ru' => 'Продукция Climbing.ge',
                'services_description_ru' => 'Услуги Climbing.ge',
            ],
        ]);

    }
}
