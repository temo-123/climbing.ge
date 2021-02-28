@extends('user.layouts.app')
	@section('content')
	<div class="container top_menu_margin">

		@component('user.components.breadcrumb')
            @slot('title') Route/s edit @endslot
            @slot('parent') Home @endslot
            @slot('active') Route/s edit @endslot
		@endcomponent
	
		<div class="form-group">
			<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
                <mtp_pitch_edit_form
                    editing_pitch_id = {{ $editing_pitch_id }}
                ></mtp_pitch_edit_form>
			</div>
		</div>
	</div>
	@endsection