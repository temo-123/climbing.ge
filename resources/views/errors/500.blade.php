@extends('errors.layouts.app')

@section('title', '500 — Server Error')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">500</div>
        <div class="error-title">Server Error</div>
        <div class="error-desc">Something went wrong on our end. Please try again later.</div>
        @php $image_num = rand(1, 3); @endphp
        <img class="error-img" src="{{ asset('images/404/404_page/' . $image_num . '.jpg') }}" alt="500">
        <div>
            <a href="{{ URL::previous() }}" class="btn-back">Go Back</a>
        </div>
    </div>
</div>
@endsection
