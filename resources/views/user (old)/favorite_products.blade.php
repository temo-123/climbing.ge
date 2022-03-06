@extends('user.layouts.app')

@section('content')
    <div class="container top_menu_margin">
        <div class="bootstrap snippet">
            <div class="row">
                <div class="col-sm-3">
                    @include('user.components.left_block')
                </div>
                <div class="col-sm-9">
                    @component('user.components.breadcrumb')
                        @slot('title') {{$page_name}} list @endslot
                        
                        @slot('parent') Home @endslot
                        @slot('active') {{$active}} @endslot
                    @endcomponent
                    
                    
                    <div class="container">
                        <div class="row">
                            @foreach ($favorite_products as $product)
                            <div class="col-md-4">
                                <figure class="card card-product">
                                    <div class="img-wrap"><img src="{{ asset('images/product_img/'.$product[0]['image']) }}"></div>
                                    <figcaption class="info-wrap">
                                            <h4 class="title"><a href="{{route('shop_page', array('title'=>$product[0]['url_title']))}}">{{ $product[0][0][0]->title }}</a></h4>
                                    </figcaption>
                                    <div class="bottom-wrap">
                                        <div class="price-wrap h5">
                                            <del class="price-old">$1980</del> <span class="price-new">{{ $product[0]['price'] }}</span> 
                                        </div>
                                    </div>
                                </figure>
                            </div>
                            @endforeach
                            
                        </div>
                    </div>        
                    
                   
                </div>
            </div>
        </div>
    </div>
@endsection
