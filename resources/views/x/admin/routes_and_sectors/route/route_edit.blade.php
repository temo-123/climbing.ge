@extends ('admin.layouts.app_admin')
	@section('header')
		@include('admin.header')
	@endsection
	@section('content')
		@include('admin.routes_and_sectors.route.partials.form')
	@endsection