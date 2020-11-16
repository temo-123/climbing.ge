<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>geo visitors</title>
	<link rel="icon" href="{{ asset('assets/favicon.png') }}" type="image/png">

	<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="{{ asset('assets/js/jquery-1.11.0.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

	<!-- <script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script> -->
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap-filestyle.min.js') }}"> </script>
</head>
<body>
	<header id="header_wrapper">
		@yield('header')

		@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		@if (session('status'))
			<div>
				{{ session('status') }}
			</div>
		@endif
	</header>

	@yield('content')

</body>
</html>