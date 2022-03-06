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
                        @slot('active') {{$active ?? ""}} @endslot
                    @endcomponent

                    <div class="panel panel-default target">
                            <div class="panel-body">
                                {{-- <div class="row">

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
                                                    <form method="post" action="{{route('galleryDel',[$tab_1->id])}}">
                                                        {{ method_field('DELETE') }}
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">    
                                                            Del
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="row">
                                                    <h4> {{$tab_1->title}} </h4>
                                                </div>
                                                <div class="row">
                                                    @if ($tab_1->published == 1)
                                                        <h5>Public</h5>
                                                    @else
                                                        <h5>No public</h5>
                                                    @endif
                                                    @if ($tab_1->category == 1)
                                                        <h6>(Index header image)</h6>
                                                    @elseif($tab_1->category == 2)
                                                        <h6>(Gallery image)</h6>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach

                                </div> --}}

                                <gallery_component/>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
