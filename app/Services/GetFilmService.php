<?php

namespace App\Services;

use App\Models\Film;
use App\Models\Film_files;
use App\Models\Locale_film;

class GetFilmService
{
    public static function get_films_list_use_locale($global_films, $locale = 'en')
    {
        $films = array();

        if($locale == "ru"){
            foreach ($global_films as $film) {
                $ru_films = Locale_film::where('id', '=', $film->ru_film_id,)->get();
                $temp_var = (new static)->create_films_array($ru_films, $film);
                array_push(
                    $films, 
                    $temp_var
                );
                $temp_var = [];
            }
        }
        elseif ($locale == "ka") {
            foreach ($global_films as $film) {
                $ka_films = Locale_film::where('id', '=', $film->ka_film_id,)->get();
                $temp_var = (new static)->create_films_array($ka_films, $film);
                array_push(
                    $films, 
                    $temp_var
                );
                $temp_var = [];
            }
        } 
        else {
            foreach ($global_films as $film) {
                $us_films = Locale_film::where('id', '=', $film->us_film_id)->get();
                $temp_var = (new static)->create_films_array($us_films, $film);
                array_push(
                    $films, 
                    $temp_var
                );
                $temp_var = [];
            }
        }
        
        return $films;
    }

    public function create_films_array($loc_film, $global_film)
    {
        return $array = [ 
            "local_film" => $loc_film[0], 
            "global_film" => $global_film 
        ];
    }


    public static function get_film_on_page_use_locale($global_film, $locale = 'en')
    {
        if($locale == "ru"){
            $ru_film_id = $global_film->ru_film_id;
            $loc_film = Locale_film::where('id', '=', $ru_film_id,)->get();
            
            return (new static)->create_page_arr($loc_film, $global_film);
        }
        elseif ($locale == "ka") {
            $ka_film_id = $global_film->ka_film_id;
            $loc_film = Locale_film::where('id', '=', $ka_film_id,)->get();
            
            return (new static)->create_page_arr($loc_film, $global_film);
        } else {
            $us_film_id = $global_film->us_film_id;
            $loc_film = Locale_film::where('id', '=', $us_film_id,)->first();
            
            return (new static)->create_page_arr($loc_film, $global_film);
        }
    }

    public function create_page_arr($loc_film, $global_film)
    {
        return $locale_film = 
            [
                'files' => $global_film->files,
                'local_film' => $loc_film, 
                'global_film' => $global_film,
            ];
    }
}