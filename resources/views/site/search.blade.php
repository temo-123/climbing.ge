@extends('site.layouts.app')

@section('content')

@section('title', 'Search')

@section('meta_title', 'Climbing in Georgia')
@section('meta_keyword', 'Climbing in Georgia')
@section('meta_description', '')
@section('meta_img', (asset('assets/img/meta_img/indoor.jpg')))

    <div class="container top_menu_margin">

        <h1  class='serch_title'>Search page</h1>

        <div class="row">
            <div class="col-md-12">

                <search-component></search_component>
                
                <p> The Search results for your query <b> {{$query}} </b> are :</p>
                
                @if(isset($outdoors))
                    @if(count($outdoors) != 0)
                    
                    <div class="clearfix"></div>
                        <div class="h-recent-work">
                            <div class="container">

                            <h2 class='serch_title'>Outdoor</h2>
                            <div class="bar"><i class="fa fa-dribbble"></i></div>
                            <h6>Outdoor in Georgia</h6>

                            @foreach($outdoors as $outdoor)
                            <div class="food col-md-4" style='horizontal-align: bottom;'>
                                <div class="portfolio-img view view-first">

                                    <img src="{{asset('images/outdoor_img/'.$outdoor->image)}}" class="img-responsive">
                                        
                                    <div class="mask">
                                        <a href="{{ route('outdoor_page', array('title'=>$outdoor->title)) }}" class="info">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="isotope-item-dtls">
                                        <h3>{{ $outdoor -> article_title }}</h3>
                                        <div class="article_size">
                                            {!! $outdoor -> description_short !!}
                                        </div>
                                    </div><!--/isotope-item-dtls-->
                                    
                                </div>
                            </div>
                            @if( (($num_1++ % 3) == 0) )
                            <div class="clearfix"></div>
                            @endif
                            @endforeach
                        </div>  
                    </div> 
                    @endif
                @endif    
                
                @if(isset($indoors))
                    @if(count($indoors) != 0)
                    <div class="clearfix"></div>
                    
                    <h2 class='serch_title'>Indoor</h2>
                    @endif
                    @foreach($indoors as $indoor)
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
                                        <input id="input-1" name="input-1" class="rating rating-loading" data-min="0" data-max="5" data-step="0.1" value="{{ $indoor -> star }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                
                @if(isset($mounts)&&$mounts != NULL)
                    @if(count($mounts) != 0)
                    
                    <div class="clearfix"></div>
                    
                    <div class="h-recent-work">
                        <div class="container">

                            <h2 class='serch_title'>Mountaineering</h2>
                            <div class="bar"><i class="fa fa-dribbble"></i></div>
                            <h6>Mountaineering in Georgia</h6>

                        @foreach($mounts as $mount)
                            <div class="food col-md-4" style='horizontal-align: bottom;'>
                                <div class="portfolio-img view view-first">
                                    
                                    <img src="{{asset('images/mount_route_img/'.$mount->image)}}" class="img-responsive">
                                        
                                    <div class="mask">
                                        <a href="{{ route('mount_page', array('title'=>$mount->title)) }}" class="info">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="isotope-item-dtls">
                                        <h3>{{ $mount -> name }}</h3>
                                        <div class="article_size">
                                            {!! $mount -> description_short !!}
                                        </div>
                                    </div><!--/isotope-item-dtls-->
                                    
                                </div>
                            </div>
                            
                            @if( (($num_2++ % 3) == 0) )
                            <div class="clearfix"></div>
                            @endif
                        @endforeach                        
                        </div>  
                    </div> 
                    @endif
                @endif    
                
                @if(isset($ices))
                    @if(count($ices) != 0)
                    <div class="clearfix"></div>
                    
                    <div class="h-recent-work">
                        <div class="container">

                        <h2 class='serch_title'>Ice & Mixed</h2>
                        <div class="bar"><i class="fa fa-dribbble"></i></div>
                        <h6>Ice & Mixed in Georgia</h6>

                        @foreach($ices as $ice)
                            <div class="food col-md-4" style='horizontal-align: bottom;'>
                                <div class="portfolio-img view view-first">
                                    
                                    <img src="{{asset('images/ice_img/'.$ice->image)}}" class="img-responsive">
                                        
                                    <div class="mask">
                                        <a href="{{ route('ice_page', array('title'=>$ice->title)) }}" class="info">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="isotope-item-dtls">
                                        <h3>{{ $ice -> article_title }}</h3>
                                        <div class="article_size">
                                            {!! $ice -> description_short !!}
                                        </div>
                                    </div><!--/isotope-item-dtls-->
                                    
                                </div>
                            </div>
                            @if( (($num_3++ % 3) == 0) )
                            <div class="clearfix"></div>
                            @endif
                            </div>  
                        </div> 
                        @endforeach
                    @endif
                @endif             
                
                @if(isset($others))
                    @if(count($others) != 0)
                    <div class="clearfix"></div>
                                
                    <div class="h-recent-work">
                        <div class="container">

                        <h2 class='serch_title'>Other Actyvity</h2>
                        <div class="bar"><i class="fa fa-dribbble"></i></div>
                        <h6>Other Actyvity in Georgia</h6>

                        @foreach($others as $other)
                            <div class="food col-md-4" style='horizontal-align: bottom;'>
                                <div class="portfolio-img view view-first">
                                    
                                    <img src="{{asset('images/other_img/'.$other->image)}}" class="img-responsive">
                                        
                                    <div class="mask">
                                        <a href="{{ route('other_page', array('title'=>$other->title)) }}" class="info">
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="isotope-item-dtls">
                                        <h3>{{ $other -> article_title }}</h3>
                                        <div class="article_size">
                                            {!! $other -> description_short !!}
                                        </div>
                                    </div><!--/isotope-item-dtls-->
                                    
                                </div>
                            </div>                            
                            @if( (($num_4++ % 3) == 0) )
                            <div class="clearfix"></div>
                            @endif
                        @endforeach                            
                        </div>  
                    </div> 
                    @endif
                @endif       


      
                @if(isset($events))
                    @if(count($events) != 0)
                    <div class="clearfix"></div>
                    
                    <div class="container h-recent-work events">
                        <div class="row">
                            <div class="container">
                                    <h2 class='serch_title'>Events</h2>
                                    <div class="bar"><i class="fa fa-cog"></i></div>
                                    <h6>Event</h6>
                                    <div class="container">
                                        <div class="row event-list">
                                            @foreach($events as $event)
                                                <div class="col-sm-4 eav">
                                                    <!-- <div class="panel panel-primary event-primary"> -->
                                                    <div class="panel-primary event-primary">
                                                        <div class="panel-heading">
                                                            <h2>
                                                                <a href="{{ route('events_page', array('title'=>$event->title)) }}">
                                                                    {{ $event -> article_title }}
                                                                </a>
                                                            </h2>
                                                        </div>
                                                        <div class="panel-body nopadding">
                                                            <img alt='Climbing inGeorgia' src="assets/img/events_img/{{ $event -> image }}" alt="event image" class="img-responsive"/>
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
                                                                {!! $event -> description_short !!}
                                                            </div>
                                                            
                                                            <a href="{{ route('events_page', array('title'=>$event->title)) }}" class="btn btn-success">
                                                                Read more...
                                                            </a>
                        
                                                        </div>
                                                    </div>
                                                </div>                            
                                                @if( (($num_5++ % 3) == 0) )
                                                <div class="clearfix"></div>
                                                @endif
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    @endif
                @endif
            </div>
        </div>
        
    </div>
@endsection