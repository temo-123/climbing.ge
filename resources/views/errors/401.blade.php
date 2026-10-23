@extends('errors.layouts.app')

@section('title', '401 — Unauthorized')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">401</div>
        <span class="cg-eyebrow">Tie in first</span>
        <div class="error-title">Unauthorized</div>
        <div class="error-desc">Sorry, you need to be logged in to access this page.</div>
        <img class="cg-scene-img error-scene" src="{{ asset('images/scenes/401.svg') }}" alt="" aria-hidden="true">
        <div class="error-actions">
            <a href="{{ config('app.url') }}" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> climbing.ge</a>
            <a href="javascript:history.back()" class="btn btn-light btn-lg"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
