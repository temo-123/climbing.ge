
    
@if(($table_1_name == 'Sector')&&($table_2_name == 'Route'))
    
    {!! Form::open(['url'=>route('routesNumEdit'), 'class'=>'form-horizontal', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
        @foreach($table_1 as $sector)
            <!-- line modal -->
            <div class="modal fade" id="squarespaceModal{{$num_2++}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
              <div class="modal-dialog" style='z-index: 103000000; width: 700px;'>
            	<div class="modal-content">
            		<div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    
            			<h3 class="modal-title" id="lineModalLabel">{{ $sector -> name }}({!! Html::link(route('sectorEdit',['title'=>$sector -> id]), 'Sector Edit', ['alt'=>$sector->name]) !!} )</h3>
    
                        <h4>{!! Html::link(route('outdoor_page',['title'=>$sector -> region]), $sector -> region, ['alt'=>$sector->name]) !!}</h4> 
                        
            		</div>
            		<div class="modal-body">
            		    <style>
            		        .list{MAX-WIDTH: 400px;}
            		    </style>
            			<div class='row '>
            			    @if(($sector -> image_2 == NULL)&&($sector -> image_3 == NULL)&&($sector -> image_4 == NULL))
                                @if(($sector -> image_height) != NULL)
                                    <img class="list col-xs-12" style="height: {{$sector -> image_height}}; width: auto;" src="../../assets/img/sector_img/{{$sector -> image_1}}">
                                @else
                                    <img class="list col-xs-12" src="../../assets/img/sector_img/{{$sector -> image_1}}">
                                @endif
            			    @elseif(($sector -> image_3 == NULL)&&($sector -> image_4 == NULL))
                                <img class='col-xs-6' src="../../assets/img/sector_img/{{$sector -> image_1}}">
                                <img class='col-xs-6' src="../../assets/img/sector_img/{{$sector -> image_2}}">
            			    @elseif(($sector -> image_4 == NULL))
                                <img class='col-xs-4' src="../../assets/img/sector_img/{{$sector -> image_1}}">
                                <img class='col-xs-4' src="../../assets/img/sector_img/{{$sector -> image_2}}">
                                <img class='col-xs-4' src="../../assets/img/sector_img/{{$sector -> image_3}}">
            			    @else
                                <img class='col-xs-3' src="../../assets/img/sector_img/{{$sector -> image_1}}">
                                <img class='col-xs-3' src="../../assets/img/sector_img/{{$sector -> image_2}}">
                                <img class='col-xs-3' src="../../assets/img/sector_img/{{$sector -> image_3}}">
                                <img class='col-xs-3' src="../../assets/img/sector_img/{{$sector -> image_4}}">
            			    @endif
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane active" id="table_1">
                                <div class="table-responsive">
                                    
                                    
                                            
                                                                        
                                            @foreach($table_3 as $mtp)
                                            @if(($mtp -> sector) == ($sector->filtr_name))
                                                <hr>
                                                <h3>Multi pitch name -> <strong>{{$mtp -> name}}</strong></h3>
                                                @if(\App\Route::where('sector','=',$mtp -> filtr_name)->count() != 0)
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
                                                            @foreach($route_in_sector as $route_3)
                                                            @if(($route_3 -> sector) == ($mtp->filtr_name))
                                                            <tr>
                                                                <td>{{$route_3 -> id}}</td>
                                                                <td>{{$route_3 -> number}}</td>
                                                                <td class="indexInput">
                                                                    <input id="names" name="{{$route_3->id}}" type="text" value="{{$num_3++}}" /><hr>
                                                                </td>
                                                                <td>{{$route_3 -> name}}</td>
                                                                <td>{{$route_3 -> category}}</td>
                                                                <td>{{$route_3 -> gread}}</td>
                                                            </tr>
                                                            @endif
                                                            @endforeach
                                                            {{$num_3 = 1}}
                                                        </tbody>
                                                    </table>
                                                @else
                                                <p>No info about pitch(Or info does not added)</p>
                                                @endif
                                            @endif
                                            @endforeach 
                                    
                                    
                                    @if(\App\Route::where('sector','=',$sector->filtr_name)->count() != 0)
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
                                                            <input id="names" name="{{$route_2->id}}" type="text" value="{{$num_3++}}" /><hr>
                                                        </td>
                                                        <!--{{-- Html::link(route('routeEdit',['route'=>$route->id]), 'Edit', ['class'=>'btn btn-default', 'alt'=>$route->name]) --}}-->
                                                        <td>
                                                            <a href="{{route('routeEdit',['route'=>$route_2->id])}}">
                                                                {{$route_2 -> name}}
                                                            </a>
                                                        </td>
                                                        <td>{{$route_2 -> category}}</td>
                                                        
                                                        <td>{{$route_2 -> gread}}</td>
    
                                                    </tr>
                                                    
                                                    @endif
                                                    
                                                    @if ($loop->last)
                                                        {{$num_3 = 1}}
                                                    @endif
                                                        
                                                @endforeach
                                            @endif
                                            
                                        </tbody>
                                    </table>
                                    @endif
                        		</div>
                            </div>
                        </div>
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
    
@endif

    @if(($table_1_name == 'Sector')&&($table_2_name == 'Route'))
        @foreach($table_3 as $mtp)
            <!-- line modal -->
            <div class="modal fade" id="squarespaceModal_3_{{$num_2++}}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
              <div class="modal-dialog" style='z-index: 103000000; width: 700px;'>
            	<div class="modal-content">
            		<div class="modal-header">
            			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    
             			<h3 class="modal-title" id="lineModalLabel">{{ $mtp -> name }}</h3>
                        
            		</div>
            		<div class="modal-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="table_1">
                                <div class="table-responsive">
                                    <hr>
                                    <h3>Multi pitch name -> <strong>{{$mtp -> name}}</strong></h3>
                                    @if(\App\Route::where('sector','=',$mtp -> filtr_name)->count() != 0)
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
                                                @foreach($route_in_sector as $route_3)
                                                @if(($route_3 -> sector) == ($mtp->filtr_name))
                                                <tr>
                                                    <td>{{$route_3 -> id}}</td>
                                                    <td>{{$route_3 -> number}}</td>
                                                    <td class="indexInput">
                                                        <input id="names" name="{{$route_3->id}}" type="text" value="{{$num_3++}}" /><hr>
                                                    </td>
                                                    <td>{{$route_3 -> name}}</td>
                                                    <td>{{$route_3 -> category}}</td>
                                                    <td>{{$route_3 -> gread}}</td>
                                                </tr>
                                                @endif
                                                @endforeach
                                                {{$num_3 = 1}}
                                            </tbody>
                                        </table>
                                    @else
                                    <p>No info about pitch(Or info does not added)</p>
                                    @endif
                        		</div>
                            </div>
                        </div>
            		</div>
            		<div class="modal-footer">
            		 <!-- <div class="col-md-6" role="group" aria-label="group button">-->
            			<!--	<button type="button" id="saveImage" class="btn btn-hover-green" data-action="save" role="button">Save</button>-->
            			<!--</div>-->
            			
            	        <div class="col-md-6" role="group" aria-label="group button">
            		        {!! Form::button('Save', ['class'=>'btn btn-primary', 'type'=>'submit']) !!}
            			</div>
            		
                        <!--<div class="col-md-6" role="group" aria-label="group button">-->
                        <!--    <button type="button" class="close" data-dismiss="modal">Cancel</button>-->
                        <!--</div>-->
            		</div>
            	</div>
              </div>
            </div>
        @endforeach 
    @endif