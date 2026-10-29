@extends('errors.layouts.app')

@section('title', '419 — Session Expired')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">419</div>
        <span class="cg-eyebrow">Weather window closed</span>
        <div class="error-title">Session Expired</div>
        <div class="error-desc">Your session has expired. Please refresh the page and try again.</div>
        <img class="cg-scene-img error-scene" src="{{ asset('images/scenes/419.svg') }}" alt="" aria-hidden="true">
        <div class="error-actions">
            <a href="{{ config('app.url') }}" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> climbing.ge</a>
            <a href="javascript:history.back()" class="btn btn-light btn-lg"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
