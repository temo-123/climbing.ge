@extends('shop.layouts.app')

@section('content')
@section('title',  $service->title)
@section('meta_description',  $service->short_description)
@section('meta_keyword',  $service->title)
@section('meta_img',  asset('images/service_img/'.$service_image))

@section('created_at',  $service->created_at)
@section('updated_at',  $service->updated_at)

<div class="container top_menu_margin h-recent-work">

    <h1 class="page_title">{{$service->title}}</h1>
    <div class="row">
        <img class="service_page_image" src="{{asset('images/service_img/'.$service_image)}}" alt="{{$service->title}}">
    </div>
    <div class="row service_page_text">
        {!! $service -> text !!}
    </div>

    @if(isset($other_services))
    <div class="row">
        <h2 class="other_servces">@lang('shop.service page other services')</h2>
        <hr>
        @foreach ($other_services as $service)
        <div class="col-md-3 col-sm-6">
            <div class="service-box ">
                <div class="service-icon red services_bacground_image" style="background-image: url({{ asset('images/service_img/'.$service['image']) }});">
                    <div class="front-content">
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
    </div>
    @endif

</div>
@endsection