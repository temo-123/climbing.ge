<template>
    <form id="us_form" name="contact-form" class="width_100"  method="POST" @submit.prevent="edit_article" style="margin-top: 5%;" enctyp ="multipart/form-data">
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Title </label>
            <div class="col-xs-8">
                <!-- <input type="text" name="value name" value="old data" class="form-control"> -->
                <input type="text" name="value name" v-model="locale_data.guid_title" class="form-control"> 
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Short description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.guid_short_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="short_description" name="short_description" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>

        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> text </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.guid_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>


        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> films_title </label>
            <div class="col-xs-8">
                <!-- <ckeditor v-model="locale_data.films_title" :config="this.$editorConfig"></ckeditor> -->
                <input type="text" name="value name" v-model="locale_data.films_title" class="form-control"> 
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> films_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.films_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> films_short_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.films_short_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> forum_title </label>
            <div class="col-xs-8">
                <!-- <ckeditor v-model="locale_data.forum_title" :config="this.$editorConfig"></ckeditor> -->
                <input type="text" name="value name" v-model="locale_data.forum_title" class="form-control"> 
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> forum_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.forum_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> forum_short_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.forum_short_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> shop_title </label>
            <div class="col-xs-8">
                <!-- <ckeditor v-model="locale_data.shop_title" :config="this.$editorConfig"></ckeditor> -->
                <input type="text" name="value name" v-model="locale_data.shop_title" class="form-control"> 
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> other_activity_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.other_activity_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> event_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.event_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> tech_tips_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.tech_tips_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> news_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.news_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> index_gallery_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.index_gallery_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> products_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.products_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>


        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> ice_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.ice_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> mount_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.mount_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> outdoor_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.outdoor_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> indoor_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.indoor_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> topo_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.topo_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> what_we_do_description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.what_we_do_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Shop short description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.shop_short_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Shop description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.shop_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Service description </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.services_description" :config="this.$editorConfig"></ckeditor>
                <!-- <textarea type="text" id="txt" name="txt" rows="15" class="form-cotrol md-textarea form-control"></textarea> -->
            </div>
        </div>
        <div class="form-group clearfix">
            <label for="name" class='col-xs-2 control-label'> Terms of use </label>
            <div class="col-xs-8">
                <ckeditor v-model="locale_data.terms_of_use" :config="this.$editorConfig"></ckeditor>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        props: [
            'locale_data_prop',
        ],
        data(){
            return {
                // editor: 'editor',
                editorConfig:{},
                
                locale_data: [

                ],
            }
        },

        watch: {
            locale_data_prop: function(newVal, oldVal) { 
                // console.log('Prop changed: ', newVal, ' | was: ', oldVal)
                this.locale_data = this.locale_data_prop
            },
        },

        mounted() {
            // console.log(this.locale_data_prop);
            // this.locale_data = this.locale_data_prop
            // this.$emit('locale_form_data', this.locale_data)
        },

    }
</script>

<style>

</style>