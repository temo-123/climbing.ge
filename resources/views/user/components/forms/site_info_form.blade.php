@extends('user.layouts.app')

@section('content')
    <div class="container top_menu_margin">
        @component('user.components.breadcrumb')
            @slot('title') edit site info @endslot
            @slot('active') edit site info @endslot
        @endcomponent
        <div class="form-group">
            @if(Session::has('message'))
                {{Session::get('message')}}
            @endif
        </div>
        <div class="form-group">
            <a href="{{ url()->previous() }}" class='btn btn-primary'  onclick="return confirm('Are you sure, you want go back?')">Back</a>
        </div>
    </div>

    <div class="wrapper container-fluid container">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            </div>
        @endif
        
        <site_info_edit_form
            :editing_info_id = {{ $editing_info_id }}
            back_url="{{ route('about') }}"
        ></site_info_edit_form>

    </div>
@endsection