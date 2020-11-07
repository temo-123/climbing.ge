<template>
        <div class="tabs">
    <input type="radio" name="tabs" id="1" checked="checked">
    <label for="1" >global info</label>
    <div class="tab" >
        <form name="contact-form" method="POST" :action="this.add_url" id="myform" style="margin-top: 5%;" enctyp ="multipart/form-data">
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Publish </label>
                <div class="col-xs-8">
                    <select class="form-control" name="published"> 
                        
                        <!-- <option value="0" @if ($data['published'] == 0) selected="" @endif>Not public</option> -->
                        <!-- <option value="1" @if ($data['published'] == 1) selected="" @endif>Public</option> -->
                    
                        <option value="0">Not public</option> 
                        <option value="1">Public</option> 
                        
                    </select> 
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Publish </label>
                <div class="col-xs-8">
                    <select class="form-control" name="published"> 
                        
                        <!-- <option value="0" @if ($data['published'] == 0) selected="" @endif>Not public</option> -->
                        <!-- <option value="1" @if ($data['published'] == 1) selected="" @endif>Public</option> -->
                    
                        <option value="0">No complited</option> 
                        <option value="1">Complited</option> 
                        
                    </select> 
                </div>
            </div>


            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Map </label>
                <div class="col-xs-8">
                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                    <input type="text" name="map" value="map" class="form-control"> 
                </div>
            </div>


            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Weather </label>
                <div class="col-xs-8">
                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                    <input type="text" name="weather" value="weather" class="form-control"> 
                </div>
            </div>

            <hr>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> Start data </label>
                <div class="col-xs-4">
                    <input type="text" name="start_data_day" value="start_data_day" class="form-control"> 
                </div>
                <div class="col-xs-4">
                    <input type="text" name="start_data_month" value="start_data_month" class="form-control"> 
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> End data </label>
                <div class="col-xs-4">
                    <input type="text" name="and_data_day" value="and_data_day" class="form-control"> 
                </div>
                <div class="col-xs-4">
                    <input type="text" name="and_data_month" value="and_data_month" class="form-control"> 
                </div>
            </div>

            <hr>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> facebook / twitter </label>
                <div class="col-xs-4">
                    <input type="text" name="fb_link" value="fb_link" class="form-control"> 
                </div>
                <div class="col-xs-4">
                    <input type="text" name="twit_link" value="twit_link" class="form-control"> 
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> google / instagram </label>
                <div class="col-xs-4">
                    <input type="text" name="google_link" value="google_link" class="form-control"> 
                </div>
                <div class="col-xs-4">
                    <input type="text" name="inst_link" value="inst_link" class="form-control"> 
                </div>
            </div>
            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> website </label>
                <div class="col-xs-8">
                    <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                    <input type="text" name="web_link" value="web_link" class="form-control"> 
                </div>
            </div>

            <div class="form-group clearfix">
                <label for="name" class='col-xs-2 control-label'> image </label>
                <div class="col-xs-8">                    
                    <input type="file" name="fileToUpload" id="fileToUpload">
                </div>
            </div>

        </form>
    </div>
    <input type="radio" name="tabs" id="2">
    <label for="2" >english article</label>
    <div class="tab" >
        <locale-article-form-component></locale-article-form-component>
    </div>

    <input type="radio" name="tabs" id="3">
    <label for="3" >rusian article</label>
    <div class="tab">
        <locale-article-form-component></locale-article-form-component>
    </div>

    <input type="radio" name="tabs" id="4">
    <label for="4" >georgian article</label>
    <div class="tab">
        <locale-article-form-component :add_url="this.add_url"></locale-article-form-component>
    </div>
</div>
</template>

<script>
    export default {
        props: [
            'add_url'
        ],
        data(){
            return {
                
            }
        },
        mounted() {
            // console.log(this.add_url)
        },
        methods: {
            add_comment() {
                axios
                .post('/add_comment', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    text: this.text,
                    article_id: this.article_id
                })
                .then(Response => {
                    alert("Tenk you for your coment"),
                    this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            }
        }
    }
</script>
