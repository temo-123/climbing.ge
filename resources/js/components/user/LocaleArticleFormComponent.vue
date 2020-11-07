<template>
    <div>
        
        <form id="contact-form form-horizontal" name="contact-form" method="POST" :action=this.category style="margin-top: 5%;" enctyp ="multipart/form-data">
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Title </label>
                <div class="col-xs-8">
                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                    <input type="text" name="value name" class="form-control"> 
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Short description </label>
                <div class="col-xs-8">
                    <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> text </label>
                <div class="col-xs-8">
                    <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Routes description </label>
                <div class="col-xs-8">
                    <textarea type="text" id="route" name="route" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> How to get hear </label>
                <div class="col-xs-8">
                    <textarea type="text" id="how_get" name="how_get" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Best time for climbing </label>
                <div class="col-xs-8">
                    <textarea type="text" id="best_time" name="best_time" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> what you need </label>
                <div class="col-xs-8">
                    <textarea type="text" id="what_need" name="what_need" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Info / contact </label>
                <div class="col-xs-8">
                    <textarea type="text" id="info" name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Working time </label>
                <div class="col-xs-8">
                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                    <input type="text" name="time" value="time" class="form-control"> 
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Info / contact </label>
                <div class="col-xs-8">
                    <textarea type="text" id="info" name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Price description </label>
                <div class="col-xs-8">
                    <textarea type="text" id="prixe_text" name="prixe_text" rows="15" class="form-cotrol md-textarea form-control"></textarea>
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Minimal price </label>
                <div class="col-xs-8">
                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                    <input type="text" name="price_from" value="price_from" class="form-control"> 
                </div>
            </div>

            <hr>


            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Meta keyword </label>
                <div class="col-xs-8">
                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                    <input type="text" name="meta_keyword" value="meta_keyword" class="form-control"> 
                </div>
            </div>

        </form>
    </div>
</template>

<script>
    export default {
        props: [
            "add_url"
        ],
        data(){
            return {
                
            }
        },
        mounted() {
            console.log(this.add_url);
        },
    }
</script>

<style scoped>

/**
 * Tabs
 */
.tabs {
	display: flex;
	flex-wrap: wrap; /* make sure it wraps */
}
.tabs label {
/* .tabs  { */
	order: 1; /* Put the labels first */
	display: block;
	padding: 1rem 2rem;
	margin-right: 0.2rem;
	cursor: pointer;
  background: #ccced0;
  font-weight: bold;
  transition: background ease 0.2s;
}
.tabs .tab {
  order: 99; /* Put the tabs last */
  flex-grow: 1;
	width: 100%;
	display: none;
  padding: 1rem;
  background: #fff;
}
.tabs input[type="radio"] {
	display: none;
}
.tabs input[type="radio"]:checked + label {
	background: #fff;
}
.tabs input[type="radio"]:checked + label + .tab {
	display: block;
}

@media (max-width: 45em) {
  .tabs .tab,
  .tabs label {
    order: initial;
  }
  .tabs label {
    width: 100%;
    margin-right: 0;
    margin-top: 0.2rem;
  }
}


</style>