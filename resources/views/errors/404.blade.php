@extends('errors.layouts.app')

@section('title', '404 — Page Not Found')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">404</div>
        <span class="cg-eyebrow">Off route</span>
        <div class="error-title">Page Not Found</div>
        <div class="error-desc">Sorry, the page you were looking for could not be found.</div>
        <img class="cg-scene-img error-scene" src="{{ asset('images/scenes/lost.svg') }}" alt="" aria-hidden="true">
        <div class="error-actions">
            <a href="{{ config('app.url') }}" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> climbing.ge</a>
            <a href="javascript:history.back()" class="btn btn-light btn-lg"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
