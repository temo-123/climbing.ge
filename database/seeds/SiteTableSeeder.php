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
        $site = new site();

        $site->title = 'Climbing In Georgia';
	    $site->title_ru = 'Скалолазание в Грузии';
        $site->title_ka = 'მეკლდეურობა საქართველოში';
        
	    $site->text	 = '';
	    $site->text_ru	 = '';
        $site->text_ka	 = '';
        
	    $site->short_description = 'Rock climbing and mountaineering in Georgia';
	    $site->short_description_ru = 'Скалолазание и альпинизм в Грузии';
        $site->short_description_ka = 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა';
        
		$site->other_activity_description	 = 'Other activity for active rest in Georgia';
		$site->other_activity_description_ru	 = 'Другая деятельность для активного отдыха в Грузии';
        $site->other_activity_description_ka = 'სხვა აკტიობები';
        	
		$site->mount_description	 = 'Mountaineering in Georgia';
		$site->mount_description_ru	 = 'Альпинизм в Грузии';
        $site->mount_description_ka	 = 'ალპინისტური მარშრუტები საქართველოში(კავკასიონის ქედი)';
        
		$site->outdoor_description	 = 'Outdoor rock climbing area in Georgia';
		$site->outdoor_description_ru	 = 'Скалолазание на открытом воздухе в Грузии';
        $site->outdoor_description_ka	 = 'ცოცვა ბუნებრივ კლდეზე';
        
		$site->indoor_description	 = 'Indoor climbing gyms in georgia';
		$site->indoor_description_ru	 = 'Скалолазные залы в грузии';
        $site->indoor_description_ka = 'საცოცი დარბაზები';
        	
		$site->topo_description	 = 'Climbing topo';
		$site->topo_description_ru	 = 'Восхождение на вершину';
        $site->topo_description_ka	 = 'საქართველოს საცოცი მარშრუტები რუკაზე';
        
		$site->what_we_do_description	 = 'Georgia rock climbing and mountaineering description';
		$site->what_we_do_description_ru	 = 'Описание скалолазания и альпинизма в Грузии';
        $site->what_we_do_description_ka	 = 'საქართველოს საცოცი სეკტორების და ალპინისტური მარშრუტების აღწერა';
        
		$site->shop_description	 = 'Climbing.ge products';
		$site->shop_description_ru	 = 'Climbing.ge продукты';
        $site->shop_description_ka	 = 'საცოცი პროდუკტები წარმოებული ჩვენი კომპანიის მიერ';

        $site->email = 'samsonadze.temo9@gmail.com';
        $site->map = '<iframe src="https://www.google.com/maps/d/embed?mid=1vp_Rgh8UmlN7nZGFGHChh_rRy1t5GdOv" width="100%" height="480"></iframe>';
        $site->save();
    }
}
