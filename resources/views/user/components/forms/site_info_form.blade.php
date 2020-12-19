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
        <form id="contact-form form-horizontal" name="contact-form" method="POST" action="{{ route('galleryAdd') }}" style="margin-top: 5%;" enctype ="multipart/form-data">


        @csrf

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Title </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <input type="text" name="title" value="{{ $data['title'] }}" class="form-control"> 
                    @endif
                </div>
            </div>


            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> text </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @endif
                </div>
            </div>

            
            <div class="form-group clearfix">
                <label for="name" class="col-xs-2 control-label">Select image to upload:</label>
                <div class="col-xs-8">
                @if (isset($data))
                    <div class="row">
                        <div class="col-xs-4">       
                            <div class="avatar-edit">
                                <input type='file' id="image" name="image" onchange="readURL(this);" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                                <img id="blah" src="{{asset('images/gallery_img/'.$data['image'])}}" class="" width="200" height="150" style="margin: 5%" />
                                <input type="hidden" id="old_image" name="old_image" value="{{$data['image']}}">
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-xs-4">
                            <input type="file" name="image" id="image" value="{{ csrf_token() }}">
                            {{-- <label for="imageUpload"></label> --}}
                        </div>
                    </div>
                @endif
                </div>
            </div>
            
            <div class="container">
                <div class="form-group">
                    <button class='btn btn-primary'>Save</button>
                </div>
            </div>

        </form>
        
    </div>
@endsection