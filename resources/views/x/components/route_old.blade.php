@if(isset($sectors) && is_object($sectors))
    @forelse($sectors as $k=>$sector)
        
        <h1 id="{{$sector->name}}">{{$sector_name++}})Sector - {{$sector->name}}</h1>
        
        {!!$sector->text!!}
    
            @foreach($mtps as $mtp)
            @if(($mtp -> sector_id) == ($sector->id))
                <div style='display:none;'>{{$mtp_num++}}</div>              
                <div class ='row'>
                    <div class='col-xs-6 col-md-6 col-lg-6'>
                        <h4>{{$mtp_num}}) Name - <strong>{{$mtp->name}}</strong></h4>
                    </div>
                    <div class='col-xs-6 col-md-6 col-lg-6'>
                        <p>Pitch - <strong>{{$mtp->pitch}}</strong></p>
                    </div>
                </div>              
                <div class ='row'>
                    <div class='col-md-12'>
                        <a href="#" data-toggle="modal" data-target="#squarespaceModal_mtp_info_{{$mtp_info_num++}}">
                            <h4><strong>More information</strong></h4>
                        </a>
                    </div>
                </div>
                @if(\App\Route::where('sector_id','=',$mtp -> id)->count() != 0)
                <table class="table col-md-12">
                    <tbody>
                        <tr>
                        <td>N</td>
                        <td>Name</td>
                        <td class="display-none-720px">Category</td>
                        <td>Height</td>
                        <td>Bolts</td>
                        <td>Gread FR</td>
                        <td class="display-none-720px">Gread YDS</td> 
                        <!--<td>S</td>-->
                        </tr>
                    </tbody>
                    <tbody>
                        
                        @foreach($mtp_routes as $mtp_route)
                        @if(($mtp_route -> sector_id) == ($mtp->id))
                        <tr>  
                        
                            <td>{{$mtp_num .'.'. $mtp_route -> number}}</td>
                            
                            <td>{{$mtp_route -> name}}</td>
                            
                            <td class="display-none-720px">{{$mtp_route -> category}}</td>
                            
                            @if((($mtp_route->height) == NULL) || ($mtp_route->height == 0))
                                <td>?</td>
                            @else
                                <td>{{$mtp_route -> height}}</td>
                            @endif
                            
                            @if($mtp_route -> category != 'Bouldering')
                                @if($mtp_route -> category != 'Traverse')
                                    @if((($mtp_route->bolts) == NULL))
                                    <td>0</td>
                                    @else
                                    <td>{{$mtp_route -> bolts}}</td>
                                    @endif
                                @endif
                            <td>
                                @if ($mtp_route->category == 'Aid Climbing')
                                    ?
                                @else
                                    {{$mtp_route -> gread}}
                                @endif
                            </td>
    
                            @else
                            <td>{{$mtp_route -> grade}}</td>
                            @endif
                            
                            <td class="display-none-720px">
                                @if($mtp_route -> gread == '4')
                                5.6
                                @elseif($mtp_route -> gread == '5a' || $mtp_route -> gread == '5b+')
                                5.7
                                @elseif($mtp_route -> gread == '5b' || $mtp_route -> gread == '5b+')
                                5.8
                                @elseif($mtp_route -> gread == '5c' || $mtp_route -> gread == '5c+')
                                5.9
                                @elseif($mtp_route -> gread == '6a')
                                5.10a
                                @elseif($mtp_route -> gread == '6a+')
                                5.10b
                                @elseif($mtp_route -> gread == '6b')
                                5.10c
                                @elseif($mtp_route -> gread == '6b+')
                                5.10d
                                @elseif($mtp_route -> gread == '6c')
                                5.11a/5.11b
                                @elseif($mtp_route -> gread == '6c+')
                                5.11c
                                @elseif($mtp_route -> gread == '7a')
                                5.11d
                                @elseif($mtp_route -> gread == '7a+')
                                5.12a
                                @elseif($mtp_route -> gread == '7b')
                                5.12b
                                @elseif($mtp_route -> gread == '7b+')
                                5.12c
                                @elseif($mtp_route -> gread == '7c')
                                5.12d
                                @elseif($mtp_route -> gread == '7c+')
                                5.13a
                                @elseif($mtp_route -> gread == '8a')
                                5.13b
                                @elseif($mtp_route -> gread == '8a+')
                                5.13c
                                @elseif($mtp_route -> gread == '8b')
                                5.13d
                                @elseif($mtp_route -> gread == '8b+')
                                5.14a
                                @elseif($mtp_route -> gread == '8c')
                                5.14b
                                @elseif($mtp_route -> gread == '8c+')
                                5.14c
                                @elseif($mtp_route -> gread == '9a')
                                5.14d
                                @elseif($mtp_route -> gread == '9a+')
                                5.15a
                                @elseif($mtp_route -> gread == '9b')
                                5.15b
                                @elseif($mtp_route -> gread == '9b+')
                                5.15c
                                @elseif($mtp_route -> gread == '9c')
                                5.15d
                                @elseif($mtp_route -> gread == '9c+')
                                5.16a
                                @elseif($mtp_route->category == 'Aid Climbing')
                                ?
                                @else
                                ?
                                @endif
                            </td>
                            
                            <!--<td>-->
                            <!--    <a href="#" data-toggle="modal" data-target="#squarespaceModal_mtp_{{$route_num++}}">-->
                            <!--        <i class="fa fa-book" aria-hidden="true"></i>-->
                            <!--    </a>-->
                            <!--</td>-->
                            </tr>
                        </tr>
                        @endif
                        @endforeach
                            
                    </tbody>
                </table>
                @else
                <table class="table col-md-12">
                    <tbody>
                        <tr>
                            <td>No info about pitch. :(</td>
                        </tr>
                    </tbody>
                </table>
                @endif
            @endif
            @endforeach
        
            <div class="container">
                <div class="row">
                    <table class="table col-md-12">
                        <tbody>
                        @if(isset($routes) && is_object($routes))
                            @forelse($routes as $k=>$route)
                                @if(($route -> sector_id) == ($sector->id))
                                <tr>
                                <td>N</td>
                                <td>Name</td>
                                <td class="display-none-720px">Category</td>
                                <td>Height</td>
                                
                                    @if ($route->category == 'Bouldering')
                                        <td>Gread</td> 
                                        <td>S</td>
                                        </tr>
                                        @break
                                    @endif
                                    @if (($route->category == 'Top Rope') || ($route->category == 'Lead Climbng'))
                                        <td>Bolts</td>
                                        <td>Gread FR</td>
                                        <td class="display-none-720px">Gread YDS</td> 
                                        <td>S</td>
                                        </tr>
                                        @break
                                    @endif                                     
                                    @if ($route->category == 'Traverse')
                                        <td>Gread FR</td>
                                        <td class="display-none-720px">Gread YDS</td> 
                                        <td>S</td>
                                        </tr>
                                        @break
                                    @endif 
                                    
                                @endif
                            @empty
                            <h2>No Route</h2>
                            @endforelse
                        @else
                            <h2>Vareibal Error</h2>
                        @endif
                        
                        </tbody>
                        <tbody>
                        
                        @if(isset($routes) && is_object($routes))
                            @forelse($routes as $k=>$route)
                                @if(($route -> sector_id) == ($sector->id))
                                <tr>  
                                
                                <td>{{ $route -> number}}</td>
                                
                                <td>{{$route -> name}}</td>
                                
                                <td class="display-none-720px">{{$route -> category}}</td>
                                
                                @if((($route->height) == NULL) || ($route->height == 0))
                                    <td>?</td>
                                @else
                                    <td>{{$route -> height}}</td>
                                @endif
                                
                                @if($route -> category != 'Bouldering')
                                    @if($route -> category != 'Traverse')
                                        @if((($route->bolts) == NULL))
                                        <td>0</td>
                                        @else
                                        <td>{{$route -> bolts}}</td>
                                        @endif
                                    @endif
                                <td>
                                    @if ($route->category == 'Aid Climbing')
                                        ?
                                    @else
                                        {{$route -> gread}}
                                    @endif
                                </td>
                                <td class="display-none-720px">
                                    @if($route -> gread == '4')
                                    5.6
                                    @elseif($route -> gread == '5a' || $route -> gread == '5b+')
                                    5.7
                                    @elseif($route -> gread == '5b' || $route -> gread == '5b+')
                                    5.8
                                    @elseif($route -> gread == '5c' || $route -> gread == '5c+')
                                    5.9
                                    @elseif($route -> gread == '6a')
                                    5.10a
                                    @elseif($route -> gread == '6a+')
                                    5.10b
                                    @elseif($route -> gread == '6b')
                                    5.10c
                                    @elseif($route -> gread == '6b+')
                                    5.10d
                                    @elseif($route -> gread == '6c')
                                    5.11a/5.11b
                                    @elseif($route -> gread == '6c+')
                                    5.11c
                                    @elseif($route -> gread == '7a')
                                    5.11d
                                    @elseif($route -> gread == '7a+')
                                    5.12a
                                    @elseif($route -> gread == '7b')
                                    5.12b
                                    @elseif($route -> gread == '7b+')
                                    5.12c
                                    @elseif($route -> gread == '7c')
                                    5.12d
                                    @elseif($route -> gread == '7c+')
                                    5.13a
                                    @elseif($route -> gread == '8a')
                                    5.13b
                                    @elseif($route -> gread == '8a+')
                                    5.13c
                                    @elseif($route -> gread == '8b')
                                    5.13d
                                    @elseif($route -> gread == '8b+')
                                    5.14a
                                    @elseif($route -> gread == '8c')
                                    5.14b
                                    @elseif($route -> gread == '8c+')
                                    5.14c
                                    @elseif($route -> gread == '9a')
                                    5.14d
                                    @elseif($route -> gread == '9a+')
                                    5.15a
                                    @elseif($route -> gread == '9b')
                                    5.15b
                                    @elseif($route -> gread == '9b+')
                                    5.15c
                                    @elseif($route -> gread == '9c')
                                    5.15d
                                    @elseif($route -> gread == '9c+')
                                    5.16a
                                    @elseif($route->category == 'Aid Climbing')
                                    ?
                                    @else
                                    ?
                                    @endif
                                </td>
                                @else
                                <td>{{$route -> gread}}</td>
                                @endif
                                
                                <td>
                                <a href="#" data-toggle="modal" data-target="#squarespaceModal{{$route_num++}}">
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                </a>
                                </td>
                                </tr>
                                @endif
                            @empty
                            No Route
                            @endforelse
                        @else
                        Error
                        @endif
                        
                        </tbody>
                    </table>
                </div>
            </div>
        
    @empty
    @endforelse
@else
<h2>Error</h2>
@endif






@forelse($sectors as $k=>$sector)
    @if(($sector -> region) == ($article->title))
        @forelse($routes as $k=>$route)
            @if(($route -> sector_id) == ($sector->id))
            
                <!-- line modal -->
                <div class="modal fade" id="squarespaceModal{{$route_page_num++}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                  <div class="modal-dialog route_description_open">
                	<div class="modal-content">
                		<div class="modal-header">
                			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                			    <h3 class="modal-title" id="lineModalLabel">{{ $route -> name }}</h3>
                		</div>
                		<div class="modal-body">
                		    <div class="container"> 
                		        <div class='col-md-12'>
                		            {!! $route -> text !!}
                		        </div>
                		        
                		        @if($route->category == 'Lead Climbng')
                		        <div class='row container'>
                		            <div class='col-md-6'>
                		                <h4> Last Carabin -
                		                    @if($route -> last_carabin == 0)
                    		                <i class="fa fa-times" aria-hidden="true"></i>
                		                    @elseif($route -> last_carabin == 1)
                    		                <i class="fa fa-check" aria-hidden="true"></i>
                		                    @else 
                		                    <i class="fa fa-question" aria-hidden="true"></i>
                		                    @endif
                		                </h4>
                                    </div>
                		        </div> 
                		        @endif
                		        
                            	<div class="tab-pane active" id="home">
                                    <div class="table-responsive">  		        
                                        <table class="table col-md-12">
                                            <thead>
                                                @if(($route -> sector_id) == ($sector->id))
                                                    <tr>
                                                    <td>N</td>
                                                    <td>Name</td>
                                                    <td >Category</td>
                                                    <td>Height</td>
                                                
                                                    @if ($route->category == 'Bouldering')
                                                        <td>Gread</td>
                                                        </tr>
                                                    @endif
                                                    @if (($route->category == 'Top Rope') || ($route->category == 'Lead Climbng'))
                                                        <td>Bolts</td>
                                                        <td>Gread FR</td>
                                                        <td>Gread YDS</td> 
                                                        </tr>
                                                    @endif                                     
                                                    @if ($route->category == 'Traverse')
                                                        <td>Gread FR</td>
                                                        <td>Gread YDS</td>
                                                        </tr>
                                                    @endif 
                                                @endif
                                            </thead>
                                            <tbody>
                                                @if(($route -> sector_id) == ($sector->id))
                                                    <tr>  
                                                    
                                                    <td>{{ $route -> number }}</td>
                                                    
                                                    <td>
                                                        @if (Route::has('login'))
                        				                    @auth
                        				                        <a href="{{route('routeEdit',['route'=>$route->id])}}">
                                                                    {{$route -> name}}
                                                                </a>
                        				                    @else
                        				                        {{$route -> name}}
                        				                    @endauth
                        				                @endif
                                                    </td>
                                                    <td>{{$route -> category}}</td>
                                                    
                                                    @if((($route->height) == NULL) || ($route->height == 0))
                                                        <td>?</td>
                                                    @else
                                                        <td>{{$route -> height}}</td>
                                                    @endif
                                                    
                                                    @if($route -> category != 'Bouldering')
                                                        @if($route -> category != 'Traverse')
                                                            @if((($route->bolts) == NULL))
                                                            <td>0</td>
                                                            @else
                                                            <td>{{$route -> bolts}}</td>
                                                            @endif
                                                        @endif
                                                    <td>{{$route -> gread}}</td>
                                                    <td>
                                                        @if($route -> gread == '4')
                                                        5.6
                                                        @elseif($route -> gread == '5a' || $route -> gread == '5b+')
                                                        5.7
                                                        @elseif($route -> gread == '5b' || $route -> gread == '5b+')
                                                        5.8
                                                        @elseif($route -> gread == '5c' || $route -> gread == '5c+')
                                                        5.9
                                                        @elseif($route -> gread == '6a')
                                                        5.10a
                                                        @elseif($route -> gread == '6a+')
                                                        5.10b
                                                        @elseif($route -> gread == '6b')
                                                        5.10c
                                                        @elseif($route -> gread == '6b+')
                                                        5.10d
                                                        @elseif($route -> gread == '6c')
                                                        5.11a/5.11b
                                                        @elseif($route -> gread == '6c+')
                                                        5.11c
                                                        @elseif($route -> gread == '7a')
                                                        5.11d
                                                        @elseif($route -> gread == '7a+')
                                                        5.12a
                                                        @elseif($route -> gread == '7b')
                                                        5.12b
                                                        @elseif($route -> gread == '7b+')
                                                        5.12c
                                                        @elseif($route -> gread == '7c')
                                                        5.12d
                                                        @elseif($route -> gread == '7c+')
                                                        5.13a
                                                        @elseif($route -> gread == '8a')
                                                        5.13b
                                                        @elseif($route -> gread == '8a+')
                                                        5.13c
                                                        @elseif($route -> gread == '8b')
                                                        5.13d
                                                        @elseif($route -> gread == '8b+')
                                                        5.14a
                                                        @elseif($route -> gread == '8c')
                                                        5.14b
                                                        @elseif($route -> gread == '8c+')
                                                        5.14c
                                                        @elseif($route -> gread == '9a')
                                                        5.14d
                                                        @elseif($route -> gread == '9a+')
                                                        5.15a
                                                        @elseif($route -> gread == '9b')
                                                        5.15b
                                                        @elseif($route -> gread == '9b+')
                                                        5.15c
                                                        @elseif($route -> gread == '9c')
                                                        5.15d
                                                        @elseif($route -> gread == '9c+')
                                                        5.16a
                                                        @elseif($route->category == 'Aid Climbing')
                                                        ?
                                                        @else
                                                        ?
                                                        @endif
                                                    </td>
                                                    @else
                                                    <td>{{$route -> grade}}</td>
                                                    @endif
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                @if($route->bolter != NULL)
                		        <div class='col-md-6'>Bolter - <strong>{{$route->bolter}}</strong></div>
                		        @endif
                		        @if($route->first_ascent != NULL)
                		        <div class='col-md-6'>First ascent - <strong>{{$route->first_ascent}}</strong></div>
                		        @endif
                		        @if ($route->category == 'Aid Climbing')
                                    <div class='col-md-12'><a href='http://www.bigwalls.net/climb/Ratings.html'>What is aid climbing?</a></div>
                                @endif
                            </div>
                		</div>
                		<div class="modal-footer">
                		 <!--   <div class="cil-md-6" role="group" aria-label="group button">-->
                			<!--	<button type="button" id="saveImage" class="btn btn-hover-green" data-action="save" role="button">Save</button>-->
                			<!--</div>-->
                		</div>
                	</div>
                  </div>
                </div>

            @endif
        @empty
        @endforelse
    @endif
@empty
@endforelse

@foreach($mtps as $mtp)
    <!-- line modal -->
    <div class="modal fade" id="squarespaceModal_mtp_info_{{$mtp_info_page_num++}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog route_description_open" style='z-index: 103000000;'>
    	<div class="modal-content">
    		<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

     			<h3 class="modal-title" id="lineModalLabel">Name - {{ $mtp -> name }}</h3>
                
    		</div>
    		<div class="modal-body">
                <div class="container"> 
                    @if(\App\Route::where('sector_id','=',$mtp -> id)->count() != 0)
                    
                    <div class ='row'>
                        <div class='col-xs-6 col-md-6 col-lg-6'>
                            <p>Pitch - <strong>{{$mtp->pitch}}</strong></p>
                        </div>
                        <div class='col-xs-6 col-md-6 col-lg-6'>
                            <h4>Bolter - <strong>{{$mtp->bolter}}</strong></h4>
                        </div>
                    </div>                
                    <div class ='row'>
                        <div class='col-xs-12 col-md-12 col-lg-12'>
                            {!!$mtp->text!!}
                        </div>
                    </div>
                    
                    <div class="tab-pane active" id="home">
                            <div class="table-responsive">  		        
                                <table class="table col-md-12">
                                    <thead>
                                        <tr>
                                            <tr>
                                            <td>N</td>
                                            <td>Name</td>
                                            <td class="display-none-720px">Category</td>
                                            <td>Height</td>
                                            <td>Bolts</td>
                                            <td>Gread FR</td>
                                            <td class="display-none-720px">Gread YDS</td>
                                            </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mtp_routes as $mtp_route)
                                        @if(($mtp_route -> sector_id) == ($mtp->id))
                                        <tr>
                                            <td>{{$mtp_route -> number}}</td>
                                            <td>{{$mtp_route -> name}}</td>
                                            
                                            <td class="display-none-720px">{{$mtp_route -> category}}</td>
                                            
                                            @if((($mtp_route->height) == NULL) || ($mtp_route->height == 0))
                                                <td>?</td>
                                            @else
                                                <td>{{$mtp_route -> height}}</td>
                                            @endif
                                            
                                            @if($mtp_route -> category != 'Bouldering')
                                                @if($mtp_route -> category != 'Traverse')
                                                    @if((($mtp_route->bolts) == NULL))
                                                    <td>0</td>
                                                    @else
                                                    <td>{{$mtp_route -> bolts}}</td>
                                                    @endif
                                                @endif
                                            <td>
                                                @if ($mtp_route->category == 'Aid Climbing')
                                                    ?
                                                @else
                                                    {{$mtp_route -> gread}}
                                                @endif
                                            </td>
                    
                                            @else
                                            <td>{{$mtp_route -> grade}}</td>
                                            @endif
                                            
                                            <td class="display-none-720px">
                                                @if($mtp_route -> gread == '4')
                                                5.6
                                                @elseif($mtp_route -> gread == '5a' || $mtp_route -> gread == '5b+')
                                                5.7
                                                @elseif($mtp_route -> gread == '5b' || $mtp_route -> gread == '5b+')
                                                5.8
                                                @elseif($mtp_route -> gread == '5c' || $mtp_route -> gread == '5c+')
                                                5.9
                                                @elseif($mtp_route -> gread == '6a')
                                                5.10a
                                                @elseif($mtp_route -> gread == '6a+')
                                                5.10b
                                                @elseif($mtp_route -> gread == '6b')
                                                5.10c
                                                @elseif($mtp_route -> gread == '6b+')
                                                5.10d
                                                @elseif($mtp_route -> gread == '6c')
                                                5.11a/5.11b
                                                @elseif($mtp_route -> gread == '6c+')
                                                5.11c
                                                @elseif($mtp_route -> gread == '7a')
                                                5.11d
                                                @elseif($mtp_route -> gread == '7a+')
                                                5.12a
                                                @elseif($mtp_route -> gread == '7b')
                                                5.12b
                                                @elseif($mtp_route -> gread == '7b+')
                                                5.12c
                                                @elseif($mtp_route -> gread == '7c')
                                                5.12d
                                                @elseif($mtp_route -> gread == '7c+')
                                                5.13a
                                                @elseif($mtp_route -> gread == '8a')
                                                5.13b
                                                @elseif($mtp_route -> gread == '8a+')
                                                5.13c
                                                @elseif($mtp_route -> gread == '8b')
                                                5.13d
                                                @elseif($mtp_route -> gread == '8b+')
                                                5.14a
                                                @elseif($mtp_route -> gread == '8c')
                                                5.14b
                                                @elseif($mtp_route -> gread == '8c+')
                                                5.14c
                                                @elseif($mtp_route -> gread == '9a')
                                                5.14d
                                                @elseif($mtp_route -> gread == '9a+')
                                                5.15a
                                                @elseif($mtp_route -> gread == '9b')
                                                5.15b
                                                @elseif($mtp_route -> gread == '9b+')
                                                5.15c
                                                @elseif($mtp_route -> gread == '9c')
                                                5.15d
                                                @elseif($mtp_route -> gread == '9c+')
                                                5.16a
                                                @elseif($mtp_route->category == 'Aid Climbing')
                                                ?
                                                @else
                                                ?
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    @else
                    <p>No info about pitch(Or info does not added)</p>
                    @endif
                </div>
    		</div>
    		<div class="modal-footer">
    		</div>
    	</div>
      </div>
    </div>
@endforeach
