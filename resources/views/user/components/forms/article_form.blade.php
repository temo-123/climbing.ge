@extends('user.layouts.app')

@section('content')
    <div class="container top_menu_margin">
        @component('user.components.breadcrumb')
            @if(isset($data))
                @slot('title') {{$edit_title}} @endslot
                @slot('active') {{$edit_active}} @endslot
            @else
                @slot('title') {{$add_title}} @endslot
                @slot('active') {{$add_active}} @endslot
            @endif
        @endcomponent
        <div class="form-group">
            @if(Session::has('message'))
                {{Session::get('message')}}
            @endif
        </div>
        <div class="form-group">
            <a href="{{ url()->previous() }}" class='btn btn-primary'>Back</a>
        </div>
        <div class="form-group">  
            <input type="submit" form="myform" />
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
        
        @csrf <article_form_component  category="{{$category}}" add_url="{{ route('articleAdd', array($category)) }}"></article_form_component>
    
    </div>

@endsection