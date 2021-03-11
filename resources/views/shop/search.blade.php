@extends('shop.layouts.app')

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

                <!-- <search-component></search_component> -->
                
                <p> The Search results for your query <b> {{$query}} </b> are :</p>
                
                    
                <section class="inner">
                    <ul class="grid">
                        @if(isset($products))
                        @forelse($products as $product)
                        <li class="grid-tile">
                            <div class="item">
                                <a href="{{route('shop_page', array('title'=>$product['url_title']))}}">
                                    <div class="item-img">
                                        @if ($product['image'])
                                            <img src="{{ asset('images/product_img/'.$product['image']) }}" alt="">
                                        @endif
                                    </div>
                                </a>
                                <div class="item-pnl product-image">
                                    @if($product[0][0]->discount != null)
                                    <div class="discount-percent-badge discount-badge-fourty">-{{$product[0][0]->discount}}%</div>
                                    @endif
                                    <div class="pnl-wrapper">
                                        <div class="pnl-description">
                                            <a href="{{route('shop_page', array('title'=>$product['url_title']))}}">
                                                <span class="pnl-label"><h2>{{$product[0][0] -> title}}</h2></span>
                                            </a>
                                            @if($product['discount'] != null)
                                                @foreach($price_array as $new_price)
                                                    @if($new_price['product_id'] == $product['id'])
                                                    <div class="old_price">
                                                        <span class="pnl-price ">
                                                            {{$product['currency']}}{{$product['price']}}
                                                        </span>
                                                    </div>

                                                    <div class="price">
                                                        <span class="pnl-price price">
                                                            {{$product['currency']}}{{$new_price['new_price']}}
                                                        </span>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            @else
                                            <div class="price">
                                                <span class="pnl-price price">{{$product['currency']}}{{$product['price']}}</span>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="pnl-favorites">
                                            <span class="pnl-ic">
                                                @if(Auth::guest())
                                                <a href="{{route('favorite_product', array('product_id'=>$product['id'], 'actions'=>'add'))}}">
                                                    <i class="fa fa-heart-o" aria-hidden="true" style="font-size: 120%; margin-right: 0.5em; margin-top: 0.8em;"></i>
                                                </a>
                                                @else
                                                    @foreach($favorite_array as $favorite)
                                                        @if($favorite["product_id"] == $product['id'])
                                                            @if($favorite["users_favorite"] == 1)
                                                                <a href="{{route('favorite_product', array('product_id'=>$product['id'], 'actions'=>'del'))}}">
                                                                    <i class="fa fa-heart" aria-hidden="true" style="font-size: 250%;"></i>
                                                                </a>
                                                            @elseif($favorite["users_favorite"] == 0)
                                                                <a href="{{route('favorite_product', array('product_id'=>$product['id'], 'actions'=>'add'))}}">
                                                                    <i class="fa fa-heart-o" aria-hidden="true" style="font-size: 250%;"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </li>
                        @if($num++ % 3 == 0)
                            <li class="clearfix"></li>
                        @endif
                        @empty
                            <h6>{{$site->shop_empty}} </h6>
                            <img class="article_404" src="{{asset('images/404/empty_page/11.jpg')}}">
                        @endforelse
                        @endif
                    </ul>
                </section>
                
            </div>
        </div>
        
    </div>
@endsection