@extends('errors.layouts.app')

@section('title', '419 — Session Expired')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">419</div>
        <div class="error-title">Session Expired</div>
        <div class="error-desc">Your session has expired. Please refresh the page and try again.</div>
        @php $image_num = rand(1, 3); @endphp
        <img class="error-img" src="{{ asset('images/404/404_page/' . $image_num . '.jpg') }}" alt="419">
    </div>
</div>
@endsection
