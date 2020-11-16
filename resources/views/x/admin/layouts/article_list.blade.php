@extends('admin.layouts.app_admin')

@section('content')
    <div class="container">
        @component('admin.components.breadcrumb')
        
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
                    @include('admin.components.left_block')
                </div>
                <div class="col-sm-9">
                      
                    <ul class="nav nav-tabs" id="myTab">
                    @if(isset($table_1))
                        <li class="active"><a href="#table_1" data-toggle="tab">{{$table_1_name}}</a></li>
                    @endif
                    @if(isset($table_2))
                        <li><a href="#table_2" data-toggle="tab">{{$table_2_name}}</a></li>
                    @endif    
                    @if(isset($table_3))
                        <li><a href="#table_3" data-toggle="tab">{{$table_3_name}}</a></li>
                    @endif    
                    @if(isset($table_4))
                        <li><a href="#table_4" data-toggle="tab">{{$table_4_name}}</a></li>
                    @endif
                    </ul>

                    <!-- <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#test_table_1" data-toggle="tab">table_1_name</a></li>
                        <li><a href="#test_table_2" data-toggle="tab">$table_2_name</a></li>
                        <li><a href="#test_table_3" data-toggle="tab">$table_3_name</a></li>
                    </ul> -->

                    <div class="tab-content">
                        <div class="tab-pane active" id="table_1">
                        @if(isset($table_1))
                            @include('admin.components.table_1')
                        @endif
                        </div>
                        <div class="tab-pane" id="table_2">
                        @if(isset($table_2))
                            @include('admin.components.table_2')
                        @endif
                        </div>
                        <div class="tab-pane" id="table_3">
                        @if(isset($table_3))
                            @include('admin.components.table_3')
                        @endif
                        </div>
                        <div class="tab-pane" id="table_4"> 
                        @if(isset($table_4))
                            @include('admin.components.table_4')
                        @endif
                        </div>
<!--                         <div class="tab-pane active" id="test_table_1">
                          <h1>table 1</h1>
                        </div>
                        
                        <div class="tab-pane" id="test_table_2">
                            <h2>table 2</h2>
                        </div>
                         
                        <div class="tab-pane" id="test_table_3">
                            <h2>table 3</h2>
                        </div> -->
                    </div>         
                </div>
            </div>
        </div>
    </div>
@endsection