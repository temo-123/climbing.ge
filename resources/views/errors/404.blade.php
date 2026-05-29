@extends('errors.layouts.app')

@section('title', '404 — Page Not Found')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">404</div>
        <div class="error-title">Page Not Found</div>
        <div class="error-desc">Sorry, the page you were looking for could not be found.</div>
        @php $image_num = rand(1, 3); @endphp
        <img class="error-img" src="{{ asset('images/404/404_page/' . $image_num . '.jpg') }}" alt="404">
        <div>
            <a href="{{ URL::previous() }}" class="btn-back">Go Back</a>
        </div>
    </div>
</div>
@endsection
