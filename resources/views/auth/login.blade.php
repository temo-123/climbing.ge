@extends('site.layouts.app')

@section('content')
    @section('title', 'Login')
    @section('meta_keyword', 'Login')
    @section('meta_description', 'Login')
    @section('meta_img', asset('assets/img/meta_img/indoor.jpg'))

    <div class="container top_menu_margin">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-header text-center"><h1>{{ __('Login') }}</h1></div>

                    {{-- <form>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <a href="{{ url('/login/facebook') }}" class="btn btn-facebook btn btn-primary"> Facebook</a>
                                
                            </div>

                            <a class="ml-1 btn btn-primary" href="{{ url('redirect') }}" style="margin-top: 0px !important;background: #4c6ef5;color: #ffffff;padding: 5px;border-radius:7px;" id="btn-fblogin">
                                <i class="fa fa-facebook-square" aria-hidden="true"></i> Login with Facebook
                            </a>
                            <div class="col-md-2 pull-right">
                                <a href="{{ url('/login/google') }}" class="btn btn-facebook btn btn-primary"> Google</a>
                                
                            </div>
                        </div>
                    </form> --}}

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <div class="col-md-12 alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                
                                    <a href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>

                                    @error('password')
                                        <div class="col-md-12 alert alert-danger">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-3 col-md-offset-4">

                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                </div>
                                <div class="col-md-5">
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                </div>
                            </div>  
                            
                            <hr>
                            <p align="center">OR</p>
                            
                            <div class="form-group row mb-0">
                                <div class="col-md-6 col-md-offset-4">
                                    <a class="btn btn-primary" href="{{route('register')}}">Register</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
