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
            @slot('active') {{$active ?? ""}} @endslot
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
                      
                    <div class="panel panel-default target">
                        <div class="panel-heading" contenteditable="false">Pets I Own</div>
                            <div class="panel-body">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="thumbnail">
                                            <a href="{{ route('galleryAdd') }}">
                                                <img alt="300x200" src="{{ asset('images/gallery_img/function_img/add_image.png') }}">
                                            </a>
                                        </div>
                                    </div>

                                    @foreach($table_1 as $tab_1)
                                    <div class="col-md-4">
                                        <div class="thumbnail">

                                            <a href="{{route('galleryEdit',[$tab_1->id])}}">
                                                <img alt="300x200" src="{{ asset('images/gallery_img/'.$tab_1 -> image) }}" style="height: 10em;">
                                            </a>

                                            <div class="caption">
                                                <div class="row">
                                                    <h3> {{$tab_1->title}} </h3>
                                                </div>
                                                <div class="row">
                                                    <form method="post" action="{{route('galleryDel',[$tab_1->id])}}">
                                                        {{ method_field('DELETE') }}
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">    
                                                                Del
                                                            </button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
