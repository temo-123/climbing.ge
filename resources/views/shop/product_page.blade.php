@extends('shop.layouts.app')

@section('content')

@section('title',  $product->title)
@section('meta_description',  $product->short_description)
@section('meta_keyword',  $product->title)
@section('meta_img',  asset('images/product_img/'.$first_product_images->image))

@section('created_at',  $product->created_at)
@section('updated_at',  $product->updated_at)

<section aria-label="Main content" role="main" class="product-detail top_menu_margin">
  <div class="container">
      <div class="_cont detail-top">
        <div class="cols">
          <div class="left-col">

            <div class="big">
              <div class="container">
                
                <section id="default" class="padding-top0">
                <div class="row">
                  <div class="large-5 column">
                    <div class="xzoom-container">
                      <img class="xzoom" id="xzoom-default" src="{{ asset('images/product_img/'.$first_product_images->image) }}" xoriginal="{{ asset('images/product_img/'.$first_product_images->image) }}" />
                      
                      <div class="xzoom-thumbs">
                        @foreach ($product_images as $product_image)
                        <a href="{{ asset('images/shop_img/'.$product_image -> image_1) }}">
                          <img class="xzoom-gallery" width="80" src="{{ asset('images/product_img/'.$product_image->image) }}"  xpreview="{{ asset('images/product_img/'.$product_image->image) }}" title="{{$product_image->short_descriptio}}">
                        </a>
                        @endforeach
                      </div>
                    </div>        
                  </div>
                  <div class="large-7 column"></div>
                </div>
                </section>
              </div>
            </div>
          </div>
          <div class="right-col">

            <div class="row">
              <div class="col-md-9 product_page_title">
                <h1>{{$product -> title}}</h1>
              </div>
            </div>
            <div class="row">

              <div class="col-md-10">
                <div class="price-shipping">
                  <div class="price" id="price-preview">

                    @if($global_product->discount != null)
                    @foreach($price_array as $new_price)
                    @if($new_price[0] == $global_product->id)

                        <div class="price_pege">
                        @if($global_product -> currency == 'GEL')
                          <p title="GEL {{$new_price[1]}}">₾
                        @elseif($global_product -> currency == 'USD')
                          <p title="USD {{$new_price[1]}}">$
                        @else
                          <p title="{{$global_product -> currency}} {{$new_price[1]}}">{{$global_product -> currency}}
                        @endif
                        {{$new_price[1]}}
                          </p>
                        </div>

                        <div class="old_price_page">
                        @if($global_product -> currency == 'GEL')
                          <p title="GEL {{$global_product -> price}}">₾
                        @elseif($global_product -> currency == 'USD')
                          <p title="USD {{$global_product -> price}}">$
                        @else
                          <p title="{{$global_product -> currency}} {{$global_product -> price}}">{{$global_product -> currency}}
                        @endif
                        {{$global_product -> price}}
                          </p>
                        </div>

                    @endif
                    @endforeach
                    @else
                    
                    <div class="price_pege">
                      @if($global_product -> currency == 'GEL')
                        <p title="GEL {{$global_product -> price}}">₾
                      @elseif($global_product -> currency == 'USD')
                        <p title="USD {{$global_product -> price}}">$
                      @else
                        <p title="{{$global_product -> currency}} {{$global_product -> price}}">{{$global_product -> currency}}
                      @endif
                      {{$global_product -> price}}
                      </p>
                    </div>

                    @endif
                  </div>
                </div>
              </div>

              <div class="col-md-2">
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
                {{-- </div> --}}
              </div>

            </div>

            <div class="row">
              @if($global_product -> size != null)
                <div class="col">
                  <div class="swatch clearfix" data-option-index="0">
                    {{-- <div class="header">Size ({{$global_product -> size_style}})</div> --}}
                    <div data-value="M" class="swatch-element plain m available">
                      <input id="swatch-0-m" type="radio" name="option-0" value="M" checked  />
                      <label for="swatch-0-m">
                        {{$global_product -> size}}
                        <img class="crossed-out" src="//cdn.shopify.com/s/files/1/1047/6452/t/1/assets/soldout.png?10994296540668815886" />
                      </label>
                    </div>
                  </div>
                </div>
              @endif

              @if($global_product -> color != null)
                <div class="col">
                  <div class="swatch clearfix" data-option-index="1">
                    @if($global_product -> color == 'White')
                    <div data-value="Red" class="swatch-element color white available">
                      <div class="tooltip">white</div>
                      <label for="swatch-1-white" style="border-color: black;">
                        <span style="background-color: white;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Black')
                    <div data-value="Red" class="swatch-element color black available">
                      <div class="tooltip">black</div>
                      <label for="swatch-1-black" style="border-color: black;">
                        <span style="background-color: black;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Brown')
                    <div data-value="Red" class="swatch-element color brown available">
                      <div class="tooltip">brown</div>
                      <label for="swatch-1-brown" style="border-color: brown;">
                        <span style="background-color: brown;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Red')
                    <div data-value="Red" class="swatch-element color red available">
                      <div class="tooltip">red</div>
                      <label for="swatch-1-red" style="border-color: red;">
                        <span style="background-color: red;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Orange')
                    <div data-value="Red" class="swatch-element color orange available">
                      <div class="tooltip">orange</div>
                      <label for="swatch-1-orange" style="border-color: orange;">
                        <span style="background-color: orange;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Yellow')
                    <div data-value="Red" class="swatch-element color yellow available">
                      <div class="tooltip">yellow</div>
                      <label for="swatch-1-yellow" style="border-color: yellow;">
                        <span style="background-color: yellow;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Green')
                    <div data-value="Red" class="swatch-element color green available">
                      <div class="tooltip">green</div>
                      <label for="swatch-1-green" style="border-color: green;">
                        <span style="background-color: green;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Blue')
                    <div data-value="Red" class="swatch-element color blue available">
                      <div class="tooltip">blue</div>
                      <label for="swatch-1-blue" style="border-color: blue;">
                        <span style="background-color: blue;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Purple')
                    <div data-value="Red" class="swatch-element color purple available">
                      <div class="tooltip">purple</div>
                      <label for="swatch-1-purple" style="border-color: purple;">
                        <span style="background-color: purple;"></span>
                      </label>
                    </div>
                    @elseif($global_product -> color == 'Grey')
                    <div data-value="Red" class="swatch-element color grey available">
                      <div class="tooltip">grey</div>
                      <label for="swatch-1-grey" style="border-color: grey;">
                        <span style="background-color: grey;"></span>
                      </label>
                    </div>
                    @endif
                  </div>
                </div>
              @endif
            </div>

            {{-- </div> --}}
            <div class="row">
              @if ($global_product->quantity != NULL && $global_product->quantity <= 3)
                <h2 style=""><strong style="color: #f70000; text-align: center !important;">Limited quantity ( @if($global_product->quantity == 1) 1 @else 3 > @endif )</strong></h2>
              @endif
            </div>
          </div>

        </div>
      </div>
  </div>

    <div class="clearfix"></div>

      <div class="container">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">

            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#home" data-toggle="tab">Description</a></li>
              <li><a href="#contacts" data-toggle="tab">contacts</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="home">

                @if($global_product->category_id != null)
                <p class="product_p"><strong>Category</strong> - @if($page_locale == "ru")
                                    {{ $category -> ru_name }}
                                  @elseif($page_locale == "ka")
                                    {{ $category -> ka_name }}
                                  @else
                                    {{ $category -> us_name}}
                                  @endif</p>
                @endif

                @if($global_product->material != null)
                <p class="product_p"><strong>Material</strong> - {{$global_product->material}}</p>
                @endif

                <p class="product_p"><strong>Seler</strong> - {{$product_seller->name}} {{$product_seller->surname}}</p>
                <hr>
                {!!$product -> text!!}
              </div>
              <div class="tab-pane" id="contacts">
                <p class="product_p"><strong>Email</strong> - {{ $site->email }}</p>
                <p class="product_p"><strong>Phone number</strong> - {{ $site->number }}</p>
                <hr>
                @if($page_locale == "ru")
                    {!! $site -> shop_description_ru !!}
                @elseif($page_locale == "ka")
                    {!! $site -> shop_description_ka !!}
                @else
                    {!! $site -> shop_description !!}
                @endif
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    </div>
    
    @if(isset($othe_products))
      <div class="container">
        <div class="row related">
          <h2>You might also like</h2>
          @foreach($othe_products as $other_product)
          {{-- {{ dd($other_product['price']) }} --}}
          <div class="collection-list col-lg-4 col-md-4 col-sm-4">
              <a class="product-box" href="{{route('shop_page', array('title'=>$other_product['url_title']))}}">
                <span class="img">
                  <span style="background-image: url('{{ asset('images/product_img/'.$other_product['image']) }}')" class="i first">
                  </span>
                  <span class="i second" style="background-image: url('{{ asset('images/product_img/'.$other_product['image']) }}')">
                  </span>
                </span>
                <span class="text">
                  <strong href="{{route('shop_page', array('title'=>$other_product['url_title']))}}">{{ $other_product[0][0]['title'] }}</strong>
                  @if($other_product['discount'] != null)
                    @foreach($other_product_price_array as $new_price)
                      @if($new_price['product_id'] == $other_product['id'])
                      <div class="old_price">
                        <span>
                          {{ $other_product['currency']}} {{$other_product['price']}}
                        </span>
                      </div>
                      <span>
                        {{  $other_product['currency'] }} {{$new_price['new_price']}}
                      </span>
                      @endif
                    @endforeach
                  @else
                    <span>{{ $other_product['currency'] }} {{$other_product['price']}}</span>
                  @endif
                  <div class="variants">
                    @if($other_product['price'] != null)
                    <div class="variant">
                      <div class="var m available">
                        <div>{{$other_product['size']}}</div>
                      </div>
                    </div>
                    @endif
                    @if($other_product['color'] != null)
                    <div class="variant">
                          @if($other_product['color'] == 'White')
                          <div class="var color white available">
                            <div class="c" style="background-color: white;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Black')
                          <div class="var color black available">
                            <div class="c" style="background-color: black;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Brown')
                          <div class="var color brown available">
                            <div class="c" style="background-color: brown;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Red')
                          <div class="var color red available">
                            <div class="c" style="background-color: red;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Orange')
                          <div class="var color orange available">
                            <div class="c" style="background-color: orange;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Yellow')
                          <div class="var color yellow available">
                            <div class="c" style="background-color: yellow;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Green')
                          <div class="var color green available">
                            <div class="c" style="background-color: green;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Blue')
                          <div class="var color blue available">
                            <div class="c" style="background-color: blue;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Purple')
                          <div class="var color purple available">
                            <div class="c" style="background-color: purple;"></div>
                          </div>
                          @elseif($other_product['color'] == 'Grey')
                          <div class="var color grey available">
                            <div class="c" style="background-color: grey;"></div>
                          </div>
                          @endif
                    </div>
                    @endif
                  </div>
                </span>
              </a>
          </div>
          @endforeach
        </div>
        <div class="row">
          <div class="more-products" id="more-products-wrap">
            <a href="{{route('shop_index')}}"><span id="more-products" data-rows_per_page="1">All products</span></a>
          </div>
        </div>
      </div>
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
@endsection