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
            
<!--             @if (count($errors) > 0)
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif -->
                
            @if (isset($site) && is_object($site) )
				<!-- @foreach($pages as $k=>$page) -->
    						
                <div class="row">
                    
                    <!--Description and links-->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="aboutus">
                            
    						{!! $site -> text !!}
    
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
                    
                <!--Contacts-->
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                         <!--<div class="col-xs-12 col-sm-6 col-lg-4"> -->
                        	<!--<div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">							-->
                        	<div class="box">							
                        		<div class="icon col-lg-12 col-md-12 col-sm-6 col-xs-12">
                        			<div class="image"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
                        			<div class="info">
                        				<h3 class="title">{{$site -> user_1}}</h3>
                        				<p>
                        					<i class="fa fa-envelope" aria-hidden="true"></i> {{$site -> mail_1}}
                        					<br>
                        					<br>
                        					<i class="fa fa-mobile" aria-hidden="true"></i> {{$site -> num_1}}
                        				</p>
                        				<h3 class="title">
                        					@if(($site->leng_1_1) != NULL)
                        						{{$site -> leng_1_1}}
                        		        	@endif
                        				</h3>
                        				<h3 class="title">
                        					@if(($site->leng_1_2) != NULL)
                        						{{$site -> leng_1_2}}
                        		        	@endif
                        				</h3>
                        				<ul>      
                        					@if(($site->fb_1) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$site -> fb_1}}">
                                                		<i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
                                                	</a> 
                                                </li>
                                            @endif 
                        					@if(($site->inst_1) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$site -> inst_1}}">
                                                		<i id="social-inst" class="fa fa-instagram fa-3x social"></i>
                                                	</a> 
                                                </li>
                                            @endif
                                    	</ul> 
                        			</div>
                        		</div>
                        		<div class="space"></div>
                        	</div> 
                        </div>
                        	
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                         <!--<div class="col-xs-12 col-sm-6 col-lg-4"> -->
                        	<div class="box">							
                        	<!--<div class="box">-->
                        		<div class="icon col-lg-12 col-md-12 col-sm-6 col-xs-12">
                        			<div class="image"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
                        			<div class="info">
                        				<h3 class="title">{{$site -> user_2}}</h3>
                        				<p>
                        					<i class="fa fa-envelope " aria-hidden="true"></i> {{$site -> mail_2}}
                        					<br>
                        					<br>
                        					<i class="fa fa-mobile" aria-hidden="true"></i> {{$site -> num_2}}
                        				</p>
                        				<h3 class="title">
                        					@if(($site->leng_2_1) != NULL)
                        						{{$site -> leng_2_1}}
                        		        	@endif
                        				</h3>
                        				<h3 class="title">
                        					@if(($site->leng_2_2) != NULL)
                        						{{$site -> leng_2_2}}
                        		        	@endif
                        				</h3>
                        				<ul>      
                        					@if(($site->fb_2) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$site -> fb_2}}">
                                                		<i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
                                                	</a> 
                                                </li>
                                            @endif 
                        					@if(($site->inst_2) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$site -> inst_2}}">
                                                		<i id="social-inst" class="fa fa-instagram fa-3x social"></i>
                                                	</a> 
                                                </li>
                                            @endif
                        
                                    	</ul>
                        			</div>
                        		</div>
                        		<!-- <div class="space"></div> -->
                        	</div> 
                        </div>
                    </div>
                    
                <!-- @endforeach -->
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

@if($partners_count > 0 && isset($partners))	
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
                        <img src="{{ asset('images/partners_img/'.$partner->image) }}" alt="{{$partner -> title}}">
                        <!-- <img src="../assets/img/partners_img/{{ $partner->image }}"> -->
                      </div>
                      <div class="card-body">
                        <h2>{{$partner -> title}}</h2>
                        <div class="central_text">
                        {!!$partner -> description!!}
                        </div>
                        <a type= "button" class="btn btn-block btn-sm" href="{{ route('partners_page', array('title'=>$partner->url_title)) }}">Read more</a>
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
