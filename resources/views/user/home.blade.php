@extends('user.layouts.app')

@section('content')
<div class="container top_menu_margin">
    <div class="container bootstrap snippet mt-5 ">
        <div class="row">
            <div class="col-sm-3">

                @include('user.components.left_block')

            </div>
            <div class="col-sm-9">

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">{{ __('Dashboard') }}</div>

                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}

                                        @if(Auth::user()->hasRole('user'))
                                            <h1>user</h1>
                                        @endif
                                    </div>
                                @endif

                                {{ __('You are logged in!') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Sites index pages</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <a href='{{ route('index') }}' target="_blank">climbing.ge</a>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6">
                                        <a href='{{ route('shop_index') }}' target="_blank">shop.climibng.ge</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                    @if(Auth::user()->name == NULL || Auth::user()->surname == NULL || Auth::user()->country == NULL || Auth::user()->city == NULL || Auth::user()->email == NULL)
                        <div class="alert alert-danger" role="alert">
                            <strong>Danger!</strong> Your personal data is missing, this can lead to rolling problems. check page "option", and add missing information.
                        </div>
                    @endif
                    @if(Auth::user()->hasRole('admin'))
                    @if($site->text  == NULL || $site->text_ru  == NULL || $site->text_ka  == NULL || $site->short_description_ru  == NULL || $site->short_description_ka  == NULL || $site->short_description == NULL )
                        <div class="alert alert-danger" role="alert">
                            <strong>Danger!</strong> Your web-site information is not fool. check page "About us", and add missing information.
                        </div>
                    @endif
                    @endif
                    @if(Auth::user()->email_verified_at == NULL)
                    <div class="alert alert-danger" role="alert">
                        We sent you an email for verification, please check your email and confirm it.
                    </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
