<div class="container">

	@component('admin.components.breadcrumb')
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
	        {!! Form::open(['url'=>route('mtpEdit', array('page'=>$data['id'])), 'class'=>'form-horizontal', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
        @else 
            {!! Form::open(['url'=>route('mtpAdd'), 'class' => 'form-horizontal', 'method' => 'POST', 'enctype'=>'multipart/form-data']) !!}
        @endif
            		
            		<div class="form-group" class='col-xs-8 control-label'>
            
            			{!! Form::label('sector', 'Sector', ['class'=>'col-xs-2 control-label']) !!}
            
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
            			{!! Form::label('name', 'Multi pitch name:', ['class'=>'col-xs-2 control-label']) !!}
            			<div class="col-xs-8">
            				@if (isset($data['id']))
				                {!! Form::text('name', $data['name'], ['class'=>'form-control', 'placeholder'=>'Multi pitch Name']) !!}
        					@else
            				    {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'Multi pitch Name']) !!}
            				@endif
            			</div>
            		</div>
            		
            		<div class="form-group">
            			{!! Form::label('name', 'Multi pitch name for filtr:', ['class'=>'col-xs-2 control-label']) !!}
            			<div class="col-xs-8">
            				@if (isset($data['id']))
				                {!! Form::text('filtr_name', $data['filtr_name'], ['class'=>'form-control', 'placeholder'=>'Multi pitch name for filtr']) !!}
        					@else
            				    {!! Form::text('filtr_name', old('filtr_name'), ['class'=>'form-control', 'placeholder'=>'Multi pitch name for filtr']) !!}
            				@endif
            			</div>
            		</div> 	
            		
            		<div class="form-group">
            			{!! Form::label('pitch', 'Pitch:', ['class'=>'col-xs-2 control-label']) !!}
            			<div class="col-xs-8">
            			    @if (isset($data['id']))
				                {!! Form::text('pitch', $data['pitch'], ['class'=>'form-control', 'placeholder'=>'pitch']) !!}
        					@else
            				    {!! Form::text('pitch', old('pitch'), ['class'=>'form-control', 'placeholder'=>'pitch']) !!}
            				@endif
            			</div>
            		</div>		
            		
            		<div class="form-group">
            			{!! Form::label('bolter', 'Bolter:', ['class'=>'col-xs-2 control-label']) !!}
            			<div class="col-xs-8">
            			    @if (isset($data['id']))
				                {!! Form::text('bolter', $data['bolter'], ['class'=>'form-control', 'placeholder'=>'Bolter']) !!}
        					@else
            				    {!! Form::text('bolter', old('bolter'), ['class'=>'form-control', 'placeholder'=>'Bolter']) !!}
            				@endif
            			</div>
            		</div>	
            		
            		<div class="form-group">
            			{!! Form::label('name', 'First ascent:', ['class'=>'col-xs-2 control-label']) !!}
            			<div class="col-xs-8">
            			    @if (isset($data['id']))
				                {!! Form::text('first_ascent', $data['first_ascent'], ['class'=>'form-control', 'placeholder'=>'First ascent']) !!}
        					@else
            				    {!! Form::text('first_ascent', old('first_ascent'), ['class'=>'form-control', 'placeholder'=>'First ascent']) !!}
            				@endif
            			</div>
            		</div>
            		
            		<div class="form-group">
            			{!! Form::label('name', 'Route description:', ['class'=>'col-xs-2 control-label']) !!}
            			<div class="col-xs-8">            				
            			    @if (isset($data['id']))
                                {!! Form::textarea('text', $data['text'], ['id'=>'editor_1','class'=>'form-control', 'placeholder'=>'Page Name']) !!}
        					@else
            				    {!! Form::textarea('text', old('text'), ['id'=>'editor_1','class'=>'form-control', 'placeholder'=>'Route Name']) !!}
            				@endif
            			</div>
            		</div>
            		
            		<div class="form-group">
            			<div class="col-xs-offset-2 col-xs-10">
            				{!! Form::button('Save', ['class'=>'btn btn-primary', 'type'=>'submit']) !!}
            			</div>
            		</div>
            
            	{!! Form::close() !!}
    
    </div>
</div>

<script>
	CKEDITOR.replace('editor_1');
</script>
<script>
    CKEDITOR.config.filebrowserBrowseUrl = "{{ asset('assets/php/browse.php') }}";
    CKEDITOR.config.filebrowserUploadUrl = "{{ asset('assets/php/upload.php') }}";
</script>
