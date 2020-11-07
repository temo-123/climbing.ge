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
        
                
            @if (isset($site) && is_object($site) )
    						
                <div class="row">
                    
                    <!--Description and links-->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="aboutus">
                            
                            @if($page_locale == "ru")
                                {!! $site -> text_ru !!}
                            @elseif($page_locale == "ka")
                                {!! $site -> text_ka !!}
                            @else
    						    {!! $site -> text !!}
                            @endif
    
        			        <div class="about-icons"> 
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
        		            </div>
                        </div>
                    </div>
                    
                    <!--Actyvity list-->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="feature">
                            <div class="feature-box">
                                <div class="clearfix">
                                    <div class="iconset category_img">
                                        <a href="{{ $site -> activity_link_1 }}">
                                            <img alt='{!! $site -> activity_link_1!!}' style='width: 60px' src="{{ asset('images/site_img/logo/'.$site -> activity_img_1) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $site -> activity_link_1 }}">
                                            <h4>{!! $site -> activity_title_1!!}</h4>
                                        </a>
                                        <p>{!! $site -> activity_text_1!!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="clearfix">
                                    <div class="iconset">
                                        <a href="{{ $site -> activity_link_2 }}">
                                            <img alt='{!! $site -> activity_link_2!!}' style='width: 60px' src="{{ asset('images/site_img/logo/'.$site -> activity_img_2) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $site -> activity_link_2 }}">
                                            <h4>{!! $site -> activity_title_2!!}</h4>
                                        </a>
                                        <p>{!! $site -> activity_text_2!!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="clearfix">
                                    <div class="iconset">
                                        <a href="{{ $site -> activity_link_3 }}">
                                            <img alt='{!! $site -> activity_link_3!!}' style='width: 60px' src="{{ asset('images/site_img/logo/'.$site -> activity_img_3) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $site -> activity_link_3 }}">
                                            <h4>{!! $site -> activity_title_3!!}</h4>
                                        </a>
                                        <p>{!! $site -> activity_text_3!!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="clearfix">
                                    <div class="iconset">
                                        <a href="{{ $site -> activity_link_4 }}">
                                            <img alt='{!! $site -> activity_title_4!!}' style='width: 60px' src="{{ asset('images/site_img/logo/'.$site -> activity_img_4) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $site -> activity_link_4 }}">
                                            <h4>{!! $site -> activity_title_4!!}</h4>
                                        </a>
                                        <p>{!! $site -> activity_text_4!!}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        	    <h2>Gread charts</h2>
                            	<img alt="{!! $site -> meta_keyword!!}" src="{{ asset('images/site_img/'.$site -> image) }}">
                        	</div>
                        </div> 
                    </div>                    
                </div>
                        
                <div class="clearfix"></div>
                    
			@endif    
				
        </div>
    </div>
    
    <!--Message-->
    
        	
            
            <message-component></message-component>



<script>
    // $(function(){
    //     $('#js_form').submit(function(event){
    //         var verified = grecaptcha.getResponse();
    //         if (verified.length === 0) {
    //             event.preventDefault();
    //         }
    //     });
    // });
</script>

@if($partners_count > 0 )	
    <div class="services" id="partner">
        <div class="container h-recent-work events">
    
		<div class="partners_block_title">
			<h2>Partners</h2>
		</div>
		
    	<div  class="bar"><i class="fa fa-book"></i></div>

        <h6>{{$site -> partner_descriptio}}</h6>	

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css"> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script> -->

        <div class="container">     
          <div class="row">
          <!-- Swiper -->
            <div class="swiper-container">
              <div class="swiper-wrapper">

              @foreach($partners as $partner)

              <p style="display: none;">{{$thurs_num++}}</p>

              @if(($thurs_num) == 1)

              <div class="swiper-slide">
                <div class="row">
              @endif
                  <div class="col-md-3 col-xs-6">
                    <div class="card">
                      <div class="card-img">
                        <img src="{{ asset('images/partners_img/'.$partner['image']) }}" alt="{{$partner[0][0] -> title}}">
                        <!-- <img src="../assets/img/partners_img/{{ $partner->image }}"> -->
                      </div>
                      <div class="card-body">
                        <h2>{{$partner[0][0] -> title}}</h2>
                        <div class="central_text">
                        {!!$partner[0][0] -> description!!}
                        </div>
                        <a type= "button" class="btn btn-block btn-sm" href="{{ route('partners_page', array('title'=>$partner['url_title'])) }}">Read more</a>
                      </div>
                    </div>
                  </div>
              @if(($thurs_num % 4) == 0 ) 
                </div>
              </div>

              <div class="swiper-slide">
                <div class="row">
              @endif
              @if(($loop->count) == $thurs_num)
                </div>
              </div>
              @endif
              @endforeach
            </div>
              <!-- Add Pagination -->

              <div class="swiper-pagination"></div>
              <!-- Add Arrows -->
              <div class="swiper-button-next"></div>
              <div class="swiper-button-prev"></div>

              </div>
            </div>
          </div>
        </div>

    	
    	</div>
    </div>

@endif
</div>

@endsection       
