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
        @forelse($head_slider as $k=>$head)
        <div class="item">

            <!-- <style>.r{becground-img:g;}</style> -->

            <img src="{{ asset('images/gallery_img/index_header_img/'.$head -> image) }}" data-color="lightblue" alt="{{ $head -> title }}">
            <img src="{{ asset('images/gallery_img/index_header_img/'.$head -> image) }}" data-color="lightblue" alt="{{ $head -> title }}">
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
@if($big_news_count > 6 || $big_news_count = 6)
<div class="h-recent-work">
    <div class="container">
        <h2 class='index_h2' id="news">News</h2>
        @if($site -> news_description != NULL)
        <div class="bar"><i class="fa fa-newspaper-o"></i></div>
        <h6>{{$site -> news_description}}</h6>
        @endif

        @foreach($big_news as $big_new)
        <section class="m-component-stack mm-component-stack--is-stacked">
            <div class="m-image-hero mm-image-hero--ssr">
                <phoenix-super-link href="/skills/the-top-5-rules-for-effective-goal-setting/">
                    <div class="m-image-hero--media">
                        <phoenix-picture>
                            <picture class="is-loaded">
                                <source type="image/webp" srcset="{{ asset('images/news_img/'.$big_new->image) }}">
                                <source srcset="{{ asset('images/news_img/'.$big_new->image) }}">
                                <img class="m-image-hero--image" alt="{{$big_new -> title}}" decoding="async" src="{{ asset('images/news_img/'.$big_new->image) }}" sizes="(min-width: 1023px) 940px, 100vw">
                            </picture>
                            <div class="m-image-hero--aspect-ratio-wrapper">
                                <div class="m-image-hero--aspect-ratio-pusher"></div>
                            </div>
                        </phoenix-picture>
                        <div class="m-image-hero--overlay"></div>
                        <div class="m-image-hero--container">
                            <div class="m-image-hero--label mm-image-hero--no-background">
                                <div class="m-image-hero--metadata-label">
                                    <a href="{{ route('news_page', array('title'=>$big_new->url_title)) }}" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Label">Skills</a>
                                </div>
                            </div>
                            <div class="m-image-hero--text-panel">
                                <a href="{{ route('news_page', array('title'=>$big_new->url_title)) }}" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Title">
                                    <phoenix-ellipsis>
                                        <h2 class="m-ellipsis m-image-hero--header-text mm-image-hero--no-background">
                                            <span class="m-ellipsis--text">{{$big_new -> title}}</span>
                                        </h2>
                                    </phoenix-ellipsis>
                                </a>
                            </div>
                        </div>
                    </div>
                </phoenix-super-link>
            </div>
        </section>
        @endforeach



        <section class="m-collection-strip mm-collection-strip--is-stretched m-component-stack mm-component-stack--is-stacked mm-component-stack--has-header">
            <phoenix-carousel class="m-collection-strip--carousel">
                <phoenix-carousel-strip class="m-collection-strip--carousel-wrapper">
                    <div class="m-collection-strip--carousel-container">
                        <div class="m-collection-strip--carousel-content">


                            @foreach($news as $k=>$new)
                            <phoenix-carousel-slide class="m-collection-strip--tile" slide-id="ci0258d58230002521" data-pos="183">
                                <phoenix-super-link href="/competition/russia-banned-from-2020-tokyo-olympics/">
                                    <article class="m-card mm-card--landscape-image mm-card--type-standard mm-card--density-strip" role="article">
                                        <div class="m-card--media">
                                            <a class="m-card--image-link" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Image" href="{{ route('news_page', array('title'=>$new->url_title)) }}">
                                                <phoenix-picture>
                                                    <picture class="is-loaded">
                                                        <source type="image/webp" sizes="320px" srcset="{{ asset('images/news_img/'.$new -> image) }}">
                                                        <source sizes="320px" srcset="{{ asset('images/news_img/'.$new -> image) }}">
                                                        <img class="m-card--image-element" decoding="async" nopin="true" sizes="320px" src="{{ asset('images/news_img/'.$new -> image) }}" srcset="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                                                        alt="{{ route('news_page', array('title'=>$new->url_title)) }}">
                                                    </picture>
                                                    <div class="m-card--image-aspect-ratio-pusher"></div>
                                                </phoenix-picture>
                                            </a>
                                        </div>
                                        <div class="m-card--content">
                                            <phoenix-ellipsis class="m-ellipsis m-card--header">
                                                <a onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Title" href="{{ route('news_page', array('title'=>$new->url_title)) }}">
                                                    <h3 class="m-ellipsis--text m-card--header-text" role="heading" aria-level="2">{{$new -> title}}</h3>
                                                </a>
                                            </phoenix-ellipsis>
                                            <phoenix-ellipsis class="m-ellipsis">
                                                <p class="m-card--body m-ellipsis--text">{{$new -> short_description}}</p>
                                            </phoenix-ellipsis>
                                        </div>
                                    </article>
                                </phoenix-super-link>
                            </phoenix-carousel-slide>    
                            @endforeach                 
                        </div>
                    </div>

                </phoenix-carousel-strip>
            </phoenix-carousel>
        </section>

    </div>
</div>
@endif
@endif















                @if($site->map != NULL)
                <div class="container h-recent-work">

                    <h2 class='index_h2'>Topo</h2>
                    @if($site->topo_description != NULL)
                    <div class="bar"><i class="fa fa-map-marker"></i></div>
                    <h6>{{$site -> topo_description}}</h6>
                    @endif

                    <div style='margin-bottom:7%;'>
                        {!!$site->map!!}
                    </div>
                </div>
                @endif


                @if($site -> activity_title_1 != NULL && $site -> activity_title_2 != NULL && $site -> activity_title_3 != NULL && $site -> activity_title_3 != NULL)
                <div class="services">
                    <div class="container">

                        <h2 class='index_h2'>What We Do</h2>

                        <div class="bar"><i class="fa fa-book"></i></div>
                        <div class="row">


                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <ul class="right-text">
                                    <li>
                                        <h4>
                                            <a href="{{ $site -> activity_link_1 }}">
                                                <strong>{!! $site -> activity_title_1!!}</strong>
                                                <!-- <i class="fa fa-tablet"></i> -->
                                                <img alt='{!! $site -> activity_title_1 !!}' class="index_category_right" src="{{ asset('images/site_img/logo/'.$site -> activity_img_1) }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> activity_text_1!!}
                                        </div>
                                    </li>
                                    <li>
                                        <h4>
                                            <a href="{{ $site -> activity_link_2 }}">
                                                <strong>{!! $site -> activity_title_2!!}</strong>
                                                <!-- <i class="fa fa-file-code-o"></i> -->
                                                <img alt='{!! $site -> activity_title_2 !!}' class="index_category_right" src="{{ asset('images/site_img/logo/'.$site -> activity_img_2) }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> activity_text_2!!}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <ul class="left-text">
                                    <li>
                                        <h4>
                                            <a href="{{ $site -> activity_link_3 }}">
                                                <strong>{!! $site -> activity_title_3!!}</strong>
                                                <!-- <i class="fa fa-eye"></i> -->
                                                <img alt='{!! $site -> activity_title_3 !!}' class="index_category_left" src="{{ asset('images/site_img/logo/'.$site -> activity_img_3) }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> activity_text_3!!}
                                        </div>
                                    </li>
                                    <li>
                                        <h4>
                                            <a href="{{ $site -> activity_link_4 }}">
                                                <strong>{!! $site -> activity_title_4!!}</strong>
                                                <!-- <i class="fa fa-fire"></i> -->
                                                <img alt='{!! $site -> activity_title_4 !!}' class="index_category_left" src="{{ asset('images/site_img/logo/'.$site -> activity_img_4) }}">
                                            </a>
                                        </h4>

                                        <div class="content-text">
                                            {!! $site -> activity_text_4!!}
                                        </div>
                                    </li>
                                </ul>
                            </div>


                        </div>
                    </div><!-- /container -->
                </div><!-- /services -->
                @endif


                @if(isset($gallerys) && count($gallerys) != 0)
                <div class="h-recent-work services">
                    <div class="container">

                        <h2 class='index_h2'>Gallery</h2>

                        @if($site->index_gallery_description != NULL)
                        <div class="bar" style="margin-bottom: 5%;"><i class="fa fa-picture-o" aria-hidden="true"></i></div>
                        <h6>{{$site -> index_gallery_description}}</h6>
                        @endif

                        <section class="portfolio" id="portfolio">
                            <div class="container-fluid">
                                <div class="row">

                                    <div class="index_gallery_button">
                                        <button class="filter-button" data-filter="all">All</button>

                                        @if(isset($tags))
                                        @foreach($tags as $k=>$tag)
                                        @if (($tag->published) == 1)

                                        <button class="filter-button" data-filter="{!! $tag->filter !!}">{!! $tag->filter !!}</button>

                                        @endif
                                        @endforeach
                                        @endif

                                    </div>
                                    @forelse($gallerys as $k=>$gallery)

                                    <div class="filter xz_filtr thumb" >
                                    <div class='clearfix_big'>
                                        @if( (($num++ % 4) == 0) )
                                        <div class="clearfix"></div>
                                        @endif
                                    </div>
                                    <div class='clearfix_mini'>
                                        @if( (($num1++ % 2) == 0) )
                                        <div class="clearfix"></div>
                                        @endif 
                                    </div>
                                    </div>

                                    <div class="gallery_product col-sm-3 col-xs-6 filter {!! $gallery -> filter !!} thumb" >
                                        <a class="thumbnail" href='#' data-image-id="" data-toggle="modal" data-toggle="modal" data-target="#squarespaceModal{{$num2++}}">

                                            <img src="{{ asset('images/gallery_img/index_gallery_img/'.$gallery -> image) }}" alt="Another alt text">

                                            <div class="size">
                                                <a data-toggle="modal" data-target="#squarespaceModal{{$num2}}">{{ $gallery -> title }}</a>
                                            </div>
                                        </a>
                                    </div>       
                                    @empty
                                    <h2 class="text-center">No text</h2>
                                    @endforelse
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                @endif


                @if(isset($gallerys) && count($gallerys) != 0)
                @foreach($gallerys as $k=>$gallery)
                <div class="modal fade" id="squarespaceModal{{$num3++}}" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

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
                                    <img class='col-xs-12' src="{{ asset('images/gallery_img/index_gallery_img/'.$gallery -> image) }}" alt="Another alt text">
                                </div>
                                <div class='col-md-4 galery_open_text'>
                                    <p>{!! $gallery -> text !!}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="container">
                            <div class="row">
                                <img class="col-xs-12" src="{{ asset('images/gallery_img/index_gallery_img/'.$gallery -> image) }}" alt="Another alt text">
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


            @if((isset($securities))&&((\App\Article::latest('id')->where('category', '=', 'security')->where('published', '=', 1)->count())!=0))
            <div class="h-recent-work services" id="services">
                <div class="container">

                    <h2 class='index_h2'>Tech tips</h2>

                    @if($site->security_description != NULL)
                    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                    <h6>{{$site -> security_description}}</h6>
                    @endif

                    <div id="carousel3d">
                        <carousel-3d :perspective="0" :space="200" :display="5" :controls-visible="true" :controls-prev-html="'❬'" :controls-next-html="'❭'" :controls-width="30" :controls-height="60" :clickable="true" :autoplay="true" :autoplay-timeout="5000">
                            @foreach($securities as $security)
                            <slide :index="{{$actyvity_num++}}">
                                <a href="{{ route('security_page', array('title'=>$security->url_title)) }}">
                                    <img src="{{ asset('images/security_img/'.$security->image) }}" alt="{!! $security -> title !!}">
                                </a>
                                <a href="{{ route('security_page', array('title'=>$security->url_title)) }}">{{$security->title}}</a>
                            </slide>
                            @endforeach
                        </carousel-3d>
                    </div>

                </div>
            </div>
            @endif


            @if (isset($index_others) && count($index_others) != 0)
            <div class="h-recent-work" id="other">
                <div class="container">

                    <h2 class='index_h2'>Other Activity</h2>

                    @if($site->other_activity_description != NULL)
                    <div class="bar"><i class="fa fa-dribbble"></i></div>
                    <h6>{{$site -> other_activity_description}}</h6>
                    @endif

                    @forelse($index_others as $k=>$other)
                    <div class="food col-md-4">
                        <div class="portfolio-img view view-first">

                            <img src="{{ asset('images/other_img/'.$other -> image )}}"class="img-responsive" alt='{{ $other -> title }}' />

                            <div class="mask">
                                <a href="{{ route('other_page', array('title'=>$other->url_title)) }}" class="info"><i class="fa fa-arrow-right"></i></a>
                            </div>
                            
                            <div class="isotope-item-dtls">
                                <h3>{{ $other -> title }}</h3>
                                <div class="article_size">
                                    <p>{!! $other -> short_description !!}</p>
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


            @if (isset($events) && is_object($events) && count($events) != 0)
            <div class="container h-recent-work events" id="event">
                <div class="row">
                    <div class="container">

                        <h2 class='index_h2'>Events</h2>

                        @if($site->other_activity_description != NULL)
                        <div class="bar"><i class="fa fa-calendar"></i></div>
                        <h6>{{$site -> event_description}}</h6>
                        @endif

                        <div class="container">
                            <div class="row event-list">
                                <!-- @--if (isset($events) && is_object($events) ) -->
                                @foreach($events as $k=>$event)
                                <div class="col-sm-4 eav">
                                    <!-- <div class="panel panel-primary event-primary"> -->
                                        <div class="panel-primary event-primary">
                                            <div class="panel-heading">
                                                <h2>
                                                    <a href="{{ route('events_page', array('title'=>$event->url_title)) }}">
                                                        {{ $event -> title }}
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="panel-body nopadding">
                                                <img src="{{ asset('images/events_img/'.$event -> image )}}"class="img-responsive" alt='{{ $event -> title }}' />
                                                <div class="row nopadding">
                                                    <div class="col-sm-6 col-xs-6 nopadding">
                                                        <time class="end blue_green_2">
                                                            Start 
                                                            <span class="day">{{ $event -> start_data_day }}</span>
                                                            <span class="month">{{ $event -> start_data_month }}</span>
                                                            <!-- <span class="year">2016</span> -->
                                                        </time>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 nopadding">
                                                        <time class="end blue_green_2">
                                                            End 
                                                            <span class="day">{{ $event -> and_data_day }}</span>
                                                            <span class ="month">{{ $event -> and_data_month }}</span>
                                                            <!-- <span class="year">2016</span> -->
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-footer panel-primary">

                                                <div class="event_size">
                                                    {!! $event -> short_description !!}
                                                </div>
                                                
                                                <a href="{{ route('events_page', array('title'=>$event->url_title)) }}" class="btn btn-success">
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
