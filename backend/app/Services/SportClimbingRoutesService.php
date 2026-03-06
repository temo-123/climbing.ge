<?php

namespace App\Services;

use App\Models\Guide\Article;
use App\Models\Guide\Route;

class SportClimbingRoutesService
{
    public static function get_routes_quantity($request)
    {
        $article_sectors = Article::where('id',strip_tags($request->article_id))->first()->sectors;

        $sport_routes = [];
        $boulder_routes = [];

        $route_5a = 0;
        $route_5b = 0;
        $route_5c = 0;

        $route_6a = 0;
        $route_6a_p = 0;
        $route_6b = 0;
        $route_6b_p = 0;
        $route_6c = 0;
        $route_6c_p = 0;

        $route_7a = 0;
        $route_7a_p = 0;
        $route_7b = 0;
        $route_7b_p = 0;
        $route_7c = 0;
        $route_7c_p = 0;

        $route_8a = 0;
        $route_8a_p = 0;
        $route_8b = 0;
        $route_8b_p = 0;
        $route_8c = 0;
        $route_8c_p = 0;

        $route_9a = 0;
        $route_9a_p = 0;
        $route_9b = 0;
        $route_9b_p = 0;
        $route_9c = 0;
        $route_9c_p = 0;



        $route_v1 = 0; 
        $route_v1_p = 0;

        $route_v2 = 0;
        $route_v2_p = 0;

        $route_v3 = 0;
        $route_v3_p = 0;

        $route_v4 = 0;
        $route_v4_p = 0;

        $route_v5 = 0;
        $route_v5_p = 0;

        $route_v6 = 0;
        $route_v6_p = 0;

        $route_v7 = 0;
        $route_v7_p = 0;

        $route_v8 = 0;
        $route_v8_p = 0;

        $route_v9 = 0;
        $route_v9_p = 0;

        $route_v10 = 0;
        $route_v10_p = 0;

        $route_v11 = 0;
        $route_v11_p = 0;

        $route_v12 = 0;
        $route_v12_p = 0;

        $route_v13 = 0;
        $route_v13_p = 0;

        $route_v14 = 0;
        $route_v14_p = 0;

        $route_v15 = 0;
        $route_v15_p = 0;

        $route_v16 = 0;
        $route_v16_p = 0;

        $route_v17 = 0;
        $route_v17_p = 0;

        $route_v18 = 0;
        $route_v18_p = 0;

        foreach ($article_sectors as $sector) {
            $routes = Route::where('sector_id',strip_tags($sector->id))->get(['grade', 'category']);

            foreach ($routes as $route) {
                if($route->category == "sport climbing"){
                    array_push($sport_routes, $route->grade); 
                }
                elseif ($route->category == "bouldering") {
                    array_push($boulder_routes, $route->grade); 
                }
            }

        }

        foreach ($sport_routes as $sport_quantyty) {
            if($sport_quantyty == "5a"){ $route_5a++; }
            if($sport_quantyty == "5b"){ $route_5b++; }
            if($sport_quantyty == "5c"){ $route_5c++; }

            if($sport_quantyty == "6a"){ $route_6a++; }
            if($sport_quantyty == "6a+"){ $route_6a_p++; }
            if($sport_quantyty == "6b"){ $route_6b++; }
            if($sport_quantyty == "6b+"){ $route_6b_p++; }
            if($sport_quantyty == "6c"){ $route_6c++; }
            if($sport_quantyty == "6c+"){ $route_6c_p++; }

            if($sport_quantyty == "7a"){ $route_7a++; }
            if($sport_quantyty == "7a+"){ $route_7a_p++; }
            if($sport_quantyty == "7b"){ $route_7b++; }
            if($sport_quantyty == "7b+"){ $route_7b_p++; }
            if($sport_quantyty == "7c"){ $route_7c++; }
            if($sport_quantyty == "7c+"){ $route_7c_p++; }

            if($sport_quantyty == "8a"){ $route_8a++; }
            if($sport_quantyty == "8a+"){ $route_8a_p++; }
            if($sport_quantyty == "8b"){ $route_8b++; }
            if($sport_quantyty == "8b+"){ $route_8b_p++; }
            if($sport_quantyty == "8c"){ $route_8c++; }
            if($sport_quantyty == "8c+"){ $route_8c_p++; }

            if($sport_quantyty == "9a"){ $route_9a++; }
            if($sport_quantyty == "9a+"){ $route_9a_p++; }
            if($sport_quantyty == "9b"){ $route_9b++; }
            if($sport_quantyty == "9b+"){ $route_9b_p++; }
            if($sport_quantyty == "9c"){ $route_9c++; }
            if($sport_quantyty == "9c+"){ $route_9c_p++; }
        }

        $sport_routes_quantyty = [
            ['Grade', 'Quantity', ['role' => 'style']],
        ];

        if($route_5a != 0){ array_push($sport_routes_quantyty, ["5a", $route_5a, "color: #a7d5ed"]); }
        if($route_5b != 0){ array_push($sport_routes_quantyty, ["5b", $route_5b, "color: #a7d5ed"]); }
        if($route_5c != 0){ array_push($sport_routes_quantyty, ["5c", $route_5c, "color: #a7d5ed"]); }

        if($route_6a != 0){ array_push($sport_routes_quantyty, ["6a", $route_6a, "color: #F1C40F"]); }
        if($route_6a_p != 0){ array_push($sport_routes_quantyty, ["6a+", $route_6a_p, "color: #F1C40F"]); }
        if($route_6b != 0){ array_push($sport_routes_quantyty, ["6b", $route_6b, "color: #F1C40F"]); }
        if($route_6b_p != 0){ array_push($sport_routes_quantyty, ["6b+", $route_6b_p, "color: #F1C40F"]); }
        if($route_6c != 0){ array_push($sport_routes_quantyty, ["6c", $route_6c, "color: #F1C40F"]); }
        if($route_6c_p != 0){ array_push($sport_routes_quantyty, ["6c+", $route_6c_p, "color: #F1C40F"]); }

        if($route_7a != 0){ array_push($sport_routes_quantyty, ["7a", $route_7a, "color: #1ABC9C"]); }
        if($route_7a_p != 0){ array_push($sport_routes_quantyty, ["7a+", $route_7a_p, "color: #1ABC9C"]); }
        if($route_7b != 0){ array_push($sport_routes_quantyty, ["7b", $route_7b, "color: #1ABC9C"]); }
        if($route_7b_p != 0){ array_push($sport_routes_quantyty, ["7b+", $route_7b_p, "color: #1ABC9C"]); }
        if($route_7c != 0){ array_push($sport_routes_quantyty, ["7c", $route_7c, "color: #1ABC9C"]); }
        if($route_7c_p != 0){ array_push($sport_routes_quantyty, ["7c+", $route_7c_p, "color: #1ABC9C"]); }

        if($route_8a != 0){ array_push($sport_routes_quantyty, ["8a", $route_8a, "color: #e1a692"]); }
        if($route_8a_p != 0){ array_push($sport_routes_quantyty, ["8a+", $route_8a_p, "color: #e1a692"]); }
        if($route_8b != 0){ array_push($sport_routes_quantyty, ["8b", $route_8b, "color: #e1a692"]); }
        if($route_8b_p != 0){ array_push($sport_routes_quantyty, ["8b+", $route_8b_p, "color: #e1a692"]); }
        if($route_8c != 0){ array_push($sport_routes_quantyty, ["8c", $route_8c, "color: #e1a692"]); }
        if($route_8c_p != 0){ array_push($sport_routes_quantyty, ["8c+", $route_8c_p, "color: #e1a692"]); }

        if($route_9a != 0){ array_push($sport_routes_quantyty, ["9a", $route_9a, "color: #E74C3C"]); }
        if($route_9a_p != 0){ array_push($sport_routes_quantyty, ["9a+", $route_9a_p, "color: #E74C3C"]); }
        if($route_9b != 0){ array_push($sport_routes_quantyty, ["9b", $route_9b, "color: #E74C3C"]); }
        if($route_9b_p != 0){ array_push($sport_routes_quantyty, ["9b+", $route_9b_p, "color: #E74C3C"]); }
        if($route_9c != 0){ array_push($sport_routes_quantyty, ["9c", $route_9c, "color: #E74C3C"]); }
        if($route_9c_p != 0){ array_push($sport_routes_quantyty, ["9c+", $route_9c_p, "color: #E74C3C"]); }
        // https://htmlcolorcodes.com/color-chart/    -------    colors

        foreach ($boulder_routes as $boulder_quantyty) {
            if($boulder_quantyty == "V1"){ $route_v1++; }
            if($boulder_quantyty == "V1+"){ $route_v1_p++; }

            if($boulder_quantyty == "V2"){ $route_v2++; }
            if($boulder_quantyty == "V2+"){ $route_v2_p++; }

            if($boulder_quantyty == "V3"){ $route_v3++; }
            if($boulder_quantyty == "V3+"){ $route_v3_p++; }

            if($boulder_quantyty == "V4"){ $route_v4++; }
            if($boulder_quantyty == "V4+"){ $route_v4_p++; }

            if($boulder_quantyty == "V5"){ $route_v5++; }
            if($boulder_quantyty == "V5+"){ $route_v5_p++; }

            if($boulder_quantyty == "V6"){ $route_v6++; }
            if($boulder_quantyty == "V6+"){ $route_v6_p++; }

            if($boulder_quantyty == "V7"){ $route_v7++; }
            if($boulder_quantyty == "V7+"){ $route_v7_p++; }

            if($boulder_quantyty == "V8"){ $route_v8++; }
            if($boulder_quantyty == "V8+"){ $route_v8_p++; }

            if($boulder_quantyty == "V9"){ $route_v9++; }
            if($boulder_quantyty == "V9+"){ $route_v9_p++; }

            if($boulder_quantyty == "V10"){ $route_v10++; }
            if($boulder_quantyty == "V10+"){ $route_v10_p++; }

            if($boulder_quantyty == "V11"){ $route_v11++; }
            if($boulder_quantyty == "V11+"){ $route_v11_p++; }

            if($boulder_quantyty == "V12"){ $route_v12++; }
            if($boulder_quantyty == "V12+"){ $route_v12_p++; }

            if($boulder_quantyty == "V13"){ $route_v13++; }
            if($boulder_quantyty == "V13+"){ $route_v13_p++; }

            if($boulder_quantyty == "V14"){ $route_v14++; }
            if($boulder_quantyty == "V14+"){ $route_v14_p++; }

            if($boulder_quantyty == "V15"){ $route_v15++; }
            if($boulder_quantyty == "V15+"){ $route_v15_p++; }

            if($boulder_quantyty == "V16"){ $route_v16++; }
            if($boulder_quantyty == "V16+"){ $route_v16_p++; }

            if($boulder_quantyty == "V17"){ $route_v17++; }
            if($boulder_quantyty == "V17+"){ $route_v17_p++; }

            if($boulder_quantyty == "V18"){ $route_v18++; }
            if($boulder_quantyty == "V18+"){ $route_v18_p++; }
        }

        $boulder_routes_quantyty = [
            ['Grade', 'Quantity', ['role' => 'style']],
        ];
        
        if($route_v1 != 0){ array_push($boulder_routes_quantyty, ["V1", $route_v1, "color: #7FB3D5"]); }
        if($route_v1_p != 0){ array_push($boulder_routes_quantyty, ["V1+", $route_v1_p, "color: #7FB3D5"]); }

        if($route_v2 != 0){ array_push($boulder_routes_quantyty, ["V2", $route_v2, "color: #85C1E9"]); }
        if($route_v2_p != 0){ array_push($boulder_routes_quantyty, ["V2+", $route_v2_p, "color: #85C1E9"]); }

        if($route_v3 != 0){ array_push($boulder_routes_quantyty, ["V3", $route_v3, "color: #2E86C1"]); }
        if($route_v3_p != 0){ array_push($boulder_routes_quantyty, ["V3+", $route_v3_p, "color: #2E86C1"]); }

        if($route_v4 != 0){ array_push($boulder_routes_quantyty, ["V4", $route_v4, "color: #1ABC9C"]); }
        if($route_v4_p != 0){ array_push($boulder_routes_quantyty, ["V4+", $route_v4_p, "color: #1ABC9C"]); }

        if($route_v5 != 0){ array_push($boulder_routes_quantyty, ["V5", $route_v5, "color: #16A085"]); }
        if($route_v5_p != 0){ array_push($boulder_routes_quantyty, ["V5+", $route_v5_p, "color: #16A085"]); }

        if($route_v6 != 0){ array_push($boulder_routes_quantyty, ["V6", $route_v6, "color: #27AE60"]); }
        if($route_v6_p != 0){ array_push($boulder_routes_quantyty, ["V6+", $route_v6_p, "color: #27AE60"]); }

        if($route_v7 != 0){ array_push($boulder_routes_quantyty, ["V7", $route_v7, "color: #1D8348"]); }
        if($route_v7_p != 0){ array_push($boulder_routes_quantyty, ["V7+", $route_v7_p, "color: #1D8348"]); }

        if($route_v8 != 0){ array_push($boulder_routes_quantyty, ["V8", $route_v8, "color: #145A32"]); }
        if($route_v8_p != 0){ array_push($boulder_routes_quantyty, ["V8+", $route_v8_p, "color: #145A32"]); }

        if($route_v9 != 0){ array_push($boulder_routes_quantyty, ["V9", $route_v9, "color: #F1C40F"]); }
        if($route_v9_p != 0){ array_push($boulder_routes_quantyty, ["V9+", $route_v9_p, "color: #F1C40F"]); }

        if($route_v10 != 0){ array_push($boulder_routes_quantyty, ["V10", $route_v10, "color: #B9770E"]); }
        if($route_v10_p != 0){ array_push($boulder_routes_quantyty, ["V10+", $route_v10_p, "color: #B9770E"]); }

        if($route_v11 != 0){ array_push($boulder_routes_quantyty, ["V11", $route_v11, "color: #CA6F1E"]); }
        if($route_v11_p != 0){ array_push($boulder_routes_quantyty, ["V11+", $route_v11_p, "color: #CA6F1E"]); }

        if($route_v12 != 0){ array_push($boulder_routes_quantyty, ["V12", $route_v12, "color: #A04000"]); }
        if($route_v12_p != 0){ array_push($boulder_routes_quantyty, ["V12+", $route_v12_p, "color: #A04000"]); }

        if($route_v13 != 0){ array_push($boulder_routes_quantyty, ["V13", $route_v13, "color: #E74C3C"]); }
        if($route_v13_p != 0){ array_push($boulder_routes_quantyty, ["V13+", $route_v13_p, "color: #E74C3C"]); }

        if($route_v14 != 0){ array_push($boulder_routes_quantyty, ["V14", $route_v14, "color: #B03A2E"]); }
        if($route_v14_p != 0){ array_push($boulder_routes_quantyty, ["V14+", $route_v14_p, "color: #B03A2E"]); }

        if($route_v15 != 0){ array_push($boulder_routes_quantyty, ["V15", $route_v15, "color: #7B241C"]); }
        if($route_v15_p != 0){ array_push($boulder_routes_quantyty, ["V15+", $route_v15_p, "color: #7B241C"]); }

        if($route_v16 != 0){ array_push($boulder_routes_quantyty, ["V16", $route_v16, "color: #5D6D7E"]); }
        if($route_v16_p != 0){ array_push($boulder_routes_quantyty, ["V16+", $route_v16_p, "color: #5D6D7E"]); }

        if($route_v17 != 0){ array_push($boulder_routes_quantyty, ["V17", $route_v17, "color: #2E4053"]); }
        if($route_v17_p != 0){ array_push($boulder_routes_quantyty, ["V17+", $route_v17_p, "color: #2E4053"]); }

        if($route_v18 != 0){ array_push($boulder_routes_quantyty, ["V18", $route_v18, "color: #17202A"]); }
        if($route_v18_p != 0){ array_push($boulder_routes_quantyty, ["V18+", $route_v18_p, "color: #17202A"]); }

        // echo '<pre>';
        // var_dump($route_quantyty);
        // echo '</pre>';

        return $routes_quantyty = ['sport_routes' => $sport_routes_quantyty, 'bouldering' => $boulder_routes_quantyty];
    }
}