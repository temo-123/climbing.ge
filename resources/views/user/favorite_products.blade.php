@extends('user.layouts.app')

@section('content')
<div class="container top_menu_margin h-recent-work">
	<div class="row">
  		<div class="col-sm-3">
              
            @include('user.components.left_block')
          
        </div>
            <div class="col-sm-9">
                <ul class="list-group">
                    @forelse($my_favorite_products as $product)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2 col-md-2">
                                <img src="{{ asset('images/shop_img/'.$product[0][0] -> image_1) }}" class="img-circle img-responsive product_table_img">
                            </div>
                            <div class="col-xs-10 col-md-10">
                                <div>
                                    <a href="http://bootsnipp.com/BhaumikPatel/snippets/Obgj"><h3>{{$product[0][0] -> title}}</h3></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <h2>You don't have favotite products</h2>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>




<style type="text/css">
.product_table_img{
    height: auto;
    width: 120%;
}
</style>
@endsection
