@extends('user.layouts.app')

@section('content')
    <div class="container top_menu_margin">
    @component('user.components.breadcrumb')
        
        @if(isset($page_name)&&isset($table_1)&&isset($table_2))
            @slot('title') {{$page_name}} list @endslot
        @elseif(isset($table_1_name))
            @if(isset($table_1_count))
                @slot('title') {{$table_1_name}} list ({{$table_1_count}}) @endslot
            @else
                @slot('title') {{$table_1_name}} list @endslot
            @endif
        @endif
        
        @slot('parent') Home @endslot
        @slot('active') {{$active}} @endslot
    @endcomponent

        <div class="bootstrap snippet">
            <div class='col-md-12'>
                <hr />
            </div>
            <div class="row">
                <div class="col-sm-3">
                    @include('user.components.left_block')
                </div>
                <div class="col-sm-9">

                        <options_form :status="{{ json_encode($status) }}" ></options_form>

                        {{--  --}}

          {{-- <form class="form" action="{{url('/options/user-update/'.Auth::user()->id)}}" method="post" id="registrationForm">
            <div class="form-group">
{{ csrf_field() }}
{{ method_field('patch') }}
                  
                  <div class="col-xs-6">
                      <label for="name"><h4>First name</h4></label>
                      <input type="text" class="form-control" name="name" id="name" placeholder="first name" title="enter your first name if any.">
                  </div>
              </div>
              <div class="form-group">
                  
                  <div class="col-xs-6">
                    <label for="surname"><h4>Last name</h4></label>
                      <input type="text" class="form-control" name="surname" id="surname" placeholder="last name" title="enter your last name if any.">
                  </div>
              </div>
  
              <div class="form-group">
                  
                  <div class="col-xs-6">
                      <label for="phone_number"><h4>Phone number</h4></label>
                      <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="enter phone_number" title="enter your phone number if any.">
                  </div>
              </div>
              <div class="form-group">
                  
                  <div class="col-xs-6">
                      <label for="email"><h4>Email</h4></label>
                      <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email.">
                  </div>
              </div>

                <div class="form-group">
                  <div class="col-md-6">
                      <label for="Location"><h4>Location</h4></label>
                            <select class="form-control" name="country"> 
                                
                            </select>
                    </div>
                </div>
                <div class="form-group">
                  
                  <div class="col-xs-6">
                      <label for="city"><h4>Yor city</h4></label>
                      <input type="text" class="form-control" name="city" id="city" placeholder="somewhere" title="enter a city">
                  </div>
              </div>
              <div class="form-group">
                  
                  <div class="col-xs-6">
                      <label for="password"><h4>Password</h4></label>
                      <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                  </div>
              </div>
              <div class="form-group">
                  
                  <div class="col-xs-6">
                    <label for="password2"><h4>Verify</h4></label>
                      <input type="password" class="form-control" name="password2" id="password2" placeholder="password2" title="enter your password2.">
                  </div>
              </div>
              <div class="form-group">
                   <div class="col-xs-12">
                        <br>
                        <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save new user information</button>
                    </div>
              </div>
        </form> --}}


                        
                </div>
            </div>
        </div>
    </div>
@endsection