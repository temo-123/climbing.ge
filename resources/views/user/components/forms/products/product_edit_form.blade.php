@extends('user.layouts.app')

@section('content')
    <div class="container top_menu_margin">
        @component('user.components.breadcrumb')
            @slot('title') Edit product @endslot
            @slot('active') Edit ptoduct @endslot
        @endcomponent
        <div class="form-group">
            @if(Session::has('message'))
                {{Session::get('message')}}
            @endif
        </div>
        {{-- <div class="form-group">
            <a href="{{ url()->previous() }}" class='btn btn-primary' onclick="return confirm('Are you sure, you want go back?')">Back</a>
        </div> --}}
        <hr>
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

        @csrf 
        
        <product_edit_form  
            back_url='{{ route('products_list') }}'
            :editing_article_id = {{ $editing_article_id }}
        ></product_edit_form>
    
    </div>

@endsection