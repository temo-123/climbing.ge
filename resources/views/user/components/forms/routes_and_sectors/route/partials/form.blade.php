<script src="https://code.jquery.com/jquery-1.8.3.min.js" integrity="sha256-YcbK69I5IXQftf/mYD8WY0/KmEDCv1asggHpJk1trM8=" crossorigin="anonymous"></script>

<div class="container top_menu_margin">

	@component('user.components.breadcrumb')
	    @if (isset($data['id'])) 
    		@slot('title') Route/s edit @endslot
    		@slot('parent') Home @endslot
    		@slot('active') Route/s edit @endslot
		@else 
    		@slot('title') Route/s add @endslot
    		@slot('parent') Home @endslot
    		@slot('active') Route/s add @endslot
		@endif
	@endcomponent
  
	<div class="form-group">
		<a href="{{route('routes_and_sectors')}}" class='btn btn-primary'>Back</a>
	</div>
    <!-- <div class="form-grup">
        <button id="add_buttmo">Add to collection</button>
    </div> -->
</div>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('routeAdd') }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
            @csrf

            <table style="width: 100%">
                <tbody>
                    <tr class="item">
                        <td>   
                            <div class="form-group">
                                <div class='col-xs-12'>
                                    <h2>New route:</h2>
                                </div>
                            </div>         
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-xs-6">
                                        <br/>Route style:
                                        <select id="country" name="country" class="form-control textarea"></select>
                                    </div>

                                    <div class="col-xs-6">
                                        <br/>Gred:
                                        <select name="state" id="state" class="form-control textarea"></select>
                                    </div>

                                    <script language="javascript">
                                        populateCountries("country", "state");
                                    </script>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-xs-6">
                                        <br/>Gred:
                                        <input type="height" name="height" class="form-control textarea" placeholder="Height">
                                    </div>
                                    <div class="col-xs-6">
                                        <br/>Gred:
                                        <input type="bolts" name="bolts" class="form-control textarea" placeholder="Bolts">
                                    </div>
                                </div>
                            </div> 
                        </td>
                        <td><i class="icon delete"></i></td>
                    </tr>
                            
                    <tr class="item inactive">
                        <td>
                            <div class="form-group">
                                <div class='col-xs-12'>
                                    <h2>New route:</h2>
                                </div>
                            </div>                    
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-xs-6">
                                        <br/>Route style:
                                        <select id="country" name="country" class="form-control textarea"></select>
                                    </div>

                                    <div class="col-xs-6">
                                        <br/>Gred:
                                        <select name="state" id="state" class="form-control textarea"></select>
                                    </div>

                                    <script language="javascript">
                                        populateCountries("country", "state");
                                    </script>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="col-xs-6">
                                        <br/>Gred:
                                        <input type="height" name="height" class="form-control textarea" placeholder="Height">
                                    </div>
                                    <div class="col-xs-6">
                                        <br/>Gred:
                                        <input type="bolts" name="bolts" class="form-control textarea" placeholder="Bolts">
                                    </div>
                                </div>
                            </div> 
                        </td>
                        <td><i class="icon delete"></i></td>
                    </tr>
                </tbody>
            </table>

                <div class="form-group">
                    <div class="col-xs-offset-2 col-xs-10">
                        <button type="submit" class="btn main-btn pull-right">Save</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

                





<script type="text/javascript">
    $("table")
        .on("click focus", ".item.inactive", function(e) {
            var curRow = $(".item.inactive"); 
            curRow.clone().appendTo("table tbody");
            curRow.removeClass("inactive").find("input:first").focus();
        })
        .on("click", ".icon.delete", function(e) {
            $(this).closest("tr").remove();
        });

    // $("button.add_button").on("click", function(e) {
    //     $(".item.inactive").click().find("input:first").focus();
    // });
</script>


