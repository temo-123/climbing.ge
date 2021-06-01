@extends('site.layouts.app')

@section('content')

@section('title', $title)

@if(isset($meta_title))
@section('meta_title', $meta_title)
@else
@section('meta_title', 'Climbing In Georgia')
@endif

@if(isset($meta_keyword))
@section('meta_keyword', $meta_keyword)
@else
@section('meta_keyword', 'Climbing In Georgia')
@endif

@if(isset($meta_description))
@section('meta_description', $meta_description)
@else
@section('meta_description', 'Climbing In Georgia')
@endif

@section('meta_img', (asset('/images/meta_img/indoor.jpg')))

<div class="container top_menu_margin h-recent-work">

    <h1 class="page_title" >@lang('site.title indoor climbing')</h1>                    

    @if($site->outdoor_description != NULL)
    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
    <h6>{!! $site -> outdoor_description!!}</h6>
    @endif

    @if (isset($indoors))
    @forelse($indoors as $indoor)
    <div class="row featurette" style='margin-bottom: 7%;'>
        <div class='row'>
            <div class='col-md-6 indoor_img'>
                <a href="{{ route('indoor_page', array('title'=>$indoor['url_title'])) }}">
                    <img src="{{asset('images/indoor_img/'.$indoor['image'])}}" alt="{{$indoor[0][0] -> title}}">
                </a>
            </div>
            <div class='col-md-6 indoor_text indoot_text_for_mobile'>
            <div class="container">
                <div class='row'>
                    <a href="{{ route('indoor_page', array('title'=>$indoor['url_title'])) }}">
                        <h2 style="margin: 2em; text-aline: center;">{{ $indoor[0][0] -> title }}</h2>
                    </a>
                    <p class="lead">{!! $indoor[0][0] -> short_description !!}</p>
                </div>
                <div class='row'>
                    <div class='col-xs-6 col-md-4 col-lg-4 col-xl-4'>
                        @if($indoor[0][0] -> prices_from != NULL)
                        <b style="font-size: 120%;">Prices from</b>
                        <p style="font-size: 100%;"><strong>{!! $indoor[0][0] -> prices_from !!} GEL </strong>( USD)</p>
                        @endif
                    </div>
                    <div class='col-xs-6 col-md-4 col-lg-4 col-xl-4'>
                        @if (($indoor[0][0] -> time) != 0)
                        <b style="font-size: 120%;">Working hours</b>
                        <p style="font-size: 100%;">{{ $indoor[0][0] -> time }}</p>
                        @endif
                    </div>
                </div>
                <div class='row'>
                    <div class='col-xs-12' style='text-align: center; margin-left: -10px;'>
                        @foreach($reviews as $review)
                        @if($review['article_title'] == $indoor[0][0]->url_title)
                        <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1" value="{{$review['review']}}">
                        @endif
                        @endforeach
                    </div>
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
@endsection