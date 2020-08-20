@extends('admin.layouts.app_admin')

@section('content')

<div class="container">

  @component('admin.components.breadcrumb')
    @slot('title') Options @endslot
    @slot('parent') Home @endslot
    @slot('active') Options @endslot
  @endcomponent

  <hr>

    <div class="container bootstrap snippet">
        <div class="row">
            <div class="col-sm-3"><!--left col-->
            
            @include('admin.components.left_block')
            
            </div><!--/col-3-->

            <div class="col-md-9">



    <div class="row">

        <div class="col-xs-12">
          <div class="row">
            <div class="col-md-6 img">
              <i style='font-size: 1000%; margin-top: 2%;' class="fa fa-user-circle-o" aria-hidden="true"></i>
            </div>
            <div class="col-md-6 details">
                <p>Name - {{ Auth::user()->name }}</p>
                <p>Surname - {{ Auth::user()->surname }}</p>
                <p>Email - {{ Auth::user()->email }}</p>
                <p>Your Status = 
                    @if(Auth::user()->hasRole('admin'))
                        Admin
                    @elseif(Auth::user()->hasRole('manager'))
                        Content manager
                    @else
                        User
                    @endif
                </p>
            </div>
        </div>



                  <form class="form" action="{{url('/user/options/user-update/'.Auth::user()->id)}}" method="post" id="registrationForm">
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
                                        @include('components.all_countries')
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
                                <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                            </div>
                      </div>
                </form>

<div class="conteiner" style='margin-top: 55%;'>
                <h1 style="text-align: center;">Admin parametars</h1>
                                  <form class="form" action="#" method="post" id="registrationForm">
                    <div class="form-group">
                <div class="checkbox">
                  <label><input type="checkbox" value="check1"/> Check Opt 1 </label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" value="check2" checked/> Check Opt 2</label>
                </div>
              </div>

                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                                <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save new parametars</button>
                            </div>
                      </div>
            </form>
          </div>

            </div>
        </div>
    </div>
</div>
@endsection