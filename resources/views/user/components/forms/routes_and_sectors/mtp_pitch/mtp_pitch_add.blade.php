@extends('user.layouts.app')
	@section('content')
	<div class="container top_menu_margin">

		@component('user.components.breadcrumb')
			@if (isset($data['id'])) 
				@slot('title') Route/s edit @endslot
				@slot('parent') Home @endslot
				@slot('active') Route/s edit @endslot
			@else 
				@slot('title') Route/s add @endslot
				@slot('parent') Home @endslot
				@slot('active') Route/s add @endslot
			@endif
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
				<mtp_pitch_add_form></mtp_pitch_add_form>
			</div>
		</div>
	</div>
	@endsection