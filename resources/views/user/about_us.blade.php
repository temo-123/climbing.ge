@extends('user.layouts.app')

@section('content')
    <div class="container top_menu_margin">
        @component('user.components.breadcrumb')
        
            @if(isset($page_name)&&isset($table_1)&&isset($table_2))
                @slot('title') {{$page_name}} list @endslot
            @elseif(isset($table_1_name))
                @if(isset($table_1_count))
                    @slot('title') {{$table_1_name}} list ({{$table_1_count}}) @endslot
                @else
                    @slot('title') {{$table_1_name}} list @endslot
                @endif
            @endif
            
            @slot('parent') Home @endslot
            @slot('active') {{$active}} @endslot
        @endcomponent

        <div class="bootstrap snippet">
            <div class='col-md-12'>
                <hr />
            </div>
            <div class="row">
                <div class="col-sm-3">
                    @include('user.components.left_block')
                </div>
                <div class="col-sm-9">
                      
                    

                </div>
            </div>
        </div>
    </div>
@endsection