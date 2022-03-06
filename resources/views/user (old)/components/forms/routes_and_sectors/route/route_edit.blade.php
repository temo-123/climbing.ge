@extends('user.layouts.app')
	@section('content')
		<div class="container top_menu_margin">

			@component('user.components.breadcrumb')
					@slot('title') Route edit @endslot
					@slot('parent') Home @endslot
					@slot('active') Route edit @endslot
			@endcomponent
		
			<div class="form-group">
				<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
			</div>
			<!-- <div class="form-grup">
				<button id="add_buttmo">Add to collection</button>
			</div> -->
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<route_edit_form
						editing_roure_id = {{ $editing_route_id }}
					></route_edit_form>
				</div>
			</div>
		</div>
	@endsection