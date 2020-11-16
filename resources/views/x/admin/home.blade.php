@extends('admin.layouts.app_admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in! <a href="{{ route('index') }}">Back to the index page</a>
                    
                </div>
            </div>
        </div>
    </div>
    

<div class="container bootstrap snippet">
    <!--<div class="row">-->
  		<!--<div class="col-sm-10"><h1>User name</h1></div>-->
    <!--	<div class="col-sm-2"><a href="/users" class="pull-right"><img title="profile image" class="img-circle img-responsive" src="http://www.gravatar.com/avatar/28fd20ccec6865e2d5f0e1f4446eb7bf?s=100"></a></div>-->
    <!--</div>-->
    <div class='col-md-12'>
        <hr />
    </div>
    <div class="row">
  		<div class="col-sm-3"><!--left col-->
  		
            @include('admin.components.left_block')
          
        </div><!--/col-3-->
    	<div class="col-sm-9">
          
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#home" data-toggle="tab">Notification</a></li>
            @if(Auth::user()->hasRole('admin'))
            <li><a href="#messages" data-toggle="tab">Messages</a></li>
            <li><a href="#messages_article" data-toggle="tab">Messages about article</a></li>
            <li><a href="#dashboard" data-toggle="tab">Dashboard</a></li>
            @endif
          </ul>
              
        <!--<div class="tab-content">-->
        <!--    <div class="tab-pane active" id="home">-->
        <!--      <div class="table-responsive">-->
        <!--        <table class="table table-hover">-->
        <div class="tab-content">
            <div class="tab-pane active" id="home">

              <h1>Welcome</h1>
              <p>Hello. we are glad you joined us.</p>

              <div class="row">
                <div class="content">
                  @if(Auth::user()->name || Auth::user()->surname || Auth::user()->country || Auth::user()->city || Auth::user()->phone_number || Auth::user()->gender || Auth::user()->email)
                  <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <strong>Danger!</strong> Your personal data is missing, this can lead to rolling problems. check page "option", and add missing information.
                  </div>
                  @endif
                </div>
              </div>
              <hr>
              
            </div><!--/tab-pane-->            



            <div class="tab-pane" id="dashboard">
              <div class="container">
                  <div class="row">
                      <div class="col-md-9">
                          <div class="panel panel-primary">
                              <div class="panel-heading">
                                  <h3 class="panel-title">
                                      <span class="glyphicon glyphicon-bookmark"></span> Quick Shortcuts</h3>
                              </div>
                              <div class="panel-body">
                                  <div class="row">
                                      <div class="col-xs-6 col-md-6">
                                        <a href="#" class="btn btn-danger btn-lg" role="button"><span class="glyphicon glyphicon-list-alt"></span> <br/>Apps</a>
                                        <a href="#" class="btn btn-warning btn-lg" role="button"><span class="glyphicon glyphicon-bookmark"></span> <br/>Bookmarks</a>
                                        <a href="#" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-signal"></span> <br/>Reports</a>
                                        <a href="#" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-comment"></span> <br/>Comments</a>
                                      </div>
                                      <div class="col-xs-6 col-md-6">
                                        <a href="#" class="btn btn-success btn-lg" role="button"><span class="glyphicon glyphicon-user"></span> <br/>Users</a>
                                        <a href="#" class="btn btn-info btn-lg" role="button"><span class="glyphicon glyphicon-file"></span> <br/>Notes</a>
                                        <a href="#" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-picture"></span> <br/>Photos</a>
                                        <a href="#" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-tag"></span> <br/>Tags</a>
                                      </div>
                                  </div>
                                  <a href="http://www.jquery2dotnet.com/" class="btn btn-success btn-lg btn-block" role="button"><span class="glyphicon glyphicon-globe"></span> Website</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <style type="text/css">
                body { padding-top:20px; }
              .panel-body .btn:not(.btn-block) { width:120px;margin-bottom:10px; }
              </style>
            </div><!--/tab-pane-->
             
             
             
            @if(Auth::user()->hasRole('admin'))
            
             <div class="tab-pane" id="messages">
            	<h2>Send message</h2>

                <form action="{{url('message_send')}}">
              		<div class="form-group">
                        <input type="text" class="form-control" name="Name" autocomplete="off" id="Name" placeholder="Name">
              		</div>

              		<div class="form-group">
                        <input type="email" name="email" class="form-control textarea" placeholder="E_mail"><br>
              		</div>

              		<div class="form-group">
                        <textarea class="form-control textarea" rows="10" name="Message" id="Message" placeholder="Message"></textarea>
              		</div>

                    <div class="form-group">
                      <button type="submit">Send</button>
                    </div>
                  </form>
              
               <h2>Message</h2>
               
               <ul class="list-group">
                  <li class="list-group-item text-muted">Inbox</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Here is your a link to the latest summary report from the..</a> 2.13.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Hi Joe, There has been a request on your account since that was..</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Nullam sapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Thllam sapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Wesm sapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">For therepien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Also we, havesapien massaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  <li class="list-group-item text-right"><a href="#" class="pull-left">Swedish chef is assaortor. A lobortis vitae, condimentum justo...</a> 2.11.2014</li>
                  
                </ul> 
             </div><!--/tab-pane-->
             
                 <!--Filter-->
    <!-- <script src="{{ asset('assets/js/filter.js') }}" type="text/javascript"></script> -->
             
             <div class="tab-pane" id="messages_article">
            	<h2>Send message to user about article</h2>
                    <div align="center">
                        <button class="filter-button" data-filter="all">All</button>
                        <button class="filter-button" data-filter="outdoor">Outdoor</button>
                        <button class="filter-button" data-filter="indoor">Indoor</button>
                        <button class="filter-button" data-filter="ice">Ice</button>
                        <button class="filter-button" data-filter="other">Other</button>
                        <button class="filter-button" data-filter="news">News</button>
                        <button class="filter-button" data-filter="event">Eventa</button>
                    </div>
                    <hr>
    		        <form action="{{url('message_to_all_user')}}">
    		        <div class="form-group">
        				<select class="form-control" name="article">
                			<option  class="form-control filter outdoor" value="no_aerticle">Article</option>
        				    @if(isset($outdoors))
        				        @foreach($outdoors as $outdoor)
                			        <option  class="form-control filter outdoor" value="{{$outdoor -> title}}">{{$outdoor -> article_title}}</option>
        				        @endforeach
        				    @endif
        				    @if(isset($indoors))
        				        @foreach($indoors as $indoor)
                			        <option  class="form-control filter indoor" value="{{$indoor -> title}}">{{$indoor -> article_title}}</option>
        				        @endforeach
        				    @endif
        				    @if(isset($ices))
        				        @foreach($ices as $ice)
                			        <option  class="form-control filter ice" value="{{$ice -> title}}">{{$ice -> article_title}}</option>
        				        @endforeach
        				    @endif
        				    @if(isset($others))
        				        @foreach($others as $other)
                			        <option  class="form-control filter other" value="{{$other -> title}}">{{$other -> article_title}}</option>
        				        @endforeach
        				    @endif
        				    @if(isset($news))
        				        @foreach($news as $new)
                			        <option  class="form-control filter news" value="{{$new -> title}}">{{$new -> article_title}}</option>
        				        @endforeach
        				    @endif
        				    @if(isset($events))
        				        @foreach($events as $event)
                			        <option  class="form-control filter event" value="{{$event -> title}}">{{$event -> article_title}}</option>
        				        @endforeach
        				    @endif
        				</select>
            		</div>

              		<div class="form-group">
                        <textarea name="msg" id="msg" placeholder="Page Name" class="form-control"></textarea>
              		</div>

                    <div class="form-group">
                      <button type="submit">Send</button>
                    </div>
                </form>
             </div><!--/tab-pane--> 
              @endif

        	</div>
        		    
              </div>
               
              </div><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
</div>

        	<script>
        	    CKEDITOR.replace('msg');

                // CKEDITOR.config.filebrowserBrowseUrl = "{{ asset('assets/php/browse.php') }}";
                // CKEDITOR.config.filebrowserUploadUrl = "{{ asset('assets/php/upload.php') }}";
            </script>

@endsection
