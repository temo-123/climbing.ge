@extends('layouts.app')

@section('title', 'Login')

@section('meta_title', 'Login')
@section('meta_keyword', 'Climbing in Georgia')
@section('meta_description', 'Login in climbing.ge')
@section('meta_img', (asset('assets/img/meta_img/outdoor.jpg')))

  @section('content')
<!-- <div class="container top_menu_margin">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><H1>Login</H1></div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <p>If you don't have an account yet, you can register</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a class="btn btn-link" href="{{route('register')}}">Regidter</a>
                            </div>
                        </div>
                        
                </div>
            </div>
        </div>
    </div>
</div> -->






<div class="container top_menu_margin">
  <div class="row">

    <div class="main">

      <h3>Please Log In, or <a href="{{route('register')}}">Sign Up</a></h3>
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
          <a href="{{url('/redirect_fb')}}" class="btn btn-lg btn-primary btn-block">Facebook</a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
          <a href="{{url('/redirect_google')}}" class="btn btn-lg btn-info btn-block">Google</a>
        </div>
      </div>
      <div class="login-or">
        <hr class="hr-or">
        <span class="span-or">or</span>
      </div>

      <form role="form" action="{{ route('login') }}">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
          <label for="inputUsernameEmail">Username or email</label>
          <!-- <input type="text" class="form-control" id="inputUsernameEmail"> -->

          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

          @if ($errors->has('email'))
          <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
          @endif
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
          <a class="pull-right" href="{{ route('password.request') }}">Forgot password?</a>
          <input id="password" type="password" class="form-control" name="password" required>

          @if ($errors->has('password'))
          <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
          </span>
          @endif
          <!-- <label for="inputPassword">Password</label> -->
          <!-- <input type="password" class="form-control" id="inputPassword"> -->
          <input type="checkbox" onclick="Login_password_show()">Show Password
        </div>
        <div class="checkbox pull-right">
          <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </div>
        <button type="submit" class="btn btn btn-primary">
          Log In
        </button>
      </form>

    </div>

  </div>
</div>
@endsection