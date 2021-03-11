@extends('shop.layouts.app')

@section('content')

@section('title', 'About us')
    
    @section('meta_title', 'About climbing.ge products')
    @section('meta_keyword', 'climbing.ge products')
    @section('meta_description', 'This product mean in Georgia by climbing.ge company.')
    @section('meta_img', (asset('assets/img/meta_img/outdoor.jpg')))

<div class="container top_menu_margin">
    
    <!--About text-->
    <div class="aboutus-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="aboutus">
                        
                        @if($page_locale == "ru")
                            {!! $site -> shop_description_ru !!}
                        @elseif($page_locale == "ka")
                            {!! $site -> shop_description_ka !!}
                        @else
                            {!! $site -> shop_description !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--Message-->
    
    <message-component
        form_title = @lang('site.message form')
    ></message-component>
</div>

@endsection       
