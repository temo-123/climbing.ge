@extends('errors.layouts.app')

@section('title', '503 — Service Unavailable')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">503</div>
        <span class="cg-eyebrow">Rebolting the route</span>
        <div class="error-title">Service Unavailable</div>
        <div class="error-desc">We are doing some maintenance. climbing.ge will be back shortly.</div>
        <img class="cg-scene-img error-scene" src="{{ asset('images/scenes/bolter.svg') }}" alt="" aria-hidden="true">
        <div class="error-actions">
            <a href="{{ config('app.url') }}" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> climbing.ge</a>
            <a href="javascript:history.back()" class="btn btn-light btn-lg"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
