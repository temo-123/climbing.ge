@extends('errors.layouts.app')

@section('title', '401 — Unauthorized')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">401</div>
        <div class="error-title">Unauthorized</div>
        <div class="error-desc">Sorry, you need to be logged in to access this page.</div>
        @php $image_num = rand(1, 3); @endphp
        <img class="error-img" src="{{ asset('images/404/404_page/' . $image_num . '.jpg') }}" alt="401">
    </div>
</div>
@endsection
