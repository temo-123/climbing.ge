@extends('layouts.app')

@section('content')

<div class="container top_menu_margin">
    <div class="row">

        <div class="main">

            <h3>Please Log In, or <a href="#">Sign Up</a></h3>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <a href="#" class="btn btn-lg btn-primary btn-block">Facebook</a>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <a href="#" class="btn btn-lg btn-info btn-block">Google</a>
                </div>
            </div>
            <div class="login-or">
                <hr class="hr-or">
                <span class="span-or">or</span>
            </div>
            <form class="" method="POST" action="{{ route('register') }}" role="form">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label">Name*</label>

                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                    <label for="surname" class="control-label">Surname*</label>

                    <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>

                    @if ($errors->has('surname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('surname') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                    <label for="surname" class="control-label">Country*</label>

                    <select class="form-control" name="country"> 
                        @include('components.all_countries')
                    </select>
                </div>  

                <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                    <label for="number" class="control-label">Phon number</label>

                    <input id="number" type="text" class="form-control" name="number" value="{{ old('number') }}" required autofocus>

                    @if ($errors->has('number'))
                    <span class="help-block">
                        <strong>{{ $errors->first('number') }}</strong>
                    </span>
                    @endif
                </div>  

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">E-Mail Address*</label>

                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label">Password*</label>

                    <input id="password" type="password" class="form-control" name="password" required>

                    <input type="checkbox" onclick="Register_password_show()">Show Password

                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="control-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </form>

        </div>

    </div>
</div>
@endsection
