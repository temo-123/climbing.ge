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
            <label for="1" >site image</label>
            <div class="tab" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} Site image</h2>
                        <p class="lead">Site image.</p>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="name" class='col-xs-2 control-label'> Title </label>
                    <div class="col-xs-8">
                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                        <input type="text" name="us_title" v-model="title_us" class="form-control"> 
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="name" class='col-xs-2 control-label'> email </label>
                    <div class="col-xs-8">
                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                        <input type="text" name="email" v-model="email" class="form-control"> 
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="name" class='col-xs-2 control-label'> fb_link </label>
                    <div class="col-xs-8">
                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                        <input type="text" name="fb_link" v-model="fb_link" class="form-control"> 
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="name" class='col-xs-2 control-label'> google_link </label>
                    <div class="col-xs-8">
                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                        <input type="text" name="google_link" v-model="google_link" class="form-control"> 
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="name" class='col-xs-2 control-label'> twit_link </label>
                    <div class="col-xs-8">
                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                        <input type="text" name="twit_link" v-model="twit_link" class="form-control"> 
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="name" class='col-xs-2 control-label'> inst_link </label>
                    <div class="col-xs-8">
                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                        <input type="text" name="inst_link" v-model="inst_link" class="form-control"> 
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label for="name" class='col-xs-2 control-label'> map </label>
                    <div class="col-xs-8">
                        <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                        <input type="text" name="map" v-model="map" class="form-control"> 
                    </div>
                </div>
            </div>
            <input type="radio" name="tabs" id="2">
            <label for="2" >english info</label>
            <div class="tab" >
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} Site English info</h2>
                        <p class="lead">Site English info.</p>
                    </div>
                </div>
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save georgian article" class="btn btn-primary" form="global_form">
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="us_title" v-model="title_us" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control">gggggggggg</textarea> -->
                            <ckeditor :editor="editor" v-model="short_description_us" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> other_activity_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="other_activity_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ice_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ice_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> mount_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="mount_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> outdoor_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="outdoor_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> indoor_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="indoor_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> topo_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="topo_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> what_we_do_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="what_we_do_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> shop_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="shop_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> meta_description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="meta_description" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>


                </form>
            </div>

            <input type="radio" name="tabs" id="3">
            <label for="3" >russian info</label>
            <div class="tab">
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save rusian article" class="btn btn-primary" form="global_form">
                </div>

                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} Sitr rusian info</h2>
                        <p class="lead">Sitr rusian info.</p>
                    </div>
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ru_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="value name" v-model="title_ru" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="short_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="text_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ice_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ice_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> mount_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="mount_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> outdoor_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="outdoor_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> indoor_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="indoor_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> topo_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="topo_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> what_we_do_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="what_we_do_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> shop_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="shop_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> meta_description_ru </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="meta_description_ru" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                </form>
            </div>

            <input type="radio" name="tabs" id="4">
            <label for="4" >georgian info</label>
            <div class="tab">
                <div class="form-group"  v-if="this.old_data">  
                    <input type="submit" value="save anglish article" class="btn btn-primary" form="global_form">
                </div>

                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="display-4">{{this.category}} site georgian information</h2>
                        <p class="lead">site georgian information.</p>
                    </div>
                </div>

                <form id="us_form" name="contact-form" method="POST" @submit.prevent="edit_ka_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Title </label>
                        <div class="col-xs-8">
                            <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                            <input type="text" name="value name" v-model="title_ka" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> Short description </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="short_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> text </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="text_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>


                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> ice_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="ice_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> mount_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="mount_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> outdoor_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="outdoor_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> indoor_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="indoor_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> topo_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="topo_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> what_we_do_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="what_we_do_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> shop_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="shop_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label for="name" class='col-xs-2 control-label'> meta_description_ka </label>
                        <div class="col-xs-8">
                            <ckeditor :editor="editor" v-model="meta_description_ka" :config="editorConfig"></ckeditor>
                            <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    // import Form from 'vform';

    export default {
        props: [
            'editing_info_id',
            'back_url'
        ],
        data(){
            return {
                editor: 'editor',
                editorConfig:{},

                editing_url: '/about/get_site_editing_data/',
                url: '',

                editing_data: '',

                title: "",	
                title_ru: "",	
                title_ka: "",
                text: "",
                text_ru: "",
                text_ka: "",
                short_description: "",
                short_description_ru: "",
                short_description_ka: "",
                other_activity_description: "",
                other_activity_description_ru: "",
                other_actyvity_description_ka: "",
                ice_description: "",
                ice_description_ru: "",
                ice_description_ka: "",
                mount_description: "",
                mount_description_ru: "",
                mount_description_ka: "",
                outdoor_description: "",
                outdoor_description_ru: "",
                outdoor_description_ka: "",
                indoor_description: "",
                indoor_description_ru: "",
                indoor_description_ka: "",
                topo_description: "",
                topo_description_ka: "",
                topo_description_ru: "",
                what_we_do_description: "",
                what_we_do_description_ru: "",
                what_we_do_description_ka: "",
                shop_description: "",
                shop_description_ru: "",
                shop_description_ka: "",
                meta_description: "",
                meta_description_ru: "",
                meta_description_ka: "",
                
                email: "",
                fb_link: "",
                google_link: "",
                twit_link: "",
                inst_link: "",
                map: "",
            }
        },
    
        mounted() {
            this.get_editing_data()
        },

        methods: {

            edit_site_info() {
                axios
                .post('/about/site_info_edit/' + this.editing_info_id, {
                    us_title_for_url_title: this.us_title,

                    published: this.published,
                    title: 	this.title,
                    title_ru: 	this.title_ru,
                    title_ka: this.title_ka,
                    text_ru: this.text_ru,
                    text_ka: this.text_ka,
                    text: this.text,
                    short_description: this.short_description,
                    short_description_ru: this.short_description_ru,
                    short_description_ka: this.short_description_ka,
                    other_activity_description: this.other_activity_description,
                    other_activity_description_ru: this.other_activity_description_ru,
                    other_activity_description_ka: this.other_activity_description_ka,
                    ice_description: this.ice_description,
                    ice_description_ru: this.ice_description_ru,
                    ice_description_ka: this.ice_description_ka,
                    mount_description: this.mount_description,
                    mount_description_ru: this.mount_description_ru,
                    mount_description_ka: this.mount_description_ka,
                    outdoor_description: this.outdoor_description,
                    outdoor_description_ru: this.outdoor_description_ru,
                    outdoor_description_ka: this.outdoor_description_ka,
                    indoor_description: this.indoor_description,
                    indoor_description_ru: this.indoor_description_ru,
                    indoor_description_ka: this.indoor_description_ka,
                    topo_description: this.topo_description,
                    topo_description_ka: this.topo_description_ka,
                    topo_description_ru: this.topo_description_ru,
                    what_we_do_description: this.what_we_do_description,
                    what_we_do_description_ru: this.what_we_do_description_ru,
                    what_we_do_description_ka: this.what_we_do_description_ka,
                    shop_description: this.shop_description,
                    shop_description_ru: this.shop_description_ru,
                    shop_description_ka: this.shop_description_ka,
                    meta_description: this.meta_description,
                    meta_description_ru: this.meta_description_ru,
                    meta_description_ka: this.meta_description_ka,
                    

                    email: this.email,
                    fb_link: this.fb_link,
                    google_link: this.google_link,
                    twit_link: this.twit_link,
                    inst_link: this.inst_link,
                    map: this.map,
                })
                .then(Response => {
                    console.log(response)
                })
                .catch(error => console.log(error))
            },

            get_editing_data() {
                this.url = this.editing_url + this.editing_info_id

                axios
                .get(this.url)
                .then(response => {
                    this.editing_data = response.data     

                    // send data in editing form value
                    this.title = 	this.editing_data.site_info['title']
                    this.title_ru = 	this.editing_data.site_info['title_ru']
                    this.title_ka = this.editing_data.site_info['title_ka']
                    this.text_ru = this.editing_data.site_info['text_ru']
                    this.text_ka = this.editing_data.site_info['text_ka']
                    this.text = this.editing_data.site_info['text']
                    this.short_description = this.editing_data.site_info['short_description']
                    this.short_description_ru = this.editing_data.site_info['short_description_ru']
                    this.short_description_ka = this.editing_data.site_info['short_description_ka']
                    this.other_activity_description = this.editing_data.site_info['other_activity_description']
                    this.other_activity_description_ru = this.editing_data.site_info['other_activity_description_ru']
                    this.other_activity_description_ka = this.editing_data.site_info['other_activity_description_ka']
                    this.ice_description = this.editing_data.site_info['ice_description']
                    this.ice_description_ru = this.editing_data.site_info['ice_description_ru']
                    this.ice_description_ka = this.editing_data.site_info['ice_description_ka']
                    this.mount_description = this.editing_data.site_info['mount_description']
                    this.mount_description_ru = this.editing_data.site_info['mount_description_ru']
                    this.mount_description_ka = this.editing_data.site_info['mount_description_ka']
                    this.outdoor_description = this.editing_data.site_info['outdoor_description']
                    this.outdoor_description_ru = this.editing_data.site_info['outdoor_description_ru']
                    this.outdoor_description_ka = this.editing_data.site_info['outdoor_description_ka']
                    this.indoor_description = this.editing_data.site_info['indoor_description']
                    this.indoor_description_ru = this.editing_data.site_info['indoor_description_ru']
                    this.indoor_description_ka = this.editing_data.site_info['indoor_description_ka']
                    this.topo_description = this.editing_data.site_info['topo_description']
                    this.topo_description_ka = this.editing_data.site_info['topo_description_ka']
                    this.topo_description_ru = this.editing_data.site_info['topo_description_ru']
                    this.what_we_do_description = this.editing_data.site_info['what_we_do_description']
                    this.what_we_do_description_ru = this.editing_data.site_info['what_we_do_description_ru']
                    this.what_we_do_description_ka = this.editing_data.site_info['what_we_do_description_ka']
                    this.shop_description = this.editing_data.site_info['shop_description']
                    this.shop_description_ru = this.editing_data.site_info['shop_description_ru']
                    this.shop_description_ka	 = this.editing_data.site_info['shop_description_ka']
                    this.meta_description	 = this.editing_data.site_info['meta_description']
                    this.meta_description_ru	 = this.editing_data.site_info['meta_description_ru']
                    this.meta_description_ka	 = this.editing_data.site_info['meta_description_ka']

                    this.email = this.editing_data.site_info['email']
                    this.fb_link = this.editing_data.site_info['fb_link']
                    this.google_link = this.editing_data.site_info['google_link']
                    this.twit_link = this.editing_data.site_info['twit_link']
                    this.inst_link = this.editing_data.site_info['inst_link']
                    this.map = this.editing_data.site_info['map']
                })
                .catch(
                    error => console.log(error)
                );
            },

            save_all() {
                this.edit_site_info();

                window.location.href = this.back_url;
            }
        }
    }
</script>
