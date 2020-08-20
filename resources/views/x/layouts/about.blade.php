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
                
            @if (isset($pages) && is_object($pages) )
				@foreach($pages as $k=>$page)
    						
                <div class="row">
                    <!--Title-->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        @auth
                        @if(Auth::user()->hasRole('admin'))
		                    @auth
		                        <h2>{{$page -> title}}</h2>
		                        <a href="{{route($article_edit_link,['route'=>$page->id])}}">
                                    (Edit)
                                </a>
		                    @else
                                <h2 class="aboutus-title">{{$page -> title}}</h2>

		                    @endauth
		                @endif
                        @endauth
                    </div>
                    
                    <!--Description and links-->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="aboutus">
                            
    						{!! $page -> text !!}
    
        			        <div class="about-icons"> 
                                <ul>            
                                	@if(($page->fb_link) != NULL)
        	                            <li>
        	                            	<a target="_blank" href="{{$page -> fb_link}}">
        	                            		<i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
        	                            	</a> 
        	                            </li>   
        					        @endif
                                	@if(($page->twit_link) != NULL)
        	                            <li>
        	                            	<a target="_blank" href="{{$page -> twit_link}}">
        	                            		<i id="social-tw" class="fa fa-twitter-square fa-3x social"></i>
        	                            	</a> 
        	                            </li>   
        					        @endif
                                	@if(($page->google_link) != NULL)
        				                <li> 
        				                	<a target="_blank" href="{{$page -> google_link}}">
        				                		<i id="social-gp" class="fa fa-google-plus-square fa-3x social"></i>
        				                	</a> 
        				                </li>   
        					        @endif
                                	@if(($page->inst_link) != NULL)
        	                            <li> 
        	                            	<a target="_blank" href="{{$page -> inst_link}}">
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
                                        <a href="{{ $page -> activity_link_1 }}">
                                            <img alt='{!! $page -> activity_link_1!!}' style='width: 60px' src="{{ asset('images/about_img/logo/'.$page -> activity_img_1) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $page -> activity_link_1 }}">
                                            <h4>{!! $page -> activity_title_1!!}</h4>
                                        </a>
                                        <p>{!! $page -> activity_text_1!!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="clearfix">
                                    <div class="iconset">
                                        <a href="{{ $page -> activity_link_2 }}">
                                            <img alt='{!! $page -> activity_link_2!!}' style='width: 60px' src="{{ asset('images/about_img/logo/'.$page -> activity_img_2) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $page -> activity_link_2 }}">
                                            <h4>{!! $page -> activity_title_2!!}</h4>
                                        </a>
                                        <p>{!! $page -> activity_text_2!!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="clearfix">
                                    <div class="iconset">
                                        <a href="{{ $page -> activity_link_3 }}">
                                            <img alt='{!! $page -> activity_link_3!!}' style='width: 60px' src="{{ asset('images/about_img/logo/'.$page -> activity_img_3) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $page -> activity_link_3 }}">
                                            <h4>{!! $page -> activity_title_3!!}</h4>
                                        </a>
                                        <p>{!! $page -> activity_text_3!!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="clearfix">
                                    <div class="iconset">
                                        <a href="{{ $page -> activity_link_4 }}">
                                            <img alt='{!! $page -> activity_title_4!!}' style='width: 60px' src="{{ asset('images/about_img/logo/'.$page -> activity_img_4) }}">
                                        </a>
                                    </div>
                                    <div class="feature-content">
                                        <a href="{{ $page -> activity_link_4 }}">
                                            <h4>{!! $page -> activity_title_4!!}</h4>
                                        </a>
                                        <p>{!! $page -> activity_text_4!!}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        	    <h2>Gread charts</h2>
                            	<img alt="{!! $page -> meta_keyword!!}" style='width: 100%'; src="{{ asset('images/about_img/'.$page -> image) }}">
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
                        				<h3 class="title">{{$page -> user_1}}</h3>
                        				<p>
                        					<i class="fa fa-envelope" aria-hidden="true"></i> {{$page -> mail_1}}
                        					<br>
                        					<br>
                        					<i class="fa fa-mobile" aria-hidden="true"></i> {{$page -> num_1}}
                        				</p>
                        				<h3 class="title">
                        					@if(($page->leng_1_1) != NULL)
                        						{{$page -> leng_1_1}}
                        		        	@endif
                        				</h3>
                        				<h3 class="title">
                        					@if(($page->leng_1_2) != NULL)
                        						{{$page -> leng_1_2}}
                        		        	@endif
                        				</h3>
                        				<ul>      
                        					@if(($page->fb_1) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$page -> fb_1}}">
                                                		<i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
                                                	</a> 
                                                </li>
                                            @endif 
                        					@if(($page->inst_1) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$page -> inst_1}}">
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
                        				<h3 class="title">{{$page -> user_2}}</h3>
                        				<p>
                        					<i class="fa fa-envelope " aria-hidden="true"></i> {{$page -> mail_2}}
                        					<br>
                        					<br>
                        					<i class="fa fa-mobile" aria-hidden="true"></i> {{$page -> num_2}}
                        				</p>
                        				<h3 class="title">
                        					@if(($page->leng_2_1) != NULL)
                        						{{$page -> leng_2_1}}
                        		        	@endif
                        				</h3>
                        				<h3 class="title">
                        					@if(($page->leng_2_2) != NULL)
                        						{{$page -> leng_2_2}}
                        		        	@endif
                        				</h3>
                        				<ul>      
                        					@if(($page->fb_2) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$page -> fb_2}}">
                                                		<i id="social-fb" class="fa fa-facebook-square fa-3x social"></i>
                                                	</a> 
                                                </li>
                                            @endif 
                        					@if(($page->inst_2) != NULL)
                                        		<li class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                	<a target="_blank" href="{{$page -> inst_2}}">
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
                    </div>
                    
                @endforeach
			@endif    
				
        </div>
    </div>
    
    <!--Message-->
    <div class="container">
    	<div class="row">
    	    
        	<h2>Send message</h2>
        	<!-- <p>You do not need to provide a phone number, but if you specify this, it will help us to meet you. Other parameters are required.</p> -->
        	
        @if (count($errors) > 0)
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
        	
            <form method="POST" action="{{url('message')}}" id="js_form">
                @csrf
    		    <div class='row'>
                    <div class="col-xs-6">
                        <div class="row">
                        	<div class="col-md-12">
                          		<div class="form-group">
                                    <input type="text" class="form-control" name="name"  id="name" placeholder="Name*">
                          		</div>
                          	</div>
                        </div>
                        <div class="row">
                        	<div class="col-md-12">
                          		<div class="form-group">
                                    <input type="text" class="form-control" name="surname"  id="surname" placeholder="Surname*">
                                </div>
                          	</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                          		<div class="form-group">
                                    <input type="email" name="email" class="form-control textarea" placeholder="E_mail">
                          		</div>
                          	</div>
                      	</div>
                        <div class="row">
                            <div class="col-md-12">
                          		<div class="form-group">
                                    <input type="text" class="form-control" name="num"  id="num" placeholder="Phone number">
                          		</div>
                          	</div>
                      	</div>
                      	<div class="row">
                        	<div class="col-md-12">
                                <div class="form-group form_left">
        				            <select class="form-control" name="country"> 
                                        @include('components.all_countries')
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form_left">
                                    <div class="g-recaptcha" data-sitekey="6LfV980UAAAAAFSMmbkzVw1J_-Q2cDroTJoBD9k1"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
              		<div class="col-xs-6">
                  		<div class="form-group">
                            <textarea rows="15" name="msg" id="msg" placeholder="Your message" class="form-control textarea">
                            </textarea>
                  		</div>
              	    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-default btn-send main-btn">Send</button>
                        </div>
                    </div>
                </div>
            </form>
            
    	</div>
    </div>


<script>
    $(function(){
        $('#js_form').submit(function(event){
            var verified = grecaptcha.getResponse();
            if (verified.length === 0) {
                event.preventDefault();
            }
        });
    });
</script>

@if($partners_count > 0 && isset($partners))	
    <div class="services" id="partner">
        <div class="container h-recent-work events">
    
		<div class="partners_block_title">
			<h2>Partners</h2>
		</div>
		
    	<div  class="bar"><i class="fa fa-book"></i></div>

        <h6>{{$site -> partner_descriptio}}</h6>	

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>

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

            
