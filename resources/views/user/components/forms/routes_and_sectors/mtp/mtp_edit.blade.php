@extends('user.layouts.app')
	@section('content')
		<div class="container top_menu_margin">

			@component('user.components.breadcrumb')
					@slot('title') Multy-pitch/s edit @endslot
					@slot('parent') Home @endslot
					@slot('active') Multy-pitch/s edit @endslot
			@endcomponent
		
			<div class="form-group">
				<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<mtp_edit_form
						editing_mtp_id = {{ $editing_mtp_id }}
					></mtp_edit_form>
				</div>
			</div>
		</div>
	@endsection