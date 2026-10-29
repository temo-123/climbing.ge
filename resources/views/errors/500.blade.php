@extends('errors.layouts.app')

@section('title', '500 — Server Error')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">500</div>
        <span class="cg-eyebrow">Rope's stuck</span>
        <div class="error-title">Server Error</div>
        <div class="error-desc">Something went wrong on our end. Please try again later.</div>
        <img class="cg-scene-img error-scene" src="{{ asset('images/scenes/500.svg') }}" alt="" aria-hidden="true">
        <div class="error-actions">
            <a href="{{ config('app.url') }}" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> climbing.ge</a>
            <a href="javascript:history.back()" class="btn btn-light btn-lg"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
