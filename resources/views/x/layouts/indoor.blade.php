@section('title', 'Indoor')

@section('meta_title', 'Indoor climbing in Georgia')
@section('meta_keyword', 'Climbing in Georgia')
@section('meta_description', 'In Georgia there are several climbing gyms for beginners and professionals. You can visit one of them')
@section('meta_img', (asset('assets/img/meta_img/indoor.jpg')))

<div class="container top_menu_margin h-recent-work">

    <h1 style="text-align: center; margin-bottom: 5%; font-size: 175%;">Indoor climbing</h1>                    

    @if($site->outdoor_description != NULL)
    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
    <h6>{{$site -> outdoor_description}}</h6>
    @endif

    @if (isset($indoors) && is_object($indoors) )
    @forelse($indoors as $k=>$indoor)
    
    <div class="row featurette" style='margin-bottom: 7%;'>
        <div class='row'>
            <div class='col-md-6 indoor_img'>
                <a href="{{ route('indoor_page', array('title'=>$indoor->url_title)) }}">
                    <img src="{{asset('images/indoor_img/'.$indoor->image)}}" alt="{{$indoor -> title}}">
                </a>
            </div>
            <div class='col-md-6 indoor_text indoot_text_for_mobile'>
                <div class='row'>
                    <a href="{{ route('indoor_page', array('title'=>$indoor->url_title)) }}">
                        <h2 class="featurette-heading">{{ $indoor -> title }}</h2>
                    </a>
                    <p class="lead">{!! $indoor -> short_description !!}</p>
                </div>
                <div class='row'>
                    <div class='col-xs-6 col-md-4 col-lg-4 col-xl-4'>
                        @if($indoor -> prices_from != NULL)
                        <b style="font-size: 120%;">Prices from</b>
                        <p style="font-size: 100%;">{!! $indoor -> prices_from !!}</p>
                        @endif
                    </div>
                    <div class='col-xs-6 col-md-4 col-lg-4 col-xl-4'>
                        @if (($indoor -> time) != 0)
                        <b style="font-size: 120%;">Working hours</b>
                        <p style="font-size: 100%;">{{ $indoor -> time }}</p>
                        @endif
                    </div>
                </div>
                <div class='row'>
                    <div class='col-xs-12' style='text-align: center; margin-left: -10px;'>
                        @foreach($reviews as $review)
                        @if($review['article_title'] == $indoor->url_title)
                        <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1" value="{{$review['review']}}">
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @empty

    <h2 class="text-center">No information about climbing gym and sectors</h2>
    <div style="display: none;">{!!$image_num = rand (1, 10)!!}</div>
    @if($image_num == 1)
    <img class="article_404" src="{{asset('images/404/empty_page/1.jpg')}}">
    @elseif($image_num == 2)
    <img class="article_404" src="{{asset('images/404/empty_page/2.jpg')}}">
    @elseif($image_num == 3)
    <img class="article_404" src="{{asset('images/404/empty_page/3.webp')}}">
    @elseif($image_num == 4)
    <img class="article_404" src="{{asset('images/404/empty_page/4.jpg')}}">
    @elseif($image_num == 5)
    <img class="article_404" src="{{asset('images/404/empty_page/5.jpg')}}">
    @elseif($image_num == 6)
    <img class="article_404" src="{{asset('images/404/empty_page/6.jpg')}}">
    @elseif($image_num == 7)
    <img class="article_404" src="{{asset('images/404/empty_page/7.jpg')}}">
    @elseif($image_num == 8)
    <img class="article_404" src="{{asset('images/404/empty_page/8.jpg')}}">
    @elseif($image_num == 9)
    <img class="article_404" src="{{asset('images/404/empty_page/9.jpg')}}">
    @else
    <img class="article_404" src="{{asset('images/404/empty_page/10.jpg')}}">
    @endif

    @endforelse
    @endif
    
</div>
