<div class="container">
    <div class="header_box">
      	@if(isset($menu))
			<nav class="navbar navbar-inverse" role="navigation">
      			<div class="logo" style="width: 10%;" >
      				<a href="#">
      					<img src="{{ asset('assets/img/logo.jpg') }}" alt="logo">
      				</a>
      			</div>
		      	<div class="navbar-header">
	      			<!-- <h1 style="font-size: 350%; color: #616161;">geo visitor</h1> -->
			        <button type="button" id="nav-toggle" class="navbar-toggle" data-toggle="collapse" data-target="#main-nav"> 
			        	<span class="sr-only">Toggle navigation</span> 
			        	<span class="icon-bar"></span> 
			        	<span class="icon-bar"></span> 
			        	<span class="icon-bar"></span> 
			        </button>
		        </div>
			    <div id="main-nav" class="collapse navbar-collapse navStyle">
					<ul class="nav navbar-nav" id="mainNav">
						@foreach ($menu as $item)
					  		<li><a href="#{{ $item['alias'] }}" class="scroll-link">{{ $item['title'] }}</a></li>
						@endforeach
					</ul>
		      	</div>
			</nav>
		@endif
    </div>
</div>