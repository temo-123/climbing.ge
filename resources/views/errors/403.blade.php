@extends('errors.layouts.app')

@section('title', '403 — Forbidden')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">403</div>
        <span class="cg-eyebrow">Closed project</span>
        <div class="error-title">Forbidden</div>
        <div class="error-desc">Sorry, you don't have permission to access this resource.</div>
        <img class="cg-scene-img error-scene" src="{{ asset('images/scenes/403.svg') }}" alt="" aria-hidden="true">
        <div class="error-actions">
            <a href="{{ config('app.url') }}" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> climbing.ge</a>
            <a href="javascript:history.back()" class="btn btn-light btn-lg"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
