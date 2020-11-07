<div class="container top_menu_margin">

	@component('user.components.breadcrumb')
	    @if (isset($data['id'])) 
    		@slot('title') Sector edit @endslot
    		@slot('parent') Home @endslot
    		@slot('active') Sector edit @endslot
		@else 
    		@slot('title') Sector add @endslot
    		@slot('parent') Home @endslot
    		@slot('active') Sector add @endslot
		@endif
	@endcomponent
  
	<div class="form-group">
		<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
	</div>
</div>

<div class="wrapper container-fluid container">

	@if (isset($data['id']))
	<form action="{{ route('sectorEdit', array('page'=>$data['id'])) }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data"> 
	@else 
	<form action="{{ route('sectorAdd') }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
	@endif
	@csrf

		<div class="form-group clearfix">
			<label for="name" class='col-md-2 control-label'> Sector name </label>
			<div class="col-md-8">
				@if (isset($data['id']))
					<input type="text" name="name" value="{{$data['name']}}" class="form-control textarea" placeholder="Name">
				@else
					<input type="text" name="name" placeholder="Name" class="form-control textarea">
				@endif
			</div>
		</div>

		<div class="form-group clearfix">
			<label class='col-md-2 control-label'> Region </label>
			<div class="col-md-8">
				<select class="form-control" name="article_id"> 
					@if (isset($data)) 
						<option value="0" @if ($data['published'] == 0) selected="" @endif>Not public</option>
						<option value="1" @if ($data['published'] == 1) selected="" @endif>Public</option>
					@else 
						@foreach($tegions_name as $region)
							<option value="{{$region->id}}">{{$region->title}}</option> 
						@endforeach
					@endif 
				</select> 
			</div>
		</div>

		<div class="form-group clearfix">
			<label class='col-md-2 control-label'> Text </label>
        	<div class="col-md-8">
					@if (isset($data['id']))
	                    <textarea name="text" id="text" value="{{$data['text']}}" placeholder="Text Area" class="form-control textarea"></textarea>
					@else
	                    <textarea name="text" id="text" placeholder="Text Area" class="form-control textarea"></textarea>
					@endif
			</div>
		</div>	

		<div class="form-group clearfix">
			<label class='col-md-2 control-label'> Shadow time: </label>
			<div class="col-md-8">
				<div class="col-md-3">
					@if (isset($data['id']))
						<input type="checkbox" name="vehicle3" value="Boat" checked>
						<label for="vehicle3"> I have a boat</label><br><br>
					@else
						<label for="vehicle1" class="sector_checbox_title"> All day in shadow</label><br>
						<input type="checkbox" name="all_day" value="1" class="largerCheckbox">
					@endif
				</div>
				<div class="col-md-3">
					@if (isset($data['id']))
						<input type="checkbox" name="vehicle3" value="Boat" checked>
						<label for="vehicle3"> I have a boat</label><br><br>
					@else
						<label for="vehicle1"> All day in sun</label><br>
						<input type="checkbox" name="no_sun" value="1"  class="largerCheckbox">
					@endif
				</div>
				<div class="col-md-3">
					@if (isset($data['id']))
						<input type="checkbox" name="vehicle3" value="Boat" checked>
						<label for="vehicle3"> I have a boat</label><br><br>
					@else
						<label for="vehicle1"> In the shadow afternoon</label><br>
						<input type="checkbox" name="afternoon" value="1"  class="largerCheckbox">
					@endif
				</div>
				<div class="col-md-3">
					@if (isset($data['id']))
						<input type="checkbox" name="vehicle3" value="Boat" checked>
						<label for="vehicle3"> I have a boat</label><br><br>
					@else
						<label for="vehicle1"> In the shadow beforenoon</label><br>
						<input type="checkbox" name="beforenoon" value="1"  class="largerCheckbox">
					@endif
				</div>
			</div>
		</div>

        
		<div class="form-group">
			<div class='col-md-12'>
				<h2>Image/s:</h2>
			</div>
			<div class="col-md-8 col-md-offset-2">
				<div data-role="appendRow">
					<div class="form-inline form-row">

						<div class="col-md-5">
							<input input type="text" class="form-control mb-2 mr-sm-2 col-sm-4" id="field-name" placeholder="Enter...."> 
						</div>
						<div class="wrap-input-container col-md-4">
							<label class="custom-file-upload form-control">
								Upload Image
							</label>
							<input class="file-upload" name="image" type="file">
						</div>
						<div class="col-md-3">               
							<button class="btn btn-sm btn-danger  mb-2" data-role="remove">
								Del
							</button>
							<button class="btn btn-sm btn-primary  mb-2" data-role="add">
								More
							</button>
						</div>

					</div>
				</div>
			</div>
		</div>


		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn main-btn pull-right">Save</button>
			</div>
		</div>

	</form>
</div>

<script>
	CKEDITOR.replace('text');
</script>
<script>
    CKEDITOR.config.filebrowserBrowseUrl = "{{ asset('assets/php/browse.php') }}";
    CKEDITOR.config.filebrowserUploadUrl = "{{ asset('assets/php/upload.php') }}";
</script>








<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/> -->


<style type="text/css">/*

@import url('https://fonts.googleapis.com/css?family=Open+Sans');
font-family: 'Open Sans', sans-serif;

 */

/*@import url('https://fonts.googleapis.com/css?family=Open+Sans');*/

.custom-upload { margin-top:20px;}
section {
    padding: 60px 0;
    overflow-y: auto !important;
}

section .section-title {
    text-align: center;
    color: #007b5e;
    margin-bottom: 50px;
    text-transform: uppercase;
}
.btn {margin-right:10px;}
/*a {
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
  transition: all 0.5s;
  font-family: 'Open Sans', sans-serif;
  color: #000;
}
a:hover {
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
  transition: all 0.5s;
  color: #000;
  text-decoration: none;
}

h1,h2,h3,h4,h5{
       font-family: 'Open Sans', sans-serif;
}
p, ul,li{
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    line-height: 25px;
}*/
.form-inline .form-control.custom-file-upload {
  border: 1px solid #ccc;
  
  padding: 6px 12px;
  cursor: pointer;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  width: 100%;
}

.wrap-input-container {
  display: inline-block;
  position: relative;
  overflow: hidden;
}
.wrap-input-container input {
position: absolute;
font-size: 400px;
opacity: 0;
z-index: 1;
top: 0;
left: 0;
}

        input.largerCheckbox { 
            width: 40px; 
            height: 40px; 
        } 
    </style> 

	
<script type="text/javascript">
	// Dynamically add-on fields

$(function() {
    // Remove button click
    $(document).on(
        'click',
        '[data-role="appendRow"] > .form-inline [data-role="remove"]',
        function(e) {
            e.preventDefault();
            $(this).closest('.form-row').remove();
        }
    );
    // Add button click
    $(document).on(
        'click',
        '[data-role="appendRow"] > .form-row [data-role="add"]',
        function(e) {
            e.preventDefault();
            var container = $(this).closest('[data-role="appendRow"]');
            new_field_group = container.children().filter('.form-row:first-child').clone();
          new_field_group.find('label').html('Upload Image'); new_field_group.find('input').each(function(){
                $(this).val('');
            });
            container.append(new_field_group);
        }
    );
});


// file upload

$(document).on('change', '.file-upload', function(){
  var i = $(this).prev('label').clone();
  var file = this.files[0].name;
  $(this).prev('label').text(file);
});

</script>