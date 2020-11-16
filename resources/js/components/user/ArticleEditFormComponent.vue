<template>
<div class="col_md_12">
    <div class="row">
        <div class="form-group">  
            <button type="submit" class="btn btn-primary" v-on:click="save_all()" >Save all</button>
        </div>
    </div>
    <div class="row">
        <div class="tabs">
            <input type="radio" name="tabs" id="1" checked="checked">
            <label for="1" >global info</label>
            <div class="tab" >
                <div class="form-group" v-if="this.old_data">  
                    <input type="submit" value="save global article" class="btn btn-primary" form="global_form">
                </div>
                <form name="contact-form" method="POST" id="global_form" @submit.prevent="add_article"  style="margin-top: 5%;" enctyp ="multipart/form-data">
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
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save georgian article" class="btn btn-primary" form="global_form">
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
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
                            <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
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
                            <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
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

            <input type="radio" name="tabs" id="3">
            <label for="3" >rusian article</label>
            <div class="tab">
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save rusian article" class="btn btn-primary" form="global_form">
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="add_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
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
                            <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
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
                            <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
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

            <input type="radio" name="tabs" id="4">
            <label for="4" >georgian article</label>
            <div class="tab">
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save anglish article" class="btn btn-primary" form="global_form">
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="add_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
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
                            <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
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
                            <textarea type="text"  name="info" rows="15" class="form-cotrol md-textarea form-control"></textarea>
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
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: [
            'back_url',
            'category'
        ],
        data(){
            return {
                
            }
        },
        mounted() {
            // console.log(this.catagory)
        },
        methods: {
            add_global_article() {
                axios
                .post('/articles/add/' + this.category, {
                    Publish: this.Publish,
                })
                .then(Response => {
                    // alert("Article addied"),
                    // this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            },
            add_ru_article() {
                axios
                .post('/articles/us/add/' + this.category, {
                    Publish: this.Publish,
                })
                .then(Response => {
                    // alert("Article addied"),
                    // this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            },
            add_us_article() {
                axios
                .post('/articles/ru/add/' + this.category, {
                    Publish: this.Publish,
                })
                .then(Response => {
                    // alert("Article addied"),
                    // this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            },
            add_ka_article() {
                axios
                .post('/articles/ka/add/' + this.category, {
                    Publish: this.Publish,
                })
                .then(Response => {
                    // alert("Article addied"),
                    this.comment.name = '',
                    update,
                    console.log(response)
                })
                .catch(error => console.log(error))
            },
            save_all() {
                this.add_global_article();
                this.add_us_article();
                this.add_ka_article();
                this.add_ru_article();
                // window.location.href = this.back_url;
            }
        }
        
    }
</script>
