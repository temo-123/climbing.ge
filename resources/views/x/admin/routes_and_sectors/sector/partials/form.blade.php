<div class="container">

	@component('admin.components.breadcrumb')
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

<div class="wrapper container-fluid">

	    @if (isset($data['id']))
	    	<form action="{{ route('sectorEdit', array('page'=>$data['id'])) }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data"> 
		@else 
			<form action="{{ route('sectorAdd') }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
		@endif
		
        <div class="form-group">
        	<div class='col-xs-12'>
        		<h2>Height And bolts:</h2>
        	</div>
        	<div class="col-md-10 col-md-offset-1">
				<div class="col-xs-6">
					@if (isset($data['id']))
	                    <input type="height" name="height" value="{{$data['height']}}" class="form-control textarea" placeholder="Height">
					@else
	                    <input type="height" name="height" class="form-control textarea" placeholder="Height">
					@endif
				</div>
				<div class="col-xs-6">
					@if (isset($data['id']))
	                    <input type="bolts" name="bolts" value="{{$data['bolts']}}" class="form-control textarea" placeholder="Bolts">
					@else
	                    <input type="bolts" name="bolts" class="form-control textarea" placeholder="Bolts">
					@endif
				</div>
			</div>
		</div>
		

		<div class="form-group">
        	<div class='col-xs-12'>
        		<h2>Height And bolts:</h2>
        	</div>
			<div class="col-xs-6 col-md-offset-3">
				<select class="form-control" name="category" > 
    				
            	@if (isset($data['id'])) 
				    <option value="No Category" @if ($data['category'] == 'No Category') selected="" @endif>Category</option>
				    
				    <option class="form-control filter-1 lead_climbing" value="Lead Climbng" @if ($data['category'] == 'Lead Climbng') selected="" @endif>Lead climbing</option>
				    <option class="form-control filter-1 top" value="Top Rope" @if ($data['category'] == 'Top Rope') selected="" @endif>Top Rope</option>
				    <option class="form-control filter-1 tred" value="Trad Climbing" @if ($data['category'] == 'Trad Climbing') selected="" @endif>Trad</option>
				    <option class="form-control filter-1 aid" value="Aid Climbing" @if ($data['category'] == 'Aid Climbing') selected="" @endif>Aid</option>
				    <option class="form-control filter-1 bolder" value="Bouldering" @if ($data['category'] == 'Bouldering') selected="" @endif>Bulder</option>
				    <option class="form-control filter-1 dry" value="Dry tooling" @if ($data['category'] == 'Dry tooling') selected="" @endif>Dry tooling</option>
				    <option class="form-control filter-1 travers" value="Traverse" @if ($data['category'] == 'Traverse') selected="" @endif>Traverse</option>
				    
				    <option class="form-control filter-1 mtp" value="Multi pitch (bolts)" @if ($data['category'] == 'Multi pitch (bolts)') selected="" @endif>Multi pitch (bolts)</option>
				    <option class="form-control filter-1 mtp" value="Multi pitch (aid)" @if ($data['category'] == 'Multi pitch (aid)') selected="" @endif>Multi pitch (aid)</option>
				    <option class="form-control filter-1 mtp" value="Multi pitch (tred)" @if ($data['category'] == 'Multi pitch (tred)') selected="" @endif>Multi pitch (tred)</option>
				    
				@else 
        			<option value="No Category">Category</option> 
        			
        			<option  class="form-control filter-1 top" value="Top Rope">Top Rope</option> 
    				<option  class="form-control filter-1 bolder" value="Bouldering">Bulder</option>
    				<option  class="form-control filter-1 tred" value="Trad Climbing">Tread</option> 
    				<option  class="form-control filter-1 aid" value="Aid Climbing">Aid</option> 
    				<option  class="form-control filter-1 dry" value="Dry tooling">Dry Tooling</option> 
				    <option  class="form-control filter-1 lead_climbing" value="Lead Climbng">Lead climbing</option>
    				<option  class="form-control filter-1 travers" value="Traverse ">Traverse </option> 
    				
    				<option  class="form-control filter-1 mtp" value="Multy pitch (bolts)">Multi pitch (bolts)</option>
    				<option  class="form-control filter-1 mtp" value="Multi pitch (aid)">Multi pitch (aid)</option>
    				<option  class="form-control filter-1 mtp" value="Multi pitch (tred)">Multi pitch (tred)</option>
				@endif
				</select>
			</div>
		</div>


        <div class="form-group">
        	<div class='col-xs-12'>
        		<h2>Height And bolts:</h2>
        	</div>
			<div class="col-xs-6 col-md-offset-3">
				@if (isset($data['id']))
                    <input type="height" name="height" value="{{$data['height']}}" class="form-control textarea" placeholder="Height">
				@else
                    <input type="height" name="height" class="form-control textarea" placeholder="Height">
				@endif
			</div>
		</div>	        

		<div class="form-group">
        	<div class='col-xs-12'>
        		<h2>Text:</h2>
        	</div>
        	<div class="col-md-10 col-md-offset-1">
				<div class="col-xs-12">
					@if (isset($data['id']))
	                    <textarea name="text" id="text" value="{{$data['height']}}" placeholder="Text Area" class="form-control textarea"></textarea>
					@else
	                    <textarea name="text" id="text" placeholder="Text Area" class="form-control textarea"></textarea>
					@endif
				</div>
			</div>
		</div>	

        
        <div class="form-group">
        	<div class='col-xs-12'>
        		<h2>Image/s:</h2>
        	</div>
        	<div class="col-md-10 col-md-offset-1">
				<div class="col-xs-8 col-md-offset-2">
				    <div data-role="appendRow">
			            <div class="form-inline form-row">
						
			              <input input type="text" class="form-control mb-2 mr-sm-2 col-sm-4" id="field-name" placeholder="Enter....">               
			              <!-- file upload start-->
			              <div class="mb-2 mr-sm-2 col-sm-5 wrap-input-container">
			                <label class="custom-file-upload form-control">
			                  Upload Image
			                </label>
			                <input class="file-upload" name="file_name" type="file">
			              </div>
			              <div class="mb-2 mr-sm-2 col-sm-3">               
							<button class="btn btn-sm btn-danger  mb-2" data-role="remove">
							 <i class="fa fa-minus"></i>
							</button>
							<button class="btn btn-sm btn-primary  mb-2" data-role="add">
							    <i class="fa fa-plus"></i>
							</button>
							</div>
				                
				        </div>  <!-- /div[data-role="dynamic-fields"] --> 	
				    </div>
				</div>
        	</div>
		</div>


		<div class="form-group">
			<div class="col-xs-offset-2 col-xs-10">
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