


<section aria-label="Main content" role="main" class="product-detail top_menu_margin">
  <div itemscope itemtype="http://schema.org/Product">
<!--     <meta itemprop="url" content="http://html-koder-test.myshopify.com/products/tommy-hilfiger-t-shirt-new-york">
    <meta itemprop="image" content="//cdn.shopify.com/s/files/1/1047/6452/products/product_grande.png?v=1446769025"> -->
    <div class="shadow">
      <div class="_cont detail-top">
        <div class="cols">
          <div class="left-col">

            <div class="big">
  <div class="container">
    
    <!-- default start -->
    <section id="default" class="padding-top0">
    <div class="row">
      <!-- <div class="large-12 column"><h3>{{$product -> title}}</h3></div> -->
        <hr>
      <div class="large-5 column">
        <div class="xzoom-container">

          @if($product -> image_1 != null)
          <img class="xzoom" id="xzoom-default" src="{{ asset('images/shop_img/'.$product -> image_1) }}" xoriginal="{{ asset('images/shop_img/'.$product -> image_1) }}" />
          @endif

          <div class="xzoom-thumbs">
            @if($product -> image_1 != null)
            <a href="{{ asset('images/shop_img/'.$product -> image_1) }}">
              <img class="xzoom-gallery" width="80" src="{{ asset('images/shop_img/'.$product -> image_1) }}"  xpreview="{{ asset('images/shop_img/'.$product -> image_1) }}" title="{{$product -> short_descriptio}}">
            </a>
            @endif
            
            @if($product -> image_2 != null)
            <a href="{{ asset('images/shop_img/'.$product -> image_2) }}">
              <img class="xzoom-gallery" width="80" src="{{ asset('images/shop_img/'.$product -> image_2) }}" title="{{$product -> short_descriptio}}">
            </a>
            @endif
            
            @if($product -> image_3 != null)
            <a href="{{ asset('images/shop_img/'.$product -> image_3) }}">
              <img class="xzoom-gallery" width="80" src="{{ asset('images/shop_img/'.$product -> image_3) }}" title="{{$product -> short_descriptio}}">
            </a>
            @endif
            
            @if($product -> image_4 != null)
            <a href="{{ asset('images/shop_img/'.$product -> image_4) }}">
              <img class="xzoom-gallery" width="80" src="{{ asset('images/shop_img/'.$product -> image_4) }}" title="{{$product -> short_descriptio}}">
            </a>
            @endif
          </div>
        </div>        
      </div>
      <div class="large-7 column"></div>
    </div>
    </section>
    <!-- default end -->
  </div>
            </div>
          </div>
          <div class="right-col">

            <div class="row">
              <div class="col-md-9">
                <h1 itemprop="name">{{$product -> title}}</h1>
              </div>
              <div class="col-md-3">
                @if(Auth::guest())
                <a href="{{route('favorite_product', array('product_id'=>$product->id, 'actions'=>'add'))}}"> 
                  <i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%; float: right;"></i>
                </a>
                @else
                  @if($favorite == 1)
                    <a href="{{route('favorite_product', array('product_id'=>$product->id, 'actions'=>'del'))}}">   
                      <i class="fa fa-heart" aria-hidden="true" style="font-size: 250%; float: right;"></i>
                    </a>
                  @elseif($favorite == 0)
                    <a href="{{route('favorite_product', array('product_id'=>$product->id, 'actions'=>'add'))}}"> 
                      <i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%; float: right;"></i>
                    </a>
                  @endif
                @endif
              </div>
            </div>

            <div itemprop="offers">
            <!-- <div itemprop="offers" itemscope itemtype="http://schema.org/Offer"> -->
              <!-- <meta itemprop="priceCurrency" content="USD">
              <link itemprop="availability" href="https://schema.org/InStock"> -->
              <div class="price-shipping">
                <div class="price" id="price-preview">
                <!-- <div class="price" id="price-preview" quickbeam="price" quickbeam-price="800"> -->

                  @if($product->discount != null)
                  @foreach($price_array as $new_price)
                  @if($new_price[0] == $product->id)

                    <!-- <div class="row">
                      <div class="col-xs-6 col-md-6 col-lg-6 "> -->

                      <div class="price_pege">
                      @if($product -> currency == 'GEL')
                        <p title="GEL {{$new_price[1]}}">₾
                      @elseif($product -> currency == 'USD')
                        <p title="USD {{$new_price[1]}}">$
                      @else
                        <p title="{{$product -> currency}} {{$new_price[1]}}">{{$product -> currency}}
                      @endif
                      {{$new_price[1]}}
                        </p>
                      </div>

                      <!-- </div>
                      <div class="col-xs-6 col-md-6 col-lg-6 "> -->

                      <div class="old_price_page">
                      @if($product -> currency == 'GEL')
                        <p title="GEL {{$product -> price}}">₾
                      @elseif($product -> currency == 'USD')
                        <p title="USD {{$product -> price}}">$
                      @else
                        <p title="{{$product -> currency}} {{$product -> price}}">{{$product -> currency}}
                      @endif
                      {{$product -> price}}
                        </p>
                      </div>


                      <!-- </div> -->
                    <!-- </div> -->


                  <!-- <p>
                    {{$product -> currency}}{{$new_price[1]}}
                  </p> -->
                  @endif
                  @endforeach
                  @else
                  
                  <div class="price_pege">
                  @if($product -> currency == 'GEL')
                    <p title="GEL {{$product -> price}}">₾
                  @elseif($product -> currency == 'USD')
                    <p title="USD {{$product -> price}}">$
                  @else
                    <p title="{{$product -> currency}} {{$product -> price}}">{{$product -> currency}}
                  @endif
                  {{$product -> price}}
                    </p>
                  </p>
                  </div>

                  @endif
                </div>
              </div>


              @if($product -> size_style != null && $product -> size != null)
              <div class="swatches">
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="swatch clearfix" data-option-index="0">
                  <div class="header">Size ({{$product -> size_style}})</div>
                  <div data-value="M" class="swatch-element plain m available">
                    <input id="swatch-0-m" type="radio" name="option-0" value="M" checked  />
                    <label for="swatch-0-m">
                      {{$product -> size}}
                      <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                    </label>
                  </div>
                </div>
                </div>
                @endif
                @if($product -> color != null)
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="swatch clearfix" data-option-index="1">
                  <div class="header">Color ({{$product -> color}})</div>
                    @if($product -> color == 'White')
                    <div data-value="Red" class="swatch-element color white available">
                      <div class="tooltip">white</div>
                      <input quickbeam="color" id="swatch-1-white" type="radio" name="option-1" value="white" />
                      <label for="swatch-1-white" style="border-color: grey;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: #d2d2d2;"></span>
                        <!--<span style="background-color: white;"></span>-->
                      </label>
                    </div>
                    @elseif($product -> color == 'Black')
                    <div data-value="Red" class="swatch-element color black available">
                      <div class="tooltip">black</div>
                      <input quickbeam="color" id="swatch-1-black" type="radio" name="option-1" value="black"  />
                      <label for="swatch-1-black" style="border-color: black;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: black;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Brown')
                    <div data-value="Red" class="swatch-element color brown available">
                      <div class="tooltip">brown</div>
                      <input quickbeam="color" id="swatch-1-brown" type="radio" name="option-1" value="brown"  />
                      <label for="swatch-1-brown" style="border-color: brown;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: orange;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Red')
                    <div data-value="Red" class="swatch-element color red available">
                      <div class="tooltip">red</div>
                      <input quickbeam="color" id="swatch-1-red" type="radio" name="option-1" value="red"  />
                      <label for="swatch-1-red" style="border-color: red;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: orange;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Orange')
                    <div data-value="Red" class="swatch-element color orange available">
                      <div class="tooltip">orange</div>
                      <input quickbeam="color" id="swatch-1-orange" type="radio" name="option-1" value="orange"  />
                      <label for="swatch-1-orange" style="border-color: orange;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: orange;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Yellow')
                    <div data-value="Red" class="swatch-element color yellow available">
                      <div class="tooltip">yellow</div>
                      <input quickbeam="color" id="swatch-1-yellow" type="radio" name="option-1" value="yellow"  />
                      <label for="swatch-1-yellow" style="border-color: yellow;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: yellow;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Green')
                    <div data-value="Red" class="swatch-element color green available">
                      <div class="tooltip">green</div>
                      <input quickbeam="color" id="swatch-1-green" type="radio" name="option-1" value="green"  />
                      <label for="swatch-1-green" style="border-color: green;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: green;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Blue')
                    <div data-value="Red" class="swatch-element color blue available">
                      <div class="tooltip">blue</div>
                      <input quickbeam="color" id="swatch-1-blue" type="radio" name="option-1" value="blue"  />
                      <label for="swatch-1-blue" style="border-color: blue;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: blue;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Purple')
                    <div data-value="Red" class="swatch-element color purple available">
                      <div class="tooltip">purple</div>
                      <input quickbeam="color" id="swatch-1-purple" type="radio" name="option-1" value="purple"  />
                      <label for="swatch-1-purple" style="border-color: purple;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: purple;"></span>
                      </label>
                    </div>
                    @elseif($product -> color == 'Grey')
                    <div data-value="Red" class="swatch-element color grey available">
                      <div class="tooltip">grey</div>
                      <input quickbeam="color" id="swatch-1-grey" type="radio" name="option-1" value="grey"  />
                      <label for="swatch-1-grey" style="border-color: grey;">
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                        <span style="background-color: grey;"></span>
                      </label>
                    </div>
                    @endif
                    </div>
                </div>
                </div>
              </div>
              @endif
                
              <div class="clearfix"></div>

              <div>
                Seler - <a href="{{route('seller_page', array('id'=>$product_seller->id))}}">{{$product_seller->name}} {{$product_seller->surname}}</a>
              </div>
              @if($product -> category != null)
              <div>
                Category - {{$product -> category}}
              </div>
              @endif
                <!-- 
                <div class="guide">
                  <a>Size guide</a>
                </div> -->
              </div>
              <!-- </div> -->
              <!-- <form method="post" enctype="multipart/form-data" id="AddToCartForm"> -->
              <!-- <form id="AddToCartForm"> -->
  <!--               <select name="id" id="productSelect" quickbeam="product" class="product-single__variants">
                  <option  selected="selected"  data-sku="" value="7924994501">
                    M / Blue - $800.00 USD
                  </option>
                  <option  data-sku="" value="7924995077">
                    M / Red - $850.00 USD
                  </option>
                  <option  data-sku="" value="7924994437">
                    L / Blue - $850.00 USD
                  </option>
                  <option  data-sku="" value="7924994693">
                    L / Yellow - $850.00 USD
                  </option>
                  <option  data-sku="" value="7924995013">
                    L / Red - $850.00 USD
                  </option>
                  <option  data-sku="" value="7924994373">
                    XL / Blue - $900.00 USD
                  </option>
                  <option  data-sku="" value="7924994629">
                    XL / Yellow - $850.00 USD
                  </option>
                  <option  data-sku="" value="7924830021">
                    XXL / Blue - $950.00 USD
                  </option>
                  <option  data-sku="" value="7924994885">
                    XXL / Red - $850.00 USD
                  </option>
                </select> -->
<!--                 <div class="btn-and-quantity-wrap">
                  <div class="btn-and-quantity">
                    <div id="AddToCart" quickbeam="add-to-cart">
                      @if(Auth::guest())
                      <a href="#"> 
                        <span id="AddToCartText">Add To Favorite</span>
                      </a>
                      @else
                      ddddddd
                      @endif
                    </div>
                  </div>
                </div> -->

              <!-- </form> -->
            </div>
            </div>
            </div>

            <!-- <div class="clearfix"></div> -->
      <div class="container">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">

            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#home" data-toggle="tab">Description</a></li>
              <li><a href="#contacts" data-toggle="tab">contacts</a></li>
            </ul>

            <div class="tab-content">

              <div class="tab-pane active" id="home">
                <div class="table-responsive">
                  <div class="row">
                      <div class="tab-slides">
                        <div id="tab-slide-1" itemprop="description"  class="slide active">
                          {!!$product -> text!!}
                        </div>
                      </div>
                  </div>
                </div><!--/table-resp-->
              </div><!--/tab-pane-->

              <div class="tab-pane" id="contacts">
                <h2>Contacts</h2>
              </div><!--/tab-pane-->

            </div><!--/tab-content-->
          </div><!--/tab-content-->
        </div><!--/tab-content-->
      </div><!--/tab-content-->













<!--                 <div class="container">
                  <div class="row">
                  <div class="col-md-10 col-md-offset-1">
              <div class="tabs">
                <div class="tab-labels">
                  <span data-id="1" class="active">Description</span>
                </div>
                <div class="tab-slides">
                  <div id="tab-slide-1" itemprop="description"  class="slide active">
                    {!!$product -> text!!}
                  </div>
                </div>
                </div>
              </div></div></div> -->
              <!-- 
              <div class="social-sharing-btn-wrapper">
                <span id="social_sharing_btn">Share</span>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    @if(isset($othe_products))
    <aside class="related">
      <div class="_cont">
        <h2>You might also like</h2>
        <div class="collection-list cols-4" id="collection-list" data-products-per-page="4">
        @foreach($othe_products as $other_product)
          <a class="product-box" href="{{route('shop_page', array('title'=>$other_product->url_title))}}">
            <span class="img">
              <span style="background-image: url('{{ asset('images/shop_img/'.$other_product -> image_1) }}')" class="i first">
              </span>
              <span class="i second" style="background-image: url('{{ asset('images/shop_img/'.$other_product -> image_1) }}')">
              </span>
            </span>
            <span class="text">
              <strong href="{{route('shop_page', array('title'=>$other_product->url_title))}}">{{$other_product -> title}}</strong>
              <span>
              @if($other_product -> currency == 'GEL')
              ₾
              @elseif($other_product -> currency == 'USD')
              $
              @else
              {{$other_product -> currency}}
              @endif
              {{$other_product -> price}}
              </span>
              <div class="variants">
                @if($other_product -> size != null && $other_product -> size_style != null)
                <div class="variant">
                  <div class="var m available">
                    <div class="t">{{$other_product -> size}}({{$other_product -> size_style}})</div>
                  </div>
                </div>
                @endif
                @if($other_product -> color != null)
                <div class="variant">
                      @if($other_product -> color == 'White')
                      <div class="var color white available">
                        <div class="c" style="background-color: white;"></div>
                      </div>
                      @elseif($other_product -> color == 'Black')
                      <div class="var color black available">
                        <div class="c" style="background-color: black;"></div>
                      </div>
                      @elseif($other_product -> color == 'Brown')
                      <div class="var color brown available">
                        <div class="c" style="background-color: brown;"></div>
                      </div>
                      @elseif($other_product -> color == 'Red')
                      <div class="var color red available">
                        <div class="c" style="background-color: red;"></div>
                      </div>
                      @elseif($other_product -> color == 'Orange')
                      <div class="var color orange available">
                        <div class="c" style="background-color: orange;"></div>
                      </div>
                      @elseif($other_product -> color == 'Yellow')
                      <div class="var color yellow available">
                        <div class="c" style="background-color: yellow;"></div>
                      </div>
                      @elseif($other_product -> color == 'Green')
                      <div class="var color green available">
                        <div class="c" style="background-color: green;"></div>
                      </div>
                      @elseif($other_product -> color == 'Blue')
                      <div class="var color blue available">
                        <div class="c" style="background-color: blue;"></div>
                      </div>
                      @elseif($other_product -> color == 'Purple')
                      <div class="var color purple available">
                        <div class="c" style="background-color: purple;"></div>
                      </div>
                      @elseif($other_product -> color == 'Grey')
                      <div class="var color grey available">
                        <div class="c" style="background-color: grey;"></div>
                      </div>
                      @endif
                </div>
                @endif
              </div>
            </span>
          </a>
          @endforeach
        </div>
        <div class="more-products" id="more-products-wrap">
          <a href="{{route('shop_list')}}"><span id="more-products" data-rows_per_page="1">More products</span></a>
        </div>
      </div>
    </aside>
    @endif
  </div>
  
  
  

<script src='https://unpkg.com/xzoom/dist/xzoom.min.js'></script>

  

  
  
  
  
  <script>
      (function ($) {
    $(document).ready(function() {
        $('.xzoom, .xzoom-gallery').xzoom({zoomWidth: 400, title: true, tint: '#333', Xoffset: 15});
        $('.xzoom2, .xzoom-gallery2').xzoom({position: '#xzoom2-id', tint: '#ffa200'});
        $('.xzoom3, .xzoom-gallery3').xzoom({position: 'lens', lensShape: 'circle', sourceClass: 'xzoom-hidden'});
        $('.xzoom4, .xzoom-gallery4').xzoom({tint: '#006699', Xoffset: 15});
        $('.xzoom5, .xzoom-gallery5').xzoom({tint: '#006699', Xoffset: 15});

        //Integration with hammer.js
        var isTouchSupported = 'ontouchstart' in window;

        if (isTouchSupported) {
            //If touch device
            $('.xzoom, .xzoom2, .xzoom3, .xzoom4, .xzoom5').each(function(){
                var xzoom = $(this).data('xzoom');
                xzoom.eventunbind();
            });
            
            $('.xzoom, .xzoom2, .xzoom3').each(function() {
                var xzoom = $(this).data('xzoom');
                $(this).hammer().on("tap", function(event) {
                    event.pageX = event.gesture.center.pageX;
                    event.pageY = event.gesture.center.pageY;
                    var s = 1, ls;
    
                    xzoom.eventmove = function(element) {
                        element.hammer().on('drag', function(event) {
                            event.pageX = event.gesture.center.pageX;
                            event.pageY = event.gesture.center.pageY;
                            xzoom.movezoom(event);
                            event.gesture.preventDefault();
                        });
                    }
    
                    xzoom.eventleave = function(element) {
                        element.hammer().on('tap', function(event) {
                            xzoom.closezoom();
                        });
                    }
                    xzoom.openzoom(event);
                });
            });

        $('.xzoom4').each(function() {
            var xzoom = $(this).data('xzoom');
            $(this).hammer().on("tap", function(event) {
                event.pageX = event.gesture.center.pageX;
                event.pageY = event.gesture.center.pageY;
                var s = 1, ls;

                xzoom.eventmove = function(element) {
                    element.hammer().on('drag', function(event) {
                        event.pageX = event.gesture.center.pageX;
                        event.pageY = event.gesture.center.pageY;
                        xzoom.movezoom(event);
                        event.gesture.preventDefault();
                    });
                }

                var counter = 0;
                xzoom.eventclick = function(element) {
                    element.hammer().on('tap', function() {
                        counter++;
                        if (counter == 1) setTimeout(openfancy,300);
                        event.gesture.preventDefault();
                    });
                }

                function openfancy() {
                    if (counter == 2) {
                        xzoom.closezoom();
                        $.fancybox.open(xzoom.gallery().cgallery);
                    } else {
                        xzoom.closezoom();
                    }
                    counter = 0;
                }
            xzoom.openzoom(event);
            });
        });
        
        $('.xzoom5').each(function() {
            var xzoom = $(this).data('xzoom');
            $(this).hammer().on("tap", function(event) {
                event.pageX = event.gesture.center.pageX;
                event.pageY = event.gesture.center.pageY;
                var s = 1, ls;

                xzoom.eventmove = function(element) {
                    element.hammer().on('drag', function(event) {
                        event.pageX = event.gesture.center.pageX;
                        event.pageY = event.gesture.center.pageY;
                        xzoom.movezoom(event);
                        event.gesture.preventDefault();
                    });
                }

                var counter = 0;
                xzoom.eventclick = function(element) {
                    element.hammer().on('tap', function() {
                        counter++;
                        if (counter == 1) setTimeout(openmagnific,300);
                        event.gesture.preventDefault();
                    });
                }

                function openmagnific() {
                    if (counter == 2) {
                        xzoom.closezoom();
                        var gallery = xzoom.gallery().cgallery;
                        var i, images = new Array();
                        for (i in gallery) {
                            images[i] = {src: gallery[i]};
                        }
                        $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                    } else {
                        xzoom.closezoom();
                    }
                    counter = 0;
                }
                xzoom.openzoom(event);
            });
        });

        } else {
            //If not touch device

            //Integration with fancybox plugin
            $('#xzoom-fancy').bind('click', function(event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                $.fancybox.open(xzoom.gallery().cgallery, {padding: 0, helpers: {overlay: {locked: false}}});
                event.preventDefault();
            });
           
            //Integration with magnific popup plugin
            $('#xzoom-magnific').bind('click', function(event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                var gallery = xzoom.gallery().cgallery;
                var i, images = new Array();
                for (i in gallery) {
                    images[i] = {src: gallery[i]};
                }
                $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                event.preventDefault();
            });
        }
    });
})(jQuery);
  </script>




<style type="text/css">
  .price p{
    margin-bottom: 10px;
  }
  .old_price_page p{ 
    text-decoration:line-through;
    color: #696969;
  }
  .old_price_page{
    font-size: 50%;
    margin-top: -25%;
    margin-bottom: -15%;
    color: #696969;
  }
  .price_pege{

  }
</style>