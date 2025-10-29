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
        $data = [
            [
                'slug' => 'guid_title',
                'us_data' => 'Rock climbing In Georgia',
                'ka_data' => 'მეკლდეურობა საქართველოში',
            ],
            [
                'slug' => 'guid_description',
                'us_data' => 'Rock climbing in Georgia is a developing sport, but the country has a great prospect in rock climbing and in mountaineering. We constantly collect information about promising rocks, we am happy to accept your messages about new and unknown places. 
                                            There are many ready-made regions for all kinds of climbing. Also there are many places to implement and establish new climbing routes and regions, so it is very attractive for those who are searching for new unique places and opportunities.
                                            If you need any additional information feel free to contact us. We will provide you with all essential details, suggest a concrete way how to get to the site of climbing or offer his services for equipment and take you to the rock climbing areas.
                                            Alse if you need a climbing guide you can contact us. We can accompany you to any mountaineering and rock climbing regions throughout Georgia. Tour prices depend on the complexity of the route and the size of the group. Write to us for more details. Celk here for write message.
                                            We are constantly updating information about all regions so that you get full information. 
                                            Information partners. climbingingeorgia.wordpress.com
                                            See information about climbing areas in neighboring countries.
                                            Climbing in Armenia - uptherock.com
                                            Climbing in Turkey - climbing-europe.com/RockClimbingTurkey',
                'ka_data' => 'სერვისი climbing.ge, არის საინფორმაციო საიტი, რომელიც მიზნად ისახავს საქართველოს საცოცი და ალპინისტური მარშრუტების დეტალურ აღწერას და ამ იმფორმაციის მეტად პოპულარიზაციას, რათა ეს იმფორმაცია იყოს მეტად ხელმისაწვდომი, როგორც საქართველოს მოსახლეობისთვის, ასევე სხვა ქვეყნიდან ჩამოსული სპორცმენებისთვის. ჩვენი გუნდი მუდმივად მუსაობს ოიმისთვის, რომ იმფორმაცია ხშირად განახლდეს, დაემატოს მეტი სიახლე და ყველა საცოცი რეგიონი თუ ალპინისტური მარშრუტი იყოს რაც შეიძლება დეტალურად აღწერილი.,
                                                საქართველოში მეკლდეურობა და სპორტული ცოცვა ჯერჯერობით არ არის განვითარებული სათანადო დონეზე, მაგრამ ჩვენ მუდამ ვცდილობთ ვიპოვოთ და შევქმნათ თქვენთვის საინტერესო საცოცი რეგიონები. აღმოვაჩინოთ ახალი, საცოცად გამოსადეგი კლდეები და ამ ადგილებში რაც შეიძლება მეტი საცოცი მარშრუტი გავაკეთოთ . სამწუხაროდ ჯერჯერობით ყვეკაფერი ეს ჩვენი პირადი ხარჯებით ხორციელდება, ამიტომაც ეს ყველაფერი საკმაოდ ნელი ტემპებით მიმდინარეობს. ჩვენ მუდმივად მზად ვართ პარტნიორობისთის, რათა სპორტის ეს სახეობა უფრო მეტად განვითარდეს. ჩვენ მოხარულები ვიქნებით ყველა ტიპის დახმარებისთვის, იქნება ეს ფინანსური, ტექნიკური თუ გამოცდილების გაზიარება.
                                                ასევე ჩვენს საიტზე თქვენ შეგიძლიათ იხილოთ ალპინისტური მარშრუტების არწერაც. საქართველოს უდიდესი ნაწილი მტებითაა დაფარული. აქ არის ბევრი ულამაზესი და ტექნიკურად რთული მწვერვალი. როგორიცაა: უშბა, ტეთნულდი, შხარა, მყინვარი, ჭაუხის მასივი და ბევრი სხვა. ამ მთებზე არის ბევრი მარშრუტი . სამწუხაროდ ეს მარშრუტები არ არის აღწერილი. ჩვენ ვცდილობთ შევაგროვოთ და ავღწეროთ ჩვენი თუ სხვის მიერ გავლილი მარშრუტები. თუ თქვენ გაქვთ იმფორმაცია და რომელიმე მარშრუტის აღწერა, თქვენ შეგიძლიათ გამოგვიგზავნოთ ის. ეს ჩვენ ძალიან დაგვეხმარება.
                                                თუ თქვენ დაგწირდათ დამატებითი იმფორმაცია ან თქვენ გინდათ გიდის აყვანა საქართველოს ნებისმიერ რეგიონში, მოგვწერეთ ქვემოთ მოყვანილი ფორმის მეშვეობით. ჩვენ მალე გიპასუხებთ
                                                Climbing.ge - ის გუნდი ყველას კარ ცოცვას და ვარჯიშს გისურვებთ.',
            ],
            [
                'slug' => 'guid_short_description',
                'us_data' => 'Rock climbing and mountaineering in Georgia',
                'ka_data' => 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა',
            ],
            [
                'slug' => 'films_title',
                'us_data' => 'Rock climbing films',
                'ka_data' => 'ცოცვის ფილმები',
            ],
            [
                'slug' => 'films_description',
                'us_data' => 'Climbing movies',
                'ka_data' => 'ცოცვის ფილმები',
            ],
            [
                'slug' => 'films_short_description',
                'us_data' => 'Climbing movies',
                'ka_data' => 'ცოცვის ფილმები',
            ],
            [
                'slug' => 'forum_title',
                'us_data' => 'Georgian Climbing forum',
                'ka_data' => 'ქართული ცოცვის ფორუმი',
            ],
            [
                'slug' => 'forum_description',
                'us_data' => 'Georgian climbing forum',
                'ka_data' => 'ქართული ცოცვის ფორუმი',
            ],
            [
                'slug' => 'forum_short_description',
                'us_data' => 'Georgian climbing forum',
                'ka_data' => 'ქართული ცოცვის ფორუმი',
            ],
            [
                'slug' => 'shop_title',
                'us_data' => 'Climbing.ge products and services',
                'ka_data' => 'Climbing.ge-ს საქონელი და მომსახურება',
            ],
            [
                'slug' => 'shop_description',
                'us_data' => 'Climbing.ge products',
                'ka_data' => 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ',
            ],
            [
                'slug' => 'shop_short_description',
                'us_data' => 'Climbing.ge products',
                'ka_data' => 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ',
            ],
            [
                'slug' => 'other_activity_description',
                'us_data' => 'Other activity for active rest in Georgia',
                'ka_data' => 'სხვა აკტიობები',
            ],
            [
                'slug' => 'mount_description',
                'us_data' => 'Mountaineering in Georgia',
                'ka_data' => 'ალპინისტური მარშრუტები საქართველოში(კავკასიონის ქედი)',
            ],
            [
                'slug' => 'event_description',
                'us_data' => 'event_description',
                'ka_data' => 'ცოცვა ბუნებრივ კლდეზე',
            ],
            [
                'slug' => 'tech_tips_description',
                'us_data' => 'Climbing tech tips',
                'ka_data' => 'ცოცვის ტექნიკური რჩევები',
            ],
            [
                'slug' => 'news_description',
                'us_data' => 'Georgian climbing news',
                'ka_data' => 'ცოცვის სიახლეები საქართველოშე',
            ],
            [
                'slug' => 'index_gallery_description',
                'us_data' => 'Gallery',
                'ka_data' => 'გალერეა',
            ],
            [
                'slug' => 'outdoor_description',
                'us_data' => 'Outdoor rock climbing area in Georgia',
                'ka_data' => 'ცოცვა ბუნებრივ კლდეზე',
            ],
            [
                'slug' => 'indoor_description',
                'us_data' => 'Indoor climbing gyms in Georgia',
                'ka_data' => 'საცოცი დარბაზები',
            ],
            [
                'slug' => 'ice_description',
                'us_data' => 'Ice climbing in Georgia',
                'ka_data' => 'ყინულზე ცოცვა',
            ],
            [
                'slug' => 'topo_description',
                'us_data' => 'Climbing topo',
                'ka_data' => 'საქართველოს საცოცი მარშრუტები რუკაზე',
            ],
            [
                'slug' => 'what_we_do_description',
                'us_data' => 'Georgia rock climbing and mountaineering description',
                'ka_data' => 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა',
            ],
            [
                'slug' => 'products_description',
                'us_data' => 'Climbing.ge products',
                'ka_data' => 'Climbing.ge-ს პროდუქცია',
            ],
            [
                'slug' => 'services_description',
                'us_data' => 'Climbing.ge services',
                'ka_data' => 'Climbing.ge-ს მომსახურება',
            ],
            [
                'slug' => 'terms_of_use',
                'us_data' => null,
                'ka_data' => null,
            ],
        ];

        DB::table('locale_sites')->insert($data);
    }
}
