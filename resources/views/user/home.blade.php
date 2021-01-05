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
                                <div class="col-md-6">
                                    <a href='{{ route('index') }}'>climbing.ge</a>
                                </div>
                                <div class="col-md-6">
                                    <a href='{{ route('shop_index') }}'>shop.climibng.ge</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                    @if(Auth::user()->name || Auth::user()->surname || Auth::user()->country || Auth::user()->city ||  Auth::user()->gender || Auth::user()->email)
                        <div class="alert alert-danger" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Danger!</strong> Your personal data is missing, this can lead to rolling problems. check page "option", and add missing information.
                        </div>
                    @endif
                    @if(Auth::user()->hasRole('admin'))
                    @if($site->text || $site->text_ru || $site->text_ka || $site->short_description_ru || $site->short_description_ka || $site->short_description)
                        <div class="alert alert-danger" role="alert">

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Danger!</strong> Your web-site information is not fool. check page "About us", and add missing information.
                        </div>
                    @endif
                    @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace('msg');

        CKEDITOR.config.filebrowserBrowseUrl = "{{ asset('assets/php/browse.php') }}";
        CKEDITOR.config.filebrowserUploadUrl = "{{ asset('assets/php/upload.php') }}";


    $('#myAlert').on('closed.bs.alert', function () {
        $(".alert").alert('close')
    })
</script>

@endsection
