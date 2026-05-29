@extends('errors.layouts.app')

@section('title', '403 — Forbidden')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">403</div>
        <div class="error-title">Forbidden</div>
        <div class="error-desc">Sorry, you don't have permission to access this resource.</div>
        @php $image_num = rand(1, 3); @endphp
        <img class="error-img" src="{{ asset('images/404/404_page/' . $image_num . '.jpg') }}" alt="403">
        <div>
            <a href="{{ URL::previous() }}" class="btn-back">Go Back</a>
        </div>
    </div>
</div>
@endsection
