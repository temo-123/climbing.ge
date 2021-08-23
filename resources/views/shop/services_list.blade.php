@extends('shop.layouts.app')

@section('content')

@section('title', $title)

@if($site->shop_description != null)
@section('meta_keyword', $site->shop_description)
@else
@section('meta_keyword', 'Services')
@endif

@section('meta_description', $site->shop_description)
@section('meta_img', (asset('images/meta_img/shop.jpg')))

<div class="container top_menu_margin h-recent-work">

    <h1 class="page_title">@lang('shop.services title')</h1>

    @if($site->services_description != null)
        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
        @if($page_locale == "ru")
            <h6>{!! $site -> services_description_ru !!}</h6>
        @elseif($page_locale == "ka")
            <h6>{!! $site -> services_description_ka !!}</h6>
        @else
            <h6>{!! $site -> services_description !!}</h6>
        @endif
    @endif

    <div class="row">

        @if(isset($services))
        @foreach ($services as $service)
        <div class="col-md-3 col-sm-6">
            <div class="service-box ">
                <div class="service-icon red services_bacground_image" style="background-image: url({{ asset('images/service_img/'.$service['image']) }});">
                    <div class="front-content">
                        {{-- <i class="fa fa-trophy"></i> --}}
                        <h3 class='services-title'>{{$service[0][0] -> title}}</h3>
                    </div>
                </div>
                <div class="service-content">
                    <h3><a href="{{route('service_page', array('url_title'=>$service['url_title']))}}">{{$service[0][0] -> title}}</a></h3>
                    {!! $service[0][0] -> short_description  !!} 
                </div>
            </div>
        </div>
        @endforeach
        @endif
        {{-- <div class="col-md-3 col-sm-6 ">
            <div class="service-box">
                <div class="service-icon yellow">
                    <div class="front-content">
                        <i class="fa fa-trophy"></i>
                        <h3>design</h3>
                    </div>
                </div>
                <div class="service-content">
                    <h3>design</h3>
                    <p>No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 ">
            <div class="service-box">
                <div class="service-icon orange">
                    <div class="front-content">
                        <i class="fa fa-anchor"></i>
                        <h3>php</h3>
                    </div>
                </div>
                <div class="service-content">
                    <h3>php developer</h3>
                    <p>No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="service-box ">
                <div class="service-icon red">
                    <div class="front-content">
                        <i class="fa fa-trophy"></i>
                        <h3>Ui Developer</h3>
                    </div>
                </div>
                <div class="service-content">
                    <h3>Developer</h3>
                    <p>No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="service-box">
                <div class="service-icon grey">
                    <div class="front-content">
                        <i class="fa fa-paper-plane-o"></i>
                        <h3>java script</h3>
                    </div>
                </div>
                <div class="service-content">
                    <h3>java script</h3>
                    <p>No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure</p>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection