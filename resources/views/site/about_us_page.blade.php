@extends('site.layouts.app')

@section('content')

@section('title', 'About us')
    
    @section('meta_title', 'About climbing in Georgia')
    @section('meta_keyword', 'Climbing in Georgia')
    @section('meta_description', 'Rock climbing in Georgia is a developing sport, but the country has a great prospect in rock climbing and in mountaineering. There are many ready-made regions for all kinds of climbing.')
    @section('meta_img', (asset('assets/img/meta_img/outdoor.jpg')))

<div class="container top_menu_margin">
    <!--About text-->
    <div class="aboutus-section">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="about_us_page_image">
                        @foreach($gallery_images as $image)
                            <img src="{{ asset('images/gallery_img/'.$image -> image) }}" alt="" style="rounded mx-auto d-block">
                        @endforeach
                    </div>
                </div>
            </div> --}}
                
            @if (isset($site) && is_object($site) )	
            <div class="row">
                
                <!--Description and links-->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="aboutus">
                        
                        @if($page_locale == "ru")
                            {!! $site -> text_ru !!}
                        @elseif($page_locale == "ka")
                            {!! $site -> text_ka !!}
                        @else
                            {!! $site -> text !!}
                        @endif

                        {{-- <div class="about-icons"> 
                            <ul>            
                                @if(($site->fb_link) != NULL)
                                    <li>
                                        <a target="_blank" href="{{$site -> fb_link}}">
                                            <i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
                                        </a> 
                                    </li>   
                                @endif
                                @if(($site->twit_link) != NULL)
                                    <li>
                                        <a target="_blank" href="{{$site -> twit_link}}">
                                            <i id="social-tw" class="fa fa-twitter-square fa-3x social"></i>
                                        </a> 
                                    </li>   
                                @endif
                                @if(($site->google_link) != NULL)
                                    <li> 
                                        <a target="_blank" href="{{$site -> google_link}}">
                                            <i id="social-gp" class="fa fa-google-plus-square fa-3x social"></i>
                                        </a> 
                                    </li>   
                                @endif
                                @if(($site->inst_link) != NULL)
                                    <li> 
                                        <a target="_blank" href="{{$site -> inst_link}}">
                                            <i id="social-em" class="fa fa-instagram fa-3x social"></i>
                                        </a> 
                                    </li>   
                                @endif
                            </ul>
                        </div> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <hr>
                        <div class="about_us_page_shop_section">
                            <a href="{{ route('shop_index') }}">
                                <img src="{{ asset('images/site_img/site_logo/climibng,ge shop (becground).jpg') }}" alt="climbing.ge shop" style="rounded mx-auto d-block">
                            </a>
                            
                            <a href="{{ route('shop_index') }}">
                                <h2><strong>climbing.ge shop</strong></h2>
                            </a>
                            {!! $site->shop_short_description !!}
                        </div>
                    </div>
                </div>

                <search-component></search-component>
                
            </div>
                    
            {{-- <div class="clearfix"></div> --}}
                    
			@endif    
				
        </div>
    </div>
    
    <!--Message-->
    
    <message-component
        form_title = @lang('site.message form')
    ></message-component>

</div>

@endsection       
