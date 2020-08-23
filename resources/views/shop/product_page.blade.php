@extends('shop.layouts.app')

@section('content')
<section aria-label="Main content" role="main" class="product-detail top_menu_margin">
  <div itemscope itemtype="http://schema.org/Product">
    <div class="shadow">
      <div class="_cont detail-top">
        <div class="cols">
          <div class="left-col">
            <div class="big">
              <div class="container">
                <section id="default" class="padding-top0">
                  <div class="row">
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
              </div>
            </div>
          </div>
          <div class="right-col">
            <!-- <div class="row"> -->
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

              <div itemprop="offers">
                <div class="price-shipping">
                  <div class="price" id="price-preview">

                    @if($product->discount != null)
                    @foreach($price_array as $new_price)
                    @if($new_price[0] == $product->id)

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
                    
                  <!-- <div class="clearfix"></div> -->

                  <div class="row">
                    Seler - <a href="{{route('seller_page', array('id'=>$product_seller->id))}}">{{$product_seller->name}} {{$product_seller->surname}}</a>
                  </div>
                  @if($product -> category != null)
                  <div>
                    Category - {{$product -> category}}
                  </div>
                  @endif
                  </div>

                </div>
              </div>
            <!-- </div> -->
          </div>

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
          </div>

          
        </div>
      </div>
    </div>
      @endsection