@extends('site.layouts.app')

@section('content')
@section('title', 'Climbing in Georgia')
    @section('meta_keyword', 'Climbing in Georgia')
    @section('meta_description', 'Climbing, mountaineering, ice and mixed climbing in Georgia')
    @section('meta_img', asset('assets/img/meta_img/indoor.jpg'))

    @if (isset($head_slider) && is_object($head_slider) )
    <div id="mycarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">

        @forelse($head_slider as $k=>$head)

        <li data-target="#mycarousel" data-slide-to="{{$head_slider_num++}}" class="active"></li> 

        @empty

        <h2 class="text-center">No text</h2>

        @endforelse

    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        @forelse($head_slider as $head)
        <div class="item">

            <!-- <style>.r{becground-img:g;}</style> -->

            <img src="{{ asset('images/gallery_img/'.$head -> image) }}" data-color="lightblue" alt="{{ $head -> title }}">
            <img src="{{ asset('images/gallery_img/'.$head -> image) }}" data-color="lightblue" alt="{{ $head -> title }}">
            <div class="carousel-caption">
                <h2 class='stroke animated bounceInDown delay-1s' style='font-size: 450%;'>
                    <a href="{{ $head -> link }}">{{ $head -> title }}</a>
                </h2>
                <p>{{$head -> text}}</p>
                <h3 class='stroke animated bounceInUp delay-1s' style='font-size: 150%;'>Climbing.ge</h3>
            </div>
        </div>
        @empty
        <h2 class="text-center">No info</h2>
        @endforelse
    </div>

    <a class="left carousel-control" href="#mycarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left animated bounceInLeft" aria-hidden="true"></span>
    </a>
    <a class="right carousel-control" href="#mycarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right animated bounceInRight" aria-hidden="true"></span>
    </a>

</div>
@endif

@if(isset($news) && isset($big_news))
@if(count($news) >= 6)
<div class="h-recent-work">
    <div class="container">
        <h2 class='index_h2' id="news">@lang('site.title news')</h2>
        @if($site -> news_description != NULL)
        <div class="bar"><i class="fa fa-newspaper-o"></i></div>
        <h6>{{$site -> news_description}}</h6>
        @endif

        @foreach($big_news as $big_new)
        <section class="m-component-stack mm-component-stack--is-stacked">
            <div class="m-image-hero mm-image-hero--ssr">
                <div href="/skills/the-top-5-rules-for-effective-goal-setting/">
                    <div class="m-image-hero--media">
                        <div>
                            <div class="is-loaded">
                                <source type="image/webp" srcset="{{ asset('images/news_img/'.$big_new[0][0]->image) }}">
                                <source srcset="{{ asset('images/news_img/'.$big_new[0][0]->image) }}">
                                <img class="m-image-hero--image" alt="{{$big_new[0][0] -> title}}" decoding="async" src="{{ asset('images/news_img/'.$big_new[0][0]->image) }}" sizes="(min-width: 1023px) 940px, 100vw">
                            </div>
                            <div class="m-image-hero--aspect-ratio-wrapper">
                                <div class="m-image-hero--aspect-ratio-pusher"></div>
                            </div>
                        </div>
                        <div class="m-image-hero--overlay"></div>
                        <div class="m-image-hero--container">
                            <div class="m-image-hero--label mm-image-hero--no-background">
                                <div class="m-image-hero--metadata-label">
                                    <a href="{{ route('news_page', array('title'=>$big_new['url_title'])) }}" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Label">News</a>
                                </div>
                            </div>
                            <div class="m-image-hero--text-panel">
                                <a href="{{ route('news_page', array('title'=>$big_new['url_title'])) }}" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Title">
                                    <div>
                                        <h2 class="m-ellipsis m-image-hero--header-text mm-image-hero--no-background">
                                            <span class="m-ellipsis--text">{{$big_new[0][0] -> title}}</span>
                                        </h2>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endforeach



        <section class="m-collection-strip mm-collection-strip--is-stretched m-component-stack mm-component-stack--is-stacked mm-component-stack--has-header">
            <div class="m-collection-strip--carousel">
                <div class="m-collection-strip--carousel-wrapper">
                    <div class="m-collection-strip--carousel-container">
                        <div class="m-collection-strip--carousel-content">


                            @foreach($news as $new)
                            <div class="m-collection-strip--tile" slide-id="ci0258d58230002521" data-pos="183">
                                <div href="/competition/russia-banned-from-2020-tokyo-olympics/">
                                    <article class="m-card mm-card--landscape-image mm-card--type-standard mm-card--density-strip" role="article">
                                        <div class="m-card--media">
                                            <a class="m-card--image-link" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Image" href="{{ route('news_page', array('title'=>$new['url_title'])) }}">
                                                <div>
                                                    <picture class="is-loaded">
                                                        <source type="image/webp" sizes="320px" srcset="{{ asset('images/news_img/'.$new[0][0] -> image) }}">
                                                        <source sizes="320px" srcset="{{ asset('images/news_img/'.$new[0][0] -> image) }}">
                                                        <img class="m-card--image-element" decoding="async" nopin="true" sizes="320px" src="{{ asset('images/news_img/'.$new[0][0] -> image) }}" srcset="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                                                        alt="{{ route('news_page', array('title'=>$new['url_title'])) }}">
                                                    </picture>
                                                    <div class="m-card--image-aspect-ratio-pusher"></div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="m-card--content">
                                            <div class="m-ellipsis m-card--header">
                                                <a onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Title" href="{{ route('news_page', array('title'=>$new['url_title'])) }}">
                                                    <h3 class="m-ellipsis--text m-card--header-text" role="heading" aria-level="2">{{$new[0][0] -> title}}</h3>
                                                </a>
                                            </div>
                                            <div class="m-ellipsis">
                                                <p class="m-card--body m-ellipsis--text">{{$new[0][0] -> short_description}}</p>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>    
                            @endforeach                 
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>
</div>
@endif
@endif















                @if($site->map != NULL)
                <div class="container h-recent-work">

                    <h2 class='index_h2'>@lang('site.title topo')</h2>
                    @if($site->topo_description != NULL)
                    <div class="bar"><i class="fa fa-map-marker"></i></div>

                    
                    <h6>{!! $site -> topo_description !!}</h6>
                    
                    @endif

                    <div style='margin-bottom:7%;'>
                        {!!$site->map!!}
                    </div>
                </div>
                @endif


                @if(
                    $site -> indoor_description != NULL && $site -> outdoor_description != NULL && $site -> ice_description != NULL && $site -> mount_description != NULL
                    || $site -> indoor_description_ru != NULL && $site -> outdoor_description_ru != NULL && $site -> ice_description_ru != NULL && $site -> mount_description_ru != NULL
                    || $site -> indoor_description_ka != NULL && $site -> outdoor_description_ka != NULL && $site -> ice_description_ka != NULL && $site -> mount_description_ka != NULL
                )
                <div class="services">
                    <div class="container">

                        <h2 class='index_h2'>@lang('site.title what we do')</h2>

                        <div class="bar"><i class="fa fa-book"></i></div>
                        <div class="row">


                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <ul class="right-text">
                                    <li>
                                        <h4>
                                            <a href="{{ $site -> activity_link_1 }}">
                                                <strong>@lang('site.title outdoor climbing')</strong>
                                                <!-- <i class="fa fa-tablet"></i> -->
                                                <img alt='outdoor climbing image' class="index_category_right" src="{{ asset('images/site_img/logo/outdoor.png') }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> outdoor_description!!}
                                        </div>
                                    </li>
                                    <div class="clearfix"></div>
                                    <li>
                                        <h4>
                                            <a href="{{ $site -> activity_link_2 }}">
                                                <strong>@lang('site.title indoor climbing')</strong>
                                                <!-- <i class="fa fa-file-code-o"></i> -->
                                                <img alt='indoor climbing image' class="index_category_right" src="{{ asset('images/site_img/logo/indoor.png') }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> indoor_description!!}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <ul class="left-text">
                                    <li>
                                        <h4>
                                            <a href="{{ $site -> activity_link_3 }}">
                                                <strong>@lang('site.title ice climbing')</strong>
                                                <!-- <i class="fa fa-eye"></i> -->
                                                {{-- <img alt='ice climbing image' class="index_category_left" src="{{ asset('images/site_img/logo/ice.png') }}"> --}}
                                                <img alt='indoor climbing image' class="index_category_left" src="{{ asset('images/site_img/logo/indoor.png') }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> ice_description!!}
                                        </div>
                                    </li>
                                    <div class="clearfix"></div>
                                    <li>
                                        <h4>
                                            <a href="$site -> activity_link_4">
                                                <strong>@lang('site.title mountaineering')</strong>
                                                <!-- <i class="fa fa-fire"></i> -->
                                                <img alt='mountaineering image' class="index_category_left" src="{{ asset('images/site_img/logo/mount.png') }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> mount_description!!}
                                        </div>
                                    </li>
                                </ul>
                            </div>


                        </div>
                    </div><!-- /container -->
                </div><!-- /services -->
                @endif


                @if(isset($index_gallery) && count($index_gallery) != 0)
                <div class="h-recent-work services">
                    <div class="container">

                        <h2 class='index_h2'>@lang('site.title gallery')</h2>

                        @if($site->index_gallery_description != NULL)
                        <div class="bar" style="margin-bottom: 5%;"><i class="fa fa-picture-o" aria-hidden="true"></i></div>
                        <h6>{{$site -> index_gallery_description}}</h6>
                        @endif

                        <section class="portfolio" id="portfolio">
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="container">
                                        <div class="filter_button_block" >
                                            <button class="filter-button" data-filter="all">All</button>
                                        </div>
                                        <div class="filter_button_block" >
                                        @if(isset($tags))
                                        @foreach($tags as $k=>$tag)

                                        <button class="filter-button" data-filter="{!! $tag->filter !!}">{!! $tag->filter !!}</button>

                                        @endforeach
                                        @endif
                                        </div>
                                    </div>
                                </div>

                                    
                                    @forelse($index_gallery as $k=>$gallery)

                                    <div class="filter xz_filtr thumb" >
                                    <div class='clearfix_big'>
                                        @if(($gallery -> id % 4) == 0)
                                        <div class="clearfix"></div>
                                        @endif
                                    </div>
                                    <div class='clearfix_mini'>
                                        @if(($gallery -> id % 2) == 0)
                                        <div class="clearfix"></div>
                                        @endif 
                                    </div>
                                    </div>

                                    <div class="gallery_product col-sm-3 col-xs-6 filter {!! $gallery -> filter !!} thumb" >
                                        <a class="thumbnail" href='#' data-image-id="" data-toggle="modal" data-toggle="modal" data-target="#squarespaceModal{{$gallery -> id}}">

                                            <img src="{{ asset('images/gallery_img/'.$gallery -> image) }}" alt="Another alt text">

                                            <div class="size">
                                                <a data-toggle="modal" data-target="#squarespaceModal{{$gallery -> id}}">{{ $gallery -> title }}</a>
                                            </div>
                                        </a>
                                    </div>       
                                    @empty
                                    <h2 class="text-center">No text</h2>
                                    @endforelse
                                </div>
                        </section>
                    </div>
                </div>
                @endif


                @if(isset($index_gallery))
                @foreach($index_gallery as $k=>$gallery)
                <div class="modal fade" id="squarespaceModal{{$gallery->id}}" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

                    @if(($gallery -> text) != NULL)
                    <div class="modal-dialog modal-lg galery_page_big">
                        @else
                        <div class="modal-dialog modal-lg galery_page_smal">
                            @endif

                            <div class="modal-content">
                               <div class="modal-header">
                                <h2 class="modal-title" id="image-gallery-title">{{ $gallery -> title }}</h2>
                                <button type="button" class="close" data-dismiss="modal" style='margin-top: -29px; font-size: 41px;'>
                                   <i class="fa fa-close" aria-hidden="true"></i>
                               </button>

                               @auth
                            @if(Auth::user()->hasRole('admin'))
                            <ul class="nav navbar-nav pull-right blog-sidebar display-none-720px">
                                <li class="dropdown" style='margin-top: -35px; font-size: 31px; float: right; margin-right: 1%;'>

                                    <a href="#" class="dropdown-toggle margin_right"  data-toggle="dropdown">
                                        <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
                                    </a>

                                    <ul class="dropdown-menu shadows" role="menu">
                                        <li><a href="#">All galeri list</a></li>
                                        <hr>
                                        <li>
                                            <a href="#">Edit</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            @endif
                            @endauth
                        </div>
                        @if(($gallery -> text) != NULL)
                        <div class="container">
                            <div class="row">
                                <div class='col-md-8'>
                                    <img class='col-xs-12' src="{{ asset('images/gallery_img/'.$gallery -> image) }}" alt="Another alt text">
                                </div>
                                <div class='col-md-4 galery_open_text'>
                                    <p>{!! $gallery -> text !!}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="container">
                            <div class="row">
                                <img class="col-xs-12" src="{{ asset('images/gallery_img/'.$gallery -> image) }}" alt="Another alt text">
                            </div>
                        </div>
                        @endif
                        <div class="modal-footer">

                            @if (Route::has('login'))
                            @auth 
                            @if(($gallery -> link) != NULL)
                            <button type="button" id="image-gallery-text" class="btn btn-secondary float-left"  style="float: left;">More</button>
                            @endif
                            @endauth
                            @endif

                            <button type="button" id="show-previous-image" class="btn btn-secondary float-left">
                                <i class="fa fa-arrow-left"></i>
                            </button>

                            <button type="button" id="show-next-image" class="btn btn-secondary float-right">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif


            {{-- @if((isset($securities)))
            <div class="h-recent-work services" id="services">
                <div class="container">

                    <h2 class='index_h2'>@lang('site.title tech tips')</h2>

                    @if($site->security_description != NULL)
                    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                    <h6>{{$site -> security_description}}</h6>
                    @endif

                    <trchtips_component></trchtips_component>
                    
                </div>
            </div>
            @endif --}}


            @if (isset($index_others) && count($index_others) != 0)
            <div class="h-recent-work" id="other">
                <div class="container">

                    <h2 class='index_h2'>@lang('site.title other')</h2>

                    @if($site->other_activity_description != NULL)
                    <div class="bar"><i class="fa fa-dribbble"></i></div>
                    <h6>{{$site -> other_activity_description}}</h6>
                    @endif

                    @forelse($index_others as $others)
                    <div class="food col-md-4">
                        <div class="portfolio-img view view-first">

                            <img src="{{ asset('images/other_img/'.$others['image'] )}}"class="img-responsive" alt='{{ $others[0][0] -> title }}' />

                            <div class="mask">
                                <a href="{{ route('other_page', array('title'=>$others['url_title'])) }}" class="info"><i class="fa fa-arrow-right"></i></a>
                            </div>
                            
                            <div class="isotope-item-dtls">
                                <h3>{{ $others[0][0] -> title }}</h3>
                                <div class="article_size">
                                    <p>{!! $others[0][0] -> short_description !!}</p>
                                </div>
                            </div><!--/isotope-item-dtls-->
                        </div>
                    </div>     
                    @empty
                    <h2 class="text-center">No text</h2>
                    @endforelse
                </div>  
            </div>
            @endif 


            @if (isset($events) && count($events) != 0)
            <div class="container h-recent-work events" id="event">
                <div class="row">
                    <div class="container">

                        <h2 class='index_h2'>@lang('site.title events')</h2>

                        @if($site->other_activity_description != NULL)
                        <div class="bar"><i class="fa fa-calendar"></i></div>
                        <h6>{{$site -> event_description}}</h6>
                        @endif

                        <div class="container">
                            <div class="row event-list">
                                <!-- @--if (isset($events) && is_object($events) ) -->
                                @foreach($events as $event)
                                <div class="col-sm-4 eav">
                                    <!-- <div class="panel panel-primary event-primary"> -->
                                        <div class="panel-primary event-primary">
                                            <div class="panel-heading">
                                                <h2>
                                                    <a href="{{ route('events_page', array('title'=>$event['url_title'])) }}">
                                                        {{ $event[0][0] -> title }}
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="panel-body nopadding">
                                                <img src="{{ asset('images/event_img/'.$event['image'] )}}"class="img-responsive" alt='{{ $event[0][0] -> title }}' />
                                                <div class="row nopadding">
                                                    <div class="col-sm-6 col-xs-6 nopadding">
                                                        <time class="end blue_green_2">
                                                            Start 
                                                            <span class="day">{{ $event['start_data_day']  }}</span>
                                                            <span class="month">{{ $event['start_data_month']  }}</span>
                                                            <!-- <span class="year">2016</span> -->
                                                        </time>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopadding">
                                                        <time class="end blue_green_2">
                                                            End 
                                                            <span class="day">{{ $event['and_data_day']  }}</span>
                                                            <span class ="month">{{ $event['and_data_month']  }}</span>
                                                            <!-- <span class="year">2016</span> -->
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer panel-primary">

                                                <div class="event_size">
                                                    {!! $event[0][0] -> short_description !!}
                                                </div>
                                                
                                                <a href="{{ route('events_page', array('title'=>$event['url_title'])) }}" class="btn btn-success">
                                                    Read more...
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif

@endsection