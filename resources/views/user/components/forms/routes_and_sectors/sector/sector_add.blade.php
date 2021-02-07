@extends('user.layouts.app')
@section('content')
	<div class="container top_menu_margin">
		@component('user.components.breadcrumb')
			@if (isset($data['id'])) 
				@slot('title') Sector edit @endslot
				@slot('parent') Home @endslot
				@slot('active') Sector edit @endslot
			@else 
				@slot('title') Sector add @endslot
				@slot('parent') Home @endslot
				@slot('active') Sector add @endslot
			@endif
		@endcomponent
	</div>
	
	<div class="wrapper container-fluid container">
		<sector_add_form></sector_add_form>
	</div>
@endsection