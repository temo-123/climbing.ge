<div class="container">

	@component('user.components.breadcrumb')
		@slot('title') Route add @endslot
		@slot('parent') Home @endslot
		@slot('active') Route add @endslot
	@endcomponent
  
	<div class="form-group">
		<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
	</div>
</div>

<div class="wrapper container-fluid">
    <div class='row'  style='margin: 5%;'>
        <div align="center">
            <div class='col-md-12'>
                <h2>All regions</h2>
                <div class='col-md-12'>
                    <button class="filter-button" data-filter="all" style='margin-bottom: 2%;'>All Regions</button>
                </div>
                @if(isset($tags))
                    @foreach($tags as $k=>$tag)
    
                        <button class="filter-button" data-filter="{!! $tag->region !!}">{!! $tag->region !!}</button>
    
                    @endforeach
                @endif
            </div>
        </div>
    </div> 
    


    <div class="col-lg-12 col-sm-12">
		@if (isset($data['id']))
	    	<form action="{{ route('mtpEdit', array('page'=>$data['id'])) }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data"> 
		@else 
			<form action="{{ route('mtpAdd') }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
		@endif
            		
            		<div class="form-group" class='col-xs-8 control-label'>

						<h2 class="col-xs-2 control-label">Sector</h2>
            
            			<div class="col-xs-8" class='form-control'>
            
            				<select class="form-control" name="sector" > 
            
                				<option value="No_sector">Sector</option>
                      
                            	@if (isset($outdoor_sectors) && is_object($outdoor_sectors) )
                    			    @foreach($outdoor_sectors as $k=>$outdoor_sector)
                                        @if (isset($data['id'])) 
                    			            <option class="form-control filter {!! $outdoor_sector -> region !!}"  @if ($data['sector'] == ($outdoor_sector -> filtr_name) )" selected="" @endif>{{$outdoor_sector -> filtr_name}}</option> 
                    					@else
                    				        <option class="filter-button filter {!! $outdoor_sector -> region !!}" value="{{$outdoor_sector -> filtr_name}}">{{$outdoor_sector -> name}}</option>
            						    @endif
                                    @endforeach
                                @endif
            				</select> 
            
            			</div>
            		</div>
            		
            		


            		<div class="form-group">
						<div class='col-xs-12'>
							<h2>filtr_name:</h2>
						</div>
						<div class="col-xs-6 col-md-offset-3">
							@if (isset($data['id']))
								<input type="filtr_name" name="filtr_name" value="{{$data['filtr_name']}}" class="form-control textarea" placeholder="filtr_name">
							@else
								<input type="filtr_name" name="filtr_name" class="form-control textarea" placeholder="filtr_name">
							@endif
						</div>
					</div>

					<div class="form-group">
						<div class='col-xs-12'>
							<h2>Text:</h2>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<div class="col-xs-12">
								@if (isset($data['id']))
									<textarea name="text" id="text" value="{{$data['text']}}" placeholder="Text Area" class="form-control textarea"></textarea>
								@else
									<textarea name="text" id="text" placeholder="Text Area" class="form-control textarea"></textarea>
								@endif
							</div>
						</div>
					</div>
            		
            		<div class="form-group">
						<div class="col-xs-offset-2 col-xs-10">
							<button type="submit" class="btn main-btn pull-right">Save</button>
						</div>
					</div>
            
				</form>
    
    </div>
</div>

<script>
	CKEDITOR.replace('editor_1');
</script>
<script>
    CKEDITOR.config.filebrowserBrowseUrl = "{{ asset('assets/php/browse.php') }}";
    CKEDITOR.config.filebrowserUploadUrl = "{{ asset('assets/php/upload.php') }}";
</script>
