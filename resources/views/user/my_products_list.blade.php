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

                <product_list
                    product_get_route = ""
                    product_name = ""
                    product_add_url = {{ route('productAddPage') }}
                    product_edit_url = ""
                    product_del_url = ""

                    {{-- product_page = {{ route('shop_page', array('title' => $product->url_title )) }} --}}
                >
                </product_list>

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
