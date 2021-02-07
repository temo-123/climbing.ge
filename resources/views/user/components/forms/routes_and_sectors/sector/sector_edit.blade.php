@extends('user.layouts.app')
@section('content')
	<div class="container top_menu_margin">
		@component('user.components.breadcrumb')
				@slot('title') Sector edit @endslot
				@slot('parent') Home @endslot
				@slot('active') Sector edit @endslot
		@endcomponent
	  
		<div class="form-group">
			<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
		</div>
	</div>
	
	<div class="wrapper container-fluid container">
		<sector_edit_form
			editing_sector_id = {{ $editing_sector_id }}
		></sector_edit_form>
	</div>
@endsection