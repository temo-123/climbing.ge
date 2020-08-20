<script type="text/javascript">
// Countries
var country_arr = new Array("Lead clombing", "moulderig");

// States
var s_a = new Array();
s_a[0] = "Gread";
s_a[1] = "4a|4b|4c|5a";
s_a[2] = "V1|V2|V3|V4";


function populateStates(countryElementId, stateElementId) {

    var selectedCountryIndex = document.getElementById(countryElementId).selectedIndex;

    var stateElement = document.getElementById(stateElementId);

    stateElement.length = 0; // Fixed by Julian Woods
    stateElement.options[0] = new Option('Select State', '');
    stateElement.selectedIndex = 0;

    var state_arr = s_a[selectedCountryIndex].split("|");

    for (var i = 0; i < state_arr.length; i++) {
        stateElement.options[stateElement.length] = new Option(state_arr[i], state_arr[i]);
    }
}

function populateCountries(countryElementId, stateElementId) {
    // given the id of the <select> tag as function argument, it inserts <option> tags
    var countryElement = document.getElementById(countryElementId);
    countryElement.length = 0;
    countryElement.options[0] = new Option('Select Country', '-1');
    countryElement.selectedIndex = 0;
    for (var i = 0; i < country_arr.length; i++) {
        countryElement.options[countryElement.length] = new Option(country_arr[i], country_arr[i]);
    }

    // Assigned all countries. Now assign event listener for the states.

    if (stateElementId) {
        countryElement.onchange = function () {
            populateStates(countryElementId, stateElementId);
        };
    }
}
    </script>




<style type="text/css">
    html, body {
    padding: 10px 15px;
}

.icon {
    display: inline-block;
    width: 16px;
    height: 16px;
    vertical-align: middle;
    font-style: normal;
    background: #333;
    border-radius: 2px;
    padding: 2px;
    cursor: pointer;
    text-align: center;
    margin-top: -4px;
    font-family: Arial, sans-serif;
    font-weight: normal;
    font-size: 16px;
    line-height: 16px;
    color: #fff;
}

.icon:hover {
    background: #666;
}

.icon.delete::after {
    content: "✖";
}

.item.inactive {
    opacity: .5;
}

.item.inactive .icon {
    display: none;
}

/*td {
    padding: 5px 0;
}

td + td {
    padding-left: 10px;
}*/

</style>

<script src="https://code.jquery.com/jquery-1.8.3.min.js" integrity="sha256-YcbK69I5IXQftf/mYD8WY0/KmEDCv1asggHpJk1trM8=" crossorigin="anonymous"></script>

<button>Add to collection</button>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('routeAdd') }}" id="js_form" class="contact-form" method="POST" enctype="multipart/form-data">
                @csrf
            <table>
                <tbody>
                    <tr class="item">
                        <td>
                            <div class="form-group">
                                <div class='col-xs-12'>
                                    <h2>New route:</h2>
                                </div>
                            </div>                    
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-1">
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
                                <div class="col-md-10 col-md-offset-1">
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
                                <div class="col-md-10 col-md-offset-1">
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
                                <div class="col-md-10 col-md-offset-1">
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
                        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <button type="submit" class="btn main-btn pull-right">Save</button>
            </div>
        </div>
            </table>
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

$("button").on("click", function(e) {
    $(".item.inactive").click().find("input:first").focus();
});
</script>


