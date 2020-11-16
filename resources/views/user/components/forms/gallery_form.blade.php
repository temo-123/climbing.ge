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
        @if(isset($data))
        <form id="contact-form form-horizontal" name="contact-form" method="POST" action="{{ route('galleryEdit', array($data['id'])) }}" style="margin-top: 5%;" enctype ="multipart/form-data">
        @else
        <form id="contact-form form-horizontal" name="contact-form" method="POST" action="{{ route('galleryAdd') }}" style="margin-top: 5%;" enctype ="multipart/form-data">
        @endif

        @csrf

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Publish </label>
                <div class="col-xs-8">
                    <select class="form-control" name="published" id="published"> 
                        @if (isset($data))
                            <option value="0" @if ($data['published'] == '0') selected="" @endif>Not public</option> 
                            <option value="1" @if ($data['published'] == '1') selected="" @endif>Public</option> 
                        @else
                            <option value="0">Not public</option> 
                            <option value="1">Public</option> 
                        @endif
                    </select> 
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Category </label>
                <div class="col-xs-8">
                    <select class="form-control" name="category" id="category"  > 
                        @if (isset($data))
                            <option value="1" @if ($data['category'] == '1') selected="" @endif>Index header image</option> 
                            <option value="2" @if ($data['category'] == '2') selected="" @endif>Gallery image</option> 
                        @else
                            <option value="1">Index header image</option> 
                            <option value="2">Gallery image</option> 
                        @endif
                    </select> 
                </div>
            </div>
            
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Article </label>
                <div class="col-xs-4">
                    <select class="form-control" name="article_id" > 
                        @if (isset($data))
                            @if ($data['article_id'] == $image_id)
                                @foreach($articles as $article)
                                    <option value="{{$article->id}}" @if ($data['article_id'] == $article->id) selected="" @endif>{{$article->url_title}}</option>
                                @endforeach
                            @else
                                <option></option>
                                @foreach($articles as $article)
                                    <option value="{{$article->id}}">{{$article->url_title}}</option>
                                @endforeach
                            @endif
                        @else
                            @foreach($articles as $article)
                                <option value="{{$article->id}}">{{$article->url_title}}</option>
                            @endforeach
                        @endif
                    </select> 
                </div>

                <label for="name" class='col-xs-3 control-label'> Show in ingex page </label>
                <div class="col-xs-1">
                    @if (isset($data))
                        <input type="checkbox" name="index_gallery_image" value="1" @if($data['index_gallery_image'] == 1) checked="checked" @endif></input>
                    @else
                        <input type="checkbox" name="index_gallery_image" value="1"></input>
                    @endif
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Filter caregory </label>
                <div class="col-xs-8">
                    <select class="form-control" name="filter" > 
                        @if (isset($data))
                            <option value="climbing" @if ($data['filter'] == 'climbing') selected="" @endif>                climbing</option> 
                            <option value="sleckline" @if ($data['filter'] == 'sleckline') selected="" @endif>              sleckline</option> 
                            <option value="zipline" @if ($data['filter'] == 'zipline') selected="" @endif>                  zipline</option> 
                            <option value="mountaineering" @if ($data['filter'] == 'mountaineering') selected="" @endif>    mountaineering</option> 
                            <option value="sky" @if ($data['filter'] == 'sky') selected="" @endif>                          sky</option> 
                        @else
                            <option value="climbing">climbing</option> 
                            <option value="sleckline">sleckline</option> 
                            <option value="zipline">zipline</option> 
                            <option value="mountaineering">mountaineering</option> 
                            <option value="sky">sky</option> 
                        @endif
                    </select> 
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Title </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <input type="text" name="title" value="{{ $data['title'] }}" class="form-control"> 
                    @else
                        <input type="text" name="title" class="form-control"> 
                    @endif
                </div>
            </div>


            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> text </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Link </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <input type="text" name="link" class="form-control" value='{{ $data['link'] }}'> 
                    @else
                        <input type="text" name="link" class="form-control"> 
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