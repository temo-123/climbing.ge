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

                    <tabs_component 
                        @if(isset($table_1)) :table_1="{{$table_1}}" @endif
                        @if(isset($table_2)) :table_2="{{$table_2}}" @endif 
                        @if(isset($table_3)) :table_3="{{$table_3}}" @endif 
                        @if(isset($table_4)) :table_4="{{$table_4}}" @endif 

                        @if(isset($table_1_name)) :table_1_name="{{ json_encode($table_1_name) }}" @endif
                        @if(isset($table_2_name)) :table_2_name="{{ json_encode($table_2_name) }}" @endif
                        @if(isset($table_3_name)) :table_3_name="{{ json_encode($table_3_name) }}" @endif
                        @if(isset($table_4_name)) :table_4_name="{{ json_encode($table_4_name) }}" @endif

                        @if(isset($table_1_add_url)) table_1_add_url="{{ route($table_1_add_url, array('category'=>$table_1_add_category)) }}" @endif
                        @if(isset($table_2_add_url)) table_2_add_url="{{ route($table_2_add_url, array('category'=>$table_2_add_category)) }}" @endif
                        @if(isset($table_3_add_url)) table_3_add_url="{{ route($table_3_add_url, array('category'=>$table_3_add_category)) }}" @endif
                        @if(isset($table_4_add_url)) table_4_add_url="{{ route($table_4_add_url, array('category'=>$table_4_add_category)) }}" @endif
                        >
                    </tabs_component>

                </div>
            </div>
        </div>
    </div>
@endsection