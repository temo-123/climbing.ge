@extends('user.layouts.app')
	@section('content')
		<div class="container top_menu_margin">

			@component('user.components.breadcrumb')
				@if (isset($data['id'])) 
					@slot('title') Multy-pitch/s edit @endslot
					@slot('parent') Home @endslot
					@slot('active') Multy-pitch/s edit @endslot
				@else 
					@slot('title') Multy-pitch/s add @endslot
					@slot('parent') Home @endslot
					@slot('active') Multy-pitch/s add @endslot
				@endif
			@endcomponent
		
			<div class="form-group">
				<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<mtp_add_form></mtp_add_form>
				</div>
			</div>
		</div>
	@endsection