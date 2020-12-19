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

                    <div class="add_buttom">
                        <a href="{{ route('siteInfoEdit', [1]) }}" class="btn btn-primary pull-left" type="submit">Edit </a>
                    </div>

                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>|</th>
                            <th>Data</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>title</th>
                                <th>|</th>
                                <td>{!! $site -> title !!}</td>
                            </tr>
                            <tr>
                                <th>Ru title</th>
                                <th>|</th>
                                <td>{!! $site -> title_ru !!}</td>
                            </tr>
                            <tr>
                            <th>Ka title</th>
                            <th>|</th>
                            <td>{!! $site -> title_ka !!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Us Text</th>
                                <th>|</th>
                                <td>{!! $site -> text !!}</td>
                            </tr>
                            <tr>
                                <th>Ru Text</th>
                                <th>|</th>
                                <td>{!! $site -> text_ru !!}</td>
                            </tr>
                            <tr>
                                <th>Ka Text</th>
                                <th>|</th>
                                <td>{!! $site -> text_ka !!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>other_activity_description</th>
                                <th>|</th>
                                <th>{!! $site -> other_activity_description!!}</th>
                            </tr>
                            <tr>
                                <th>ka other actyvity description</th>
                                <th>|</th>
                                <td>{!! $site -> ka_other_activity_description!!}</td>
                            </tr>
                            <tr>
                                <th>ru_other_activity_description!</th>
                                <th>|</th>
                                <th>{!! $site -> ru_other_activity_description!!}</th>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>outdoor_description</th>
                                <th>|</th>
                                <td>{!! $site -> outdoor_description!!}</td>
                            </tr>
                            <tr>
                                <th>ru_outdoor description</th>
                                <th>|</th>
                                <td>{!! $site -> ru_outdoor_description!!}</td>
                            </tr>
                            <tr>
                                <th>ka outdoor escription</th>
                                <th>|</th>
                                <td>{!! $site -> ka_outdoor_description!!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>ice_description</th>
                                <th>|</th>
                                <td>{!! $site -> ice_description!!}</td>
                            </tr>
                            <tr>
                                <th>ru_ice_description</th>
                                <th>|</th>
                                <td>{!! $site -> ru_ice_description!!}</td>
                            </tr>
                            <tr>
                                <th>ka ice description</th>
                                <th>|</th>
                                <td>{!! $site -> ka_ice_description!!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>   
                                <th>indoor_description</th>
                                <th>|</th>
                                <th>{!! $site -> indor_description!!}</th>
                            </tr>
                            <tr>    
                                <th>ru indoor description</th>
                                <th>|</th>
                                <td>{!! $site -> ru_indor_description!!}</td>
                            </tr>
                            <tr>    
                                <th>ka indoor description</th>
                                <th>|</th>
                                <td>{!! $site -> ka_indor_description!!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>mount_description</th>
                                <th>|</th>
                                <th>{!! $site -> mount_description!!}</th>
                            </tr>
                            <tr>
                                <th>ru meta description</th>
                                <th>|</th>
                                <td>{!! $site -> ru_mount_description!!}</td>
                            </tr>
                            <tr>
                                <th>ka mount description</th>
                                <th>|</th>
                                <td>{!! $site -> ka_mount_description!!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>    
                                <th>meta description</th>
                                <th>|</th>
                                <td>{!! $site -> meta_description!!}</td>
                            </tr>
                            <tr>
                                <th>ru_meta_description</th>
                                <th>|</th>
                                <td>{!! $site -> ru_indor_description!!}</td>
                            </tr>
                            <tr>
                                <th>ka_meta_description</th>
                                <th>|</th>
                                <td>{!! $site -> ru_indor_description!!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>       
                                <th>short description</th>
                                <th>|</th>
                                <td>{!! $site -> shop_description!!}</td>
                            </tr>
                            <tr>
                                <th>ru short description</th>
                                <th>|</th>
                                <td>{!! $site -> ru_shop_description!!}</td>
                            </tr>
                            <tr>
                                <th>ka short description</th>
                                <th>|</th>
                                <td>{!! $site -> ka_shop_description!!}</td>
                            </tr>
                            <tr>
                                <th>-</th>
                                <th>-</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>meta keyword</th>
                                <th>|</th>
                                <td>{!! $site -> meta_keyword!!}</td>
                            </tr>   
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection