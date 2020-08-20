@extends('admin.layouts.app_admin')
	@section('header')
		@include('admin.header')
	@endsection
	
	@section('content')

        <div class="container">
            @component('admin.components.breadcrumb')
                @if(isset($data))
                    @slot('title') {{$edit_title}} @endslot
                    @slot('active') {{$edit_active}} @endslot
                @else
                    @slot('title') {{$add_title}} @endslot
                    @slot('active') {{$add_active}} @endslot
                @endif
            @endcomponent
        	<div class="form-group">
            	@if(Session::has('message'))
            		{{Session::get('message')}}
            	@endif
            </div>
        	<div class="form-group">
        		<a href="{{route($back_btn)}}" class='btn btn-primary'>Back</a>
        	</div>
        </div>
    
        <div class="wrapper container-fluid">
            @if (count($errors) > 0)
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            
            
        	<div class="container">
        		<div class="form-group">
        			<div class="col-xs-offset-2 col-xs-10">
        				<button>Save</button>
        			</div>
        		</div>
    		</div>
        
        
        	<script>
                @if (isset($text))
        		    CKEDITOR.replace('text');
                @endif
            	@if (isset($description_short))
                    CKEDITOR.replace('description_short');
                @endif
            	@if (isset($route_text))
                    CKEDITOR.replace('route_text');
                @endif
            	@if (isset($route))
                    CKEDITOR.replace('route');
                @endif
    			@if (isset($address))
                    CKEDITOR.replace('address');
                @endif
        		@if (isset($info))
                    CKEDITOR.replace('info');
                @endif
           	    @if (isset($how_get))
                    CKEDITOR.replace('how_get');
                @endif
                @if (isset($what_need))
                    CKEDITOR.replace('what_need');
                @endif
                @if (isset($prices_text))
                    CKEDITOR.replace('prices_text');
                @endif
                @if (isset($best_time))
                    CKEDITOR.replace('best_time');
                @endif
                @if(isset($slider_img))
                    CKEDITOR.replace('slider_text_1');
                    CKEDITOR.replace('slider_text_2');
                    CKEDITOR.replace('slider_text_3');
                    CKEDITOR.replace('slider_text_4');
                    CKEDITOR.replace('slider_text_5');
                @endif
        		@if(isset($actyvity_list))
            		CKEDITOR.replace('actyvyrty_1');
            		CKEDITOR.replace('actyvyrty_2');
            		CKEDITOR.replace('actyvyrty_3');
            		CKEDITOR.replace('actyvyrty_4');
            	@endif
        	</script>
        	<script>
                CKEDITOR.config.filebrowserBrowseUrl = "{{ asset('assets/php/browse.php') }}";
                CKEDITOR.config.filebrowserUploadUrl = "{{ asset('assets/php/upload.php') }}";
            </script>
        
        </div>

	@endsection
	