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
        <form id="contact-form form-horizontal" name="contact-form" method="POST" action="{{ route('productsEdit', array($data['id'])) }}" style="margin-top: 5%;" enctype ="multipart/form-data">
        @else
        <form id="contact-form form-horizontal" name="contact-form" method="POST" action="" style="margin-top: 5%;" enctype ="multipart/form-data">
        @endif

        @csrf

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> category </label>
                <div class="col-xs-8">
                    <select class="form-control" name="category"> 
                        @if (isset($data)) 
                            <option value="climbing" @if ($data['category'] == 'climbing') selected="" @endif>      climbing</option>
                            <option value="sky" @if ($data['category'] == 'sky') selected="" @endif>                sky & snow</option>
                            <option value="mount" @if ($data['category'] == 'mount') selected="" @endif>            mountaineering</option>
                            <option value="hiking" @if ($data['category'] == 'hiking') selected="" @endif>          hiking</option>
                            <option value="sleckline" @if ($data['category'] == 'sleckline') selected="" @endif>    sleckline</option>
                            <option value="outdoor" @if ($data['category'] == 'outdoor') selected="" @endif>        outdoor</option>
                            <option value="other" @if ($data['category'] == 'other') selected="" @endif>            other</option>
                        @else 
                            <option value="climbing">climbing</option> 
                            <option value="sky">sky & snow</option> 
                            <option value="mount">mountaineering</option> 
                            <option value="hiking">hiking</option> 
                            <option value="sleckline">sleckline</option> 
                            <option value="outdoor">outdoor</option> 
                            <option value="other">other</option> 
                        @endif 
                    </select> 
                </div>
            </div>	

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Product name </label>
                <div class="col-xs-8">
                    @if (isset($data)) 
                        <input type="text" name="title" value="{{ $data['title'] }}" class="form-control">
                    @else
                        <input type="text" name="title" class="form-control"> 
                    @endif
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'>Text / Description </label>
                <div class="col-xs-8">
                    @if (isset($data)) 
                        <textarea type="text" id="text" name="text" rows="15" class="form-cotrol md-textarea form-control">{{$data['text']}}</textarea>
                    @else
                        <textarea type="text" id="text" name="text" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> price </label>
                <div class="col-xs-6">
                    @if (isset($data)) 
                        <input type="text" name="price" value="{{ $data['price'] }}" class="form-control">
                    @else
                        <input type="text" name="price" class="form-control"> 
                    @endif
                </div>
                <div class="col-xs-2">
                    <select class="form-control" name="currency"> 
                        @if (isset($data)) 
                            <option value="GEL" @if ($data['currency'] == 'GEL') selected="" @endif>      GEL</option>
                            <option value="sky" @if ($data['currency'] == 'USD') selected="" @endif>                USD</option>
                            <option value="mount" @if ($data['currency'] == 'EUR') selected="" @endif>            EUR</option>
                        @else 
                            <option value="GEL">GEL</option> 
                            <option value="USD">USD</option> 
                            <option value="mount">EUR</option>
                        @endif 
                    </select> 
                </div>
            </div>
            

            
            <div class="form-group clearfix">
                <label for="name" class="col-xs-2 control-label">Select image to upload:</label>
                <div class="col-xs-8">			
                @if (isset($data))
                    <div class="row">
                        <div class="col-xs-4">       
                            <div class="avatar-edit">
                                @if($data['general_image'] == 1)
                                <input type="radio" name="general_image" value="1" checked="checked">
                                @else
                                <input type="radio" name="general_image" value="1">
                                @endif
                                <input type='file' id="image" name="image_1" onchange="readURL(this);" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                                <img id="blah" src="{{asset('images/shop_img/'.$data['image_1'])}}" class="" width="200" height="150" style="margin: 5%" />
                                <input type="hidden" id="old_image_1" name="old_image_1" value="{{$data['image_1']}}">
                            </div>
                        </div>
                        <div class="col-xs-4">      
                            <div class="avatar-edit">
                                @if($data['general_image'] == 2)
                                <input type="radio" name="general_image" value="1" checked="checked">
                                @else
                                <input type="radio" name="general_image" value="1">
                                @endif
                                <input type='file' id="image" name="image_2" onchange="readURL(this);" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                                <img id="blah" src="{{asset('images/shop_img/'.$data['image_2'])}}" class="" width="200" height="150" style="margin: 5%" />
                                <input type="hidden" id="old_image_2" name="old_image_2" value="{{$data['image_2']}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">      
                            <div class="avatar-edit">
                                @if($data['general_image'] == 3)
                                <input type="radio" name="general_image" value="1" checked="checked">
                                @else
                                <input type="radio" name="general_image" value="1">
                                @endif
                                <input type='file' id="image" name="image_3" onchange="readURL(this);" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                                <img id="blah" src="{{asset('images/shop_img/'.$data['image_3'])}}" class="" width="200" height="150" style="margin: 5%" />
                                <input type="hidden" id="old_image_3" name="old_image_3" value="{{$data['image_3']}}">
                            </div>
                        </div>
                        <div class="col-xs-4">      
                            <div class="avatar-edit">
                                @if($data['general_image'] == 4)
                                <input type="radio" name="general_image" value="1" checked="checked">
                                @else
                                <input type="radio" name="general_image" value="1">
                                @endif
                                <input type='file' id="image" name="image_4" onchange="readURL(this);" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                                <img id="blah" src="{{asset('images/shop_img/'.$data['image_4'])}}" class="" width="200" height="150" style="margin: 5%" />
                                <input type="hidden" id="old_image_4" name="old_image_4" value="{{$data['image_4']}}">
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-xs-4">       
                            <input type="radio" name="general_image" value="1">
                            <input type="file" name="image_1" id="image_1" value="{{ csrf_token() }}">
                            <label for="imageUpload"></label>
                        </div>
                        <div class="col-xs-4">
                            <input type="radio" name="general_image" value="2">
                            <input type="file" name="image_2" id="image_2" value="{{ csrf_token() }}">
                            <label for="imageUpload"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <input type="radio" name="general_image" value="3">
                            <input type="file" name="image_3" id="image_3" value="{{ csrf_token() }}">
                            <label for="imageUpload"></label>
                        </div>
                        <div class="col-xs-4">
                            <input type="radio" name="general_image" value="4">
                            <input type="file" name="image_4" id="image_4" value="{{ csrf_token() }}">
                            <label for="imageUpload"></label>
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