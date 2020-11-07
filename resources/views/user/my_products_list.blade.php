@extends('user.layouts.app')

@section('content')
<div class="container top_menu_margin h-recent-work">
    
	<div class="row">
  		<div class="col-sm-3">
              
            @include('user.components.left_block')
          
        </div>
            <div class="col-sm-9">
                @component('user.components.breadcrumb')    
                    @if(isset($page_name) && isset($table_1) && isset($table_2))
                        @slot('title') {{$page_name}} list @endslot
                    @elseif(isset($table_1_name))
                        @if(isset($table_1_count))
                            @slot('title') {{$table_1_name}} list ({{$table_1_count}}) @endslot
                        @else
                            @slot('title') {{$title}} list ({{$title}}) @endslot
                        @endif
                    @endif
                    
                    @slot('parent') Home @endslot
                    @slot('active') {{$active}} @endslot
                @endcomponent

                @if(session('status'))
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('status') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('error') }}
                </div>
                @endif

                <div class="row list-group">
                    <div class="add_buttom">
                        <a href="{{ route('productsAdd') }}" class="btn btn-primary pull-left" type="submit">New </a>
                    </div>
                </div>

                <ul class="row list-group">
                    @forelse($products as $product)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2 col-md-2">
                                <!-- <img src="http://placehold.it/120" class="img-circle img-responsive" alt="" /> -->
                                <img src="{{ asset('images/shop_img/'.$product -> image_1) }}" class="img-circle img-responsive product_table_img">
                            </div>
                            <div class="col-xs-10 col-md-10">
                                <div>
                                    <a href="http://bootsnipp.com/BhaumikPatel/snippets/Obgj"><h3>{{$product -> title}}</h3></a>
                                </div>
                                <!-- <div class="comment-text">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh
                                    euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
                                </div> -->
                                <div class="action">
                                	<div style="float: left;">
                                        @if($product -> approved == 1)
	                                    <button type="button" class="btn btn-success btn-xs" title="Approved">
	                                        <span class="glyphicon glyphicon-ok"></span>
	                                    </button>
                                        @elseif($product -> approved == 1 || $product -> approved == NULL)
                                        <button type="button" class="btn btn-danger btn-xs" title="Approved">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                        @endif
                                    </div>
                                    
                                	<div style="float: right;">
	                                    <a type="button" class="btn btn-primary btn-xs" href="{{route('productsEdit',[$product->id])}}">
	                                        <span class="glyphicon glyphicon-pencil"></span>
                                        </a>
                                        <form method="post" action="{{route('productsDel',[$product->id])}}">
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="form-group">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">    
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                            </div>
                                        </form>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <h2>You don't have products</h2>
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
