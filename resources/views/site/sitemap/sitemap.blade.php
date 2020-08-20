@section('title', 'Sitemap')
@extends('layouts.app')

@section('content')

<div class="container top_menu_margin">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"> 
<!---- Must Add above thing or you can use alternative icons or CSS Entities---->

	<div class="tree ">
		<ul>
			<li>
				<span>
					<a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#Web" aria-expanded="true" aria-controls="Web">
						<i class="collapsed"><i class="fas fa-folder"></i></i>
						<i class="expanded"><i class="far fa-folder-open"></i></i> 
					Climbing.ge
					</a>
				</span>
				<div id="Web" class="collapse show">
					<ul>
						<li>
							<span>
								<i class="far fa-file"></i><a href="{{route('about_page')}}">About Us</a>
							</span>
						</li>

						<li>
							<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#Page2" aria-expanded="false" aria-controls="Page2"> Climbing</a></span>
							<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#Page2" aria-expanded="false" aria-controls="Page2"><i class="fa fa-caret-square-o-down"></i></a></span>
							<ul>
								<div id="Page2" class="collapse">
									<li>
										<span><a href="{{route('indoor_list')}}">Indoor Climbing</a></span>
										<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#indoor" aria-expanded="false" aria-controls="indoor"><i class="fa fa-caret-square-o-down"></i></a></span>
										<ul>
											<div id="indoor" class="collapse">
	    										@foreach($indoors as $indoor)
												<li>
													<span>
														<i class="far fa-file"></i><a href="{{route('indoor_page', array('title'=>$indoor->url_title))}}"> {{$indoor -> title}}1</a>
													</span>
												</li>
	    										@endforeach
											</div>
										</ul>
									</li>

									<li>
										<span><a href="{{route('outdoor_list')}}">Outdoor Climbing</a></span>
										<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#outdoor" aria-expanded="false" aria-controls="outdoor"><i class="fa fa-caret-square-o-down"></i></a></span>
										<ul>
											<div id="outdoor" class="collapse">
												@foreach($outdoors as $outdoor)
												<li>
													<span>
														<i class="far fa-file"></i><a href="{{route('outdoor_page', array('title'=>$outdoor->url_title))}}"> {{$outdoor -> title}}</a>
													</span>
												</li>
	    										@endforeach
											</div>
										</ul>
									</li>    

								</div>
							</ul>
						</li>

						<li>
							<span><a href="{{route('ice_list')}}">Ice & Mixed</a></span>
							<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#mix" aria-expanded="false" aria-controls="mix"><i class="fa fa-caret-square-o-down"></i></a></span>
							<ul>
								<div id="mix" class="collapse">
									@foreach($ices as $mix)
									<li><span><i class="far fa-file"></i><a href="{{route('ice_page', array('title'=>$mix->url_title))}}"> {{$mix -> title}} </a></span></li>
	    							@endforeach
								</div>
							</ul>
						</li>
						<li>
							<span><a href="{{route('mount_list')}}">Mountaineering</a></span>
							<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#mount" aria-expanded="false" aria-controls="mount"><i class="fa fa-caret-square-o-down"></i></a></span>
							<ul>
								<div id="mount" class="collapse">
									@foreach($mountains as $mountain)
									<li><span><i class="far fa-file"></i><a href="{{route('mount_page', array('title'=>$mountain->url_title))}}"> {{$mountain -> title}} </a></span></li>
	    							@endforeach
								</div>
							</ul>
						</li>
						<li>
							<span><a href="{{route('other_list')}}">Other</a></span>
							<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#other" aria-expanded="false" aria-controls="other"><i class="fa fa-caret-square-o-down"></i></a></span>
							<ul>
								<div id="other" class="collapse">
									@foreach($others as $other_a)
									<li><span><i class="far fa-file"></i><a href="{{route('other_page', array('title'=>$other_a->url_title))}}"> {{$other_a -> title}} </a></span></li>
	    							@endforeach
								</div>
							</ul>
						</li>
						<li>
							<span><a href="{{route('index')}}#news">News</a></span>
							<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#news" aria-expanded="false" aria-controls="news"><i class="fa fa-caret-square-o-down"></i></a></span>
							<ul>
								<div id="news" class="collapse">
									@foreach($news as $new)
									<li><span><i class="far fa-file"></i><a href="{{route('news_page', array('title'=>$new->url_title))}}"> {{$new -> title}} </a></span></li>
	    							@endforeach
								</div>
							</ul>
						</li>
						<li>
							<span><a href="{{route('index')}}#event">Events</a></span>
							<span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#events" aria-expanded="false" aria-controls="events"><i class="fa fa-caret-square-o-down"></i></a></span>
							<ul>
								<div id="events" class="collapse">	    
									@foreach($events as $event)
									<li><span><i class="far fa-file"></i><a href="{{route('events_page', array('title'=>$event->url_title))}}"> {{$event -> title}} </a></span></li>
	    							@endforeach
								</div>
							</ul>
						</li>
						<li>
							<span><a href="{{route('index')}}#services">Tech tips</a></span>
							<span>
								<a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#tech_tips" aria-expanded="false" aria-controls="tech_tips">
									<i class="fa fa-caret-square-o-down"></i>
								</a>
							</span>
							<ul>
								<div id="tech_tips" class="collapse">	    
									@foreach($securitis as $security)
									<li><span><i class="far fa-file"></i><a href="{{route('security_page', array('title'=>$security->url_title))}}"> {{$security -> title}} </a></span></li>
									@endforeach
								</div>
							</ul>
						</li>
						<li>
							<span><a href="{{route('about_page')}}#partner">Partners</a></span>
							<span>
								<a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#partners" aria-expanded="false" aria-controls="partners">
									<i class="fa fa-caret-square-o-down"></i>
								</a>
							</span>
							<ul>
								<div id="partners" class="collapse">
	    							@foreach($partners as $partner)
									<li><span><i class="far fa-file"></i><a href="{{route('partners_page', array('title'=>$partner->url_title))}}"> {{$partner -> title}} </a></span></li>
	    							@endforeach
								</div>
							</ul>
						</li>
						<li>
							<span><a href="{{route('shop_list')}}">Shop</a></span>
							<span>
								<a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#products" aria-expanded="false" aria-controls="products">
									<i class="fa fa-caret-square-o-down"></i>
								</a>
							</span>
							<ul>
								<div id="products" class="collapse">
	    							@foreach($products as $product)
									<li><span><i class="far fa-file"></i><a href="{{route('shop_page', array('title'=>$product->url_title))}}"> {{$product -> title}} </a></span></li>
	    							@endforeach
								</div>
							</ul>
						</li>

						<li>
							<span>
								<i class="far fa-file"></i><a href="{{url('sitemap.xml')}}">Sitemap.xml</a>
							</span>
						</li>

					</ul>

				</div>
			</li>
		</ul>
	</div>

</div>

<style type="text/css">
	.tree li {
    list-style-type:none;
    margin:0;
    padding:10px 5px 0 5px;
    position:relative
}
.tree li::before, 
.tree li::after {
    content:'';
    left:-20px;
    position:absolute;
    right:auto
}
.tree li::before {
    border-left:2px solid #000;
    bottom:50px;
    height:100%;
    top:0;
    width:1px
}
.tree li::after {
    border-top:2px solid #000;
    height:20px;
    top:25px;
    width:25px
}
.tree li span {
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border:2px solid #000;
    border-radius:3px;
    display:inline-block;
    padding:3px 8px;
    text-decoration:none;
    cursor:pointer;
}
.tree>ul>li::before,
.tree>ul>li::after {
    border:0
}
.tree li:last-child::before {
    height:27px
}
.tree li span:hover {
    background: hotpink;
    border:2px solid #94a0b4;
    }

[aria-expanded="false"] > .expanded,
[aria-expanded="true"] > .collapsed {
  display: none;
}
    
    
</style>

@endsection