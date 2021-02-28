@extends('shop.layouts.app')

@section('content')
@section('title', $title)

@section('meta_title', 'Climbing store')

@if($site->shop_description != null)
@section('meta_keyword', $site->shop_description)
@else
@section('meta_keyword', 'Climbing gear shop In Georgia')
@endif

@section('meta_description', 'Gear store in Georgia')

@section('meta_img', (asset('images/meta_img/shop.jpg')))

<div class="container top_menu_margin h-recent-work">

    <h1 class="page_title">Products</h1>

    @if($site->shop_description != null)
    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
    <h6>{!! $site->shop_description !!}</h6>
    @endif

    {{-- <div class="col-sm-3">
        <!--left col-->
       
        <div class="panel panel-default">
            <div class="panel-heading">Filters</div>

            @if(isset($tags) && $tags_count != NULL)
            <div class="panel-body">
                <div class="index_gallery_button" >
                    <button class="filter-button" data-filter="all">All</button>
                </div>
                <div class="index_gallery_button" >
                @if(isset($tags))
                @foreach($tags as $k=>$tag)

                <button class="filter-button" data-filter="{!! $tag->id !!}">{!! $tag->category !!}</button>

                @endforeach
                @endif
                </div>
            </div>
            @endif
        </div>
        
        <div class="panel panel-default">
            <div class="panel-body"><a href="{{route('shop_index')}}">@lang('shop.shop menu products')</a></div>
            <div class="panel-body"><a href="{{route('shop_index')}}">@lang('shop.shop menu categoris')</a></div>
        </div>
    </div> --}}

    <div class="col-sm-12">
        <section class="inner">
            <section class="portfolio" id="portfolio">
                <!-- <div class="container-fluid"> -->

                    <div class="layout">
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
                                    <div class="bar"><i class="fa fa-exclamation-triangle"></i></div>
                                    <h6>{{$site->shop_empty}} </h6>
                                    <img class="article_404" src="{{asset('images/404/empty_page/11.jpg')}}">
                                @endforelse
                                @endif
                            </ul>
                        </section>
                    </div>

                <!-- </div> -->
            </section>
        </section>
    </div>
</div>
        
@endsection
