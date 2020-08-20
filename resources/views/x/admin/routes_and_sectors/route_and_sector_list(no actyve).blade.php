@extends('admin.layouts.app_admin')

@section('content')

<div class="container">

    @component('admin.components.breadcrumb')
        @slot('title') Sector and route list @endslot
        @slot('parent') Home @endslot
        @slot('active') Sector and route @endslot
    @endcomponent
    
    <a href="{{route('outdoor_list')}}">
        <h2>Outdoor climbing page</h2>
    </a> 
    
    <hr>
        <div class='col-md-5'> 
               
        <h2 style='text-align: center;'>Sector List ({{$count_sector}})</h2>
        
        <hr />
        
        <div class='col-md-12'>
            {!! Html::link(route('sectorAdd'), 'New Section',['class'=>'btn btn-primary pull-right', 'type'=>'submit',  'style'=>'margin-bottom: 50px;']) !!}
        </div>
            
            <hr />
            <div class='row' style='margin-bottom: 5%; margin-top: 5%;'>
                <div align="center">
                    <div class='col-md-12'>
                        <button alt='Climbing in Georgia' class="filter-button-1" data-filter-1="all-1" style='margin-bottom: 2%;'>All</button>
                    </div>
                    
                    @if(isset($sector_tags))
                        @foreach($sector_tags as $k=>$sector_tag)
    
                    <button class="filter-button-1" data-filter-1="{!! $sector_tag->region !!}">{!! $sector_tag->region !!}</button>
                            
                        @endforeach
                    @endif
                </div>
            </div>
            
            @if($sectors)
            <table class='col-md-5 table table-striped'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>|</th>
                        <th>Name</th>
                        <th>|</th>
                        <th class="text-right">Edit</th>
                        <th class="text-right">Delate</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sectors as $k=>$sector)
                    <tr  class='filter-1 {!! $sector -> region !!}'>
                        <td>{{ $sector->id }}</td>          
                        <th>|</th>
                            <th>
                                <button data-toggle="modal" data-target="#squarespaceModal{{$num_1++}}">{{ $sector->name }}</button> 
                            </th>
  
                        <th>|</th>
                        <td class="text-right form-horisontal">
                            {!! Html::link(route('sectorEdit',['outdoor'=>$sector->id]), 'Edit', ['class'=>'btn btn-default', 'alt'=>$sector->name]) !!}
                        </td>
                        <td class="text-right">
                            {!! Form::open(['url'=>route('sectorEdit',['outdoor'=>$sector->id]), 'class' => 'form-horisontal', 'method' => 'POST']) !!}
                            
                            {{ method_field('DELETE') }}
                            {!! Form::button('Delete',['class'=>'btn btn-default', 'type'=>'submit']) !!}
                            
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
            <!--{{--$sectors->appends(['routes' => $routes->currentPage()])->links()--}}-->
        </div>
    
    <div class='col-sm-2'></div>
    
    <div class='col-md-5'>   

    
        <h2 style='text-align: center;'>Route List ({{$count_route}})</h2>
        <hr />
        
        <div class='col-md-12'>
            {!! Html::link(route('routeAdd'), 'New Route',['class'=>'btn btn-primary pull-right', 'type'=>'submit',  'style'=>'margin-bottom: 50px;']) !!}
        </div>
    
        <hr />
        <div class='row'  style='margin-bottom: 5%; margin-top: 5%;'>
            <div align="center">
                <div class='col-md-12'>
                    <button alt='Climbing in Georgia' class="filter-button" data-filter="all" style='margin-bottom: 2%;'>All</button>
                </div>
                
            @if(isset($route_tags))
                @foreach($route_tags as $k=>$route_tag)
                
                <button class="filter-button" data-filter="{!! $route_tag->sector !!}">{!! $route_tag->sector !!}</button>
                
                @endforeach
            @endif
            
            </div>
        </div>  
    
        @if($routes)
        <table class='col-md-5 table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>|</th>
                    <th>Name</th>
                    <th>|</th>
                    <!--<th>Gread</th>-->
                    <!--<th>|</th>-->
                    <th class="text-right">Edit</th>
                    <th class="text-right">Delate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $k=>$route)
                <tr class='filter {!! $route -> sector !!}'>
                    <td>{{ $route->id }}</td>
                    <th>|</th>
                    <td>{{ $route->name }}</td>            
                    <th>|</th>
                    <!--<td>{{-- $route -> gread_fr --}}</td>-->
                    <!--<th>|</th>-->
                    <td class="text-right form-horisontal">
                        {!! Html::link(route('routeEdit',['route'=>$route->id]), 'Edit', ['class'=>'btn btn-default', 'alt'=>$route->name]) !!}
                    </td>
                    <td class="text-right">
                        {!! Form::open(['url'=>route('routeEdit',['route'=>$route->id]), 'class' => 'form-horisontal', 'method' => 'POST']) !!}
                        
                        {{ method_field('DELETE') }}
                        {!! Form::button('Delete',['class'=>'btn btn-default', 'type'=>'submit']) !!}
                        
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        <!--{{--$routes->appends(['sectors' => $sectors->currentPage()])->links()--}} -->
    </div>
</div>

{!! Form::open(['url'=>route('routesNumEdit'), 'class'=>'form-horizontal', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
    @foreach($sectors as $k=>$sector)
        <!-- line modal -->
        <div class="modal fade" id="squarespaceModal{{$num_2++}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog" style='z-index: 103000000;'>
        	<div class="modal-content">
        		<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

        			<h3 class="modal-title" id="lineModalLabel">{{ $sector -> name }}</h3>

                    <h4>{!! Html::link(route('outdoor_page',['title'=>$sector -> region]), $sector -> region, ['alt'=>$sector->name]) !!} ( {--!! Html::link(route('outdoorEdit',['title'=>$sector -> id]), 'Edit', ['alt'=>$sector->name]) !!--} )</h4>
                    
        		</div>
        		<div class="modal-body">
        		    
        			<div class='row'>
        			    @if(($sector -> image_2 == NULL)&&($sector -> image_3 == NULL)&&($sector -> image_4 == NULL))
                            @if(($sector -> image_height) != NULL)
                                <img class="col-xs-12" style="height: {{$sector -> image_height}}; width: auto;" src="../../assets/img/sector_img/{{$sector -> image_1}}">
                            @else
                                <img class="col-xs-12" src="../../assets/img/sector_img/{{$sector -> image_1}}">
                            @endif
        			    @elseif(($sector -> image_3 == NULL)&&($sector -> image_4 == NULL))
                            <img class='col-md-6' src="../../assets/img/sector_img/{{$sector -> image_1}}">
                            <img class='col-md-6' src="../../assets/img/sector_img/{{$sector -> image_2}}">
        			    @elseif(($sector -> image_4 == NULL))
                            <img class='col-md-4' src="../../assets/img/sector_img/{{$sector -> image_1}}">
                            <img class='col-md-4' src="../../assets/img/sector_img/{{$sector -> image_2}}">
                            <img class='col-md-4' src="../../assets/img/sector_img/{{$sector -> image_3}}">
        			    @else
                            <img class='col-md-3' src="../../assets/img/sector_img/{{$sector -> image_1}}">
                            <img class='col-md-3' src="../../assets/img/sector_img/{{$sector -> image_2}}">
                            <img class='col-md-3' src="../../assets/img/sector_img/{{$sector -> image_3}}">
                            <img class='col-md-3' src="../../assets/img/sector_img/{{$sector -> image_4}}">
        			    @endif
                    </div>
                                    
                    <table class='col-md-5 table table-striped' id="myTable">
                        <thead>
                            <tr>
                                <th>DB ID</th>
                                <th>№</th>
                                <th>New №</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Gread</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        @if(isset($route_in_sector))
                            @foreach($route_in_sector as $route_2)
                            
                                @if(($route_2 -> sector) == ($sector->filtr_name))
                                    
                                <tr>
                                    <td>{{$route_2 -> id}}</td>
                                    
                                    <td>{{$route_2 -> number}}</td>
                                    
                                    <td class="indexInput">
                                        <input id="names" name="{{$route_2->id}}" type="text" value="{{$num_4++}}" /><hr>
                                    </td>
                                    <!--{{-- Html::link(route('routeEdit',['route'=>$route->id]), 'Edit', ['class'=>'btn btn-default', 'alt'=>$route->name]) --}}-->
                                    <td>
                                        <a href="{{route('routeEdit',['route'=>$route_2->id])}}">
                                            {{$route_2 -> name}}
                                        </a>
                                    </td>
                                    <td>{{$route_2 -> category}}</td>
                                    
                                    @if($route_2 -> category == 'Bouldering')
                                    <td>{{$route_2 -> grade_boulder}}</td>
                                    @else
                                    <td>{{$route_2 -> gread_fr}}</td>
                                    @endif
                                    
                                </tr>
                                
                                @endif
                                
                                @if ($loop->last)
                                    {{$num_4 = 1}}
                                @endif
                                    
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                  
        		</div>
        		<div class="modal-footer">
        		 <!-- <div class="col-md-6" role="group" aria-label="group button">-->
        			<!--	<button type="button" id="saveImage" class="btn btn-hover-green" data-action="save" role="button">Save</button>-->
        			<!--</div>-->
        			
        		{!! Form::button('Save', ['class'=>'btn btn-primary', 'type'=>'submit']) !!}
        		
        		  <div class="col-md-6" role="group" aria-label="group button">
        				<button type="button" class="close" data-dismiss="modal">Cancel</button>
        			</div>
        		</div>
        	</div>
          </div>
        </div>
    @endforeach
{!! Form::close() !!}

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script src="{{ asset('assets/js/admin/table_move.js') }}" type="text/javascript"></script>
    
@endsection