@extends('user.layouts.app')

@section('content')
{{-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> --}}
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
                            <div class="col-xs-12">
                                <ul class="event-list">
                                    @foreach ($events as $event)
                                    <li>
                                        <time datetime="2014-07-20 0000">
                                            <span class="day">{{ $event[0]['start_data_day'] }}</span>
                                            <span class="month">{{ $event[0]['start_data_month'] }}</span>
                                            {{-- <span class="year">2014</span>
                                            <span class="time">12:00 AM</span> --}}
                                        </time>
                                        <div class="info">
                                            <h2 class="title"><a href="{{ route('events_page', array('title'=>$event[0]['url_title'])) }}">{{ $event[0][0][0]->title }}</a></h2>
                                            <p class="desc">{{  $event[0][0][0]->short_description  }}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>            
                    
                   
                </div>
            </div>
        </div>
    </div>
@endsection