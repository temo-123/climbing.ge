<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Article extends Model implements Searchable
{
    public function getSearchResult(): SearchResult
    {
       $url = route('members.show', $this->id);
         
       return new SearchResult($this, $this->full_name, $url);
    }
}
