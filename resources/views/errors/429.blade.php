@extends('errors.layouts.app')

@section('title', '429 — Too Many Requests')

@section('content')
<div class="error-body">
    <div class="error-card">
        <div class="error-code">429</div>
        <span class="cg-eyebrow">Queue on the route</span>
        <div class="error-title">Too Many Requests</div>
        <div class="error-desc">Too many requests in a short time. Take a breather and try again in a minute.</div>
        <img class="cg-scene-img error-scene" src="{{ asset('images/scenes/429.svg') }}" alt="" aria-hidden="true">
        <div class="error-actions">
            <a href="{{ config('app.url') }}" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> climbing.ge</a>
            <a href="javascript:history.back()" class="btn btn-light btn-lg"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
