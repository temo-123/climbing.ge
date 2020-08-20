

    @section('meta_title',  $events->title )    
    @if ($events->short_description != NULL)
        @section('meta_description',  $events->short_description )
    @else
        @section('meta_description',  $events->title )
    @endif
    @if ($events->meta_keyword != NULL)
        @section('meta_keyword',  $events->meta_keyword )
    @else
        @section('meta_keyword',  $events->title )
    @endif

    @section('meta_img',  asset("images/events_img/".$events->image))

<div class="container top_menu_margin">
    <div class='row'>
        
        <h1 class="blog-title">
            {{$events->article_title}}
            
            @if (Route::has('login'))
                @auth
                <a href="">Edit</a>
                @endauth
            @endif
        </h1>
            
        <div class="col-md-6">
            @component('components.breadcrumb')
                @slot('parent') Home @endslot
                @slot('link') {{route('index')}} @endslot
                @slot('active') Events @endslot
                @slot('article') {{ $events->title }} @endslot
            @endcomponent
        
            <p class="calendar">
                {{ $events -> start_data_day }}<em>{!!$events->start_data_month!!}</em>
            </p>
            
            {!!$events->text!!}
        </div><!--/. col-md-6 -->
        
        <div class="col-md-6">
            <div style="text-align: center;">
                {!!$events->map!!}
            </div>
            @if(Auth::guest())
                <div style="text-align: center; margin: 4% 0 4% 0;">
                    <a href="{{ route('events_interes', array('events_id'=>$events->id, 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                </div>
            @else
                @auth
                @if(isset($interested))
                    @forelse($interested as $iteres)
                        @if(Auth::user()->id == $iteres->user_id)
                            <div style="text-align: center; margin: 4% 0 4% 0;">
                                <a href="{{ route('events_interes', array('events_id'=>$events->id, 'actions'=>'del')) }}" type="button" class="btn btn-info" style="width: 100%;">No Interested</a>
                            </div>
                        @else
                            <div style="text-align: center; margin: 4% 0 4% 0;">
                                <a href="{{ route('events_interes', array('events_id'=>$events->id, 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                            </div>
                        @endif
                    @empty
                    <div style="text-align: center; margin: 4% 0 4% 0;">
                        <a href="{{ route('events_interes', array('events_id'=>$events->id, 'actions'=>'add')) }}" type="button" class="btn btn-success" style="width: 100%;">Interested</a>
                    </div>
                    @endforelse
                @endif
                @endauth
            @endif


            @if($events->info != NULL)
            <h2>Contact Information</h2>
            {!!$events->info!!}
            @endif
            
            <div class="col-md-12" style='margin: 20px;'> 
                <ul class="social-network social-circle" style="text-align: center;">
                    
                    @if(($events->fb_link) != NULL)       
                      <li>
                        <a target="_blank" href="{{$events->fb_link}}" class="icoFacebook ico_color" title="Facebook">
                          <i class="fa fa-facebook"></i>
                        </a>
                      </li>
                    @endif
        
                    @if(($events->twit_link) != NULL) 
                    <li>
                      <a target="_blank" href="{{$events->twit_link}}" class="icoTwitter ico_color" title="Twitter">
                        <i class="fa fa-twitter"></i>
                      </a>
                    </li>
                    @endif
        
                    @if(($events->google_link) != NULL) 
                    <li>
                      <a target="_blank" href="{{$events->google_link}}" class="icoGoogle ico_color" title="Google +">
                        <i class="fa fa-google-plus"></i>
                      </a>
                    </li>
                    @endif
        
                    @if(($events->inst_link) != NULL) 
                    <li>
                      <a target="_blank" href="{{$events->inst_link}}" class="icoLinkedin ico_color" title="Instagram">
                        <i class="fa fa-instagram"></i>
                      </a>
                    </li>
                    @endif
                    
                </ul>
            </div>
            
        </div><!--/. map(col-md-6)-->

    </div><!--/. row-->
</div><!--/. container -->
