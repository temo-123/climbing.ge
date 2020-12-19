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
        <form id="contact-form form-horizontal" name="contact-form" method="POST" action="{{ route('mountEdit', array($data['id'])) }}" style="margin-top: 5%;" enctype ="multipart/form-data">
        @else
        <form id="contact-form form-horizontal" name="contact-form" method="POST" action="{{ route('mountAdd') }}" style="margin-top: 5%;" enctype ="multipart/form-data">
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
            <hr>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Title english </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <input type="text" name="title" value="{{ $data['title'] }}" class="form-control"> 
                    @else
                        <input type="text" name="title" class="form-control"> 
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Title rusian </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <input type="text" name="title" value="{{ $data['title'] }}" class="form-control"> 
                    @else
                        <input type="text" name="title" class="form-control"> 
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Title georgian </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <input type="text" name="title" value="{{ $data['title'] }}" class="form-control"> 
                    @else
                        <input type="text" name="title" class="form-control"> 
                    @endif
                </div>
            </div>
            <hr>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> English text </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Rusian text </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Georgian text </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <hr>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> English description </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Rusian description </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Georgian description </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <hr>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> English how get hear </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Rusian how get hear </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Georgian how get hear </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <hr>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> English best time </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Rusian best time </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
                    @endif
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Georgian best time </label>
                <div class="col-xs-8">
                    @if (isset($data))
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control">{{ $data['text'] }}</textarea>
                    @else
                        <textarea type="text"  name="text" rows="15"class="form-cotrol md-textarea form-control"></textarea>
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