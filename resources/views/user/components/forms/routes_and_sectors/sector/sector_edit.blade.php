@extends('user.layouts.app')
	@section('header')
		@include('user.header')
	@endsection
	@section('content')
		@include('user.components.forms.routes_and_sectors.sector.partials.form')
	@endsection