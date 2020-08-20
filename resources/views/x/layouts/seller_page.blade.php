<!--         <div class="container">
        <div class="layout top_menu_margin">
    <div class="col-xs-12">
          <div class="row">
            <div class="col-md-6 img">
              <i style='font-size: 1000%; margin-top: 2%;' class="fa fa-user-circle-o" aria-hidden="true"></i>
            </div>
            <div class="col-md-6 details">
                <p>Seller - {{ $seller->name }} {{ $seller->surname }}</p>
                <p>Email - {{ $seller->email }}</p>
                <p>Number - {{ $seller->phone_number }}</p>
                <p>Products - {{$seler_products_count}} </p>
            </div>
        </div> -->






<div class="container top_menu_margin">
    <div class="span3 well">
        <center>
        <a href="#aboutModal" data-toggle="modal" data-target="#myModal"><img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRbezqZpEuwGSvitKy3wrwnth5kysKdRqBW54cAszm_wiutku3R" name="aboutme" width="140" height="140" class="img-circle"></a>
        <h3>Joe Sixpack</h3>
        <em>click my face for more</em>
		</center>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title" id="myModalLabel">More About {{ $seller->name }}</h4>
                    </div>
                <div class="modal-body">
                    <center>
                    <img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRbezqZpEuwGSvitKy3wrwnth5kysKdRqBW54cAszm_wiutku3R" name="aboutme" width="140" height="140" border="0" class="img-circle"></a>
                    <h3 class="media-heading">Joe Sixpack <small>USA</small></h3>
                    <!-- <span><strong>Skills: </strong></span>
                        <span class="label label-warning">HTML5/CSS</span>
                        <span class="label label-info">Adobe CS 5.5</span>
                        <span class="label label-info">Microsoft Office</span>
                        <span class="label label-success">Windows XP, Vista, 7</span> -->
                    </center>
                    <center>            	
                    	<div class="seler_info">
			                <p>Email - {{ $seller->email }}</p>
			                <p>Number - {{ $seller->phone_number }}</p>
			                <p>Products - {{$seler_products_count}} </p>
		            	</div>
                    </center>
                    <hr>
                    <!-- <center>
                    <p class="text-left"><strong>Bio: </strong><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut sem dui, tempor sit amet commodo a, vulputate vel tellus.</p>
                    <br>
                    </center> -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
<!-- </div>









        <div class="container" style="margin-top: 5%;"> -->
<section class="inner">
    <section class="portfolio" id="portfolio">
        <div class="container-fluid">
            <div class="row">
				@if(isset($title))
				<div class="artile_list_title">
					<h1>{{$title}}</h1>
				</div>
				@endif
				@if(isset($products_count) && $products_count != null)
                <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                <h6>{{$site->shop_description}} </h6>
                @endif
                @if(isset($tags) && $tags_count != NULL)
                <div align="center">
                    <button class="filter-button" data-filter="all">All</button>
                    @foreach($tags as $k=>$tag)
                    <button class="filter-button" data-filter="{!! $tag->id !!}">{!! $tag->category !!}</button>
                    @endforeach
                </div>
                <hr>
                @endif
				<ul class="grid">
					@if(isset($products))
					@forelse($products as $product)

					<li class="grid-tile gallery_product filter {{$product -> id}}">
						<div class="item shop_view">
							<a href="{{route('shop_page', array('title'=>$product->url_title))}}">
								@if($product -> general_image == 1)
								<div class="item-img" style="background-image: url('{{ asset('images/shop_img/'.$product -> image_1) }}'); background-size: auto auto; background-size: 150%; background-position: center 30%;"></div>
								@elseif($product -> general_image == 2)
								<div class="item-img" style="background-image: url('{{ asset('images/shop_img/'.$product -> image_2) }}'); background-size: auto auto; background-size: 150%; background-position: center 30%;"></div>
								@elseif($product -> general_image == 3)
								<div class="item-img" style="background-image: url('{{ asset('images/shop_img/'.$product -> image_3) }}'); background-size: auto auto; background-size: 150%; background-position: center 30%;"></div>
								@elseif($product -> general_image == 4)
								<div class="item-img" style="background-image: url('{{ asset('images/shop_img/'.$product -> image_4) }}'); background-size: auto auto; background-size: 150%; background-position: center 30%;"></div>
								@else
								<div class="item-img" style="background-image: url('{{ asset('images/shop_img/'.$product -> image_1) }}'); background-size: auto auto; background-size: 150%; background-position: center 30%;"></div>
								@endif
							</a>
							<div class="item-pnl product-image">
								@if($product->discount != null)
                                <div class="discount-percent-badge discount-badge-fourty">-{{$product->discount}}%</div>
                                @endif
								<div class="pnl-wrapper">
									<div class="pnl-description">
										<a href="{{route('shop_page', array('title'=>$product->url_title))}}">
											<h2><span class="pnl-label">{{$product -> title}}</span></h2>
										</a>
										
										@if($product->discount != null)
										@foreach($price_array as $new_price)
										@if($new_price['product_id'] == $product->id)
										<div class="old_price">
										<span class="pnl-price ">
											{{$product -> currency}}{{$product -> price}}
										</span>
										</div>

										<div class="price">
										<span class="pnl-price price">
											{{$product -> currency}}{{$new_price['new_price']}}
										</span>
										</div>
										@endif
										@endforeach
										@else
										<div class="price">
										<span class="pnl-price price">{{$product -> currency}}{{$product -> price}}</span>
										</div>
										@endif

									</div>
									<div class="pnl-tocart">
										<div class="pnl-ic-wrapper shop_block_icons">
											<span class="pnl-ic">

												@if(Auth::guest())
												<a href="{{route('favorite_product', array('product_id'=>$product->id, 'actions'=>'add'))}}">
													<i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%;"></i>
												</a>
												@else
													@foreach($favorite_array as $favorite)
														@if($favorite["product_id"] == $product -> id)
															@if($favorite["users_favorite"] == 1)
																<a href="{{route('favorite_product', array('product_id'=>$product->id, 'actions'=>'del'))}}">
																	<i class="fa fa-heart" aria-hidden="true" style="font-size: 250%;"></i>
																</a>
															@elseif($favorite["users_favorite"] == 0)
																<a href="{{route('favorite_product', array('product_id'=>$product->id, 'actions'=>'add'))}}">
																	<i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%;"></i>
																</a>
															@endif
														@endif
													@endforeach
												@endif

											</span>
										</div>
									</div>
<!-- 									<div class="pnl-tocart">
										<div class="pnl-ic-wrapper shop_block_icons">
											<span class="pnl-ic">
												<i class="fa fa-shopping-cart" aria-hidden="true" style="font-size: 250%;"></i>
												<i class="fa fa-cart-plus" aria-hidden="true" style="font-size: 250%;"></i>
											</span>
										</div>
									</div> -->
								</div>
							</div>
						</div>
					</li>
					@empty
                        <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                        <h6>{{$site->shop_empty}} </h6>
                    	<img class="article_404" src="{{asset('images/404/empty_page/11.jpg')}}">
					@endforelse
					@endif

				</ul>

			</div>
		</div>
	</section>
</section>
<!-- </div> -->

        <div class="more-products" id="more-products-wrap">
          <a href="{{route('shop_list')}}"><span id="more-products" data-rows_per_page="1">All products</span></a>
        </div>
		</div>
	</div></div></div>




		
		
		
		
		
			<style type="text/css">
.seler_info p{
	margin-bottom: 0;
}
.seler_info{
	margin-bottom: 10px;
	margin-top: 10px;
}








	.old_price{
		font-size: 90%; 
		text-decoration:line-through;
		color: #696969;
	}
	.old_price span{
		color: #696969;
		margin-bottom: -5%;
	}
	.price{
		font-size: 140%; 
	}
</style>
		
		
		<style>
.container .img{
    text-align:center;
}
.user_img{
    width: 45%;
}
.container .details{
    border-left:3px solid #ded4da;
}
.container .details p{
    font-size:15px;
    font-weight:bold;
}
.details p, h2{
    text-align:center;
}




		
		
.shop_view{
	margin-bottom: 5%;
    box-shadow: 0px 16px 38px -27px #000000;
    border-radius: 10px;
    border: solid 1px #97fffe;
}
.shop_block_icons{
	margin: 10em;
}

.layout {
	width: 100%; height: 100%;
	position: relative;
	font-family: 'Arimo', sans-serif;
	font-size: 16px;
	line-height: 1.5em;
	font-weight: 400;
	-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
}

.inner {
/*	max-width: 100%;
	padding: 0 0.5em;
	padding-top: 0.5em;
	transition: all 0.3s;*/
}

.grid { 
	/*margin: 0 0 0 -0.5em; */
	list-style-type: none; 
	transition: all 0.3s 
}
.grid:before, .grid:after { 
	/*content: " "; */
	display: table 
}
.grid:after { 
	clear: both 
}
.grid-tile {
	width: 100%;
	position: relative; float: left;
	/*padding: 0 0 0.5em 0.5em;*/
	box-sizing: border-box;
	transition: all 0.3s;
}
cursor
.item {
	width: 100%;
    position: relative;
    overflow: hidden;
	background-color: #fff;
	/*border-radius: 15px;*/
	/*border: 2px solid #eee;*/
	box-sizing: border-box;
}

.item-img {
	width: 100%;
    padding-bottom: 80%;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}

/*.item-img:hover {
	cursor: -webkit-grab; 
	cursor: -moz-grab;
}

.item-img:active {
	cursor: -webkit-grabbing; 
	cursor: -moz-grabbing;
}*/


.item-pnl {
	/*position: relative;*/
	width: 100%;
}

.pnl-wrapper {
	width: 100%;
	height: 60px;
	display: table;
}

.pnl-wrapper > div {
	display: table-cell;
	vertical-align: middle;
}

.pnl-description {
	position: relative;
	padding: 0;
	padding-left: 0.5em;
}

.pnl-label {
	color: #424346;
	line-height: 16px;
	max-height: 2.6em;
	font-size: 0.75em;
	/*overflow: hidden;*/
	display: block;
}

.pnl-price {
	line-height: 16px;
	color: #111;
	font-weight: 700;
	font-size: 0.75em;
	display: block;
	/*margin-top: 0.125em;*/
}

.pnl-ic-wrapper {
	width: 32px; height: 32px;
	line-height: 0;
	position: relative;
	margin: 0 auto;
	transform: scale(0.5);
}

.pnl-ic-wrapper svg {
    fill: none;
    stroke-width: 2.0;
    stroke-linecap: round;
    stroke-miterlimit: 4;
    stroke-location: outside;
}

.pnl-ic {
	display: inline-block;
	vertical-align: middle;
	width: 100%; height: 100%;
}

.pnl-favorites svg {
    stroke: #a1a8ad;
}

.pnl-favorites {
	width: 32px;
	cursor: pointer;
	position: relative;
}

.pnl-tocart svg {
    stroke: #a1a8ad;
}

.pnl-tocart {
	/*width: 32px;*/
	cursor: pointer;
	position: relative;
}
.pnl-label, .pnl-price{
	margin: 5%;
}

@media screen and (min-width: 320px) {
	.pnl-wrapper { height: 70px }
	.pnl-label, .pnl-price { font-size: 1em; line-height: 20px; }
	/*.pnl-favorites, .pnl-tocart { width: 56px }*/
	.pnl-ic-wrapper { transform: scale(0.75) }
	.pnl-description { padding-left: 1.0em }
}


@media screen and (min-width: 360px) {
	/*.inner { padding: 0 1.0em; padding-top: 1.0em }*/
	/*.grid { margin: 0 0 /0 -1.0em }*/
	/*.grid-tile { padding: 0 0 1.0em 1.0em }*/
	.pnl-description { padding-left: 1.5em }
	.pnl-wrapper { height: 70px }
}


/* PORTRAIT MOBILE MODE
 */

@media screen and (min-width: 480px) {
	/*.inner { padding: 0 0.5em; padding-top: 0.5em }*/
	/*.grid { margin: 0 0 /0 -0.5em }*/
	/*.grid-tile { padding: 0 0 0.5em 0.5em }*/
	.grid-tile { width: 50% }
	.pnl-wrapper { height: 60px }
	.pnl-description { padding-left: 0.5em }
	.pnl-label, .pnl-price { font-size: 0.75em; line-height: 18px; }
	.pnl-ic-wrapper { transform: scale(0.5) }
	/*.pnl-favorites, .pnl-tocart { width: 40px }*/
}

/* LANDSCAPE MOBILE MODE (~416px)
 */

@media (max-height: 26em) {
	.pnl-wrapper { height: 48px }
	.grid-tile { width: 33.3333% }
	.pnl-label { font-size: 0.7em; line-height: 14px; }
	.pnl-price { font-size: 0.7em; line-height: 14px; }
	/*.pnl-favorites { position: absolute; width: 40px; height: 40px; top:0; right: 0 }*/
	.pnl-ic-wrapper { transform: scale(0.5) }
}



@media screen and (min-width: 600px) {
	/*.inner { padding: 0 1.0em; padding-top: 1.0em }*/
	/*.grid { margin: 0 0 0 -1.0em }*/
	/*.grid-tile { padding: 0 0 1.0em 1.0em }*/
	.pnl-wrapper { height: 70px }
	.pnl-label, .pnl-price { font-size: 1em; line-height: 20px; }
	/*.pnl-favorites, .pnl-tocart { width: 56px }*/
	.pnl-ic-wrapper { transform: scale(0.75) }
	.pnl-description { padding-left: 1.0em }
}


@media screen and (min-width: 768px) {
	/*.inner { padding: 0 1.5em; padding-top: 1.5em }*/
	/*.grid { margin: 0 0 0 -1.5em }*/
	.grid-tile { padding: 0 0 1.5em 1.5em }
}


@media screen and (min-width: 912px) {
	
}


@media screen and (min-width: 1024px) {
	.grid-tile { width: 33.3333% }
}


@media screen and (min-width: 1300px) {
	.pnl-description { padding-left: 1.5em }
}


@media screen and (min-width: 1440px) {
	/*.inner { width: 1400px; margin: 0 auto }*/
}
		</style>