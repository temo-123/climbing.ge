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

@if(isset($meta_img))
@section('meta_img', (asset('assets/img/meta_img/'.$meta_img)))
@else
@section('meta_img', (asset('assets/img/site_logo/x.png')))
@endif

<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css"/>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->

<div class="container top_menu_margin h-recent-work">

    <section class="portfolio" id="portfolio">
        <div class="container-fluid">
            <div class="row">

                @if(isset($title))
                <div class="artile_list_title">
                    <h1>{{$title}}</h1>
                </div>
                @endif
                @if($article_count != 0) 
                @if(isset($outdoor_climbing))
                    @if($site->outdoor_description != NULL)
                    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                    <h6>{{$site -> outdoor_description}}</h6>
                    @endif
                @elseif(isset($mount))
                    @if($site->mount_description != NULL)
                    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                    <h6>{{$site -> mount_description}}</h6>
                    @endif
                @elseif(isset($ice))
                    @if($site->ice_description != NULL)
                    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                    <h6>{{$site -> ice_description}}</h6>
                    @endif
                @elseif(isset($other))
                    @if($site->other_activity_description != NULL)
                    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                    <h6>{{$site -> other_activity_description}}</h6>
                    @endif
                @endif
                @endif

                @if(isset($tags) && $tags_count != NULL)
                <div align="center">
                    <button class="filter-button" data-filter="all">All</button>
                    @foreach($tags as $k=>$tag)
                    <button class="filter-button" data-filter="{!! $tag->id !!}">{!! $tag->title !!}</button>
                    @endforeach
                </div>
                <hr>
                @endif

                @if (isset($article_list) && is_object($article_list) )
                @forelse($article_list as $k=>$article)

                @if(isset($tags))
                <div class="gallery_product filter {{ $article -> mount_id }}">
                    @endif

                    <!--<div class="food col-md-4" style='horizontal-align: bottom;'>-->
                        <div class="food col-md-4" style='horizontal-align: bottom;'>
                            <div class="portfolio-img view view-first product-image">

                                @if(isset($time_array))
                                @foreach($time_array as $time)
                                @if($time['id'] == $article -> id)
                                @if($time['time'] == 1)
                                <div class="discount-percent-badge discount-badge-fourty">NEW</div>
                                @endif
                                @endif
                                @endforeach
                                @endif

                                @if(isset($image_dir) && $article->image != null)
                                <img src="{{asset('images/'.$image_dir.'/'.$article->image)}}" class="img-responsive">
                                @else
                                <img src="https://via.placeholder.com/150x100" class="img-responsive">
                                @endif

                                @if(isset($articles_link))
                                <div class="mask">
                                    <a href="{{ route($articles_link, array('title'=>$article->url_title)) }}" class="info">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                                @else
                                <div class="mask">
                                    <a href="{{ route('index') }}" class="info">
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                                @endif

                                <div class="isotope-item-dtls">
                                    <h3>{{ $article -> title }}</h3>
                                    <div class="article_size">
                                        {!! $article -> short_description !!}

                                        @if(isset($route_quantity))
                                        @foreach($route_quantity as $route_num)
                                        @if ($article -> title == $route_num[0])
                                        {{$route_num[1]}}
                                        {{$route_num[2]}}.
                                        {{$route_num[3]}}
                                        {{$route_num[4]}}.
                                        <div class="clearfix"></div>
                                        @if ($route_num[5] > 0)
                                        Multi-pitch {{$route_num[5]}}.
                                        @endif
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div><!--/isotope-item-dtls-->

                            </div>
                        </div>

                        @if(isset($tags))
                    </div>
                    @endif

                    @if( (($num++ % 3) == 0) )
                    <div class="clearfix"></div>
                    @endif

                    @empty

                    @if(isset($article_count) && $article_count == 0) 
                    <!-- <h6 class="text-center"> -->
                    <!-- </h6> -->
                    @if(isset($outdoor_climbing))
                        @if($site->outdoor_emprty != NULL)
                        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                        <h6>{{$site->outdoor_emprty}} </h6>
                        @endif
                    @elseif(isset($mount))
                        @if($site->mount_empty != NULL)
                        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                        <h6>{{$site->mount_empty}} </h6>
                        @endif
                    @elseif(isset($ice))
                        @if($site->ice_empty != NULL)
                        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                        <h6>{{$site->ice_empty}} </h6>
                        @endif
                    @elseif(isset($other))
                        @if($site->other_actyvity_empty != NULL)
                        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                        <h6>{{$site->other_actyvity_empty}} </h6>
                        @endif
                    @endif
                    @endif

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
                    <p style="text-align: center; ">@if(isset($empty_page_text)) {{$empty_page_text}} @else @endif</p>
                    @endforelse

                    @endif

                </div>
            </div>
        </section>

    </div>
