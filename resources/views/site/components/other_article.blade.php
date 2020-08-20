@if (isset($other_article) && is_object($other_article) )
    <div class="row similar_articles">
        <div class="container">
            <h2 id="other">Similar Articles.</h2>
            <hr class="other_line">
        </div>
        @foreach($other_article as $other_article_list)
                <div class="col-md-2">
                    <div class="many_articles_img">
                        <a href="{{ route($other_article_link, array('title'=>$other_article_list->url_title)) }}">
                            <img src="{{asset($other_article_img.$other_article_list->image)}}" alt="{{$other_article_list->title}}">
                        </a>
                    </div>
                    <div class="many_articles_title">
                        <a href="{{ route($other_article_link, array('title'=>$other_article_list->url_title)) }}">
                            <h3>{{$other_article_list->title}}</h3>
                        </a>
                    </div>
                </div>
        @endforeach
    </div>
@endif